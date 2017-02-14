<?php
/**
 * This cron identifies Junk characters entered in 'About me' and auto-mark incomplete after removing Junk characters
 */

class cronJunkCharacterRemovalTask extends sfBaseTask
{
    CONST MAIL_ID = "1841";
    CONST TRUE_INCOMPLETE = 'Y'; 
    CONST TRUE_ACTIVATED = 'Y'; 
    CONST UNDERSCREENING = 'U'; 

    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronJunkCharacterRemoval';
        $this->briefDescription    = 'cron to remove junk characters from about me section.';
        $this->detailedDescription = <<<EOF
     cron to identify Junk characters entered in 'About me' and auto-mark incomplete after removing Junk characters
      Call it with:[php symfony cron:cronJunkCharacterRemoval|INFO]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $memcacheObj = JsMemcache::getInstance();
        $redisQueueInterval = JunkCharacterEnums::REDIS_QUEUE_INTERVAL;
        $key = JunkCharacterEnums::JUNK_CHARACTER_KEY;
        $newTime = strtotime(JunkCharacterEnums::TIME_DIFFERENCE_PROFILE_SCREENING);
        $minute = date('i', $newTime);
        $startIndex = floor($minute/$redisQueueInterval);
        $key = $key.(($startIndex) * $redisQueueInterval)."_".(($startIndex + 1) * $redisQueueInterval);

        $lengthOfQueue = $memcacheObj->getLengthOfQueue($key);
        
        //use pipeline  for multiple pops
        $pipeline = $memcacheObj->pipeline();

        for($i=0;$i<$lengthOfQueue;$i++)
            $pipeline->RPOP($key);

        $profileLists = $pipeline->execute();


        if ( !empty($profileLists))
        {
            foreach ($profileLists as $profileId) 
            {
		unset($paramArr);
                $jProfileObj = new Jprofile;
                $profileData = $jProfileObj->getArray(array("PROFILEID" => $profileId), "", "", "YOURINFO,FAMILYINFO,EDUCATION,JOB_INFO,SPOUSE,INCOMPLETE,ACTIVATED");

                if ( $profileData[0]['INCOMPLETE'] != self::TRUE_INCOMPLETE && $profileData[0]['ACTIVATED'] != self::TRUE_ACTIVATED && $profileData[0]['ACTIVATED'] != self::UNDERSCREENING)
                {
                    //writing in the file to keep track
                    file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/incompleteJunk2.txt","Picked: ".$profileId."\n",FILE_APPEND);
                    $flagChangeMade = 0;
                    $junkCharacterRemovalLib = new JunkCharacterRemovalLib();   

                    $about = $junkCharacterRemovalLib->removeJunkCharacters('about',$profileData[0]['YOURINFO']);

                    if ( strcmp($about,$profileData[0]['YOURINFO']) != 0 )
                    {
                        $paramArr['YOURINFO'] = $about;
                        $flagChangeMade = 1;
                    }

                    if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$about))
                    {
                        $about = '';
                        $paramArr['YOURINFO'] = $about;
                        $flagChangeMade = 1;
                    }

                    if ( !$junkCharacterRemovalLib->removeJunkCharacters('familyInfo', $profileData[0]['FAMILYINFO']) && (!empty($profileData[0]['FAMILYINFO'])))
                    {
                        $paramArr['FAMILYINFO'] = '';
                        $flagChangeMade = 1;
                    }

                    if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields', $profileData[0]['EDUCATION']) && (!empty($profileData[0]['EDUCATION'])))
                    {
                        $paramArr['EDUCATION'] = '';
                        $flagChangeMade = 1;
                    }

                    if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields', $profileData[0]['JOB_INFO']) && (!empty($profileData[0]['JOB_INFO'])))
                    {
                        $paramArr['JOB_INFO'] = '';
                        $flagChangeMade = 1;
                    }

                    if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields', $profileData[0]['SPOUSE']) && (!empty($profileData[0]['SPOUSE'])))
                    {
                        $paramArr['SPOUSE'] = '';
                        $flagChangeMade = 1;
                    }
                    if ( $flagChangeMade)
                    {
                        $jProfileObj->edit($paramArr,$profileId,'PROFILEID');
                        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/incompleteJunk2.txt","Changed: ".$profileId."\n",FILE_APPEND);
                        
                    }

                    if ( strlen($about) < 100 )
                    {
                        $actionTaken = "modified";
                        $jProfileObj->updateIncompleteProfileStatus(array($profileId));

                        if ( strlen($about) == 0 )
                        {
                            $actionTaken = "removed";  
                        }
                        $this->sendJunkCharacterMail($profileId,$actionTaken);   
                        $instantNotificationObj = new InstantAppNotification("INCOMPLETE_SCREENING");
                        $instantNotificationObj->sendNotification($profileId);
                    }
                }
            }
        }

    }
    
    /**
     * sends email if about me becomes less than 100.
     * @param  string $profileId   
     * @param  string $actionTaken modified or removed
     */
    private function sendJunkCharacterMail($profileId,$actionTaken)
    {
        $email_sender = new EmailSender(MailerGroup::JUNK_REMOVAL, self::MAIL_ID);
        $emailTpl = $email_sender->setProfileId($profileId);
        $smartyObj = $emailTpl->getSmarty();
        
        $jProfileObj= JPROFILE::getInstance();
        $uName= $jProfileObj->getUsername($profileId);
        $email= $jProfileObj->getEmailFromProfileId($profileId)['EMAIL'];

        $smartyObj->assign("profileid",$profileId);
        $smartyObj->assign("username",$uName);
        $smartyObj->assign("email",$email);        
        $smartyObj->assign("actionTaken",$actionTaken);
        $email_sender->send();
    }
}

