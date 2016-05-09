<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once("update_functions.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$filename = "overall_updates.csv";		//This filename to be used for overall updates of city values for merges and deletes
$data = read_file($filename);
foreach ($data as $k=>$v)
{
 	$values = explode("|",trim($v));
   	if (trim($values[0]) && trim($values[1]))
    	{
             	$old_value[] = trim($values[0]);
            	$new_value[] = trim($values[1]);
      	}
}

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$dbM) or die(mysql_error().$sql);

normal_update($old_value,$new_value,"JPROFILE","CITY_RES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|JPROFILE|CITY_RES|PROFILEID \n";
comma_separated_type3_update($old_value,$new_value,"SEARCH_AGENT","CITY_RES","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|CITY_RES|ID \n";
comma_separated_type3_update($old_value,$new_value,"SEARCH_AGENT","RES_STATUS","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|RES_STATUS|ID \n";
normal_update($old_value,$new_value,"SEARCH_FEMALE","CITY_RES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE|CITY_RES|PROFILEID \n";
normal_update($old_value,$new_value,"SEARCH_MALE","CITY_RES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE|CITY_RES|PROFILEID \n";

special_update($old_value,$new_value,"AP_DISPATCHER_CITIES","CITY","Assisted_Product",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_DISPATCHER_CITIES|CITY \n";
comma_separated_type1_update($old_value,$new_value,"AP_DPP_FILTER_ARCHIVE","PARTNER_CITYRES","Assisted_Product","DPP_ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_DPP_FILTER_ARCHIVE|PARTNER_CITYRES|DPP_ID \n";
normal_update($old_value,$new_value,"AP_SERVICE_TABLE","CITY","Assisted_Product","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_SERVICE_TABLE|CITY|PROFILEID \n";
comma_separated_type1_update($old_value,$new_value,"AP_TEMP_DPP","PARTNER_CITYRES","Assisted_Product","CREATED_BY,PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_TEMP_DPP|PARTNER_CITYRES|CREATED_BY,PROFILEID \n";


normal_update($old_value,$new_value,"FORCE_SCREEN","CITY_RES","MIS","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "MIS|FORCE_SCREEN|CITY_RES|PROFILEID \n";
normal_update($old_value,$new_value,"KEYWORD_PROFILE_REPORT","City","MIS","Profileid",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "MIS|KEYWORD_PROFILE_REPORT|City|Profileid \n";
normal_update($old_value,$new_value,"SKIIPPED_CITY_MTONGUE","CITY","MIS","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "MIS|SKIIPPED_CITY_MTONGUE|CITY|ID \n";
special_update($old_value,$new_value,"SOURCE_MEMBERS","CITY_RES","MIS",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "MIS|SOURCE_MEMBERS|CITY_RES \n";

normal_update($old_value,$new_value,"EASY_BILL_LOCATIONS","CITY_VALUE","billing","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "billing|EASY_BILL_LOCATIONS|CITY_VALUE|ID \n";
normal_update($old_value,$new_value,"VOUCHER_OPTIN","CITY_RES","billing","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "billing|VOUCHER_OPTIN|CITY_RES|ID \n";
normal_update($old_value,$new_value,"VOUCHER_SUCCESSSTORY","CITY_RES","billing","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "billing|VOUCHER_SUCCESSSTORY|CITY_RES|ID \n";

normal_update($old_value,$new_value,"AFFILIATE_DATA","CITY_RES","jsadmin","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|AFFILIATE_DATA|CITY_RES|PROFILEID \n";
special_update($old_value,$new_value,"MMM_NEARBRANCH","CITY_VALUE","jsadmin",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|MMM_NEARBRANCH|CITY_VALUE \n";
special_update($old_value,$new_value,"MMM_NEARBRANCH","NEAR_BRANCH","jsadmin",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|MMM_NEARBRANCH|NEAR_BRANCH \n";
normal_update($old_value,$new_value,"PROFILE_CHANGE_REQUEST","CITY","jsadmin","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|PROFILE_CHANGE_REQUEST|CITY|ID \n";
normal_update($old_value,$new_value,"PROFILE_CHANGE_REQUEST","NEW_CITY","jsadmin","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|PROFILE_CHANGE_REQUEST|NEW_CITY|ID \n";

normal_update($old_value,$new_value,"FROM_GOOGLE","CITY_RES","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|FROM_GOOGLE|CITY_RES|ID \n";
normal_update($old_value,$new_value,"INSURANCE_MAIL","CITY","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|INSURANCE_MAIL|CITY|PROFILEID \n";
comma_separated_type3_update($old_value,$new_value,"JPARTNER_OFFLINE","CITY_RES","newjs","PARTNERID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|JPARTNER_OFFLINE|CITY_RES|PARTNERID \n";
normal_update($old_value,$new_value,"PROMOTIONAL_MAIL","CITY","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|PROMOTIONAL_MAIL|CITY|ID \n";
normal_update($old_value,$new_value,"SCORE_MTON_CITY_MAP","CITY","newjs","SNO",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SCORE_MTON_CITY_MAP|CITY|SNO \n";
comma_separated_type1_update($old_value,$new_value,"SEARCH_FEMALE_REV","PARTNER_CITYRES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE_REV|PARTNER_CITYRES|PROFILEID \n";
comma_separated_type1_update($old_value,$new_value,"SEARCH_MALE_REV","PARTNER_CITYRES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE_REV|PARTNER_CITYRES|PROFILEID \n";
normal_update($old_value,$new_value,"STOCK_TRADING_MAIL","CITY","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|STOCK_TRADING_MAIL|CITY|PROFILEID \n";

normal_update($old_value,$new_value,"leads_cstm","city_c","sugarcrm","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm|leads_cstm|city_c|id_c \n";
normal_update($old_value,$new_value,"connected_leads_cstm","city_c","sugarcrm_housekeeping","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm_housekeeping|connected_leads_cstm|city_c|id_c \n";
normal_update($old_value,$new_value,"inactive_leads_cstm","city_c","sugarcrm_housekeeping","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm_housekeeping|inactive_leads_cstm|city_c|id_c \n";

//|caste#percentile|caste#percentile|caste#percentile|
pipe_separated_update($old_value,$new_value,"TRENDS","CITY_VALUE_PERCENTILE","twowaymatch","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|TRENDS|CITY_VALUE_PERCENTILE|PROFILEID \n";
pipe_separated_update($old_value,$new_value,"TRENDS_FOR_SPAM","CITY_VALUE_PERCENTILE","twowaymatch","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|TRENDS_FOR_SPAM|CITY_VALUE_PERCENTILE|PROFILEID \n";

//scoring_new|caste|caste
scoring_update($old_value,$new_value,"city","city","scoring_new",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "scoring_new|city|city \n";
scoring_update1($old_value,$new_value,"CITY_ZONE","City","scoring_new",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "scoring_new|CITY_ZONE \n";

//trends update
trends_update($old_value,$new_value,"CITY_FEMALE_PERCENT","PERCENT","twowaymatch","CITY",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|CITY_FEMALE_PERCENT|CITY \n";
trends_update($old_value,$new_value,"CITY_MALE_PERCENT","PERCENT","twowaymatch","CITY",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|CITY_MALE_PERCENT|CITY \n";

//community pages
community_pages_update1($old_value,$new_value,"COMMUNITY_PAGES","VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES|VALUE|ID \n";
community_pages_update2($old_value,$new_value,"COMMUNITY_PAGES_MAPPING","PARENT_VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES_MAPPING|PARENT_VALUE|ID \n";
community_pages_update3($old_value,$new_value,"COMMUNITY_PAGES_MAPPING","MAPPED_VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES_MAPPING|MAPPED_VALUE|ID \n";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
