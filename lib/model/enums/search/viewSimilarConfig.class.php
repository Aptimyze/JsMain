<?php
//This class has configurabe variables for top search band
class viewSimilarConfig
{
	public static $suggAlgoScoreConst = 0.1; //constant used in contacts-algo
	public static $suggAlgoMinimumNoOfContactsRequired=10; //minimum no of contacts required for executing contacts algo
	public static $suggAlgoMaxLengthOfEachField = 16; //to be dislayed in the similar profile section of view profile page
	public static $suggAlgoNoOfResults = 15; //total no of results to be shown for a profile
	public static $suggAlgoNoOfResults_Mobile = 25; //total no of results to be shown for a profile
	public static $suggAlgoNoOfResultsNoFilter = 100; //total no of results to be send for a profile to search
	public static $suggAlgoNoOfResultsToBeShownAtATime=5; //max no of results to be shown at a time //changes for this variable to made in modules/profile/config/module.yml
	public static $suggAlgoNoOfResultsToBeShownAtATime_Mobile = 20; //total no of results to be shown for a profile
	public static $suggAlgoNoOfResultsToBeFetched=16; //no of results to be fetched from search, so that viewed and inactivated profiles can be removed
	public static $suggAlgoTimeToStoreResultsInMemcache=86400; //total time for which logged-out similar profile results are stored in memcache
    
    //mapping of search response fields to vsp response fields
    public static $SearchToVSPResponseMappingArr = array("profilechecksum"=>"PROFILECHECKSUM",
    													"userloginstatus"=>"userLoginStatus",
    													"subscription_icon"=>"SUBSCRIPTION",
    													"age"=>"AGE",
    													"username"=>"USERNAME",
    													"height"=>"DECORATED_HEIGHT",
    													"occupation"=>"DECORATED_OCCUPATION",
    													"caste"=>"DECORATED_CASTE",
    													"mtongue"=>"DECORATED_MTONGUE",
    													"edu_level_new"=>"DECORATED_EDU_LEVEL_NEW",
    													"location"=>"DECORATED_CITY_RES",
    													"income"=>"DECORATED_INCOME",
    													"photo"=>"PHOTO",
    													"album_count"=>"ALBUM_COUNT",
    													"religion"=>"DECORATED_RELIGION",
    													"featured"=>"FEATURED",
    													"verification_seal"=>"VERIFICATION_SEAL",
    													"mstatus"=>"MSTATUS",
    													"college"=>"COLLEGE",
    													"pg_college"=>"PG_COLLEGE",
    													"company_name"=>"COMPANY_NAME",
    													"name_of_user"=>"NAME_OF_USER",
    													"orig_username"=>"USERNAME",
    													"gender"=>"GENDER"
    													); 

        public static function getFieldLabel($label,$value,$returnArr=''){
	switch($label){
			
	case "AgeGroupSuggAlgo":

		$arr=array("MALE"=>array("21" => "21,25",
					"22" => "21,25",
					"23" => "22,25",
					"24" => "22,26",
					"25" => "23,27",
					"26" => "24,28",
					"27" => "25,29",
					"28" => "26,30",
					"29" => "26,30",
					"30" => "28,32",
					"31" => "28,33",
					"32" => "29,35",
					"33" => "30,36",
					"34" => "30,37",
					"35" => "31,39",
					"MAX" => "32,70"),
				"FEMALE"=>array("18" => "18,22",
					"19" => "18,22",
					"20" => "18,23",
					"21" => "18,24",
					"22" => "19,25",
					"23" => "20,26",
					"24" => "21,27",
					"25" => "22,28",
					"26" => "23,29",
					"27" => "24,29",
					"28" => "25,30",
					"29" => "26,32",
					"30" => "27,33",
					"31" => "28,34",
					"32" => "29,35",
					"33" => "30,36",
					"MAX" => "31,70"));

		break;
        default :
                break;
        }
        	return $arr;
        }


}
?>
