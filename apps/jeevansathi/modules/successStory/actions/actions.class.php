<?php

/**
 * class successStoryActions
 *
 *
 * @package    jeevansathi
 * @subpackage successStory
 * @author     Hemant Agrawal
 * @version    1.0
 *
 */
class successStoryActions extends sfActions
{
    
    /**
     * Executes pre  action
     *
     * @param sfRequest $request A request object
     */
    
    public function preExecute() {
        $this->lableArr = array(
            "COUNTRY" => "COUNTRY",
            "CITY" => "CITY",
            "MTONGUE" => "community_small",
            "RELIGION" => "religion",
            "CASTE" => "caste_small",
            "OCCUPATION" => "occupation",
            "STATE" => "CITY"
        );
        $this->jprofileColumns = "PROFILEID,USERNAME,AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,HAVEPHOTO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,GENDER,SUBCASTE,GOTHRA,NAKSHATRA,RELIGION";
        $this->pageName = "successStory";
    }
    
    /**
     *executes action for success stories and assign smarty variables
     *@access public
     *uses FetchStory()
     *@param sfRequest $request A request object
     *@return
     *
     */
    
    public function executeStory(sfWebRequest $request) {
        
        //$this->forward("successStory","jspcStory");
        $this->loginData = $request->getAttribute("profileid");
        $year = $request->getParameter('year');
        $page = $request->getParameter('page');
        $this->setMetaTags($request);
        if (!$year || $year < 2005) $year = date("Y");
        
        $this->year = $year;
        
        $fetchStoryObj = new FetchStory();
        
        $parentType = $request->getParameter('parenttype');
        $mappedType = $request->getParameter('mappedtype');
        $parentValue = $request->getParameter('parentvalue');
        $mappedValue = $request->getParameter('mappedvalue');
        
        if ($parentType == 'STATE' || $parentType == 'CITY_RES') $parentType = 'CITY';
        if ($mappedType == 'STATE' || $mappedType == 'CITY_RES') $mappedType = 'CITY';
        
        $fetchStoryObj->setParentType($parentType);
        $fetchStoryObj->setParentValue($parentValue);
        $fetchStoryObj->setMappedType($mappedType);
        $fetchStoryObj->setMappedValue($mappedValue);
        
        $fetchStoryObj->setYear($year);
        $seo = $fetchStoryObj->getParentType();
        if (isset($seo)) $fromSeo = 'Y';
        else $fromSeo = 'N';
        
        $fetchStoryObj->setFromSEO($fromSeo);
        $this->fetchStoryObj = $fetchStoryObj;
        
        $storyArr = $fetchStoryObj->getSuccessStories($fetchStoryObj);
        
        $story = $storyArr['withoutphoto'];
        $storyP = $storyArr['withphoto'];
        
        $seo = $storyArr['seo'];
        $storyYear = $storyArr['year'];
        $countP = count($storyP);
        
        $limit = FetchStory::STORY_LIMIT;
        if (!isset($page)) {
            $page = 1;
        }
        $start = ($page - 1) * $limit;
        if ($storyP) {
            $this->storyPToShow = @array_slice($storyP, $start, $limit);
            $countPToShow = count($this->storyPToShow);
        }
        
        if (isset($countPToShow) && $countPToShow <= $limit) {
            if ($countPToShow != 0) {
                $start = 0;
                $last = $limit - $countPToShow;
            } 
            else {
                $start = ($page - $countP / $limit - 1) * $limit;
                $last = $limit;
            }
        } 
        else {
            $start = 0;
            $last = $limit;
        }
        
        if ($story) {
            $this->storyToShow = @array_slice($story, $start, $last);
        }
        
        $totalStory = count($storyP) + count($story);
        $noOfPages = $totalStory / $limit;
        for ($i = 0; $i <= $noOfPages; $i++) $totalPages[$i] = $i + 1;
        $this->totalPages = $totalPages;
        
        if ($storyYear) {
            $this->storyYear = $storyYear;
        } 
        else {
            $this->parentValue = FieldMap::getFieldLabel(strtolower($this->lableArr[$fetchStoryObj->getParentType() ]) , $fetchStoryObj->getParentValue());
            $this->mappedValue = FieldMap::getFieldLabel(strtolower($this->lableArr[$fetchStoryObj->getmappedType() ]) , $fetchStoryObj->getMappedValue());
        }
        
        $this->prev = $page - 1;
        if ($this->prev < 1) $this->prev = '';
        
        $this->next = $page + 1;
        if ($this->next > $noOfPages + 1) $this->next = '';
        
        $this->page = $page;
        
        $this->displaySuccessStory();
        $this->referer = $request->getReferer();
        $this->setTemplate('jspcStory');
    }
    
    /**
     *executes action for old success stories (before 2006) and assign smarty variables
     *@access public
     *uses FetchStory()
     *@param sfRequest $request A request object
     *@return
     *
     */
    public function executeOldstory(sfWebRequest $request) {
        $this->loginData = $request->getAttribute("profileid");
        $year = $request->getParameter('year');
        $page = $request->getParameter('page');
        if (!$year) $year = date("Y");
        
        $this->year = $year;
        $this->totalPages = 1;
        $fetchStoryObj = new FetchStory();
        $this->fetchStoryObj = $fetchStoryObj;
        $this->displaySuccessStory();
        $this->setTemplate('story');
    }
    
    /**
     *executes action for complete success story and assign smarty variables
     *@access public
     *@param sfRequest $request A request object
     *@return
     *
     */
    
    public function executeCompletestory(sfWebRequest $request) {
        $sid = $request->getParameter('sid');
        $this->year = $request->getParameter('year');
        $nextPage = $request->getParameter('next_page');
        $prevPage = $request->getParameter('prev_page');
        $source = $request->getParameter('source');
        $requestType = $request->getParameter('requestType');
        $page = $_SERVER['PHP_SELF'];
        JsCommon::saveHits($source, $page);
        
        $csObj = new CompleteStory();
        $sidArr = $csObj->getNextSuccessStory($this->year);
        $nISObj = new newjs_INDIVIDUAL_STORIES();
        $this->totalStoryCount = $nISObj->getStoryCountForYear($this->year);
        if ($nextPage == 1) {
            $i = array_search($sid, $sidArr);
            $i++;
            if (isset($sidArr[$i])) $sid = $sidArr[$i];
        } 
        else if ($prevPage == 1) {
            $i = array_search($sid, $sidArr);
            $i--;
            if (isset($sidArr[$i])) $sid = $sidArr[$i];
        }
        
        if (is_array($sidArr)) {
            if ($sid == $sidArr[0]) {
                $this->first = 1;
            }
            if ($sid == end($sidArr)) {
                $this->last = 1;
            }
        }
        
        $this->sid = $sid;
        $this->currentPosition = array_search($sid, $sidArr);
        $this->currentPosition+= 1;
        $detailStoryArr = $csObj->getCompleteSuccessStory($this->sid);
        
        if (is_array($detailStoryArr)) {
            $this->name1 = $detailStoryArr[NAME1];
            $this->name2 = $detailStoryArr[NAME2];
            $this->combinedName = $detailStoryArr[NAME1] . " ~ " . $detailStoryArr[NAME2];
            $this->heading = $detailStoryArr[HEADING];
            $this->story = htmlentities($detailStoryArr[STORY], ENT_QUOTES);
            if(empty($detailStoryArr[SQUARE_PIC_URL])){
            	$this->pic = $detailStoryArr[MAIN_PIC_URL];
            } else {
            	$this->pic = $detailStoryArr[SQUARE_PIC_URL];
            }
            if(empty($this->pic)){
            	$this->pic = '/images/jspc/success_story/successCouple.png';
            }
            if (!$this->year) $this->year = $detailStoryArr[YEAR];
        }
        
        $this->displaySuccessStory();
        
        if ($requestType == 'ajax') {
            $output['name1'] = $this->name1;
            $output['name2'] = $this->name2;
            $output['combinedName'] = $this->combinedName;
            $output['sid'] = $this->sid;
            $output['heading'] = $this->heading;
            $output['story'] = $this->story;
            $output['pic'] = PictureFunctions::getCloudOrApplicationCompleteUrl($this->pic);
            $output['year'] = $this->year;
            $output['totalStoryCount'] = $this->totalStoryCount;
            $output['currentPosition'] = $this->currentPosition;
            $output['showYear'] = $this->showYear;
            print json_encode($output);
            return sfView::NONE;
            die();
        } 
        else {
            $this->setTemplate('jspcCompletestory');
        }
    }
    
    /**
     *executes action for more success stories and assign smarty variables
     *@access public
     *@param sfRequest $request A request object
     *@return
     *
     */
    public function executeMorestory(sfWebRequest $request) {
        $limit = MoreSuccessStory::STORY_LIMIT;
        
        $page = $request->getParameter('page');
        $this->page = $page;
        if (!$this->page) $this->page = 0;
        $start = ($this->page - 1) * $limit;
        
        $moreStoryObj = new MoreSuccessStory();
        $moreStoryArr = $moreStoryObj->getMoreSuccessStory();
        $moreStoryShow = @array_slice($moreStoryArr, $start, $limit);
        $this->countNoStory = count($moreStoryArr);
        
        $noOfPages = floor($this->countNoStory / $limit);
        
        $pid["PROFILEID"] = implode(",", $moreStoryShow);
        $multipleProfileObj = new ProfileArray();
        $profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pid, '', '', $this->jprofileColumns, "JPROFILE", "");
        
        $storyArr = $this->getProfileDetails($profileDetails);
        if (is_array($storyArr)) {
            foreach ($storyArr as $key => $val) {
                $moreStory[] = $val;
            }
        }
        
        $this->moreStory = $moreStory;
        unset($moreStory);
        
        for ($i = 0; $i < $noOfPages; $i++) $totalPages[$i] = $i + 1;
        $this->totalPages = $totalPages;
        
        $this->prev = $page - 1;
        if ($this->prev < 1) $this->prev = '';
        
        $this->next = $page + 1;
        if ($this->next > $noOfPages + 1) $this->next = '';
        
        $this->displaySuccessStory();
        $this->page = $page;
        $this->a = array(
            'a1',
            'a2',
            'a3',
            'a4',
            'a5',
            'a6',
            'a7',
            'a8',
            'a9',
            'a10',
            'a11',
            'a12',
            'a13',
            'a14',
            'a15',
            'a16',
            'a17',
            'a18',
            'a19',
            'a20',
            'a21',
            'a22',
            'a23',
            'a24'
        );
        $this->b = array(
            'b1',
            'b2',
            'b3',
            'b4',
            'b5',
            'b6',
            'b7',
            'b8',
            'b9',
            'b10',
            'b11',
            'b12',
            'b13',
            'b14',
            'b15',
            'b16',
            'b17',
            'b18',
            'b19',
            'b20',
            'b21',
            'b22',
            'b23',
            'b24'
        );
        $this->boxheight = array(
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'tewelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen',
            'twenty',
            'twentyone',
            'twentytwo',
            'twentythree',
            'twentyfour'
        );
    }
    
    /**
     *get profile information of stries to show
     *
     *@access private
     *@param array $profileDetails
     *@uses PictureArray
     *CommonStory::getPhotoChecksum
     *@return array
     *
     */
    
    private function getProfileDetails($profileDetails) {
        if (is_array($profileDetails)) {
            $picObj = new PictureArray($profileDetails);
            $picture = $picObj->getProfilePhoto();
            unset($picObj);
            foreach ($profileDetails as $key => $profileObj) {
                $profileid = $profileObj->getPROFILEID();
                $username = $profileObj->getUSERNAME();
                $city = $profileObj->getDecoratedCity();
                if (!$city) $city = $profileObj->getDecoratedCountry();
                
                $nakshatra = $profileObj->getNakshatra();
                $occupation = $profileObj->getDecoratedOccupation();
                $education = $profileObj->getDecoratedEducation();
                $income = FieldMap::getFieldLabel("income_map", $profileObj->getINCOME());
                $caste = str_replace("-", "", FieldMap::getFieldLabel("caste_small", $profileObj->getCaste()));
                $religion = $profileObj->getDecoratedReligion();
                $mtongue = FieldMap::getFieldLabel("community_small", $profileObj->getMTONGUE());
                $height = $profileObj->getDecoratedHeight();
                $gothra = $profileObj->getGothra();
                $subcaste = $profileObj->getSubcaste();
                $age = $profileObj->getAge();
                $havephoto = $profileObj->getHavePhoto();
                $privacy = $profileObj->getPrivacy();
                $activated = $profileObj->getActivated();
                
                if (array_key_exists($profileid, $picture) && $picture[$profileid] != "") {
                    $picObj = $picture[$profileid];
                    if (isset($picObj)) {
                        $thumbnail = $picObj->getThumbailUrl();
                        $profilepic = $picObj->getProfilePicUrl();
                    }
                }
                
                if ($havephoto == 'Y') {
                    $photoT = $thumbnail;
                    $photoP = $profilepic;
                } 
                else {
                    $photoP = "";
                    $photoT = "";
                }
                
                $photoChecksum = CommonStory::getPhotoChecksum($profileid);
                
                if ($age) $info = "$age, ";
                if ($height) $info.= "$height, ";
                if ($religion && $religion != 'Other') $info.= "$religion, ";
                if ($caste) $info.= "$caste, ";
                if ($subcaste) $info.= "($subcaste), ";
                if ($gothra && $gothra != "i don't know") $info.= "$gothra, ";
                if ($nakshatra && $nakshatra != "i don't know" && $nakshatra != "Don't Know") $info.= "$nakshatra, ";
                if ($mtongue) $info.= "$mtongue, ";
                if ($education) $info.= "$education, ";
                if ($occupation) $info.= "$occupation, ";
                if ($income) $info.= "$income, ";
                if ($city) $info.= "$city";
                
                $info = htmlspecialchars_decode($info, ENT_QUOTES);
                if ($activated == 'D') {
                    $resultArr[$profileid] = array(
                        "USERNAME" => $username,
                        "INFO" => $info,
                        "PHOTO_P" => $photoP,
                        "PHOTO_T" => $photoT,
                        "PHOTO_CHECKSUM" => $photoChecksum
                    );
                }
            }
            return $resultArr;
        }
        return NULL;
    }
    
    private function displaySuccessStory() {
        $currentYear = date("Y");
        $showYear[] = $currentYear;
        $i = 2006;
        while ($i != $currentYear) {
            $showYear[] = --$currentYear;
        }
        while ($currentYear > 2004) {
            $hideYear[] = --$currentYear;
        }
        $this->showYear = $showYear;
        $this->hideYear = $hideYear;
        
        $totalStoryArr = CommonStory::getTotalStoriesCount();
        $this->totalStories = number_format($totalStoryArr[CNT]);
        
        $rightPanelStory = IndividualStories::showSuccessPoolStory();
        $this->rightPanelStory = $rightPanelStory;
    }
    
    public function executeLogin(sfWebRequest $request) {
        $result = '';
        $username = stripslashes($request->getParameter("username"));
        $password = stripslashes($request->getParameter("password"));
        if (!$username || !$password) $result = 'N';
        
        if ($result != 'N') {
            $find = '@';
            $email = stripos($username, $find);
            if ($email) {
                $jprofile = new JPROFILE();
                $result = $jprofile->get($username, "EMAIL", "USERNAME,PROFILEID,PASSWORD");
            } 
            else {
                $jprofile = new JPROFILE();
                $result = $jprofile->get($username, "USERNAME", "USERNAME,PROFILEID,PASSWORD");
            }
            
            if ($result) {
                $username = $result[USERNAME];
                $sObj = new NEWJS_SUCCESS_STORIES;
                $cnt1 = $sObj->getCount($username);
                $sObj = new NEWJS_SS_SPOUSE;
                $cnt = $sObj->getCount($username);
                if ($cnt > 0 || $cnt1 != 0) $result = "N";
                else if (PasswordHashFunctions::validatePassword($password, $result['PASSWORD'])) {
                    setcookie("SuccessUser", bin2hex(Encrypted::encrypt(date("Y-m-d") , $result[PROFILEID], 1)) , 0, "/", "");
                    
                    //setcookie("IS_NRI",1,0,"/",$this->domain);
                    $result = "Y";
                } 
                else $result = "N";
            } 
            else $result = "N";
        }
        
        $szToUrl = sfConfig::get('app_site_url');
        $ajax_result = $result;
        
        //For HTTPS Return JS Code
        $js_function = " <script>	var message = \"\";
		if(window.addEventListener)	
			message ={\"body\":\"$ajax_result\"};
		else
			message = \"$ajax_result\";

		if (typeof parent.postMessage != \"undefined\") {
            parent.postMessage(message, \"$szToUrl\");
        } else {
            window.name = message; //FOR IE7/IE6
            window.location.href = '$szToUrl';
        }
		</script> ";
        die($js_function);
    }

    public function FetchProfile($request) {
        
       
        if($this->fromMailer!=true){
            $this->loginData = $data = $request->getAttribute("loginData");
            $this->profileid = $this->loginData["PROFILEID"];
            $this->profileChecksum = $this->loginData['CHECKSUM'];
            if (!$this->profileid)
                $this->profileid = Encrypted::decrypt(date("Y-m-d") , Encrypted::hex2bin_ra($_COOKIE[SuccessUser]) , 1);
        }
        else {
            $this->mailerid = $request->getParameter("mailid");
            $successStoryMailLog = new incentive_SUCCESS_STORY_EMAIL_LOG();
            $this->profileid = $successStoryMailLog->getLogEntry($this->mailerid);
        }
    }

    public function executeSubmitlayer(sfWebRequest $request) {
        $this->FetchProfile($request);
        $jprofile = new JPROFILE('newjs_slave');
        $loggedInObj = LoggedInProfile::getInstance();
        $spouse_name = trim($request->getParameter(spouse_name));
        $spouse_id = trim($request->getParameter(spouse_id));
        $spouse_email = trim($request->getParameter(spouse_email));
        $offerConsent=$request->getParameter('offerConsent');
        if (empty($_FILES["wedding_photo"][name])){
        	$error = "photo";
        } else {
        	if (CommonUtility::UploadImageCheck("wedding_photo")) {
            	$error = "photo";
        	}
        }
        
        // Get Spouse Details
        $rowJproSpouse = $jprofile->get($request->getParameter(spouse_id) , "USERNAME", "USERNAME,EMAIL,GENDER,ACTIVATED,CONTACT,SUBSCRIPTION,PROFILEID");
        $rowd = $rowJproSpouse;
        $row = $jprofile->get($this->profileid, "PROFILEID", "USERNAME,EMAIL,GENDER,ACTIVATED,CONTACT,SUBSCRIPTION,PROFILEID");

        if (!$error) if (CommonUtility::checkValidEmail($request->getParameter(spouse_email))) {
            if (!$rowJproSpouse) $error = "email_invalid";
			$email = $request->getParameter(email);
			$spouseEmail = $request->getParameter(spouse_email);
            if($email == $spouseEmail){
            	$error = 'email_same';
            }
        } 
        else $error = "email_invalid";
        
        if (!$error) {
            if ($request->getParameter(spouse_id)) {
                if ($rowJproSpouse) {
                	$userGender = $loggedInObj->getGENDER();
                    if ($userGender == $rowJproSpouse[GENDER]) $error = "same_gender";
                } 
                else $error = "user_invalid";
            } 
            else $error = "user_invalid";
        }
        
        if (!$error) {
            $dbObj = new BILLING_PURCHASES('newjs_slave');
            if ($this->profileid && $rowJproSpouse[PROFILEID]) {
                if (!$dbObj->getPaidStatus($this->profileid) && !$dbObj->getPaidStatus($rowJproSpouse[PROFILEID])) $error = "not_compatible";
            } 
            else $error = "not_compatible";
        }

        if (!$error) {
            $w_month = $request->getParameter(w_month);
            $w_day = $request->getParameter(w_day);
            $w_year = $request->getParameter(w_year);
            if ($w_month && $w_day && $w_year) {
            	$enteredTime = mktime(0,0,0,$w_month,$w_day,$w_year);
                if (!checkdate($w_month, $w_day, $w_year) || strtotime(date("Y-m-d H:i:s")) < $enteredTime) {
                	$error = "invalid_date";
                }
            }
        }
        
        if ($error) {
            $this->MSG = $error;
        } else {
            $this->MSG = 'verified';
            $this->InsertIntoSuccessStory($request, $row, $rowd);
            //// tracking of offer consent  added by Palash Chordia
            if($offerConsent=='Y')
                (new NEWJS_OFFER_CONSENT())->insertConsent($this->profileid);
            ////////////////  
            if($this->fromMailer!=true){
	        $this->DeleteProfile($row);
	        $this->DeleteProfile($rowd);
            }else{
                
            }
                   
        }

        echo $this->MSG;
        die;
    }

    public function DeleteProfile($row) {
        $reason = 1;
        $specify_reason = "Success Story Submitted";
        
        $dbObj = new NEWJS_PROFILE_DEL_REASON;
        $dbObj->Replace($row[USERNAME], $reason, $specify_reason, $row[PROFILEID]);
        
        $dbObj = new JPROFILE;
        $dbObj->Deactive($row[PROFILEID]);
        
        $dbObj = new JSADMIN_MARK_DELETE;
        $dbObj->Update($row[PROFILEID]);
        
        $dbObj = new JSADMIN_OFFLINE_BILLING;
        $data = $dbObj->fetch($row[PROFILEID]);
        
        if ($data) {
            $entry_date = $data['ENTRY_DATE'];
            $bid = $data['BILLID'];
            $dbObj->Update($row[PROFILEID], $entry_date, $bid);
        }
        
        if (!$row[SUBSCRIPTION]) {
            
            if (!CommonFunction::isOfflineMember($row[SUBSCRIPTION])) {
                $dbObj = new ASSISTED_PRODUCT_AP_PROFILE_INFO;
                $dbObj->Delete($row[PROFILEID]);
            }
            
            $subArray = explode(",", $row["SUBSCRIPTION"]);
            if (!in_array("L", $subArray)) {
                $dbObj = new ASSISTED_PRODUCT_AP_MISSED_SERVICE_LOG;
                $dbObj->Update($row[PROFILEID]);
                
                $dbObj = new ASSISTED_PRODUCT_AP_SERVICE_TABLE;
                $dbObj->Delete($row[PROFILEID]);
            }
        }
        $dbObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY;
        $dbObj->Delete($row[PROFILEID]);
        $dbObj->Update($row[PROFILEID]);
        
        if ($row['ACTIVATED'] != 'D') {
            $path = sfConfig::get("sf_web_dir") . "/profile/deleteprofile_bg.php " . $row[PROFILEID] . " > /dev/null &";
            $cmd = "/usr/local/php/bin/php -q " . $path;
	    $cmd = preg_replace('/[^A-Za-z0-9\. -_]/', '', $cmd);
            passthru($cmd);
        }
    }

    public function InsertIntoSuccessStory($request, $user, $spouse) {
        $spouse_name = htmlspecialchars(stripslashes($request->getParameter(spouse_name)) , ENT_QUOTES);
        $contact_address = htmlspecialchars(stripslashes($request->getParameter(contact_address)) , ENT_QUOTES);
        $ss_story = htmlspecialchars(stripslashes($request->getParameter(ss_story)) , ENT_QUOTES);
        $username = $request->getParameter(username);
        if ($user[GENDER] == "F") {
            $username_w = $request->getParameter(username);
            
            $name_w = $request->getParameter(spouse1_name);
            $email_w = $request->getParameter(email);
            $username_h = $request->getParameter(spouse_id);
            $name_h = $request->getParameter(spouse_name);
            $email_h = $request->getParameter(spouse_email);
        } 
        else {
            $username_w = $request->getParameter(spouse_id);
            $name_w = $request->getParameter(spouse_name);
            $email_w = $request->getParameter(spouse_email);
            $username_h = $request->getParameter(username);
            $name_h = $request->getParameter(spouse1_name);
            $email_h = $request->getParameter(email);
        }
        
        if ($user[GENDER] == 'F') {
            $send_email = $email_h;
            $EMAIL = $email_h;
            $EMAIL1 = $email_w;
        } 
        else {
            $send_email = $email_w;
            $EMAIL = $email_h;
            $EMAIL1 = $email_w;
        }
        
        $w_month = $request->getParameter(w_month);
        $w_day = $request->getParameter(w_day);
        $w_year = $request->getParameter(w_year);
        
        if ($w_month == '') $w_month = '0';
        if ($w_day == '') $w_day = '0';
        $date = $w_year . "-" . $w_month . "-" . $w_day;
        
        //print_r($_POST);die;
        
        $resultArr = array(
            "NAME_H" => $name_h,
            "NAME_W" => $name_w,
            "USERNAME" => $username,
            "WEDDING_DATE" => $date,
            "CONTACT_DETAILS" => $contact_address,
            "EMAIL" => $EMAIL,
            "EMAIL_W" => $EMAIL1,
            "COMMENTS" => $ss_story,
            "DATETIME" => date("Y-m-d H:i:s") ,
            "USERNAME_H" => $username_h,
            "USERNAME_W" => $username_w,
            "PIC_URL" => "",
            "UPLOADED" => "N",
            "SEND_EMAIL" => $send_email,
            "PHOTO" => "wedding_photo",
            "NAME" => "",
            "SKIP_COMMENTS" => ""
        );
        
        $lastid = AddStory::AddSuccessStory($resultArr);
        
        if ($user[GENDER] == "F") {
            $spouse_username = $username_h;
        } 
        else $spouse_username = $username_w;
        
        $dbObj = new NEWJS_SS_SPOUSE;
        $dbObj->insert($lastid, $spouse_username);
        
        $this->SSMAILER($username);
    }

    public function SSMAILER($username) {
        $dbObj = new MAILER_SS_MAILER;
        if ($dbObj->insert($username, 1)) {
            if (!$_FILES["wedding_photo"]["tmp_name"]) {
                $profilesArr = $dbObj->getUnsentProfiles('1');
                foreach ($profilesArr as $uname) {
                    $profileObj = new Profile();
                    $profileObj->getDetail($uname, 'USERNAME', 'PROFILEID,USERNAME,GENDER,PHONE_MOB,PHONE_RES,CITY_RES,EDU_LEVEL,EMAIL,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON, HAVEPHOTO,PREACTIVATED,SORT_DT,VERIFY_EMAIL');
                    $emailSender = new EmailSender(MailerGroup::SUCCESS_STORY_PHOTO);
                    $tpl = $emailSender->setProfile($profileObj);
                    $emailSender->send();
                    $dbObj->update($uname);
                }
            }
        }
    }
    
    public function executeLayer(sfWebRequest $request) {
    	
    	if (MobileCommon::isMobile()) {
            if (MobileCommon::isNewMobileSite()) {
                $this->forward("successStory", "jsmsInputStory");
            }
        }
        $this->FetchProfile($request);
        $this->error = $request->getParameter("error");
        $this->offerConsent = $request->getParameter("offerConsent");
        
        $this->fromMailer = $request->getParameter("fromSuccessStoryMailer");
        if($this->fromMailer==true){
            $this->mailerid = $request->getParameter("mailid");
            $authenticationJsObj = new JsAuthentication();
            $this->mailerid=$authenticationJsObj->js_decrypt($this->mailerid);
            if($this->mailerid==""||empty($this->mailerid)){
                //Invalid URL requested
                $this->error ="wrongmailid";
                $context = $this->getContext();
                $context->getController()->forward("static", "logoutPage",0); //Logout page
            }
            if(!$this->error){
                $successStoryMailLog = new incentive_SUCCESS_STORY_EMAIL_LOG();
                $this->profileid = $successStoryMailLog->getLogEntry($this->mailerid);
                if($this->profileid==""){
                    //Profileid and mailer id not linked to each other.
                    $this->error ="wrongmailid";
                    $context = $this->getContext();
                    $context->getController()->forward("static", "logoutPage",0); //Logout page
                }
            }
            
        }
        if (is_numeric($this->profileid)) {
            $jprofile = new JPROFILE();
            $row = $jprofile->get($this->profileid, "PROFILEID", "USERNAME,GENDER,EMAIL,CONTACT");
            $this->USERNAME = $row["USERNAME"];
            $this->EMAIL = $row["EMAIL"];
            $this->CONTACT = $row["CONTACT"];
            $this->CHECKSUM = $checksum;
            $this->PROFILEID = $profileid;
            
            $user_name = $row["USERNAME"];
            $gender = $row["GENDER"];
            
            $dbObj = new NEWJS_SUCCESS_STORIES;
            $resultArr = $dbObj->fetchStoryDetail(array(
                "USERNAME" => $user_name
            ));
            $row = $resultArr[0];
            
            if ($gender == 'F') {
                $this->NAME_H = $row["NAME_H"];
                $this->NAME = $row["NAME_W"];
                $this->USERNAME_W = $row["USERNAME_H"];
                $this->EMAIL_W = $row["EMAIL"];
            }
            if ($gender == 'M') {
                $this->NAME_H = $row["NAME_W"];
                $this->NAME = $row["NAME_H"];
                $this->USERNAME_W = $row["USERNAME_W"];
                $this->EMAIL_W = $row["EMAIL_W"];
            }
            
            if(empty($this->NAME)){
                $nameOfUserOb=new NameOfUser();
                if($this->fromMailer!=true){
                    $loginProfile=LoggedInProfile::getInstance();        
                    $nameOfUserArr = $nameOfUserOb->getNameData($loginProfile->getPROFILEID());
                    $this->NAME = $nameOfUserArr[$loginProfile->getPROFILEID()]["NAME"];
                }
                else{
                    $nameOfUserArr = $nameOfUserOb->getNameData($this->profileid);
                    $this->NAME = $nameOfUserArr[$this->profileid]["NAME"];
                }
                
            	unset($nameOfUserOb);
                /*$objNameStore = new incentive_NAME_OF_USER;
		        $loginProfile=LoggedInProfile::getInstance();
				$this->NAME = $objNameStore->getName($loginProfile->getPROFILEID());*/
            }

            $W_DATE = $row["WEDDING_DATE"];
            $WEDD_DATE = explode("-", $W_DATE);
            $w_year = $WEDD_DATE[0];
            $w_month = $WEDD_DATE[1];
            $w_day = $WEDD_DATE[2];
            
            $this->W_YEAR = (int)ltrim($w_year, '0');
            $this->W_MONTH = (int)ltrim($w_month, '0');
            $this->W_MONTH_TEXT = date("M", strtotime($W_DATE));
            $this->W_DAY = (int)ltrim($w_day, '0');
            
            $this->WEDDING_DATE = $row["WEDDING_DATE"];
            $this->CONTACT_DETAILS = $row["CONTACT_DETAILS"];
            $this->COMMENTS = $row["COMMENTS"];
        }
        if ($request->getParameter("from_delete_profile")) $this->FROM_DELETE_PROFILE = 1;
        
        $curDate = date('Y');
        for ($i = $curDate; $i >= 2000; $i--) $dateArray[] = $i;
        $this->dateArray = $dateArray;
        $this->curDate = $curDate;
        
        $this->setTemplate('jspcLayer');
    }
    
    public function executeSpriteimages(sfWebRequest $request) {
        $storyArr = IndividualStories::showSuccessPoolStory();
        $imgBuf = array();
        $i = 0;
        if ($request->getParameter("get_home_photo") == 'y') $column = 'HOME_PIC_URL';
        else $column = 'MAIN_PIC_URL';
        
        foreach ($storyArr as $key => $val) {
            $imgBuf[$i++] = $val[$column];
        }
        $this->getDynamicImage($imgBuf[0], $imgBuf[1], $imgBuf[2], $imgBuf[3]);
        return sfView::NONE;
    }
    
    public function getDynamicImage($image1URL, $image2URL, $image3URL, $image4URL) {
        $response = $this->getResponse();
        $response->setContentType('image/jpg');
        $response->setHttpHeader('Cache-Control', 'private');
         //optional cache header
        $response->setHttpHeader('Expires', gmdate('D, d M Y H:i:s', time() + (3600 * 24)) . " GMT");
         //optional cache header
        
        $image1 = $this->createImagefromURL($image1URL);
        $image2 = $this->createImagefromURL($image2URL);
        $image3 = $this->createImagefromURL($image3URL);
        $image4 = $this->createImagefromURL($image4URL);
        $iOut = imagecreatetruecolor("85", "220");
        imagecopyresized($iOut, $image1, 0, 0, 0, 0, 85, 55, imagesx($image1) , imagesy($image1));
        imagedestroy($image1);
        imagecopyresized($iOut, $image2, 0, 55, 0, 0, 85, 55, imagesx($image2) , imagesy($image2));
        imagedestroy($image2);
        imagecopyresized($iOut, $image3, 0, 110, 0, 0, 85, 55, imagesx($image3) , imagesy($image3));
        imagedestroy($image3);
        imagecopyresized($iOut, $image4, 0, 165, 0, 0, 85, 55, imagesx($image4) , imagesy($image4));
        imagedestroy($image4);
        imagejpeg($iOut);
    }
    
    public function createImagefromURL($url) {
        $url = PictureFunctions::getCloudOrApplicationCompleteUrl($url);
        $img = imagecreatefromstring(file_get_contents($url));
        return $img;
    }
    
    private function setMetaTags($request) {
        $response = $this->getResponse();
        $year = $request->getParameter('year');
        $page = $request->getParameter('page');
        $mappedValue = $request->getParameter('mappedvalue');
        $parentValue = $request->getParameter('parentvalue');
        $parentType = $request->getParameter('parenttype');
        $mappedType = $request->getParameter('mappedtype');
        if (($year == 2007 && $page == 91) || ($page == 2 && $mappedValue == 18 && $parentValue == 20 && $parentType == "OCCUPATION" && $mappedType == "CASTE")) $response->addMeta('robots', 'noindex,nofollow');
    }

    /*fetch success story data 
    * @return: $output as api response
    */
    public function executeGetSuccessStoryData(sfWebRequest $request)
    {
        // Success Story Data
        $individualStoriesObj = new IndividualStories;
        $output["stories"] = $individualStoriesObj->showSuccessPoolStory();
        unset($individualStoriesObj);
        foreach($output["stories"] as $key=>$value)
        {
            $output["stories"][$key]["vspSSPicUrl"] = PictureFunctions::getCloudOrApplicationCompleteUrl($value["SQUARE_PIC_URL"]);
        }
        $respObj = ApiResponseHandler::getInstance();
        if($output["stories"])
            $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        else
            $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        $respObj->setResponseBody($output);  
        $respObj->generateResponse();
        die;
    }

    public function executeJsmsInputStory(sfWebRequest $request){
    	$this->FetchProfile($request);
    	if (is_numeric($this->profileid)) {
            $loggedInObj = LoggedInProfile::getInstance();
            $this->USERNAME = $loggedInObj->getUSERNAME();
            $dbObj = new NEWJS_SUCCESS_STORIES('newjs_slave');
            $resultArr = $dbObj->fetchStoryDetail(array(
                "USERNAME" => $this->USERNAME
            ));
            $row = $resultArr[0];
            $this->COMMENTS = $row["COMMENTS"];
        }
    	// opens template, takes input story
    }

    public function executeJsmsSkipDelete(sfWebRequest $request){
    	// opens template, confirms deletion of profile	
    }

    public function executeJsmsSelectImage(sfWebRequest $request){
    	$this->successStoryMsg = $request->getParameter('successStoryMsg');
    	$this->FetchProfile($request);
        $this->error = $request->getParameter("error");

        if (is_numeric($this->profileid)) {
            $loggedInObj = LoggedInProfile::getInstance();
            $this->USERNAME = $loggedInObj->getUSERNAME();
            $this->EMAIL = $loggedInObj->getEMAIL();
            $this->CONTACT = $loggedInObj->getCONTACT();
            $this->CHECKSUM = $checksum;
            $this->PROFILEID = $profileid;
            
            $user_name = $loggedInObj->getUSERNAME();
            $gender = $loggedInObj->getGENDER();
            
            $dbObj = new NEWJS_SUCCESS_STORIES('newjs_slave');
            $resultArr = $dbObj->fetchStoryDetail(array(
                "USERNAME" => $user_name
            ));
            $row = $resultArr[0];
            
            if ($gender == 'F') {
                $this->NAME_H = $row["NAME_H"];
                $this->NAME = $row["NAME_W"];
                $this->USERNAME_W = $row["USERNAME_H"];
                $this->EMAIL_W = $row["EMAIL"];
            }
            if ($gender == 'M') {
                $this->NAME_H = $row["NAME_W"];
                $this->NAME = $row["NAME_H"];
                $this->USERNAME_W = $row["USERNAME_W"];
                $this->EMAIL_W = $row["EMAIL_W"];
            }
            
            if(empty($this->NAME)){
                $nameOfUserOb=new NameOfUser();                
                $nameOfUserArr = $nameOfUserOb->getNameData($this->profileid);
                $this->NAME = $nameOfUserArr[$this->profileid]["NAME"];
                unset($nameOfUserOb);
            	/*$objNameStore = new incentive_NAME_OF_USER('newjs_slave');
				$this->NAME = $objNameStore->getName($this->profileid);*/
            }

            $W_DATE = $row["WEDDING_DATE"];
            $WEDD_DATE = explode("-", $W_DATE);
            $w_year = $WEDD_DATE[0];
            $w_month = $WEDD_DATE[1];
            $w_day = $WEDD_DATE[2];
            
            $this->W_YEAR = (int)ltrim($w_year, '0');
            $this->W_MONTH = (int)ltrim($w_month, '0');
            $this->W_MONTH_TEXT = date("M", strtotime($W_DATE));
            $this->W_DAY = (int)ltrim($w_day, '0');
            
            $this->WEDDING_DATE = $row["WEDDING_DATE"];
            $this->CONTACT_DETAILS = $row["CONTACT_DETAILS"];
            $this->COMMENTS = $row["COMMENTS"];
        }
        if ($request->getParameter("from_delete_profile")) $this->FROM_DELETE_PROFILE = 1;
        
        $curDate = date('Y');
        for ($i = $curDate; $i >= 2000; $i--) $dateArray[] = $i;
        $this->dateArray = $dateArray;
        $this->curDate = $curDate;
    }
    }

