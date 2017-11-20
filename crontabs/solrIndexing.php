<?php
ini_set('max_execution_time', '0');
ini_set('memory_limit', '1024M');
$flag_using_php5 = 1;
include(JsConstants::$docRoot . "/profile/config.php");
include(JsConstants::$docRoot . "/profile/connect.inc");
include_once(JsConstants::$docRoot . "/classes/Mysql.class.php");
include_once(JsConstants::$docRoot . "/classes/Memcache.class.php");
include_once(JsConstants::$docRoot . "/profile/connect_functions.inc");
include_once(JsConstants::$docRoot . "/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.inc");
include_once(JsConstants::$docRoot . "/commonFiles/sms_inc.php");
global $errorMsg, $errorServer;
$lastModifiedOn = "-2 Hour"; // time to fetch data from search_male/female if not found in cache
$postLimit = 1000; // post 1000 profiles at once
$errorMsg = array(); // error message array
$errorServer = array(); // error server array
$params = $paramTypes = array();
$type = $argv[1];
$pid = $argv[2];
$gender = $argv[3];

$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/solrCommit.txt";
file_put_contents($fileName,"\n".'Started at -'.date("Y-m-d H:i:s", strtotime("now"))."\n", FILE_APPEND);

$lastIndexOn = date("Y-m-d H:i:s", strtotime($lastModifiedOn));

if ($type == "PID") {
        if (!$pid) {
                die("Invalid 2nd arguments value: Profile Id Missing");
        } else {
                $params = array("PROFILEID" => $pid);
                $paramTypes = array("PROFILEID" => array("type" => PDO::PARAM_INT, "operator" => "equal"));
        }
} elseif ($type == "DELTA") {
        $params = array("LAST_MODIFIED" => $lastIndexOn);
        $paramTypes = array("LAST_MODIFIED" => array("type" => PDO::PARAM_STR, "operator" => "greatorthanequal"));
}

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master", "ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000', $connSlave);

//*************** set where condition on the basis of parameters START*******************
$whrCondition = '';
if (!empty($params)) {
        $whrCondition .= " WHERE ";
        foreach ($params as $key => $value) {
                if ($paramTypes[$key]['type'] == PDO::PARAM_STR) {
                        $value = "'" . $value . "'";
                }
                if ($paramTypes[$key]['operator'] == 'greatorthanequal')
                        $sqlSelectDetailArray[] = "S." . $key . " > " . $value;
                elseif ($paramTypes[$key]['operator'] == 'equal')
                        $sqlSelectDetailArray[] = "S." . $key . " = " . $value;
        }
        $whrCondition .= implode(" AND ", $sqlSelectDetailArray);
}
//*************** set where condition on the basis of parameters ENDS*******************

// Post female profiles data
if ($type == "DELTA" || $type == "FULL" || ($type == "PID" && $gender == "F")) {
        $sqlSelectDetail = "SELECT S.PROFILEID AS id,USERNAME,S.RELIGION AS RELIGION,S.CASTE AS CASTE,S.MTONGUE AS MTONGUE,FEATURE_PROFILE,IF(S.COUNTRY_RES=51 AND S.NATIVE_CITY!='',CONCAT_WS(',',S.CITY_RES,S.NATIVE_CITY),S.CITY_RES) AS CITY_RES,IF(S.COUNTRY_RES=51 AND S.CITY_RES REGEXP '[a-z]+[0-9]',IF(S.COUNTRY_RES=51 AND S.NATIVE_CITY!='',CONCAT_WS(',',S.CITY_RES,S.NATIVE_CITY),S.CITY_RES),'') AS CITY_INDIA,IF(S.CITY_RES IN ('DE00','UP25','HA03','HA02','UP12','UP47','UP48','MH04','MH12','MH28','MH29','KA02','WB05','TN02','AP03','GU01'),1,0) AS CITY_METRO,S.AGE AS AGE,HEIGHT, CONCAT_WS(',',EDU_LEVEL_NEW, IF(UG_DEGREE, IF(UG_DEGREE!=EDU_LEVEL_NEW,UG_DEGREE,NULL),NULL),IF(PG_DEGREE, IF(PG_DEGREE!=EDU_LEVEL_NEW,PG_DEGREE,NULL),NULL)) as EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,S.INCOME AS INCOME,S.COUNTRY_RES AS COUNTRY_RES,S.MSTATUS AS MSTATUS,IF(DIET!='',DIET,'NS') AS DIET,  IF(DRINK!='',DRINK,'NS') AS DRINK,IF(SMOKE!='',SMOKE,'NS') AS SMOKE, IF(MANGLIK!='',MANGLIK,'D') as MANGLIK ,RELATION,IF(HAVECHILD!='',HAVECHILD,'N') AS HAVECHILD ,COMPLEXION , BTYPE,IF(HANDICAPPED!='',HANDICAPPED,'NS') AS HANDICAPPED, HAVEPHOTO,(S.CASTE*1000+S.MTONGUE) AS NEW_CASTE_MTONGUE , PROFILE_SCORE , FRESHNESS_POINTS , INCOME_SORTBY , DATEDIFF( CURDATE( ) , SORT_DT ) AS SORT_DT , TOTAL_POINTS , if(S.COUNTRY_RES=51,1,2) as INDIA_NRI ,HOROSCOPE, SPEAK_URDU , HIJAB_MARRIAGE, SAMPRADAY, ZARATHUSHTRI, AMRITDHARI, CUT_HAIR , MATHTHAB, WORK_STATUS , IF(HIV!='',HIV,'NS') as HIV, NATURE_HANDICAP, GOTHRA , SUBCASTE , YOURINFO , FAMILYINFO, EDUCATION , IF(NATIVE_STATE!='',NATIVE_STATE,'NS') AS NATIVE_STATE, JOB_INFO,ANCESTRAL_ORIGIN,DATE_FORMAT(DATE(ENTRY_DT),'%Y-%m-%dT%TZ') AS ENTRY_DT, DATE_FORMAT(LAST_LOGIN_DT,'%Y-%m-%dT%TZ') AS LAST_LOGIN_DT,SUBSCRIPTION,COMPANY_NAME,COLLEGE,PG_COLLEGE,SCHOOL,KEYWORDS,NAKSHATRA, IF(PROFILE_SCORE>326,100000,0) +  POPULAR*1000 AS POPULAR ,IF(PHOTO_DISPLAY='C',1,IF(PRIVACY='F',2,IF(PRIVACY='R',2,4))) AS PHOTO_VISIBILITY_LOGGEDOUT,IF(PHOTO_DISPLAY='C',1,2) as PHOTO_VISIBILITY_LOGGEDIN,NTIMES*POW(2,-1*(DATEDIFF(CURDATE(),LAST_LOGIN_DT)/30)) as VIEW_SCORE_WITH_INACTIVE_PENALITY,NTIMES, 'F' as GENDER ,IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <7, 1, IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <30, 2, IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <60, 3, 4))) AS LAST_ACTIVITY,IF(DATEDIFF(CURDATE(),ENTRY_DT)<7,1,IF(DATEDIFF(CURDATE(),ENTRY_DT)<14,2,IF(DATEDIFF(CURDATE(),ENTRY_DT)<30,3,IF(DATEDIFF(CURDATE(),ENTRY_DT)<60,4,5)))) AS PROFILE_ADDED,MARRIED_WORKING,GOING_ABROAD,EDUCATION_GROUPING,OCCUPATION_GROUPING,CASTE_GROUP,IF(S.COUNTRY_RES=51 AND NATIVE_STATE!='',CONCAT_WS(',',S.STATE,NATIVE_STATE),S.STATE) AS STATE,PHOTO_DISPLAY,IF(PRIVACY='','A',PRIVACY) AS PRIVACY,(IF(PROFILE_SCORE>325,96,IF(PROFILE_SCORE >150,41,27))+(IF(FRESHNESS_POINTS=300,85,25))) as PROFILE_FRESHNESS_SCORE,LINKEDIN,ASTRO_DETAILS,PHOTOSCREEN,R.PARTNER_LAGE AS PARTNER_LAGE,R.PARTNER_HAGE AS PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,IF(PARTNER_MTONGUE IS NULL OR PARTNER_MTONGUE='',99999,PARTNER_MTONGUE) AS PARTNER_MTONGUE,IF(PARTNER_CASTE IS NULL OR PARTNER_CASTE='',99999,PARTNER_CASTE) AS PARTNER_CASTE,IF(PARTNER_RELIGION IS NULL OR PARTNER_RELIGION='',99999,PARTNER_RELIGION) AS PARTNER_RELIGION,IF(PARTNER_COUNTRYRES IS NULL OR PARTNER_COUNTRYRES='',99999,PARTNER_COUNTRYRES) AS PARTNER_COUNTRYRES,IF(PARTNER_BTYPE IS NULL OR PARTNER_BTYPE='',99999,PARTNER_BTYPE) AS PARTNER_BTYPE,IF(PARTNER_COMP IS NULL OR PARTNER_COMP='',99999,PARTNER_COMP) AS PARTNER_COMP,IF(PARTNER_ELEVEL_NEW IS NULL OR PARTNER_ELEVEL_NEW='',99999,PARTNER_ELEVEL_NEW) AS PARTNER_ELEVEL_NEW,IF(PARTNER_INCOME IS NULL OR PARTNER_INCOME='',99999,PARTNER_INCOME) AS PARTNER_INCOME,IF(PARTNER_INCOME_FILTER IS NULL OR PARTNER_INCOME_FILTER='',99999,PARTNER_INCOME_FILTER) AS PARTNER_INCOME_FILTER,IF(PARTNER_OCC IS NULL OR PARTNER_OCC='',99999,PARTNER_OCC) AS PARTNER_OCC,IF(PARTNER_MSTATUS IS NULL OR PARTNER_MSTATUS='',99999,PARTNER_MSTATUS) AS PARTNER_MSTATUS,IF(PARTNER_CITYRES IS NULL OR PARTNER_CITYRES='',99999,PARTNER_CITYRES) AS PARTNER_CITYRES,IF(PARTNER_STATE IS NULL OR PARTNER_STATE='',99999,PARTNER_STATE) AS PARTNER_STATE,IF(PARTNER_DRINK IS NULL OR PARTNER_DRINK='',99999,PARTNER_DRINK) AS PARTNER_DRINK,IF(PARTNER_SMOKE IS NULL OR PARTNER_SMOKE='',99999,PARTNER_SMOKE) AS PARTNER_SMOKE,IF(PARTNER_DIET IS NULL OR PARTNER_DIET='',99999,PARTNER_DIET) AS PARTNER_DIET,IF(PARTNER_HANDICAPPED IS NULL OR PARTNER_HANDICAPPED='',99999,PARTNER_HANDICAPPED) AS PARTNER_HANDICAPPED,IF(PARTNER_MANGLIK IS NULL OR PARTNER_MANGLIK='',99999,PARTNER_MANGLIK) AS PARTNER_MANGLIK,FEATURE_PROFILE_SCORE,UG_DEGREE,PG_DEGREE,OTHER_UG_DEGREE,OTHER_PG_DEGREE,CHECK_PHONE ,IF(F.AGE IS NULL OR F.AGE='' OR R.PARTNER_LAGE IS NULL OR R.PARTNER_LAGE='' OR R.PARTNER_HAGE IS NULL OR R.PARTNER_HAGE='','N',F.AGE) AS AGE_FILTER,IF(F.MSTATUS IS NULL OR F.MSTATUS='' OR PARTNER_MSTATUS IS NULL OR PARTNER_MSTATUS='','N',F.MSTATUS) AS MSTATUS_FILTER,IF(F.RELIGION IS NULL OR F.RELIGION='' OR PARTNER_RELIGION IS NULL OR PARTNER_RELIGION='','N',F.RELIGION) AS RELIGION_FILTER,IF(F.CASTE IS NULL OR F.CASTE='' OR PARTNER_CASTE IS NULL OR PARTNER_CASTE='','N',F.CASTE) AS CASTE_FILTER,IF(F.COUNTRY_RES IS NULL OR F.COUNTRY_RES='' OR PARTNER_COUNTRYRES IS NULL OR PARTNER_COUNTRYRES='','N',F.COUNTRY_RES) AS COUNTRY_RES_FILTER,IF(F.CITY_RES IS NULL OR F.CITY_RES='' OR PARTNER_CITYRES IS NULL OR PARTNER_CITYRES='','N',F.CITY_RES) AS CITY_RES_FILTER,IF(F.MTONGUE IS NULL OR F.MTONGUE='' OR PARTNER_MTONGUE IS NULL OR PARTNER_MTONGUE='','N',F.MTONGUE) AS MTONGUE_FILTER,IF(F.INCOME IS NULL OR F.INCOME='' OR PARTNER_INCOME_FILTER='' OR PARTNER_INCOME_FILTER IS NULL,'N',F.INCOME) AS INCOME_FILTER,0 as ONLINE,DATE_FORMAT(VERIFY_ACTIVATED_DT,'%Y-%m-%dT%TZ') as VERIFY_ACTIVATED_DT,DATE_FORMAT(VERIFY_ACTIVATED_DT,'%Y-%m-%dT00:00:00Z') as VERIFY_ACTIVATED_DT_ONLY,VERIFICATION_SEAL,IF(DATEDIFF(CURDATE( ) , LAST_LOGIN_DT)<=15,100,0) as LAST_LOGIN_SCORE,IF(NAME_OF_USER!='',NAME_OF_USER,'') as NAME_OF_USER, IF(DATEDIFF(CURDATE( ) , PAID_DATE)<=15,100,0) as PAID_ON_SCORE,if(KNOWN_COLLEGE IS NULL ,'000',KNOWN_COLLEGE) as KNOWN_COLLEGE FROM (((SEARCH_FEMALE AS S INNER JOIN SEARCH_FEMALE_TEXT AS E ON S.PROFILEID = E.PROFILEID) LEFT JOIN SEARCH_FEMALE_REV R ON S.PROFILEID = R.PROFILEID) LEFT JOIN newjs.FILTERS F ON S.PROFILEID=F.PROFILEID) ";
        $sqlSelectDetail .= $whrCondition;
        $resSelectDetail = $mysqlObjS->executeQuery($sqlSelectDetail, $connSlave) or die($selectSql);

        $postCounter = 1;
        while ($rowSelectDetail = $mysqlObjS->fetchAssoc($resSelectDetail)) {
                $detailArr[] = $rowSelectDetail;
                if ($postCounter == $postLimit) { // post to solr if solr post limit reached and empty array and continue
                        curlPostJsonData(json_encode($detailArr));
                        $postCounter = 0;
                        $detailArr = array();
                }
                $postCounter++;
        }
        if (!empty($detailArr)) {
                curlPostJsonData(json_encode($detailArr));
        }
}

//Post male profiles...
if ($type == "DELTA" || $type == "FULL" || ($type == "PID" && $gender == "M")) {
        $sqlSelectDetail = "SELECT S.PROFILEID AS id,USERNAME,S.RELIGION AS RELIGION,S.CASTE AS CASTE,S.MTONGUE AS MTONGUE,FEATURE_PROFILE,IF(S.COUNTRY_RES=51 AND S.NATIVE_CITY!='',CONCAT_WS(',',S.CITY_RES,S.NATIVE_CITY),S.CITY_RES) AS CITY_RES,IF(S.COUNTRY_RES=51 AND S.CITY_RES REGEXP '[a-z]+[0-9]',IF(S.COUNTRY_RES=51 AND S.NATIVE_CITY!='',CONCAT_WS(',',S.CITY_RES,S.NATIVE_CITY),S.CITY_RES),'') AS CITY_INDIA,IF(S.CITY_RES IN ('DE00','UP25','HA03','HA02','UP12','UP47','UP48','MH04','MH12','MH28','MH29','KA02','WB05','TN02','AP03','GU01'),1,0) AS CITY_METRO,S.AGE AS AGE,HEIGHT,CONCAT_WS(',',EDU_LEVEL_NEW, IF(UG_DEGREE, IF(UG_DEGREE!=EDU_LEVEL_NEW,UG_DEGREE,NULL),NULL),IF(PG_DEGREE, IF(PG_DEGREE!=EDU_LEVEL_NEW,PG_DEGREE,NULL),NULL)) AS EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,S.INCOME AS INCOME,S.COUNTRY_RES AS COUNTRY_RES,S.MSTATUS AS MSTATUS,IF(DIET!='',DIET,'NS') AS DIET,  IF(DRINK!='',DRINK,'NS') AS DRINK,IF(SMOKE!='',SMOKE,'NS') AS SMOKE, IF(MANGLIK!='',MANGLIK,'D') as MANGLIK ,RELATION,IF(HAVECHILD!='',HAVECHILD,'N') AS HAVECHILD ,COMPLEXION , BTYPE,IF(HANDICAPPED!='',HANDICAPPED,'NS') AS HANDICAPPED, HAVEPHOTO,(S.CASTE*1000+S.MTONGUE) AS NEW_CASTE_MTONGUE , PROFILE_SCORE , FRESHNESS_POINTS , INCOME_SORTBY , DATEDIFF( CURDATE( ) , SORT_DT ) AS SORT_DT , TOTAL_POINTS , if(S.COUNTRY_RES=51,1,2) as INDIA_NRI ,HOROSCOPE, SPEAK_URDU , HIJAB_MARRIAGE, SAMPRADAY, ZARATHUSHTRI, AMRITDHARI, CUT_HAIR , WEAR_TURBAN,MATHTHAB, WORK_STATUS , IF(HIV!='',HIV,'NS') AS HIV, NATURE_HANDICAP, GOTHRA , SUBCASTE , YOURINFO , FAMILYINFO, EDUCATION , IF(NATIVE_STATE!='',NATIVE_STATE,'NS') AS NATIVE_STATE, JOB_INFO,ANCESTRAL_ORIGIN,DATE_FORMAT(DATE(ENTRY_DT),'%Y-%m-%dT%TZ') AS ENTRY_DT, DATE_FORMAT(LAST_LOGIN_DT,'%Y-%m-%dT%TZ') AS LAST_LOGIN_DT,LIVE_PARENTS,SUBSCRIPTION,COMPANY_NAME,COLLEGE,PG_COLLEGE,SCHOOL,KEYWORDS,NAKSHATRA, IF(PROFILE_SCORE>326,100000,0) +  POPULAR*1000 AS POPULAR ,IF(PHOTO_DISPLAY='C',1,IF(PRIVACY='F',2,IF(PRIVACY='R',2,4))) AS PHOTO_VISIBILITY_LOGGEDOUT,IF(PHOTO_DISPLAY='C',1,2) as PHOTO_VISIBILITY_LOGGEDIN,NTIMES*POW(2,-1*(DATEDIFF(CURDATE(),LAST_LOGIN_DT)/30)) as VIEW_SCORE_WITH_INACTIVE_PENALITY,NTIMES, 'M' as GENDER ,IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <7, 1, IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <30, 2, IF (DATEDIFF(CURDATE( ) , LAST_LOGIN_DT) <60, 3, 4))) AS LAST_ACTIVITY,IF(DATEDIFF(CURDATE(),ENTRY_DT)<7,1,IF(DATEDIFF(CURDATE(),ENTRY_DT)<14,2,IF(DATEDIFF(CURDATE(),ENTRY_DT)<30,3,IF(DATEDIFF(CURDATE(),ENTRY_DT)<60,4,5)))) AS PROFILE_ADDED,MARRIED_WORKING,GOING_ABROAD,EDUCATION_GROUPING,OCCUPATION_GROUPING,CASTE_GROUP,IF(S.COUNTRY_RES=51 AND NATIVE_STATE!='',CONCAT_WS(',',S.STATE,NATIVE_STATE),S.STATE) AS STATE,PHOTO_DISPLAY,IF(PRIVACY='','A',PRIVACY) AS PRIVACY,(IF(PROFILE_SCORE>325,96,IF(PROFILE_SCORE >150,41,27))+(IF(FRESHNESS_POINTS=300,85,25))) as PROFILE_FRESHNESS_SCORE,LINKEDIN,ASTRO_DETAILS,PHOTOSCREEN,R.PARTNER_LAGE AS PARTNER_LAGE,R.PARTNER_HAGE AS PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,IF(PARTNER_MTONGUE IS NULL OR PARTNER_MTONGUE='',99999,PARTNER_MTONGUE) AS PARTNER_MTONGUE,IF(PARTNER_CASTE IS NULL OR PARTNER_CASTE='',99999,PARTNER_CASTE) AS PARTNER_CASTE,IF(PARTNER_RELIGION IS NULL OR PARTNER_RELIGION='',99999,PARTNER_RELIGION) AS PARTNER_RELIGION,IF(PARTNER_COUNTRYRES IS NULL OR PARTNER_COUNTRYRES='',99999,PARTNER_COUNTRYRES) AS PARTNER_COUNTRYRES,IF(PARTNER_BTYPE IS NULL OR PARTNER_BTYPE='',99999,PARTNER_BTYPE) AS PARTNER_BTYPE,IF(PARTNER_COMP IS NULL OR PARTNER_COMP='',99999,PARTNER_COMP) AS PARTNER_COMP,IF(PARTNER_ELEVEL_NEW IS NULL OR PARTNER_ELEVEL_NEW='',99999,PARTNER_ELEVEL_NEW) AS PARTNER_ELEVEL_NEW,IF(PARTNER_INCOME IS NULL OR PARTNER_INCOME='',99999,PARTNER_INCOME) AS PARTNER_INCOME,IF(PARTNER_INCOME_FILTER IS NULL OR PARTNER_INCOME_FILTER='',99999,PARTNER_INCOME_FILTER) AS PARTNER_INCOME_FILTER,IF(PARTNER_OCC IS NULL OR PARTNER_OCC='',99999,PARTNER_OCC) AS PARTNER_OCC,IF(PARTNER_MSTATUS IS NULL OR PARTNER_MSTATUS='',99999,PARTNER_MSTATUS) AS PARTNER_MSTATUS,IF(PARTNER_CITYRES IS NULL OR PARTNER_CITYRES='',99999,PARTNER_CITYRES) AS PARTNER_CITYRES,IF(PARTNER_STATE IS NULL OR PARTNER_STATE='',99999,PARTNER_STATE) AS PARTNER_STATE,IF(PARTNER_DRINK IS NULL OR PARTNER_DRINK='',99999,PARTNER_DRINK) AS PARTNER_DRINK,IF(PARTNER_SMOKE IS NULL OR PARTNER_SMOKE='',99999,PARTNER_SMOKE) AS PARTNER_SMOKE,IF(PARTNER_DIET IS NULL OR PARTNER_DIET='',99999,PARTNER_DIET) AS PARTNER_DIET,IF(PARTNER_HANDICAPPED IS NULL OR PARTNER_HANDICAPPED='',99999,PARTNER_HANDICAPPED) AS PARTNER_HANDICAPPED,IF(PARTNER_MANGLIK IS NULL OR PARTNER_MANGLIK='',99999,PARTNER_MANGLIK) AS PARTNER_MANGLIK,FEATURE_PROFILE_SCORE,WIFE_WORKING,UG_DEGREE,PG_DEGREE,OTHER_UG_DEGREE,OTHER_PG_DEGREE,CHECK_PHONE,IF(F.AGE IS NULL OR F.AGE='' OR R.PARTNER_LAGE IS NULL OR R.PARTNER_LAGE='' OR R.PARTNER_HAGE IS NULL OR R.PARTNER_HAGE='','N',F.AGE) AS AGE_FILTER,IF(F.MSTATUS IS NULL OR F.MSTATUS='' OR PARTNER_MSTATUS IS NULL OR PARTNER_MSTATUS='','N',F.MSTATUS) AS MSTATUS_FILTER,IF(F.RELIGION IS NULL OR F.RELIGION='' OR PARTNER_RELIGION IS NULL OR PARTNER_RELIGION='','N',F.RELIGION) AS RELIGION_FILTER,IF(F.CASTE IS NULL OR F.CASTE='' OR PARTNER_CASTE IS NULL OR PARTNER_CASTE='','N',F.CASTE) AS CASTE_FILTER,IF(F.COUNTRY_RES IS NULL OR F.COUNTRY_RES='' OR PARTNER_COUNTRYRES IS NULL OR PARTNER_COUNTRYRES='','N',F.COUNTRY_RES) AS COUNTRY_RES_FILTER,IF(F.CITY_RES IS NULL OR F.CITY_RES='' OR PARTNER_CITYRES IS NULL OR PARTNER_CITYRES='','N',F.CITY_RES) AS CITY_RES_FILTER,IF(F.MTONGUE IS NULL OR F.MTONGUE='' OR PARTNER_MTONGUE IS NULL OR PARTNER_MTONGUE='','N',F.MTONGUE) AS MTONGUE_FILTER,IF(F.INCOME IS NULL OR F.INCOME='' OR PARTNER_INCOME_FILTER='' OR PARTNER_INCOME_FILTER IS NULL,'N',F.INCOME) AS INCOME_FILTER,0 as ONLINE,DATE_FORMAT(VERIFY_ACTIVATED_DT,'%Y-%m-%dT%TZ') as VERIFY_ACTIVATED_DT,DATE_FORMAT(VERIFY_ACTIVATED_DT,'%Y-%m-%dT00:00:00Z') as VERIFY_ACTIVATED_DT_ONLY,VERIFICATION_SEAL,IF(DATEDIFF(CURDATE( ) , LAST_LOGIN_DT)<=15,100,0) as LAST_LOGIN_SCORE,IF(NAME_OF_USER!='',NAME_OF_USER,'') as NAME_OF_USER, IF(DATEDIFF(CURDATE( ) , PAID_DATE)<=15,100,0) as PAID_ON_SCORE,if(KNOWN_COLLEGE IS NULL ,'000',KNOWN_COLLEGE) as KNOWN_COLLEGE FROM (((SEARCH_MALE AS S INNER JOIN SEARCH_MALE_TEXT AS E ON S.PROFILEID = E.PROFILEID) LEFT JOIN newjs.SEARCH_MALE_REV R ON S.PROFILEID = R.PROFILEID) LEFT JOIN newjs.FILTERS F ON S.PROFILEID=F.PROFILEID) " . $whrCondition;

        $resSelectDetail = $mysqlObjS->executeQuery($sqlSelectDetail, $connSlave) or die($selectSql);
        $postCounter = 1;
        $detailArr = array();
        while ($rowSelectDetail = $mysqlObjS->fetchAssoc($resSelectDetail)) {
                $detailArr[] = $rowSelectDetail;
                if ($postCounter == $postLimit) {
                        curlPostJsonData(json_encode($detailArr));
                        $postCounter = 0;
                        $detailArr = array();
                }
                $postCounter++;
        }
        if (!empty($detailArr)) {
                curlPostJsonData(json_encode($detailArr));
        }
}

if($type == "DELTA"){
        deleteHiddenDeletedProfiles();
}

$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/solrCommit.txt";
file_put_contents($fileName,"\n".'Commit Started at -'.date("Y-m-d H:i:s", strtotime("now"))."\n", FILE_APPEND);

// after posting data initiate commit on all servers
curlPostCommitData();

function deleteHiddenDeletedProfiles(){
        $deletedHiddenProfilesObj = new newjs_HIDDEN_DELETED_PROFILES('newjs_masterDDL');
        $profilesArr = $deletedHiddenProfilesObj->getProfiles();
        if($profilesArr)
        {
                $strProfilesArr = implode(" ",$profilesArr);
                $SearchServiceObj = new SearchService();
                $SearchServiceObj->deleteIdsFromSearch($strProfilesArr);
                $deletedHiddenProfilesObj->truncateTable(date('Y-m-d h:i:s',strtotime('-4 Hours')));
        }
}
/**
 * Function to post data on solr
 * @global type $errorMsg
 * @global type $errorServer
 * @param type $profiles json encoded profile data
 */
function curlPostJsonData($profiles) {
        global $errorMsg, $errorServer;
        $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/SolrError_".date('Y_m_d').".txt";
        $timeout = 50000;
        $threshold = 10;
        $append = "/update?&overwrite=true&wt=json";
        foreach(JsConstants::$solrServerUrls as $key=>$solrUrl){
                $index = array_search($solrUrl, JsConstants::$solrServerUrls);
                if($index == $key && $solrUrl == JsConstants::$solrServerUrls[$index]){
                        $urlToHit = $solrUrl.$append;
                        $ch = curl_init($urlToHit);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $profiles);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
                        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout * 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        $output = curl_exec($ch);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
						$headerStr = substr($output, 0, $header_size);
						$output = substr($output, $header_size);
                        $unserialzedOutput = json_decode($output);
                        if ($unserialzedOutput->responseHeader->status != 0) {
                                file_put_contents($fileName, "Solr Error::: ".$output."\n\n", FILE_APPEND);
                                $errorMsg[] = $unserialzedOutput->error->msg;
                                $errorServer[] = $k;
                        }
                        if(count($errorMsg) >= $threshold){
                                sendsolrSMS();
                                $msg = formatMsg();
                                notify($msg);
                                $errorMsg = array();
                                $errorServer = array();
                        }
                }
        }
}
/**
 * This function finally initiate commits command on all servers
 * @global type $errorMsg
 * @global type $errorServer
 */
function curlPostCommitData() {
        global $errorMsg, $errorServer;
        $timeout = 50000;
        $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/solrCommit.txt";
        $append = "/update?commit=true&wt=json";
        foreach(JsConstants::$solrServerUrls as $key=>$solrUrl){
                $index = array_search($solrUrl, JsConstants::$solrServerUrls);
                if($index == $key && $solrUrl == JsConstants::$solrServerUrls[$index]){
                        $urlToHit = $solrUrl.$append;
                        file_put_contents($fileName, date("Y-m-d H:i:s", strtotime("now")).':: M-'.$urlToHit."\n", FILE_APPEND);
                        $ch = curl_init($urlToHit);
                        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
						curl_setopt($ch, CURLOPT_HEADER, $header);
						curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
                        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout * 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        $output = curl_exec($ch);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
						$headerStr = substr($output, 0, $header_size);
						$output = substr($output, $header_size);
                        $unserialzedOutput = json_decode($output);
                        if ($unserialzedOutput->responseHeader->status != 0) {
                                $errorMsg[] = $unserialzedOutput->error->msg. " IN COMMIT";
                                $errorServer[] = $k;
                        }
                        sleep(120);
                }
        }
        if(!empty($errorMsg)){
                sendsolrSMS();
                $msg = formatMsg();
                notify($msg);
        }
}

/**
 * this function sends sms on error
 * @global type $errorMsg
 * @global type $errorServer
 */
function sendsolrSMS() {
        global $errorMsg, $errorServer;
        $FROM_ID = "JSSRVR";
        $PROFILE_ID = "144111";
        $SMS_TO = array('9773889617',"9873639543","9818424749");
        $servers = implode(',', array_unique($errorServer));
        $smsMessage = "Mysql Error Count have reached Threshold on " . $servers . " solr post failed within 5 minutes";
        foreach ($SMS_TO as $mobPhone) {
                $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
                $xml_content = "%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22" . urlencode($smsMessage) . "%22%20PROPERTY=%220%22%20ID=%22" . $PROFILE_ID . "%22%3E%3CADDRESS%20FROM=%22" . $FROM_ID . "%22%20TO=%22" . $mobPhone . "e%22%20SEQ=%22" . $PROFILE_ID . "%22%20TAG=%22%22/%3E%3C/SMS%3E";
                $xml_end = "%3C/MESSAGE%3E";
                $xml_code = $xml_head . $xml_content . $xml_end;
                $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
                if ($fd) {
                        $response = '';
                        while (!feof($fd)) {
                                $response.= fread($fd, 4096);
                        }
                        fclose($fd);
                        CommonUtility::logTechAlertSms($smsMessage, $mobPhone);
                }
        }
}

/*
 * This function formats the server's error messages and append it to mailmessage variable
 */

function formatMsg() {
        global $errorMsg, $errorServer;
        foreach ($errorServer as $k => $server) {
                $mailMessage = "Error Details $server <br/>";
                $mailMessage .= $errorMsg[$k] . "<br/>";
        }
        return $mailMessage;
}

/*
 * This function trigger email
 */

function notify($mailMessage) {
        $EMAIL_TO = "bhavana.kadwal@jeevansathi.com"; //",lavesh.rawat@gmail.com,bhavana.kadwal@jeevansathi.com";
        $dt = date("Y-m-d H:i:s");
        $serverMessage = "Hi,<br/><br/>" . "Please find below the Error Details of solr post failed.<br/>" . $mailMessage;
        SendMail::send_email($EMAIL_TO, $serverMessage, "Solr Post Failed - $dt");
}

?>
