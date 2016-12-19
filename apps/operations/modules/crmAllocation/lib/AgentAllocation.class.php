<?php
class AgentAllocation
{
	
	public function profileAllocation($processObj,$paramsArr=array())
	{
		$method         =$processObj->getMethod();
		$subMethod	=$processObj->getSubMethod();
			
		if($method=='TRANSFER_PROFILES')
			$this->updateCurrentAllocation($processObj);
		elseif($method=="UPSELL" || $method=="RENEWAL" || $method=="NEW_FAILED_PAYMENT" || $method=='FIELD_SALES' || $method == 'REALLOCATION' || $method=="WEBMASTER_LEADS")
		{
			// Special condition for CENTRAL_RENEWAL
			if($subMethod == 'CENTRAL_RENEWAL' || $subMethod=='CENTRAL_RENEWAL_MONTHLY'){
				// This is done to remove those profiles which are already allocated 
               	$mainAdminObj=new incentive_MAIN_ADMIN('newjs_slave');
               	$profiles = $processObj->getProfiles();
               	$newProfiles = array();
               	// This condition checks if profile is already Allocated in MAIN ADMIN, 
               	// if yes, then don't reallocate it again
               	foreach($profiles as $key=>$id){
               		if(!$mainAdminObj->checkIfProfileAlloted($id)){
               			$newProfiles[] = $id;
					}
               	}
               	$processObj->setProfiles($newProfiles);           	
               	$this->allocateProfiles($processObj);
            } else {
            	// General case
               	$this->allocateProfiles($processObj);
            }
		}
		elseif($method=='OUTBOUND' || $method=='MANUAL' || $method=='INBOUND' || $method=='MANUAL_EXT' || $method=='MANUAL_EXT_DAYS')
		{
			$mainAdminObj           	=new incentive_MAIN_ADMIN();
			$historyObj			=new incentive_HISTORY();
			$jprofileContactObj    		= new ProfileContact();
			$iProfileAlternateNumberObj	=new PROFILE_ALTERNATE_NUMBER();
			$crmDailyAllotObj       	=new CRM_DAILY_ALLOT();
			$serviceStatusObj		=new BILLING_SERVICE_STATUS();
			$profileid      		=$processObj->getProfiles();
			$agentName			=$processObj->getExecutive();
			$agentPrivilege = $paramsArr['PRIVILEGE'];

			if(!$profileid)
				$profileid		=$paramsArr['PROFILEID'];	
			if($paramsArr['PHONE_WITH_STD'])
				$paramsArr['RES_NO']	=$paramsArr['PHONE_WITH_STD'];
			if($paramsArr['PHONE_MOB'])
				$paramsArr['MOB_NO']	=$paramsArr['PHONE_MOB'];
			if($paramsArr['ALT_MOB'])
				$jprofileContactObj->updateAltMobile($profileid, $paramsArr['ALT_MOB']);
				$iProfileAlternateNumberObj->addPhoneNumber($profileid, $paramsArr['ALT_MOB'], $agentName);
			
                        /* Condition for different methods in manual
			   MANUAL method does not re-allot profile which is already alloted to agent
                           MANUAL_EXT method update only relax days for already alloted profile to himself
                           MANUAL_EXT_DAYS update only relax days for already alloted profile to himself
			*/
			
			if($paramsArr['WILL_PAY']=='FVD') {
				// Check for FVD done before else send SMS for survey
				$count = $historyObj->getCountOfDisposition($profileid,'FVD');
				if(!$count){
					$memHandlerObj = new MembershipHandler();
					$memHandlerObj->sendInstantSMS($profileid, $paramsArr['MOB_NO'], 'Rate your Jeevansathi visit at home experience. Take this short survey: https://www.surveymonkey.com/r/BNDXQG9');
					$mailerMsg = "Dear User,<br><br>We are contacting you to find out what you thought of the Jeevansathi visit at home experience.<br><br>Whatever you have to say, positive or negative, is important to us.<br><br>Giving this feedback should take no more than 15 seconds.<br>https://www.surveymonkey.com/r/BNDXQG9<br>Please help us in improving the experience we offer our customers by answering the survey.<br><br>Regards,<br>Team Jeevansathi";
					$subject = "Your Jeevansathi profile verification process is complete!";
				    $from = "info@jeevansathi.com";
				    $from_name = "Jeevansathi Info"; 
				    $to = $paramsArr['EMAIL'];
				    SendMail::send_email($to, $mailerMsg, $subject, $from,"","","","","","","1","",$from_name);
				}
			}

			$alreadyAlloted	=$mainAdminObj->get($profileid,"PROFILEID","PROFILEID,ALLOTED_TO,STATUS,ALLOT_TIME");
			if($alreadyAlloted)
			{
				if($method=='MANUAL_EXT' || $method=='MANUAL_EXT_DAYS')
        	                	$this->allocateRelaxDays($profileid,$alreadyAlloted['ALLOTED_TO'],$subMethod,$paramsArr['RELAX_DAYS']);
				else{
					$paramsArrNew =array("FOLLOWUP_TIME"=>$paramsArr['FOLLOWUP_TIME'],"STATUS"=>$paramsArr['STATUS'],"EMAIL"=>$paramsArr['EMAIL'],"MOB_NO"=>$paramsArr['MOB_NO'],"RES_NO"=>$paramsArr['RES_NO'],"ALTERNATE_NO"=>$paramsArr['ALTERNATE_NO'],"WILL_PAY"=>$paramsArr['WILL_PAY'],"REASON"=>$paramsArr['REASON'],"COMMENTS"=>$paramsArr['COMMENTS']);	

                                        if($method=='INBOUND' && ($alreadyAlloted['STATUS']=='F' && $paramsArr['STATUS']!='F')){
                                                unset($paramsArrNew['FOLLOWUP_TIME']);
                                                unset($paramsArrNew['STATUS']);    
                                        }
					$mainAdminObj->updateAllocation($paramsArrNew,$profileid);

					// code section to set expiry date on handling profile through Outbound links 
					if($method=='OUTBOUND'){
			                        $mainMemExpiryDate 	=$serviceStatusObj->getLastExpiry($profileid);
						$expiryDateSetw30 	=date('Y-m-d',time()+29*86400);
						if($mainMemExpiryDate>=date('Y-m-d',time()) && $mainMemExpiryDate<=$expiryDateSetw30){
                                			$subMethod	='SUB_EXPIRY';
							$deAllocationDt =$this->fetchDeAllocationDate($subMethod,$profileid,'','',$mainMemExpiryDate);
							$crmDailyAllotObj->updateExpectedDeallocationDt($profileid,$alreadyAlloted['ALLOTED_TO'],$alreadyAlloted['ALLOT_TIME'],$deAllocationDt);
						}	
					}
				}
			}
			else
				$this->executeAllocation($paramsArr,$method,$subMethod);
                        //For verification Seal
                        if($paramsArr['WILL_PAY']=='FVD') {
                                $fsoSetObj = new VerificationSealLib($profileid);
                                $fsoSetObj->setFsoVisitSeal();
                                $this->sendPostVerificationSMSAndMail($profileid, $paramsArr["ALLOTED_TO"], $paramsArr["USERNAME"]);
                        }
                        
                        if($method=='OUTBOUND'){
        	                $allocationTechObj 	=new incentive_PROFILE_ALLOCATION_TECH();
				$ftaAllocationTechObj 	=new incentive_FTA_ALLOCATION_TECH();

				$allocationTechObj->updateHandledStatus($profileid);
				$ftaAllocationTechObj->updateHandledStatus($profileid);
				$this->updateFailedPayment($profileid);
			}
			if($paramsArr['ALTERNATE_NO']){
                        	$alternateNumObj =new PROFILE_ALTERNATE_NUMBER();
                                $alternateNumObj->addPhoneNumber($profileid,$paramsArr['ALTERNATE_NO'],$agentName);
			}
                        if($paramsArr['ORDERS'])
                                $this->updateOrderStatus($profileid,$paramsArr['WILL_PAY']);    

			if($method!='MANUAL_EXT_DAYS' || ($method=='MANUAL_EXT' && !$alreadyAlloted)){
				// Code added for tracking process of executive while disposition
				$paramsArr['MODE'] .= $this->getModeProcess($paramsArr['PRIVILEGE']);
				$historyObj->addAllocationHistory($paramsArr);
			}
		}
	}
	public function fetchAllotedBucketDays($subMethod='',$bucketType='AP',$profileid='',$method='')
	{
                if($method=='OUTBOUND' && $subMethod==''){
                }
                else{
                	if($profileid && $subMethod!='UPSELL' && $subMethod!='NEW_FAILED_PAYMENT' && $subMethod!='FIELD_SALES' && $subMethod!="WEBMASTER_LEADS" && $subMethod!='CENTRAL_RENEWAL' && $subMethod!='CENTRAL_RENEWAL_MONTHLY'){
                	        $agentAllocDetailsObj =new AgentAllocationDetails();
                	        $paidStatus =$agentAllocDetailsObj->checkPaidProfile($profileid);
                	        if($paidStatus)
                	                $subMethod='RENEWAL';
                	}
		}

		if($subMethod=='UPSELL' || $subMethod=='CENTRAL_RENEWAL' || $subMethod=='CENTRAL_RENEWAL_MONTHLY')
			$processId ='U';
		elseif($subMethod=='RENEWAL' || $subMethod=='RENEWAL_NOT_DUE' || $subMethod=='SUB_EXPIRY')
			$processId ='R';
		else
			$processId ='S';

		$allocBucketObj 	=new incentive_ALLOCATION_BUCKET('newjs_slave');
		$result 		=$allocBucketObj->get($processId);
		$allocDay 		=$result[$bucketType]['VALUE'];
		return $allocDay;	
	}
	public function fetchDeAllocationDate($subMethod='', $profileid='', $allotTime='', $relaxDays='',$expiryDate='',$method='')
	{
		if(!$allotTime)
			$allotTime =date("Y-m-d");
		if($method=='OUTBOUND' && $subMethod==''){
		}
		else{
			if($profileid && $subMethod!='UPSELL' && $subMethod!='NEW_FAILED_PAYMENT' && $subMethod!='FIELD_SALES' && $subMethod!="WEBMASTER_LEADS" && $subMethod!='CENTRAL_RENEWAL' && $subMethod!='CENTRAL_RENEWAL_MONTHLY'){
	        	        $agentAllocDetailsObj =new AgentAllocationDetails();
        		        $paidStatus =$agentAllocDetailsObj->checkPaidProfile($profileid);
				if($paidStatus)
                		        $subMethod='RENEWAL';
			}
		}

		$allocationDays =$this->fetchAllotedBucketDays($subMethod);
		if($expiryDate)
			$allotTime =$expiryDate;
		elseif($subMethod=='RENEWAL' || $subMethod=='RENEWAL_NOT_DUE' || $subMethod=='SUB_EXPIRY' || $subMethod=='UPSELL' || $subMethod=='CENTRAL_RENEWAL' || $subMethod=='CENTRAL_RENEWAL_MONTHLY'){
			$serviceStatusObj 	=new BILLING_SERVICE_STATUS('newjs_slave');
			$expiryDate		=$serviceStatusObj->getLastExpiry($profileid);
			$allotTime		=$expiryDate;
		}
		else
			$allocationDays =$allocationDays-1;

		if($relaxDays)
			$allocationDays +=$relaxDays;

		if($allotTime=='2099-01-01')
			$deAllocDate =$allotTime;
		else	
			$deAllocDate =date("Y-m-d",JSstrToTime("$allotTime + $allocationDays days"));
		return $deAllocDate;
	}
	public function updateViewedStatus($profileid) 
	{
                $unallotedFreeOnlineObj =new jsadmin_UNALLOTED_FREE_ONLINE_VIEWED();
                $unallotedFreeOnlineObj->insertUnallotedProfile($profileid);
	}
	public function updateOrderStatus($profileid,$willPay)
	{
		$ordersObj =new BILLING_ORDERS();
                if($willPay)
                	$val="P";
                else
                	$val="D";
                $ordersObj->updateOrder(array("STATUS"=>"$val"),$profileid);
	}

	public function updateFailedPayment($profileid)
	{
		$failedPaymentObj =new incentive_UNALLOTED_FAILED_PAYMENT();
		$failedPaymentObj->editFailedPayment($profileid);
	}
	public function allocateProfiles($processObj)
        {
		$jprofileObj            =new JPROFILE('newjs_masterRep');

		// Queries cannot be moved to slave- due to slave lag spike arises in FS allocation
		$lastAgentAllotedObj    =new AGENT_ALLOTED();
		$manualAllotObj 	=new MANUAL_ALLOT();
		// End

		$currentExec		=0;
		$method			=$processObj->getMethod();
		$executives             =$processObj->getExecutives();
		$totalExecutives        =count($executives);
		//$executives =array("amuda");
		if($method=='FIELD_SALES'){
			$mainAdminObj   =new incentive_MAIN_ADMIN('newjs_masterRep');
			$center		=$processObj->getCenter();
			$allocationLimit=$processObj->getLimit();
			if($totalExecutives>0)
				$agentTotalAllocArr =$manualAllotObj->getTotalAllocationCntForFieldSales($executives);
		}
		if($subMethod=='CENTRAL_RENEWAL' || $subMethod=='CENTRAL_RENEWAL_MONTHLY'){
			$lastAllotedAgent	=$lastAgentAllotedObj->getLastAgentAlloted('REALLOCATION',$center);	
		} else {
			$lastAllotedAgent	=$lastAgentAllotedObj->getLastAgentAlloted($method,$center);
		}
		$lastExecutive		=$totalExecutives-1;
		if($lastAllotedAgent && $totalExecutives)
			$currentExec	=array_search($lastAllotedAgent,$executives);
		if($currentExec==$lastExecutive)
			$currentExec=0;
		else
			$currentExec+=1;
                $profiles		=$processObj->getProfiles();
		$subMethod 		=$processObj->getSubMethod();
		$fields			="PHONE_RES,PHONE_MOB,EMAIL";
                if($method=="UPSELL"){
                        $paramsArr['STATUS']='U';
                        $paramsArr['COMMENTS']="Unalloted Upsell Profile";
			$paramsArr['CALL_SOURCE']="U";
                }elseif($method == 'REALLOCATION'){
                		$paramsArr['STATUS']='U';
                        $paramsArr['COMMENTS']="Central Renewal Allocation";
						$paramsArr['CALL_SOURCE']="U";
                }
                elseif($method=="RENEWAL"){
			$paramsArr['STATUS']='R';
                        $paramsArr['COMMENTS']="Unalloted Renewal Profile";
			$paramsArr['RELAX_DAYS']=0;
			$paramsArr['CALL_SOURCE']="RC";
                }
                elseif($method=="NEW_FAILED_PAYMENT"){
                        $paramsArr['STATUS']='FP';
                        $paramsArr['COMMENTS']="Unalloted failedPayment Profile";
                        $paramsArr['RELAX_DAYS']=0;
                        $paramsArr['CALL_SOURCE']="FP";
			$paramsArr['ORDERS']="N";
			$paramsArr['TIMES_TRIED']="1";
                }
		elseif($method=="WEBMASTER_LEADS"){
                        $paramsArr['STATUS']='WL';
                        $paramsArr['COMMENTS']="Unalloted webMaster Leads";
                        $paramsArr['RELAX_DAYS']=0;
                        $paramsArr['CALL_SOURCE']="WL";
                        $paramsArr['ORDERS']="N";
                        $paramsArr['TIMES_TRIED']="1";
                }
		elseif($method=="FIELD_SALES"){
                        $paramsArr['STATUS']='FS';
                        $paramsArr['COMMENTS']="Unalloted fieldSales Profile";
                        $paramsArr['RELAX_DAYS']=0;
                        $paramsArr['CALL_SOURCE']="FS";
		}
		$allot_to ='';
		if($method=="WEBMASTER_LEADS")
			$profiles = array_unique($profiles);
		if(count($profiles)>0)
		{
                for($i=0;$i<count($profiles);$i++){
			$profileid=$profiles[$i];
			if($profileid)
			{
			if($subMethod=="FIELD_SALES"){
				for($j=$currentExec; $j<$totalExecutives; $j++){
					
					$allot_to =$executives[$j];
					$agentTotalAllocCnt =$agentTotalAllocArr[$allot_to];
					if($agentTotalAllocCnt>=$allocationLimit){
        	                        	unset($executives[$j]);
        	                        	$executives =array_values($executives);
        	                        	$totalExecutives =count($executives);
						$checkCnt =$j;
						$j--;
						if($checkCnt == $totalExecutives){
							$j = 0;
							$j--;
						}
					}		
					else
						break;
				}
				$currentExec =$j;
			}
			$allot_to=$executives[$currentExec];
			if($allot_to && $totalExecutives>0){
				$result=$jprofileObj->get($profileid,"PROFILEID",$fields);
				$paramsArr['PROFILEID']=$profileid;
				$paramsArr['EMAIL']=$result['EMAIL'];
				$paramsArr['RES_NO']=$result['PHONE_RES'];
				$paramsArr['MOB_NO']=$result['PHONE_MOB'];
				$paramsArr['ALLOT_TIME']=date("Y-m-d H:i:s ",time());
				$paramsArr['MODE']='O';
				$paramsArr['ALLOTED_BY']='jstech';
				$paramsArr['ALLOTED_TO']=$allot_to;
				//echo $allot_to."+".$currentExec;
				$this->executeAllocation($paramsArr,$method,$subMethod);
				$agentTotalAllocArr[$allot_to]++;
			}
			else
				break;
			$currentExec++;
			if($currentExec == $totalExecutives)
				$currentExec = 0;
			}
		}
		}
		if($allot_to)
			$lastAgentAllotedObj->updateLastAllotedAgent($allot_to,$method,$center);
	}
        public function executeAllocation($paramsArr,$method,$subMethod='')
        {
                $paramsArr['ALLOCATION_DAYS']    =$this->fetchAllotedBucketDays($subMethod,'AP',$paramsArr['PROFILEID'],$method);
                $paramsArr['DE_ALLOCATION_DT']   =$this->fetchDeAllocationDate($subMethod,$paramsArr['PROFILEID'],$paramsArr['ALLOT_TIME'],'','',$method);

                $mainAdminObj		=new incentive_MAIN_ADMIN();
                $mainAdminPoolObj	=new incentive_MAIN_ADMIN_POOL();
                $crmDailyAllotObj	=new CRM_DAILY_ALLOT();

                $mainAdminObj->allocateProfile($paramsArr);
                $crmDailyAllotObj->insertProfile($paramsArr);
                
                $mainAdminPoolObj->updateAllotmentStatus($paramsArr['PROFILEID']);

		if($method=="UPSELL" || $method=="RENEWAL" || $method=="MANUAL" || $method=="MANUAL_EXT" || $method=='NEW_FAILED_PAYMENT' || $method=='FIELD_SALES' || $method=='REALLOCATION' || $method=="WEBMASTER_LEADS"){
			$manAllotObj=new MANUAL_ALLOT();
		        $manAllotObj->insertProfile($paramsArr);
			if($method=='FIELD_SALES'){
				$this->senInstantSmsAndMailer($paramsArr['PROFILEID'],$paramsArr['ALLOTED_TO']);
				// field sales allocation logging
				$fieldSalesAllocLog =new incentive_FIELD_SALES_LOG();
				$fieldSalesAllocLog->insertRecord($paramsArr['PROFILEID'], $paramsArr['ALLOT_TIME']); 	
			}

		}
		elseif($method=='INBOUND'){
			$inboundAllotObj=new incentive_INBOUND_ALLOT();
			$inboundAllotObj->insertProfile($paramsArr);
		}
        }
	public function allocateRelaxDays($profileid,$agentName,$subMethod='',$extensionPeriod=0)
	{
		$extensionLimit         =$this->fetchAllotedBucketDays($subMethod,'EP');
		$crmDailyAllotObj       =new CRM_DAILY_ALLOT();
		$lastAllotedDetails     =$crmDailyAllotObj->getLastAllocationDetails($profileid);
		$relaxDays              =$lastAllotedDetails['RELAX_DAYS'];
		$totalRelaxDays		=$relaxDays+$extensionPeriod;
		if($totalRelaxDays>$extensionLimit)
			$extensionDays =$extensionLimit-$relaxDays;
		elseif(($totalRelaxDays<=$extensionLimit) && $extensionPeriod>0)
			$extensionDays =$extensionPeriod;
		elseif(($totalRelaxDays<=$extensionLimit) && $extensionPeriod==0)
			$extensionDays =$extensionLimit-$relaxDays;
		$crmDailyAllotObj->updateRelaxDays($extensionDays,$profileid,$agentName);
	}
        public function updateCurrentAllocation($processObj)
        {
		$subMethod		=$processObj->getSubMethod();
                $executives		=$processObj->getExecutives();
                $profiles       	=$processObj->getProfiles();
		$allotedDt		=$processObj->getCurDate();
		$executive		=$executives[0];	

		$mainAdminObj   	=new incentive_MAIN_ADMIN();
		$crmDailyAllotObj       =new CRM_DAILY_ALLOT();
		$manAllotObj		=new MANUAL_ALLOT();
		$agentAllocDetailsObj 	=new AgentAllocationDetails();
		$agentAllotedObj 	=new AGENT_ALLOTED();
		$jsadminPswrdsObj 	=new jsadmin_PSWRDS();
		$agentDetails 		=$agentAllocDetailsObj->fetchExecutiveDetails($executive);	
		$agentDetailsArr[$executive] =$agentDetails;

		if($processObj->getMethod() == "TRANSFER_PROFILES" && $processObj->getSubMethod() == "FRESH")
		{
			$incPATObj = new incentive_PROFILE_ALLOCATION_TECH();
			for($i=0;$i<count($profiles);$i++)
				$incPATObj->updateAllotedAgent($executive, $profiles[$i]);
			$processObj->setTransferredProfilesCount(count($profiles));
			$processObj->setRemainingTransferrableLimit(0);
		}
		elseif($processObj->getMethod() == "TRANSFER_PROFILES" && $processObj->getSubMethod() == "FIELD_SALES"){
			$transferredProfiles = array();
			$execCenter = $jsadminPswrdsObj->getExecutiveDetails($executive);
			$execLimit = $agentAllotedObj->getAllocationLimitForCenter($execCenter['SUB_CENTER']);
			$execProfilesCount = $mainAdminObj->getTotalAllocationCnt("FS",array($executive));
			$execProfilesCount = $execProfilesCount[$executive];
			if($execProfilesCount == NULL)
				$execProfilesCount = 0;
			$actualLimit = $execLimit - $execProfilesCount;
			$toBeAllotedProfilesCnt = count($profiles);
			if($toBeAllotedProfilesCnt < $actualLimit){
				$newCnt = $toBeAllotedProfilesCnt;
			} else {
				$newCnt = $actualLimit;
			}
			if($newCnt > 0){
				for($i=0;$i<$newCnt;$i++){
					$transferredProfiles[] = $profiles[$i];
					$profileid=$profiles[$i];
					$mainAdminObj->updateAllotedAgent($profileid,$executive,$allotedDt);
					$crmDailyAllotObj->updateAllotedAgent($profileid,$executive,$allotedDt);
					$manAllotObj->updateAllotedAgent($profileid,$executive,$allotedDt);
					$this->senInstantSmsAndMailer($profileid,$executive,$agentDetailsArr);
				}
			}
			// Calculating remaining allotements that can be made for executive
			$execProfilesCount = $mainAdminObj->getTotalAllocationCnt("FS",array($executive));
			$execProfilesCount = $execProfilesCount[$executive];
			$remainingLimit = $execLimit - $execProfilesCount;
			$processObj->setRemainingTransferrableLimit($remainingLimit);
			$processObj->setTransferredProfilesCount($newCnt);
			$processObj->setProfiles($transferredProfiles);
		} else {
			$totalCount = count($profiles);
			for($i=0;$i<count($profiles);$i++){
				$profileid=$profiles[$i];
				$mainAdminObj->updateAllotedAgent($profileid,$executive,$allotedDt);
				$crmDailyAllotObj->updateAllotedAgent($profileid,$executive,$allotedDt);
				$manAllotObj->updateAllotedAgent($profileid,$executive,$allotedDt);
				//$this->senInstantSmsAndMailer($profileid,$executive,$agentDetailsArr);
			}
			$processObj->setTransferredProfilesCount($totalCount);
			$processObj->setRemainingTransferrableLimit(0);
		}
	}
	public function senInstantSmsAndMailer($profileId,$agentName,$agentDetailsArr='')
	{
		// sms functionality integrated 
                include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
                $sms=new InstantSMS("AGENT_KYC",$profileId,array("SANAME"=>$agentName));
                $sms->send();

		//Mailer functionality integrated
		$loginProfile = new JPROFILE();
		$jprofileDetails =$loginProfile->get($profileId,"PROFILEID","GENDER,MSTATUS,USERNAME,CONTACT,PINCODE,PHONE_MOB,PHONE_WITH_STD");

		// get Other Phone Numbers -Start
		$agentAllocDetailsObj =new AgentAllocationDetails();
		$otherPhoneNumber =$agentAllocDetailsObj->getOtherPhoneNums($profileId);
		$altMobileDetails =$agentAllocDetailsObj->fetchJprofileContact(array($profileId));		
		$altMobileNumber =$altMobileDetails[$profileId]['ALT_MOBILE'];
		// get Other Phone Numbers -End
		
		if(!$agentDetailsArr){
			//$agentAllocDetailsObj =new AgentAllocationDetails();
			$agentDetails =$agentAllocDetailsObj->fetchExecutiveDetails($agentName);
		}
		else
			$agentDetails =$agentDetailsArr[$agentName];
	
                $pictureObj =new PictureFunctions();
                $photoUrl =$pictureObj->getCloudOrApplicationCompleteUrl($agentDetails["PHOTO_URL"]);

		$emailSender = new EmailSender(MailerGroup::SCREENING_KYC,1776);
		$emailTpl=$emailSender->setProfileId($profileId);
		$smartyObj = $emailTpl->getSmarty();
		$smartyObj->assign("relationManagerPicUrl",$photoUrl);
		$smartyObj->assign("relationManagerNumber",$agentDetails["PHONE"]);
		$smartyObj->assign("relationManagerName",$agentDetails["FIRST_NAME"]." ".$agentDetails["LAST_NAME"]);
		$smartyObj->assign("gender",$jprofileDetails['GENDER']);
		$smartyObj->assign("maritalStatus",$jprofileDetails['MSTATUS']);
		$emailSender->send();

		// Field Sales Email sender to executive
		$agentEmail =$agentDetails['EMAIL'];
	        $excEmailSender = new EmailSender(MailerGroup::SCREENING_KYC, '1798');
	        $excEmailTpl = $excEmailSender->setProfileId($profileId);
	        $smartyObjEx = $excEmailTpl->getSmarty();
		$smartyObjEx->assign("username",$jprofileDetails['USERNAME']);
		$smartyObjEx->assign("address",$jprofileDetails['CONTACT']);
		$smartyObjEx->assign("pincode",$jprofileDetails['PINCODE']);
		if($jprofileDetails['PHONE_MOB'])
			$phoneNumberArr[] =$jprofileDetails['PHONE_MOB'];
		if($jprofileDetails['PHONE_WITH_STD'])
			$phoneNumberArr[] =$jprofileDetails['PHONE_WITH_STD'];
		if($altMobileNumber)
			$phoneNumberArr[] =$altMobileNumber;
		if($otherPhoneNumber)
			$phoneNumberArr[] =$otherPhoneNumber;
		$phoneNumberArrUn =array_unique($phoneNumberArr);
		$phoneNumberStr =implode(",",$phoneNumberArrUn);
		$smartyObjEx->assign("phoneNumberStr",$phoneNumberStr);
	        $excEmailSender->send($agentEmail);
	}
    
    public function sendPostVerificationSMSAndMail($profileid, $name, $username)
    {
        //jsc-459
        $fvdSmsObj = new incentive_FVD_SMS_SENT_LIST();
        if(!$fvdSmsObj->smsAlreadySent($profileid))
        {
                        include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
                        $sms=new InstantSMS("FVD",$profileid,array("USERNAME"=>$username));
                        $sms->send();
            $fvdSmsObj->smsNowSent($profileid);
        }
        //end
        
        //JSC-679
        $agentAllocationDetailsObj = new AgentAllocationDetails();
        $executiveDetails = $agentAllocationDetailsObj->fetchExecutiveDetails($name);
        $executiveFullName = $executiveDetails["FIRST_NAME"]." ".$executiveDetails["LAST_NAME"];
        $mailerMsg = "Dear User,<br><br>".$executiveFullName." has completed the verification procedure for your profile ".$username.". Your profile has now been marked 'Verified' on the website.<br><br>Regards<br>Team Jeevansathi";
        $jprofileObj = new JPROFILE("newjs_slave");
		$jprofileDetails =$jprofileObj->get($profileid,"PROFILEID","EMAIL");
        $to = $jprofileDetails["EMAIL"];
        $subject = "Your Jeevansathi profile verification process is complete!";
        $from = "info@jeevansathi.com";
        $from_name = "Jeevansathi Info";
        $canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to,"EMAIL_TYPE"=>"29"),$profileid);
        $canSend = $canSendObj->canSendIt();
        if($canSend)
        {
	        SendMail::send_email($to, $mailerMsg, $subject, $from,"","","","","","","1","",$from_name);
	}
    }

    public function getModeProcess($priv){
    	if(strpos($priv, 'ExcDIb') !== false){
            return crmParams::$processFlag['INBOUND_TELE'];
        }
        else if(strpos($priv, 'ExcBSD') !== false || strpos($priv, 'ExcBID') !== false){
           	return crmParams::$processFlag['CENTER_SALES'];
        }
        else if(strpos($priv, 'ExcFP') !== false){
        	return crmParams::$processFlag['FP_TELE'];
        }
        else if(strpos($priv, 'ExcRnw') !== false){
        	return crmParams::$processFlag['CENTRAL_RENEW_TELE'];
        }
        else if(strpos($priv, 'ExcFld') !== false){
        	return crmParams::$processFlag['FIELD_SALES'];
        }
        else if(strpos($priv, 'ExcFSD') !== false || strpos($priv, 'ExcFID') !== false){
        	return crmParams::$processFlag['FRANCHISEE_SALES'];
        }
        else if(strpos($priv, 'ExcDOb') !== false || strpos($priv, 'ExcPrm') !== false || strpos($priv, 'PreNri') !== false){
        	return crmParams::$processFlag['OUTBOUND_TELE'];
        }
        else{
        	return crmParams::$processFlag['UNASSISTED_SALES'];
        }
    }
}
