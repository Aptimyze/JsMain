<?php
/**
 * This cron identifies Junk characters entered in 'About me' and auto-mark incomplete after removing Junk characters
 */

class cronPromotionalAlternateEmailTask extends sfBaseTask
{
    CONST MAIL_ID = "1844";

    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronPromotionalAlternateEmail';
        $this->briefDescription    = 'cron to send promotional mails.';
        $this->detailedDescription = <<<EOF
    This cron is for sending promotional alternate emails. 
      Call it with:[php symfony cron:cronPromotionalAlternateEmail  totalScript currentScript isLegacyProfiles]
EOF;
        $this->addArguments(array(
            new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
            new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
            new sfCommandArgument('isLegacyProfiles', sfCommandArgument::REQUIRED, 'My argument'),
            ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){   
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $this->totalScript = $arguments["totalScript"]; // total no of scripts
        $this->currentScript = $arguments["currentScript"]; // current script number

        $this->isLegacyProfiles = $arguments["isLegacyProfiles"]; // whether mail is to be sent all legacy profiles?

        $this->activateDate = date('Y-m-d',strtotime(PromotionalAlternateEmailEnums::VERIFY_ACTIVATED_LIMIT));
        $this->entryDate = date('Y-m-d',strtotime(PromotionalAlternateEmailEnums::ENTRY_DATE_LIMIT));
        $this->lastLoginDate  = date('Y-m-d',strtotime(PromotionalAlternateEmailEnums::LAST_LOGIN_LIMIT));
        $date = date('Y-m-d');

        try 
        {
            $iterationArr = $this->getIterationArr();  
            foreach($iterationArr as $x=>$case)
            {
                $file = sfConfig::get("sf_upload_dir")."/SearchLogs/altPromo_".$this->totalScript."_".$this->currentScript."_".$case."_".$this->isLegacyProfiles;
                if(!$this->isLegacyProfiles)
                    $file.="_".$date;
                $file.=".txt";
                $i = 0;
                do
                {
                    $profileIDs= $this->getData($case,$file,$i);

                    if(is_array($profileIDs))
                    {
                        $this->sendPromotionalAlternateEmailProfileIds($profileIDs,$file,$i);
                        $i++;
                    }
                    else
                    {
                        break;
                    }
                } while (1);
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getIterationArr()
    {
        $iterationArr = array(1,2);
        if ( $this->isLegacyProfiles )
        {
            $iterationArr=array(3,4);
        }
        return $iterationArr;
    }
    public function getData($case,$file,$i)
    {

		$limit = PromotionalAlternateEmailEnums::LIMIT_FETCH_PROFILE;
		$offset = $this->getFileOffset($file,$i);
        $jprofileContact = new NEWJS_JPROFILE_CONTACT('newjs_slave');
		switch($case)
		{
			case 1:
                $profileIDs = $jprofileContact->getPromotionalMailerAccountNoContact($this->activateDate,$this->entryDate,$this->totalScript,$this->currentScript,$limit,$offset);
				break;
			case 2:
                $profileIDs = $jprofileContact->getPromotionalMailerAccounts($this->activateDate,$this->entryDate,$this->totalScript,$this->currentScript,$limit,$offset);
				break;
			case 3:
                $profileIDs = $jprofileContact->getPromotionalMailerAccountNoContactOnce($this->activateDate,$this->lastLoginDate,$this->totalScript,$this->currentScript,$limit,$offset);
				break;
			case 4:
                $profileIDs = $jprofileContact->getPromotionalMailerAccountsOnce($this->activateDate,$this->lastLoginDate,$this->totalScript,$this->currentScript,$limit,$offset);
				break;
		}
		if(is_array($profileIDs))
			return $profileIDs;
		return false;
        
    }

    public function getFileOffset($file,$i)
    {
        $offset=0;
        if($i!=0)
            $offset = $i * PromotionalAlternateEmailEnums::LIMIT_FETCH_PROFILE;
    	elseif($offsetFile = file_get_contents($file))
    		$offset = $offsetFile;
    	return (int)$offset;
    }

    public function sendPromotionalAlternateEmailProfileIds($profileIDs,$file,$i)
    {
        if ( is_array($profileIDs))
        {
            foreach ($profileIDs as $key => $value) {
                $this->sendPromotionalAlternateEmail($value['PROFILEID']);
                file_put_contents($file,($i * PromotionalAlternateEmailEnums::LIMIT_FETCH_PROFILE) + $key + 1 );
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    
    /**
     * This function is used to send email for a given profile id for Promotional Alternate Email Id.
     * @param  string $profileId 
     */
    public function sendPromotionalAlternateEmail($profileId)
    {
        $email_sender = new EmailSender(MailerGroup::PROMOTIONAL_ALTERNATE_EMAIL, self::MAIL_ID);
        $emailTpl = $email_sender->setProfileId($profileId);
        $smartyObj = $emailTpl->getSmarty();
        $email_sender->send();
    }
}

