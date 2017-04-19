<?php
/* This task is used to send mailer showing members turned paid in the last week
 *@author : Sanyam Chopra
 *created on : 17 April 2017 
 */

class paidMembersMailerTask extends sfBaseTask
{
	private $smarty;
    private $mailerName = "PAID_MEMBERS_MAILER"; 
    private $limit = 1000; 
	protected function configure()
	{
		$this->namespace        = 'mailer';
		$this->name             = 'paidMembersMailer';
		$this->briefDescription = 'new paid members mailer';
		$this->detailedDescription = <<<EOF
		This task sends mailer with profiles who turned paid in the last week once in every week.
		Call it with:

		[php symfony mailer:paidMembersMailer totalScript currentScript] 
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
        $receivers = $mailerServiceObj->getpaidMembersMailerReceivers($totalScript,$currentScript,$this->limit);
        $stypeMatch = SearchTypesEnums::PAID_MEMBERS_MAILER;
        $this->smarty = $mailerServiceObj->getMailerSmarty();
       
        if(is_array($receivers))
        {
        	$mailerLinks = $mailerServiceObj->getLinks();
    		$this->smarty->assign('mailerLinks',$mailerLinks);
    		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);

    		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);
    		foreach($receivers as $sno => $values)
    		{
    			$pid = $values["RECEIVER"];    			
                $data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray);                
                if(is_array($data))
				{
					$data["stypeMatch"] =$stypeMatch;
					$subjectAndBody = $this->getSubjectAndBody($data["COUNT"]);
                    $data["body"]=$subjectAndBody["body"];
                    $subject = $subjectAndBody["subject"];
					$this->smarty->assign('data',$data);
                    $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
                    // $file = fopen("sampleMailer.html","w");
                    // fwrite($file,$msg);
                    //Sending mail and tracking sent status
                    $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid);
				}
				else
					$flag = 'I'; // Invalid users given in database
				$mailerServiceObj->updateSentForPaidMembersMailerReceivers($sno,$flag,$pid);
				unset($subject);
                        unset($mailSent);
                        unset($data);
    		}
        }
	}

	/**
  This function is to get subject of the mail depending on the search name and count of the profiles
  *@param $searchName : name of the saved search
  *@param $count : number of users sent in mail
  *@return $subject : subject of the mail
  */

  protected function getSubjectAndBody($count)
  {
        if($count>1)
        {
        	$subject["subject"] = "Your ".$count." matches have become a paid member last week ";
            $subject["body"] = "Below is the profile which has converted to paid membership. Check this profile and send interest to connect with them.";        	
        }
        else
        {
        	$subject["subject"] = $count." new member matching saved search '".$searchName."' has joined last week";

            $subject["body"] = "Below are the profiles which have converted to paid membership. Check their profile and send interest to connect with them.";        	
        }
        return $subject;
  }
}