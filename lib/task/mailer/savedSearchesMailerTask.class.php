<?php
/* This task is used to send saved search mailer 
 *@author : Sanyam Chopra
 *created on : 24 June 2016 
 */

class savedSearchesMailerTask extends sfBaseTask
{
	private $smarty;
    private $mailerName = "SAVED_SEARCH";
    private $limit = 1000; 
	protected function configure()
	{
		$this->namespace        = 'mailer';
		$this->name             = 'savedSearchesMailer';
		$this->briefDescription = 'new saved Search mailer';
		$this->detailedDescription = <<<EOF
		This task sends saved search mailer once in every week.
		Call it with:

		[php symfony mailer:savedSearchesMailer totalScript currentScript] 
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
        $receivers = $mailerServiceObj->getSavedSearchMailerReceivers($totalScript,$currentScript,$this->limit);
        $stypeMatch = SearchTypesEnums::SaveSearchMailer;
        $this->smarty = $mailerServiceObj->getMailerSmarty();
       
        // $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        // $instanceId = $countObj->getID('SAVED_SEARCH_MAILER');
        // $this->smarty->assign('instanceID',$instanceId);
        
        if(is_array($receivers))
        {
        	$mailerLinks = $mailerServiceObj->getLinks();
    		$this->smarty->assign('mailerLinks',$mailerLinks);
    		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);

    		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);
    		foreach($receivers as $sno => $values)
    		{
    			$pid = $values["RECEIVER"];
    			$searchId = $values["SEARCH_ID"];
                $data = $mailerServiceObj->getRecieverDetails($pid,$values,$this->mailerName,$widgetArray);                
                if(is_array($data))
				{
					$data["stypeMatch"] =$stypeMatch;
					$data["SEARCHNAME"] = $values["SEARCH_NAME"];
					$data["SEARCHID"] =  $values["SEARCH_ID"];

					$subjectAndBody = $this->getSubjectAndBody($data["SEARCHNAME"],$data["COUNT"]);
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
				$mailerServiceObj->updateSentForSavedSearchUsers($sno,$flag,$searchId);
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

  protected function getSubjectAndBody($searchName,$count)
  {
        if($count>1)
        {
        	$subject["subject"] = $count." new members matching saved search '".$searchName."' have joined last week"; 
        	$subject["body"] = "Following members matching your saved search '".$searchName."' have joined last week. <br> You may send them an interest";
        }
        else
        {
        	$subject["subject"] = $count." new member matching saved search '".$searchName."' has joined last week";
        	$subject["body"] = "Following member matching your saved search '".$searchName."' has joined last week.  <br> You may send them an interest";
        }
        return $subject;
  }
}