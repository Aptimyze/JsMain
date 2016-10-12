<?php

class cronJunkCharacterRemovalTask extends sfBaseTask
{
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
        $number0fRedisQueues = ceil(60 / $redisQueueInterval);
        $key = "ScreeningIDs_";
        $newTime = strtotime('-30 minutes');
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
                $jProfileObj = new Jprofile;
                $profileData = $jProfileObj->getArray(array("PROFILEID" => $profileId), "", "", "YOURINFO,FAMILYINFO,EDUCATION,JOB_INFO,SPOUSE");


                $junkCharacterRemovalLib = new JunkCharacterRemovalLib();   


                $about = $junkCharacterRemovalLib->removeJunkCharacters('about',$profileData[0]['YOURINFO']);

                $familyInfo = $profileData[0]['FAMILYINFO'];
                $education = $profileData[0]['EDUCATION'];
                $jobInfo = $profileData[0]['JOB_INFO'];
                $spouse = $profileData[0]['SPOUSE'];

                if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$about))
                {
                    $about = '';
                }

                if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$familyInfo))
                {
                    $familyInfo = '';   
                }

                if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$education))
                {
                    $education = '';   
                }

                if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$jobInfo))
                {
                    $jobInfo = '';   
                }

                if ( !$junkCharacterRemovalLib->removeJunkCharacters('openFields',$spouse))
                {
                    $spouse = '';
                }

                $paramArr['YOURINFO'] = $about;
                $paramArr['FAMILYINFO'] = $familyInfo;
                $paramArr['EDUCATION'] = $education;
                $paramArr['SPOUSE'] = $spouse;
                $paramArr['JOB_INFO'] = $jobInfo;

                $jProfileObj->edit($paramArr,$profileId,'PROFILEID');

                if ( strlen($about) < 100 )
                {
                    $jProfileObj->updateIncompleteProfileStatus(array($profileId));       
                }

                echo "finally. \n about: ".$about
                                  ."\n familyInfo: ".$familyInfo
                                  ."\n education: ".$education
                                  ."\n jobInfo: ".$jobInfo
                                  ."\n spouse: ".$spouse

                ;
            }
        }
    }

   
   
}

