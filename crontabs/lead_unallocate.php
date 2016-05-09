<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/** This cron will make assigned_user_id column of sugarcrm.leads table blank of those leads that 
 * are 7 days old.
 * */
chdir(dirname(__FILE__));
include_once("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");
$db=connect_db();
$then=date('Y-m-d',strtotime("-7 days"));
$start="$then 00:00:00";
$end="$then 23:59:59";
$sql="UPDATE sugarcrm.leads set assigned_user_id='' where date_entered>='$start' and date_entered<='$end' and status IN ('13','24','1','16') and deleted=0";
$res=mysql_query_decide($sql) or send_email('jaiswal.amit@jeevansathi.com',"Problem in lead_unallocate cron","problem in lead_unallocate");
?>
