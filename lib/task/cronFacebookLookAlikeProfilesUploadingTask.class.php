<?php

/**
 * Description of cronFacebookLookAlikeProfilesUploadingTask
 * cron for uploading mobile number and email ids to facebook
 *
 * @author    Mohammad Shahjahan  
 */
class cronFacebookLookAlikeProfilesUploadingTask extends sfBaseTask
{

    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronFacebookLookAlikeProfilesUploading';
        $this->briefDescription    = 'Cron to upload data to the facebook api.';
        $this->detailedDescription = <<<EOF
        this cron used to upload look alike  profiles to facebook
      Call it with:[php symfony cron:cronFacebookLookAlikeProfilesUploading|INFO]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
         $coreCommunity = array(10,33,19,7,27,30,34,14,28,20,36,12,6,13);

        // getting data for profile inclusion for look alike data
        
        $misSource= new MIS_SOURCE();

        $sourceGroupArray = array('Jeevansathi',  'SEO_COM_PAGE_L1',  'SEO_COM_PAGE_L2',  'MobileApp',  'iOSApp',  'mobiledirect',  'facebook',  'google_custom',  'mobileSEM',  'yahoosearch_2008',  'bandhan_may10',  'YSM Mobile',  'UNKNOWN');
      
        $emailInclusion = array();
        $mobileInclusion = array();
        $profileObj = new JPROFILE();
       
        // echo "I am in source group.";

        foreach ($sourceGroupArray as $sourceGroup) { 

            $arraySourceId = $misSource->getSourceID($sourceGroup);

            //check whether arraySourceId is empty or not

            if ( !empty($arraySourceId))
            {
                // echo $sourceGroupArray[$i];
                $valueArray['activatedKey'] = '1';
                $valueArray['SOURCE'] = implode(",", $arraySourceId);
                
                // mobile should be verified.
                $valueArray['MOB_STATUS']="Y";

                //member should of core community
                $valueArray['MTONGUE']=implode(",", $coreCommunity);

                //the last login must be greater than 150 days before
                // print_r($valueArray);
                // die();
                $lastLoginLimit = date('Y-m-d', strtotime("-150 days"));

                $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;

                //shouldn't be deleted
                $excludeArray["ACTIVATED"] = "'D'";

                // $excludeArray['MTONGUE']="1,3,16,17,31";
                $fields="EMAIL,PHONE_MOB";
                // echo "got source id.";
                $qualityProfileQuery = "((`GENDER` = 'M' AND `AGE` >= 26) ||  (`GENDER` = 'F' AND `AGE` >= 22))";


                $result = $profileObj->getArray($valueArray, $excludeArray, $greaterThanArray, $fields ,  "", "", "", "", "", "","",$qualityProfileQuery);

                print_r($result);
                

                if ( !empty($result))
                {
                   foreach ($result as $key => $data) 
                   {
                        $emailInclusion[]=$data['EMAIL'];
                        $mobileInclusion[]=$data['PHONE_MOB'];
                    }
                }
            }
            // break;
        }
        
        // print_r($emailInclusion);
        echo "phone number is:\n";
        print_r($mobileInclusion);
        echo "\n";
    }
    
}

