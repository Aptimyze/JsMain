        <?php
/**
 * DetailedViewApi.class.php
 */

/**
 * Class DetailedViewApi Used For Decorating Response for profile
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    09-12-2013
 */
class DetailedViewApi
{
	/**
	 * This Variable holds action object of ApiDetailAction
	 * @access private
	 * @var Object
	 */
	protected	$m_actionObject;

	/**
	 * This Variable holds profile object
	 * @access private
	 * @var Object
	 */
	protected 	$m_objProfile;

	/**
	 * This Variable holds response of different section
	 * @access public
	 * @var Array
	 */
	public 		$m_arrOut;

	/**
	 * This Variable holds complied response of sections
	 * @access public
	 * @var Array
	 */
	private		$m_arrSectionOut= array(
										'about'=>array(),
										'family'=>array(),
										'lifestyle'=>array(),
										'dpp'=>array(),
										'pic'=>array(),
										'page_info'=>''
										);

  /*
   * Const Variable PROFILE_SHARE_LINK
   */
	const PROFILE_SHARE_LINK = 'PROFILE_SHARE_LINK';

  /**
	 * This Variable holds religion specific information
	 * @access protected
	 * @var Array
	 */
  protected $m_arrReligionInfo ;

	/**
     * Constructor function
     * @param $actionObject
     * @return void
     * @access public
     */
	public function __construct($actionObject)
	{
		$this->m_actionObject = $actionObject;
		if($this->m_actionObject->profile instanceof Profile)
		{
			$this->m_objProfile = $this->m_actionObject->profile;
		}
    $this->m_arrAstro = $this->m_objProfile->getAstroKundali();
    $this->m_arrReligionInfo = $this->m_objProfile->getReligionInfo();
	}

	/**
	 * getResponse()
	 *
 	 * @param void
	 * @return Decorated Detailed View as per section
	 * @access public
	 */
	public function getResponse()
	{
        $originalProflie = null;

        if($this->m_actionObject->loginProfile->getPROFILEID() == $this->m_objProfile->getPROFILEID())
        {
            $originalProflie = $this->m_objProfile;
            $this->m_objProfile = $this->m_actionObject->loginProfile;
        }

		//About Me Section
		$this->m_arrOut = array();
		$this->getDecorated_AboutMe();
		$this->getDecorated_PrimaryInfo();
		$this->getDecorated_MyEducation();
		$this->getDecorated_MyCareer();
		$this->getDecorated_AstroInfo();
		$this->getDecorated_MoreReligionInfo();
		$arrAboutSec = $this->m_arrOut;
		//Family
		$this->m_arrOut = array();
		$this->getDecorated_AboutFamily();
		$arrFamily = $this->m_arrOut;

		//LifeStyle
		$this->m_arrOut = array();
		$this->getDecorated_LifeStyle();
		$arrLifeStyle = $this->m_arrOut;

		//Profile Pic Section
		$this->m_arrOut = array();
		$this->getDecorated_Photo();
		$arrPicSection = $this->m_arrOut;

		//More Info
		$this->m_arrOut = array();
		$this->getDecorated_MoreInfo();
		$arrMoreInfo = $this->m_arrOut;

		//Decorated Data
		if ( MobileCommon::isNewMobileSite())
		{
			$this->m_arrOut = array();
			$this->getMetaTags();
			$arrMoreInfo['meta_tags'] = $this->m_arrOut;
			$thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($this->m_actionObject->THUMB_URL,'MobileAppPicUrl','',$this->m_objProfile->getGender());
			$arrMoreInfo['thumb_url'] = $thumbNailArray['url'];
//       $fromViewSimilar = $_GET['fromViewSimilar']; //added for similar profiles section on profile page
//       if($fromViewSimilar == 1)
// 				navigation("DP_NEW","",$this->m_objProfile->getUSERNAME());
// 			elseif($fromViewSimilar == 2)
// 				navigation("CVS_NEW","",$this->m_objProfile->getUSERNAME());
// 			else
// 			{
// 				if(!$_GET['NAVIGATOR'])
// 				{
// 					navigation("CVS_NEW","",$this->m_objProfile->getUSERNAME());
// 				}
// 				else
// 				{
// 					navigation("DP","",$this->m_objProfile->getUSERNAME());
// 				}
//       }
// //      ProfileCommon::old_smarty_assign($this);
//       $navFinal = sfContext::getInstance()->getRequest()->getParameter('NAVIGATOR_FOR_SYMFONY');
//       $arrMoreInfo['NAVIGATOR'] = $navFinal ? $navFinal : sfContext::getInstance()->getRequest()->getParameter('NAVIGATOR');
		}

		$this->m_arrOut = array();
		$this->getDecorated_CriticalInfo();
		$arrCriticalInfo = $this->m_arrOut;

        if($originalProflie)
            $this->m_objProfile = $originalProflie;
		//LookingFor
		$this->m_arrOut = array();
		$this->getDecorated_LookingFor();
		$arrLookingFor = $this->m_arrOut;

		// Compiling Output Section
		$this->m_arrSectionOut = array(
							'about'		=>	$arrAboutSec,
							'family'	=>	$arrFamily,
							'lifestyle'	=>	$arrLifeStyle,
							'dpp'		=>	$arrLookingFor,
							'pic'		=>	$arrPicSection,
							'page_info' =>	$arrMoreInfo,
							'Critical' =>	$arrCriticalInfo,
							);

		return $this->m_arrSectionOut;
	}


	protected function getMetaTags()
	{
		$objProfile = $this->m_objProfile;
		$viewerProfile = $this->m_actionObject->loginProfile;
		$viewedProfile = $this->m_objProfile;
		// var_dump($viewerProfile);
		// var_dump($viewedProfile);
		// var_dump($objProfile);


		//http://www.jeevansathi.com/<bride>-<mother-tongue>-<religion>-<caste>-<username/userID>-profiles
		$casteAllow=0;
		if(CommonUtility::CasteAllowed($viewedProfile->getRELIGION()))
			$casteAllow=1;
		// die(print_r(CommonUtility::CasteAllowed($viewedProfile->getRELIGION())));

		//Canonical url
		$mtongue = $viewedProfile->getMTONGUE();
		$religion = $viewedProfile->getReligion();
		$caste = $viewedProfile->getCASTE();

		$mtongue = FieldMap::getFieldLabel("community_small",$mtongue);
		$religion = FieldMap::getFieldLabel("religion",$religion);
		$caste = FieldMap::getFieldLabel("caste",$caste);
		$can_url=$mtongue."-".$religion;
		if($casteAllow)
			$can_url.="-".$caste;

		$country_res = (FieldMap::getFieldLabel("country",$viewedProfile->getCOUNTRY_RES()));
		// die($country_res);
		$city_res = (FieldMap::getFieldLabel("city",$viewedProfile->getCITY_RES()));
		// //Title
		// //strip tags check added to remove meta content in page title
		if($viewedProfile->getGOTHRA() && strip_tags($viewedProfile->getGOTHRA())!="")
			$gotra=" - ".$viewedProfile->getGOTHRA();

		if($city_res || $country_res)
		{
			$location=" - ";
			if($city_res)
				$location=$location.$city_res.", ";
			if($country_res)
				$location=$location.$country_res;

		}
		// die($city_res);
		$title=$mtongue;
		if($casteAllow)
			$title.=" - ".$caste;
		if($viewedProfile->getGOTHRA() && strip_tags($viewedProfile->getGOTHRA())!="")
			$gothra=" - ".$viewedProfile->getGOTHRA();
		$title.=" - ".$religion.$gothra;

		if($location)
			$title=$title.$location;
		$title=$title." - ".$viewedProfile->getAGE()." - ".$viewedProfile->getUSERNAME();

		$location=ltrim($location," - ");
		$desc="Looking for an ideal ".$mtongue;
		// die($caste);
		if($casteAllow)
			$desc.=" ".$caste;
		$desc.=" ".$religion;

		if($viewedProfile->getGENDER()=="M")
		{
			$whois="groom";

			$title="Groom - ".$title;
			$desc=$desc." Groom in $location";
		}
		else
		{
			$whois="bride";

			$title="Bride - ".$title;
			$desc=$desc." Bride in $location";
		}
		$desc=$desc."? Your dream Life Partner is just a click away.";
		if(!$viewerProfile)
			$desc=$desc." Log on to Jeevansathi.com Now!";

		$can_url=CommonUtility::CanonicalProfile($viewedProfile);

		$desc=htmlspecialchars_decode($desc,ENT_QUOTES);

		$keyword=$mtongue." ".ucfirst($whois)."s, ";
		if($casteAllow)
			$keyword.=$caste." ".ucfirst($whois)."s, ";

		$keyword.=$location." ".ucfirst($whois)."s, ".ucfirst($whois).", ".ucfirst($whois)."s, ".$religion." ".ucfirst($whois)."s, Life partner, find ".$whois."s, find $whois, $whois matchmaking, ".FieldMap::getFieldLabel("occupation",$viewedProfile->getOCCUPATION())." ".($whois)."s, Jeevansathi.com, Indian matrimony, matrimony, matrimonial, matrimonial";
		$keyword=htmlspecialchars_decode($keyword,ENT_QUOTES);

		$metaTags['keyword'] = $keyword;
		$metaTags['title'] = $title;
		$metaTags['title'] = $title;
		$metaTags['can_url'] = $can_url;
		$metaTags['desc'] = $desc;

		$this->m_arrOut = $metaTags;
	}

	protected function getDecorated_CriticalInfo(){
                $objProfile = $this->m_objProfile;
                $this->m_arrOut['m_status']  = $objProfile->getDecoratedMaritalStatus();
                $this->m_arrOut['age'] = $objProfile->getAGE();
                $this->m_arrOut['dtofbirth'] = "(".date("jS M Y", strtotime($objProfile->getDTOFBIRTH())).")";
        }
	/**
	 * getDecorated_AboutMe
	 *
 	 * @param void
	 * @return void , Stores Key Value Pairsof About Me Section in m_arrOut
	 * @access protected
	 */
	protected function getDecorated_AboutMe()
	{
		$objProfile = $this->m_objProfile;

		//About Me Text
        $szMyInfo = $objProfile->getDecoratedYourInfo();
        $szMyInfo = strlen($szMyInfo)?$this->DecorateOpenTextField($szMyInfo):null;
		$this->m_arrOut['myinfo'] =  $szMyInfo;

		//Appearance
		$arrAppearance =array();
		$szBTYPE = $objProfile->getDecoratedBodytype();

		if($szBTYPE && strlen(trim($szBTYPE))!=0 && $szBTYPE != ApiViewConstants::getNullValueMarker())
		{
			$szWeight = $objProfile->getDecoratedWeight();
			if($szWeight && strlen(trim($szWeight))!=0 && $szWeight != ApiViewConstants::getNullValueMarker())
			{
				$szBTYPE .= " (Weight $szWeight)";
			}
			$arrAppearance[] = $szBTYPE;
		}

		$szComplexion = $objProfile->getDecoratedComplexion();
		if($szComplexion && strlen(trim($szComplexion))!=0 && $szComplexion!= ApiViewConstants::getNullValueMarker())
		{
			$arrAppearance[] .= $szComplexion;
		}
		$szAppearance = implode(", ",$arrAppearance);
		$this->m_arrOut['appearance'] =  (strlen($szAppearance)!=0) ? $szAppearance : null ;

		// Special-Case
		$szSpecialCase = null;
		$iHandicapped = $objProfile->getHANDICAPPED();
		$arrSpecialCase = array();
		//Hiv
		if($objProfile->getHIV() && in_array(strtoupper($objProfile->getHIV()),ApiViewConstants::$YES))
		{
			$arrSpecialCase[] = ApiViewConstants::HIV;
		}
		//Handicapped
		if($iHandicapped && !in_array(strtoupper($iHandicapped),ApiViewConstants::$NO))
		{
			$szSpecialCase = $objProfile->getDecoratedHandicapped() ;
			if($objProfile->getNATURE_HANDICAP())
			{
				$szNaturehandicapped = $objProfile->getDecoratedNatureHandicap();
				$szSpecialCase .= " ($szNaturehandicapped)";
			}
			$arrSpecialCase[] = $szSpecialCase;
		}


		$szSpecialCase = implode(", ",$arrSpecialCase);
		$this->m_arrOut['special_case'] =  (strlen($szSpecialCase)!=0) ? $szSpecialCase : null;

		//Service Type
		$this->m_arrOut['subscription_icon'] = $this->getMembershipType();
		if($this->getMembershipType() == 'erishta')
		{
			$this->m_arrOut['subscription_text'] = mainMem::ERISHTA_LABEL;
		}
		elseif($this->getMembershipType() == 'evalue')
		{
			$this->m_arrOut['subscription_text'] = mainMem::EVALUE_LABEL;
		}
		elseif($this->getMembershipType() == 'jsexclusive')
		{
			$this->m_arrOut['subscription_text'] = mainMem::JSEXCLUSIVE_LABEL;
		}
		elseif($this->getMembershipType() == 'eadvantage')
		{
			$this->m_arrOut['subscription_text'] = mainMem::EADVANTAGE_LABEL;
		}else{
                        $this->m_arrOut['subscription_text'] = '';
                }
	}


	/**
	 * getDecorated_PrimaryInfo
	 *
 	 * @param void
	 * @return void , Stores Key Value Pairs of Primary Section in m_arrOut
	 * @access private
	 */
	protected function getDecorated_PrimaryInfo()
	{
		$objProfile = $this->m_objProfile;
		$viewerProfile = $this->m_actionObject->loginProfile->getPROFILEID();
		$viewedProfile = $this->m_objProfile->getPROFILEID();
		$this->m_arrOut['username'] = $objProfile->getUSERNAME();
		$this->m_arrOut['age'] = $objProfile->getAGE();
		$this->m_arrOut['height'] = html_entity_decode($objProfile->getDecoratedHeight());
		$this->m_arrOut['occupation'] = $objProfile->getDecoratedOccupation();
                $this->m_arrOut['education'] = $objProfile->getDecoratedEducation();
		$this->m_arrOut['educationOnSummary'] = $this->getAllEducationFields();

		$nameOfUserObj = new NameOfUser;
                $name = $nameOfUserObj->showNameToProfiles($this->m_actionObject->loginProfile, array($objProfile));
                if(is_array($name) && $name[$objProfile->getPROFILEID()]['SHOW']=="1" && $name[$objProfile->getPROFILEID()]['NAME']!='')
                {
                        $this->m_arrOut['name_of_user'] = $nameOfUserObj->getNameStr($name[$objProfile->getPROFILEID()]['NAME'],$this->m_actionObject->loginProfile->getSUBSCRIPTION());
                }else{
                        $this->m_arrOut['name_of_user'] = null;
                }
                unset($nameOfUserObj);
        if($objProfile->getGender() == $this->m_actionObject->loginProfile->getGender())
        	$this->m_arrOut['sameGender']=1;
    	else
        	$this->m_arrOut['sameGender']=0;
		$szInc_Lvl = $objProfile->getDecoratedIncomeLevel();
		$this->m_arrOut['income'] = (strtolower($szInc_Lvl) == "no income") ?$szInc_Lvl :($szInc_Lvl." per Annum") ;

		//COMMENTED THE PRVIOUS CODE AS A COMMON FUNCTION IS BEING USED NOW

		// if($objProfile->getDecoratedCountry()=="India" || ($objProfile->getDecoratedCountry()=="United States" && $objProfile->getDecoratedCity()!=""))
		// {
		// 	if(substr($objProfile->getCITY_RES(),2)=="OT")
		//         {
		// 		$stateLabel = FieldMap::getFieldLabel("state_india",substr($objProfile->getCITY_RES(),0,2));
		// 		$szLocation = $stateLabel."-"."Others";
		// 	}
		// 	else
		// 		$szLocation=$objProfile->getDecoratedCity();
		// }
		// else
		// 	$szLocation = $objProfile->getDecoratedCountry();

		//This is the part which was done to make Location on PD summary same as that on Search
		$city = $objProfile->getCITY_RES();
		$state = "";
		if($city != "")
		{
			$state = substr($city,0,2);
		}
		$nativePlaceObj = new JProfile_NativePlace($objProfile);
		$nativeArr = $nativePlaceObj->getInfo();
		if(is_array($nativeArr))
		{
			if($nativeArr["NATIVE_STATE"] != "")
				$state = $state.",".$nativeArr["NATIVE_STATE"];
			if($nativeArr["NATIVE_CITY"] != "")
				$city = $city.",".$nativeArr["NATIVE_CITY"];
		}
		$szLocation = CommonFunction::getResLabel($objProfile->getCOUNTRY_RES(),$state,$city,$objProfile->getANCESTRAL_ORIGIN(),"city");
		$this->m_arrOut['location'] = $szLocation;
		//Caste
                $this->m_arrOut['religionId'] = $objProfile->getReligion();
		if(stripos($objProfile->getDecoratedCaste(),": ")!=false)
		{
			$this->m_arrOut['caste'] = substr($objProfile->getDecoratedCaste(),stripos($objProfile->getDecoratedCaste(),": ") + 2);
		}
		elseif($objProfile->getCASTE() == 162 && $objProfile->getDecoratedCaste() == "No Religion/Caste")
		{
			$this->m_arrOut['caste'] = "No Religion";
		}
		else
		{
			$this->m_arrOut['caste'] = $objProfile->getDecoratedCaste();
		}

                if($objProfile->getReligion() == 2 && $objProfile->getCaste() == 152){
                    $religionInfo = (array)$objProfile->getReligionInfo();
                    if($religionInfo["JAMAAT"])
                        $this->m_arrOut['caste'] = $this->m_arrOut['caste'].", ".$religionInfo["JAMAAT"];
                }
		//Caste End Here
                if($this->m_actionObject->ISONLINE && MobileCommon::isDesktop())
                    $this->m_arrOut['last_active'] = "Online now";
                else
                    $this->m_arrOut['last_active'] = "Last Online ".CommonUtility::convertDateToISTDay($objProfile->getLAST_LOGIN_DT());

        $mtongue = $objProfile->getMTONGUE();
        $communityLabel = FieldMap::getFieldLabel("community_small",$mtongue);
		$this->m_arrOut['mtongue'] = $communityLabel;

        $this->m_arrOut['gender'] = $objProfile->getDecoratedGender();
		$this->m_arrOut['m_status']  = $objProfile->getDecoratedMaritalStatus();
                if( $objProfile->getMSTATUS() != "N")
                    $this->m_arrOut['have_child']  = ApiViewConstants::$hasChildren[$objProfile->getHAVECHILD()];

		$bHoroScope = $objProfile->getSHOW_HOROSCOPE();
    if($bHoroScope === 'D'){
      $this->m_arrOut['toShowHoroscope']  = $bHoroScope;
    }
    else{
        $astroArr = (array)$this->m_arrAstro;
        $this->m_arrOut['astro_date'] = $astroArr['dateOfBirth'];
        $this->m_arrOut['astro_time'] = $astroArr['birthTimeHour']." hrs:".$astroArr['birthTimeMin']." mins";
        $this->m_arrOut['astro_sunsign'] = $astroArr['sunsign'];
        $this->m_arrOut['astro_time_check'] = $astroArr['birthTimeHour'];
        $this->m_arrOut['rashi'] = $astroArr['rashi'];
        $cManglik = CommonFunction::setManglikWithoutDontKnow($this->m_objProfile->getMANGLIK());
        $szManglik = ApiViewConstants::getManglikLabel($cManglik);
        $this->m_arrOut['astro_manglik'] = $szManglik;
        $this->m_arrOut['toShowHoroscope']  = $bHoroScope;
        $horoscope = new Horoscope;
        if($viewerProfile){
          $this->m_arrOut['myHoroscope'] = $horoscope->ifHoroscopePresent($viewerProfile);
          $this->m_arrOut['requestedHoroscope'] = $horoscope->ifHoroscopeRequested((array)$viewerProfile,$viewedProfile,1)[$viewerProfile];
        }
        $this->m_arrOut['othersHoroscope'] = $this->getHoroscopeExist();
    }
     $subscriptionData = $this->m_actionObject->loginProfile->getSUBSCRIPTION();
        if(!strstr($subscriptionData,'A'))
            $this->m_arrOut['COMPATIBILITY_SUBSCRIPTION']='N';
        else
            $this->m_arrOut['COMPATIBILITY_SUBSCRIPTION']='Y';

        if($subscriptionData)
            $this->m_arrOut['paidMem']='Y';
        else
            $this->m_arrOut['paidMem']='N';

        if ($this->m_arrOut['myHoroscope']=='Y' && $this->m_arrOut['othersHoroscope']=='Y')
                $this->m_arrOut['NO_ASTRO']=0;
            else
                $this->m_arrOut['NO_ASTRO']=1;

        //condition change for astro report to be shown
        if($bHoroScope === 'D' && $this->m_arrOut['NO_ASTRO'] == 1)
        {
            $this->m_arrOut['NO_ASTRO']=0;
        }
        if(MobileCommon::isAndroidApp() || MobileCommon::isNewMobileSite())
        {
            $this->m_arrOut['thumbnailPic'] = null;
            $havePhoto=$this->m_objProfile->getHAVEPHOTO();
            if($havePhoto=='Y')
            {
            	if($this->m_actionObject->THUMB_URL)
            	{
            		$thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($this->m_actionObject->THUMB_URL,'ThumbailUrl','',$this->m_objProfile->getGender());
            		$this->m_arrOut['thumbnailPic'] = $thumbNailArray['url'];
            	}
            	if($this->m_actionObject->PIC120_URL)
            	{
            		$pic120Array = PictureFunctions::mapUrlToMessageInfoArr($this->m_actionObject->PIC120_URL,'ProfilePic120Url','',$this->m_objProfile->getGender());
            		$this->m_arrOut['profilePic120Url'] = $pic120Array['url'];
            	}
            }

            //thumbnail for self
            if($viewerProfile)
            {
            	$selfHavePhoto = $this->m_actionObject->loginProfile->getHAVEPHOTO();
            	if(!$selfHavePhoto)
            		$selfHavePhoto = "N";
            	if($selfHavePhoto != "N")
            	{
            		$pictureServiceObj=new PictureService($this->m_actionObject->loginProfile);
            		$ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
            		$this->ProfilePicUrl='';
            		if (is_subclass_of($ProfilePicUrlObj, 'Picture'))
            		{
            			$this->profilePicPictureId = $ProfilePicUrlObj->getPICTUREID();
            			$this->thumbnailPic = $ProfilePicUrlObj->getThumbailUrl();
            		}
            	}
            	else
            	{
            		$this->thumbnailPic = PictureService::getRequestOrNoPhotoUrl('noPhoto', "ThumbailUrl", $this->m_actionObject->loginProfile->getGENDER());
            	}
            	$this->m_arrOut["selfThumbail"] = $this->thumbnailPic;
            }
        }
	}

	/**
	 * getDecorated_MyEducation
	 *
 	 * @param void
	 * @return void , Stores Key Value Pairs of Education Section in m_arrOut
	 * @access protected
	 */
	protected function getDecorated_MyEducation()
	{

		$objProfile = $this->m_objProfile;

		//My Education Open Field
		$szAboutMyEdu = $this->DecorateOpenTextField($objProfile->getDecoratedEducationInfo());

		if(strlen($szAboutMyEdu)==0)
			$szAboutMyEdu = null;

		$this->m_arrOut['myedu'] = $szAboutMyEdu;
		//PG Degree
		$objEducation = $this->m_objProfile->getEducationDetail();

		$iHighestDegree = $objProfile->getEDU_LEVEL_NEW();
		$arrEduLabels = FieldMap::getFieldLabel("education",'',1);

		//List of Master Degree
		$arrPG_Degree = FieldMap::getFieldLabel("degree_grouping",'',1);
		$arrPG_Group = $arrPG_Degree['PG'];
		$arrUG_Group = $arrPG_Degree['UG'];
		$arrPG_Group = explode(" , ",$arrPG_Group);
		$arrUG_Group = explode(" , ",$arrUG_Group);

		foreach($arrPG_Group as $key=>$val)
		{
			$arrPG_Group[$key] = trim($val);
		}
		//$arrPG_Group[$key+1] = '22';// For Others

		foreach($arrUG_Group as $key=>$val)
		{
			$arrUG_Group[$key] = trim($val);
		}

		$arrPGOut = array('deg'=>null,'name'=>null);

		//PG Degree And College Name
		if(in_array($iHighestDegree,$arrPG_Group))
		{

			if($objEducation->PG_DEGREE != $objEducation->nullValueMarker)
			{
				$arrPGOut['deg'] = $objEducation->PG_DEGREE;
			}
			else
			{
				$arrPGOut['deg'] = $objProfile->getDecoratedEducation();
			}

			if($objEducation->PG_COLLEGE != $objEducation->nullValueMarker)
			{
				$arrPGOut['name'] = $objEducation->PG_COLLEGE;
			}
		}
		else
		{
			$arrPGOut = null;
		}

		$this->m_arrOut['post_grad'] = $arrPGOut;
		$NonGradDegree = 0;
		//UG Degree and Colg name
		$arrUGOut = array('deg'=>null,'name'=>null);
		if(in_array($iHighestDegree,$arrUG_Group) || in_array($iHighestDegree,$arrPG_Group))
		{
			if($objEducation->UG_DEGREE != $objEducation->nullValueMarker)
			{
				$arrUGOut['deg'] = $objEducation->UG_DEGREE;
			}
			else if(in_array($iHighestDegree,$arrUG_Group))
			{
				$arrUGOut['deg'] = $objProfile->getDecoratedEducation();
			}

			if($objEducation->COLLEGE != $objEducation->nullValueMarker)
			{
				$arrUGOut['name'] = $objEducation->COLLEGE;
			}
		}
		else
		{
			$arrUGOut = null;
			$NonGradDegree = 1;
		}

                $this->m_arrOut['college'] = $objProfile->getCOLLEGE();
                $this->m_arrOut['pg_college'] = $objProfile->getPG_COLLEGE();
		$this->m_arrOut['under_grad'] = $arrUGOut;
		$this->m_arrOut['non_grad'] = $NonGradDegree;
		//School
		$this->m_arrOut['school'] = null;
		if($objEducation->SCHOOL != $objEducation->nullValueMarker)
			$this->m_arrOut['school'] = $objEducation->SCHOOL;

		//verification seal
		$verificationSealObj=new VerificationSealLib($objProfile,'1');
   		$this->m_arrOut['verification_status']=$verificationSealObj->getFsoStatus();
   		unset($verificationSealObj);

   		//adding aadhar verification part
                $aadharObj = new aadharVerification();
                $aadharArr = $aadharObj->getAadharDetails($this->m_actionObject->profile->getPROFILEID());
                unset($aadharObj);
                if($this->m_arrOut['verification_status'] && $aadharArr[$this->m_actionObject->profile->getPROFILEID()]["VERIFY_STATUS"] == "Y")
                {
                        $this->m_arrOut['complete_verification_status'] = 3; //this indicates that both are verified.
                }
                elseif($aadharArr[$this->m_actionObject->profile->getPROFILEID()]["VERIFY_STATUS"] == "Y")
                {
                    $this->m_arrOut['complete_verification_status'] = 2; //this indicates aadhar is verified.
                }
                else
                    $this->m_arrOut['complete_verification_status'] = $this->m_arrOut['verification_status'];
	}

	/**
	 * getDecorated_MyCareer
	 *
 	 * @param void
	 * @return void , Stores Key Value Pairs of Career Section in m_arrOut
	 * @access private
	 */
	protected function getDecorated_MyCareer()
	{
		$objProfile = $this->m_objProfile;

		// My Career Open Text Field
		$szStr = $this->DecorateOpenTextField($objProfile->getDecoratedJobInfo());
		$this->m_arrOut['mycareer'] = $szStr ==="" ? null : $szStr ;

		//Work Status
		//List Of Occuption Allowd as Currently Label
		$iOccu = $objProfile->getOCCUPATION();
		$arrWorkInfo = array('label'=>null,'value'=>null,'company'=>null);

		$arrWorkInfo['label'] = "Employed as";
		if($iOccu && in_array($iOccu,ApiViewConstants::$arrOccAllowed))
		{
			$arrWorkInfo['label'] = "Currently";
		}

		if($iOccu && strlen(trim($iOccu))!=0 && $iOccu!= ApiViewConstants::getNullValueMarker())
		{
			$arrWorkInfo['value'] = $objProfile->getDecoratedOccupation();
		}

		if($objProfile->getDecoratedCompany() && $objProfile->getDecoratedCompany() != ApiViewConstants::getNullValueMarker())
		{
			$arrWorkInfo['company'] = $objProfile->getDecoratedCompany();
		}

		if($arrWorkInfo['company'] == null && $arrWorkInfo['label'] == null && $arrWorkInfo['value'] == null )
			$arrWorkInfo = null;

		$this->m_arrOut['work_status'] = $arrWorkInfo;
		$this->m_arrOut['company_name'] = $objProfile->getCOMPANY_NAME() ?"Works at ".$objProfile->getCOMPANY_NAME():"";
		//Earnings
		$this->m_arrOut['earning'] = null;
		if(($szInc_Lvl = $objProfile->getDecoratedIncomeLevel()) != ApiViewConstants::getNullValueMarker())
		{
			$this->m_arrOut['earning'] = (strtolower($szInc_Lvl) == "no income") ?$szInc_Lvl :($szInc_Lvl." per Annum") ;
		}

		//Plan to Work After marriage
		$szPlan = null;

		if($objProfile->getMARRIED_WORKING())
		{
			$szPlan = ApiViewConstants::$arrPlan[$objProfile->getDecoratedCareerAfterMarriage()];
		}
		$this->m_arrOut['plan_to_work'] = $szPlan;

		//Settling Abroad
		$szAbroad = null;
		if($objProfile->getGOING_ABROAD())
		{
			$szAbroad = ApiViewConstants::$arrSettling_Abroad[$objProfile->getDecoratedSettlingAbroad()];
		}
		$this->m_arrOut['abroad'] = $szAbroad;
	}

	/**
	 * getDecorated_AstroInfo
	 *
 	 * @param void
	 * @return void , Stores Key Value Pairs of Career Section in m_arrOut
	 * @access private
	 */
	protected function getDecorated_AstroInfo()
	{
		$objProfile = $this->m_objProfile;

		//Posted By
		$szPosted = "";
		$szRelation = $objProfile->getDecoratedRelation();
		$szPH_Name = $objProfile->getDecoratedPersonHandlingProfile();
		if($szRelation)
		{
			$szPosted = ApiViewConstants::$arrPostedBy[$objProfile->getGENDER()] . " $szRelation";
//			if(strlen($szPH_Name)!=0 && $szPH_Name != ApiViewConstants::getNullValueMarker())
//				$szPosted .= " ($szPH_Name)";
		}

		if($szPosted == "")
			$szPosted = null;
		$this->m_arrOut['posted_by'] = $szPosted;

		//Astro privacy Settings
		$bHoroScope = $objProfile->getSHOW_HOROSCOPE();

		if($bHoroScope ===  'D')
		{
			$this->m_arrOut['city_country'] = null;
			$this->m_arrOut['date_time'] = null;
			$this->m_arrOut['more_astro'] = null;
			return;
		}

		//City And Country of Birth
		$arrCity_Country = array();

		if($objProfile->getCITY_BIRTH())
		{
			$arrCity_Country[] = $objProfile->getDecoratedBirthCity();
		}

		if($objProfile->getCOUNTRY_BIRTH())
		{
			$arrCity_Country[] = $objProfile->getDecoratedBirthCountry();
		}

		$szStr = implode(", ",$arrCity_Country);
		$this->m_arrOut['city_country'] = (strlen($szStr) === 0 )? null : $szStr ;

		//Date And Time Of Birth
		$arrAstroKundali = $this->m_arrAstro;

		$szDate_Time = null;
		if($arrAstroKundali->dateOfBirth !="")
			$szDate_Time .= $arrAstroKundali->dateOfBirth;

		if($arrAstroKundali->dateOfBirth !="" && ($arrAstroKundali->birthTime !="" && strtolower($arrAstroKundali->birthTime) !="not available"))
			$szDate_Time .=	"-";

		if($arrAstroKundali->birthTime !="" && strtolower($arrAstroKundali->birthTime) !="not available")
			$szDate_Time .=	$arrAstroKundali->birthTime;

		$this->m_arrOut['date_time'] = $szDate_Time;

		// More About Astro
		$arrMoreAstro = array('rashi'=>null,'nakshatra'=>null,'horo_match'=>null,'astro_privacy'=>null);
		$cManglik = $cManglik = CommonFunction::setManglikWithoutDontKnow($objProfile->getMANGLIK());

		$szManglik = ApiViewConstants::getManglikLabel($cManglik);

		if($arrAstroKundali->rashi && $arrAstroKundali->rashi != ApiViewConstants::getNullValueMarker())
		{
			$arrMoreAstro['rashi'] = $arrAstroKundali->rashi . " Rashi";
			if($szManglik)
				$arrMoreAstro['rashi'] .= " ($szManglik)";
		}
		else if($szManglik)
		{
			$arrMoreAstro['rashi'] = $szManglik;
		}

		if($arrAstroKundali->nakshatra && $arrAstroKundali->nakshatra != ApiViewConstants::getNullValueMarker())
		{
			$arrMoreAstro['nakshatra'] = $arrAstroKundali->nakshatra . " Nakshatra";
		}

		$szHoro = null;
		if($objProfile->getHOROSCOPE_MATCH())
			$szHoro = ApiViewConstants::$arrHoroScope_Required[$objProfile->getHOROSCOPE_MATCH()] ;
		$arrMoreAstro['horo_match'] = $szHoro;

		if($arrMoreAstro['horo_match'] == null && $arrMoreAstro['rashi'] == null && $arrMoreAstro['nakshatra'] == null)
			$arrMoreAstro = null;

        $arrMoreAstro['astro_privacy'] = FieldMap::getFieldLabel("astro_privacy_label", $objProfile->getSHOW_HOROSCOPE());
		$this->m_arrOut['more_astro'] = $arrMoreAstro;
	}

	/**
	 * getDecorated_MoreReligionInfo
	 * if Muslim , Sikh Or Christian , then providing more info
 	 * @param void
	 * @return void , Stores Key Value Pair of Religions
	 * @access private
	 */
	protected function getDecorated_MoreReligionInfo()
	{
		$objProfile = $this->m_objProfile;
    $oldNullValueMarker = $objProfile->getNullValueMarker();
		$objProfile->setNullValueMarker("");

		$iReligion = $objProfile->getRELIGION();
		$arrReligionInfo = $this->m_arrReligionInfo;
		$arrReligionInfo = (array) $arrReligionInfo;

		$this->m_arrOut['muslim_m'] = null;
		$this->m_arrOut['christian_m'] = null;
		$this->m_arrOut['sikh_m'] = null;

		switch($iReligion)
		{
			case 2:// Muslim
			{
				foreach(ApiViewConstants::$arrMulsim_key as $key => $val)
				{
					if(array_key_exists($val,$arrReligionInfo) && $arrReligionInfo[$val])
						$arrMoreInfo[strtolower($val)] = $arrReligionInfo[$val];
				}
				if(isset($arrMoreInfo['hijab_marriage']))
				{
					$arrMoreInfo['hijab'] = $arrMoreInfo['hijab_marriage'];
					unset($arrMoreInfo['hijab_marriage']);
				}

				if($arrMoreInfo['working_marriage'] == "Yes")
					$arrMoreInfo['working_marriage'] = "The girl can work after marriage";

				if($objProfile->getGENDER() == 'F' && isset($arrMoreInfo['working_marriage']))
					unset($arrMoreInfo['working_marriage']);
				$this->m_arrOut['muslim_m'] = $arrMoreInfo;
			}
			break;
			case 3://Christian
			{
				$szChristianInfo = null;

				$arrChristianInfo = array();

				foreach(ApiViewConstants::$arrChristian_Key as $key => $val)
				{
					$value = ApiViewConstants::$arrChristian[$val][$arrReligionInfo[$val]];
					if($arrReligionInfo[$val] && $value)
					{
						$arrChristianInfo[] = $value;
					}
				}

				$szChristianInfo = implode(", ",$arrChristianInfo);

				if($szChristianInfo == "")
					$szChristianInfo = null;

				$this->m_arrOut['christian_m'] = $szChristianInfo;
			}
			break;
			case 4://Sikh
			{
				$szSikhInfo = "";
				$arrSikhInfo = array();
				if(in_array(strtoupper($arrReligionInfo['AMRITDHARI']),ApiViewConstants::$NO))
				foreach(ApiViewConstants::$arrSikh_Key as $key => $val)
				{
					$szStr = $arrReligionInfo[$val];

					if($szStr != '')
						$value = ApiViewConstants::$arrSikh[$val][$szStr];

					if($arrReligionInfo[$val] && $value)
					{
						if($val != 'CUT_HAIR' && $objProfile->getGENDER() == 'F')
						{
							continue; // For Male Only Check WEAR_TURBAN And CLEAN_SHAVEN Case
						}
						$arrSikhInfo[] = $value;
					}
				}

				$szSikhInfo = implode(", ",$arrSikhInfo);

				if($szSikhInfo == "")
					$szSikhInfo = null;

				$this->m_arrOut['sikh_m'] = $szSikhInfo;
			}
			break;
		}//End of Switch
		$objProfile->setNullValueMarker($oldNullValueMarker);

	}

	/**
	 * getDecorated_AboutFamily
	 *
 	 * @param void
	 * @return void
	 * @access protected
	 */
	protected function getDecorated_AboutFamily()
	{

		$objProfile = $this->m_objProfile;

		$this->m_arrOut['myfamily'] = null;
		if($objProfile->getDecoratedFamilyInfo())
		{
			$this->m_arrOut['myfamily'] = $this->DecorateOpenTextField($this->m_objProfile->getDecoratedFamilyInfo());
		}

		// Family Background
		$szFamilyBG = null;
		$arrTemp = array();

		foreach(ApiViewConstants::$arrFamilyBG as $key => $val)
		{
			$value =  $objProfile->$val();
			if($value)
				$arrTemp[] = $value;
		}

		$szFamilyBG = implode(", ",$arrTemp);
		if($szFamilyBG == "")
			$szFamilyBG = null;

		$this->m_arrOut['family_bg'] = $szFamilyBG;

		//Family Income
		$this->m_arrOut['family_income'] = null;
		$szFamilyIncome = $objProfile->getDecoratedFamilyIncome();
		if($objProfile->getFAMILY_INCOME() && $objProfile->getDecoratedFamilyIncome()!=ApiViewConstants::getNullValueMarker() && $szFamilyIncome)
		{
			$this->m_arrOut['family_income'] = (strtolower($szFamilyIncome) == "no income") ?$szFamilyIncome :($szFamilyIncome." per Annum") ;
		}

		//Father Occ
		$this->m_arrOut['father_occ'] = null;
		if($objProfile->getDecoratedFamilyBackground())
		{
			//$this->m_arrOut['father_occ'] = ApiViewConstants::$arrFatherOcc_Mapping[$objProfile->getFAMILY_BACK()];
			$this->m_arrOut['father_occ']= $objProfile->getDecoratedFamilyBackground();
		}

		//Mother Occ
		$this->m_arrOut['mother_occ'] = null;
		if($objProfile->getMOTHER_OCC())
		{
			//$this->m_arrOut['mother_occ'] = ApiViewConstants::$arrMotherOcc_Mapping[$objProfile->getMOTHER_OCC()];
			$this->m_arrOut['mother_occ'] = $objProfile->getDecoratedMotherOccupation();
		}

		//Sibling Info
		$this->m_arrOut['sibling_info'] = null;

		$numBrother = $objProfile->getT_BROTHER();
		$numSister = $objProfile->getT_SISTER();
		$numMBrother = $objProfile->getM_BROTHER();
		$numMSister = $objProfile->getM_SISTER();

		if(strlen($numBrother) === 0 && strlen($numSister) === 0)
		{
			$szSiblingInfo = null;
		}
		else if(strlen($numBrother) >0 || strlen($numSister) >0)
		{
			if(strlen($numSister) >0 && strlen($numBrother) >0 && intval($numBrother) === 0 && intval($numSister) === 0)
			{
				$szSiblingInfo ="No Brother or Sister";
			}
			else
			{
				$numBrother = $numBrother > 3 ? "3+" : $numBrother;
				$numSister = $numSister > 3 ? "3+" : $numSister;

				$numMBrother = $numMBrother > $numBrother ? $numBrother : $numMBrother;
				$numMSister = $numMSister > $numSister ? $numSister : $numMSister;

				if(strlen($numMBrother) > 0)
				{
					if($numMBrother == 0)
						$numMBrother = "none";
					else
						$numMBrother = $numMBrother > 3 ? "3+" : $numMBrother;

					$szMarriedBrother = "of which " . $numMBrother ." married.";
				}
				else
				{
					$szMarriedBrother = "";
				}

				if(strlen($numMSister) > 0)
				{
					if($numMSister == 0)
						$numMSister = "none";
					else
						$numMSister = $numMSister > 3 ? "3+" : $numMSister;

					$szMarriedSister = "of which " . $numMSister ." married.";
				}
				else
				{
					$szMarriedSister = "";
				}

				if($numBrother > 1 )
					$szTempBrother = " brothers ";
				else if($numBrother == 1)
					$szTempBrother = " brother ";
				else
				{
					$numBrother = "";
					$szTempBrother = "";
				}

				if($numSister > 1 )
					$szTempSister = " sisters ";
				else if($numSister == 1)
					$szTempSister = " sister ";
				else
				{
					$szTempSister = "";
					$numSister = "";
				}


				$szBrotherStr = $numBrother . $szTempBrother . $szMarriedBrother;
				$szSisterStr  = $numSister . $szTempSister . $szMarriedSister;

				if(strlen($szBrotherStr) && $numBrother > 0)
				{
					$arrSiblingInfo[] = $szBrotherStr;
				}

				if(strlen($szSisterStr) && $numSister > 0)
				{
					$arrSiblingInfo[] = $szSisterStr;
				}

				$szSiblingInfo = implode(" \n",$arrSiblingInfo);
				if(strlen($szSiblingInfo) == 0)
					$szSiblingInfo = null;
			}
		}
		$this->m_arrOut['sibling_info'] = $szSiblingInfo;
		//Sub Caste
		$this->m_arrOut['sub_caste'] = null;
		if($objProfile->getSUBCASTE())
		{
			$this->m_arrOut['sub_caste'] = $objProfile->getDecoratedSubcaste();
		}

		$iReligion = $objProfile->getRELIGION();
		//Gothra

		$this->m_arrOut['gothra'] = null;
		//Gothra Allowd for Hindu,Sikh,Buddhist and Jain Only
		$arrGothraAllowedFor = array(1,4,7,9);
		if($objProfile->getGOTHRA() && in_array($iReligion,$arrGothraAllowedFor))
		{
			$this->m_arrOut['gothra'] = $objProfile->getDecoratedGothra();
		}

		//Native Place
		$this->m_arrOut['native_place'] = null;
		$nativePlaceObj = new JProfile_NativePlace($objProfile);
		$nativePlaceObj->getInfo();
		if($nativePlaceObj->getDecorated_ViewField() && $nativePlaceObj->getDecorated_ViewField()!=ApiViewConstants::getNullValueMarker())
		{
			$this->m_arrOut['native_place'] = $nativePlaceObj->getDecorated_ViewField();
		}

		// Caste - Muslim,Christian,Sikh
		$arrAllowedCaste = array(2,3);

		$arrReligionInfo = $this->m_arrReligionInfo;

		$this->m_arrOut['caste'] = null;
		if(in_array($iReligion,$arrAllowedCaste))
		{
			if($objProfile->getCASTE())
			{
				$this->m_arrOut['caste'] = $objProfile->getDecoratedSect();//Show Sect under caste label
			}
		}


		if($iReligion == 2) /*Muslim*/
		{
			$arrMuslimData = $this->m_arrReligionInfo;
			$this->m_arrOut['mathab'] = null;
			if($arrMuslimData->MATHTHAB)
			{
				$this->m_arrOut['mathab'] = $arrMuslimData->MATHTHAB;
			}
		}

		if($iReligion == 3) /*Christian*/
		{
			$arrChristianData = $this->m_arrReligionInfo;
			$this->m_arrOut['diocese'] = null;
			if($arrChristianData->DIOCESE)
			{
				$this->m_arrOut['diocese'] = $arrChristianData->DIOCESE;
			}
		}

		if($iReligion == 4) /*Sikh*/
		{
			$this->m_arrOut['sect'] = null;
			if($objProfile->getSECT())
			{
				$this->m_arrOut['sect'] = $objProfile->getDecoratedSect();
			}
		}

		//Live With parents
		$szValue = $objProfile->getDecoratedLiveWithParents();
		$this->m_arrOut['living'] = ApiViewConstants::$arrLivingStatus[$szValue];

	}

	/**
	 * getDecorated_LifeStyle
	 *
 	 * @param void
	 * @return void
	 * @access protected
	 */
	protected function getDecorated_LifeStyle()
	{
		$objProfile = $this->m_objProfile;

		//LifeStyle
		$szLifeStyle = "";

		$arrTemp = array();
		$arrTemp1 = array();

		//Diet
		if(strlen($objProfile->getDIET())!=0 && $objProfile->getDIET() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp1[] = $objProfile->getDecoratedDiet();
		}

		//Drink
		if(strlen($objProfile->getDRINK())!=0 && $objProfile->getDRINK() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp1[] = ApiViewConstants::$arrDrinkLabel[$objProfile->getDRINK()];
		}

		//Smoke
		if(strlen($objProfile->getSMOKE())!=0 && $objProfile->getSMOKE() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp1[] = ApiViewConstants::$arrSmokeLabel[$objProfile->getSMOKE()];
		}
		if(is_array($arrTemp1) && count($arrTemp1))
		{
			$arrTemp[] = implode(", ",$arrTemp1);
		}
		unset($arrTemp1);
		//Residential status in Country
		if($objProfile->getDecoratedCountry() && $objProfile->getDecoratedCountry() != "India" && $objProfile->getDecoratedRstatus() )
		{
			$arrTemp[] = $objProfile->getDecoratedRstatus() ." in " . $objProfile->getDecoratedCountry();
		}

		//House And Car
		$cHouse = (strlen($objProfile->getOWN_HOUSE())>0)?$objProfile->getOWN_HOUSE():'N';
		$cCar 	= (strlen($objProfile->getHAVE_CAR())>0)?$objProfile->getHAVE_CAR():'N';

		if(in_array($cHouse,ApiViewConstants::$YES) && in_array($cCar,ApiViewConstants::$YES))
		{
			$arrTemp[] = ApiViewConstants::$arrHouseAndCar[$cHouse.$cHouse][$cCar];
		}
		else if(in_array($cHouse,ApiViewConstants::$YES) || in_array($cCar,ApiViewConstants::$YES))
		{
			$arrTemp[] = ApiViewConstants::$arrHouseAndCar[$cHouse][$cCar];
		}


		//Spoken Languages
		$arrHobbies = $objProfile->getHobbies();

		if($arrHobbies && $arrHobbies->LANGUAGE != $arrHobbies->nullValueMarker )
		{
			$arrTemp[] =  "Speaks " . $arrHobbies->LANGUAGE;
		}
		$szLifeStyle = implode("\n",$arrTemp);

		if($szLifeStyle == "")
			$szLifeStyle = null;

		$this->m_arrOut['lifestyle'] = $szLifeStyle;

		// Hobbies,Interest,DressStyle,Fav-Books,Movies,TvShow,Cuisine,Food
		foreach(ApiViewConstants::$arrHobbies as $key => $val)
		{
			$this->m_arrOut[strtolower($key)] = null;
			if($arrHobbies->$val && $arrHobbies->$val != ApiViewConstants::getNullValueMarker()  )
				$this->m_arrOut[strtolower($key)] = $this->DecorateOpenTextField($arrHobbies->$val);
		}

		//Open to pets
		$this->m_arrOut['open_to'] = null;
		if($objProfile->getOPEN_TO_PET() != ApiViewConstants::getNullValueMarker())
		{
			$this->m_arrOut['open_to'] = ApiViewConstants::$arrPets_Preference[$objProfile->getOPEN_TO_PET()];
		}
	}

	/**
	 * getDecorated_LookingFor
	 *
 	 * @param void
	 * @return void
	 * @access private
	 */
	protected function getDecorated_LookingFor()
	{
		$objProfile = $this->m_objProfile;

		$jPartnerObj = $objProfile->getJpartner();
		//Spouse Info
		$this->m_arrOut['about_partner'] = $this->getSpouseInfo();

		//Height Range
		if($jPartnerObj->getLHEIGHT() && $jPartnerObj->getHHEIGHT())
		{
			$this->m_arrOut['dpp_height'] = htmlspecialchars_decode($jPartnerObj->getDecoratedLHEIGHT()) . " to " . htmlspecialchars_decode($jPartnerObj->getDecoratedHHEIGHT());
		}

		//Age Range
		if($jPartnerObj->getLAGE() && $jPartnerObj->getHAGE())
		{
			$this->m_arrOut['dpp_age'] =  $jPartnerObj->getDecoratedLAGE() . " to " . $jPartnerObj->getDecoratedHAGE(). " Years";
		}

		//DPP Info
		foreach(ApiViewConstants::$arrDPPInfo as $key=>$val)
		{
			$this->m_arrOut[strtolower($key)] = null;
                        if($key == "DPP_MANGLIK")
                            $value = CommonFunction::setManglikWithoutDontKnow($jPartnerObj->$val());
                        else
                            $value = $jPartnerObj->$val();

			if($value && $value != ApiViewConstants::getNullValueMarker())
			{
				$this->m_arrOut[strtolower($key)] = $value;
			}
		}
                //have children
                if($jPartnerObj->getPARTNER_MSTATUS() != "'N'"){
                    if($jPartnerObj->getDecoratedCHILDREN())
                        $this->m_arrOut['dpp_have_child'] = $jPartnerObj->getDecoratedCHILDREN();
                }
		//Small Community Labels for DPP Mtongue
        if($this->m_arrOut['dpp_mtongue'] && strlen($jPartnerObj->getPARTNER_MTONGUE()))
        {
            $mtongue = $jPartnerObj->getPARTNER_MTONGUE();
            $mtongue = str_replace("'","",$mtongue);
            $arrMtongue = explode(",",$mtongue);
            $arrMtongueOut= array();
            $arrCommunityLabel = FieldMap::getFieldLabel("community_small",'','1');

            foreach($arrMtongue as $key=>$val)
            {
                $arrMtongueOut[] = $arrCommunityLabel[trim($val)];
            }
            $communityLabel = implode(", ",$arrMtongueOut);
            $this->m_arrOut['dpp_mtongue'] = $communityLabel;
        }
		//Earnings
		$value = $jPartnerObj->getDecoratedPARTNER_INCOME();
		$this->m_arrOut[strtolower('DPP_EARNING')] = null;
		if($value && $value != ApiViewConstants::getNullValueMarker())
		{
			$this->m_arrOut[strtolower('DPP_EARNING')] = html_entity_decode($value);
		}
		// DPP-LifeStyle
		$arrTemp = array();
		foreach(ApiViewConstants::$arrDPP_LifeStyle as $key=>$val)
		{
			$value = $jPartnerObj->$key() ;
			if($value && $value != ApiViewConstants::getNullValueMarker())
			{
				if($val === null)
				{
					$arrTemp[] = $value;
				}
				else
				{
					$arrTemp[] = $this->getDecoratedLabels($value,$val);
				}
			}
		}

		$szDPP_LifeStyle = implode("\n",$arrTemp);

		if($szDPP_LifeStyle == "")
			$szDPP_LifeStyle = null;

		$this->m_arrOut['dpp_lifestyle'] = $szDPP_LifeStyle;

		// DPP - Appeanace Section
		$arrTemp = array();

		if(strlen($jPartnerObj->getDecoratedPARTNER_COMP())!=0)
		{
			$arrCOMPLEXION = explode(",",$jPartnerObj->getDecoratedPARTNER_COMP());
			$len = count($arrCOMPLEXION);
			$szStr = "";
			if($len>1)
			{
				$szStr = " or " . trim($arrCOMPLEXION[$len -1]);
				unset($arrCOMPLEXION[$len -1]);
			}
			$szComplexions = implode(",",$arrCOMPLEXION);
			$arrTemp[] = $szComplexions . $szStr . " Complexion";
		}

		if(strlen($jPartnerObj->getDecoratedPARTNER_BTYPE())!=0)
		{
			$arrBTYPE = explode(",",$jPartnerObj->getDecoratedPARTNER_BTYPE());
			$len = count($arrBTYPE);
			$szStr = "";
			if($len>1)
			{
				$szStr = " or " . trim($arrBTYPE[$len -1]);
				unset($arrBTYPE[$len -1]);
			}
			$szBtype = implode(",",$arrBTYPE);
			$arrTemp[] = $szBtype . $szStr . " Body";
		}

		$szDPP_Appearance = implode("\n",$arrTemp);

		if($szDPP_Appearance == "")
			$szDPP_Appearance = null;

		$this->m_arrOut['dpp_appearance'] = $szDPP_Appearance;

		//Special Case // TODO HIV Section
		$szSpecialCaseOut = "";

		if(strlen($jPartnerObj->getHANDICAPPED()) == 0 || $jPartnerObj->getHANDICAPPED() === "'N'")
		{
			$szSpecialCaseOut = ApiViewConstants::HANDICAPPED_NONE;
		}
		else
		{
			if($jPartnerObj->getDecoratedHANDICAPPED())
				$szSpecialCaseOut = $jPartnerObj->getDecoratedHANDICAPPED();

			if($jPartnerObj->getDecoratedNHANDICAPPED())
			{
				$szNatureHandicapped = $jPartnerObj->getDecoratedNHANDICAPPED();
				$szSpecialCaseOut .= " ($szNatureHandicapped)";
			}

			if($szSpecialCaseOut == "")
			{
				$szSpecialCaseOut = null;
			}
		}

		$this->m_arrOut['dpp_special_case'] = $szSpecialCaseOut;;

                if(!MobileCommon::isDesktop())
                {
			if($this->m_arrOut['dpp_state'] && $this->m_arrOut['dpp_city'])
				$this->m_arrOut['dpp_city'] = $this->m_arrOut['dpp_state'].','.$this->m_arrOut['dpp_city'];
			elseif($this->m_arrOut['dpp_state'])
				$this->m_arrOut['dpp_city'] = $this->m_arrOut['dpp_state'];
                }
        $this->m_arrOut['dpp_diet'] = $jPartnerObj->getDecoratedPARTNER_DIET();
       $this->m_arrOut['dpp_smoke'] = $jPartnerObj->getDecoratedPARTNER_SMOKE();
       $this->m_arrOut['dpp_drink'] = $jPartnerObj->getDecoratedPARTNER_DRINK();
       $this->m_arrOut['dpp_complexion']=$jPartnerObj->getDecoratedPARTNER_COMP();
       $this->m_arrOut['dpp_btype'] = $jPartnerObj->getDecoratedPARTNER_BTYPE();
       $this->m_arrOut['dpp_handi'] = $jPartnerObj->getDecoratedHANDICAPPED();
	}
	/**
	 * getDecorated_Photo
	 *
 	 * @param void
	 * @return void
	 * @access private
	 */
	protected function getDecorated_Photo()
	{
		$actionObj = $this->m_actionObject;
		$arrPICOut = array('label'=>"",'url'=>"",'action'=>"");

		$arrPICOut['label'] 	= $actionObj->PIC_MSG;
		$arrPICOut['url'] 		= $actionObj->PIC_URL;
		$arrPICOut['action'] 	= $actionObj->PIC_ACTION;
		$arrPICOut['app_pic']		= $actionObj->IsMainPic;
		$arrPICOut['pic_count'] = $actionObj->ALBUM_CNT;

		$this->m_arrOut = $arrPICOut;

	}
	/**
	 * getDecorated_Album
	 *
 	 * @param void
	 * @return void
	 * @access private
	 */
	private function getDecorated_Album()
	{

		$this->m_arrOut = $this->m_actionObject->m_arrAlbum;
		if(!is_array($this->m_arrOut))
			$this->m_arrOut = null;
	}
	/**
	 * getDecorated_ShowNext
	 *
 	 * @param void
	 * @return void
	 * @access private
	 */
	private function getDecorated_ShowNext()
	{

		$actObj 	= 	$this->m_actionObject;
		$request	= 	sfContext::getInstance()->getRequest();

		$szShow_Profile = $request->getParameter('show_profile');

		if(isset($szShow_Profile))
		{
			$this->m_arrOut['j'] = $actObj->j;
			$this->m_arrOut['t_rec'] = $actObj->total_rec;
			$this->m_arrOut['search_id'] = $actObj->searchid;
			$this->m_arrOut['stye'] = $actObj->stype;
			$this->m_arrOut['sort'] = $actObj->Sort;
			$this->m_arrOut['actual_offset'] = $actObj->actual_offset;
			$this->m_arrOut['offset'] = $actObj->offset;
			$this->m_arrOut['p_chksum'] = $actObj->profilechecksum;
			$this->m_arrOut['show_next'] = $actObj->SHOW_NEXT;
			$this->m_arrOut['show_prev'] = $actObj->SHOW_PREV;
		}
		else
			$this->m_arrOut = null;
	}

	/**
	 * getDecoratedLabels
	 *
 	 * @param $szInput : String , Input of Kind "'Y','N','O'" and Mapping to Array define in abstract class ApiViewConstants
 	 * @param $szVarName : String , An Array name declared in ApiViewConstants.class.php file
	 * @return String
	 * @access private
	 */
	protected function getDecoratedLabels($szInput,$szVarName)
	{
		$arrOut = array();
		$arrTemp = explode("'",$szInput) ;
		$arrMap = ApiViewConstants::$$szVarName;

		$arrNotAllowd = array("",",");

		foreach($arrTemp as $key => $val)
		{
			if(!in_array(trim($val),$arrNotAllowd))
				$arrOut[] = $arrMap[$val];
		}

		$szOut = implode(", ",$arrOut);
		if($szOut == "")
			return null;
		return $szOut;
	}

	/**
	 * getDecorated_MoreInfo
	 *
 	 * @param void
	 * @return void
	 * @access private
	 */
	protected function getDecorated_MoreInfo()
	{

		$actObj 	= 	$this->m_actionObject;
		$request	= 	sfContext::getInstance()->getRequest();

		//ProfileCheck Sum
		if($this->m_actionObject->profilechecksum == null){
			$this->m_actionObject->profilechecksum = JsCommon::createChecksumForProfile($this->m_actionObject->profile->getPROFILEID());
		}
		$this->m_arrOut['profilechecksum'] = $this->m_actionObject->profilechecksum;

		//IS Bookmarked
		$this->m_arrOut['is_bookmark'] = "NO";
		if(isset($actObj->BOOKMARKED) && $actObj->BOOKMARKED == 1)
		{
			$this->m_arrOut['is_bookmark'] = "YES";
		}
		//Search Type
		$this->m_arrOut['stype'] = $actObj->stype;

		//Actual Offset
		$iOffset = -1;
		if(	is_numeric($actObj->actual_offset) )
		{
			$iOffset = $actObj->actual_offset + 1;
		}
		$this->m_arrOut['page_offset'] = $iOffset;

		//Response Tracking
		$this->m_arrOut['responseTracking'] = $actObj->responseTracking;
		//$gtalkOnline = $this->m_actionObject->GTALK_ONLINE;
    	$isOnline = $this->m_actionObject->ISONLINE;
    	if($isOnline) // this part was removed -> $gtalkOnline ||
    		$this->m_arrOut["userloginstatus"] = "Online now";


    //Profile Share Text
    $this->m_arrOut['share_text'] = ApiViewConstants::SHARE_TEXT . $this->getShareLink();

    //IsIgnore
		$this->m_arrOut['is_ignored'] = "0";
		if(isset($actObj->IGNORED) && $actObj->IGNORED == 1)
		{
			$this->m_arrOut['is_ignored'] = "1";
		}
                $this->m_arrOut['show_ecp'] = 'true';

        //AstroApiParam for third party
	 $appVersion=sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION")?sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION"):0;
	if(MobileCommon::getHttpsUrl()==true && $appVersion>3.9)
	{
		$this->m_arrOut['guna_api_parmas'] = CommonFunction::createChecksumForProfile($this->m_objProfile->getPROFILEID());
		if(true !== is_null($this->m_arrOut['guna_api_parmas']))
		{
		    $this->m_arrOut['guna_api_url'] = JsConstants::$ssl_siteUrl.'/api/v3/profile/gunascore?oprofile=';
		}
	}
	else
	{
		$this->m_arrOut['guna_api_parmas'] = $this->getGunaApiParams();
		if(true !== is_null($this->m_arrOut['guna_api_parmas']))
		{
                        if(MObileCommon::isApp()=="A")
                                    $this->m_arrOut['guna_api_url'] = 'http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?';
                        else
                                    $this->m_arrOut['guna_api_url'] = 'https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?';

		}
	}
	}

	protected function DecorateOpenTextField($szInput)
	{
		$breaks = array("<br />","<br>","<br/>");
		return  str_ireplace($breaks, " ", $szInput);
	}

    protected  function getMembershipType()
    {
        $request    = sfContext::getInstance()->getRequest();

        //Service Type
		$serviceType = $this->m_objProfile->getSUBSCRIPTION();
        $value = CommonFunction::getMainMembership($serviceType);
        //If JsExclusive and request does not contain jsexclusive action then return null
        if($value == false || ($value == mainMem::JSEXCLUSIVE && strpos($request->getParameter("newActions"), "JSEXCLUSIVE") === false )
          )
        {
            $value = null;
        }
        return $value;
    }

    private function getSpouseInfo()
    {
        $objProfile = $this->m_objProfile;
        if($this->m_actionObject->loginProfile &&
           $objProfile->getPROFILEID() == $this->m_actionObject->loginProfile->getPROFILEID()
            )
        {
            $objProfile = $this->m_actionObject->loginProfile;

        }

        return $this->DecorateOpenTextField($objProfile->getDecoratedSpouseInfo());
    }

    /*
     * Function for share link which is deep linked and auto login is off
     * @return Url to share
     */
    protected function getShareLink()
    {
      $linkId = MailerArray::getLinkId(self::PROFILE_SHARE_LINK);

      if(!$linkId || 0 === strlen($linkId)){
        //Send Mail to Developer 
       // $subject = "Profile Share Link: Profile Share Link not found in MailerArray";
       // SendMail::send_email("kunal.test02@gmail.com","Issue in retrieving link id of PROFILE_SHARE_LINK, check LINK_MAILER table & MailerArray Class, '".print_r(self::PROFILE_SHARE_LINK,true)."'"."\n\n'".print_r($_SERVER,true)."'",$subject);
        return ;
      }

      $linkObj = new LinkClass($linkId);
      $url = $linkObj->getLinkUrl("1");

      $arrUrl = explode('/',$url);
      //Unset last '0' if present in url
      if(count($arrUrl) && $arrUrl[count($arrUrl)-1] == "0"){
        unset($arrUrl[count($arrUrl)-1]);
      }

      $url = implode('/',$arrUrl);
      $url .='/?username='.$this->m_objProfile->getUSERNAME();

      return html_entity_decode($url);
    }

    protected function getAllEducationFields(){
        $highestDegree = $this->m_objProfile->getDecoratedEducation();
        $educationArr = $this->m_objProfile->getEducationDetail();
        if($educationArr->PG_DEGREE=="" && $educationArr->OTHER_PG_DEGREE=="" && $educationArr->UG_DEGREE=="" && $educationArr->OTHER_UG_DEGREE=="")
            return $highestDegree;
        else {
            if($highestDegree != '' && $highestDegree != '-')
                $arrTemp1[] = $highestDegree;
            if($educationArr->PG_DEGREE!="" && $educationArr->PG_DEGREE !="-")
              $arrTemp1[] = $educationArr->PG_DEGREE;
            if($educationArr->OTHER_PG_DEGREE != '' && $educationArr->OTHER_PG_DEGREE != '-')
              $arrTemp1[] = $educationArr->OTHER_PG_DEGREE;
            if($educationArr->UG_DEGREE!="" && $educationArr->UG_DEGREE!="-")
              $arrTemp1[] = $educationArr->UG_DEGREE;
            if($educationArr->OTHER_UG_DEGREE != '' && $educationArr->OTHER_UG_DEGREE != '-')
              $arrTemp1[] = $educationArr->OTHER_UG_DEGREE;
            if(is_array($arrTemp1) && count($arrTemp1))
            {
              $educationString = implode(", ",array_unique($arrTemp1));
            }
        return $educationString;
        }
    }

    /*
     *getHoroscopeExist()
     * @return 'Y' if exist else 'N'
     */
    protected function getHoroscopeExist() {

      if ($this->m_arrAstro->astroDetailExist) {
        return 'Y';
      }

      /*$horoscope = new newjs_HOROSCOPE();
			$result = $horoscope->getIfHoroscopePresent($this->m_objProfile->getPROFILEID());
			if ($result == 1) {
				return 'Y';
      }*/
      return 'N';
    }

    /**
     *
     * @return string
     */
    protected function getGunaApiParams()
    {
        $loginProfile = $this->m_actionObject->loginProfile;
        $otherProfile = $this->m_objProfile;

        return ProfileCommon::getGunaApiParams($loginProfile, $otherProfile);
    }
}
