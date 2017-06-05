<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

define('sugarEntry',true);
$path=realpath(dirname(__FILE__)."/../..");
require_once("$docRoot/crontabs/connect.inc");
chdir("$path/sugarcrm");
require_once("$path/sugarcrm/include/entryPoint.php");
require_once("$path/sugarcrm/include/utils/Jsde_duplicate.php");
require_once("$path/sugarcrm/custom/crons/housekeepingConfig.php");
global $partitionsArray;
$db=connect_slave();
$db1=connect_db();
$duplicate=new Duplicate(); 
$sql="select EXECUTIVE,PROFILEID from newjs.OFFLINE_REGISTRATION where EXECUTIVE != '' AND SOURCE = 'onoffreg'";
$res1=mysql_query_decide($sql,$db);
while($row1=mysql_fetch_assoc($res1))
{
	$sql_profile="select EMAIL,PHONE_RES, PHONE_MOB,INCOMPLETE,USERNAME,SOURCE,PROFILEID,STD from newjs.JPROFILE where PROFILEID=".$row1[PROFILEID]." LIMIT 1";
	$res_profile=mysql_query_decide($sql_profile,$db);
	$row=mysql_fetch_assoc($res_profile);
	$leads=$duplicate->getDuplicateLeadId($row['EMAIL'],$row['PHONE_MOB'],$row['PHONE_RES'],$row['STD']);
	if(is_array($leads)){
		foreach($leads as $lead_id){
		//	echo "$lead_id\n";
			if($row['INCOMPLETE']=='Y')
				$status='24';
			else
				$status='26';
			$now = date("Y-m-d H:i:s");
			$assigned_user_id=get_sugar_exec($row1[EXECUTIVE],$db);
			if($assigned_user_id){
		echo		$sql1="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='$status',date_modified='$now',assigned_user_id='$assigned_user_id',username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."' where id='$lead_id' AND id=id_c";
		echo "\n";
			mysql_query_decide($sql1,$db1);
			$updateDone=mysql_affected_rows($db1);
			if(!$updateDone){
				foreach($partitionsArray as $partition=>$partitionArray)
				{
					$leadsTable="sugarcrm_housekeeping.".$partition."_leads";
					$leadsCstmTable="sugarcrm_housekeeping.".$partition."_leads_cstm";
			echo		$sql1="UPDATE $leadsTable,$leadsCstmTable SET status='$status',assigned_user_id='$assigned_user_id',date_modified='$now', username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."' WHERE id='$lead_id' AND deleted!='1' AND id=id_c";
		echo "\n";
					mysql_query_decide($sql1,$db1);
					if(mysql_affected_rows($db1))
						break;
				}
			}
			}
			}
		}
}
function get_sugar_exec($executive,$db)
{
	$sql_sugar_exec="select id from sugarcrm.users where user_name='$executive' LIMIT 1";
	$res_sugar_exec=mysql_query_decide($sql_sugar_exec,$db);
	$row_sugar_exec=mysql_fetch_assoc($res_sugar_exec);
	$assigned_user_id=$row_sugar_exec[id];
	return $assigned_user_id;
}
