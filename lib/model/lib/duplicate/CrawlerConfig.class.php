<?
class CrawlerConfig
{
	//list of checks that need to be done in the crawler logic
	static $crawlerChecks = array('BirthDetails','DtOfBirth','Age');

	//list of parameters to be fetched from JProfile for the profileid whose duplicates need to be found
	static $JprofileFields = "BTIME,GENDER,MTONGUE,RELIGION,MSTATUS,CASTE,DTOFBIRTH,COUNTRY_RES,CITY_RES,COUNTRY_BIRTH,CITY_BIRTH,EDU_LEVEL_NEW,HEIGHT,INCOME,AGE";

	//value represents date greater than 6 months
	static $greaterThanConditions = array("LAST_LOGIN_DT" => "6"); 

	//list of fields to be compared for finding duplicates using the following (BirthDetails) Logic
	//the array key represents the plus-minus value of each field
	static $birthDetailMatches = array("0" => array( "BTIME","GENDER","MTONGUE","RELIGION","MSTATUS","CASTE","DTOFBIRTH","COUNTRY_RES","CITY_RES","COUNTRY_BIRTH","CITY_BIRTH"),
					   "1" => NULL, 
					   "2" => NULL);

	//list of fields to be compared for finding duplicates using the following (Date of Birth) logic
	//the array key represents the plus-minus value of each field
	static $dtOfBirthMatches = array("0" => array("GENDER","MTONGUE","RELIGION","MSTATUS","CASTE","DTOFBIRTH","COUNTRY_RES","CITY_RES","EDU_LEVEL_NEW"), 
					 "1" => array("HEIGHT","INCOME"),
					 "2" => NULL);

	//list of fields to be compared for finding duplicates using the following logic
	//the array key represents the plus-minus value of each field
	static $ageMatches = array("0" => array("GENDER","MTONGUE","RELIGION","MSTATUS","CASTE","COUNTRY_RES","CITY_RES","EDU_LEVEL_NEW"), 
				   "1" => array("INCOME","AGE"), 
				   "2" => array("HEIGHT"));

	//list of parameters to be checked while marking profiles returned from the age logic as probable duplicate / not duplicate
	//array keys represent table names and values replresent the list of columns from each table
	//table JPROFILE and JPROFILE_EDUCATION are present in newjs db and NAME_OF_USER in incentive db
	static $secondaryParams = array("JPROFILE" => "PROFILEID,MESSENGER_ID,SUBCASTE,COMPANY_NAME,HEIGHT,OCCUPATION,EMAIL,IPADD",
					"JPROFILE_EDUCATION" => "PROFILEID,SCHOOL, COLLEGE, PG_COLLEGE",
					"NAME_OF_USER" => "PROFILEID,NAME");

	//list of values where only the string before @ is to be matched
	static $getValuesBeforeAtTheRate = array("MESSENGER_ID","EMAIL");

	//fields for which case insensitive matching needs to be done
	static $caseInsensitiveFields = array('SUBCASTE','COLLEGE','PG_COLLEGE','SCHOOL','COMPANY_NAME','NAME');

	//fields for which matching needs to be done after removing spaces and special characters
	static $excludeSpecialCharFields = array('SUBCASTE','COLLEGE','PG_COLLEGE','SCHOOL','COMPANY_NAME','NAME');

	//fields for which comparison is to be done without excluding any values and for which matching needs to be done which one field name only
	static $directComparison = array('COMPANY_NAME','SCHOOL','COLLEGE','PG_COLLEGE','SUBCASTE','NAME');

	//passwords that need to be ignored while matching
//	static $passwordsIgnore = array('india1947','jeevansathi');

	//ips which need to be ignored while matching
	static $ipsIgnore = array('115.249.243.194','121.243.22.130','182.19.23.18','183.182.85.228','122.160.211.2','61.246.241.189','111.118.255.112','110.234.12.82','115.254.79.170','111.118.255.113','111.118.248.186','115.249.45.252','115.249.44.252','115.249.25.252');

	//minimum no of secondary parameter matches required to mark a profile as a probable duplicate
	static $minimumMatchesRequiredToMarkProbable = 1;
}
