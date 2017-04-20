<?php
/*This class is used to handle the regular matchalerts
* author : Reshu Rajput
* created : 20 May 2014
*/
class MailerService
{
	private $tupleName = "MATCHALERT_MAILER_TUPLE";
        private $photoUnderScreening='U';
        private $photoPresent='Y';
	private $renewDiscount = 15;
	private $userList=Array();
	private $userIds = Array();
	private $failCount = 0;
	
	//This function is used to get links from jeevansathi_mailer.LINK_MAILERS format
	public function getLinks()
	{
		$mailerLinksArray = MailerArray::getLinkArray();
		foreach($mailerLinksArray as $name=>$linkId)
		{
			$linkObj = new LinkClass($linkId);
			$mailerLinks[$name] = $linkObj->getLinkUrl("1");
		}
		return $mailerLinks;
	}
	/* This function is used to take lock on the task 
	*@param $mailerName : mailer name 
	*@param $totalScript : total number of scripts 
	*@param $currentScript : current script number
	*/
	public function getLock($mailerName,$totalScript,$currentScript)
	{
		$LockingService = new LockingService;
	        $file = $mailerName."_".$totalScript."_".$currentScript.".txt";
        	$lock = $LockingService->getFileLock($file,1);
         	if(!$lock)
                	 successfullDie();
	}

	/*This function is used to send mail and verify if its fails
	* If fail count is more than limit than a mail is fired
	*@param $emailID : email id of the receiver
	*@param $msg : mail body
	*@param $subject : mail subject
	*@param $mailerName : name of mailer to find mailer send details
	*@return $flag: "Y" or "F" if mail sent is success or fail respectively
	*/
	public function sendAndVerifyMail($emailID,$msg,$subject,$mailerName,$pid="",$alternateEmailID ='')
	{
		$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$emailID,"EMAIL_TYPE"=>$mailerName),$pid);
		$canSend = $canSendObj->canSendIt();
		if($canSend)
		{
			$senderDetails = MAILER_COMMON_ENUM::getSenderEnum($mailerName);
        	        // Sending mail and tracking sent status
                	$mailSent = SendMail::send_email($emailID,$msg,$subject,$senderDetails["SENDER"],$alternateEmailID,'','','','','','1','',$senderDetails["ALIAS"]);
	                $flag= $mailSent?"Y":"F";
        	        if($flag =="F")
                		$this->failCount++;
			if($this->failCount > MAILER_COMMON_ENUM::$MAIL_FAIL_LIMIT)
        	        {
                	        SendMail::send_email("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","$mailerName Failed more than limit","$mailerName failed",$senderDetails["SENDER"]);
                 	       die;
	                }
		}
		else
			$flag='B';
		return $flag;
	}

        	
	/* This function is used to get receivers to sent mail 
	* @param totalScript : total scripts executing for mailer cron
	* @param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getMailerReceivers($totalScript="",$script="",$limit='', $fields='')
	{
		$matchalertMailerObj = new matchalerts_MAILER();
		$recievers = $matchalertMailerObj->getMailerProfiles($fields,$totalScript,$script,$limit);
		return $recievers;	
	}

	/* This function is used to get new matches receivers to sent mail 
	*@param totalScript : total scripts executing for mailer cron
	*@param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getNewMatchesMailerReceivers($totalScript="",$script="",$limit='')
	{
        	$newMatchesObj = new new_matches_emails_MAILER();
		$recievers = $newMatchesObj->getMailerProfiles("",$totalScript,$script,$limit);
		return $recievers;
	}

	/* This function is used to get receivers to send mail 
	* @param totalScript : total scripts executing for mailer cron
	* @param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getMailerReceiversVisitorAlert($totalScript="",$script="",$limit='')
	{
		$visitorAlertMailerObj = new visitorAlert_MAILER();
		$recievers = $visitorAlertMailerObj->getMailerProfiles($totalScript,$script,$limit);
		return $recievers;	
	}
	 /* This function is used check whether to show Android Icon to Receiver or not
	* @param  NO
	* @return recievers : 1
	*/
	public function getIfShowAndroidIcon()
	{
		return 1;
	}
	
	/* This function is used check whether to show IOS Icon to Receiver or not
	* @param  NO
	* @return recievers : 1
	*/
	public function getIfShowIOSIcon()
	{
		return 0;
	}

	/* This funxtion is used update the sent flag(Y for sent and F for fail) for each matchalert mail receiver
	*@param sno : serial number of mail
	*@param flag : sent status of the mail
	*/
	public function updateSentForUsers($sno,$flag)
	{
		if(!$sno || !$flag)
			throw  new jsException("No sno/flag in updateSentForUsers() in RegularMatchAlerts.class.php");
		$matchalertMailerObj = new matchalerts_MAILER();
                $matchalertMailerObj->updateSentForUsers($sno,$flag);

	}
	
	/* This funxtion is used update the sent flag(Y for sent and F for fail) for each new matches mail receiver
        *@param sno : serial number of mail
        *@param flag : sent status of the mail
        */
        public function updateSentForNewMatchesUsers($sno,$flag)
        {
                if(!$sno || !$flag)
                        throw  new jsException("No sno/flag in updateSentForNewMatchesUsers() in RegularMatchAlerts.class.php");
                $matchalertMailerObj = new new_matches_emails_MAILER();
                $matchalertMailerObj->updateSentForUsers($sno,$flag);

        }
        /* This funxtion is used update the sent flag(Y for sent and F for fail) for each visitor alert mail receiver
	*@param sno : serial number of mail
	*@param flag : sent status of the mail
	*/
	public function updateSentForVisitorAlertUsers($sno,$flag)
	{
		if(!$sno || !$flag)
			throw  new jsException("No sno/flag in updateSentForUsers() in RegularMatchAlerts.class.php");
		$matchalertMailerObj = new visitorAlert_MAILER();
                $matchalertMailerObj->updateSentForUsers($sno,$flag);

	}

	
	/*This function is used to get details of the receiver
	*@param loggedInProfileObj : logged in profile object of the receiver
	*@return loggedInProfileObj : logged in object with required details set
	*/
	public function getReceiverInfoWithName($loggedInProfileObj,$nameFlag=false)
	{
		if(!$loggedInProfileObj)
			throw  new jsException("No logged in object in getRecieverInfoWithName() function in RegularMatchAlerts.class.php");
                $loggedInProfileObj->getDetail("","","HAVEPHOTO,GENDER,USERNAME,EMAIL,SUBSCRIPTION,RELIGION,LAST_LOGIN_DT"); 
		if($nameFlag)
		{
			$incentiveNameOfUserObj = new incentive_NAME_OF_USER();
                	$loggedInProfileObj->setName(ucwords($incentiveNameOfUserObj->getName($loggedInProfileObj->getPROFILEID())));
		}
		return $loggedInProfileObj;
	}
	
	/* This function is used to get dpp of the given profile
	*@param loggedInProfileObj: logged in object to find dpp 
	*@return dppData : dpp data in matchalert required format
	*/
	public function getDppData($loggedInProfileObj)
	{
		if(!$loggedInProfileObj)
                	throw  new jsException("No logged in object in getDppData() function in RegularMatchAlerts.class.php");
		$partnerProfile = new PartnerProfile($loggedInProfileObj);
		$partnerProfile->getDppCriteria("","MAILER");
		if($partnerProfile->getLAGE()!='' && $partnerProfile->getHAGE()!='' && $partnerProfile->getLAGE()!='0' && $partnerProfile->getHAGE()!='0')
			$dppData["AGE"] = $partnerProfile->getLAGE() ." to ".$partnerProfile->getHAGE();
		else
			$dppData["AGE"] = "Doesn't Matter";

		if($partnerProfile->getLHEIGHT()!='' && $partnerProfile->getLHEIGHT()!='0' && $partnerProfile->getHHEIGHT()!='' &&$partnerProfile->getHHEIGHT()!='0')
			$dppData["HEIGHT"]=FieldMap::getFieldLabel("height_without_meters",$partnerProfile->getLHEIGHT()) ." to ".FieldMap::getFieldLabel("height_without_meters",$partnerProfile->getHHEIGHT());
		else
			$dppData["HEIGHT"] = "Doesn't Matter";

		if($partnerProfile->getMTONGUE()!='')
			$dppData["MTONGUE"] = JsCommon::getMultiLabels("community_small",$partnerProfile->getMTONGUE(),"","true");
		else
			$dppData["MTONGUE"] = "Doesn't Matter";
		if(strlen($dppData["MTONGUE"])>55)
                	$dppData["MTONGUE"] = substr($dppData["MTONGUE"],0,55)."...";

		if($partnerProfile->getMSTATUS()!='')
			$dppData["MSTATUS"] =  JsCommon::getMultiLabels("mstatus",$partnerProfile->getMSTATUS(),"","true");
		else
			$dppData["MSTATUS"] = "Doesn't Matter";

		if($partnerProfile->getLINCOME()!= "" && $partnerProfile->getHINCOME() !="")
		{
			$hincome = $partnerProfile->getHINCOME();
			$dppData["INCOME"] = FieldMap::getFieldLabel("lincome",$partnerProfile->getLINCOME())." ";
			if($hincome != 19)
				$dppData["INCOME"].="to ";
			$dppData["INCOME"].= FieldMap::getFieldLabel("hincome",$hincome);

		}
		else
			 $dppData["INCOME"] = "Doesn't Matter";
		if($partnerProfile->getLINCOME_DOL()!= "" && $partnerProfile->getHINCOME_DOL() !="")
                {
                        $hincome_dol = $partnerProfile->getHINCOME_DOL();
                        $dppData["INCOME_DOL"] = FieldMap::getFieldLabel("lincome_dol",$partnerProfile->getLINCOME_DOL())." ";
                        if($hincome_dol != 19)
                                $dppData["INCOME_DOL"].="to ";
                        $dppData["INCOME_DOL"].= FieldMap::getFieldLabel("hincome_dol",$hincome_dol);
                }
		elseif($dppData["INCOME"] == "Doesn't Matter")
			 $dppData["INCOME_DOL"]="";
		else
			$dppData["INCOME_DOL"]="Doesn't Matter";
		
		$dppData["CASTE_SECT"]= JsCommon::getCasteLabel($loggedInProfileObj);
		if($partnerProfile->getCASTE()!='')
		{
			$dppData["CASTE"] = JsCommon::getMultiLabels("caste",$partnerProfile->getCASTE(),"","true");
			if(strlen($dppData["CASTE"])>55)
                        	$dppData["CASTE"] = substr($dppData["CASTE"],0,55)."...";
		}
		else
		{
			if($partnerProfile->getRELIGION()!="")
				$dppData["CASTE"] = JsCommon::getMultiLabels("religion",$partnerProfile->getRELIGION(),"","true").": ";
			$dppData["CASTE"].="Doesn't Matter";
		}
		unset($partnerProfile);
		return $dppData;
	}
	
	/* This function is used to membership details of the profile and set the renew details
	*@param pid : profile id 
	*@return membershipDetail : array of membership,renew detail and expiry else null
	*/ 
	public function getMembershipDetails($pid)
	{
                if(!$pid)
                        throw  new jsException("No pid in getMembershipDetails() function in RegularMatchAlerts.class.php");
		$billingServiceStatus = new BILLING_SERVICE_STATUS();
		$membershipHandlerObj = new MembershipHandler();
		$result = $billingServiceStatus->getExpiryDateForInstantEOIMailer($pid);
		if($result[0]!="" && $result[1]!="" && !strstr($result[1],'L') && !strstr($result[0],"0000-00-00"))
		{
			$expDate =strtotime($result[0]);
			$curDate = strtotime(date("Y-m-d"));
			$daysDiff = ($curDate - $expDate)/(60*60*24);
			if($daysDiff<=10 && $daysDiff>-30)
			{
				$membershipDetail["RENEW"] = 1;
				//$membershipDetail["RENEW_DISCOUNT"]= $this->renewDiscount;
				$membershipDetail["RENEW_DISCOUNT"]= $membershipHandlerObj->getVariableRenewalDiscount($pid);
				$renewDate = date("Y-m-d", strtotime($result[0])+(24*3600*10));
			}
			else 
				$membershipDetail["RENEW"] = 0;
			if($daysDiff>0)
				$membershipDetail["EXPIRED"] = 1;
			else
				$membershipDetail["EXPIRED"] = 0;
			
			$membershipDetail["EXPIRY_DT"] = $this->getDateFormat($result[0]);
			$membershipDetail["RENEW_DT"] = $this->getDateFormat($renewDate);
		}
		return $membershipDetail;		
	}

	 /* This function is used to get variable discount details of the profile 
        *@param pid : profile id 
        *@return vd : array of  variable discount details else null
        */

	public function getVariableDiscount($pid)
	{
		$variableDiscountObj = new VariableDiscount;
                $variableDiscountdetails = $variableDiscountObj->getDiscDetails($pid);
                if(is_array($variableDiscountdetails))
                {
                	$vd = array();
			$vdDisplayText = $variableDiscountObj->getVdDisplayText($pid,'small');
			$discount = $variableDiscountdetails["DISCOUNT"]; 
                        $vd["DATE"] = $this->getDateFormat($variableDiscountdetails["EDATE"]);

			$vd["DISCOUNT"] =$discount; 
			$vd["DISCOUNT_TEXT"] =$discount;
			$vd["VD_DISCOUNT_TEXT"] =$vdDisplayText;
                        return $vd;
                }
                else
                	return null;

	}

	/*This funxtion is used to get match alert date format
	*@param dateString : date in string format
	*@return formatDate : date in array of different parts 
	*/	
	public function getDateFormat($dateString)
	{
		$formatDate = array();
		$date = strtotime($dateString);
                $formatDate["MONTH"] = date("M",$date);
                $formatDate["YEAR"] = date("Y",$date);
                $formatDate["DAY"] = date("d",$date);
                $formatDate["DAY_SUFFIX"] = date("S",$date);
		return $formatDate;
	}

	/*This function is used to set  $this->userList and this->userIds
	*@param $values : values of the receiver
	*@param $pattern : field label for users to be sent
	*/	

	public function setUsersToSend($values,$pattern)
	{
		if(!is_array($values) || !$pattern)
                        throw  new jsException("No values or pattern in setUsersToSend() function in RegularMatchAlerts.class.php");
		$userList = Array();
		$pattern = "/".$pattern."\d/";
      
		foreach($values as $key=>$v)
		{
			if(preg_match($pattern,$key) && $v!=0)
			{
				$this->userList["MATCH_ALERT"][$v]=Array("PROFILEID"=>$v);
				$this->userIds[]=$v;
			}
		}
		if(count($this->userList)==0)
		{
			unset($this->userList);
		}
	}

	/* This function is used to gte the users details to be sent to receiver, this function verifies
	   the gender on basis of a flag and your info for each user as well.
	*@param profileObj : profile obj of receiver
	*@param filterGenderFlag : flag if need to filter on basis of gender gender of receiver
	*@return tuplesValues : array of tuple objects with the details set for all the users
	*/	
	public function getUsersListToSend($profileObj,$filterGenderFlag=false)
	{
		if(!is_array($this->userList) || !$profileObj)
		{
			jsException::log("No userList or profile in getUsersListToSend() function in RegularMatchAlerts.class.php");
			return null;
		}
		else
		{
			if($profileObj->getGENDER()=='F')
				$requiredGender= "M";
			else
				$requiredGender= "F";
			$tupleService = new TupleService();
			$tupleService->setLoginProfileObj($profileObj);
			$tupleFields            = $tupleService->getFields($this->tupleName);
			$tupleService->setProfileInfo($this->userList,$tupleFields);
			unset($this->userList);
			$tuplesValues = $tupleService->getMATCH_ALERT();
			if(is_array($tuplesValues))
			{
				foreach($tuplesValues as $tuples=>$tupleObj)
				{	
					if(($filterGenderFlag && $tupleObj->getGENDER()!=$requiredGender) || $tupleObj->getACTIVATED()!="Y")
						unset($tuplesValues[$tuples]);
					else
					{
						$yourInfo = $tupleObj->getYOURINFO();
						if($yourInfo!='')
						{
							$yourInfoTemp=strlen($yourInfo);
							if($yourInfoTemp>160)
							{
								$newInfo=substr($yourInfo,0,strrpos(substr($yourInfo,0,161)," "));
							}
							else
							{
								$newInfo=$yourInfo;
							}
							$tupleObj->setYOURINFO(strip_tags($newInfo));
						}
						//This has been added in case of Kundli Matches Mailer
						if(is_array($this->gunaScoreArr))
						{
							$tupleObj->setGUNA($this->gunaScoreArr[$tupleObj->getPROFILEID()]);						
						}
						
					}
				}
				return $tuplesValues;
			}
			else
				return null;
		}
						
	}
	
	/*This function is used to set users logical level based on type NEW_MATCHES or matchalert
	* it fires mail if no logical level is found and terminates the mailer
	*@param $tuplesValues : tuple values of the users
	*@param $profileObj : profile obj of receiver
	*@param $type : type of mailer
	*@return $tuplesValues : tuple values with set logical level
	*/
	public function setUsersLogicalLevel($tuplesValues,$profileObj,$type='')
	{
		if($type == "NEW_MATCHES")
			{$matchesLogObj = new new_matches_emails_LOG();}
		else
			{$matchesLogObj = new matchalerts_LOG(); }
		$logicLevels = $matchesLogObj->getLogicLevelFromLogTemp($profileObj->getPROFILEID(), $this->userIds);
		unset($this->userIds);
		if(!is_array($logicLevels))
		{
			 SendMail::send_email("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","Regular Mailer Failed for no logic level found for pid :$pid","Regular Mailer failed: No logic level","reshu.rajput@jeevansathi.com");
                        die;
		}
		foreach($tuplesValues as $tuples=>$tupleObj)
                {

			if(array_key_exists($tupleObj->getPROFILEID(),$logicLevels))
			{
				$stype = $this->getStpyeForLogicLevel($logicLevels[$tupleObj->getPROFILEID()]);
				$tupleObj->setLOGICLEVEL($stype);
			}
			else
			{
				SendMail::send_email("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","Regular Mailer Failed for no logic level found for pid :$pid and user: ".$tupleObj->getPROFILEID(),"Regular Mailer failed: No logic level","reshu.rajput@jeevansathi.com");
				die;
			}
		}
		unset($matchesLogObj);
		return $tuplesValues;
	}

	/*This function is used to sort the users list according to the photo with following sort order:
	*Photo visible to all – 1 
	*Photo visible on accept – 2 
	*Photo under screening – 3 
	*No photo – 4
	*@param userList : users array of tuple to be sorted 
	*@return matchesDataFinal: sorted array of tuples of users
	*/
	public function sortUsersListByPhoto($userList)
	{
		if(!is_array($userList))
			throw  new jsException("No userList in sortUsersListByPhoto() function in RegularMatchAlerts.class.php");

		foreach($userList as $k=>$v)
		{
			if($v->getHAVEPHOTO()== $this->photoPresent)
			{
				if($v->getPHOTO_DISPLAY()!= PhotoProfilePrivacy::photoVisibleIfContactAccepted)
					$sortArr[$v->getPROFILEID()] = 1;
				else
					$sortArr[$v->getPROFILEID()] = 2;
			}
			elseif($v->getHAVEPHOTO()== $this->photoUnderScreening)
			{
				$sortArr[$v->getPROFILEID()] = 3;
			}
			else
			{
				$sortArr[$v->getPROFILEID()] = 4;
			}
		}
		asort($sortArr);
		$i=0;
		foreach($sortArr as $k=>$v)
		{
			foreach($userList as $kk=>$vv)
			{
				if($vv->getPROFILEID()==$k)
				{
					$matchesDataFinal[$i]=$vv;
					$i++;
				}
			}
		}
		unset($userList);
		return $matchesDataFinal;
	}

	/*This function is used to map logical level with styp
	*@param logiclevel : logical level of the user
	*@return stype : mapped stype 
	*/	
	function getStpyeForLogicLevel($logiclevel)
	{
		if($logiclevel==11)
			$stype = "Ba";
		elseif($logiclevel==111)
			$stype = "B1";
		elseif($logiclevel==12)
			$stype = "Bb";
		elseif($logiclevel==121)
			$stype = "B2";
		elseif($logiclevel==13)
			$stype = "Bc";
		elseif($logiclevel==131)
			$stype = "B3";
		elseif($logiclevel==14)
			$stype = "Bd";
		elseif($logiclevel==141)
			$stype = "B4";
		elseif($logiclevel==15)
			$stype = "Be";
		elseif($logiclevel==16)
			$stype = "Bf";
		elseif($logiclevel==17)
			$stype = "Bg";
		elseif($logiclevel==18)
			$stype = "Bh";
		elseif($logiclevel==21)
			$stype = "Bi";
		elseif($logiclevel==211)
			$stype = "B5";
		elseif($logiclevel==22)
			$stype = "Bj";
		elseif($logiclevel==221)
			$stype = "B6";
		elseif($logiclevel==23)
			$stype = "Bk";
		elseif($logiclevel==231)
			$stype = "B7";
		elseif($logiclevel==24)
			$stype = "Bl";
		elseif($logiclevel==241)
			$stype = "B8";
		elseif($logiclevel==25)
			$stype = "Bm";
		elseif($logiclevel==26)
			$stype = "Bn";
		elseif($logiclevel==27)
			$stype = "Bo";
		elseif($logiclevel==28)
			$stype = "Bp";
		elseif($logiclevel==31)
			$stype = "Bq";
		elseif($logiclevel==32)
			$stype = "Br";
		elseif($logiclevel==33)
			$stype = "Bs";
		elseif($logiclevel==41)
			$stype = "Bt";
		elseif($logiclevel==42)
			$stype = "Bu";
		elseif($logiclevel==43)
			$stype = "Bv";
		elseif($logiclevel==51)
                	$stype = "F1";
        	elseif($logiclevel==52)
                	$stype = "F2";
        	elseif($logiclevel==53)
                	$stype = "F3";
        	elseif($logiclevel==61)
                	$stype = "F4";
        	elseif($logiclevel==62)
                	$stype = "F5";
        	elseif($logiclevel==63)
                	$stype = "F6";
		return $stype;
	}
	
	/*This function is used to track daily new matches send
	* it updates MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING according to values from new_matches_emails_MAILER entries
	*/
	public function newMatchesEmailsTracking()
	{
		$newMatchesEmailsMAILERObj = new new_matches_emails_MAILER();
		$fields = "SUM( USER10 !=0 ) AS 10_MATCH,SUM( USER9 !=0 ) AS 9_MATCH,SUM( USER8 !=0 ) AS 8_MATCH,SUM( USER7 !=0 ) AS 7_MATCH, SUM( USER6 !=0) AS 6_MATCH,SUM( USER5 !=0 ) AS 5_MATCH,SUM( USER4 !=0 ) AS 4_MATCH,SUM( USER3 !=0 ) AS 3_MATCH,SUM( USER2 !=0 ) AS 2_MATCH,SUM( USER1 !=0 ) AS 1_MATCH";
		$result = $newMatchesEmailsMAILERObj->getMailerProfiles($fields,"1","0","",'Y');
		if(is_array($result))
		{
			$sub = 0;
			$sum = 0;
			foreach($result as $k=>$v)
			{
				$param[$k] = $v-$sub;
				$sum += $param[$k];
				$sub = $v;
			}
			$param["PROFILES_MAIL_SENT"]=$sum;
			$matchAlertTrackingObj=new MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING();
                	$matchAlertTrackingObj->updateNewMatchesTracking($param);
		}
	}
	
	/*This function is used to get smarty for the mailer*/

	public function getMailerSmarty()
	{
		$appModuleArr= array();
	        $appModuleArr["module"]="mailer";
        	$appModuleArr["useModule"]=1;
		$smarty = JsCommon::getSmartySettings("appModule",$appModuleArr);
		return $smarty;		
	}
	/* This function is used to load partials , all the partials load is not neccessary so single is uploaded
	*/
	public function loadPartials()
	{
        	sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerheader");
  	}
        public function getEducationDetails($pid)
	{
                $educationObj = ProfileEducation::getInstance();
                $Education = $educationObj->getProfileEducation($pid,$from="mailer");
                $edu=$this->getEducationDisplay($Education);
                $eduDisplay="";
                if($edu)
                        $eduDisplay = implode(", ",array_unique($edu));
                return $eduDisplay;
        }

public function getMultipleEducationDetails($profileIdArray)
	{
		if(!is_array($profileIdArray)) return false;
                $educationObj = ProfileEducation::getInstance();
                $EducationArray = $educationObj->getProfileEducation($profileIdArray,'mailer');
                foreach($EducationArray as $k=>$Education)
                {
                	unset($edu);
                $edu=$this->getEducationDisplay($Education);
                if($edu)
                        $eduDisplay[$Education['PROFILEID']] = implode(", ",array_unique($edu));
                    else 
                        $eduDisplay[$Education['PROFILEID']] ='';
				}
                return $eduDisplay;
        }
public function getEducationDisplay($row){

				if($row["EDU_LEVEL_NEW"])
                        $edu[]=FieldMap::getFieldLabel('education',$row["EDU_LEVEL_NEW"]);
                if($row["PG_DEGREE"])
                        $edu[]=FieldMap::getFieldLabel('education',$row["PG_DEGREE"]);
                if($row["OTHER_PG_DEGREE"] && Flag::isFlagSet("other_ug_degree", $row["SCREENING"]))
                        $edu[]=substr($row["OTHER_PG_DEGREE"],0,30);
                if($row["UG_DEGREE"])
                        $edu[]=FieldMap::getFieldLabel('education',$row["UG_DEGREE"]);
                if($row["OTHER_PG_DEGREE"] && Flag::isFlagSet("other_pg_degree", $row["SCREENING"]))
                        $edu[]=substr($row["OTHER_UG_DEGREE"],0,30);

return $edu;


}

	/*This function is used to get complete receiver details to be sent according to various flags set 
	* in widget array as true or false and mailer name
	*@param $pid : profile id of the receiver
	*@param $values : values of the corresponding receiver from correspondinf mailer table
	*@param $mailerName: name of the mailer
	*@param $widgetArray:  Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>true,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true); 
	*@return $data : complete data to be sent in mail 
	*/
	public function getRecieverDetails($pid,$values,$mailerName,$widgetArray,$gunaScoreArr="")
	{
		if(!$pid || !is_array($values) || !$mailerName)
			throw new jsException("No pid/values/mailerName passed in getRecieverDetails RegularMatchAlerts.class.php");
		
		//In case gunaScoreArr is set like in case of kundli Matches Mailer, then this array is assigned so that it can be used later in getUsersListToSend()
		if(is_array($gunaScoreArr))
		{
			$this->gunaScoreArr = $gunaScoreArr;
		}
		$operatorProfileObj = Operator::getInstance('newjs_master',$pid);
                if(!$operatorProfileObj)
                                throw new jsException("Invalid pid passed in getRecieverDetails RegularMatchAlerts.class.php");

		$operatorProfileObj = $this->getReceiverInfoWithName($operatorProfileObj,$widgetArray["nameFlag"]);
		$userFieldLabel = MAILER_COMMON_ENUM::getUserFieldLabel($mailerName);
		$this->setUsersToSend($values,$userFieldLabel);
		$users = $this->getUsersListToSend($operatorProfileObj,$widgetArray["filterGenderFlag"]);
		$usersCount = sizeof($users);
                if($usersCount >0)
                {
			$data = array();
			$receiverProfilechecksum = JsAuthentication::jsEncryptProfilechecksum($pid);
                        $emailId = $operatorProfileObj->getEMAIL();

            if ( $widgetArray["alternateEmailSend"] === true )
            {
	            $jprofileContactObj    =new ProfileContact();
	            $receiverProfileData = $jprofileContactObj->getProfileContacts($pid);

	            if ( is_array($receiverProfileData ))
	            {
		            $alternateEmailID = $receiverProfileData["ALT_EMAIL"];
		            $alternateEmailIDStatus = $receiverProfileData["ALT_EMAIL_STATUS"];
		            if ( $alternateEmailIDStatus != 'Y' || $alternateEmailID == NULL)
		            {
		            	$alternateEmailID = '';
		            }
	            }
	            else
	            {
	            	$alternateEmailID = '';	
	            }
            }
            else
            {
            	$alternateEmailID = '';
            }

            $data["RECEIVER"]["ALTERNATEEMAILID"] = $alternateEmailID;


			$data["RECEIVER"]["PROFILE"] = $operatorProfileObj;
			$data["RECEIVER"]["PROFILECHECKSUM"] = $receiverProfilechecksum;
			$data["RECEIVER"]["EMAILID"] = $emailId;
                        if($widgetArray["autoLogin"])
			{
				$receiverechecksum = JsAuthentication::jsEncrypt($pid,"");
				$data["commonParamaters"] ="/".$receiverechecksum."/".$receiverProfilechecksum;
			}
			if(array_key_exists("LOGIC_USED",$values))
				$data["logic"]= $values["LOGIC_USED"];
			else
				$data["logic"] = null;
			if($widgetArray["openTrackingFlag"])
			{
				$emailViewCountObj = new EmailViewCount();
                        	$sentDate =$emailViewCountObj->getLogicalDate();
                        	$emailType =$emailViewCountObj->getEmailDomain($emailId);
				$data["OpenTracking"]["sentDate"] =$sentDate;
                                $data["OpenTracking"]["frequency"] =$values["FREQUENCY"];
                                $data["OpenTracking"]["emailType"] = $emailType;
			}
			if($widgetArray["dppFlag"])
			{
				$dpp = $this->getDppData($operatorProfileObj);
				$data["DPP"] = $dpp;
			}

			if($widgetArray["primaryMailGifFlag"])
			{
				$data["GifFlag"] = $this->getGifFlag($emailId);
				
			}
			if($widgetArray["membershipFlag"])
			{
				if(strstr($operatorProfileObj->getSUBSCRIPTION(),'F'))
                                	$data["MEMBERSHIP"]["membership"]=1;
                                else
                                {
                                        $data["MEMBERSHIP"]["membership"]=0;
				}
				// RENEWAL logic
				$receiverMembership = $this->getMembershipDetails($pid);
				if($receiverMembership)
					$data["MEMBERSHIP"]["renew"] = $receiverMembership;
				else
					 $data["MEMBERSHIP"]["renew"] = 0;
				if(!$receiverMembership || !$receiverMembership['RENEW'] || $receiverMembership['RENEW']==0){
					 $variableDiscountdetails = $this->getVariableDiscount($pid);
					 if(is_array($variableDiscountdetails))
						$data["MEMBERSHIP"]["vd"] = $variableDiscountdetails;
					else
						$data["MEMBERSHIP"]["vd"] = 0;	
				}
				$data["MEMBERSHIP"]["tracking"] = MAILER_COMMON_ENUM::getMembershipTracking($mailerName);
			}
			
			if($widgetArray["sortPhotoFlag"])
				$users = $this->sortUsersListByPhoto($users);
                        
                        
			if($widgetArray["sortSubscriptionFlag"])
				$users = $this->sortUsersListBySubscription($users,SearchConfig::$jsBoostSubscription);
                        
			//if($widgetArray["logicLevelFlag"] && 0)
				//$users = $this->setUsersLogicalLevel($users,$operatorProfileObj,$mailerName);
			
                        $this->loadPartials();
			$data["USERS"] = $users;
			$data["COUNT"] = $usersCount;
                        
                        foreach($users as $profileID=>$ProfileData){
                                $Education = $ProfileData->getedu_level_new();
                                if($Education!="")
                                        $ProfileData->setEDUCATION($Education);
                        }
          
			if($widgetArray["googleAppTrackingFlag"])
			{
				
				$data["APP"]["ANDROID"]["ICON"] = $this->getIfShowAndroidIcon();
				$data["APP"]["ANDROID"]["TRACKING"] = MAILER_COMMON_ENUM::getGooglePlayTracking($mailerName);
				if($this->getIfShowIOSIcon())
				{
					$data["APP"]["IOS"]["ICON"] = 1;
					$data["APP"]["IOS"]["TRACKING"] = MAILER_COMMON_ENUM::getITunesTracking($mailerName);
				}
			}
			
			return $data;
			
		}
		else{
			return null;		
		}
	}

	/* This function is used to get saved search receivers to sent mail 
	*@param totalScript : total scripts executing for mailer cron
	*@param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getSavedSearchMailerReceivers($totalScript="",$script="",$limit='')
	{
		$savedSearchObj = new send_saved_search_mail();
		$recievers = $savedSearchObj->getMailerProfiles("",$totalScript,$script,$limit);
		return $recievers;
	}

	/* This funxtion is used update the sent flag(Y for sent and F for fail) for each savedSearch mail receiver
	*@param sno : serial number of mail
	*@param flag : sent status of the mail
	*/
	public function updateSentForSavedSearchUsers($sno,$flag,$searchId)
	{
		if(!$sno || !$flag)
			throw  new jsException("No sno/flag in updateSentForSavedSearchUsers() in savedSearchesMailerTask.class.php");
		$savedSearchObj = new send_saved_search_mail();
                $savedSearchObj->update($sno,$flag,$searchId);

	}

	public function getGifFlag($email)
	{
		if(strpos($email,"gmail"))
		{
			return ((date("d")%2));
		}
		else
		{
			return 0;
		}
	}


	/* This function is used to get featured profile receivers to send mail 
	*@param totalScript : total scripts executing for mailer cron
	*@param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getFeaturedProfileMailerReceivers($totalScript="",$script="",$limit='')
	{
		$featuredProfileObj = new FEATURED_PROFILE_MAILER("newjs_masterRep");
		$recievers = $featuredProfileObj->getMailerProfiles($totalScript,$script,$limit);
		return $recievers;
	}

	/* This funxtion is used update the sent flag(Y for sent and F for fail) for each featured Profile mail receiver
	*@param profileId : profileId of person to whom mail is sent
	*@param flag : sent status of the mail
	*/
	public function updateSentForFeaturedProfileUsers($profileId,$flag)
	{
		if(!$profileId || !$flag)
			throw  new jsException("No sno/flag in updateSentForSavedSearchUsers() in savedSearchesMailerTask.class.php");
		$featuredProfileObj = new FEATURED_PROFILE_MAILER("newjs_masterRep");
                $featuredProfileObj->update($profileId,$flag);

	}


	/* This function is used to get kundli match alert receivers to sent mail 
	*@param totalScript : total scripts executing for mailer cron
	*@param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getKundliAlertMailerReceivers($totalScript="",$script="",$limit='')
	{
		$kundliMailerObj = new KUNDLI_ALERT_KUNDLI_MATCHES_MAILER();
		$recievers = $kundliMailerObj->getMailerProfiles("",$totalScript,$script,$limit);
		return $recievers;
	}


	/* This funxtion is used update the sent flag(Y for sent and F for fail) for each savedSearch mail receiver
	*@param sno : serial number of mail
	*@param flag : sent status of the mail
	*/
	public function updateSentForKundliMatchesMailer($sno,$flag,$pid)
	{
		if(!$sno || !$flag)
			throw  new jsException("No sno/flag in updateSentForKundliMatchesMailer() in kundliAlertsMailerTask.class.php");
		$kundliMailerObj = new KUNDLI_ALERT_KUNDLI_MATCHES_MAILER();
                $kundliMailerObj->updateKundliMatchesUsersFlag($sno,$flag,$pid);

	}
        /**
         * This function sort profile on the basis of subscription
         * @param type $userList
         * @param type $subscription
         * @return type
         * @throws jsException
         */
        public function sortUsersListBySubscription($userList, $subscription)
	{
		if(!is_array($userList))
			throw  new jsException("No userList in sortUsersListBySubscription() function in RegularMatchAlerts.class.php");
                
		foreach($userList as $k=>$v)
		{
                        $subsCount = count(array_intersect($subscription,explode(",",$v->getSUBSCRIPTION())));
			if($subsCount>0 && $v->getHAVEPHOTO()== $this->photoPresent)
                        {
                                if($v->getPHOTO_DISPLAY()!= PhotoProfilePrivacy::photoVisibleIfContactAccepted)
					$sortArr[$v->getPROFILEID()] = 1;
				else
					$sortArr[$v->getPROFILEID()] = 2;
			}
			elseif($subsCount>0){
				$sortArr[$v->getPROFILEID()] = 3;
			}elseif($v->getHAVEPHOTO()== $this->photoUnderScreening)
			{
				$sortArr[$v->getPROFILEID()] = 4;
			}else
			{
				$sortArr[$v->getPROFILEID()] = 5;
			}
		}
		asort($sortArr);
		$i=0;
		foreach($sortArr as $k=>$v)
		{
			foreach($userList as $kk=>$vv)
			{
				if($vv->getPROFILEID()==$k)
				{
					$matchesDataFinal[$i]=$vv;
					$i++;
				}
			}
		}
		unset($userList);
		return $matchesDataFinal;
	}

	/* This function is used to get receivers for sending paid members mail
	*@param totalScript : total scripts executing for mailer cron
	*@param script : current script
	* @param limit : limit of receivers to send mail at a cron execution
	* @return recievers : array of receivers
	*/
	public function getpaidMembersMailerReceivers($totalScript="",$script="",$limit='')
	{
		$paidMembersMailerObj = new search_PAID_MEMBERS_MAILER("newjs_masterRep");
		$recievers = $paidMembersMailerObj->getMailerProfiles("",$totalScript,$script,$limit);
		return $recievers;
	}

	/* This function is used update the sent flag(Y for sent and F for fail) for each paidMember mail receiver
	*@param sno : serial number of mail
	*@param flag : sent status of the mail
	*@param profileId : profileId of the user
	*/
	public function updateSentForPaidMembersMailerReceivers($sno,$flag,$profileId)
	{
		if(!$sno || !$flag)
			throw  new jsException("No sno/flag in updateSentForPaidMembersMailerReceivers() in savedSearchesMailerTask.class.php");
		$paidMembersMailerObj = new search_PAID_MEMBERS_MAILER("newjs_masterRep");
                $paidMembersMailerObj->updatePaidMembersReceiverFlag($sno,$flag,$profileId);

	}
}
?>
