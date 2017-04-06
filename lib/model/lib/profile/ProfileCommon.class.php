<?php
/**
 * ProfileCommon class mainly contains static function required
 * by profile module as handling legacy smarty variables, timeouts
 * dpp fetch etc.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: ProfileCommon.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
class ProfileCommon{
	public  static $ViewerProfid=0;
	public  static $ViewedProfid=0;
	
	public static function getViewed()
	{
		return $viewed;
	}
	public function __construct($loginData,$isEdit='')
	{
		include_once(sfConfig::get("sf_web_dir")."/profile/tables.php");
		global $smarty,$CALL_NOW;
		$smarty = JsCommon::getSmartySettings("web");
		//include_once(sfConfig::get("sf_web_dir")."/profile/hits.php");
		$banners=array("CHAT","SMS");
		$bannerid=rand(0,1);
		$bannerid=$banners[$bannerid];

		$smarty->assign("random_image",rand(1,9999999));
		$smarty->assign("BANNERID",$bannerid);
		$smarty->assign("LEFTBANNER",basename($_SERVER['PHP_SELF']));
		//print_r(sfContext::getInstance()->getRequest()->getRelativeUrlRoot());
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_db.php");
		include_once(sfConfig::get("sf_web_dir")."/profile/js_encryption_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_reg.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_auth.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_functions.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/common_functions.inc");
		include_once(sfConfig::get("sf_web_dir")."/classes/globalVariables.Class.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/Mysql.class.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/Memcache.class.php");
		if(!$isEdit)include_once(sfConfig::get("sf_web_dir")."/profile/contacts_functions.php");
		include_once(sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
		include_once(sfConfig::get("sf_web_dir")."/profile/commonfile.php");
		if(!$isEdit)include_once(sfConfig::get("sf_web_dir")."/profile/contact.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/horoscope_upload.inc");
		include_once(sfConfig::get("sf_web_dir")."/profile/functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
		//To include those functions are previously part to viewprofile script.
		include_once(sfConfig::get("sf_web_dir")."/profile/profileFunction.php");
		include_once(sfConfig::get("sf_web_dir")."/profile/functions_edit_dpp.php");
		
	if($loginData['PROFILEID'])
	{
		$smarty->assign("LOGIN",1);
		$smarty->assign("USERNAME",$loginData['USERNAME']);
		$smarty->assign("LOGGED_PERSON_USERNAME",$loginData['USERNAME']);
		$smarty->assign("LOGGED_PERSON_PROFILEID",$loginData['PROFILEID']);
		
	//	savesearch_onsubheader($data['PROFILEID']);
	}
		connect_db();
	}
	/**
	 * Handling legacy smarty variables.
	 * @param: $actObj Profile Profile obj.
	 */
	public static function old_smarty_assign($actObj)
	{
		if($actObj instanceof	sfAction || $actObj instanceof sfActions)
		{
			
			$var=$actObj->smarty->getTemplateVars();
			sfContext::getInstance()->getRequest()->setAttribute("BREADCRUMB",$var[BREADCRUMB]);
			//if($var[BREADCRUMB])
				//die(htmlspecialchars($var[BREADCRUMB]));
			foreach($var as $key=>$val)
			{
				$actObj->$key=$val;
			}
		}
	}
	/**
	 * Timeout page.
	 */
	public static function showTimeOut($actionObj)
	{
		if($actionObj instanceof sfAction || $actionObj instanceof sfActions)
		{
			
		//	$request=$actionObj->getRequest();
		//	$protect_obj=$request->getAttribute("protect_obj");
		//	$protect_obj->TimedOut();
		//	throw new sfStopException();
			$actionObj->forward("static","logoutPage");
		}	
	}
	/**
	 * Dpp of profile
	 * @profileid profileid of user
	 * @return $jpartnerObj jpartner of profile
	 *         null if jpartner not found.
	 */
	public static function getDpp($profileid,$type="raw",$page_source='')
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		if($type=="raw")
			$jpartnerObj=new Jpartner;
		elseif($type == "decorated")
			$jpartnerObj=new JPartnerDecorated('',$page_source);
		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");		
		$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
		$request=sfContext::getInstance()->getRequest();
		if($jpartnerObj instanceof JPartnerDecorated && !$request->getAttribute("loginData"))
		{
			$jpartnerObj->setSeoLinks();
		}	
		//if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
		return $jpartnerObj;
		//return null;
	}
	
	/**
	 * Filter paramenter of particular profile having 
	 * Jpartner data
	 * @param $jpartnerObj Jpartner profile jpartner object
	 * @return $DPP_PARAMETERS mixed partner details
	 * @throw jsException if partnerObj is null
	 */
	public static function getFilterDpp($jpartnerObj)
	{
		$DPP_PARAMETERS=array();
		if($jpartnerObj instanceof Jpartner)
		{
			$DPP_PARAMETERS["LAGE"]=$jpartnerObj->getLAGE();
			$DPP_PARAMETERS["HAGE"]=$jpartnerObj->getHAGE();

			if($jpartnerObj->getPARTNER_CASTE()!='')
			{
				$PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
				$DPP_PARAMETERS["CASTE"]=get_all_caste($PARTNER_CASTE);
			}
			if($jpartnerObj->getPARTNER_COUNTRYRES()!='')
				$DPP_PARAMETERS["COUNTRY_RES"]=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
				
			if($jpartnerObj->getPARTNER_CITYRES()!='')
			{
				$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());
				$DPP_PARAMETERS["CITY_RES"]=get_all_cities($PARTNER_CITYRES);
			}
			if($jpartnerObj->getPARTNER_MSTATUS()!="")
				$DPP_PARAMETERS["MSTATUS"]=display_format($jpartnerObj->getPARTNER_MSTATUS());
			if($jpartnerObj->getPARTNER_MTONGUE()!="")
				$DPP_PARAMETERS["MTONGUE"]=display_format($jpartnerObj->getPARTNER_MTONGUE());
			if($jpartnerObj->getPARTNER_RELIGION()!="")
				$DPP_PARAMETERS["RELIGION"]=display_format($jpartnerObj->getPARTNER_RELIGION());
			if($jpartnerObj->getPARTNER_INCOME()!="")
				$DPP_PARAMETERS["INCOME"]=display_format($jpartnerObj->getPARTNER_INCOME());
				
			if($jpartnerObj->getLINCOME()>=0 || $jpartnerObj->getLINCOME_DOL()>=0)
			{
			
				if($jpartnerObj->getLINCOME()>=0)
				{
					if($jpartnerObj->getLINCOME_DOL()>=0)
					{
						$rsArray=array('minIR'=>$jpartnerObj->getLINCOME(),'maxIR'=>19);
						$dolArray=array('minID'=>$jpartnerObj->getLINCOME_DOL(),'maxID'=>19);
					}
					else{
						$rsArray=array('minIR'=>$jpartnerObj->getLINCOME(),'maxIR'=>19);
						$dolArray=array('minID'=>null,'maxID'=>null);
					}
				}
				else
					if($jpartnerObj->getLINCOME_DOL()>=0){
						$dolArray=array('minID'=>$jpartnerObj->getLINCOME_DOL(),'maxID'=>19);
						$rsArray=array('minIR'=>null,'maxIR'=>null);
					}
			    $incomeMapObj=new IncomeMapping($rsArray,$dolArray);
				$incomes=$incomeMapObj->incomeMapping();	
				if($incomes[istr])
					$DPP_PARAMETERS["INCOME"]=explode(",",str_replace("'","",$incomes[istr]));

		}	
						
		}
		else
		{
			$ex = new sfException(sprintf(' Jpartner object is not present  %s::%s.', get_class($this), $method));
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$ex);
			throw $ex;
		}
		return $DPP_PARAMETERS;
	}
	
	/*
	 * Forward to noprofile action if error is found
	 * Filter,login,activation checks are applied here,
	 * @param $actionObj Object of action class
	 * @param $fromwhere String fromAlbum/fromDetailed
	 * 
	 * also handles no profile .
	 * 
	 */
	public static function checkViewed($actionObj,$fromWhere,$szFromWhom="")
	{
		$szActionName = sfContext::getInstance()->getActionName();
		$szFrwdTO = sfConfig::get("mod_profile_".$szActionName."_profileError");
			
		//sfWebRequest object
		$request=$actionObj->getRequest();

		
		//Viewed doesn't exist
		if($actionObj->profile==null || $actionObj->profile->getPROFILEID()==null) 	
		{
			if($request->getParameter("fromVSP")==1)
				return 1;

			if($actionObj->bFwdTo_SearchIDExpirePage === false || $actionObj->bFwdTo_SearchIDExpirePage === null)
			{
				$request->setAttribute("ERROR",1);
				$actionObj->forward("profile",$szFrwdTO);
			}
			else
			{
			
				$request->setAttribute("ERROR",10); // Search Id Expire Case
				$actionObj->forward("profile",$szFrwdTO);
			}
		}
		
		
		//Viewed profile is not activated
		if($actionObj->profile->getACTIVATED()!="Y" && self::isOwnProfile($actionObj)==false)	
		{
			
			if($request->getParameter("fromVSP")==1)
				return 2;

			$request->setAttribute("ERROR",2);
			$actionObj->forward("profile",$szFrwdTO);
		}
		
    //Check for ignore status
		DetailActionLib::IsBookmarkedOrIgnore($actionObj);
   if(isset($actionObj->IGNORED) && $actionObj->IGNORED === 2)
   {
     $ignoreType = ProfileEnums::IGNORED_BY_OTHER;
     
     $request->setAttribute("ERROR", $ignoreType);
			$actionObj->forward("profile",$szFrwdTO);
   }
    
    //Not login and profile privacy in R,F,C
		if(!$actionObj->loginProfile->getPROFILEID() && ($actionObj->profile->getPRIVACY()=="R" || $actionObj->profile->getPRIVACY()=="F" || $actionObj->profile->getPRIVACY()=="C"))	
		{
			
			if($request->getParameter("clicksource")=='photo_request')
			{
				global $smarty;
				$smarty->assign("login_mes","Please login to continue");
				ProfileCommon::showTimeOut($request);
				
			}
			if($request->getParameter("fromVSP")==1)
				return 3;
			
			$request->setAttribute("ERROR",3);
			$actionObj->forward("profile",$szFrwdTO);	
		 
		}
		
		//Same gender and privacy in F,C
		//If Not Viewing Own Profile then Check PRIVACY AND GENDER CASE
		if( self::isOwnProfile($actionObj)===false && 
            $actionObj->loginProfile->getGENDER()==$actionObj->profile->getGENDER() &&
            ( $actionObj->profile->getPRIVACY()=="F" ||
              $actionObj->profile->getPRIVACY()=="C"
            )
          ) 
		{
			$request->setAttribute("ERROR",4);
			$actionObj->forward("profile",$szFrwdTO);
		}
		
		//Privacy is set a C and not login profile.
		if($actionObj->profile->getPRIVACY()=="C") 
		{
			$showPrivacy=0;
			if($actionObj->loginProfile->getPROFILEID()==null)
			{
				$request->setAttribute("ERROR",5);
				$actionObj->forward("profile",$szFrwdTO);
			}
		}
		$actionObj->contact_status="";
		
		//Getting partner details of viewer
		$gender = $actionObj->profile->getGENDER();
		
		if($gender == 'M')
			$page_source = 'G';
		else
			$page_source = 'B';
		
            
		if(stripos($szFrwdTO,"Api") != false)
			$page_source = null;
		
		
        if(MobileCommon::isMobile())//For Removing SEO links from Mobile Site
            $jpartnerObj=ProfileCommon::getDpp($actionObj->profile->getPROFILEID(),"decorated",null);
        else
            $jpartnerObj=ProfileCommon::getDpp($actionObj->profile->getPROFILEID(),"decorated",$page_source);
		$jpartnerObj->fromPage="View";
		$actionObj->profile->setJpartner($jpartnerObj);
		unset($loginProfile);
	
		//Special check for partner handicap
		$ph_fstr=$jpartnerObj->getDecoratedHANDICAPPED();
		if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
                                $actionObj->show_nhandicap=1;
		
		//Getting privacy of filter and contact is checked
		$actionObj->filter=false;
		if($actionObj->loginProfile->getPROFILEID())
		{
			
			//Getting contact b/w 2 users.
			$contactsRecordObj = new ContactsRecords();
			$contact_status_new = $contactsRecordObj->getContactType($actionObj->loginProfile,$actionObj->profile);


			//print_r($contact_status_new);

			if($contact_status_new["R_TYPE"])
				$actionObj->contact_status = $contact_status_new["R_TYPE"];
			else
				$actionObj->contact_status = $contact_status_new["TYPE"];

			$actionObj->contact_status_new=$contact_status_new;

			if($actionObj->profile->getPROFILEID() != $actionObj->loginProfile->getPROFILEID() && $actionObj->contact_status=="" && $actionObj->profile->getPRIVACY()=="C") //Privacy is C and not contacted
			{
				$request->setAttribute("ERROR",5);
				$actionObj->forward("profile",$szFrwdTO);
			}				
			
			
			//Getting filter b/w 2 users

			if(!in_array($actionObj->contact_status,array('A','RA','RI')))
				$actionObj->FILTERED=$actionObj->FILTER=$actionObj->filter_prof=ProfileCommon::getFilters($actionObj);
			
			$request->setAttribute("contactStatus",$actionObj->contact_status);
			
            //Filtered profile and no contact
			if(self::isOwnProfile($actionObj)===false && 
               ($actionObj->contact_status=="" || $actionObj->contact_status=="N" || $actionObj->contact_status=="I") && 
               $actionObj->profile->getPRIVACY()=="F" && 
                $actionObj->filter_prof==1
              ) 
			{
				
				$request->setAttribute("ERROR",6);
				$actionObj->forward("profile",$szFrwdTO);
			}
			
			
		}
		//echo $actionObj->profile->getHAVEPHOTO();
		//Error message for profile album page
		if($fromWhere=="fromAlbum")
		{
			if($actionObj->profile->getHAVEPHOTO()=="U")
			{
				$request->setAttribute("ERROR",8);
				$actionObj->forward("profile",$szFrwdTO);
			}
			
			//Photos only to contacted profiles.[I,A]
			if($actionObj->profile->getPHOTO_DISPLAY()=="C" && !($actionObj->contact_status=="I" || $actionObj->contact_status=="A" || $actionObj->contact_status=="RA"))
			{
				$request->setAttribute("ERROR",7);
				$actionObj->forward("profile",$szFrwdTO);
			}
			
			if($actionObj->profile->getHAVEPHOTO()!="Y")
			{
				$request->setAttribute("ERROR",9);
				$actionObj->forward("profile",$szFrwdTO);
			}
			
				
		}	
		
		
		
	}
	/**
	 * returns viewer is filtered by viewed profile or not, filter
	 * is decided by filter parameters, spam logic, spam score.
	 * @param $actionObj action object
	 * @return 0#notfilter, 1#filtered
	 */
	public static function getFilters($actionObj)
	{
		$request=$actionObj->getRequest();
		
		$is_filter=0;
		
		//viewer should be login
		if( $actionObj->loginProfile->getPROFILEID()    && 
            $actionObj->profile->getPROFILEID()         && 
            self::isOwnProfile($actionObj)==false      //Should not be viewing his/her own profile
          )
		{
			
			//Getting profile is filtered or not.
			$userFilter= UserFilterCheck::getInstance($actionObj->loginProfile,$actionObj->profile);
			$is_filter=$userFilter->getFilteredContact('viewContactDetails');
			//Required by contact engine
			$actionObj->spammer=$userFilter->spammer;

			if($is_filter)
			{
				//Will check later on with manoj
				global $IVR_filtersCheck;
				$IVR_filtersCheck =1;
			}		
		}
		return $is_filter;
	}
	/**
	 * returns the dpp parameters of the viewed user
	 * @param $profileObj Profile object whos filter dpp if fetched
	 * @throws Exception when function is called without setting viewed
	 * details.
	 * @return dpp parameters of viewed user
	 */
	public static function getViewerDpp($profileObj)
	{
		$dpp_parameters=array();
		if($profileObj->getPROFILEID())
		{
			$jpartnerObj=$profileObj->getJpartner();
			
			if($jpartnerObj!=null)
			{
				$dpp_parameters=ProfileCommon::getFilterDpp($jpartnerObj);
			}
			
		}
		else
		{
			$ex = new sfException(sprintf(' No profile class object send with profileid  %s::%s.', get_class($actionObj), $method));
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $ex);
			throw $ex;
		}
		return $dpp_parameters;
	}
	/**
	 * @param $profileObj
	 * @param $userlogin
	 * @param $contact_status
	 * @param $login
	 * Return Main pic+ album count + stopAlbumView[helps in providing link on albumpage or not]
	 * 
	 */	
	public static function getprofilePicnCnt($profileObj,$contact_status,$login=0)
	{
		$ALBUM_CNT=0;
		$PHOTO="";
		$pictureServiceObj=new PictureService($profileObj);
		$pictureObj=new ScreenedPicture;
		$stopAlbumView=0;
        $album=$pictureServiceObj->getAlbum($contact_status);
        $mobile="";
	 $request=sfContext::getInstance()->getRequest();	
        if(MobileCommon::isMobile() && !$request->getParameter("pdf"))
			$mobile="mobile";
		
        
        //Album not present, but may be because of filter
        if(!$album)
        {
			$stopAlbumView=1;
			if($profileObj->getHAVEPHOTO()!="N")
			{
				if($profileObj->getHAVEPHOTO()=="U")
				{
					$PHOTO=$pictureObj->getPhotoUrlForSpecCase($profileObj,"U",$mobile);
				}
				if($profileObj->getPHOTO_DISPLAY()=="C")
				{	
					if($login==0)
					{
						 $PHOTO=$pictureObj->getPhotoUrlForSpecCase($profileObj,"NL",$mobile);
					}
					else
					{
						if(!in_array($contact_status,array('I','A','RA')))
							$PHOTO=$pictureObj->getPhotoUrlForSpecCase($profileObj,'',$mobile);
					}
				}
			
			}	
		}
		else
		{
			if(gettype($album[0])=="object")
			{
				if($mobile)
					$PHOTO=$album[0]->getProfilePicUrl();
				else	
					$PHOTO=$album[0]->getProfilePicUrl();
				$ALBUM_CNT=count($album);
			}		
		}
		
		
       
		$ret[0]=$PHOTO;
		$ret[1]=$ALBUM_CNT;
		$ret[2]=$stopAlbumView;
        return $ret;

	}
	/**
	 * @param $profileObj
	 * @param $userlogin
	 * @param $contact_status
	 * @param $login
	 * Return Main pic+ album count + stopAlbumView[helps in providing link on albumpage or not]
	 * 
	 */	
	public static function getprofilePicForApi($profileObj,$contact_status,$login=0,$bIsPhoto_Requested='')
	{								
		$ALBUM_CNT=0;
		$PHOTO="";
		$loggedInProfileObj = LoggedInProfile::getInstance();	
		if($loggedInProfileObj->getPROFILEID() == $profileObj->getPROFILEID())
		{				
			$pictureServiceObj=new PictureService($loggedInProfileObj);				
		}			
		else
		{
			$pictureServiceObj=new PictureService($profileObj);
		}
		
		$pictureObj=new ScreenedPicture;
		$stopAlbumView=0;
                if($contact_status == 'I')
                    $contact_status = 'RI';
                else if($contact_status == 'RI')
                    $contact_status = 'I';
       
        $album=$pictureServiceObj->getAlbum($contact_status);
       	//print_R($album);die;
        $mobile="";
		$request=sfContext::getInstance()->getRequest();	
        
        if(MobileCommon::isMobile() && !$request->getParameter("pdf"))
			$mobile="mobile";
		    $szThumbnailURL = null;
        //Album not present, but may be because of filter
        if(!$album)
        {
			$stopAlbumView=1;
			
			if($profileObj->getHAVEPHOTO()!="N")
			{
                                $PHOTO=$pictureServiceObj->getProfilePic($contact_status);
				if($PHOTO){
                                    if(MobileCommon::isDesktop())
					{
						$PHOTO = self::getProfilePhotoJspc($PHOTO);
					}
                                    else
					$PHOTO = $PHOTO->getMobileAppPicUrl();
                                }
			}	
		}
		else
		{
			if(gettype($album[0])=="object")
			{
                                if(MobileCommon::isDesktop())
				{
					$PHOTO = self::getProfilePhotoJspc($album[0]);
          $szThumbnailURL = $album[0]->getThumbailUrl();
          
				}
				else if($mobile)
					$PHOTO=$album[0]->getMobileAppPicUrl();
				else	
					$PHOTO=$album[0]->getMobileAppPicUrl();
					
				$ALBUM_CNT=count($album);
			}		
		}
		if(MobileCommon::isDesktop())
                    $tempArr = PictureFunctions::mapUrlToMessageInfoArr($PHOTO,"ProfilePic450Url",$bIsPhoto_Requested,$profileObj->getGENDER());
                else
	 	    $tempArr = PictureFunctions::mapUrlToMessageInfoArr($PHOTO,"MobileAppPicUrl",$bIsPhoto_Requested,$profileObj->getGENDER());
		
		$ret[3] = null;
		if(is_array($album) && gettype($album[0])=="object" && $album[0]->getMainPicUrl() != $tempArr['url'])
		{
			$ret[3] = 'true';
		}
	
		
		$ret[0]=$tempArr;
		$ret[1]=$ALBUM_CNT;
		$ret[2]=$stopAlbumView;
    $ret['THUMB_URL']=$szThumbnailURL;
        return $ret;

	}
	public static function getProfilePhotoJspc($obj)
	{
	       $photo=$obj->getProfilePic450Url();
		if(!$photo)
			$photo = $obj->getMobileAppPicUrl();
		if(!$photo || $photo== $obj->getMainPicUrl())
			$photo = $obj->getProfilePicUrl();
		if(!$photo)
			$photo = $obj->getMainPicUrl();
		return $photo;
	}
	/*
	 * Returns UI compatible username
	 * @Param $username
	 * @return String username
	 */
	public static function getTopUsername($username)
	{
		//Setting topUsername	
		

		$len=strlen($username);
		if($len>16)
			$username=substr($username,0,13)."...";
		return $username;	
	}
	/*
	 * Returns last login date in specified format
	 * @param $date date of user
	 * @return String 
	 */
	public static function getLastLoginFormat($date)
	{
		
		//Date of Birth of viewed user.
		$dob=explode("-",$date);
		$OnlineMes="";
        if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
        {
			//User last login time.
			$user_time=mktime(0,0,0,$dob[1],$dob[2],$dob[0]);
			//1 month back/
			$lastmon=mktime(0,0,0,date("m")-1,date("d"),date("Y"));
			//2 month back.
			$last2mon=mktime(0,0,0,date("m")-2,date("d"),date("Y"));
			
			if($user_time>$lastmon)
				$OnlineMes=date("jS M Y",$user_time);
			elseif($user_time<=$lastmon && $user_time>=$last2mon)	
				$OnlineMes="About 1 month ago";
			else
				$OnlineMes="More than 2 months ago";
        }
        return $OnlineMes;
	}
	
	/**
	 * Fetch which profile to show when Next/Previous link is clicked by users
	 * Currently Next/Previous option is only available if coming from detailed/contact page.
	 */
	public static function showNextPrev($actObj)
	{
			
		$request=$actObj->getRequest();
		$NAVIGATOR=$request->getParameter("NAVIGATOR");
		$j=$request->getParameter("j");
		$stype=$request->getParameter("stype");
		
		if($request->getParameter("fromPage")=='contacts')
		{

			if($request->getParameter("page") != "matches")
			{
				$other_params = 'NAVIGATOR='.$NAVIGATOR.'&j='.$j.'&stype='.$stype;
				include_once(sfConfig::get("sf_web_dir")."/profile/cmr.php");
				if(!$request->getParameter("actual_offset"))
					$request->setParameter("actual_offset",0);
				$profilechecksum = getNextProfile($request->getParameter("profileids"), $request->getParameter("total_rec"), $request->getParameter("actual_offset"), $request->getParameter("profilechecksum"), $request->getParameter("contact"), $request->getParameter("self"), $request->getParameter("self_profileid"), $request->getParameter("flag"), $request->getParameter("type"), $request->getParameter("archive"), $request->getParameter("date_search"), $request->getParameter("start_date"), $request->getParameter("end_date"), $other_params, $request->getParameter("page"));

				if($profilechecksum)
				{
					$actObj->fromPage='contacts';
					//Js authentication.. root/lib..
					$profileid=JsCommon::getProfileFromChecksum($profilechecksum);
					$actObj->setViewed($profileid);
				}
				else
				{
					ValidationHandler::getValidationHandler("","No profilechecksum found in next prev of contacts ");
				}
			}
		}
		else
		{
			
			$jc=UserSpecific::getInstance();
			
			//Object already being created
			if($jc->profilechecksum=="")
			{
				$jc->showNextPrev();
			}
			
			//Setting parameters that are required by template to show 
			//Next/Previous option to user.
			
			$actObj->searchid=$jc->searchid;
			$actObj->show_profile=$jc->show_profile;
			$actObj->stype=$jc->stype;
			$actObj->ONLINE_SEARCH=$jc->ONLINE_SEARCH;
			$actObj->Sort=$jc->Sort;
			$actObj->actual_offset=$jc->actual_offset;
			$actObj->offset=$jc->offset;
			$actObj->actual_offset_real=$jc->actual_offset_real;
			$actObj->j=$jc->j;
			$actObj->total_rec=$jc->total_rec;
			$actObj->profilechecksum=$jc->profilechecksum;
			$actObj->SHOW_NEXT_PREV=$jc->SHOW_NEXT_PREV;
			$actObj->other_params=$jc->other_params;
			// Array of Kundli matches stype as profile next prev not required
			$KundliStypeArray = array(SearchTypesEnums::KundliAlerts,SearchTypesEnums::KundliAlertsAndroid,SearchTypesEnums::KundliAlertsIOS,SearchTypesEnums::KundliAlertsJSMS);
			
			if(!in_array($actObj->stype,$KundliStypeArray))
			{
				$actObj->SHOW_PREV=$jc->SHOW_PREV;
				$actObj->SHOW_NEXT=$jc->SHOW_NEXT;
			}
			
			$actObj->next_prev_prof=$jc->next_prev_prof;
			
			//Seting profile class for this profileid.
			if($actObj->next_prev_prof)
				$actObj->setViewed($actObj->next_prev_prof);
            
			if($actObj->searchid && !is_numeric($actObj->searchid))
            {
                $actObj->searchid = null;
                $request->setParameter("searchid","");
            }
			if($jc->bIsSearchIdExpire === true)	
			{
				$actObj->bFwdTo_SearchIDExpirePage = true;
			}
		}

	}
	/**
	 * Sets common smarty assign variables.
	 * @param $actObj action object
	 * @whichPage from which action page coming
	 */
	public static function smartyAssign($actObj,$whichPage)
	{
				// Matchalert assigning//
		$request=sfContext::getInstance()->getRequest();

		//CODE ADDED BY ANAND
		if ($request->getParameter("kundli_type"))
			$actObj->KUNDLI_TYPE=$request->getParameter("kundli_type");
		else
			$actObj->KUNDLI_TYPE=0;
		//CODE ADDED BY ANAND ENDS

		$logic_used=$request->getParameter("logic_used");
		
		$recomending=$request->getParameter("recomending");
		$is_user_active=$request->getParameter("is_user_active");
		$matchalertlogin=$request->getParameter("matchalertlogin");
		if($logic_used)
		{
			$matchalert_mis_variable=$logic_used."###".$recomending."###".$is_user_active;
			$actObj->matchalert_mis_variable=$matchalert_mis_variable;
			$actObj->matchalertlogin=1;
		}
		else
			$actObj->matchalertlogin=$matchalertlogin;
		
		//Matchalert assigning ends here//
		$actObj->CLICKSOURCE=$request->getParameter("clicksource");
		if(!$request->getParameter("stype"))
			$request->setParameter("stype",17);
		
		$actObj->STYPE=$request->getParameter("stype");
		$actObj->CURRENTUSERNAME=$actObj->profile->getUSERNAME();
		
		//Added By lavesh for counting number of user that accept initial contact through suggested Profile.//
		$suggest_profile=$request->getParameter("suggest_profile");
		if($suggest_profile==1)
			$actObj->suggest_profile=1;
		//Adding ends here//
		
		$actObj->head_tab="my jeevansathi"; 
		$actObj->bottom_channel="details_bottom";
		$actObj->VIEWPROFILE="Y";
		
		//Added by lavesh-->if profile is declined from search page..(decline button should be clicked by default)	//
		
		$search_decline=$request->getParameter("search_decline");
		$CAME_FROM_CONTACT_MAIL=$request->getParameter("CAME_FROM_CONTACT_MAIL");
		$button=$request->getParameter("button");
		if($search_decline || ($CAME_FROM_CONTACT_MAIL==1 && $button=='decline'))
			$actObj->search_decline=1;
			
		if($CAME_FROM_CONTACT_MAIL==1)
		{
			if($button=="accept")
				$actObj->STATUS="A";
			elseif($button=="decline")
				$actObj->STATUS="D";
				
			$actObj->CAME_FROM_CONTACT_MAIL="1";
		}	
		//Ends here
		
		$actObj->google_ads_left="1";
		
		$actObj->data=$actObj->loginProfile->getPROFILEID();
		
		
		
		//If viewed profile exist.
		if($actObj->profile->getPROFILEID())
		{
			$actObj->sim_contact=$actObj->profile->getPROFILEID();
			$actObj->viewed_gender=$actObj->profile->getGENDER();
			$actObj->PROFILECHATID=$actObj->profile->getPROFILEID();
			$actObj->PROFILENAME=$actObj->profile->getUSERNAME();
			$actObj->GENDER=$actObj->profile->getGENDER();
			if($actObj->GENDER=='M')
				$actObj->HIMHER="Him";
			else
				$actObj->HIMHER="Her";
			$actObj->other_profileid=$actObj->profile->getPROFILEID();
			$actObj->PROFILECHECKSUM_NEW=$actObj->PROFILECHECKSUM=JSCOMMON::createChecksumForProfile($actObj->profile->getPROFILEID());
			
		}
		

		if($actObj->loginProfile->getPROFILEID())
		{
			$actObj->PERSON_LOGGED_IN="1";
		}
		
		$actObj->CHECKSUM=$request->getParameter("checksum");

	}
	/**
	 * function unset the key containing blank values
	 * @param $Arr 
	 * @return $Arr with no blank values
	 */
	public static function removeBlank($Arr)
	{
		if(is_array($Arr))
		{
			foreach($Arr as $key=>$val)
			{
				if($val!="" && $val!="-")
					$modifyArr[$key]=$val;
				else
				{
					if(!MobileCommon::isMobile())
						$modifyArr[$key]="-";
				}
			}
			return $modifyArr;
		}
	}
	/**
	 * Return annulled value if exists
	 * 
	 */
public static function getAnnulled($profileid,$mstatus)
	{
		if($mstatus=="S" || $mstatus=="M" || $mstatus=="A")
		{
			$obj=new NEWJS_ANNULLED();
			return $obj->AnnulledReason($profileid);
		}
		return '';
	}
	/**
	 * Returns trueif caste is present in following religion
	 * @param religion int 
	 * return boolean true/false
	 */
	public static function CasteAllowed($religion)
	{
		if(in_array($religion,array(1,2,3,4,9)))
			return true;
		else	
			return false;	
	}
	/**
	 * Function set the page information required by edit/profile/print
	 * @actObj Action object
	 * @profileObj profile object of whom information to be shown(need to pass because to handle screening and many more
	 */
	public static function setPageInformation($actObj,$profileObj)
	{
		//To show caste label.
		$actObj->casteLabel=JsCommon::getCasteLabel($profileObj);
		$actObj->sectLabel=JsCommon::getSectLabel($profileObj);

		$actObj->religionSelf=$profileObj->getDecoratedReligion();
		
		//About her section 
		$actObj->YOURINFO=$profileObj->getDecoratedYourInfo();
		$moreAbtArr["YOURINFO"]=$actObj->YOURINFO;
		$moreAbtArr[Family]=$profileObj->getDecoratedFamilyInfo();
	//	$moreAbtArr["Desired Partner"]=$profileObj->getDecoratedSpouseInfo();
		$moreAbtArr[Education]=$profileObj->getDecoratedEducationInfo();
		$moreAbtArr[Occupation]=$profileObj->getDecoratedJobInfo();
		$actObj->moreAboutArr=ProfileCommon::removeBlank($moreAbtArr);
                //limits changes so as to remove the read more option on values
		$InfoLimit["YOURINFO"]=5000;
		$InfoLimit["Family"]=5000;
		$InfoLimit["Education"]=1000;
		$InfoLimit["Occupation"]=1000;
		$actObj->InfoLimit=$InfoLimit;
		
		//About her section ends here.
		
		
		//Content Right to profile pic
		$len=strlen($actObj->YOURINFO);
		$limit=sfConfig::get("app_info_limit_site");
		if($len>$limit)
		{
			$actObj->FIRST_YINFO=substr($actObj->YOURINFO,0,$limit);
			$actObj->HIDE_YINFO=substr($actObj->YOURINFO,$limit,$len);
		}
		else
			$actObj->FIRST_YINFO=$actObj->YOURINFO;
			
		$actObj->TopUsername=ProfileCommon::getTopUsername($profileObj->getUSERNAME());
		
		//Basic information
		$actObj->AGE=$profileObj->getAGE();
		$actObj->HEIGHT=$profileObj->getDecoratedHeight();
		$actObj->PROFILEGENDER=$profileObj->getDecoratedGender();
		
		$actObj->MTONGUE=$profileObj->getDecoratedCommunity();
		
		
		if(ProfileCommon::CasteAllowed($profileObj->getRELIGION()))
			$actObj->CASTE=$profileObj->getDecoratedCaste();
			
		$actObj->SUBCASTE=$profileObj->getDecoratedSubcaste();
		$actObj->MSTATUS=$profileObj->getDecoratedMaritalStatus();
		$actObj->CHILDREN=$profileObj->getDecoratedHaveChild();
		$actObj->Annulled_Reason=ProfileCommon::getANNULLED($profileObj->getPROFILEID(),$profileObj->getMSTATUS());
		$actObj->EDU_LEVEL_NEW=$profileObj->getDecoratedEducation();
		$actObj->OCCUPATION=$profileObj->getDecoratedOccupation();
		$actObj->CITY_RES=$profileObj->getDecoratedCity();
		$actObj->COUNTRY_RES=$profileObj->getDecoratedCountry();
		$actObj->INCOME=$profileObj->getDecoratedIncomeLevel();
		$actObj->GOTHRA=$profileObj->getDecoratedGothra();
		
		
		$actObj->GOTHRA_MATERNAL=$profileObj->getDecoratedGothraMaternal();
		
		$actObj->RELATION=$profileObj->getDecoratedRelation();
		
    if (MobileCommon::isMobile() && !MobileCommon::isNewMobileSite())
		{
      $profileSections=new ProfileSections($profileObj);
      $actObj->lifeAttrArray=ProfileCommon::removeBlank($profileSections->getLifeAttr());
      $actObj->Hobbies=ProfileCommon::removeBlank($profileSections->getHobbies());
      $astroKundali=$profileSections->getAstroKundali();
      $astroKundali["City of Birth"]=str_replace("Delhi Paharganj,","",$astroKundali["City of Birth"]);
      if($astroKundali["Time of Birth"]=="Not Available")
        $astroKundali["Time of Birth"]="";
      $actObj->AstroKundaliArr=ProfileCommon::removeBlank($astroKundali);

      $actObj->educationAndOccArr=ProfileCommon::removeBlank($profileSections->getEducationAndOcc());
      $actObj->familyArr=ProfileCommon::removeBlank($profileSections->getFamilyDetails());
      $actObj->ReligionAndEth=ProfileCommon::removeBlank($profileSections->getRelgionAndEthnicity($actObj->casteLabel,$actObj->sectLabel));

			$mobileInfr=$moreAbtArr;
			$mobileInfr[YOURINFO]=$actObj->YOURINFO;
			$mobileInfr[SPOUSEINFO]=$profileObj->getDecoratedSpouseInfo();
			$mobileInfr[FAMILYINFO]=$moreAbtArr["Family"];
			$mobileInfr[EDUINFO]=$moreAbtArr["Education"];
			$mobileInfr[OCCINFO]=$moreAbtArr["Occupation"];			
			$actObj->MobileAbtArr=MobileCommon::getMoreAbout($mobileInfr);
			foreach($actObj->MobileAbtArr as $key=>$val)
			{
				$breaks = array("<br />","<br>","<br/>");  
				$val = str_ireplace($breaks, "\r\n", $val); 
				$actObj->MobileAbtArr[$key] =  stripcslashes($val);
			}
			if(ProfileCommon::CasteAllowed($profileObj->getRELIGION()))
				$actObj->smallCasteMobile=ltrim(FieldMap::getFieldLabel("caste_small",$profileObj->getCASTE()),"-");
			$actObj->smallMtongueMobile=FieldMap::getFieldLabel("community_small",$profileObj->getMTONGUE());
			if($profileObj->getDecoratedIncomeLevel())
				$actObj->incomeSelf=$profileObj->getDecoratedIncomeLevel();
			//$actObj->SNIP_VIEW=MobileCommon::getSnipView($profileObj);
			
			//Dpp Array Calculations:
			$actObj->dppEducationAndOccArr=ProfileCommon::removeBlank($profileSections->getDppEducationAndOcc());
			$actObj->dppReligionAndEthArr=ProfileCommon::removeBlank($profileSections->getDppReligionAndEth());
			$actObj->dpplifeAttrArr=ProfileCommon::removeBlank($profileSections->getDppLifeAttr());
			//Last Login Profile:	
			$lastLoginDate=$profileObj->getLAST_LOGIN_DT();
			if($lastLoginDate)
			{
				$lastLoginArr['LAST_LOGIN_SHOW']="Y";
				$lastLoginArr['LAST_LOGIN_DT']=$lastLoginDate;
				
			}
			else
			{
				$lastLoginArr['LAST_LOGIN_SHOW']="N";			
			}
			$actObj->lastLoginArr=$lastLoginArr;
		}
	}
	/**
	 * Sets intro call related information
	 * @param $type type of contact
	 * @param $actObj action Object
	 */
	public static function addIntroCall($type,$actObj)
	{
		if(in_array($type,array("I","A","RI","RA")))
		{
				global $jprofile_result;

				//Creating jprofile_result, required by contact engine function
				if($actObj->profile->getPROFILEID()!=null)
				$jprofile_result[viewed]=$actObj->profile->convertObjectToArray();
				if($actObj->loginProfile->getPROFILEID()!=null)	
				$jprofile_result[viewer]=$actObj->loginProfile->convertObjectToArray();
				
				off_call_history();
		}		
	}
	
	/**
	 * Users will not allowed to contact if contact limit is reached or exceed.
	 */
	public static  function contactLimitReached($actObj)
	{
		if($actObj->loginData[PROFILEID])
		{
			$limitReached=JsCommon::contactLimitReached($actObj->loginData,$actObj->contact_status);
			
			$actObj->contactLimitReached=$limitReached[0];
			$actObj->contactLimitMessage=$limitReached[1];
		}	
				
			
	}
	/**
	 * function for matchAlert Tracking For DPP 
	 * @param $sfWebRequest 	: request
	 * @param $objloginProfile 	: object of loginProfile
	 * @param $szType 			: Type of Command to perform on store objects(Insert or Update)
	 * @param $cStatus 			: Status(Enum specified in MATCHALERT_TRACKING.TRACKIN_EDIT_DPP) 
	 * @return $Arr with app requriement
	 */
	public static  function matchAlertTrackingForDPP($sfWebRequest,$objloginProfile,$szType,$cStatus)
	{
		if(($sfWebRequest->getParameter("clicksource") === "matchalert1"/*From match Alert*/ ||
		$sfWebRequest->getParameter("clicksource") === "matchal-ert1"/*Patch For RediffMail*/ ) &&
		 $objloginProfile instanceof LoggedInProfile && $objloginProfile->getPROFILEID())
		{
			$iProfileId = $objloginProfile->getPROFILEID();
			$szLogic 	= $sfWebRequest->getParameter("logic_used");
			$storeObj 	= new matchalert_tracking_track_edit_dpp;
                        if(!$szLogic)
                                return;

			if($szType === "INSERT")
			{
				$storeObj->InsertRecord($iProfileId,$cStatus,$szLogic);
			}
			if($szType === "UPDATE")
			{
				if($storeObj->UpdateRecord($iProfileId,$cStatus,$szLogic) == 0)
				{
					$storeObj->InsertRecord($iProfileId,$cStatus,$szLogic);
				}
			}
			unset($storeObj);
		}
	}
	
	/**
	 * function change the key value values for those key containing blank values
	 * @param $Arr 
	 * @return $Arr with app requriement
	 
	public static function removeApiBlank($Arr)
	{
		if(is_array($Arr))
		{
			foreach($Arr as $key=>$val)
			{
				if($val!="" && $val!="-")
					$modifyArr[$key]=$val;
				else
				{
					$modifyArr[$key]="-";
				}
			}
			return $modifyArr;
		}
	}*/
    
    public static function isOwnProfile($actionObj)
    {
        //Check is user is login or not
        if(!$actionObj->loginProfile ||
            ($actionObj->loginProfile && $actionObj->loginProfile->getPROFILEID() === null)
          )
        {
            return false;
        }
        
        //If loginProfile Object and Profile Object have same PROFILEID
        //then ownProfile
        if($actionObj->profile &&
           $actionObj->loginProfile->getPROFILEID() === $actionObj->profile->getPROFILEID()
          )
        {
            return true;
        }
        
        return false;
    }
    
    /*
     * Function To Update Profile Completion Score
     * @return void
     * @param $iProfileID : Profile Id 
     * @access Public static
     */
    public static function updateProfileCompletionScore($iProfileID)
    {
        //Compute And Store Profile Completion Score
        $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$iProfileID);
        $cScoreObject->updateProfileCompletionScore();      
        unset($cScoreObject);
    }
    
    public static function getGunaApiParams($loginProfileObj, $otherProfileObj)
    {
        $loginProfile = $loginProfileObj;
        $otherProfile = $otherProfileObj;
        
        $profileid = $loginProfile->getPROFILEID();
        $oProfile = $otherProfile->getPROFILEID();
        
        $result = null;
        
        if($otherProfile->getPROFILEID() && $loginProfile->getPROFILEID()) {
            
            $arrloginProfileData = array('GENDER'=>$loginProfile->getGENDER(),'CASTE'=>$loginProfile->getCASTE());
            $arrOtherProfileData = array('GENDER'=>$otherProfile->getGENDER(),'CASTE'=>$otherProfile->getCASTE());
            
            if($arrloginProfileData['GENDER']==$arrOtherProfileData['GENDER']) {
                $notshow = true;
            }
            
            if($arrloginProfileData['GENDER'] == 'M') {
                $gender_value = 1;
            } else {
                $gender_value = 2;
            }
            
            if($arrloginProfileData['CASTE'])
			{
				$dbObj=new NEWJS_CASTE;
				$parent=$dbObj->getParentIfSingle($arrloginProfileData['CASTE']);
			}
            
            if(in_array($parent,array(1,9,4,7)) && !$notshow)
			{
                $dbObj= ProfileAstro::getInstance();
				$dbdata=$dbObj->getAstroDetails(array(intval($profileid),intval($oProfile)),"PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL",1);
                
                if(is_array($dbdata)) {
                    foreach($dbdata as $key=>$myrow_astro)
                    {
                        $astro_pid1=$myrow_astro["PROFILEID"];
                        if($astro_pid1)
                        {
                            $lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
                            $sun=$myrow_astro['SUN_DEGREES_FULL'];
                            $mo=$myrow_astro['MOON_DEGREES_FULL'];
                            $ma=$myrow_astro['MARS_DEGREES_FULL'];
                            $me=$myrow_astro['MERCURY_DEGREES_FULL'];
                            $ju=$myrow_astro['JUPITER_DEGREES_FULL'];
                            $ve=$myrow_astro['VENUS_DEGREES_FULL'];
                            $sa=$myrow_astro['SATURN_DEGREES_FULL'];
                            $gender_val=1;
                            if($gender_value==1)
                                $gender_val=2;
                            if($astro_pid1==$profileid)
                                $logged_astro_details="$astro_pid1:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
                            else
                                $compstring="$astro_pid1:$gender_val:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa@";
                        }
                    }
                }
                
                if($logged_astro_details && $compstring) {
                    $result = $logged_astro_details."&".$compstring;
                }
            }
        }
        return $result;
    }
    
    
    //performs actions as received from mailer and outputs the button array.
    public static function performContactEngineAction($request,$pageSource=''){
        
        $request->setParameter("actionName","postAccept");
        $request->setParameter("moduleName",'contacts');
        $request->setParameter("pageSource",$pageSource);
        ob_start();
        sfContext::getInstance()->getController()->getPresentationFor("contacts", "postAcceptv2");
        ob_end_clean();
       
    }
}
?>
