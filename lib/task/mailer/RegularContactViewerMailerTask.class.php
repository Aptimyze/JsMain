<?php

/**
 This task is used to send regular contact Viewers mailer 
 *@author : Reshu Rajput
 *created on : 20 May 2014 
 */
class RegularContactViewerMailerTask extends sfBaseTask
{
    private $smarty;
    private $mailerName = "CONTACTVIEWERS";
    private $preHeader="Please add contacts@jeevansathi.com to your address book to ensure delivery of this mail into you inbox";
  	private $tupleName = "MATCHALERT_MAILER_TUPLE";
  	private $limitNoOfTuples=10;

  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'RegularContactViewerMailer';
    $this->briefDescription = 'regular contact Viewer mailer';
    $this->detailedDescription = <<<EOF
      The task send contactViewers mailer .
      Call it with:

      [php symfony mailer:RegularContactViewerMailer totalScript currentScript] 
EOF;
    $this->addArguments(array(
		new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                ));
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
	$LockingService = new LockingService;
        $file = $this->mailerName."_".$totalScript."_".$currentScript.".txt";
        $lock = $LockingService->getFileLock($file,1);
        if(!$lock)
        	successfullDie();
	// match alert configurations
    $this->setReceiversData($currentScript, $totalScript);
	$mailerServiceObj = new MailerService();
	$stypeMatch = SearchTypesEnums::contactViewerMailer;
	$this->smarty = $mailerServiceObj->getMailerSmarty();

	/** code for daily count monitoring**/
        $cronDocRoot = JsConstants::$cronDocRoot;
        $php5 = JsConstants::$php5path;
        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring CONTACT_MAILER#INSERT");
        $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceID = $countObj->getID('CONTACT_MAILER');
		$this->smarty->assign('instanceID',$instanceID);

	/**code ends*/	

	$temp=new DateTime();
	$day=$temp->format('d'); 
	$month=substr($temp->format('F'),0,3); 
	$mailtrackObj=new MAIL_contactViewers('newjs_master');
	$mailtrackObj->EmptyMailerCV();
	if(is_array($this->receiversData))
	{	
		$mailerLinks = $mailerServiceObj->getLinks();
		$this->smarty->assign('mailerLinks',$mailerLinks);
		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"membershipFlag"=>true,"openTrackingFlag"=>true,"googleAppTrackingFlag"=>true);
		foreach($this->receiversData as $pid=>$value)
		{
			$data = $this->getRecieverDetails($pid,$value,$this->mailerName,$widgetArray);
			if(!$data)continue;
			if(is_array($data))
			{
				//Common Parameters required in mailer links
				$data["stypeMatch"] =$stypeMatch;
				$count=$this->countsArray[$pid];
				$data["body"]="Following members have viewed your contacts in the last 24 hours. If you haven't already received a call, you may send an interest or view their contacts details and call them.";
				$subject ='=?UTF-8?B?' . base64_encode($count." member(s) viewed your contact(s) yesterday, You may send them an interest. | $day $month" ) . '?='; 
				$this->smarty->assign('data',$data);
				$this->smarty->assign('PREHEADER',$this->preHeader);
				$msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
				$sent=$this->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid);
				$mailtrackObj->InsertMailerCV($pid,$sent);
			}
			unset($subject);
			unset($mailSent);
			unset($data);
	 	}
		/** code for daily count monitoring**/
		passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring CONTACT_MAILER");
		/** code end**/
	}
  }

 
public function setReceiversData($currentScript,$totalScript)
{
		$endDate=(new DateTime())->format('Y-m-d H:i:s');
		$tmp=(new DateTime())->sub(new DateInterval('P1D'));
		$startDt=$tmp->format('Y-m-d H:i:s');
		$dbObj=new JSADMIN_VIEW_CONTACTS_LOG();
		$arr=$dbObj->getContactViewsForTimePeriod($startDt,$endDate,$currentScript,$totalScript);
			foreach ($arr as $key => $value) 
			{
			if(!$this->countsArray[$value['VIEWED']])$this->countsArray[$value['VIEWED']]=0;
			$this->countsArray[$value['VIEWED']]++;	
			if(count($temp[$value['VIEWED']]) >= ($this->limitNoOfTuples)) continue;
			$temp[$value['VIEWED']][]=$value['VIEWER'];
			}

		$this->receiversData=$temp;
		unset($temp);
		unset($arr);
}


	public function getRecieverDetails($pid,$values,$mailerName,$widgetArray)
	{
		if(!$pid || !is_array($values) || !$mailerName)
			throw new jsException("No pid/values/mailerName passed in getRecieverDetails RegularMatchAlerts.class.php");
		
		$mailerServiceObj = new MailerService();
		$operatorProfileObj = Operator::getInstance('newjs_master',$pid);
                if(!$operatorProfileObj)
                                throw new jsException("Invalid pid passed in getRecieverDetails RegularMatchAlerts.class.php");

		$operatorProfileObj = $mailerServiceObj->getReceiverInfoWithName($operatorProfileObj,$widgetArray["nameFlag"]);
		$userFieldLabel = MAILER_COMMON_ENUM::getUserFieldLabel($mailerName);
		$this->setUsersToSend($values,$userFieldLabel);
		$users = $this->getUsersListToSend($operatorProfileObj,$widgetArray["filterGenderFlag"]);
		$usersCount = sizeof($users);
        if($usersCount >0)
                {
			$data = array();
			$receiverProfilechecksum = JsAuthentication::jsEncryptProfilechecksum($pid);
                        $emailId = $operatorProfileObj->getEMAIL();
			$data["RECEIVER"]["PROFILE"] = $operatorProfileObj;
			
			if($operatorProfileObj->getACTIVATED()!='Y')
				return null;
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
                        
			//if($widgetArray["logicLevelFlag"] && 0)
				//$users = $this->setUsersLogicalLevel($users,$operatorProfileObj,$mailerName);
			$this->loadPartials();
          	$data["USERS"] = $users;
			$data["COUNT"] = $usersCount;
                        
                        foreach($users as $profileID=>$ProfileData){
                                $Education = $this->getEducationDetails($ProfileData->getPROFILEID());
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
		else
			return null;		
	}


public function getUsersListToSend($profileObj,$filterGenderFlag=false)
	{
		if(!is_array($this->userList) && !$profileObj)
			throw  new jsException("No userList or profile in getUsersListToSend() function in RegularMatchAlerts.class.php");

		if($profileObj->getGENDER()=='F')
			$requiredGender= "M";
		else
			$requiredGender= "F";
		$tupleService = new TupleService();
		$tupleService->setLoginProfileObj($profileObj);
		$tupleFields     = $tupleService->getFields($this->tupleName);
		$tupleService->setProfileInfo($this->userList,$tupleFields);
		unset($this->userList);
		$tuplesValues = $tupleService->getMATCH_ALERT();
		if(is_array($tuplesValues))
		{
			foreach($tuplesValues as $tuples=>$tupleObj)
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
				
			}

			return $tuplesValues;
		}
		else
			return null;				
	}
	
	public function setUsersToSend($values)
	{
		foreach($values as $key=>$v)
		{
			
				$this->userList["MATCH_ALERT"][$v]=Array("PROFILEID"=>$v);
				$this->userIds[]=$v;
		}
	}

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

		private function loadPartials()
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

	/*This function is used to send mail and verify if its fails
	* If fail count is more than limit than a mail is fired
	*@param $emailID : email id of the receiver
	*@param $msg : mail body
	*@param $subject : mail subject
	*@param $mailerName : name of mailer to find mailer send details
	*@return $flag: "Y" or "F" if mail sent is success or fail respectively
	*/
	public function sendAndVerifyMail($emailID,$msg,$subject,$mailerName,$pid="")
	{
		$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$emailID,"EMAIL_TYPE"=>$mailerName),$pid);
		$canSend = $canSendObj->canSendIt();
		if($canSend)
		{
			$senderDetails = MAILER_COMMON_ENUM::getSenderEnum($mailerName);
        	        // Sending mail and tracking sent status
                        
                        if(CommonConstants::contactMailersCC)
                        {    

                	                $contactNumOb=new ProfileContact();
                        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$pid),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
                        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
                        {
                           $ccEmail =  $numArray['0']['ALT_EMAIL'];    
                        }
                        else
                            $ccEmail = "";
                        }
                        else $ccEmail = "";

                        $mailSent = SendMail::send_email($emailID,$msg,$subject,$senderDetails["SENDER"],$ccEmail,'','','','','','1','',$senderDetails["ALIAS"]);
	                $flag= $mailSent?"Y":"F";
        	        if($flag =="F")
                		$this->failCount++;
			if($this->failCount > MAILER_COMMON_ENUM::$MAIL_FAIL_LIMIT)
        	        {
                	        SendMail::send_email("palashc2011@gmail.com,niteshsethi1987@gmail.com","$mailerName Failed more than limit","$mailerName failed",$senderDetails["SENDER"]);
                 	       die;
	                }
		}
		else
			$flag='B';
		return $flag;
	}





}
	
