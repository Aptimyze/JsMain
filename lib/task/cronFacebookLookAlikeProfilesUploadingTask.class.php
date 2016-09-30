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

        /**
         * the array consists of all group whose users are considered to be quality users.
         * @var array
         */
        $this->sourceGroupArray = array('Jeevansathi',  'SEO_COM_PAGE_L1',  'SEO_COM_PAGE_L2',  'MobileApp',  'iOSApp',  'mobiledirect','facebook',  'google_custom',  'mobileSEM',  'yahoosearch_2008',  'bandhan_may10',  'YSM Mobile',  'UNKNOWN');
        /**
         * key for tonge to decide for community
         * @var array
         */
        $this->coreCommunity = array(10,33,19,7,27,30,34,14,28,20,36,12,6,13);

        /**
         * facebook access token, must have ads_management previleges
         * @var string
         */     
        $this->access_token = "EAAWcsBunOAMBALwwhJx5kr6S6IxIaqkv6SV9dq4vi3MUp93jXM02yAN2LBAGIbfRpI5YAaXKmzT0ZBIpfEUh0ZAYCy6WZCNXqWaPsBcIIXua3FpR3zDhLdKBsjzqkAMCOxj7nk3tAATpBAkdsPbcFG39Hn3EWCTTXiFXLhrzk08ZB8q46f3T";

        /**
         * app id 
         * @var string
         */
        $this->app_id = "1579655075674115";
        
        /**
         * app secret
         * @var string
         */
        $this->app_secret = "2da4dc7ebdef63857945d926f3fd1644";
        
        /**
         * App ad account id.
         * @var string
         */
        $this->account_id = "act_106359619828994";
        
        /**
         * the custom audience id created at facebook
         * @var string
         */
        $this->includeCustomAudienceId = "23842511013090637";

        /**
         * the look alike id, created from given custom audience.
         * @var string
         */
        $this->excludeCustomAudienceId = "23842511021870637";


        /**
         * contains email list to be excluded in look alike array.
         * @var array
         */
        $this->emailExclusion = array();
        
        /**
         * contains mobile list to be excluded in look alike array
         * @var array
         */
        $this->mobileExclusion = array();

        /**
         * contains email list to be included for creation of custom audience
         * @var array
         */
        $this->emailInclusion = array();

        /**
         * create email list to be included for creation of custom audience.
         * @var array
         */
        $this->mobileInclusion = array();



        if (is_null($this->access_token) || is_null($this->app_id) || is_null($this->app_secret) ) {
          throw new \Exception(
            'You must set your access token, app id and app secret before executing'
          );
        }

        if (is_null($this->account_id) || is_null($this->excludeCustomAudienceId) || is_null($this->includeCustomAudienceId)) {
          throw new \Exception(
            'You must set your account id, custom audience idss before executing');
        }

        try 
        {
            FacebookAds\Api::init($this->app_id, $this->app_secret, $this->access_token);

            $this->audience = new FacebookAds\Object\CustomAudience(null, $this->account_id);


            $this->getInclusionData();


            $this->addUsersCustomAudience($this->includeCustomAudienceId,$this->emailInclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
            

            $this->addUsersCustomAudience($this->includeCustomAudienceId,$this->mobileInclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
            
            $this->getExclusionData();



            $this->addUsersCustomAudience($this->excludeCustomAudienceId,$this->emailExclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
            

            $this->addUsersCustomAudience($this->excludeCustomAudienceId,$this->mobileExclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
        } 
        catch (Exception $e) 
        {
            throw new jsException($e);
        }
       
    }

    /**
     * function adds users to the custom audience
     * @param string $customAudienceId 
     * @param array $data             email or mobile array
     * @param enum $dataType         FacebookAds\Object\Values\CustomAudienceTypes::EMAIL or FacebookAds\Object\Values\CustomAudienceTypes::PHONE
     */
    private function addUsersCustomAudience($customAudienceId,$data,$dataType)
    {
        try 
        {        
            $this->audience->id = $customAudienceId;
            $this->audience->addUsers($data,$dataType,array($customAudienceId));
        } 
        catch (Exception $e) {
            throw new jsException($e);
        }
    }

    /**
     * function remove users from custom audience
     * @param string $customAudienceId 
     * @param array $data             email or mobile array
     * @param enum $dataType         FacebookAds\Object\Values\CustomAudienceTypes::EMAIL or FacebookAds\Object\Values\CustomAudienceTypes::PHONE
     */
    private function removeUsersCustomAudience($customAudienceId,$data,$dataType)
    {
        try 
        {
            $this->audience->id = $customAudienceId;
            $this->audience->removeUsers($data,$dataType);
        } 
        catch (Exception $e) 
        {
            throw new jsException($e);
        }
    }

    /**
     * create a custom audience.
     * @param  string $name        Name of the custom audience to be created.
     * @param  string $description The description of the custom audience created.
     */
    private function createCustomAudience($name,$description)
    {
            
        try {    
        $this->audience->setData(array(
              FacebookAds\Object\Fields\CustomAudienceFields::NAME => $name,
              FacebookAds\Object\Fields\CustomAudienceFields::DESCRIPTION => $description,
              FacebookAds\Object\Fields\CustomAudienceFields::SUBTYPE => FacebookAds\Object\Values\CustomAudienceSubtypes::CUSTOM,
            ));
        $this->audience->create();
        } 
        catch (Exception $e) 
        {
            throw new jsException($e);
        }
    }
    /**
     * creates look alike audience
     * @param  string $originAudienceId the custom audience Id from which the look alike audience is to created.
     * @param  string $name             the name of the look alike to be created
     * @param  string $description      the description of the look alike audience created
     * @param  string $country          IN for india, US for us
     */
    private function createLookAlike($originAudienceId,$name,$description,$country = 'IN')
    {
       try 
       {
            $this->audience->setData(array(
            FacebookAds\Object\Fields\CustomAudienceFields::NAME => $name,
            FacebookAds\Object\Fields\CustomAudienceFields::DESCRIPTION => $description,
            FacebookAds\Object\Fields\CustomAudienceFields::SUBTYPE => FacebookAds\Object\Values\CustomAudienceSubtypes::LOOKALIKE,

            FacebookAds\Object\Fields\CustomAudienceFields::ORIGIN_AUDIENCE_ID => $originAudienceId,
            FacebookAds\Object\Fields\CustomAudienceFields::LOOKALIKE_SPEC => array(
                'type' => 'similarity',
                'country' => $country,
              )
            ));
            $this->audience->create();
       } 
       catch (Exception $e)
        {
           throw new jsException($e);
       }
    }

    /**
     * get exlusion data from look alike audience
     */
    private function getExclusionData()
    {
        try 
        {
            $profileObj = new JPROFILE();

            $start_joined_date  = date('2000-1-1 00:00:00');
        
            $last_joined_date = date('Y-m-d h:m:s',strtotime('+365 days', strtotime($start_joined_date)));
            
            while ( $last_joined_date <= date('Y-m-d h:m:s'))
            {
                $last_joined_date  = date('Y-m-d h:m:s',strtotime('+365 days', strtotime($start_joined_date)));

                $valueArray['MOB_STATUS']="Y";

                $lastLoginLimit = date('Y-m-d', strtotime("-365 days"));

                $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;
               
                $greaterThanArray["ENTRY_DT"] = $start_joined_date;
                
                $lessThanArray["ENTRY_DT"] = $last_joined_date;

                $fields="EMAIL,PHONE_MOB";


                $result = $profileObj->getArray($valueArray, "", $greaterThanArray, $fields ,  $lessThanArray, "", "", "", "", "","","");

                            

                if ( !empty($result))
                {
                   foreach ($result as $key => $data) 
                   {
                        $this->emailExclusion[]=$data['EMAIL'];
                        $this->mobileExclusion[]=$data['PHONE_MOB'];
                    }
                }
                
                $start_joined_date = $last_joined_date;
                
            }
        }
        catch (Exception $e) 
        {
            throw new jsException($e);    
        }
        
    }

    /**
     * gets data to be send to facebook api to generate look alike audience
     */
    private function getInclusionData()
    {
        try 
        {
                        
            $misSource= new MIS_SOURCE();

            $profileObj = new JPROFILE();
           
            // echo "I am in source group.";

            foreach ($this->sourceGroupArray as $sourceGroup) { 

                $arraySourceId = $misSource->getSourceID($sourceGroup);

                //check whether arraySourceId is empty or not

                if ( !empty($arraySourceId))
                {
                    // echo $this->sourceGroupArray[$i];
                    $valueArray['activatedKey'] = '1';
                    $valueArray['SOURCE'] = implode(",", $arraySourceId);
                    
                    // mobile should be verified.
                    $valueArray['MOB_STATUS']="Y";

                    //member should of core community
                    $valueArray['MTONGUE']=implode(",", $this->coreCommunity);

                    //the last login must be greater than 150 days before
                  
                    $lastLoginLimit = date('Y-m-d', strtotime("-150 days"));

                    $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;

                    //shouldn't be deleted
                    $excludeArray["ACTIVATED"] = "'D'";

                    $fields="EMAIL,PHONE_MOB";
                    $qualityProfileQuery = "((`GENDER` = 'M' AND `AGE` >= 26) ||  (`GENDER` = 'F' AND `AGE` >= 22))";

                    $result = $profileObj->getArray($valueArray, $excludeArray, $greaterThanArray, $fields ,  "", "", "", "", "", "","",$qualityProfileQuery);


                    if ( !empty($result))
                    {
                       foreach ($result as $key => $data) 
                       {
                            $this->emailInclusion[]=$data['EMAIL'];
                            $this->mobileInclusion[]=$data['PHONE_MOB'];
                        }
                    }
                }
            }
        
        } 
        catch (Exception $e) 
        {
            throw new jsException($e);
        }
    }
}

