<?php
/**
 * API For DetailedProfile
 *
 */

/**
 * Class apidetailedv1Action represents the presentation of viewer and viewed profile.<p></p>
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    09-12-2013
 */
class apidetailedv1Action extends sfAction
{
	/**
	 * This Variable holds profile object
	 * @access public
	 * @var Object
	 */
	public $profile;

	/**
	 * This Variable holds login information
	 * @access public
	 * @var Array
	 */
	public $loginData;

	/**
	 * This Variable holds logged in profile object
	 * @access public
	 * @var Object
	 */
	public $loginProfile;

	/**
	 * This Variable holds Jpartner object
	 * @access public
	 * @var Object
	 */
	public $jpartnerObj;

	/**
	 * This Variable holds filter
	 * @access public
	 * @var string
	 */
	public $filter;

	/**
	 * This Variable holds filter btw 2 users
	 * @access public
	 * @var string
	 */
	public $filter_prof;

	/**
	 * This Variable holds about the type of contact status b/w 2 users
	 * @access public
	 * @var string
	 */
	public $contact_status;
	public $contact_status_new;

	/**
	 * This Variable holds contact object
	 * @access public
	 * @var object
	 */
	public $contactObj;

	/**
	 * This Variable holds contact engine object
	 * @access public
	 * @var object
	 */

	public $contactEngineObj;
	/**
	 * This Variable holds album output
	 * @access public
	 * @var array
	 */
	public $m_arrAlbum ;

	/**
	 * This Variable boolean value of search id expire status
	 * @access public
	 * @var boolean
	 */
	public $bFwdTo_SearchIDExpirePage = false;

	/**
     * excute Action
     * @param $request : sfWebRequest
     * @return void
     * @access public
     */
	public function execute($request)
	{
		//Contains login credentials
		$this->loginData=$request->getAttribute("loginData");

		(new ProfileCommon($this->loginData));

		$apiResponseHandlerObj=ApiResponseHandler::getInstance();

		//Contains logined Profile information;
		if($this->loginData[PROFILEID])
		{
			$this->loginProfile=LoggedInProfile::getInstance();

      if($this->loginProfile->getAGE()== "")
        $this->loginProfile->getDetail($this->loginData[PROFILEID],"PROFILEID","*");
		}
		else
			$this->loginProfile=LoggedInProfile::getInstance();

		$this->profile=$this->returnProfile();

                // VA Whitelisting
                //whiteListing of parameters
                //DetailActionLib::whiteListParams($request);

		// Do Horscope Check
		DetailActionLib::DoHorscope_Check();

		//Next Previous Calculation
		$this->HandleNextPrevious();
		$bOwnView = false;

		//TODO :In Second Phase , BreadCrump Navigation
		// If No Profile Case then Forward to No Profile API

			$x=DetailActionLib::IsNoProfile($this,"fromDetailed");

                if($request->getParameter("fromSearchByPId")){
                    $respObj = ApiResponseHandler::getInstance();
		    $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		    $successArr['USERNAME']=$this->profile->getUSERNAME();
		    $respObj->setResponseBody($successArr);
                    $respObj->generateResponse();
                    die;
                }

		//Initalize Contact Engine
		$this->Init_ContactEngineObject();

		//Update and Log
		DetailActionLib::UpdateAndLog($this);

		//For Profile Pic Response
		DetailActionLib::GetProfilePicForApi($this);

		//Get Profile Information
		DetailActionLib::GetProfileData($this);

		//Now Create OutPut Array
		$arrOut = $this->BakeMyView();
		$arrOut['USERNAME']=$this->profile->getUSERNAME();
////////////lightning cal code starts here/////////////////////////////////

// redis implementation
// 
	$request->setParameter('calFromPD',1);
	$request->setParameter('layerId',19);
	sfContext::getInstance()->getController()->getPresentationFor("common", "ApiCALayerV1");
	$layerData = ob_get_contents();
	ob_end_clean();
	$layerData = json_decode($layerData, true);
	$arrOut['calObject'] = $layerData['calObject'] ? $layerData['calObject'] : null;
///////////////////
		$respObj = ApiResponseHandler::getInstance();
		if($x)
    	{
    		$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
    		$request->setAttribute("ERROR",$x);
    	}
    	else
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);

		if($arrOut)
		{
			$respObj->setResponseBody($arrOut);
		}
		$respObj->generateResponse();
    if($request->getParameter('internal')){
      return sfView::NONE;
    }

		die;
	}

	/**
	 * Update class variable profile if profileid is passed
	 * @param $profileid Integer profileid of user
	 * @throws jsException if blank $profileid is passed
	 * @access public
	 */
	public function setViewed($profileid)
	{
		DetailActionLib::fillProfileData($profileid,$this);
	}

	/**
	 * BakeMyView
	 *
	 * Create Output Array for API Response
	 * @param void
     * @return array of response
     * @access public
	 */
	public function BakeMyView()
	{
		$request	= sfContext::getInstance()->getRequest();
		$iUpdateLogValue = $request->getParameter("ul");

		if($iUpdateLogValue ==1)//refer JsLib_Profile_Update_ViewCount
			return null;

		//Stype
		$stype = $request->getParameter("stype");
		if($stype == 17 || $stype == null)
		{
			if(MobileCommon::isAndroidApp()){
				$request->setParameter("stype","A17");
				$stype = "A17";
			}
			else if(MobileCommon::isIOSApp()){
				$request->setParameter("stype","I17");
				$stype = "I17";
			}
			else if(MobileCommon::isDesktop()){
				$request->setParameter("stype","P17");
				$stype = "P17";
			}
		}
		$this->stype = $stype;
		$this->STYPE = $stype;

		//Response Tracking
		$JSTrackingObj = new JSResponseTracking();
		$this->responseTracking = $JSTrackingObj->getProfilePageTracking($request);
		$this->profile->setNullValueMarker("");


        //Create Object as per Android or iOS App , by default consider as Android App

        $objDetailedDisplay = new DetailedViewApi($this);
        if(MobileCommon::isIOSApp() || MobileCommon::isNewMobileSite())//If iOS App Then
        {
            $objDetailedDisplay = new JsmsView($this);
        }
        if(MobileCommon::isDesktop())//If Desktop
        {
            $forEdit = $request->getParameter("forEdit");
            $objDetailedDisplay = new desktopView($this);
            if(isset($forEdit)){
              $objDetailedDisplay->setResponseForEditView($forEdit);
            }
        }
		$out = array();

        if(MobileCommon::isDesktop() && $request->getParameter('forViewProfile'))
        {
          $out =  $objDetailedDisplay->getViewProfileResponse();
        }
        else{
          $out =  $objDetailedDisplay->getResponse();
        }
		$this->profile->setNullValueMarker("");
		$arrPass = array('STYPE'=>$stype,"responseTracking"=>$this->responseTracking,'page_source'=>"VDP",'stype'=>$stype);
		if($request->getParameter('forViewProfile'))
		{
			$arrPass['page_source'] = "VDP_VSP";
		}
		$arrPass[isIgnored] = $this->IGNORED ? 1 :0;
		$out["buttonDetails"] = null;
		if($this->loginProfile->getPROFILEID() != $this->profile->getPROFILEID())
		{
			$buttonObj = new ButtonResponse($this->loginProfile,$this->profile,$arrPass);

			if(MobileCommon::isIOSApp())
				$out["buttonDetails"] = $buttonObj->getButtonArray(array('PHOTO'=>$out['pic']['url'],"IGNORED"=>$this->IGNORED));
			else
				$out["buttonDetails"] = $buttonObj->getButtonArray(array('IGNORED'=>$this->IGNORED));

		}
                if(MobileCommon::isAndroidApp()){
                    $out["checkonline"] = false;
                    if(!in_array($out["buttonDetails"]["contactType"],array('C','D')) && !$this->IGNORED && !$this->filter_prof ){
                    	if ( JsConstants::$chatOnlineFlag['profile'] )
						{
                            $out["checkonline"] = true;
						}
                    }
                }
        //this part is used to add dpp_Ticks for dppMatching on Android
        if(MobileCommon::isAndroidApp() || MobileCommon::isNewMobileSite() || MobileCommon::isIOSApp())
        {
        	$tickArr = array();

        	if($this->loginProfile->getPROFILEID())
        	{
				//Green label for desired partner profile section of viewed profile.
        		if($this->profile->getJpartner()!=null)
        		{
        			$tickArr = $this->CODEDPP=JsCommon::colorCode($this->loginProfile,$this->profile->getJpartner(),$this->casteLabel,$this->sectLabel);
        		}
        	}

			$out["dpp_Ticks"] = $this->dppMatching($out["dpp"],$tickArr);

			if($this->loginProfile->getPROFILEID())
			{
				$out["dpp_Ticks"]["matching"] = $this->getTotalAndMatchingDppCount($out["dpp_Ticks"]);
			}
        }
        //tick array part ends

        //this has been added to ensure that guna score flag for preview profile is "n"
        if($this->loginProfile->getPROFILEID() == $this->profile->getPROFILEID())
		{
			$out['show_gunascore'] = "n";
		}
		else
		{
			$out['show_gunascore'] = is_null($out['page_info']['guna_api_parmas'])? "n" :"y";
		}
		if (JsConstants::$hideUnimportantFeatureAtPeakLoad >= 4) {
			$out['show_gunascore'] = "n";
		}
                $out['show_vsp'] = true;
                if (JsConstants::$hideUnimportantFeatureAtPeakLoad >= 3) {
			$out['show_vsp'] = false;
		}
		//adding an extra flag which was in detailedAction but was missing from the api
		$out["astroSent"] = $this->checkIfAstroSent();
		return $out;
	}

	/**
	 * returnProfile
	 *
	 * Create Output Array for API Response
	 * @param void
     * @return Profile Object
     * @access private
	 */
	private function returnProfile()
	{
		$request=sfContext::getInstance()->getRequest();
		$protect_obj=$request->getAttribute("protect_obj");
		$uPID=$request->getParameter("uPID");
		$username=$request->getParameter('username');
		$username=(trim($request->getParameter('username')));

		//If coming through canonical url.
		if(!$username)
		{
			$canurl=$request->getParameter("canurl");
			if($canurl)
			{
				$arr=explode("-",$canurl);
				$username=str_replace("_____","-",$arr[count($arr)-1]);
			}

		}
		if($request->getParameter('profilechecksum'))
		{
			$profileid=JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
		}
		elseif($uPID)
		{
			if(!$request->getParameter("stype"))
				$request->setParameter("stype",10);
			$userId=substr($uPID,0,strlen($uPID)-2);
			$userName=substr($uPID,strlen($uPID)-2,1);
			$rotator=substr($uPID,strlen($uPID)-1,1);

			for($tempcnt=0;$tempcnt<strlen($userId);$tempcnt++)
			{
				$newpos=$tempcnt-$rotator;
				if($newpos<0)
					$newpos=$newpos+strlen($userId);
				else
					$newpos=$newpos;
				$userIdOrg[$newpos]=$userId{$tempcnt};
			}

			ksort($userIdOrg);

			if(count($userIdOrg)>1)
				$userProId=implode("",$userIdOrg);
			else
				$userProId=$userIdOrg[0];
			$profileid=$userProId;
		}
		elseif($username)
		{
			// $username_temp=$protect_obj->get_correct_username($username);
			// if($username_temp)
			// 	$username=$username_temp;

			//Change this later
			$profile = Profile::getInstance("newjs_masterRep");
			$profile->getDetail($username,'USERNAME',"*","RAW");
			$usernameUpper=strtoupper($username);
			if(!$profile->getPROFILEID() && $usernameUpper!=$username)
			{
				$username=$usernameUpper;
				$profile->getDetail($username,'USERNAME',"*","RAW");
			}
			//$profileid=JSCOMMON::getProfileFromUsername($username);


		}
		if($profileid)
		{
			$profile = Profile::getInstance("newjs_masterRep");
			$profile->getDetail($profileid,'PROFILEID',"*","RAW");
		}

		return $profile;
	}
	/**
	 * SetNextPreviousOffset
	 *
	 * Adjust the actual offset
	 * @param void
     * @return void
     * @access private
	 */
	private function SetNextPreviousOffset()
	{
		$request=sfContext::getInstance()->getRequest();
		$val = $request->getParameter('actual_offset');
		if(isset($val))
		{
			$request->setParameter('actual_offset',($val - 1));
            $request->setParameter('show_profile',"current");
		}
	}

	/**
	 * Init_ContactEngineObject
	 *
	 * Initalize Contact Object, Contact Engine Object
	 * @param void
     * @return void
     * @access private
	 */
	private function Init_ContactEngineObject()
	{
		if($this->loginProfile->getPROFILEID() && $this->contactObj == null && $this->contactEngineObj ==null)
		{
			$this->contactObj = new Contacts($this->loginProfile, $this->profile);
			$contactHandlerObj = new ContactHandler($this->loginProfile,$this->profile,"EOI",$this->contactObj,'',ContactHandler::PRE);
			$this->contactEngineObj=ContactFactory::event($contactHandlerObj);
		}
	}

	/**
	 * HandleNextPrevious()
	 *
	 * Handles Various Cases, Where we get profilechecksum from other services
	 * @param void
     * @return void
     * @access private
	 */
	private function HandleNextPrevious()
	{
		$request	= sfContext::getInstance()->getRequest();
		$szContactID = $request->getParameter("contact_id");
		$iTotalRecord = $request->getParameter('total_rec');
		$iOffset = $request->getParameter('actual_offset');//Offset Range from 1 to TotalRecords
		if($request->getParameter('profilechecksum'))
			return;
		if(strlen($szContactID)!=0 && $this->loginProfile->getPROFILEID() && ($iOffset+1)>0 && ($iOffset+1)<=$iTotalRecord)
		{
			$objProfileDisplay = new profileDisplay;

			// Adding +1 in offset as ProfileDisplay ID starts from 1 to total rec
			$this->profilechecksum = $objProfileDisplay->getNextPreviousProfile($this->loginProfile,$szContactID,$iOffset + 1,$request->getParameter("stype"));

			// Subtracting -1 ,as in case of else call to function ProfileCommon::showNextPrev() will need
			// offset to start from -1 And while baking response DetailedViewApi we add +1 actual_offset
			$this->actual_offset = $iOffset - 1 ;

			$this->stype=$request->getParameter("stype");
			$this->Sort=$request->getParameter("Sort");
			$this->actual_offset_real=$this->actual_offset;
			$this->total_rec=$request->getParameter("total_rec");

			//ProfileID
			$iProfileID = JsCommon::getProfileFromChecksum($this->profilechecksum);
			$this->next_prev_prof=$iProfileID;

			//Seting profile class for this profileid.
			if($this->next_prev_prof)
				$this->setViewed($this->next_prev_prof);
		}
		else
		{
			//Next Previous
			$this->SetNextPreviousOffset();
			DetailActionLib::Show_Next_Previous($this);
		}
	}

	//this function uses dpp array and tick array to make a new dppTickArray which is then added to the $out
	public function dppMatching($dppArray,$tickArray)
	{
		$dppTickArray = array();
		foreach($dppArray as $key=>$value)
		{
			$tickKey = ProfileEnums::$dppTickFields[$key];
			if($key==ProfileEnums::HAVE_CHILD_KEY)
			{
				$tickKey = "HAVECHILD";
			}
			if(strpos($dppArray["dpp_religion"],ProfileEnums::MUSLIM_NAME) !== false && $key==ProfileEnums::CASTE_KEY)
			{
				$tickKey = "SECT";
			}
			if(!in_array($key,ProfileEnums::$removeFromDppTickArr))
			{
				$dppTickArray[$key]["VALUE"] = $value;
				if($tickArray[$tickKey] && $value)
				{
					$dppTickArray[$key]["STATUS"] = $tickArray[$tickKey];
				}
			}
		}
		return $dppTickArray;
	}

	public function getTotalAndMatchingDppCount($ticksArr)
	{
		$totalCount = 0;
		$matchingCount = 0;
		$countArr = array();
		foreach($ticksArr as $key=>$value)
		{
			if($value["VALUE"])
			{
				$totalCount++;
				if($value["STATUS"] == "gnf")
				{
					$matchingCount++;
				}
			}

		}
		$countArr["totalCount"] =$totalCount;
		$countArr["matchingCount"] =$matchingCount;
		return $countArr;
	}

	public function checkIfAstroSent()
    {    	
    	$astroObj = new astroReport();
    	$flag = $astroObj->getActualReportFlag($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());					
    	if($flag)
    	{
    		return 0;
    	}
    	else
    	{
    		$count = $astroObj->getNumberOfActualReportSent($this->loginProfile->getPROFILEID());					
    		if($count >= "100")
    		{
    			return 0;
    		}
    		else
    		{
    			return 1;
    		}
    	}	
    }
}
?>
