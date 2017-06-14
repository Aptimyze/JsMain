<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include_once($_SERVER[DOCUMENT_ROOT].'/profile/connect.inc');
include_once($_SERVER[DOCUMENT_ROOT].'/sugarcrm/include/utils/systemProcessUsersConfig.php');
global $process_user_mapping;

$processUserId=$process_user_mapping["lead_unallocate"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

$db=connect_db();
$then=date('Y-m-d');
$start="$then 00:00:00";
$end="$then 23:59:59";
$sql="UPDATE sugarcrm.leads set assigned_user_id='',modified_user_id='$processUserId',date_modified='$updateTime' where date_entered<='$end' and status IN ('13','24') and deleted=0";
$res=mysql_query_decide($sql) or send_email('jaiswal.amit@jeevansathi.com',"Problem in lead_unallocate cron","problem in lead_unallocate");
?>
