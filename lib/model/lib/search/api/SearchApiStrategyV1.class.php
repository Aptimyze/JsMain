<?php
/**
* Search Api classes for version 1.0
* @author : Lavesh Rawat
* @package Search
* @subpackage Api
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-12-01
*/
class SearchApiStrategyV1
{
	private $defaultResultType = 'Both';
	private $photoType; 
	private $responseObj;
	private $output;
	private $searchCat;
	private $version;
	private $channel;
	private $profileTupleInfoArr = array('PROFILECHECKSUM','userLoginStatus','SUBSCRIPTION','AGE','USERNAME','DECORATED_HEIGHT','DECORATED_OCCUPATION','DECORATED_CASTE','DECORATED_INCOME','DECORATED_MTONGUE','DECORATED_EDU_LEVEL_NEW','DECORATED_CITY_RES','PHOTO','SIZE','ALBUM_COUNT','CONTACT_STATUS','BOOKMARKED','VERIFY_ACTIVATED_DT','NEW_FLAG','DECORATED_RELIGION','GENDER','FEATURED','FILTER_SCORE','FILTER_REASON','HIGHLIGHTED','VERIFICATION_SEAL','VERIFICATION_STATUS','stype','MSTATUS','COLLEGE','PG_COLLEGE','COMPANY_NAME','IGNORE_BUTTON','GUNASCORE','NAME_OF_USER','PROFILEID','THUMBNAIL_PIC','availforchat');
        private $profileInfoMappingArr = array("subscription"=>"subscription_icon","decorated_city_res"=>"decorated_location","contact_status"=>"eoi_label","verify_activated_dt"=>"timetext","new_flag"=>"seen","VERIFICATION_SEAL"=>"verification_seal");

	const caste_relaxation_text1  = 'To get $casteMappingCnt more matching profiles, include castes $casteMappingCastes';
        const caste_relaxation_text_JSMS1  = 'To get more matching profiles, we suggest you to include castes';
	const caste_relaxation_text2  = 'Want to include above castes?';

	const search_relaxation_text1 = 'We have relaxed some of your criteria to give you more results';
        const search_relaxation_text_JSMS1 = 'To get more profiles for you, <br>we have relaxed following criterias';
	const search_relaxation_text2 = 'Perform your original search';

        const search_relaxation_text_Desktop = 'To get more profiles for you, <br>we have relaxed following criterias';
	const contactDetailLabel      = 'Contact';
	const albumLabel              = 'Album'; 
	const photoLabel              = 'Photo'; 

	const profileIsBookmarked     = 'Y';

	const contactAcceptedlabel    = 'Accepeted'; 
	const contactDeclinedLabel    = 'Declined';
	const contactCancelLabel      = 'Cancelled';
	const contactReceivedLabel    = 'Interest Recvd';
	const contactSentLabel        = 'Interest Sent';
	const contactNoLabel          = 'Send Interest';
	const shortListLabel          = 'Shortlist';
	const shortListedLabel        = 'Shortlisted';
	const DONT_MATTER = "Doesn't Matter";
	const allText = "All";

	/**
	* constructor.
	* @access public
        * @param SolrResponse $responseObj search-results.
        * @param string $results_orAnd_cluster (default all results, other possible value 'onlyClusters','onlyResults')
	*/
	public function __construct($responseObj="",$results_orAnd_cluster='')
	{
		$this->responseObj = $responseObj;
		if(!$results_orAnd_cluster)
			$results_orAnd_cluster=$this->defaultResultType;
		$this->results_orAnd_cluster = $results_orAnd_cluster;
		$searchChannelFactoryObj = new SearchChannelFactory();
		$this->SearchChannelObj = $searchChannelFactoryObj->getChannel();
		$this->channel = $this->SearchChannelObj->getChannelType();
		
		$this->version = "V1";
		
	}
	
	
	

	/*
	* This function will convert the response to api format array.
	* @access public
	* @param LoggedInProfile $loggedInProfileObj logged in profile object 
	* @param array $searchClustersArray
	* @param int $searchId
	* @param SearchParamters $SearchParamtersObj
	* @param int $relaxedResults  set as 1 if results are auto relaxed
	* @param int $casteMappingCnt no of results, if user opts for castemapping.
	* @param string $casteMappingCastes list of caste suggested
	* @param int $currentPage
	* @param int $noOfPages
	* @param mixed $request
	* @param array $relaxCriteria array to be relaxed
	* @return array search respone in format for api
	*/
	public function convertResponseToApiFormat($loggedInProfileObj,$searchClustersArray,$searchId,$SearchParamtersObj,$relaxedResults="",$casteMappingCnt="",$casteMappingCastes="",$currentPage,$noOfPages,$request,$relaxCriteria)
	{
            
		if($request->getParameter("myJs")==1)
			$this->photoType= 'ProfilePic120Url';
		if($request->getParameter("searchBasedParam"))
			$this->searchCat = $request->getParameter("searchBasedParam");
		elseif($request->getParameter("justJoinedMatches")==1)
			$this->searchCat = 'justJoinedMatches';
		elseif($request->getParameter("reverseDpp")==1)
			$this->searchCat = 'reverseDpp';
		elseif($request->getParameter("twowaymatch")==1)
			$this->searchCat = 'twowaymatch';
		elseif($request->getParameter("partnermatches")==1)
			$this->searchCat = 'partnermatches';
		elseif($request->getParameter("matchalerts")==1)
                        $this->searchCat = 'matchalerts';
		elseif($request->getParameter("kundlialerts")==1)
                        $this->searchCat = 'kundlialerts';
		elseif($request->getParameter("contactViewAttempts")==1)
                        $this->searchCat = 'contactViewAttempts';

                $this->setMetaDataResponse($SearchParamtersObj,$currentPage,$searchId,$noOfPages,$relaxedResults,$casteMappingCnt,$casteMappingCastes,$relaxCriteria,$loggedInProfileObj,$request);
                if($this->noResults!=1)
		{
			if($this->results_orAnd_cluster!='onlyClusters')
				$this->setSearchResults($loggedInProfileObj,$searchId,$SearchParamtersObj,"",$this->responseObj->getFeturedProfileArr());
			if($this->results_orAnd_cluster!='onlyResults')
				$this->setClusters($searchClustersArray,$SearchParamtersObj);
		}
                $params['request'] = $request;
                $params['searchCat'] = $this->searchCat;
                $params['loggedInProfileObj'] = $loggedInProfileObj;
                $params['noOfResults'] = $this->responseObj->getTotalResults();
                $params['result_count'] = $this->output['result_count'];
                $params['pageSubHeading'] = $this->output['pageSubHeading'];
                $params['profileCount'] = 0;
                $params["nextAvail"] = $this->output['next_avail'];
                if(is_array($this->output["profiles"]))
					$params['profileCount'] = sizeOf($this->output["profiles"]);
				$outputArray = $this->SearchChannelObj->setRequestParameters($params);
              
                $this->output = array_merge($this->output,$outputArray);
               
		return $this->output;
	}


	/**
	* This function will add top level response in search results like status code,result_count,searchid.
        * @access public
        * @param SearchParamters $SearchParamtersObj
        * @param int $currentPage
        * @param int $searchId
        * @param int $noOfPages
        * @param int $relaxedResults  set as 1 if results are auto relaxed
        * @param int $casteMappingCnt no of results, if user opts for castemapping.
        * @param string $casteMappingCastes list of caste suggested
        * @param array $relaxCriteria array to be relaxed
	*/ 
	public function setMetaDataResponse($SearchParamtersObj,$currentPage,$searchId,$noOfPages,$relaxedResults,$casteMappingCnt,$casteMappingCastes,$relaxCriteria,$loggedInProfileObj,$request)
	{
		$cnt = $this->responseObj->getTotalResults();
		$this->output["dppLinkAtEnd"] = null;
		$this->output["myPageTitle"] = null;
		if(in_array($this->searchCat,array('justJoinedMatches','matchalerts','kundlialerts','contactViewAttempts')))
			$this->output["showSortingOption"] = 'N';
		else
			$this->output["showSortingOption"] = 'Y';
		
                
		$params["Version"]=$this->version;
		$params["Channel"]= $this->channel;
		$params["SearchType"]= $this->getSearchType($SearchParamtersObj->getSEARCH_TYPE());
		$params["Count"]=$cnt;
		$finalResponse['listType'] = 'search';

		$this->output["matchAlertsLogic"] = null;
		if($this->output["searchBasedParam"]=='matchalerts' || $this->searchCat=='matchalerts')
		{
			$newjsMatchLogicObj = new newjs_MATCH_LOGIC(SearchConfig::getSearchDb());
			$this->output["matchAlertsLogic"] = $newjsMatchLogicObj->getPresentLogic($loggedInProfileObj->getPROFILEID(),MailerConfigVariables::$oldMatchAlertLogic);
		}
		// For kundli alerts message changes as per horoscope uploaded condition
        if(($this->output["searchBasedParam"]=='kundlialerts' || $this->searchCat=='kundlialerts')&& $cnt==0)
        {   
         	$params["horoscope"] = "withoutHoro";  	
			$this->output["uploadHoroscope"] = "1";
           	//The same check has been applied on apps/jeevansathi/modules/profile/templates/_jspcViewProfile/_jspcViewProfileAstroSection.tpl
           	if($loggedInProfileObj->getBTIME()!="" && $loggedInProfileObj->getCITY_BIRTH()!="" && $loggedInProfileObj->getCOUNTRY_BIRTH()!="")
           	{
           		$params["horoscope"] = "withHoro";
           		$this->output["uploadHoroscope"] = "0";
           	}
        }
		$params["matLogic"]= $this->output["matchAlertsLogic"];
		$this->output["pageTitle"] = SearchTitleAndTextEnums::getTitle($params);
		$this->output["result_count"] = SearchTitleAndTextEnums::getHeading($params);
        $this->output["pageSubHeading"] = SearchTitleAndTextEnums::getSubHeading($params);
        $this->output["gaTracking"] = SearchTitleAndTextEnums::getGATracking($params);
		$this->output["noresultmessage"] = SearchTitleAndTextEnums::getMessageResult($params);
		$this->output["searchBasedParam"] = $params["SearchType"]?$params["SearchType"]:null;
		$this->output["diffGenderSearch"] = null;
		if($loggedInProfileObj && $loggedInProfileObj->getGENDER()!=$SearchParamtersObj->getGENDER())
			$this->output["diffGenderSearch"] = 1;
		if($request->getParameter("androidMyjsNew")==1){
			$this->photoType= 'ProfilePic120Url';
		}
		else
		{
			$this->photoType = SearchTitleAndTextEnums::getDefaultPicSize($params);
		}
		/* trac#4249 : at the end*/
		if($SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::AppJustJoinedMatches || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::JustJoinedMatches || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::iOSJustJoinedMatches || $this->searchCat == 'justJoinedMatches')
		{
			$this->output["newTagJustJoinDate"] = $SearchParamtersObj->getNewTagJustJoinDate();
			$this->output["dppLinkAtEnd"] = 'Go To Desired Partner Matches.';
		}

		$this->output["no_of_results"]= "$cnt";
		$this->output["page_index"]= "$currentPage";
		$this->output["searchid"]= "$searchId";
		if($SearchParamtersObj->getONLINE() == "O" && $this->channel == "PC")
		{
			$this->output["result_count"] = $this->output["result_count"]." online";
		}
		$this->output["sorting"]= $SearchParamtersObj->getSORT_LOGIC();
		if($SearchParamtersObj->getSORT_LOGIC()==SearchSortTypesEnums::dateSortFlag)
			$this->output["sortType"]= 'Date';
		else
			$this->output["sortType"]= 'Relevance';
		$this->output["stype"]= $SearchParamtersObj->getSEARCH_TYPE();
                if($request->getParameter("retainSearchType"))
                {
                        $this->output["stype"]= $request->getParameter("retainSearchType");
                }
		$this->output["defaultImage"]= PictureFunctions::getNoPhotoJSMS($SearchParamtersObj->getGENDER(),$this->photoType);
		if($noOfPages>$currentPage)
			$this->output["next_avail"]= "true";
		else
			$this->output["next_avail"]= "false";
		
		$this->output["relaxation_text1"] = null;
		$this->output["relaxation_text2"] = null;
		$this->output["relaxation_text_params"] = null;
		$this->output["relaxation"] = null;
		$this->output["relaxationHead"]=null;
		$this->output["relaxationType"]=null;
		$this->output["checkonline"]=false;
		if (JsConstants::$chatOnlineFlag['search'] && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
		{
			//$this->output["checkonline"]=true;
		}

		if($relaxedResults && $this->output["result_count"]>0 && MobileCommon::isApp()=='A')
		{
			$this->output["relaxation_text1"] = self::search_relaxation_text1;
			$this->output["relaxation_text2"] = self::search_relaxation_text2;
			$this->output["relaxation_text_params"] = "searchId=$searchId&noRelaxation=1";
		}
		else if($relaxedResults && is_array($relaxCriteria) && count($relaxCriteria)>0  && (MobileCommon::isMobile() || MobileCommon::isApp()=='I'))
		{
			$this->output["relaxation"] = $relaxCriteria;
			$this->output["relaxationHead"]=self::search_relaxation_text_JSMS1;
			$this->output["relaxationType"]="auto";
			$this->output["relaxation_text_params"] = "searchId=$searchId&noRelaxation=1";
		}
                else if($relaxedResults)
		{
                        if($relaxCriteria["Height"]==" to ")
                                unset($relaxCriteria["Height"]);
			$this->output["relaxation"] = implode(" | ",$relaxCriteria);
			$this->output["relaxationHead"]=array("main"=>"Low search Results","text"=>"These criterias are relaxed to get more profiles"); // count of profiles
			$this->output["relaxationType"]="auto";
			$this->output["relaxation_text_params"] = "searchId=$searchId&noRelaxation=1";
		}

		if($casteMappingCnt && $casteMappingCastes && (MobileCommon::isMobile() || MobileCommon::isApp()))
		{ 
			/*can create message parser */
			$msg = str_replace('$casteMappingCnt',$casteMappingCnt,self::caste_relaxation_text1); 
                        $msg = str_replace('$casteMappingCastes',$casteMappingCastes,$msg);
			$this->output["relaxation_text1"] = $msg;
			$this->output["relaxation_text2"] = self::caste_relaxation_text2;
			$this->output["relaxation_text_params"] = "searchId=$searchId&addEthnicities=1";
                        $this->output["relaxationHead"]=self::caste_relaxation_text_JSMS1;
                        $this->output["relaxation"] = array("Castes"=>$casteMappingCastes);
                        $this->output["relaxationType"]="suggest";
                       
		}
                else if($casteMappingCnt && $casteMappingCastes)
		{ 
			/*can create message parser */
			$msg = str_replace('$casteMappingCnt',$casteMappingCnt,self::caste_relaxation_text1); 
                        $msg = str_replace('$casteMappingCastes',$casteMappingCastes,$msg);
			$this->output["relaxation_text1"] = $msg;
			$this->output["relaxation_text2"] = self::caste_relaxation_text2;
			$this->output["relaxation_text_params"] = "searchId=$searchId&addEthnicities=1";
                        $this->output["relaxationHead"]=array("main"=>$cnt."  Profiles Only","text"=>"Recommended criteria to get more results");;
                        $this->output["relaxation"] = $casteMappingCastes;
                        $this->output["relaxationType"]="suggest";
                       
		}
	}

	/**
	* This function will return search type based on SearchTypesEnums and searchcat.
	* @access public
        * @param string $stype
        * @return string search type
	*/
	protected function getSearchType($stype)
	{
		if($this->searchCat == 'partnermatches' || $stype==SearchTypesEnums::AppDpp || $stype==SearchTypesEnums::WapDpp || $stype==SearchTypesEnums::iOSDpp)
			return "partnermatches";
		else if($this->searchCat == 'justJoinedMatches' || $stype==SearchTypesEnums::AppJustJoinedMatches || $stype==SearchTypesEnums::JustJoinedMatches || $stype==SearchTypesEnums::iOSJustJoinedMatches)
			return "justJoinedMatches";
		else if($this->searchCat == 'reverseDpp' || $stype==SearchTypesEnums::wapRevDPP || $stype==SearchTypesEnums::iOSRevDPP)
			return "reverseDpp";
		else if($this->searchCat == 'twowaymatch' || $stype==SearchTypesEnums::WapTwoWayMatch || $stype==SearchTypesEnums::iOSTwoWayMatch)
			return "twowaymatch";
                else if($this->searchCat == 'verifiedMatches' || $stype==SearchTypesEnums::VERIFIED_MATCHES_JSPC || $stype==SearchTypesEnums::VERIFIED_MATCHES_JSMS || $stype==SearchTypesEnums::VERIFIED_MATCHES_ANDROID || $stype==SearchTypesEnums::VERIFIED_MATCHES_IOS)
			return "verifiedMatches";
		else if($this->searchCat == "matchalerts" )
			return "matchalerts";
		else if($this->searchCat == "kundlialerts" )
                        return "kundlialerts";
		else if($this->searchCat == "contactViewAttempts" )
                        return "contactViewAttempts";
		else
			return "";
	}



	/**
	* This function will set the results array.
	* @access public
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        * @param int $searchId
        * @param SearchParamters $SearchParamtersObj
	*/
	public function setSearchResults($loggedInProfileObj,$searchId="",$SearchParamtersObj="",$searchResultArray="",$featuredProfileArr="",$fromVspAndroid="")
	{
		//echo "":print_r($searchResultArray);die;
		if($searchResultArray)
		{
			$resultsArray = $searchResultArray;
		}
		else {
                        $SearchDisplayObj = new SearchApiDisplay($SearchParamtersObj);
                        $resultsArray = $SearchDisplayObj->searchPageDisplayInfo('',$loggedInProfileObj,$this->responseObj,$searchId,$SearchParamtersObj->getSORT_LOGIC(),'',$this->photoType);
                }
                if($searchId){
                        $searchSummaryObj = new SearchService();
                        $searchSummaryResult = $searchSummaryObj->searchSummary($searchId);
                        $this->output["searchSummary"]=$searchSummaryResult;
                        if(MobileCommon::isAndroidApp()){
                                
                        }
                        $clusterIndex = $SearchParamtersObj->getCURRENT_CLUSTER();
                        if($clusterIndex != ""){
                                if(in_array($clusterIndex, explode(",",SearchConfig::$searchFullRangeParameters))){
                                        eval('$clusterLVal = $SearchParamtersObj->getL'.$clusterIndex.'();');
                                        eval('$clusterHVal = $SearchParamtersObj->getH'.$clusterIndex.'();');
                                        $this->output["searchSummary"]["FILTER_FIELD"] = array("L".$clusterIndex=>$clusterLVal,"H".$clusterIndex=>$clusterHVal);
                                }else{
                                        eval('$clusterVal = $SearchParamtersObj->get'.$clusterIndex.'();');
                                        $this->output["searchSummary"]["FILTER_FIELD"] = array($clusterIndex=>$clusterVal);
                                }
                        }
                }
                $featuredProfileArrNew = array();
               // echo '<pre>';
                //print_r($resultsArray);
								
                if($this->responseObj->getFeturedProfileArr()){					
                    $getExcludedFeatured = false; // Need to exclude first featured profile from result
                    $profileidsArray = $this->responseObj->getSearchResultsPidArr();

                    foreach($this->responseObj->getFeturedProfileArr() as $k=>$v){
                            $featured[$v["id"]]=$v;
                    }

                    foreach($resultsArray as $k=>$v){
                          if($featured[$k]){							
                                  if($getExcludedFeatured == true && is_array($profileidsArray) && in_array($k,$profileidsArray))
                                  {
                                          $resultArrNew[$k] =  $v;
                                  }
                                  else
                                          $getExcludedFeatured= true;
                                  $v["stype"] = $this->SearchChannelObj->getFeaturedProfilesStype();
                                  $featuredProfileArrNew[$k] =  $v;
                          }
                          elseif(is_array($profileidsArray) && in_array($k,$profileidsArray))
                                  $resultArrNew[$k] =  $v;
                    }
                    $resultsArray=$resultArrNew;
                }
                $profilesArr["profiles"] = $resultsArray;
             
                $profilesArr["featuredProfiles"] = $featuredProfileArrNew;
		$nameOfUserObj = new NameOfUser;
		$nameData = $nameOfUserObj->getNameData($loggedInProfileObj->getPROFILEID());	
		foreach($profilesArr as $profileKey=>$profileVal){
                        $gender = $loggedInProfileObj->getGENDER()=="M"?"F":"M";
                        $decoratedMappingSearchDisplay = SearchConfig::decoratedMappingSearchDisplay();
                        $i=0;
                        $bookm=false;
                        $contactState = "N";
                        
                        if(is_array($profileVal))
                        {

                                foreach($profileVal as $k=>$v)
                                {
                                	    foreach($this->profileTupleInfoArr as $kk=>$vv)
                                        { 
																			
                                                $fieldName = $this->customizedFieldName($vv);

                                                if(!$searchResultArray)
                                                {
                                                        $value = $this->handlingSpecialCasesForSearch($fieldName,$v[$vv],$profileVal[$k]['PHOTO_REQUESTED'],$SearchParamtersObj->getGENDER(),$SearchParamtersObj,$profileVal[$k]);                
							if($fieldName=='subscription_icon')
                                                                $this->output[$profileKey][$i]['subscription_text'] = $this->handlingSpecialCasesForSearch('subscription_text',$v[$vv],$profileVal[$k]['PHOTO_REQUESTED'],$SearchParamtersObj->getGENDER(),$SearchParamtersObj,$profileVal[$k]); 
                                                }
                                                
                                                if(in_array($fieldName,array('bookmarked','album_count','eoi_label','ignore_button')))
                                                {
                                                        if($fieldName=='album_count')
                                                                $this->output[$profileKey][$i][$fieldName] = $value["value"];

                                                        $tempkey = $fieldName=='eoi_label'?0:($fieldName=='bookmarked'?1:2);

                                                        /// added by Palash for android app for ignore button and ruling out album button
                                                        $appVersion=sfContext::getInstance()->getRequest()->getParameter('API_APP_VERSION');
                                                        if($fieldName!='ignore_button' && !($fieldName=='album_count' && MobileCommon::isApp()=='A' && $appVersion >=51) )	
                                                     	   $button[$tempkey] = $value;
                                                        else if($fieldName=='ignore_button' && MobileCommon::isApp()=='A' && $appVersion>=51 )
                                                        	$button[$tempkey] = $value;
                                                        /// added by Palash for android app for ignore button and ruling out album button


                                                }
                                                elseif($fieldName=='availforchat'){
                                                        $this->output[$profileKey][$i][$fieldName] = $value;
						}
                                                elseif($fieldName=='photo')
                                                        $this->output[$profileKey][$i][$fieldName] = $value;
                                                elseif($fieldName=='size'){
                                                        $this->output[$profileKey][$i][$fieldName] = $value;
                                                }
                                                elseif(in_array($fieldName,array('college','pg_college','company_name'))){
                                                        if(!$v[$vv]){
                                                                $v[$vv] = null;
                                                        }
                                                        $this->output[$profileKey][$i][$fieldName] = $v[$vv];
                                                }
                                                elseif($fieldName=="filter_score"){
                                                        $searchDisplayObj = new SearchDisplay();
                                                        //if($i<3){$value="";}else{$value=64;}  // Testing Purpose
                                                        $this->output[$profileKey][$i][$fieldName] = ucwords($searchDisplayObj->checkFilterReasons($value));
                                                }
						elseif($fieldName=="name_of_user")
						{
							if(is_array($nameData)&& $nameData[$loggedInProfileObj->getPROFILEID()]['DISPLAY']=="Y" && $nameData[$loggedInProfileObj->getPROFILEID()]['NAME']!='')
							{
								$name = $nameOfUserObj->getNameStr($value,$loggedInProfileObj->getSUBSCRIPTION());
							}
							$this->output[$profileKey][$i][$fieldName]=$name;
						}
                                                else
                                                {
							if($fieldName=='stype' && $value=='')
							{
							}
							else	
							{
	                                                        if($value)
        	                                                        $this->output[$profileKey][$i][$fieldName] = $value;
                	                                        else
                        	                                        $this->output[$profileKey][$i][$fieldName] = null;
							}
                                                }
                                                if($fieldName=="bookmarked"){ $bookm = $v[$vv];}
                                                if($fieldName=="eoi_label"){ $contactState = $v[$vv];}
                                                if($fieldName=="filter_reason"){ $this->output[$profileKey][$i][$fieldName] = ucwords($v["FILTER_REASONS"]);}
                                                if($fieldName=="highlighted"){$this->output[$profileKey][$i][$fieldName] = in_array("B",explode(",",$v["SUBSCRIPTION"]))?1:0;}
                                        }
                                        $button[3] = ButtonResponseApi::getContactDetailsButton();
                                        ksort($button);
                                        if($fromVspAndroid)
                                            $buttonDetails = ButtonResponseApi::buttonDetailsMerge(array("buttons"=>array(ButtonResponseApi::getInitiateButton())));
                                        else
                                            $buttonDetails = ButtonResponseApi::buttonDetailsMerge(array("buttons"=>$button));
                                        $this->output[$profileKey][$i]['buttonDetails'] = $buttonDetails;
        //				print_r($this->profileTupleInfoArr);
                                        if(MobileCommon::isApp()=='I'){
                                                $userloggedin=0;
                                            if($loggedInProfileObj->getPROFILEID())
                                            {
                                                  $userloggedin=1;
                                            }
                                                $params = array("SHORTLIST"=>$bookm,
						"PAGE"=>array("stype"=>($this->output[$profileKey][$i]["stype"])?$this->output[$profileKey][$i]["stype"]:$this->output["stype"]),
                                                "PHOTO"=>$this->output[$profileKey][$i]["photo"],
                                                "USERNAME"=>$this->output[$profileKey][$i]["username"],
                                                "GENDER"=>$gender,
                                                "OTHER_PROFILEID"=>$v['PROFILEID'],
                                                //"LOGIN"=>$this->output["profiles"][$i]["userloginstatus"]);
                                                "LOGIN"=>$userloggedin,
                                                "IGNORE"=>0);
                                                $this->output[$profileKey][$i]['buttonDetailsJSMS'] = ButtonResponse::getButtons($contactState,$params);
                                        }
                                        elseif(MobileCommon::isNewMobileSite() || MobileCommon::isDesktop()){
                                        	$userloggedin=0;
                                            if($loggedInProfileObj->getPROFILEID())
                                            {
                                                  $userloggedin=1;
                                            }
                                            if(MobileCommon::isDesktop())
												$source="P";
											else
												$source="M";
                                            $params = array("SHORTLIST"=>$bookm,
                                            "PAGE"=>array("stype"=>$this->output["stype"]),
                                            "STYPE"=>($this->output[$profileKey][$i]["stype"])?$this->output[$profileKey][$i]["stype"]:$this->output["stype"],
                                            "PHOTO"=>$this->output[$profileKey][$i]["photo"],
                                            "USERNAME"=>$this->output[$profileKey][$i]["username"],
                                            "GENDER"=>$gender,
                                            "OTHER_PROFILEID"=>$v['PROFILEID'],
                                            //"LOGIN"=>$this->output["profiles"][$i]["userloginstatus"]);
                                            "LOGIN"=>$userloggedin,
                                            "IGNORE"=>0,
	                                            "CHAT_GROUP"=>$contactState?$contactState:"N"
                                            );
                                           // print_r($params);die;
                                            $this->output[$profileKey][$i]['buttonDetailsJSMS'] = ButtonResponseFinal::getListingButtons("S",$source,"",$contactState,$params);
                                            //print_r($this->output[$profileKey][$i]['buttonDetailsJSMS']);die;
                                            if($source=="M")
                                            {
                                            	//$buttonObj = new ButtonResponse(LoggedInProfile::getInstance('newjs_master'),$profileObject,$page);
										            //$restResponseArray= $buttonObj->jsmsRestButtonsrray(array('PHOTO'=> PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'],'CC_LISTING'=>$infoKey,'BOOKMARKED'=>$tupleObj->getIS_BOOKMARKED(),'IGNORED'=>$tupleObj->getIS_IGNORED()));
									               $restResponseArray=ButtonResponseFinal::jsmsSearchRestButtonsarray($loggedInProfileObj,$params);
									                $this->output[$profileKey][$i]['buttonDetailsJSMS']["photo"]=$restResponseArray["photo"];
									                $this->output[$profileKey][$i]['buttonDetailsJSMS']["topmsg"]=$restResponseArray["topmsg"];
									                $this->output[$profileKey][$i]['buttonDetailsJSMS']["infobtnlabel"]=$restResponseArray["infobtnlabel"];
									               // print_r($this->output[$profileKey][$i]['buttonDetailsJSMS']);die;
									                
													unset($button);
                                            }
                                        }
                                        if(MobileCommon::isApp()=="A")
                                        {
                                        	$buttonDetailArr[$i] = $this->output[$profileKey][$i]['buttonDetails'];
                                        }
                                        else
                                        {
                                        	$buttonDetailArr[$i] = $this->output[$profileKey][$i]['buttonDetailsJSMS'];	
                                        }
                                        
                                        $i++;
                                }
                        }
                        else
                                $this->output[$profileKey] = null;
                }
                if($searchResultArray)
				return $buttonDetailArr;
               
	}


	/**
	* This function will map common-lib labels to labels to be used in app.
        * @access public
        * @param $key label
        * @param $value current-value
        * @param $isPhotoRequested set 1 if its photorequested case.
        * @param char $gender
        * @param SearchParamters $SearchParamtersObj
        * @param mixed $infoArr information of profile (get from solr)
        * @return string mapped value 
	*/
	public function handlingSpecialCasesForSearch($key,$value,$isPhotoRequested='',$gender='',$SearchParamtersObj='',$infoArr="")
	{
                //echo $key."\n\n";
		switch($key) 
		{
			case "availforchat":
				return $value;
			 case "gender":
                                $value = $SearchParamtersObj->getGENDER();
                                break;
                        case "seen":
                                $value=0;
                                $d2 = $infoArr["VERIFY_ACTIVATED_DT"];
                                $d1 = $SearchParamtersObj->getNewTagJustJoinDate();
                                if($d1)
                                {
                                        if($d2>$d1)
                                                $value='N';
                                        else
                                                $value='Y';
                                }
                                else
                                        $value='Y';
                                break;
			case "timetext":
				if($this->searchCat == 'justJoinedMatches' || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::AppJustJoinedMatches || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::JustJoinedMatches || $this->searchCat == 'matchalerts' || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::contactViewAttempt)
				{
					if($this->searchCat == 'matchalerts')
					{
                                                $value = CommonUtility::convertDateToDay($infoArr['SENT_DATE']);
                                                if(stripos($value,'today') === false){
                                                  $value = "on ".$value;
                                                }
						$value = "Sent to you ".$value;
					}
					else
					{
                                          if($this->searchCat == 'contactViewAttempts'){
                                                if(stripos($infoArr['SENT_DATE'],'today') === false){
                                                  $infoArr['SENT_DATE'] = "on ".$infoArr['SENT_DATE'];
                                                }
                                            if($SearchParamtersObj->getGENDER()=='M')
                                                    $value = "He contacted you ".$infoArr['SENT_DATE'];
                                            else
                                                    $value = "She contacted you ".$infoArr['SENT_DATE'];
                                          }else{
						$value = CommonUtility::convertDateToDay($value);
                                                if(stripos($value,'today') === false){
                                                  $value = "on ".$value;
                                                }
						if($SearchParamtersObj->getGENDER()=='M')
							$value = "He joined ".$value;
						else
							$value = "She joined ".$value;
                                          }
					}
				}
				else
					$value=null;
				break;
			case "bookmarked":
				$value = ButtonResponseApi::getShortListButton('','',$value=='Y'?1:0);
				break;
			case "mstatus":
				$value = $value;
				break;
			case "photo":
				$value = PictureFunctions::mapUrlToMessageInfoArr($value,$this->photoType,$isPhotoRequested,$gender);
				break;
                        case "THUMBNAIL_PIC":
				$value = PictureFunctions::mapUrlToMessageInfoArr($value,'ThumbailUrl',$isPhotoRequested,$gender);
				break;
			case "album_count":
				$value =  ButtonResponseApi::getAlbumButton($value,$gender);
				break;
			case "ignore_button":
				$value =  ButtonResponseApi::getIgnoreButton('','','',true,'Ignore');
				break;
			
			case "age":
				$value = $value." Years";
				break;
			case "subscription_text":
				if(CommonFunction::isEvalueMember($value))
					$value = mainMem::EVALUE_LABEL;
				elseif(CommonFunction::isErishtaMember($value))
					$value = mainMem::ERISHTA_LABEL;
				elseif(CommonFunction::isJsExclusiveMember($value))
                    $value = mainMem::JSEXCLUSIVE_LABEL;
                elseif(CommonFunction::isEadvantageMember($value))
                	$value = mainMem::EADVANTAGE_LABEL;
                else
                    $value = null;
				break;
			case "subscription_icon":
				//var_dump($value);die;
				if(CommonFunction::isEvalueMember($value))
				{
					if(MobileCommon::isApp()=='A')
						$value = IdToAppImagesMapping::EVALUE_SRP;
					else
						$value = mainMem::EVALUE_LABEL;
				}
				elseif(CommonFunction::isErishtaMember($value))
				{
					if(MobileCommon::isApp()=='A')
						$value = IdToAppImagesMapping::ERISHTA_SRP;
					else
						$value = mainMem::ERISHTA_LABEL;
				}
				elseif(CommonFunction::isEadvantageMember($value))
				{
					if(MobileCommon::isApp())
						$value = IdToAppImagesMapping::EADVANTAGE_SRP;
					else
						$value = mainMem::EADVANTAGE_LABEL;
				}
				elseif(CommonFunction::isJsExclusiveMember($value))
				{
					$request    = sfContext::getInstance()->getRequest();
					if(MobileCommon::isApp()=='A' && strpos($request->getParameter("newActions"), "JSEXCLUSIVE") === false)
						$value = null;
					else
					{
						if(strpos($request->getParameter("newActions"), "JSEXCLUSIVE") === false)
							$value = mainMem::JSEXCLUSIVE_LABEL;
						else
							$value = mainMem::JSEXCLUSIVE;
					}
				}
				else
					$value = null;
			break;
			
			case "eoi_label": 
				switch($value)
				{
					case ContactHandler::ACCEPTANCES_SENT:
					case ContactHandler::ACCEPTANCES_RECEIVED:
						$value = self::contactAcceptedlabel;
						break;
					case ContactHandler::DECLINED_SENT:
					case ContactHandler::DECLINED_RECEIVED:
						$value = self::contactDeclinedLabel;
						break;
					case ContactHandler::CANCEL_SENT:
					case ContactHandler::CANCEL_RECEIVED:
						$value = self::contactCancelLabel;
						break;	
					case ContactHandler::CANCEL_EOI_SENT:
					case ContactHandler::CANCEL_EOI_RECEIVED:
						$value = self::contactCancelLabel;
						break;	
					case ContactHandler::INTEREST_RECEIVED:
						$value = self::contactReceivedLabel;
						break;
					case ContactHandler::INTEREST_SENT:
						$value = self::contactSentLabel;
						break;
					default:
						$value = self::contactNoLabel;
						break;
				}
				break;
                        case "college":
                                $value = "Studied at ".$value;
                                break;
                        case "company_name":
                                $value = "Works at ".$value;
                                break;
			default:
				break;
		}
		if($key=='eoi_label')
		{
			if($value==self::contactNoLabel)		
			{  
				$request = sfContext::getInstance()->getRequest();
				$iconId = IdToAppImagesMapping::ENABLE_CONTACT;
				$page = '';
				if(MobileCommon::isApp() =="A" && $request->getParameter('API_APP_VERSION') >= 96)
				$page['comingFromPage'] = 'search';
				$value = ButtonResponseApi::getInitiateButton($page);
			}
			else
				$value = ButtonResponseApi::getCustomButton($value,"","","",$value==self::contactSentLabel?IdToAppImagesMapping::TICK_CONTACT:IdToAppImagesMapping::DISABLE_CONTACT);
		}
		return $value;
	}

	/**
	* This function will change the fieldName as per api requirements.
	* @access public
	* @param string fieldName
	* @return string 
	*/
	public function customizedFieldName($fieldName)
	{
		$fieldName = strtolower($fieldName);
		if(array_key_exists($fieldName,$this->profileInfoMappingArr))
			$fieldName = $this->profileInfoMappingArr[$fieldName];
		if(strstr($fieldName,"decorated_"))
		{
			$temp = explode("decorated_",$fieldName);
			$fieldName = $temp[1];
		}
		return $fieldName;
	}

	/**
	* This function set the cluseter as per app.
	* @access public
	* @param array $searchClustersArray
	* @param SearchParamters $SearchParamtersObj
	*/
	public function setClusters($arr,$SearchParamtersObj)
	{
		$clusterLabelMappingArray = searchConfig::clusterLabelMapping($SearchParamtersObj->getRELIGION());

		$i=0;
		$id = 1;//temp
		$solr_labels = FieldMap::getFieldLabel("solr_clusters",1,1);
		
		if(is_array($arr))
		foreach($arr as $k1=>$v1)
		{
			$j=0;	

			/* Temp */
			/*
			f(!in_array($k1,$clusersToShow))
				continue;
			*/
			/* Temp */

			$id++;
			if($k1=='EDU_LEVEL_NEW')
			{
				$idTemp = array_search ('EDUCATION_GROUPING',$solr_labels);
				$output["result_arr"][$i]["id"] = "$idTemp";
				$output["result_arr"][$i]["label"] = $clusterLabelMappingArray['EDUCATION_GROUPING'];
				$output["result_arr"][$i]["stext"] = self::allText;
			}
			elseif($k1=='OCCUPATION')
			{
				$idTemp = array_search ('OCCUPATION_GROUPING',$solr_labels);
				$output["result_arr"][$i]["id"] = "$idTemp";
				$output["result_arr"][$i]["label"] = $clusterLabelMappingArray['OCCUPATION_GROUPING'];
				$output["result_arr"][$i]["stext"] = self::allText;
			}
			else
			{		
				$idTemp = array_search ($k1,$solr_labels);
				$output["result_arr"][$i]["id"] = "$idTemp";
				$output["result_arr"][$i]["label"] = $clusterLabelMappingArray[$k1];
				$output["result_arr"][$i]["stext"] = self::DONT_MATTER;
			}
			$output["result_arr"][$i]["isSlider"] = 'false';

			if($v1["Slider"]=='Show')
			{
				$output["result_arr"][$i]["isSlider"] = 'true';
				if($k1=='INCOME')
				{
                                        $lr = $SearchParamtersObj->getLINCOME();
                                        $hr = $SearchParamtersObj->getHINCOME();
                                        $ld = $SearchParamtersObj->getLINCOME_DOL();
                                        $hd = $SearchParamtersObj->getHINCOME_DOL();
                                        if($lr!='' && $hr!='')
                                                $selected[] =  html_entity_decode(FieldMap::getFieldLabel("lincome",$lr)." to ". FieldMap::getFieldLabel("hincome",$hr));
                                        else //set defalt values to show
                                        {
                                                $tempArr = self::get1stAndLastElementOfArr(FieldMap::getFieldLabel("lincome",'',1));
                                                $lr = $tempArr["first"];
                                                $tempArr = self::get1stAndLastElementOfArr(FieldMap::getFieldLabel("hincome",'',1));
                                                $hr = $tempArr["end"];
                                        }
                                        $output["result_arr"][$i]['arr2'][$j]["id"]  = "Rupee";
                                        $output["result_arr"][$i]['arr2'][$j]["min"] = $lr;
                                        $output["result_arr"][$i]['arr2'][$j]["max"] = $hr;
                                        $j++;

                                        if($ld!='' && $hd!='')
                                                $selected[] =  html_entity_decode(FieldMap::getFieldLabel("lincome_dol",$ld)." to ". FieldMap::getFieldLabel("hincome_dol",$hd));
                                        else //set defalt values to show
                                        {
                                                $tempArr = self::get1stAndLastElementOfArr(FieldMap::getFieldLabel("lincome_dol",'',1));
                                                $ld = $tempArr["first"];
                                                $tempArr = self::get1stAndLastElementOfArr(FieldMap::getFieldLabel("hincome_dol",'',1));
                                                $hd = $tempArr["end"];
                                        }
                                        $output["result_arr"][$i]['arr2'][$j]["id"]  = "Dollar";
                                        $output["result_arr"][$i]['arr2'][$j]["min"] = $ld;
                                        $output["result_arr"][$i]['arr2'][$j]["max"] = $hd;

				}
				else
				{
					if($v1[0] && $v1[1])
					{
						if($k1==='HEIGHT')
							$selected[] = html_entity_decode(FieldMap::getFieldLabel("height_without_meters",$v1[0])." to ". FieldMap::getFieldLabel("height_without_meters",$v1[1]));
						elseif($k1==='AGE')
							$selected[] = $v1[0]." to ".$v1[1];
					}
					else //set defalt values to show
					{	
						if($k1=='HEIGHT')
						{
							$tempArr = self::get1stAndLastElementOfArr(FieldMap::getFieldLabel('height_without_meters','',1));	
							$v1[0] = $tempArr["first"];
							$v1[1] = $tempArr["end"];
						}
						else
						{
							$v1[0] = $SearchParamtersObj->getGENDER()=='F'?18:21;
							$v1[1] = 70;
						}
					}
					$output["result_arr"][$i]['arr2'][$j]["min"] = $v1[0]?$v1[0]:null;
					$output["result_arr"][$i]['arr2'][$j]["max"] = $v1[1]?$v1[1]:null;
				}
				$j++;
			}
			else
			{
				foreach($v1 as $k=>$v)
				{
                                        if($k1=='EDU_LEVEL_NEW' || $k1=='OCCUPATION')
                                        {
                                                if($v[0]=='Heading')
                                                {
                                                        $parentId =  "@$v[1]";
                                                        $output["result_arr"][$i]['arr2'][$j]["id"] = $parentId;
                                                        //$output["result_arr"][$i]['arr2'][$j]["parentId"] = null;
                                                }
                                                else
                                                {
                                                        $output["result_arr"][$i]['arr2'][$j]["id"] = "$v[1]";
                                                        $output["result_arr"][$i]['arr2'][$j]["parentId"] = $parentId;
                                                }
                                        }
                                        else
                                                $output["result_arr"][$i]['arr2'][$j]["id"] = "$v[1]";

					$output["result_arr"][$i]['arr2'][$j]["label"] = htmlspecialchars_decode($k);

					if($v[0]=='Show')
						$v[0] = null;

					if($v[0]=='Heading')
					{
						$v[0] = "";
						$output["result_arr"][$i]['arr2'][$j]["isHeading"] = 'Y';
					}
					else
						$output["result_arr"][$i]['arr2'][$j]["isHeading"] = null;
					
					$output["result_arr"][$i]['arr2'][$j]["count"] = "$v[0]";
					$output["result_arr"][$i]['arr2'][$j]["isSelected"] = $v[2]=='Y'?"true":"false";
					if($v[2]=='Y')
						$selected[] = $k;
					$j++;
				}
			}
			if(is_array($selected))
				$output["result_arr"][$i]["stext"] = html_entity_decode(implode(",",$selected));
			elseif($v1["Slider"]=='Show')
				$output["result_arr"][$i]["stext"] = self::DONT_MATTER;
			unset($selected);
			$i++;
		}
		$this->output["clusters"]  = $output;
		//print_r($output);die;
	}

	/**
	* This function will give you 1st and last element of an array
	* @access public
	* @static
	* @param array $tempArr
	* @return array 
	*/
	public static function get1stAndLastElementOfArr($tempArr)
	{
		if(!is_array($tempArr))
			return NULL;
		foreach($tempArr as $k=>$v)
		{
			if(!isset($arr['first']))
				$arr["first"] = $k;
			$arr["end"] = $k;
		}
		return $arr;	
	}
}
