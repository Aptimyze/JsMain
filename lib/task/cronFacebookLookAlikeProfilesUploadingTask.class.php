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
        ini_set('memory_limit','1024M');

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

        $androidAppRequirements = array(
                'access_token' => "EAAIUo67ZBFGEBAC83iZCgHVBZCnVOrZBSbwJF4ME5YiqGlsOSaZBXtPUX2KtcuoOLikHcmBVIZBKVFWRelHdv7MPDWU4peOwW6ZCzD8OYYfQuYKghUwQ6elBZByCHCkvAfSSc0xuLMCyXyEaWlRxqJumsd4iDNWegdg2aZCfHGS1qpQZDZD",
                'app_id' => "585643201533025",
                'app_secret' => "775d11b4ebb8dc803ff439cb59fc292a",
                'account_id' => "act_1383924095217677",
                'includeCustomAudienceId' => "6053066635398",
                'excludeCustomAudienceId' => "6053066601998",
                 );

        
        $iosAppRequirements = array(
                'access_token' => "EAALjaF5TfOcBADYA2CSCZAaUdPepNW3ZAVT6ZAkQujZCzZBpmDrj7ZBsUZBvZCcZBJH6A38MK8tgvwwOVYUjtcRNux5gWfApCY87s2SDPoC5jk8hHHsWYb3y4twstJuBJM9szrP1V6HFvFsISY5n16J2xKl5IwXIZCPvUn31YWuwNZBZBQZDZD",
                'app_id' => "812987352055015",
                'app_secret' => "7047cf9aca2e61aa836dad6651998eb8",
                'account_id' => "act_1380817025529770",
                'includeCustomAudienceId' => "6066220566480",
                'excludeCustomAudienceId' => "6066220612480",
                 );


        $this->requirements = array();

        $this->requirements[] = $androidAppRequirements;
        $this->requirements[] = $iosAppRequirements;
        
        $this->emailExclusion = array();
        $this->mobileExclusion = array();
        
        $this->emailInclusion = array();
        $this->mobileInclusion = array();

        try 
        {
            $this->getInclusionData();
            $this->getExclusionData();
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
    // private function removeUsersCustomAudience($customAudienceId,$data,$dataType)
    // {
    //     try 
    //     {
    //         $this->audience->id = $customAudienceId;
    //         $this->audience->removeUsers($data,$dataType);
    //     } 
    //     catch (Exception $e) 
    //     {
    //         throw new jsException($e);
    //     }
    // }

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
        echo "Created id: ".$this->audience->id;
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
            echo "the id is: " .$this->audience->id;
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
            $profileObj = new JPROFILE("newjs_slave");

            $start_joined_date  = date('1999-01-01 00:00:00');
        
            $last_joined_date = date('Y-m-d h:m:s',strtotime('+365 days', strtotime($start_joined_date)));

            $valueArray['MOB_STATUS']="Y";

            $lastLoginLimit = CommonUtility::makeTime(date('Y-m-d', strtotime("-365 days")));

            $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;
            
            $fields="EMAIL,PHONE_MOB";
            
            $fields_count="COUNT(*)";

            $limitPushExclude = 5000;

            $today =  date('Y-m-d h:m:s');

            $totalExclusionEmail = 0;
            $totalExclusionMobile = 0;
            
            while ( $last_joined_date <= $today )
            {
                $last_joined_date  = date('Y-m-d h:m:s',strtotime('+365 days', strtotime($start_joined_date)));
               
                $greaterThanArray["ENTRY_DT"] = $start_joined_date;
                
                $lessThanArray["ENTRY_DT"] = $last_joined_date;


                $totalResult = $profileObj->getArray($valueArray, "", $greaterThanArray, $fields_count ,  $lessThanArray, "", "", "", "", "","","");

                $totalCount = $totalResult[0]["COUNT(*)"];

                // var_dump($totalInclusionEmail)
                $i = 0;

                if ( $totalCount > 0 )
                {
                    do
                    {
                        $result = $profileObj->getArray($valueArray, "", $greaterThanArray, $fields ,  $lessThanArray, "", ($i * $limitPushExclude).",".$limitPushExclude, "", "", "","","");

                        if ( !empty($result))
                        {
                           foreach ($result as $key => $data) 
                           {
                                $this->emailExclusion[]=$data['EMAIL'];
                                $this->mobileExclusion[]=$data['PHONE_MOB'];
                            }

                            $totalExclusionEmail += count($this->emailExclusion);
                                
                            $totalExclusionMobile += count($this->mobileExclusion);
                                
                            print_r("emailExclusion: ".count($this->emailExclusion));
                            echo "\n";
                            print_r("mobileExclusion: ".count($this->mobileExclusion));
                            echo "\n";

                            file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbApi.txt",var_export($this->emailExclusion,true)."\n",FILE_APPEND);
                            file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbApi.txt",var_export($this->mobileExclusion,true)."\n\n\n\n",FILE_APPEND);

                            foreach ($this->requirements as $requirement) 
                            {
                                $this->access_token = $requirement['access_token'];

                                $this->app_id = $requirement['app_id'];
                                
                                $this->app_secret = $requirement['app_secret'];
                                
                                $this->account_id = $requirement['account_id'];
                                
                                $this->includeCustomAudienceId = $requirement['includeCustomAudienceId'];

                                $this->excludeCustomAudienceId = $requirement['excludeCustomAudienceId'];

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


                                    $this->addUsersCustomAudience($this->excludeCustomAudienceId,$this->emailExclusion,FacebookAds\Object\Values\CustomAudienceTypes::EMAIL);
            

                                    $this->addUsersCustomAudience($this->excludeCustomAudienceId,$this->mobileExclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
                                }
                                catch (Exception $e) {
                                     throw new jsException($e);
                                }
                            }

                            $this->emailExclusion = array();
                            $this->mobileExclusion = array();

                            $totalExclusionEmail = 0;
                            $totalExclusionMobile = 0;                        }
                        $i++;
                    } while (($i * $limitPushExclude) < $totalCount);
                }
                
                $start_joined_date = $last_joined_date;
            }

            echo "Total exclusion email: ".$totalExclusionEmail;
            echo "\n";
            echo "Total exclusion mobile: ".$totalExclusionMobile;
            echo "\n";

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
                        
            $misSource= new MIS_SOURCE("newjs_slave");

            $profileObj = new JPROFILE("newjs_slave");
           
            $valueArray['activatedKey'] = '1';
            // mobile should be verified.
            $valueArray['MOB_STATUS']="Y";

            //member should of core community
            $valueArray['MTONGUE']=implode(",", $this->coreCommunity);

            //the last login must be greater than 150 days before
          
            $lastLoginLimit = date('Y-m-d', strtotime("-150 days"));

            $greaterThanArray["LAST_LOGIN_DT"] = $lastLoginLimit;

            //shouldn't be deleted
            $valueArray["ACTIVATED"] = "'Y','H'";

            $fields="EMAIL,PHONE_MOB";

            $fields_count = "COUNT(*)";

            $limitPushInclude = 5000;

            $qualityProfileQuery = "((`GENDER` = 'M' AND `AGE` >= 26) ||  (`GENDER` = 'F' AND `AGE` >= 22))";

            $totalInclusionEmail = 0;
            $totalInclusionMobile = 0;

            foreach ($this->sourceGroupArray as $sourceGroup) { 

                $arraySourceId = $misSource->getSourceID($sourceGroup);

                //check whether arraySourceId is empty or not

                if ( !empty($arraySourceId))
                {
                    
                    $valueArray['SOURCE'] = implode(",", $arraySourceId);


                    $resultCount = $profileObj->getArray($valueArray, $excludeArray, $greaterThanArray, $fields_count ,  "", "", "", "", "", "","",$qualityProfileQuery);

                    // die(var_dump($resultCount[0]["count(*)"]));
                    $totalCount = $resultCount[0]["COUNT(*)"];
                    $i = 0;
                    
                    if ( $totalCount > 0 )
                    {
                        do
                        {

                             $result = $profileObj->getArray($valueArray, $excludeArray, $greaterThanArray, $fields ,  "", "", ($i * $limitPushInclude).",".$limitPushInclude, "", "", "","",$qualityProfileQuery);

                            if ( !empty($result))
                            {
                               foreach ($result as $key => $data) 
                                {
                                    $this->emailInclusion[]=$data['EMAIL'];
                                    $this->mobileInclusion[]=$data['PHONE_MOB'];
                                }


                                $totalInclusionEmail += count($this->emailInclusion);
                                
                                $totalInclusionMobile += count($this->mobileInclusion);
                                
                                print_r("emailInclusion: ".count($this->emailInclusion));
                                echo "\n";
                                print_r("mobileInclusion: ".count($this->mobileInclusion));
                                echo "\n";


                                file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbApi.txt",var_export($this->emailInclusion,true)."\n",FILE_APPEND);
                                file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbApi.txt",var_export($this->mobileInclusion,true)."\n\n\n\n",FILE_APPEND);

                                foreach ($this->requirements as $requirement) 
                                {

                                    $this->access_token = $requirement['access_token'];

                                    $this->app_id = $requirement['app_id'];
                                    
                                    $this->app_secret = $requirement['app_secret'];
                                    
                                    $this->account_id = $requirement['account_id'];
                                    
                                    $this->includeCustomAudienceId = $requirement['includeCustomAudienceId'];

                                    $this->excludeCustomAudienceId = $requirement['excludeCustomAudienceId'];

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


                                        $this->addUsersCustomAudience($this->includeCustomAudienceId,$this->emailInclusion,FacebookAds\Object\Values\CustomAudienceTypes::EMAIL);
            

                                        $this->addUsersCustomAudience($this->includeCustomAudienceId,$this->mobileInclusion,FacebookAds\Object\Values\CustomAudienceTypes::PHONE);
                                    }
                                    catch (Exception $e) {
                                         throw new jsException($e);
                                    }
                                }

                                    $this->emailInclusion = array();
                                    $this->mobileInclusion = array();

                                    $totalInclusionEmail = 0;
                                    $totalInclusionMobile = 0;
                            } 
                            $i++;      
                        } while(($i * $limitPushInclude) < $totalCount);
                    }
                }
            }
            echo "\n";
            echo "Total inclusion email: ".$totalInclusionEmail;
            echo "\n";
            echo "Total inclusion mobile: ".$totalInclusionMobile;

        } 
        catch (Exception $e) 
        {
            throw new jsException($e);
        }
    }
}
