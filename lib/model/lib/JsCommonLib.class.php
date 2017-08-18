<?php
/**
 * JsCommon
 * 
 * This class contains common functionality for the website
 * 
 * @package    jeevansathi
 * @author     Tanu Gupta
 * @created    30-06-2011
 */

class JsCommon{

        /**
         * getSmartySettings
	 *
         * To use conventional smarty methods like assign/ fetch etc
	 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Smarty_assign.2F_fetch.2F_display
	 *
         * @param $param which template directory to use among app, module, web
         * @return smarty object;
         */
	   static public function getSmartySettings($param="web",$appModuleArr='') {
                include_once sfConfig::get('sf_lib_dir') . '/vendor/smarty/libs/Smarty.class.php';

                $smarty = new Smarty ( );
		if($param=="module"){
			$module = sfContext::getInstance ()->getModuleName ();
			$smarty->template_dir = sfConfig::get ( 'sf_app_module_dir' ).'/'.$module.'/templates';
			$smarty->compile_dir = sfConfig::get('sf_app_cache_dir').'/smarty/templates_c';
		}
		elseif($param=="app"){
	                $smarty->template_dir = sfConfig::get( 'sf_app_dir' ).'/templates';
			$smarty->compile_dir = sfConfig::get('sf_app_cache_dir').'/smarty/templates_c';
		}
		elseif($param=="web"){
			$smarty->template_dir    =  sfConfig::get("sf_web_dir").'/smarty/templates/jeevansathi/';
			
			$smarty->compile_dir    =  sfConfig::get("sf_web_dir").'/smarty/templates_c';
		}
		elseif($param=='appModule')
		{
			$app    = $appModuleArr["app"];
			$module = $appModuleArr["module"];
			if($appModuleArr["useModule"])
			{
				$smarty->template_dir = sfConfig::get ( 'sf_app_module_dir' ).'/'.$module.'/templates';
                        	$smarty->compile_dir = sfConfig::get('sf_app_cache_dir').'/smarty/templates_c';
			}
			else if($appModuleArr["useUploads"])
			{
				$smarty->template_dir = JsConstants::$alertDocRoot."/uploads/".$module.'/templates/';
				$smarty->compile_dir = sfConfig::get('sf_app_cache_dir').'/smarty/templates_c';
			}
			else
			{
				$smarty->template_dir = JsConstants::$alertDocRoot."../apps/".$app."/modules/".$module.'/templates/';
				$smarty->compile_dir = sfConfig::get('sf_app_cache_dir').'/smarty/templates_c';
			}
		}
                if (!file_exists($smarty->compile_dir)) {
                   if (!mkdir($smarty->compile_dir, 0777, true)) {
                      throw new sfCacheException('Unable to create cache directory "' . $smarty->compile_dir . '"');
                   }
                   jsException::log('{Common} creating compile directory: ' . $smarty->compile_dir);
                }
                return $smarty;
        }





   

// DNC consent message flag insert
public static function insertConsentMessageFlag($profileid) {

	$consentMsgOb=new NEWJS_CONSENTMSG();
	$consentMsgOb->setConsentStatus($profileid);
	$memObject=JsMemcache::getInstance();
	$memObject->set('showConsentMsg_'.$profileid,'N');

}

                static public function showDuplicateNumberConsent($profileid)
                {
        		if (!CommonConstants::DUPLICATE_NUMBER_CONSENT) return false; //check if the global constant flag is set to true
        		
       			$loggedInProfileObj=LoggedInProfile::getInstance();
               	if(!$loggedInProfileObj->getPROFILEID()) $loggedInProfileObj->getDetail($profileid,'','*');
               	$primaryNum=$loggedInProfileObj->getPHONE_MOB();
                if ($primaryNum)
                {
					$isd=$loggedInProfileObj->getISD();
                	$jprofileObj=new JPROFILE();
                	$resultArray=$jprofileObj->checkPhone(array($primaryNum),$isd);
                	$selfProfileId=$loggedInProfileObj->getPROFILEID();
                	

                	foreach ($resultArray as $key => $value) 
                	{
                		if(
                			$value['PROFILEID']!=$selfProfileId && 
                		 	$value['ISD']==$isd && 
                		 	$value['NUMBER']==$primaryNum && 
                		 	$value['TYPE']=='MOBILE' &&
                		 	$value['ACTIVATED']=='Y' &&
                		 	$value['MOB_STATUS']=='Y'

                		 )
                		return true; 
                	}

                }
                return false;
                }

                
        static public function showConsentMessage($profileid){
        		if (!CommonConstants::SHOW_CONSENT_MSG) return false; //check if the global constant flag is set to true
        
        //check whether it is subscribed to both offerCalls and membershipCalls
        		$dbObj=new JprofileAlertsCache();	
        		$res=$dbObj->fetchMembershipStatus($profileid);  
        		if (($res['MEMB_CALLS']!='S')||($res['OFFER_CALLS']!='S')) return false;

                $consentMsgOb=new NEWJS_CONSENTMSG();
                if ($consentMsgOb->getConsentStatus($profileid)) return false;
                 
                $loggedInProfileObj=LoggedInProfile::getInstance();
               
                $dncOb= new dnc_DNC_LIST();

                if ($loggedInProfileObj->getPHONE_MOB()||$loggedInProfileObj->getPHONE_RES()){
                $resultArray=$dncOb->DncStatus(array($loggedInProfileObj->getPHONE_MOB(),$loggedInProfileObj->getPHONE_WITH_STD()));
                if (is_array($resultArray)) return true;
                	}


                $contactNumOb= new ProfileContact();
                $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileid),'','',"ALT_MOBILE");
                if($numArray['0']['ALT_MOBILE']){
                	$resultArray=$dncOb->DncStatus(array($numArray['0']['ALT_MOBILE']));
                	if (is_array($resultArray)) return true;
				} 

				return false;
        }
        
      /**
         * @fn oldIncludes
         * @brief Files included for integrating older functionality
	 * Its been used in for integrating authentication functionality
         * @return null
         */  
	static public function oldIncludes($ifconnect=true){
		include_once(sfConfig::get("sf_web_dir")."/profile/tables.php");
		global $smarty;
//		$smarty = JsCommon::getSmartySettings("web");
		$smarty=new TempSmarty();
		//include_once(sfConfig::get("sf_web_dir")."/profile/hits.php");

		/**********Commented as not sure whether these variables are in use or not*****
		$banners=array("CHAT","SMS");
		$bannerid=rand(0,1);
		$bannerid=$banners[$bannerid];

		$smarty->assign("random_image",rand(1,9999999));
		$smarty->assign("BANNERID",$bannerid);
		$smarty->assign("LEFTBANNER",basename($_SERVER['PHP_SELF']));
		***********/
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_db.php");//connection
		include_once(sfConfig::get("sf_web_dir")."/profile/js_encryption_functions.php");//Authentication function
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_auth.inc");//Authentication functions
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_functions.inc");
		include_once(sfConfig::get("sf_web_dir")."/classes/globalVariables.Class.php");//Memcache
		include_once(sfConfig::get("sf_web_dir")."/classes/Mysql.class.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/Memcache.class.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
		include_once(sfConfig::get("sf_web_dir")."/profile/tracking.php");
		//include_once(sfConfig::get("sf_web_dir")."/profile/commonfile.php");
		//include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
		//include_once(sfConfig::get("sf_web_dir")."/profile/google_key.php");
		//include_once(sfConfig::get("sf_web_dir")."/profile/connect_reg.inc");
		//include_once(sfConfig::get("sf_web_dir")."/profile/thumb_identification_array.inc");//Trends
		//include_once(sfConfig::get("sf_web_dir")."/profile/contacts_functions.php");
		//include_once(sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
		//include_once(sfConfig::get("sf_web_dir")."/profile/common_functions.inc");
		//include_once(sfConfig::get("sf_web_dir")."/profile/contact.inc");
		
		//To include those functions are previously part to viewprofile script.
		//include_once(sfConfig::get("sf_web_dir")."/profile/profileFunction.php");
		if($ifconnect)
			connect_db();
	}

	/**
	 * returns profile from given checksum
	 * @param $checksum encoded value of profileid of user
	 * @return $profiled/0
	 */
	public static function formatDate($date, $format=""){
	$datesArr = explode(" ",$date);
	$dateArr = $datesArr[0];
	$hrsArr = $datesArr[1];
	$hourArr = explode(":",$hrsArr);
	$dateArr = explode("-",$date);
	$day = $dateArr[2];
	$month = $dateArr[1];
	$year = $dateArr[0];
	$hour = $hourArr[0];
	$min = $hourArr[1];
	if($format == 2)//Old format 2
        {
                if($month=="01" || $month=="1") $month="jan";
                elseif($month=="02" || $month=="2") $month="feb";
                elseif($month=="03" || $month=="3") $month="mar";
                elseif($month=="04" || $month=="4") $month="apr";
                elseif($month=="05" || $month=="5") $month="may";
                elseif($month=="06" || $month=="6") $month="jun";
                elseif($month=="07" || $month=="7") $month="july";
                elseif($month=="08" || $month=="8") $month="aug";
                elseif($month=="09" || $month=="9") $month="sep";
                elseif($month=="10") $month="oct";
                elseif($month=="11") $month="nov";
                else $month="dec";
        }
	elseif($format==4)//Old format 4
	{
		if($month=="01" || $month=="1") $month="January";
                elseif($month=="02" || $month=="2") $month="February";
                elseif($month=="03" || $month=="3") $month="March";
                elseif($month=="04" || $month=="4") $month="April";
                elseif($month=="05" || $month=="5") $month="May";
                elseif($month=="06" || $month=="6") $month="June";
                elseif($month=="07" || $month=="7") $month="July";
                elseif($month=="08" || $month=="8") $month="August";
                elseif($month=="09" || $month=="9") $month="September";
                elseif($month=="10") $month="October";
                elseif($month=="11") $month="November";
                else $month="December";
	}
	else
        {
                if($month=="01" || $month=="1") $month="Jan";
                elseif($month=="02" || $month=="2") $month="Feb";
                elseif($month=="03" || $month=="3") $month="Mar";
                elseif($month=="04" || $month=="4") $month="Apr";
                elseif($month=="05" || $month=="5") $month="May";
                elseif($month=="06" || $month=="6") $month="Jun";
                elseif($month=="07" || $month=="7") $month="Jul";
                elseif($month=="08" || $month=="8") $month="Aug";
                elseif($month=="09" || $month=="9") $month="Sep";
                elseif($month=="10") $month="Oct";
                elseif($month=="11") $month="Nov";
                else $month="Dec";
        }
        if(strlen($day)==1) $day= "0" . $day;
	if($hour!='')
        {
                if($hour>12)
                {
                        $hour=$hour-12;
                        if($hour<10) $hour="0".$hour;
                        $clock='PM';
                }
                elseif($hour==12) $clock='PM';
                elseif($hour==0)
                {
                        $hour=12;
                        $clock='AM';
                }
                else
                        $clock='AM';
                return $day." ".$month." ".$year." ".$hour.".".$min." ". $clock;
        }
        //added by lavesh
        if($format==1)
        {
                $suffix = $this->getDateSuffix($day);
                return $day.$suffix." ".$month.", ".$year;
        }
        elseif($format==2)
        {
                $yr = substr($year,2,3);
                return $day.$month."'".$yr;
        }
	elseif($format==3)
        {
                return $month;
        }
	elseif($format==4)
	{
		$suffix = $this->getDateSuffix($day);
                return $day.$suffix." ".$month.", ".$year;
	}
        //ends here.

        return $month . " " . $day . ", " . $year;
	}

	private function getDateSuffix($day)
	{
		$l=0;
		$l = strlen($day);
		if($l==1)
		{
			switch($day){	
				case(1): $suffix="st"; break;
				case(2): $suffix="nd"; break;
				case(3): $suffix="rd"; break;
				default: $suffix="th"; break;
			}
		}
		else
		{
			if(($day == '11')||($day == '12')||($day == '13')) $suffix="th";
			$last_digit = substr($day,1,2);
			switch($last_digit){
			case 1: $suffix="st"; break;
			case 2: $suffix="nd"; break;
			case 3: $suffix="rd"; break;
			default: $suffix="th"; break;
			}
		}
		return $suffix;
	}

	//Created by Nikhil

	static public function getProfileFromChecksum($checksum)
	{
		if($checksum)
		{
                    $checksum;
			$profileid=substr($checksum,33,strlen($checksum));
			$temp_check=substr($checksum,0,32);
			$real_check=md5($profileid);
			if($temp_check==$real_check)
				return $profileid;
		}
		return 0;
	}

	/**
	 * returns profilechecksum of given profileid
	 * @param $profileid profileid of user
	 * @return $checksum mixed
	 */
	static public function createChecksumForProfile($profileid)
	{
		$checksum='';
		if($profileid)
		{
			$start_tag="start";
			$end_tag="end";
			$checksum=md5($profileid)."i".$profileid;
			//$checksum=md5($start_tag.$profileid.$end_tag).$profileid;

		}
		return $checksum;
	}
	/**
	 * returns array containing elements that are matching profile dpp 
	 * with other profile infomation
	 * @param $profile Profile class object
	 * @param $jpartnerObj partner object of user.
	 * @param $casteLabel string 
	 * @param $sectLabel String
	 * @return $CODE containing value as gnf
	 */
	static public function colorCode($profile,$jpartnerObj,$casteLabel,$sectLabel)
	{
		$CODE=array();
		if(true)
		{
			if($profile->getAGE()>=$jpartnerObj->getLAGE() && $profile->getAGE()<=$jpartnerObj->getHAGE())
			{
				$CODE['AGE']='gnf';
			}
			if($profile->getHEIGHT()>=$jpartnerObj->getLHEIGHT() && $profile->getHEIGHT()<=$jpartnerObj->getHHEIGHT())
			{
				$CODE['HEIGHT']='gnf';
			}
			
			$value=$jpartnerObj->getCHILDREN();
			
			$CHILD=explode(",",$value);
                        if(is_array($CHILD)){
                        foreach ($CHILD as $k=>$v)
                            $CHILD[$k] = trim ($v,"'");
                        }
                        else
                            $CHILD = trim($CHILD,"'");
			if(is_array($CHILD))
			if(in_array($profile->getHAVECHILD(),$CHILD))
			{
				$CODE['HAVECHILD']='gnf';
			}
			$HANDI=explode(",",JsCommon::remove_quot($jpartnerObj->getHANDICAPPED()));
			if(is_array($HANDI))
			if(in_array($profile->getHANDICAPPED(),$HANDI))
			{
				$CODE['HANDI']='gnf';
				$CODE['Challenged']='gnf';
			}
				$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_MANGLIK()));
			if(is_array($ARR))
			if(in_array($profile->getMANGLIK(),$ARR) || ($profile->getMANGLIK() == '' && in_array('N',$ARR)))
			{
				$CODE["MANGLIK"]='gnf';
				$CODE["Manglik/Chevvai Dosham"]='gnf';
				$CODE["Manglik"]='gnf';
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_BTYPE()));
			if(is_array($ARR))
			if(in_array($profile->getBTYPE(),$ARR))
			{
				$CODE['BTYPE']='gnf';
				$CODE["Body Type"]='gnf';
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_COMP()));
			if(is_array($ARR))
			if(in_array($profile->getCOMPLEXION(),$ARR))
			{
				$CODE['COMP']='gnf';
				$CODE['Complexion']='gnf';
			}
			
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_DIET()));
			if(is_array($ARR))
			if(in_array($profile->getDIET(),$ARR))
			{
				$CODE['DIET']='gnf';
				$CODE['Diet']='gnf';
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_DRINK()));
			if(is_array($ARR))
			if(in_array($profile->getDRINK(),$ARR))
			{
				$CODE['DRINK']='gnf';
				$CODE['Drink']='gnf';
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_MSTATUS()));
			if(is_array($ARR))
			if(in_array($profile->getMSTATUS(),$ARR))
			{
				$CODE['MSTATUS']='gnf';
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_RES_STATUS()));
			if(is_array($ARR))
			if(in_array($profile->getRES_STATUS(),$ARR))
			{
				$CODE['RES_STATUS']='gnf';
				$CODE['Residential Status']='gnf';
			}
			
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_SMOKE()));
			if(is_array($ARR))
			if(in_array($profile->getSMOKE(),$ARR))
			{
				$CODE['SMOKE']='gnf';
				$CODE['Smoke']='gnf';
			}
			$caste=display_format($jpartnerObj->getPARTNER_CASTE());
			if($caste)
			$all_caste=get_all_caste($caste);
			if(is_array($all_caste))
			if(in_array($profile->getCASTE(),$all_caste))
			{
				$CODE[strtoupper($casteLabel)]='gnf';
				$CODE[$casteLabel]='gnf';
				
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_RELIGION()));
			if(is_array($ARR))
			if(in_array($profile->getRELIGION(),$ARR))
			{
				$CODE['RELIGION']='gnf';
				$CODE['Religion']='gnf';
				
			}
			
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_ELEVEL_NEW()));
                        $ugPg = $profile->getEducationDetail(1);
                        $pg = 0;
                        $ug = 0;
                        if(!empty($ugPg) && is_array($ugPg)){
                                if($ugPg["PG_DEGREE"])
                                      $pg = $ugPg["PG_DEGREE"];
                                if($ugPg["UG_DEGREE"])
                                      $ug = $ugPg["UG_DEGREE"];
                        }
			if(is_array($ARR))
			if(in_array($profile->getEDU_LEVEL_NEW(),$ARR) || in_array($pg,$ARR) || in_array($ug,$ARR))
			{
				$CODE['ELEVEL_NEW']='gnf';
				$CODE['Highest Degree']='gnf';
				$CODE['Education']='gnf';
				
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_MTONGUE()));
			if(is_array($ARR))
			if(in_array($profile->getMTONGUE(),$ARR))
			{
				$CODE['MTONGUE']='gnf';
				$CODE['Mother Tongue']='gnf';
				
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_OCC()));
			if(is_array($ARR))
			if(in_array($profile->getOCCUPATION(),$ARR))
			{
				$CODE['OCCUPATION']='gnf';
				$CODE['Occupation']='gnf';
				
			}
			$ARR=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_COUNTRYRES()));
			if(is_array($ARR))
			if(in_array($profile->getCOUNTRY_RES(),$ARR))
			{
				$CODE['COUNTRYRES']='gnf';
				$CODE['COUNTRYRES']='gnf';
			}
			$ARR=array_filter(explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_INCOME())));
                        $inc = IncomeCommonFunction::getIncomeDppFilterArray(implode(",",$ARR));
                        /*
                        $incomeObj = new IncomeMapping;
-                       $ARR = $incomeObj->removeNoIncome($ARR);
-                       $incomeArray = $incomeObj->getLowerIncomes($profile->getINCOME());
-                       $result = array_intersect($ARR, $incomeArray);
                         */
			if(is_array($inc) && !empty($inc)){
                                if(in_array($profile->getINCOME(),$inc))
                                {
                                        $CODE['INCOME']='gnf';
                                        $CODE['Income']='gnf';
                                        $CODE['Annual Income']='gnf';
                                }
                        }else{
                                $CODE['INCOME']='gnf';
                                $CODE['Income']='gnf';
                                $CODE['Annual Income']='gnf';
                        }
			$cityArr=explode(",",JsCommon::remove_quot($jpartnerObj->getPARTNER_CITYRES()));
			if($jpartnerObj->getSTATE())
			{
				$stateArr=explode(",",JsCommon::remove_quot($jpartnerObj->getSTATE()));
			}	
			$stateCityMapping = FieldMap::getFieldLabel('state_CITY','',1);
			if(count($stateArr))
			{
				$cityString = self::getCitiesForStates($stateArr);
				$ARR = array_merge($cityArr,explode(",",rtrim($cityString,",")));
			}
			else
			{
				$ARR = $cityArr;
			}
                        $nativePlaceObj = ProfileNativePlace::getInstance();
                        $nativeData = $nativePlaceObj->getNativeData($profile->getPROFILEID());
                        $nativeState = $nativeData['NATIVE_STATE'];
                        $nativeCity = $nativeData['NATIVE_CITY'];
                        if(strlen($profile->getCITY_RES())==2){
                            $resState = $profile->getCITY_RES();
                            if(is_array($stateArr) && in_array($resState,$stateArr))
                                $CODE['CITYRES']='gnf';
                        }
                        if((is_array($stateArr) && in_array($nativeState,$stateArr)) || (is_array($ARR) && (in_array($profile->getCITY_RES(),$ARR) || ($nativeCity && in_array($nativeCity,$ARR)))))
				$CODE['CITYRES']='gnf';
		}
		return $CODE;	
	}
	/**
	 * removes quotes from value
	 * @param $value String 
	 * @return $value String
	 */
	public static function remove_quot($value)
	{
		return str_replace("'","",$value);
	}
	/**
	 * Returns the contact limit message/reached for a particular user.
	 * @param $data array mixed users infomation fetched during authentication
	 * @param $contactStatus String Contact status of user with other user.
	 * @throws jsexception if $data is not array
	 * @return $res array mixed Contact limit message[String] and 
	 * Contact limit reached[Int]
	 */
	public static function contactLimitReached($data,$contactStatus="")
	{
		if($data[PROFILEID])
		{
			$contact_status=unserialize(memcache_call($data[PROFILEID]));
			$total_ini_total=$contact_status['TODAY_INI_TOTAL'];
			$week_ini_total=$contact_status['WEEK_INI_TOTAL'];
			$month_ini_total=$contact_status['MONTH_INI_TOTAL'];
			$total_contacts_made=$contact_status['TOTAL_CONTACTS_MADE'];
			$day_limit=$data['DAY_LIMIT'];
			$weekly_limit=$data['WEEKLY_LIMIT'];
			$month_limit=$data['MONTH_LIMIT'];
			$overall_limit=$data['OVERALL_LIMIT'];
			$notvalidnumber_limit=$data['NOT_VALID_NUMBER_LIMIT'];
			$subscription=$data['SUBSCRIPTION'];
			
			$contact_limit_reached=0;
			$contact_limit_message="";
			
			if($total_ini_total>$day_limit )
			{
				$contact_limit_message=sfConfig::get("app_day_limit");
			}
			else if($week_ini_total>$weekly_limit)
			{
				$contact_limit_message=sfConfig::get("app_week_limit");
			}
			else if($month_ini_total>$month_limit)	
			{
				$contact_limit_message=sfConfig::get("app_month_limit");
			}
			else if($total_contacts_made>$overall_limit)
			{
				if(CommonFunction::isPaid($subscription))
					$contact_limit_message=sfConfig::get("app_overall_limit_paid");
				else
				{
					if(!$add_slashes)
						$contact_limit_message=addslashes(sfConfig::get("app_overall_limit_free"));
					else
						$contact_limit_message=sfConfig::get("app_overall_limit_free");
					
				}	
			}
			else if(checkPhoneVerificationLayerCondition($data[PROFILEID]))
			{
				$overall_cont=get_dup_overall_cnt($data[PROFILEID]);
				if($overall_cont>=$notvalidnumber_limit)
					$contact_limit_message="Not Valid";
			}
			if($contact_limit_message!="" && $contactStatus=="")
			{
				$contact_limit_reached=1;
			}
			$res[0]=$contact_limit_reached;
			$res[1]=$contact_limit_message;
		}
		else 
			throw new JSException("Error in setting Contact limit , Data wrong sent");
		return $res;			
	}
	/**
	 * User is paid or not
	 * @param $subscription String Subscription taken by user
	 * @return $paid int 0[unpaid]/1[paid]
	 */
	public static function isPaid($subscription)
	{
		$paid = 0;
			if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D") || strstr($subscription,"F"))
					$paid=1;
		return $paid;
	}
	/**
	 * returns online status of user on google talk
	 * @param $profile int profileid of user
	 * @throws jsException of profileid not present
	 * @return true/false
	 */

	// public static function gtalkOnline($profile)   //COMMENTING THIS CODE SINCE IT IS NO LONGER USED
	// {
	// 	if($profile)
	// 	{
	// 		$onlineObj=new USER_ONLINE();
	// 		if($onlineObj->isOnline($profile)==true)
	// 		{
	// 			return true;
	// 		}
			
	// 	}
	// 	else
	// 		throw new jsException("online status of user gtalk: Profileid missing.");
		
	// 	return false;
	// }
	
	/**
	 * returns online status of user on jeevansathi
	 * @param $profile int profileid of user
	 * @throws jsException of profileid not present
	 * @return true/false
	 */
	public static function UserOnline($profile)
	{
		if($profile)
		{
			if(JsConstants::$jsChatFlag=='1')
	                {
				$arr = ChatLibrary::getPresenceOfIds($profile);
				if(is_array($arr) && count($arr)>0)
					return true;
        	        }
                	else
			{
				$onlineObj=new USERPLANE_USERS();
				if($onlineObj->isOnline($profile)==true)
				{
					return true;
				}	
			}
		}
		else
			throw new JSException("online status of user userplane: Profileid missing.");
		
		return false;
	}	
	/*
	 * Functions return the string of particular field
	 * @param $label String 
	 * @param $values String
	 * @param $default  To return default if blank.
	 * @returns String	Concanated String of Labels of values given in @values
	 */
	public static function getMultiLabels($label,$values,$default="",$stringFlag='')
	{
		if(!empty($stringFlag))
			$data = explode(",",$values);
		else
			$data=JsCommon::display_format($values);
		if(is_array($data))
		{
			if($data[0]=="DM" || $data[0]=="")
				return $default;	
			for($ll=0;$ll<count($data);$ll++)
			{
				$temp[]=FieldMap::getFieldLabel($label,$data[$ll]);
			}
			
			$ret=implode(", ",$temp);
			return $ret;
		}
		return $default;
	}
	/**
	 * Returns the array by removing quotes from string
	 * return String
	 */
	public static function display_format($str)
	{
			if($str)
			{
					$str=trim($str,"'");

					$arr=explode("','",$str);
					return $arr;
			}

	}
	public static function getCasteLabel(Profile $profileObj){
		$sectArr=array(
			"Muslim","Christian"
		);
		$religion=$profileObj->getDecoratedReligion();
		if(in_array($religion,$sectArr))
			return "Sect";
		else
			return "Caste";
	}	
	public static function getSectLabel(Profile $profileObj){
		$sectArr=array(
			"Muslim","Christian"
		);
		$religion=$profileObj->getDecoratedReligion();
		if(in_array($religion,$sectArr))
			return "Caste";
		else
			return "Sect";
	}
	/** @param profile Profile
	 *  @returns true if contact number is verified or false otherwise
	 *  */
	public static function isContactVerified(Profile $profile)
	{
		if($profile->getLANDL_STATUS()=='Y' or $profile->getMOB_STATUS()=='Y' or $profile->getExtendedContacts()->ALT_MOB_STATUS=='Y')
			return true;
		else
			return false;
	}
        /**
         * returns isPhoneValid
         * @param $profile profileObj of user
         * @return true/false checks if phone is valid in PHONE_FLAG of profile
         */

	public static function isPhoneValid(Profile $profile)
	{
		return ($profile->getPHONE_FLAG()=="I")?false:true;
	}
        /**
         * returns current time 
         * @return $currentTime in date format
         */

	public static function currentTime()
        {
                $currentTime            =       mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
                return date("Y-m-d H:i:s",$currentTime);
        }
        /**
         * returns date diffrence in days for start and end date
         * @param $start starting date, $end ending date
         * @return $dateDiff in number of days
         */

	public static function dateDiff($start, $end) 
	{
		$start_ts = JSstrToTime($start);
		$end_ts = JSstrToTime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}
	public static function dateDiffInSec($start, $end)
        {
                $start_ts = JSstrToTime($start);
                $end_ts = JSstrToTime($end);
                $diff = $end_ts - $start_ts;
                return $diff;
        }

        /**
         * returns profileState of given profile
         * @param $profile profileObj of user
         * @return state as
	 * IU for incomplete or underscreened
	 * P for paid profiles
	 * D1,D2 etc substate for FTO profiles
	 * F for free users
         */

	public static function getProfileState(Profile $profile)
	{
		if($profile->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()=='Y'||$profile->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()=='Y')
			return "IU";
		elseif($profile->getPROFILE_STATE()->getPaymentStates()->isPAID())
			return "P";
		elseif($subState=$profile->getPROFILE_STATE()->getFTOStates()->getSubState())
			return $subState;
		else
			return "F";
	}
	public static function SeoFooter($actionObj='')
	{
			$cpObj=new NEWJS_COMMUNITY_PAGES();
			if($actionObj)
				$actionObj->SEO_FOOTER=$cpObj->getAllTopSeoLinks();
			else
				return $cpObj->getAllTopSeoLinks();
	}
	
	public static function saveHits($source,$page)
	{
		$ip = CommonFunction::getIP();
		
		$dbObj = new MIS_HITS();
		if($source != "" && !stristr($_SERVER['HTTP_USER_AGENT'],"Adsbot-Google"))
		{
			$date=date("Y-m-d G:i:s");
			$dbObj->insertRecord($source,$date,$page,$ip);
		}
		
	}
        /** In case a user isn't online on neither JSChat nor Gtalk, the last login date of the user is shown.
          * This function returns the text that would be displayed in such a case.
          * @param: $lastLoginDate - last login date of the user
          * @return : text that needs to be shown about the "Last Login Status" of the user.
        **/

        public static function getLastLogin($lastLoginDate,$fromWhere='search')
        {
            $arrAllowedFrom = array('search','mobileSeo','newMobSeo');
            $text="Login";
            if(in_array($fromWhere,$arrAllowedFrom));
            {
                $text="Online";
            }
            
                $lastLogin = explode("T",$lastLoginDate);
                $lastLoginDate = $lastLogin[0];

//              $lastLoginDate = str_replace("T"," ",$lastLoginDate);
//              $lastLoginDate = str_replace("Z"," ",$lastLoginDate);
                $today = JSstrToTime(date("Y-m-d"));
                $lastLogin = JSstrToTime($lastLoginDate);
                $diff = ($today - $lastLogin)/(24*60*60);

                if($diff < 1)
                {
			$noOfHours = $diff*24;
			if(($fromWhere=="newMobSeo")&&($noOfHours<12))
				$lastOnlineStr = "$text few hours ago";
			else
                        	$lastOnlineStr = "$text today";
                        //same day
/*
                        $noOfHours = $diff*24;
                        if($noOfHours < 1)
                        {
                                //online now
                                $lastOnlineStr = 'Online Now';
                        }
                        else
                        {
                                //last online x hours ago
                                $lastOnlineStr = "Last Online ".intval($noOfHours);
                                if(intval($noOfHours) == 1)
                                        $lastOnlineStr.=" hour ago";
                                else
                                        $lastOnlineStr.=" hours ago";
                        }

*/
                }
                elseif($diff >= 1 && $diff < 7)
                {
                        //within the week
                        if(($fromWhere=="newMobSeo")&&(intval($diff) >1&&intval($diff) <2))
							$lastOnlineStr = "$text yesterday";
						else
						{
								$lastOnlineStr = "$text ".intval($diff);
								if(intval($diff) == 1)
										$lastOnlineStr.=" day ago";
								else
										$lastOnlineStr.=" days ago";
						}
                }
                elseif($diff >= 7 && $diff < 30)
                {
                        //within a month
                        $diff/=7;
                        
                        $lastOnlineStr = "$text ".intval($diff);
                        
                        if(intval($diff) == 1)
                                $lastOnlineStr.=" week ago";
                        else
                                $lastOnlineStr.=" weeks ago";
                }
                elseif($diff >=30)// && $diff <= 90)
                {
                        //within 3 months
                        $diff/=30;
                        $lastOnlineStr = "$text ";
                        if(intval($diff) == 1)
                                $lastOnlineStr.="1 month ago";
                        else
                                $lastOnlineStr.="2 months ago";
                }
/*
                elseif($diff > 90)
                {
                        //more than 3 months
                        $lastOnlineStr = "Last Online more than 3 months ago";
                }
*/
                return $lastOnlineStr;
        }

	public static function showPhoneVerificationPageOnMobile($profileid,$isMobile,$showLayerValue,$phoneVerificationFromMobile)
	{
		if($_GET[nophver])
		$phoneVerificationFromMobile=$_GET[nophver];
		
		$moduleName = sfContext::getInstance()->getRequest()->getParameter('module');
		if(!isset($isMobile))
			$isMobile = MobileCommon::isMobile();
		if($profileid && $isMobile && $showLayerValue!='Y'&& !$phoneVerificationFromMobile && $moduleName!="register" && $moduleName!="static"&&$moduleName!="phone")
			return true;
		return false;
	}
        public static function verifyPhoneForRequest($profileid,$moduleName,$actionName)
        {
		$isPhoneVerified = JsMemcache::getInstance()->get($profileid."_PHONE_VERIFIED");
		if(!$isPhoneVerified)
		{
			$isPhoneVerified = phoneVerification::hidePhoneVerLayer(LoggedInProfile::getInstance());
			JsMemcache::getInstance()->set($profileid."_PHONE_VERIFIED",$isPhoneVerified);
		}
                if($profileid && $isPhoneVerified!='Y'&& $moduleName!="register" && $moduleName!="static" && $moduleName!="phone")
                        return true;
                return false;
        }
	public static function ESItoIST($time,$format="Y-m-d H:i:s")
	{
		$orgTZ = date_default_timezone_get();
		$time =JSstrToTime($time);
		date_default_timezone_set("Asia/Calcutta");
		$time = date($format, $time);
		date_default_timezone_set($orgTZ);
		return $time;
	}
	public static function checkAppPromoValid($ua)
        {
                $ua = $_SERVER['HTTP_USER_AGENT'];
                $pm = preg_match('/Android\s*([0-9\.]*)/',$ua,$matches);
                if(!strstr($ua,"Android"))
                        return false;
                if(strstr($ua,"Opera Mini"))
                        return true;
                if(!strstr($ua,"Mobile"))
                        return false;
                if(!$pm)
                        return true;
                $av = substr($matches[1],0,3);
                if($av >2.3)
                        return true;
                if($av==2.3 && $matches[1][4]>0)
                        return true;
                return false;
        }
	public static function convert99($num)
	{
		if($num>99)
			return "99+";
		else
			return $num;
	}

	public static function checkIosPromoValid($ua)
        {
                $ua = $_SERVER['HTTP_USER_AGENT'];
                $pm = preg_match('/iPhone\s*([0-9\.]*)/',$ua,$matches);
                 if(!strstr($ua,"iPhone"))
                        return false;
                $av = intval(explode(" ",explode("OS ",$ua)[1])[0]);
                if($av>=7)
                	return true;
                return false;
	}
        public static function setOnlineUser($pid)
        {
        	$JsMemcacheObj =JsMemcache::getInstance();
                $expiryTime =CommonConstants::ONLINE_USER_EXPIRY;
                $listName =CommonConstants::ONLINE_USER_LIST;
		$onlineUserKey =CommonConstants::ONLINE_USER_KEY;
		$key =$onlineUserKey.$pid;
                $JsMemcacheObj->set($key, time(), $expiryTime);
                $JsMemcacheObj->zAdd($listName,time(),$pid);
        }
        public static function getOnlineUsetList($score1='',$score2='')
        {
                $JsMemcacheObj  =JsMemcache::getInstance();
                $listName       =CommonConstants::ONLINE_USER_LIST;
                if($score1 && $score2)
                        $onlineProfilesArr =$JsMemcacheObj->zRangeByScore($listName, $score1, $score2);
                else
                        $onlineProfilesArr =$JsMemcacheObj->zRange($listName, 0, -1);
                return $onlineProfilesArr;
        }
        public static function removeOnlineUser($pid)
        {
        	// Remove Online-User 
		$onlineUserKey =CommonConstants::ONLINE_USER_KEY;
		$key=$onlineUserKey.$pid;
                $JsMemcacheObj =JsMemcache::getInstance();
                $JsMemcacheObj->delete($key);
                $listName =CommonConstants::ONLINE_USER_LIST;
                $JsMemcacheObj->zRem($listName, $pid);
        }
        public static function getOnlineStatus($pid)
        {
                // Check Online-User 
		$onlineUserKey =CommonConstants::ONLINE_USER_KEY;
		$key =$onlineUserKey.$pid;
                $JsMemcacheObj =JsMemcache::getInstance();
                $online =$JsMemcacheObj->get($key);
		if($online)
			return true;
		return false;
        }
        public static function removeOfflineProfiles($score1='',$score2='')
        {
                // Remove Online-User list
		$score1 =0;
		if(!$score2){
			$expiryTime =CommonConstants::ONLINE_USER_EXPIRY;
			$start  =date("Y-m-d H:i:s", time()-$expiryTime);
			$score2 =strtotime($start);
		}
		$listName =CommonConstants::ONLINE_USER_LIST;
                $JsMemcacheObj =JsMemcache::getInstance();
                $JsMemcacheObj->zRemRangeByScore($listName, $score1, $score2);
        }

        public static function getCitiesForStates($stateArr){
            $stateCityMapping = FieldMap::getFieldLabel('state_CITY','',1);
            foreach($stateArr as $key=>$val)
            {
                    if(array_key_exists($val, $stateCityMapping))
                    {
                            $cityString .= $stateCityMapping[$val];
                            $cityString .= ",";
                    }
            }
            return $cityString;
        }
        
        /**
         * Function to log Function Calling in Redis
         * @param type $className
         * @param type $funName
         */
        public static function logFunctionCalling($className, $funName)
        {
            $key = $className.'_'.date('Y-m-d');
            JsMemcache::getInstance()->hIncrBy($key, $funName);

            //JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
        }
        public static function setAutoScreenFlag($screenVal,$editArr)
        {
                $autoScreenArr = array("PHONE_MOB","PHONE_RES","PROFILE_HANDLER_NAME","LINKEDIN_URL","FB_URL","BLACKBERRY","ALT_MESSENGER_ID");
                foreach($editArr as $k=>$v)
                {
                        if(in_array($v,$autoScreenArr))
                        {
				$screenVal = Flag::setFlag(strtolower($v),$screenVal);
                        }
                }
		return $screenVal;
        }
}
?>
