<?php
ini_set(memory_limit,-1);
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];//realpath(dirname(__FILE__)."/..");
require_once("connect.inc");
chdir("$path/sugarcrm");
require_once("$path/sugarcrm/include/entryPoint.php");
require_once("$path/sugarcrm/include/utils/Jsde_duplicate.php");
require_once("$path/sugarcrm/include/utils/systemProcessUsersConfig.php");
require_once("$path/sugarcrm/custom/crons/housekeepingConfig.php");
global $partitionsArray;
global $process_user_mapping;

$processUserId=$process_user_mapping["lead_status_update"];
if(!$processUserId)
	$processUserId=1;

$db=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$db1=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db1);
$prevDate=date('Y-m-d',strtotime("-1 days"));
$curDate=date('Y-m-d');
$duplicate=new Duplicate($db); 
$sql="select EMAIL,PHONE_RES, PHONE_MOB,INCOMPLETE,USERNAME,SOURCE,PROFILEID,STD,SERIOUSNESS_COUNT from newjs.JPROFILE where MOD_DT BETWEEN '$prevDate 00:00:00' AND '$curDate 23:59:59'";
$res=mysql_query_decide($sql,$db) or die(mysql_error($db));
while($row=mysql_fetch_assoc($res))
{
	$leads=$duplicate->getDuplicateLeadId($row['EMAIL'],$row['PHONE_MOB'],$row['STD'],$row['PHONE_RES'], false);
    if($leads){	
		foreach($leads as $lead_id){
			echo "$lead_id\n";
			//$disposition added by Sadaf to set disposition of the lead
			if($row['INCOMPLETE']=='Y')
			{
				$status='24';
				$disposition='23';
			}
			else
			{
				$status='26';
				$disposition='30';
			}
			$now = date("Y-m-d H:i:s");
			if($row[SOURCE]=="onoffreg"){
				$assigned_user_id=get_sugar_exec($row[PROFILEID],$db);
				$sql1="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='$status',date_modified='$now',assigned_user_id='$assigned_user_id',username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."',disposition_c='$disposition',modified_user_id='".$processUserId."',seriousness_count_c='".$row["SERIOUSNESS_COUNT"]."' where id='$lead_id' AND id=id_c";
			}
			else
				$sql1="UPDATE sugarcrm.leads,sugarcrm.leads_cstm  SET status='$status',date_modified='$now',username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."',disposition_c='$disposition',modified_user_id='".$processUserId."',seriousness_count_c='".$row["SERIOUSNESS_COUNT"]."' WHERE id='$lead_id' and id=id_c";
			mysql_query_decide($sql1,$db1) or die(mysql_error($db1));
			$updateDone=mysql_affected_rows($db1);
			if(!$updateDone){
				foreach($partitionsArray as $partition=>$partitionArray)
				{
					$leadsTable="sugarcrm_housekeeping.".$partition."_leads";
					$leadsCstmTable="sugarcrm_housekeeping.".$partition."_leads_cstm";
					if($assigned_user_id)
						$sql1="UPDATE $leadsTable,$leadsCstmTable SET status='$status',assigned_user_id='$assigned_user_id',date_modified='$now', username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."',disposition_c='$disposition',modified_user_id='".$processUserId."',seriousness_count_c='".$row["SERIOUSNESS_COUNT"]."' WHERE id='$lead_id' AND deleted!='1' AND id=id_c";
				    else
						$sql1="UPDATE $leadsTable,$leadsCstmTable SET status='$status',username_c='".$row['USERNAME']."',date_modified='$now', jsprofileid_c='".$row['USERNAME']."',disposition_c='$disposition',modified_user_id='".$processUserId."',seriousness_count_c='".$row["SERIOUSNESS_COUNT"]."' WHERE id='$lead_id' AND deleted!='1' AND id=id_c";
					mysql_query_decide($sql1,$db1) or die(mysql_error($db1));
					if(mysql_affected_rows($db1))
						break;
				}
			}
		}
		unset($row);
		unset($assigned_user_id);
	}
}
function get_sugar_exec($profileid,$db)
{
	$sql_exec="SELECT EXECUTIVE from newjs.OFFLINE_REGISTRATION where PROFILEID=".$profileid." LIMIT 1";
	$res_exec=mysql_query_decide($sql_exec,$db) or die(mysql_error($db));
	$row_exec=mysql_fetch_assoc($res_exec);
	$executive=$row_exec[EXECUTIVE];
	$sql_sugar_exec="select id from sugarcrm.users where user_name='$executive' LIMIT 1";
	$res_sugar_exec=mysql_query_decide($sql_sugar_exec,$db) or die(mysql_error($db));
	$row_sugar_exec=mysql_fetch_assoc($res_sugar_exec);
	$assigned_user_id=$row_sugar_exec[id];
	return $assigned_user_id;
}
