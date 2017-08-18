<?php
/**
* This task sends sms and email to users who have been requested for horoscope
* @author : Bhavana Kadwal
* @package Monitoring
* @since 2016-05-26
*/
class SendHoroscopeRequestTask extends sfBaseTask
{
  const HOROSCOPEMAILID = 1833;
  private $shardsArray = array(0,1,2);
  private $noOfDays = 1;
  private $emailSenderObj;
  protected function configure()
  {
    $this->addArguments(array(
		new sfCommandArgument('shardId', sfCommandArgument::REQUIRED, 'shard to be selected'),
		));

    $this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
    $this->namespace        = 'mailer';
    $this->name             = 'SendHoroscopeRequest';
    $this->briefDescription = '';
    // currentscript is the shard to be selected
    $this->detailedDescription = <<<EOF
The [mailer:SendHoroscopeRequest|INFO] task sends email and sms to horoscope requested users.
Call it with:

  [php symfony mailer:SendHoroscopeRequest shardId| possible value of shardId is 0,1,2]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $shardId = $arguments["shardId"]; // current script number	either 0 or 1 or 2
        if(!in_array($shardId, $this->shardsArray))
        {
                exit;
        }
        
        $dt=date('Y-m-d',JSstrToTime('now -'.$this->noOfDays.' days'));
        $activeServerId = $shardId;
        $dbName = JsDbSharding::getShardDbName($activeServerId,1);
   
        //initiate sms and mailer class
        include_once (JsConstants::$docRoot."/profile/InstantSMS.php");
        
        $this->emailSenderObj = new EmailSender(MailerGroup::HOROSCOPE_REQUEST,self::HOROSCOPEMAILID); // 1830 is mailer_link table id
        
        
        $horoscopeReqObj = new newjs_HOROSCOPE_REQUEST($dbName);
        $profilesToSend = $horoscopeReqObj->getHoroscopeForMails($dt);
        if(!empty($profilesToSend) && $profilesToSend != ''){
            foreach($profilesToSend as $requestedIds){
                    $requestedIds['total_count']--;
                    if(is_numeric($requestedIds['PROFILEID_REQUEST_BY']) && is_numeric($requestedIds['PROFILEID'])){
                            $this->sendSMS($requestedIds['PROFILEID_REQUEST_BY'], $requestedIds['PROFILEID']);
                            $this->sendMail($requestedIds['PROFILEID_REQUEST_BY'], $requestedIds['PROFILEID'],$requestedIds['total_count']);
                    }
            }
        }
        unset($horoscopeReqObj);
  }
  /**
   * This function sends sms to users
   * @param type $requestedTo horoscope requested to
   * @param type $requestedBy horoscope requested by
   */
  private function sendSMS($requestedTo,$requestedBy){
        $smsObj = new InstantSMS("HOROSCOPE_REQUEST",$requestedTo, array(),$requestedBy);
        $smsObj->send();
  }
  /**
   * This function sends mails to respective users
   * @param type $requestedTo horoscope requested to
   * @param type $requestedBy horoscope requested by
   * @param type $countOfRequest number of requests
   */
  private function sendMail($requestedTo,$requestedBy,$countOfRequest){                
                $tpl = $this->emailSenderObj->setProfileId($requestedTo);
                $p_list = new PartialList;
                $p_list->addPartial('requested_tuple','photo_profiles',array($requestedBy));
                $tpl->setPartials($p_list);

                $smartyObj = $tpl->getSmarty();
                $smartyObj->assign("otherProfile",$requestedBy);
                if($countOfRequest)
			$smartyObj->assign("TOTAL_REQUEST",$countOfRequest);
                
                $this->emailSenderObj->send();
  }
}
