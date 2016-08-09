<?php
/* This task is used to send featured profile mailer 
 *@author : Sanyam Chopra
 *created on : 08 August 2016 
 */

ini_set("max_execution_time",-1);
class featuredProfileMailerTask extends sfBaseTask
{
	private $smarty;
    private $mailerName = "FEATURED_PROFILE";
    private $limit = 1000;
   	private $tupleName = "FEATURED_PROFILE_MAILER_TUPLE";
    protected function configure()
	{
		$this->namespace        = 'mailer';
		$this->name             = 'featuredProfileMailer';
		$this->briefDescription = 'featured profile mailer';
		$this->detailedDescription = <<<EOF
		This task sends featured profile mailer once in every week.
		Call it with:

		[php symfony mailer:featuredProfileMailer totalScript currentScript] 
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

        $receivers = $mailerServiceObj->getFeaturedProfileMailerReceivers($totalScript,$currentScript,$this->limit);
        
        //$stypeMatch = SearchTypesEnums::SaveSearchMailer;
        $this->smarty = $mailerServiceObj->getMailerSmarty();

        if(is_array($receivers))
        {
        	$mailerLinks = $mailerServiceObj->getLinks();
        	$mailerServiceObj->loadPartials();
    		$this->smarty->assign('mailerLinks',$mailerLinks);
    		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);

    		//$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);
    		foreach($receivers as $sno => $values)
    		{
    			$profileId = $values;
    			
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_slave',$profileId);

                $tupleService = new TupleService();
				$tupleService->setLoginProfileObj($loggedInProfileObj);
				$tupleService->setLoginProfile($profileId);
				$tupleFields = $tupleService->getFields($this->tupleName);
				$tupleArray = array($profileId=>array("PROFILEID"=>$profileId));
                $tupleService->setProfileInfo(array("FEATURED_PROFILE_TUPLE"=>$tupleArray),$tupleFields);
                
                $data = $tupleService->getFEATURED_PROFILE_TUPLE();
                $dataArr = $data[$profileId];
               	
                if($dataArr && is_object($dataArr))
				{	
					$subjectAndBody = $this->getSubjectAndBody();					
                    $subject = $subjectAndBody["subject"];
                    
                    $receiverechecksum = JsAuthentication::jsEncrypt($profileId,"");
					$commonParamaters ="/".$receiverechecksum."/".$dataArr->getPROFILECHECKSUM();
					$this->smarty->assign('commonParamaters',$commonParamaters);
					$this->smarty->assign('dataArr',$dataArr);
					$this->smarty->assign('profileId',$profileId);
                    $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
                    // $file = fopen("sampleMailer.html","w");
                    // fwrite($file,$msg);echo("ndaklnsd");die;

                    //Sending mail and tracking sent status
                    $flag = $mailerServiceObj->sendAndVerifyMail($dataArr->getEMAIL(),$msg,$subject,$this->mailerName,$profileId);
				}
				else
				{
					$flag = 'I'; // Invalid users given in database
				}
				$mailerServiceObj->updateSentForFeaturedProfileUsers($profileId,$flag);
				unset($subject);
				unset($dataArr);
				unset($data);
				unset($TupleService);
    		}
        }
    }

    protected function getSubjectAndBody()
  {
        $subject["subject"] = "Want to feature at the top of search results on Jeevansathi? Find out how";
        return $subject;
  }
}