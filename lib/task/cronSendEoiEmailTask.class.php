<?php
/************************************************************************************************************************
 *    FILENAME           : cronSendEoiEmailTask.class.php
 *    INCLUDED           : connect.inc,contact.inc,payment_array.php
 *    DESCRIPTION        : Sends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail.
 *    NON SYMFONY FILE   : email_once_send.php
 *    CREATED BY         : Pankaj Khandelwal
 ***********************************************************************************************************************/
class cronSendEoiEmailTask extends sfBaseTask
{
    private $contacts;
    private $receiver;
    private $senderStatus;
    private static $maxSenderProfile = 5;
    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronSendEoiEmail';
        $this->briefDescription    = 'Sends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail';
        $this->detailedDescription = <<<EOF
      The [cronSendEoiEmail|INFO] tSends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail:

      [php symfony cron:cronSendEoiEmail] 
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    protected function execute($arguments = array(), $options = array())
    {
       ini_set("memory_limit","512M"); 
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
         //open rate tracking by nitesh as per vibhor        
            $cronDocRoot = JsConstants::$cronDocRoot;
            $php5 = JsConstants::$php5path;
            passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring EOI_MAILER#INSERT");
	    $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
            $instanceID = $countObj->getID('EOI_MAILER');
        
          $contactOnceObj   = new NEWJS_CONTACTS_ONCE;
	$memcacheObj = JsMemcache::getInstance();
	if($memcacheObj->get("cronSendEoi")>=19|| $memcacheObj->get("cronSendEoi") == null)
		$memcacheObj->set("cronSendEoi",-1);	
	for($i=0;$i<=19;$i++){
echo $i;
	if($memcacheObj->get("cronSendEoi")>=$i)
		continue;
        $this->contacts   = $contactOnceObj->getUnsentContacts(20,$i);
echo "\n".count($this->contacts);
        $this->receiver   = $this->getReceiverList();
echo "\n".count($this->receiver);
        $chunkSize        = 400;
        $chunkifyReceiver = array_chunk($this->receiver, $chunkSize);
	unset($this->receiver);
        foreach ($chunkifyReceiver as $chunk => $receiverList) {
            $contactIdArr = array();
            $contactIdArr = $this->getContactId($receiverList);
            $contactId    = implode("','", $contactIdArr);
            $shard        = JsDbSharding::getShardList();
            $contactType  = array();
            foreach ($shard as $key => $dbName) {
				$contactTypeArr = array();
                $contactsObj    = new newjs_CONTACTS($dbName);
                $contactTypeArr = $contactsObj->getContactTypeFromContactId($contactId);
                if(!empty($contactTypeArr))
					$contactType    = $contactType + $contactTypeArr;
            }
	    unset($contactTypeArr);
           // print_r($this->contacts); die();
            //$senderArr = $this->getSenderStatus($receiverList);
            foreach ($this->contacts as $key => $val) {
                $toSend = 1;
                if ($contactType[$val["CONTACTID"]]["TYP"] == 'I')
                    $this->contacts[$key]["SEND"] = 'Y';
                elseif (in_array($val["RECEIVER"], $receiverList) ) {
                    unset($this->contacts[$key]);
                    $notSendContactsId[] = $val["CONTACTID"];
                    $toSend = 0;
                }

               if($toSend && ($val["MESSAGE"] == "" || $val["MESSAGE"] == NULL) && $contactType[$val["CONTACTID"]]["MSG_DEL"] == "Y")
               {
                $jprofObj = NEWJS_JPROFILE::getInstance();
                $userName = $jprofObj->getUsername($val['SENDER']);
                $this->contacts[$key]["MESSAGE"] = Messages::getMessage(Messages::AP_MESSAGE,array('USERNAME'=>$userName));
               }
            }
            //print_r($this->contacts); die('dead');
        }
        unset($contactType);
        unset($chunkifyReceiver);
        if(!empty($notSendContactsId))
			$contactOnceObj->updateUnSentContactsOnce($notSendContactsId);
unset($notSendContactsId);
        $this->receiver = $this->getReceiverList();
//print_r($this->contacts);
//print_r($this->receiver);
echo "\n".count($this->receiver)."\n";
		if(!empty($this->receiver))
		{
            foreach ($this->receiver as $key => $val) {
				foreach ($this->contacts as $key1 => $val1) {
	                if ($val1["RECEIVER"] == $val) {
	                    $contactSender[]                 = $val1["SENDER"];
	                    $contactMessage[$val1["SENDER"]] = html_entity_decode($val1["MESSAGE"],ENT_QUOTES);
	                } 
	            }
	//print_r($contactMessage);
	            $status = $this->sendMail($val, $contactSender, $contactMessage,$instanceID);
	            unset($contactSender);
	            unset($contactMessage);
	            $contactOnceObj->updateContactsOnce($val,$status);
	        }
		}
		
unset($this->contacts);
unset($this->receiver);
$memcacheObj->set("cronSendEoi",$i);
}
				if($instanceID)
				{
					/** code for daily count monitoring**/
                       passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring EOI_MAILER");
                    /**code ends*/
				}
                unset($countArr);
 $contactOnceObj->deleteYesterData();
    }

    
    
    /*
     * Retrive the receiver list from the contacts list
     */
    protected function getReceiverList()
    {
		if(is_array($this->contacts))
		{
			$arr = array_values($this->contacts);
			foreach ($arr as $key => $val)
				$receiver[] = $val["RECEIVER"];
		}
        if(is_array($receiver))
        {
			$receiver1 = array_unique($receiver);
			$receiver  = array_values($receiver1);
		}
        return $receiver;
    }
    /*
     * Manage the contacts list arrange with receiver
     */
    protected function getContactsList()
    {
        foreach ($this->contacts as $key => $val) {
            $contactsList[][$val["RECEIVER"]] = $val;
        }
        return $contactsList;
    }
    /*
     * get the contacts id from the given list of contacts 
     * for the given set of receivers 
     */
    protected function getContactId($receiverList)
    {
        foreach ($this->contacts as $key => $val) {
            if (in_array($val["RECEIVER"], $receiverList))
                $contactsList[] = $val["CONTACTID"];
        }
        return $contactsList;
    }
    /*
     * sends mail to receiver 
     * get the list of sender and draft message 
     */
    private static function sendMail($viewedProfileId, $viewerProfileId, $draft,$instanceID)
    {
		$count      = count($viewerProfileId);
		if($count > self::$maxSenderProfile)
        {
			for($i=self::$maxSenderProfile; $i<$count; $i++)
			{
				unset($draft[$viewerProfileId[$i]]);
			}
		}	
		$profileObj = new Profile('', $viewedProfileId);
        $profileObj->getDetail('', '', 'SUBSCRIPTION');
        $profileMemcacheServiceObj = new ProfileMemcacheService($viewedProfileId);
		$totalCount = $profileMemcacheServiceObj->get("AWAITING_RESPONSE");
        $subscriptionStatus = $profileObj->getPROFILE_STATE()->getPaymentStates()->isPaid();
        if ($count == 1) {
            $emailSender = new EmailSender(MailerGroup::EOI, 1754);
        } else
            $emailSender = new EmailSender(MailerGroup::EOI, 1767);
        $tpl = $emailSender->setProfileId($viewedProfileId);
        if ($count == 1)
            $tpl->getSmarty()->assign("otherProfileId", $viewerProfileId[0]);
        $tpl->getSmarty()->assign("eoi_draft", $draft);
        $tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
        $tpl->getSmarty()->assign("count", $count);
        $tpl->getSmarty()->assign("totalCount",$totalCount);
        $tpl->getSmarty()->assign("instanceID", $instanceID);
        $variableDiscountObj = new VariableDiscount;
	$variableDiscount = $variableDiscountObj->getDiscDetails($viewedProfileId);

		if(!empty($variableDiscount))
		{
			$vdDisplayText = $variableDiscountObj->getVdDisplayText($viewedProfileId,'small');
			$discountMax = $variableDiscount["DISCOUNT"];
			$tpl->getSmarty()->assign("vdDisplayText",$vdDisplayText);
			$tpl->getSmarty()->assign("variableDiscount",$discountMax);
			$tpl->getSmarty()->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
			//$tpl->getSmarty()->assign("topSource","VDEOI1".$variableDiscount["DISCOUNT"]);
			//$tpl->getSmarty()->assign("BottomSource","VDEOI2".$variableDiscount["DISCOUNT"]);
			$tpl->getSmarty()->assign("topSource","VDEOI1".$discountMax);
			$tpl->getSmarty()->assign("BottomSource","VDEOI2".$discountMax);
		}
		else
		{
			$tpl->getSmarty()->assign("BottomSource","EOI2");
		}
        $partialObj = new PartialList();
        $partialObj->addPartial("eoi_profile", "eoi_profile", $draft);
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        
        if(CommonConstants::contactMailersCC)
        {
        $contactNumOb=new ProfileContact();
        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$viewedProfileId),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
        {
           $ccEmail =  $numArray['0']['ALT_EMAIL'];    
        }
        else $ccEmail = "";
        }
        else $ccEmail = "";
        $emailSender->send('','',$ccEmail);
        $status = $emailSender->getEmailDeliveryStatus();
        unset($emailSender);

        return $status;
    }
    
    
    
    protected function getSenderStatus($receiverList)
    {
		foreach ($this->contacts as $key => $val) {
            if (in_array($val["RECEIVER"], $receiverList))
                $senderList[] = $val["SENDER"];
        }
        $sender["PROFILEID"] = implode(',',$senderList);
        $multipleProfileObj = new JPROFILE();
        $profileDetail =$multipleProfileObj->getArray($sender,'','',"PROFILEID,ACTIVATED");
        for($i=0;$i<count($profileDetail);$i++)
        {
			$this->senderStatus[$profileDetail[$i]["PROFILEID"]] = $profileDetail[$i]["ACTIVATED"];
		}
	}
		
}
