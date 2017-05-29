<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];
require_once("$path/profile/connect.inc");
chdir("$path/sugarcrm");
require_once("$path/sugarcrm/include/entryPoint.php");
require_once("$path/sugarcrm/include/utils/Jsde_duplicate.php");

$db1=connect_db();
$curDate=date('Y-m-d',JSstrToTime("-1 days"));
$duplicate=new Duplicate(); 
$sql="select EMAIL,PHONE_RES, PHONE_MOB,INCOMPLETE,USERNAME,STD from newjs.JPROFILE where MOD_DT BETWEEN '$curDate 00:00:00' AND '$curDate 23:59:59'";
$res=mysql_query_decide($sql,$db1);
while($row=mysql_fetch_assoc($res))
{
	$leads=$duplicate->getDuplicateLeadId($row['EMAIL'],$row['PHONE_MOB'],$row['STD'],$row['PHONE_RES']);
//	print_r($leads);
    if($leads){	
		foreach($leads as $lead_id){
//			echo "$lead_id\n";
			if($row['INCOMPLETE']=='Y')
				$status='24';
			else
				$status='26';
			if($status=='26')
				$sql1="UPDATE sugarcrm.leads SET status='26',assigned_user_id='1' WHERE id='$lead_id'";
			else
				$sql1="UPDATE sugarcrm.leads SET status='24' WHERE id='$lead_id'";
			mysql_query_decide($sql1,$db1);
			$sql2="UPDATE sugarcrm.leads_cstm SET username_c='".$row['USERNAME']."', jsprofileid_c='".$row['USERNAME']."' WHERE id_c='$lead_id'";
			mysql_query_decide($sql2,$db2);
		}
	}
}
		
