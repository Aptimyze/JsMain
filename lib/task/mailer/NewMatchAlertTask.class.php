<?php

/* This task is used to send new matchalert mailer 
 *@author : Reshu Rajput
 *created on : 20 June 2014 
 */
ini_set("max_execution_time",-1);
class NewMatchAlertTask extends sfBaseTask
{
    private $smarty;
    private $mailerName = "NEW_MATCHES";
    private $limit = 1000;
    // Different subject lines for different week as per requirement 
    private $subjectArray = Array("who just joined match your criteria","who just joined you may contact","who just joined you may like");
  

  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'NewMatchAlert';
    $this->briefDescription = 'new matchalert mailer';
    $this->detailedDescription = <<<EOF
      The task send new matchalert mailer .
      Call it with:

      [php symfony mailer:NewMatchAlert totalScript currentScript] 
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
	$receivers = $mailerServiceObj->getNewMatchesMailerReceivers($totalScript,$currentScript,$this->limit);
	$stypeMatch = SearchTypesEnums::NewMatchesEmail;
	$this->smarty = $mailerServiceObj->getMailerSmarty();
        $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceId = $countObj->getID('NMA_MAILER');
        $this->smarty->assign('instanceID',$instanceId);
	if(is_array($receivers))
	{
		$mailerLinks = $mailerServiceObj->getLinks();
    $this->smarty->assign('mailerLinks',$mailerLinks);
    $this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>true,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>false,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true,"primaryMailGifFlag"=>true,"alternateEmailSend"=>true);
		foreach($receivers as $sno=>$values)
		{
			$pid = $values["RECEIVER"];
                        $data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray);
                        if(is_array($data))
			{
				$data["relaxCriteria"] = $values["RELAX_CRITERIA"];
                                $data["stypeMatch"] =$stypeMatch;
				$data["viewAllLinkFlag"] = $values["LINK_REQUIRED"];
				$this->smarty->assign('data',$data);
                                $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");

				// Sending mail and tracking sent status
				$subject = $this->getSubject($data["RECEIVER"]["PROFILE"]->getNAME(),$data["COUNT"]);
                                $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,"",$data["RECEIVER"]["ALTERNATEEMAILID"]);
					 
			}
			else
				$flag = "I"; // Invalid users given in database
			$mailerServiceObj->updateSentForNewMatchesUsers($sno,$flag);
			unset($subject);
                        unset($mailSent);
                        unset($data);

		}
	}
  }

  /**
  This function is to get subject of the mail out of the 3 possible subject lines required as per business
  *@param $name : name of the receiver of the mail
  *@param $count : number of users sent in mail
  *@return $subject : subject of the mail
  */

  protected function getSubject($name,$count)
  {
	$currentWeek = date("W");
	$subjectIndex = $currentWeek % 3;
	$subName="";
         // Setting subject for the mail
        if(!empty($name))
                $subName =ucwords($name).", ";
        if($count >1)
                $subject = "New Matches: ".$subName.$count." People ".$this->subjectArray[$subjectIndex];
        else
                $subject = "New Match: ".$subName."Member ".$this->subjectArray[$subjectIndex];

        return $subject;
  }


}
