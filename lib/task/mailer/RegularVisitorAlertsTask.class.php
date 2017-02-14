<?php
/** This Task file is used to send regular Visitor mailer
* 
* @package    jeevansathi
* @subpackage visitorAlert
* @author     akash Kumar <akash.k@jeevansathi.com>
*/
/** This class is used to send Visitor mailer to users based on already populated tabled in database.
 */
class RegularVisitorAlertsTask extends sfBaseTask
{	
	/** To assign variable in Smarty Templates 
	*/
	private $smarty;
    	private $mailerName = "VISITORALERT";
    	private $limit = 1000;
 /** Configuring the task
 */
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'visitorAlert';
    $this->briefDescription = 'regular Visitor Alert mailer';
    $this->detailedDescription = <<<EOF
      The task send matchalert mailer .
      Call it with:

      [php symfony mailer:visitorAlert totalScript currentScript
] 
EOF;
    $this->addArguments(array(
		new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                ));
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }
  /** Execution of task
  *
  * @param int $arguments Array of Total number of scripts running and the Current script number
  * @param string $options Optional argument for name of application
  * @return Nothing
  */
  protected function execute($arguments = array(), $options = array())
  {
      if(CommonUtility::hideFeaturesForUptime())
        successfullDie();
	$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
	$mailerServiceObj = new MailerService();
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$LockingService = new LockingService;
        $file = $this->mailerName."_".$totalScript."_".$currentScript.".txt";
        $lock = $LockingService->getFileLock($file,1);
        if(!$lock)
                successfullDie();
	// Visitor alert configurations
	$receivers = $mailerServiceObj->getMailerReceiversVisitorAlert($totalScript,$currentScript,$this->limit);
	$stypeMatch = SearchTypesEnums::VisitorAlertMailer;
	$this->smarty = $mailerServiceObj->getMailerSmarty();
        $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceId = $countObj->getID('VA_MAILER');
        $this->smarty->assign('instanceID',$instanceId);
	if(is_array($receivers))
	{
		$mailerLinks = $mailerServiceObj->getLinks();
    $this->smarty->assign('mailerLinks',$mailerLinks); 
    $this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>false,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>false,"googleAppTrackingFlag"=>true,"alternateEmailSend"=>true);
		foreach($receivers as $sno=>$values)
		{
                    if(CommonUtility::hideFeaturesForUptime())
                        successfullDie();
			$pid = $values["PROFILEID"];
                        $data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray);
                                
			if(is_array($data))
			{ 
				$data["stypeMatch"] =$stypeMatch;
				$this->smarty->assign('data',$data);
				$msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
                                $subject = $this->getSubject($data["USERS"][0],$data["COUNT"]);
                                $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,"",$data["RECEIVER"]["ALTERNATEEMAILID"]);
				$otherUserId = $data["USERS"][0]->getPROFILEID();
				$this->recentProfileVisitorNotification($pid,$subject,$otherUserId);
                		$this->recentProfileVisitorsBrowserNotification($pid, $subject,$otherUserId);
			}
			else
				$flag = "I"; // Invalid users given in database
			$mailerServiceObj->updateSentForVisitorAlertUsers($sno,$flag);
			unset($subject);
                        unset($mailSent);
                        unset($data);

		}
	}
  }
  protected function recentProfileVisitorNotification($profileid, $subject,$otherUserId)
  {
	$notificationKey ='PROFILE_VISITOR';
	$instantNotificationObj =new InstantAppNotification($notificationKey);
	$instantNotificationObj->sendNotification($profileid,$otherUserId,$subject);	

  }
  
  protected function recentProfileVisitorsBrowserNotification($pid, $subject,$otherUserId)
  {
        $producerObj=new Producer();
        if($producerObj->getRabbitMQServerConnected()){
            $notificationData = array();
            $notificationData["notificationKey"] = "PROFILE_VISITOR";
            $notificationData["selfUserId"] = $pid;
            $notificationData["message"] = $subject;
            $notificationData["otherUserId"] = $otherUserId;
            $producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
        }
        else{
            //send mail alert in case of connection failure to rabbitmq producer
            $message="Connection to RabbitMQ producer failed in cron regularVisitorAlertTask";
            RabbitmqHelper::sendAlert($message,"browserNotification");
        }
  }
  
  protected function getSubject($firstUser,$count)
  {
	if($firstUser->getGENDER()=="M")
        	$heSheCall="He";
        else
        	$heSheCall="She";
        
       //For Subject rotation
       if(rand(1, 4)%2==0)
        	$viewSawCall="viewed";
        else
        	$viewSawCall="saw";
        // Setting subject for the mail
        if($count >1)
        	$subject = "Profile Visitors: ".$count." People ".$viewSawCall." your profile, they may be interested in you.";
        else
        	$subject = "Profile Visitors: ".$firstUser->getUSERNAME()." ".$viewSawCall." your profile,".$heSheCall." may be interested in you.";
	return $subject;
  }

}
