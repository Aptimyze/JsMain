<?php
/* This task is used to kundli match alert mailer 
 *@author : Sanyam Chopra
 *created on : 07 Sept 2016 
 */

class kundliAlertsMailerTask extends sfBaseTask
{
	private $smarty;
    private $mailerName = "KUNDLI_ALERTS";
    private $limit = 5000;
	protected function configure()
	{
		$this->namespace        = 'mailer';
		$this->name             = 'kundliAlertsMailer';
		$this->briefDescription = 'new kundli match alert mailer';
		$this->detailedDescription = <<<EOF
		This task sends kundli match alerts mailer once in every week.
		Call it with:

		[php symfony mailer:kundliAlertsMailer totalScript currentScript] 
EOF;
		$this->addArguments(array(
			new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
			new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
			));
$this->addOptions(array(
	new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	));
	}

	public function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
        	sfContext::createInstance($this->configuration);

		$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        $mailerServiceObj = new MailerService();

        $LockingService = new LockingService;
        $file = $this->mailerName."_".$totalScript."_".$currentScript.".txt";
        $lock = $LockingService->getFileLock($file,1);
        if(!$lock)
        	successfullDie();
        $receivers = $mailerServiceObj->getKundliAlertMailerReceivers($totalScript,$currentScript,$this->limit);        
        $stypeMatch = SearchTypesEnums::kundliAlertMailer;
        $this->smarty = $mailerServiceObj->getMailerSmarty();
       foreach($receivers as $key => $value)
       {
       		foreach($value as $k1=>$v1)
       		{
       			if($k1 == "RECEIVER")
            {
              $receiverId = $v1;
            }
            elseif(strpos($k1,"GUNA_")===false && $v1!=0)
            {
              $gunaScoreArr[$receiverId][$v1] = $value["GUNA_U".substr($k1,4)];
            }
       		}
        }
        if(is_array($receivers))
        {
        	$mailerLinks = $mailerServiceObj->getLinks();
    		$this->smarty->assign('mailerLinks',$mailerLinks);
    		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);

    		//check and reconfirm what all flags have to be set in this.
    		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>true,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true,"alternateEmailSend"=>true);

        foreach($receivers as $sno => $values)
    		{
    			$pid = $values["RECEIVER"];
          $data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray,$gunaScoreArr[$pid]);                
          if(is_array($data))
          {
           $data["stypeMatch"] =$stypeMatch;

           $subjectAndBody = $this->getSubjectAndBody($data["COUNT"]);
           $data["body"]=$subjectAndBody["body"];
           $subject = $subjectAndBody["subject"];
           $this->smarty->assign('data',$data);
           $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
          /*$file = fopen("sampleMailer.html","w");
          fwrite($file,$msg);die;*/
            //Sending mail and tracking sent status
           $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid,$data["RECEIVER"]["ALTERNATEEMAILID"]);
          }
				  else
				  {
					 $flag = 'I'; // Invalid users given in database
				  }
				  $mailerServiceObj->updateSentForKundliMatchesMailer($sno,$flag,$pid);
				  unset($subject);
          unset($mailSent);
          unset($data);
    		}
    	}
    }

    		/**
  This function is to get subject of the mail depending on the count of the profiles
  *@param $count : number of users sent in mail
  *@return $subject : subject of the mail
  */

  protected function getSubjectAndBody($count)
  {
  	if($count>1)
  	{
  		$subject["subject"] = $count." Kundli matches for today which match your Desired Partner Profile"; 
  		$subject["body"] = "Shown below are ".$count." matches which have created a horoscope on Jeevansathi just like you, and their guna match with you based on horoscope matching is more than 18/36.";
  	}
  	else
  	{
  		$subject["subject"] = $count." Kundli match for today which match your Desired Partner Profile"; 
  		$subject["body"] = "Shown below is ".$count." match which has created a horoscope on Jeevansathi just like you, and their guna match with you based on horoscope matching is more than 18/36.";
  	}
  	return $subject;


  }
}