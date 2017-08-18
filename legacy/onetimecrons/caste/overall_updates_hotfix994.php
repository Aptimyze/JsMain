<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE

include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once("update_functions_hotfix994.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$dbM) or die(mysql_error().$sql);

$filename = "overall_updates_hotfix994.csv";		//This filename to be used for overall updates of caste values for merges and deletes
$data = read_file($filename);
foreach ($data as $k=>$v)
{
 	$values = explode("|",trim($v));
   	if (trim($values[0]) && trim($values[1]))
    	{
             	$old_value[] = trim($values[1]);
            	$new_value[] = trim($values[0]);
      	}
}

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
        $shardDbM=$mysqlObjM->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbM);

        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
        $shardDbS=$mysqlObjS->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbS);

	plus_separated_update($old_value,$new_value,"JPARTNER","CASTE_MTONGUE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$shardDbM,$shardDbS);
	echo "SHARD".$activeServerId." newjs|JPARTNER|CASTE_MTONGUE|PROFILEID \n";
	
	comma_separated_type1_update($old_value,$new_value,"JPARTNER","PARTNER_CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$shardDbM,$shardDbS);
	echo "SHARD".$activeServerId." newjs|JPARTNER|PARTNER_CASTE|PROFILEID \n";

	mysql_close($shardDbS);
	mysql_close($shardDbM);
}


//NORMAL UPDATE CASE
normal_update($old_value,$new_value,"JPROFILE","CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|JPROFILE|CASTE|PROFILEID \n";
normal_update($old_value,$new_value,"SEARCH_MALE","CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE|CASTE|PROFILEID \n";
normal_update($old_value,$new_value,"SEARCH_FEMALE","CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE|CASTE|PROFILEID \n";
normal_update($old_value,$new_value,"JPROFILE_AFFILIATE","CASTE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|JPROFILE_AFFILIATE|CASTE|ID \n";
normal_update($old_value,$new_value,"AFFILIATE_DATA","CASTE","jsadmin","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|AFFILIATE_DATA|CASTE|PROFILEID \n";
normal_update($old_value,$new_value,"DUPLICATE_NUMBER_PROFILE","CASTE","jsadmin","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|DUPLICATE_NUMBER_PROFILE|CASTE|PROFILEID \n";
normal_update($old_value,$new_value,"MAILER","CASTE_ID","jsadmin","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "jsadmin|MAILER|CASTE_ID|ID \n";
normal_update($old_value,$new_value,"PROFILE_BRIEF","CASTE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|PROFILE_BRIEF|CASTE|ID \n";
//NORMAL UPDATE CASE ENDS

////'ABC','BCD','FGH'
comma_separated_type1_update($old_value,$new_value,"AP_DPP_FILTER_ARCHIVE","PARTNER_CASTE","Assisted_Product","DPP_ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_DPP_FILTER_ARCHIVE|PARTNER_CASTE|DPP_ID \n";
comma_separated_type1_update($old_value,$new_value,"AP_TEMP_DPP","PARTNER_CASTE","Assisted_Product","CREATED_BY,PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_TEMP_DPP|PARTNER_CASTE|CREATED_BY \n";
comma_separated_type1_update($old_value,$new_value,"SEARCH_FEMALE_REV","PARTNER_CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE_REV|PARTNER_CASTE|PROFILEID \n";
comma_separated_type1_update($old_value,$new_value,"SEARCH_MALE_REV","PARTNER_CASTE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE_REV|PARTNER_CASTE|PROFILEID \n";

//caste-mtongue,caste-mtongue,caste-mtongue
comma_separated_type2_update($old_value,$new_value,"SEARCH_FEMALE","CASTE_MTONGUE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE|CASTE_MTONGUE|PROFILEID \n";
comma_separated_type2_update($old_value,$new_value,"SEARCH_MALE","CASTE_MTONGUE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE|CASTE_MTONGUE|PROFILEID \n";

//ABC,BCD,LMN
comma_separated_type3_update($old_value,$new_value,"SEARCH_AGENT","CASTE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|CASTE|ID \n";
comma_separated_type3_update($old_value,$new_value,"SEARCH_AGENT","CASTE_DISPLAY","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|CASTE_DISPLAY|ID \n";
comma_separated_type3_update($old_value,$new_value,"CASTE_COMMUNITY_TOP_BAND","CASTE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|CASTE_COMMUNITY_TOP_BAND|CASTE|ID \n";
comma_separated_type3_update($old_value,$new_value,"CASTE_COMMUNITY_TOP_BAND","HINDU_CASTE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|CASTE_COMMUNITY_TOP_BAND|HINDU_CASTE|ID \n";


//caste-mtongue+caste-mtongue+caste-mtongue
plus_separated_update($old_value,$new_value,"CASTE_MAPPING","CASTE_MTONGUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|CASTE_MAPPING|CASTE_MTONGUE|ID \n";
plus_separated_update($old_value,$new_value,"SEARCH_AGENT","CASTE_MTONGUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|CASTE_MTONGUE|ID \n";


//religion_caste,religion_caste,religion_caste
comma_separated_type4_update($old_value,$new_value,"leads_cstm","caste_c","sugarcrm","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm|leads_cstm|caste_c|id_c \n";
comma_separated_type4_update($old_value,$new_value,"connected_leads_cstm","caste_c","sugarcrm_housekeeping","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm_housekeeping|connected_leads_cstm|caste_c|id_c \n";
comma_separated_type4_update($old_value,$new_value,"inactive_leads_cstm","caste_c","sugarcrm_housekeeping","id_c",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "sugarcrm_housekeeping|inactive_leads_cstm|caste_c|id_c \n";

//scoring_new|caste|caste
scoring_update($old_value,$new_value,"caste","caste","scoring_new",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "scoring_new|caste|caste \n";

//community pages
community_pages_update1($old_value,$new_value,"COMMUNITY_PAGES","VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES|VALUE|ID \n";
community_pages_update2($old_value,$new_value,"COMMUNITY_PAGES_MAPPING","PARENT_VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES_MAPPING|PARENT_VALUE|ID \n";
community_pages_update3($old_value,$new_value,"COMMUNITY_PAGES_MAPPING","MAPPED_VALUE","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|COMMUNITY_PAGES_MAPPING|MAPPED_VALUE|ID \n";

//|caste#percentile|caste#percentile|caste#percentile|
pipe_separated_update($old_value,$new_value,"TRENDS","CASTE_VALUE_PERCENTILE","twowaymatch","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|TRENDS|CASTE_VALUE_PERCENTILE|PROFILEID \n";
pipe_separated_update($old_value,$new_value,"TRENDS_FOR_SPAM","CASTE_VALUE_PERCENTILE","twowaymatch","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "twowaymatch|TRENDS_FOR_SPAM|CASTE_VALUE_PERCENTILE|PROFILEID \n";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>

