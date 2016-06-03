<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 

//Sharding On Contacts done by Lavesh Rawat
$flag_using_php5=1;
include "connect.inc";

$backtime=mktime(0,0,0,date("m"),date("d")-1,date("Y")); // To get the time for previous days
$backdate=date("Y-m-d",$backtime);

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $mysqlObj=new Mysql;
        $myDbName=getActiveServerName($activeServerId);
        $myDbName=getActiveServerName($activeServerId,'slave');
        $myDb=$mysqlObj->connect("$myDbName");

	$sql="SELECT COUNT(*) AS COUNT,LOGIC_USED,IS_USER_ACTIVE,RECOMEND,TYPE FROM newjs.CONTACTS AS C JOIN MIS.MATCHALERT_CONTACT_BY_RECOMEND AS S on S.CONTACTID=C.CONTACTID  WHERE DATE(C.TIME)='$backdate' GROUP BY LOGIC_USED,RECOMEND,IS_USER_ACTIVE,TYPE";
	$result = $mysqlObj->executeQuery($sql,$myDb);
	while($row=mysql_fetch_array($result))
	{
		$logic_used=$row['LOGIC_USED'];
		$recomending=$row['RECOMEND'];
		$is_user_active=$row['IS_USER_ACTIVE'];
		$c_type=$row["TYPE"];
		$count=$row['COUNT'];
		
		if($logic_used && $recomending && $is_user_active && $c_type && $count)
		{
			if(!is_array($logic_used_all) || !in_array($logic_used,$logic_used_all) )
				$logic_used_all[]=$logic_used;

			if(!is_array($recomending_all) || !in_array($recomending,$recomending_all) )
				$recomending_all[]=$recomending;

			if(!is_array($is_user_active_all) || !in_array($is_user_active,$is_user_active_all) )
				$is_user_active_all[]=$is_user_active;

			if(!is_array($c_type_all) || !in_array($c_type,$c_type_all) )
				$c_type_all[]=$c_type;
				
			//$lavesh_arr[$logic_used][$recomending][$is_user_active][$c_type]=$count;
			$lavesh_arr[$logic_used][$recomending][$is_user_active][$c_type]+=$count;
		}
	}
}

$db2=connect_db();
for($l1=0;$l1<count($logic_used_all);$l1++)
{
	$currentLogic=$logic_used_all[$l1];
	for($l2=0;$l2<count($recomending_all);$l2++)
	{
		$currectRec=$recomending_all[$l2];

		for($l3=0;$l3<count($is_user_active_all);$l3++)
		{
			$currentIsUserActive=$is_user_active_all[$l3];	

			$icount=$lavesh_arr[$currentLogic][$currectRec][$currentIsUserActive]['I'];
			$acount=$lavesh_arr[$currentLogic][$currectRec][$currentIsUserActive]['A'];
			$dcount=$lavesh_arr[$currentLogic][$currectRec][$currentIsUserActive]['D'];
			$ccount=$lavesh_arr[$currentLogic][$currectRec][$currentIsUserActive]['C'];

        		$sql_view_matchalert="UPDATE MIS.MATCHALERT_TRACKING_V2 SET INITIALS='$icount',DECLINES='$dcount',ACCEPTANCES='$acount',CANCELS='$ccount' WHERE LOGIC_USED='$currentLogic' AND RECOMEND='$currectRec' AND  IS_USER_ACTIVE='$currentIsUserActive' AND ENTRY_DT='$backdate'";
        		mysql_query($sql_view_matchalert,$db2);
			if(mysql_affected_rows()==0)
			{
				$sql_view_matchalert="INSERT IGNORE into MIS.MATCHALERT_TRACKING_V2(LOGIC_USED,RECOMEND,ENTRY_DT,IS_USER_ACTIVE,INITIALS,ACCEPTANCES,DECLINES,CANCELS) VALUES($currentLogic,'$currectRec','$backdate','$currentIsUserActive','$icount','$acount','$dcount','$ccount')";
				mysql_query($sql_view_matchalert,$db2);
			}
		}
	}
	
}

?>

