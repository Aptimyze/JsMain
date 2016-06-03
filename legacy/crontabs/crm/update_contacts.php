<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
include_once("../connect.inc");
include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
$db=connect_db();
$db1=connect_slave();

$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN";
$res=mysql_query($sql,$db1) or logError($sql,$db1);
if($row=mysql_fetch_array($res))
{
	do
	{
		unset($acc_count);
		unset($rcv_count);

		$profileid=$row['PROFILEID'];
		$contactResult_SA=getResultSet("count(*) as count",$profileid,"","","","'A'","","","","","","","",1);
		$acc_count=$contactResult_SA[0]['count'];
		if($acc_count>0)
		{
			$contactResult_SI=getResultSet("count(*) as count,TYPE","","",$profileid,"","'I','A'","","","TYPE","","","","",1);
			$count=$contactResult_SI[0]['count'];
			if($count>0)
			{
				$i=0;
				do
				{
					$type=$contactResult_SI[$i]['TYPE'];
					if($type=='A')
						$acc_count+=$contactResult_SI[$i]['count'];
					elseif($type=='I')
						$rcv_count=$contactResult_SI[$i]['count'];
					$i++;
				}while($i!=2);
			}
			$sql_u="UPDATE incentive.MAIN_ADMIN SET CONTACTS_ACC='$acc_count',CONTACTS_RCV='$rcv_count' WHERE PROFILEID='$profileid'";
			mysql_query($sql_u,$db) or logError($sql_u,$db);
		}
	}while($row=mysql_fetch_array($res));
}
?>
