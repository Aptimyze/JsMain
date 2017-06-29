<?php

/**
 * seo actions.
 *
 * @package    jeevansathi
 * @subpackage seo
 * @author     Hemant Agrawal, Rohit Khandelwal
 * @version    SVN: $Id: actions.class.php
 */

class seoActions extends sfActions
{
    
    private $groomArr;
    private $brideArr;
    public function preExecute() {
        $this->getResponse()->addVaryHttpHeader("User-Agent");
        
        $this->jprofileColumns = "PROFILEID,USERNAME,GENDER,AGE,HEIGHT,CASTE,MTONGUE,PHOTOSCREEN,OCCUPATION,COUNTRY_RES,CITY_RES,SUBCASTE,RELIGION,EDU_LEVEL,INCOME,GOTHRA,YOURINFO,HAVEPHOTO,EDU_LEVEL_NEW,PHOTO_DISPLAY,PRIVACY,SCREENING,LAST_LOGIN_DT,SUBSCRIPTION";
        $this->jprofileScreenColumns = "GOTHRA,YOURINFO,SUBCASTE";
    }
    
    public function updateHits($params) {
        $params = str_replace("?", "", $params);
        if ($params) {
            $arr = explode("&", $params);
            foreach ($arr as $key => $val) {
                $tempArr = explode("=", $val);
                if ($tempArr[0] == "source") $source = $tempArr[1];
            }
            if ($source) CommonUtility::SaveHit($source, $this->seoUrl);
        }
    }
    public function executeIndex(sfWebRequest $request) {
        $queryString = "?" . $_SERVER[QUERY_STRING];
        $this->seoUrl = str_replace($queryString, "", $_SERVER[REQUEST_URI]);
        
        $this->crazyEgg();
        if ($queryString == "?") $queryString = "";
        
        //301 redirection for hindi-delhi to hindi
        if ($this->seoUrl == '/hindi-delhi-matrimony-matrimonials') {
            $this->redirect('/matrimonials/hindi-matrimonial' . $queryString, 301);
        } 
        elseif ($this->seoUrl == '/hindi-delhi-brides-girls') {
            $this->redirect('/hindi-brides-girls' . $queryString, 301);
        } 
        elseif ($this->seoUrl == '/hindi-delhi-grooms-boys') {
            $this->redirect('/hindi-grooms-boys' . $queryString, 301);
        }
        
        $this->loginData = $request->getAttribute("loginData");
        if ($this->loginData["PROFILEID"]) {
            $this->redirect("/P/mainmenu.php?checksum=" . $this->loginData['CHECKSUM']);
        } 
        else {
            $loggedin = 0;
        }
        
        //Function will give us back which level this URL belongs to .
        $fetchlevelObj = new FetchLevel();
        $this->levelObj = $fetchlevelObj->FetchLevelObj($this->seoUrl);
        
        if (!$this->levelObj) $this->forward("seo", "404");
        
        $this->UpdateHits($queryString);
        sfContext::getInstance()->getResponse()->setCanonical(sfConfig::get("app_site_url") . $this->seoUrl);

        /* Google Remarketing Starts */
        $isearch = $_COOKIE["ISEARCH"];
        $this->GR_ISEARCH = $isearch;
        $this->GR_LOGGEDIN = $loggedin;
        if ($loggedin === 0 && !$isearch) {
            $this->GR_DATE = date('Y-m-d');
            $parent_type = $this->levelObj->getParentType() ? $this->levelObj->getParentType() : null;
            
            if ($parent_type) {
                $parent_value = $this->levelObj->getParentValue() ? $this->levelObj->getParentValue() : null;
                if ($parent_value) {
                    switch ($parent_type) {
                        case "MTONGUE":
                            $this->GR_MTONGUE = GoogleRemarketing::getMtongueTag($parent_value);
                            break;

                        case "RELIGION":
                            $this->GR_RELIGION = GoogleRemarketing::getReligionTag($parent_value);
                            break;

                        case "CASTE":
                            $this->GR_CASTE = GoogleRemarketing::getCasteTag($parent_value);
                            break;

                        case "COUNTRY":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($parent_value, $parent_type);
                            break;

                        case "CITY":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($parent_value, $parent_type);
                            break;

                        case "STATE":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($parent_value, $parent_type);
                            break;

                        case "OCCUPATION":
                            $this->GR_EDU_OCC = GoogleRemarketing::getEducationOccupationTag($parent_value);
                            break;

                        case "MSTATUS":
                            $this->GR_MSTATUS = GoogleRemarketing::getMstatusTag($parent_value);
                            break;

                        default:
                            
                            //error_log(__FILE__ . "::" . __LINE__ . ":" . " SEO GR, Parent Type not handled " . $parent_type);
                            break;
                    }
                }
            }
            
            $mapped_type = $this->levelObj->getMappedType() ? $this->levelObj->getMappedType() : null;
            
            if ($mapped_type) {
                
                $mapped_value = $this->levelObj->getMappedValue() ? $this->levelObj->getMappedValue() : null;
                
                if ($mapped_value) {
                    
                    switch ($mapped_type) {
                        case "MTONGUE":
                            $this->GR_MTONGUE = GoogleRemarketing::getMtongueTag($mapped_value);
                            break;

                        case "RELIGION":
                            $this->GR_RELIGION = GoogleRemarketing::getReligionTag($mapped_value);
                            break;

                        case "CASTE":
                            $this->GR_CASTE = GoogleRemarketing::getCasteTag($mapped_value);
                            break;

                        case "COUNTRY":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($mapped_value, $mapped_type);
                            break;

                        case "STATE":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($mapped_value, $mapped_type);
                            break;

                        case "CITY":
                            $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($mapped_value, $mapped_type);
                            break;

                        case "OCCUPATION":
                            $this->GR_EDU_OCC = GoogleRemarketing::getEducationOccupationTag($mapped_value);
                            break;

                        case "MSTATUS":
                            $this->GR_MSTATUS = GoogleRemarketing::getMstatusTag($mapped_value);
                            break;

                        default:
                            error_log(__FILE__ . "::" . __LINE__ . " SEO GR, Mapped Type not handled " . $mapped_type);
                            break;
                    }
                }
            }
        }
        
        /* Google Remarketing Ends */
        
        $this->levelObj->setBrideGroomURL();
        
        $this->breadCrumbObj = $this->levelObj->createBreadCrumb();
        
        //parameter added for google dynamic search ads
        $this->registerationSource = $request->getParameter("adnetwork");

        $page_source = $this->levelObj->getPageSource();
        
        if (strtoupper($this->levelObj->getParentType()) == 'CASTE') $this->MORE_WIDTH = '1';
        if (strtoupper($this->levelObj->getParentType()) == 'RELIGION') $this->LESS_WIDTH = '1';
        
        if (strtoupper($this->levelObj->getParentType()) == 'RELIGION' || strtoupper($this->levelObj->getParentType()) == 'OCCUPATION' || strtoupper($this->levelObj->getParentType()) == 'COUNTRY' || strtoupper($this->levelObj->getParentType() == 'SPECIAL_CASES') || strtoupper($this->levelObj->getParentType() == 'MSTATUS')) $this->NOMORE = 'TRUE';
        
        $this->SLIDER_IMAGE = $this->levelObj->getImgUrl();
        $this->SOURCE = $this->levelObj->getSource();
        
        $this->setBreadcrumb();
        
        $this->MiniRegistration();
        
        //to auto fill top search band fileds
        $this->TopSearchBandFields();
        
        $this->setMetaTags($this->levelObj);
        
        $this->levelOrigObj = clone ($this->levelObj);
        
        $this->decorateObjects($page_source);
        $this->seoSearchProfileObj = $this->levelObj->getProfiles();
        
        //get bride profile details
        
        if ($this->seoSearchProfileObj->getBrideProfiles()) {
            $this->brideArr = $this->profilesInformation($this->seoSearchProfileObj->getBrideProfiles());
        }
        
        //get groom profile details
        if ($this->seoSearchProfileObj->getGroomProfiles()) {
            $this->groomArr = $this->profilesInformation($this->seoSearchProfileObj->getGroomProfiles());
        }
        
        $this->page_source = $page_source;
        
        if ($page_source == 'N') {
            $this->leftArr = $this->brideArr;
            $this->rightArr = $this->groomArr;
            $this->LOOP_F = count($this->brideArr);
            $this->LOOP_M = count($this->groomArr);
            $this->titleL = "Bride";
            $this->titleR = "Groom";
            $this->GR_GENDER = "Profiles";
        } 
        else if ($page_source == 'B') {
            $this->profileDisplay($this->brideArr);
            $this->titleL = "Bride";
            $this->titleR = "Bride";
            $this->GR_GENDER = "Brides";
        } 
        else if ($page_source == 'G') {
            $this->profileDisplay($this->groomArr);
            $this->titleL = "Groom";
            $this->titleR = "Groom";
            $this->GR_GENDER = "Grooms";
        }
        JsCommon::SeoFooter($this);
        
        $this->leftCnt = count($this->leftArr);
        $this->rightCnt = count($this->rightArr);
        unset($this->levelObj);
        $this->levelObj = $this->levelOrigObj;


        if ($this->isMobile = MobileCommon::isMobile("JS_MOBILE")) {
            if (MobileCommon::isNewMobileSite())
                $this->setTemplate("seo_mob");
            else
                $this->setTemplate("jsmb_seo");
        }
        else {
        	$this->setTemplate('jspcIndex');
        }
        //Start: JSC2600: AMP on community pages
        if (strpos($request->getUri(), 'amp=1')){
            $v = explode("?",$request->getUri());
            $request->setAttribute("ampurl", $v[0]);
        }else{
            $request->setAttribute("ampurl", $request->getUri() . "?amp=1");
        }
        if($request->getParameter('amp')=="1"){
             $this->setTemplate("seo_mob_amp");
        }
        //End: JSC2600: AMP on community pages
        $this->MtongueDropdownForTemplate = CommonFunction::generateMtongueDropdownForTemplate();

        // Success Story Data
        $individualStoriesObj = new IndividualStories;
		$this->successStoryData = $individualStoriesObj->showSuccessPoolStory();

		$this->curDate = date('F j, Y');
    }
    
    // ******** executeIndex function ends here ***************
    public function executeSeo_IndexTabs(sfWebRequest $request) {
        JsCommon::SeoFooter($this);
    }
    
    public function execute404(sfWebRequest $request) {
        //JsCommon::SeoFooter($this);
         $specificDomain = explode('/',$request->getUri());
    $segregateCode = $specificDomain[3]; 
        sfContext::getInstance()->getResponse()->setStatusCode(404, 'Not Found');
        if (MobileCommon::isMobile()) {
            $this->forward("static", "page404");
        }
        LoggingManager::getInstance(LoggingEnums::EX404)->logThis(LoggingEnums::LOG_ERROR, new Exception("404 page encountered"), array(LoggingEnums::MESSAGE => $request->getUri(), LoggingEnums::MODULE_NAME => LoggingEnums::EX404."_".$segregateCode));
    }
    
    function TopSearchBandFields() {
        $this->quick_search_country = array(
            '128',
            '125',
            '126',
            '7',
            '22',
            '51'
        );
        
        if ($this->levelObj->getLevelNum() == 2) {
            
            if ($this->levelObj->getMappedType() == 'CITY') $this->levelObj->setMappedType('CITY_RES');
            
            $parent_type1 = $this->levelObj->getParentType() . '-' . $this->levelObj->getMappedType();
            $parent2 = $this->levelObj->getParentValue() . '-' . $this->levelObj->getMappedValue();
            if ($this->levelObj->getPageSource() == "B" || $this->levelObj->getPageSource() == "G") {
                $parent_type1.= "-GENDER";
                if ($this->levelObj->getPageSource() == 'B') $parent2.= "-F";
                else $parent2.= "-M";
            }
            $this->field = $parent_type1;
            $this->value = $parent2;
        } 
        else {
            if ($this->levelObj->getPageSource() == "B" || $this->levelObj->getPageSource() == "G") {
                $fieldB = "-GENDER";
                if ($this->levelObj->getPageSource() == 'B') $valueB = "-F";
                else $valueB = "-M";
            }
            if (($this->levelObj->getParentType() == 'CITY_RES' && $this->levelObj->getParentValue() == '22') || ($this->levelObj->getParentType() == 'CITY_RES' && $this->levelObj->getParentValue() == '7')) $this->levelObj->setParentValue("");
            if (in_array($this->levelObj->getParentValue() , $this->quick_search_country) && $this->levelObj->getParentType() == 'COUNTRY') {
                $this->field = 'CITY_RES' . $fieldB;
                $this->value = $this->levelObj->getParentValue() . '' . $valueB;
            } 
            else {
                $this->field = $this->levelObj->getParentType() . '' . $fieldB;
                $this->value = $this->levelObj->getParentValue() . '' . $valueB;
            }
        }
    }
    
    //Crazy egg monitoring on this page
    
    public function crazyEgg() {
        $this->crazyEggMonitorUrl = "/matrimonials/hindu-matrimonial/";
        if ($this->seoUrl == $this->crazyEggMonitorUrl) $this->crazyEgg = 1;
    }
    
    // mini registration
    public function MiniRegistration() {
        
        $this->minireg = new SEOMiniRegistration();
        $this->minireg->assign($this->levelObj->getParentType() , $this->levelObj->getParentValue());
    }
    
    // decorate objects
    public function decorateObjects($page_source) {
        if ($page_source == 'N') {
            $this->levelObj = new BridePage($this->levelObj);
            $this->levelObj = new GroomPage($this->levelObj);
        }
        if ($page_source == 'B') {
            $this->levelObj = new BridePage($this->levelObj, 3);
            $this->levelObj = new BridePage($this->levelObj, 1);
        }
        if ($page_source == 'G') {
            $this->levelObj = new GroomPage($this->levelObj, 3);
            $this->levelObj = new GroomPage($this->levelObj, 1);
        }
    }
    
    //get profile deatils
    
    public function profilesInformation($profileStr) {
        
        $pid["PROFILEID"] = implode(",", $profileStr);
        
        $multipleProfileObj = new ProfileArray();
        
        $this->profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pid, '', '', $this->jprofileColumns, "JPROFILE", "");
        
        //get picture array and picture count of profiles passed
        
        $picObj = new PictureArray($this->profileDetails);
        $picture = $picObj->getProfilePhoto();
        $picCount = $picObj->getNoOfPics($this->profileDetails);
        unset($picObj);
        
        foreach ($this->profileDetails as $key => $profileObj) {
            
            $profileid = $profileObj->getPROFILEID();
            $columnsArr = explode(",", $this->jprofileColumns);
            
            foreach ($columnsArr as $columnsIndex => $columnsName) {
                $funcName = "get" . $columnsName;
                $profileArr[$profileid][$columnsName] = $profileObj->$funcName();
            }
            $columnsArr = explode(",", $this->jprofileScreenColumns);
            foreach ($columnsArr as $columnsIndex => $columnsName) {
                $funcName = "getDecorated" . $columnsName;
                
                $profileArr[$profileid][$columnsName] = $profileObj->$funcName();
            }
            
            if (array_key_exists($profileid, $picture) && $picture[$profileid] != "") {
                $picObj = $picture[$profileid];
                if (isset($picObj)) {
                    $profileArr[$profileid]['MAIN_PIC'][0] = $picObj->getProfilePic450Url();
                    if (MobileCommon::isMobile("JS_MOBILE")) {
                        $profileArr[$profileid]['MAIN_PIC'][0] = $picObj->getProfilePicUrl();
                        if (MobileCommon::isNewMobileSite()) {
                            if ($picObj->getProfilePic120Url()) $profileArr[$profileid]['MAIN_PIC'][0] = $picObj->getProfilePic120Url();
                            else $profileArr[$profileid]['MAIN_PIC'][0] = $picObj->getThumbailUrl();
                        }
                    }
                    if ($picObj->getIsPhotoShown() == 'yes') {
                        $profileArr[$profileid]['MAIN_PIC'][1] = 1;
                        $profileArr[$profileid]['MAIN_PIC'][2] = 0;
                    } 
                    else {
                        $profileArr[$profileid]['MAIN_PIC'][1] = 0;
                        $profileArr[$profileid]['MAIN_PIC'][2] = 0;
                    }
                }
            } 
            else {
                $gender = $profileObj->getGENDER();
                if (MobileCommon::isMobile("JS_MOBILE")) $photoSize = 'ProfilePic120Url';
                else $photoSize = 'ProfilePic120Url';
                
                $profileArr[$profileid]['MAIN_PIC'][0] = PictureService::getRequestOrNoPhotoUrl('requestPhoto', $photoSize, $gender);
                $profileArr[$profileid]['MAIN_PIC'][1] = 0;
                $profileArr[$profileid]['MAIN_PIC'][2] = 0;
            }
            
            //for whether more photos link to show or not
            if (array_key_exists($profileid, $picCount)) {
                $count = $picCount[$profileid];
                $picObj = $picture[$profileid];
                $photoFlag = 0;
                
                if (isset($picObj)) {
                    if ($picObj->getIsPhotoShown() == 'yes') $photoFlag = 1;
                }
                
                if ($count > 1 && $photoFlag) {
                    $profileArr[$profileid]['MAIN_PIC'][2] = 1;
                }
            }
            
            //Last Login Profile:
            if ($profileArr[$profileid]['LAST_LOGIN_DT']) $profileArr[$profileid]['LAST_LOGIN_SHOW'] = "Y";
            
            //Hyperlink logic below
            
            $city = "'" . $profileArr[$profileid]["CITY_RES"] . "'";
            $mtng = $profileArr[$profileid]["MTONGUE"];
            $religion = $profileArr[$profileid]["RELIGION"];
            $caste = $profileArr[$profileid]["CASTE"];
            $occupation = $profileArr[$profileid]["OCCUPATION"];
            
            $dbObj = new NEWJS_COMMUNITY_PAGES();
            
            $linkArr = $dbObj->getLink($caste, $occupation, $religion, $mtng, $city);
            foreach ($linkArr as $key => $linkUrl) {
                $link = $linkUrl["URL"];
                $type = $linkUrl["TYPE"];
                if ($link) $link = "$link";
                if (strtoupper($type) == 'MTONGUE') {
                    $profileArr[$profileid]["MTNG_LINK"] = $link;
                } 
                else if (strtoupper($type) == 'OCCUPATION') $profileArr[$profileid]["OCC_LINK"] = $link;
                else if (strtoupper($type) == 'CITY') $profileArr[$profileid]["CITY_LINK"] = $link;
                else if (strtoupper($type) == 'RELIGION') $profileArr[$profileid]["REL_LINK"] = $link;
                else if (strtoupper($type) == 'CASTE') $profileArr[$profileid]["CASTE_LINK"] = $link;
                
                unset($link);
                unset($type);
            }
            
            $profileArr[$profileid]["CITY_RES"] = $profileObj->getDecoratedCity();
            if (!$profileArr[$profileid]["CITY_RES"]) $profileArr[$profileid]["CITY_RES"] = $profileObj->getDecoratedCountry();
            
            $profileArr[$profileid]["COUNTRY_RES"] = $profileObj->getDecoratedCountry();
            $profileArr[$profileid]["OCCUPATION"] = $profileObj->getDecoratedOccupation();
            $profileArr[$profileid]["EDU_LEVEL_NEW"] = $profileObj->getDecoratedEducation();
            $profileArr[$profileid]["INCOME"] = $profileObj->getDecoratedIncomeLevel();
            $profileArr[$profileid]["CASTE"] = str_replace("-", "", FieldMap::getFieldLabel("caste_small", $profileObj->getCaste()));
            $profileArr[$profileid]["RELIGION"] = $profileObj->getDecoratedReligion();
            $profileArr[$profileid]["MTONGUE"] = FieldMap::getFieldLabel("community_small", $profileObj->getMTONGUE());
            $profileArr[$profileid]["HEIGHT"] = $profileObj->getDecoratedHeight();
            $profileArr[$profileid]["PROFILE_URL"] = $this->getProfileUrl($profileObj);
            
            $profileArr[$profileid]["profilechecksum"] = md5($profileid) . "i" . $profileid;
            
            $profileArr[$profileid]["SUBSCRIPTION"] = $profileObj->getSUBSCRIPTION();
            
            if (strlen($profileArr[$profileid]["YOURINFO"]) > 180) {
                
                //$yourinfo = nl2br($yourinfo);
                $yourinfo = substr($profileArr[$profileid]["YOURINFO"], 0, 180);
                $yourinfo = strip_tags($yourinfo);
                $yourinfo.= "....";
            } 
            else $yourinfo = $profileArr[$profileid]["YOURINFO"];
            
            $yourinfo = str_replace(array(
                "\r\n",
                "\r"
            ) , '', $yourinfo);
            $profileArr[$profileid]["YOURINFO"] = nl2br($yourinfo);
            
            if ($profileArr[$profileid]["GOTHRA"]) $profileArr[$profileid]["GOTHRA"] = $profileArr[$profileid]["GOTHRA"] . "(Gotra)";
        }
        
        // Logic for maintaining order
        
        for ($i = 0; $i < count($profileStr); $i++) if ($profileStr[$i] && $profileArr[$profileStr[$i]]) $returnArray[$profileStr[$i]] = $profileArr[$profileStr[$i]];
        
        return $returnArray;
    }
    
    // distibute profiles to display in left nad right panel
    
    function profileDisplay($profilesArr) {
        $oddNumber = 1;
        $count_row = count($profilesArr);
        if ($count_row % 2 == 0) $oddNumber = 0;
        $start_index_left_aligned = 0;
        $start_index_right_aligned = floor($count_row / 2);
        $leftCnt = floor($count_row / 2);
        if ($oddNumber) {
            $leftCnt = ceil($count_row / 2);
            $start_index_right_aligned = ceil($count_row / 2);
        }
        $rightCnt = floor($count_row / 2);
        $this->LOOP_F = $leftCnt;
        $this->LOOP_M = $rightCnt;
        $this->leftArr = @array_slice($profilesArr, $start_index_left_aligned, $leftCnt);
        $this->rightArr = @array_slice($profilesArr, $start_index_right_aligned, $rightCnt);
    }
    
    /**
     * Sets page meta tags like canonical url, title , description
     * and keywords
     */
    private function getProfileUrl($profileObj) {
        
        //http://www.jeevansathi.com/<bride>-<mother-tongue>-<religion>-<caste>-<username/userID>-profiles
        $casteAllow = 0;
        if ($this->CasteAllowed($profileObj->getRELIGION())) $casteAllow = 1;
        
        //Canonical url
        $can_url = $profileObj->getDecoratedCommunity() . "-" . $profileObj->getDecoratedReligion();
        if ($casteAllow) $can_url.= "-" . $profileObj->getDecoratedCaste();
        
        if ($profileObj->getGender() == "M") {
            $can_url = "groom-" . $can_url;
        } 
        else {
            $can_url = "bride-" . $can_url;
        }
        if ($can_url) {
            $can_url = strtolower($can_url);
            $can_url = $this->urlCompatible($can_url);
            $can_url.= "-" . $profileObj->getUSERNAME() . "-profiles";
        }
        return $can_url;
    }
    
    /**
     * Returns url comaptible string
     *
     */
    function urlCompatible($url) {
        $url = htmlspecialchars_decode($url, ENT_QUOTES);
        $url = preg_replace("/[&?:;@,!_=\/'\s()]/", "-", $url);
        $url = preg_replace("/-+/", "-", $url);
        $words = explode('-', $url);
        $array_size = count($words);
        $j = 1;
        $wordstats[0] = $words[0];
        for ($i = 1; $i <= $array_size; $i++) {
            if ($words[$i] != $words[$i - 1]) $wordstats[($j++) ] = $words[$i];
        }
        $url = implode('-', $wordstats);
        $url = rtrim($url, "-");
        
        //$url=urlencode($url);
        return $url;
    }
    
    /**
     * Returns trueif caste is present in following religion
     * @param religion int
     * return boolean true/false
     */
    public function CasteAllowed($religion) {
        if (in_array($religion, array(
            1,
            2,
            3,
            4,
            9
        ))) return true;
        else return false;
    }
    private function setMetaTags($levelObj) {
        $response = sfContext::getInstance()->getResponse();
        $title = htmlspecialchars_decode($levelObj->getTitle() , ENT_QUOTES);
        $response->setTitle($title);
        
        $desc = htmlspecialchars_decode($levelObj->getDescription() , ENT_QUOTES);
        $response->addMeta('description', $desc);
        
        $keyword = htmlspecialchars_decode($levelObj->getKeywords() , ENT_QUOTES);
        $response->addMeta("keywords", $keyword);
        
        $date = date("Y-m-d");
        $response->addMeta("dc.date.modified", $date);
    }
    
    //for cancer Landing Page
    public function executeCancer($request) {
        $param = $request->getParameterHolder()->getAll();
        $param[vcare] = intval($param[vcare]);
        $this->setCancerMetaTags();
        if ($param["vcare"] == 2) {
            return 'Story2';
        } 
        else if ($param["vcare"] == 3) {
            return 'Story3';
        } 
        else {
            return 'Story1';
        }
    }
    private function setCancerMetaTags() {
        $response = sfContext::getInstance()->getResponse();
        $title = htmlspecialchars_decode("Jeevansathi.com partners with V Care for Cancer Survior Matrimonial", ENT_QUOTES);
        $response->setTitle($title);
    }
    
    private function setBreadcrumb() {
        $dbObj = new NEWJS_COMMUNITY_PAGES();
        
        if ($this->levelObj->getParentValue() && $this->levelObj->getLevelNum() == 1) {
            $this->level1 = $this->levelObj->getSmallLabel() ? $this->levelObj->getSmallLabel() : $this->levelObj->getLabelName();
        } 
        elseif ($this->levelObj->getMappedValue()) {
            $this->level1 = $this->levelObj->getParentLabel();
            $this->level2 = $this->levelObj->getMappedLabel();
        }
        
        $breadcrumbLinkLevel1 = $dbObj->getBreadcrumbLink(strtoupper($this->levelObj->getParentType()) , $this->levelObj->getParentValue());
        $breadcrumbLinkLevel2 = $dbObj->getBreadcrumbLink(strtoupper($this->levelObj->getMappedType()) , $this->levelObj->getMappedValue());
        $this->urlLevel1 = $breadcrumbLinkLevel1['URL'];
        $this->urlLevel2 = $breadcrumbLinkLevel2['URL'];
    }
}
