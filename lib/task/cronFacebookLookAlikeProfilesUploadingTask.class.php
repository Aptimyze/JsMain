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
        include_once(sfConfig::get('sf_lib_dir') . '/vendor/facebook-ads-php-sdk/facebook/php-ads-sdk/src/FacebookAds/Api.php');
        include_once(sfConfig::get('sf_lib_dir') . '/vendor/facebook-ads-php-sdk/autoload.php');


        if(!sfContext::hasInstance())
        sfContext::createInstance($this->configuration);



        $this->access_token = "EAAWcsBunOAMBALwwhJx5kr6S6IxIaqkv6SV9dq4vi3MUp93jXM02yAN2LBAGIbfRpI5YAaXKmzT0ZBIpfEUh0ZAYCy6WZCNXqWaPsBcIIXua3FpR3zDhLdKBsjzqkAMCOxj7nk3tAATpBAkdsPbcFG39Hn3EWCTTXiFXLhrzk08ZB8q46f3T";
        $this->app_id = "1579655075674115";
        $this->app_secret = "2da4dc7ebdef63857945d926f3fd1644";
        // should begin with "act_" (eg: $this->account_id = 'act_1234567890';)
        $this->account_id = "act_106359619828994";
        // Configurations - End

        if (is_null($this->access_token) || is_null($this->app_id) || is_null($this->app_secret)) {
          throw new \Exception(
            'You must set your access token, app id and app secret before executing'
          );
        }
        else
        {
            echo "fine.";
        }
        if (is_null($this->account_id)) {
          throw new \Exception(
            'You must set your account id before executing');
        }
        else
        {
            echo "fine.";
        }

        FacebookAds\Api::init($this->app_id, $this->app_secret, $this->access_token);


        //getting data for profile exclusion
        $this->loadLookAlike();
            // $this->getInclusionData();
            // $this->getExclusionData();

    }

    private function loadLookAlike()
    {


        // Create a custom audience object, setting the parent to be the account id
        $this->audience = new FacebookAds\Object\CustomAudience(null, $this->account_id);
        $this->audience->setData(array(
          FacebookAds\Object\Fields\CustomAudienceFields::NAME => 'Jeevansathi Test Data',
          FacebookAds\Object\Fields\CustomAudienceFields::DESCRIPTION => 'Adding some people',
          FacebookAds\Object\Fields\CustomAudienceFields::SUBTYPE => FacebookAds\Object\Values\ CustomAudienceSubtypes::CUSTOM,

          // FacebookAds\Object\Fields\CustomAudienceFields::ORIGIN_AUDIENCE_ID => "23842510783050637",
          // FacebookAds\Object\Fields\CustomAudienceFields::LOOKALIKE_SPEC => array(
          //   'type' => 'similarity',
          //   'country' => 'IN',
          // )


        ));



        // // Create the audience
        $this->audience->create();

        echo "Audience ID: " . $this->audience->id."\n";

        $emails = array(
            'scottai911@hotmail.com',
            'sharynau613@hotmail.com',
            'roxannaqw807@hotmail.com',
            'shellygs489@hotmail.com',
            'shastada040@hotmail.com',
            'shawnkn615@hotmail.com',
            'rosetteqr685@hotmail.com',
            'pearlenedq409@hotmail.com',
            'shelbydv770@hotmail.com',
            'shanellebj021@hotmail.com',
            'shantahn850@hotmail.com',
            'samathauv659@hotmail.com',
            'seanhj402@hotmail.com',

        );

        echo "Adding users.";

        $this->audience->addUsers($emails,(FacebookAds\Object\Values\CustomAudienceTypes::EMAIL));
        echo "Reading users.";
        $this->audience->read(array(FacebookAds\Object\Fields\CustomAudienceFields::APPROXIMATE_COUNT));
        echo "Estimated Size:"
          . $this->audience->{FacebookAds\Object\Fields\CustomAudienceFields::APPROXIMATE_COUNT}."\n";
    }
    private function getExclusionData()
    {
        $emailExclusion = array();
        $mobileExclusion = array();
        $profileObj = new JPROFILE();

        $valueArray['activatedKey'] = '1';
        $valueArray['MOB_STATUS']="Y";

        $lastLoginLimit = date('Y-m-d', strtotime("-365 days"));

        $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;

        $fields="EMAIL,PHONE_MOB";


        $result = $profileObj->getArray($valueArray, "", $greaterThanArray, $fields ,  "", "", "", "", "", "","","");

        print_r($result);
        

        if ( !empty($result))
        {
           foreach ($result as $key => $data) 
           {
                $emailExclusion[]=$data['EMAIL'];
                $mobileExclusion[]=$data['PHONE_MOB'];
            }
        }
        
        print_r($emailExclusion);
        echo "phone number is:\n";
        print_r($mobileExclusion);
        echo "\n";
       
    }   

    private function getInclusionData()
    {
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

                // print_r($result);
                

                if ( !empty($result))
                {
                   foreach ($result as $key => $data) 
                   {
                        $emailInclusion[]=$data['EMAIL'];
                        $mobileInclusion[]=$data['PHONE_MOB'];
                    }
                }
            }
        }
        
        print_r($emailInclusion);
        print_r($mobileInclusion);

}

}

