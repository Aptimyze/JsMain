<?php
/* This task is used to send add photo mailer
 *@author : Sanyam Chopra
 *created on : 28 April 2017 
 */

class addPhotoMailerTask extends sfBaseTask
{
	private $smarty;
    private $mailerName = "ADD_PHOTO_MAILER";
    private $limit = 1000; 
	protected function configure()
	{
		$this->namespace        = 'photoRelated';
		$this->name             = 'addPhotoMailer';
		$this->briefDescription = 'new add Photo Mailer';
		$this->detailedDescription = <<<EOF
		This task sends add photo mailer once in every week.
		Call it with:

		[php symfony photoRelated:addPhotoMailer totalScript currentScript] 
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
		if(!CommonUtility::runFeatureInDaytime())
            successfullDie();
        if(!sfContext::hasInstance())
        	sfContext::createInstance($this->configuration);

		$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        $mailerServiceObj = new MailerService();

        $receivers = $mailerServiceObj->getaddPhotoMailerReceivers($totalScript,$currentScript,$this->limit);        
        $stypeMatch = SearchTypesEnums::ADD_PHOTO_MAILER;
        $this->smarty = $mailerServiceObj->getMailerSmarty();
       
        if(is_array($receivers))
        {
        	$mailerLinks = $mailerServiceObj->getLinks();
    		$this->smarty->assign('mailerLinks',$mailerLinks);
    		$this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);            
    		
    		foreach($receivers as $sno => $values)
    		{
    			$pid = $values["PROFILEID"];
    			$type = $values["TYPE"];             
                $profileObj = LoggedInProfile::getInstance('',$pid);
                $profileObj->getDetail('','','*');

                $data["RECEIVER"]["PROFILE"] = $profileObj;
                $data["RECEIVER"]["EMAILID"] = $profileObj->getEMAIL();
                $havePhoto = $profileObj->getHAVEPHOTO();
                
                if(is_array($data) && isset($data["RECEIVER"]["PROFILE"]) && !in_array($havePhoto, noPhotoMailerEnum::$havePhotoConditionArr))
				{
					$data["stypeMatch"] =$stypeMatch;
                    $data["type"] = $type;					
                    $receiverProfilechecksum = JsAuthentication::jsEncryptProfilechecksum($pid);
                    $receiverechecksum = JsAuthentication::jsEncrypt($pid,"");
            
                    //Common Parameters required in mailer links
                    $data["commonParamaters"] ="/".$receiverechecksum."/".$receiverProfilechecksum;
					$subjectAndBody = $this->getSubjectAndBody($type);
                    $data["body"]=$subjectAndBody["body"];
                    $subject = $subjectAndBody["subject"];
					$this->smarty->assign('data',$data);
                    $mailerServiceObj->loadPartials();                    
                    $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");

                    /*$file = fopen("addPhotoSample.html","w");
                    fwrite($file,$msg);die("DONE");*/
                    //Sending mail and tracking sent status
                    $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid);
				}
				else
					$flag = 'I'; // Invalid users given in database
				$mailerServiceObj->updateSentForaddPhotoUsers($sno,$flag,$pid);
                unset($subject);
                unset($mailSent);
                unset($data);
                unset($havePhoto);
    		}
        }
	}

	/**
  This function is to get subject of the mail depending on the search name and count of the profiles
  *@param $searchName : name of the saved search
  *@param $count : number of users sent in mail
  *@return $subject : subject of the mail
  */

  protected function getSubjectAndBody($type)
  {
        if($type==1)
        {
        	$subject["subject"] = "Upload photo to receive 10x responses"; 
        	$subject["body"] = "People who upload their photos on Jeevansathi receive 10 times more responses.<br><br>
Moreover over 75% of our members feel that they need at least 3 photos on other members' profile to send them an interest.<br><br>
Your photos are safe with us and will carry our watermark. There is absolutely no reason to wait!<br><br>";
        }
        else
        {
        	$subject["subject"] = "Make your photo visible ONLY to accepted contacts.Click to learn how";
        	$subject["body"] = 'Uploading photos on Jeevansathi is safe and easy.<br><br>
You can make your photo visible ONLY to people you send interests to and people you accept.Your photos will not be visible to the rest of the members on the site.
You will have to select "Visible on Accept" setting while uploading your photographs.<br><br>
You will get an increased response from the members you send interest to as they will be able to see your photo.<br><br>
Your photos will carry our watermark.<br>
There is absolutely no reason to wait !<br><br>';
        }
        return $subject;
  }
}