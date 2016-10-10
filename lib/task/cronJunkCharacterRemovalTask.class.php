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

        $profileId = $memcacheObj->get("shahjahan");
        // echo "the shahjahan key has value: " .;
        // 
        $jprofileObj = new Jprofile;
        $profileData = $jprofileObj->getArray(array("PROFILEID" => $profileId), "", "", "YOURINFO,FAMILYINFO,EDUCATION,JOB_INFO,SPOUSE");

        $about = $this->removeJunkAbout($profileData[0]['YOURINFO']);

        $familyInfo = $profileData[0]['FAMILYINFO'];
        $education = $profileData[0]['EDUCATION'];
        $jobInfo = $profileData[0]['JOB_INFO'];
        $spouse = $profileData[0]['SPOUSE'];

        if ( !$this->removeJunkOpenFields($about))
        {
            $about = '';
        }

        if ( !$this->removeJunkOpenFields($familyInfo))
        {
            $familyInfo = '';   
        }

        if ( !$this->removeJunkOpenFields($education))
        {
            $education = '';   
        }

        if ( !$this->removeJunkOpenFields($jobInfo))
        {
            $jobInfo = '';   
        }

        if ( !$this->removeJunkOpenFields($spouse))
        {
            $spouse = '';
        }

        if ( strlen($about) < 100 )
        {
            echo "Mark this profile incomplete.";
        }

        echo "finally. \n about: ".$about
                          ."\n familyInfo: ".$familyInfo
                          ."\n education: ".$education
                          ."\n jobInfo: ".$jobInfo
                          ."\n spouse: ".$spouse

        ;


    }

    // public function replaceMultipleOccurances($originalMessage,$valueToBeReplaced,$minimumCountToBeReplaced,$valueByWhichReplaced,$minimumCountByWhichReplaced)
    // {       
    //     return preg_replace("/[".$valueToBeReplaced."]{".$minimumCountToBeReplaced.",}/,".$valueByWhichReplaced.",".$originalMessage);
    // }
    public function removeJunkAbout($about)
    {
        echo "About is: ".$about;
        echo "\n";
        // // remove spaces
        // $about = preg_replace("/\s+/"," ",$about);
        // // multiple dots to 3 dots.
        $about =  preg_replace('/[.]{4,}/','...',$about);
        //replace any special occurences with one
        // $re = '';

        $about = preg_replace('/([^\w.])\1+/','$1',$about);

        echo "Replaced about me is: ".$about;
        return $about;
    }

    public function removeJunkOpenFields($text)
    {
        echo "Junk fields are: ".$text;
        echo "\n";
        // if no spaces
        $five_unique = count( array_unique( str_split( preg_replace('/[^a-z]/i','',$text)))) > 5 ? 1 : 0;
        $space_vowels = preg_match('/(?=.*\s+)(?=.*[aeiou]+)/i',$text); 

        // echo "five_unique ".$five_unique;
        // echo "space_vowels ".$space_vowels;

        return $five_unique && $space_vowels;
    }
}

