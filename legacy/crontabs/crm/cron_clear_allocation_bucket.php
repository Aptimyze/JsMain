<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
include "../connect.inc";

$db=connect_db();
$db_slave=connect_slave();
$limit 				=265;
//$disposition_order_arr 	=array(1=>"D",2=>"DNC",3=>"CF",4=>"NI",5=>"CNC",6=>"SEQ",7=>"L",8=>"A",9=>"AA",10=>"SPR",11=>"SC");
$disposition_order_arr 		=array(1=>"CNC",2=>"SEQ",3=>"L",4=>"A",5=>"AA",6=>"SPR",7=>"SC");
$disposition_del_arr 		=array(0=>"D",1=>"DNC",2=>"CF",3=>"NI");
$tot_disp 			=count($disposition_order_arr);

//Logic:1 Start
// Start- Deletion of profiles based on specific disposition 
$i=0;
$disposition_del_str ="'".@implode("','",$disposition_del_arr)."'";
$sqlD = "SELECT PROFILEID,ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE STATUS NOT IN ('P','S') AND WILL_PAY IN($disposition_del_str)";
$resD = mysql_query($sqlD,$db_slave) or  $msg .= "\n$sql \nError :".mysql_error();
while($rowD = mysql_fetch_array($resD))
{	
                $profileArr[$i]['PROFILEID'] = $rowD['PROFILEID'];
		$profileArr[$i]['ALLOTED_TO'] = $rowD['ALLOTED_TO'];
		$i++;
}
deleteMyDisposedProfiles($profileArr);

// Logic:2 Start
$sql = "SELECT ALLOTED_TO,count(*) as cnt FROM incentive.MAIN_ADMIN WHERE STATUS NOT IN ('P','S') GROUP BY ALLOTED_TO HAVING cnt>$limit";
$res = mysql_query($sql,$db_slave) or  $msg .= "\n$sql \nError :".mysql_error();
while($row = mysql_fetch_array($res))
{
		$executives[] = $row['ALLOTED_TO'].":".$row['cnt'];
}

$sqlt = "TRUNCATE TABLE incentive.TEMP_ALLOCATION_BUCKET";
mysql_query($sqlt,$db) or $msg .= "\n$sqlt \nError :".mysql_error();
$pro_arr =array();

for($i=0;$i<count($executives);$i++)
{
	$exe_arr = explode(":",$executives[$i]);
	$exe = $exe_arr[0];

	$sql_id1 = "SELECT ma.PROFILEID,ma.WILL_PAY,h.ENTRY_DT,ma.ALLOTED_TO FROM incentive.MAIN_ADMIN as ma JOIN incentive.HISTORY as h ON ma.PROFILEID=h.PROFILEID WHERE ma.ALLOTED_TO='$exe' AND ma.STATUS!='P' AND h.ENTRYBY='$exe' ORDER BY h.ENTRY_DT DESC";
	$result_id1 = mysql_query($sql_id1,$db_slave) or  $msg .= "\n$sql_id1 \nError :".mysql_error();
	while($myrow_id1 = mysql_fetch_array($result_id1))
	{
		if(!in_array($myrow_id1['PROFILEID'],$pro_arr))
		{
			$sqli = "INSERT INTO incentive.TEMP_ALLOCATION_BUCKET (PROFILEID,DISP,LAST_DISP_DT,EXEC) VALUES ($myrow_id1[PROFILEID],'$myrow_id1[WILL_PAY]','$myrow_id1[ENTRY_DT]','$myrow_id1[ALLOTED_TO]')"; 
			mysql_query($sqli,$db) or $msg .= "\n$sqli \nError :".mysql_error();
			$pro_arr[]=$myrow_id1['PROFILEID'];
		}
	}
}

for($i=0;$i<count($executives);$i++)
{
        $exe_arr = explode(":",$executives[$i]);
        $exe = $exe_arr[0];
        $cnt = $exe_arr[1];
        $exceed = $cnt-$limit;
        for($d=1; $d<=$tot_disp; $d++)
        {
                $disposition =$disposition_order_arr[$d];
                $sql_id3 = "SELECT PROFILEID FROM incentive.TEMP_ALLOCATION_BUCKET WHERE EXEC='$exe' AND DISP='$disposition' ORDER BY LAST_DISP_DT LIMIT $exceed";
                $result_id3 = mysql_query($sql_id3,$db) or  $msg .= "\n$sql \nError :".mysql_error();
                while($myrow_id3 = mysql_fetch_array($result_id3))
                {	
                        delete_profile_form_bucket($myrow_id3['PROFILEID'],$db);
                        $exceed--;
                }
		if($exceed<1)
			break;
        }
}

if($msg=='')
	$msg="Done";
mail("vibhor.garg@jeevansathi.com,manoj.rana@naukri.com","Allocation Bucket Cleared", $msg);

function delete_profile_form_bucket($profileid,$db)
{
	$sql1 = "REPLACE INTO incentive.MAIN_ADMIN_LOG SELECT * FROM incentive.MAIN_ADMIN WHERE PROFILEID IN($profileid)";
        if(mysql_query($sql1,$db))
        {
		$sql2 = "DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID IN($profileid)";
		mysql_query($sql2,$db) or $msg .= "\n$sql2 \nError :".mysql_error();

		$sql3="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='Y' WHERE PROFILEID IN($profileid)";
	        mysql_query($sql3,$db) or $msg .= "\n$sql3 \nError :".mysql_error();
	}
	else
		$msg .= "\n$sql1 \nError :".mysql_error();
}
function deleteMyDisposedProfiles($profileArr)
{
	global $db;
	for($i=0;$i<count($profileArr);$i++)
	{
		$sqlHis="SELECT ENTRYBY FROM incentive.HISTORY WHERE PROFILEID=".$profileArr[$i]['PROFILEID']." ORDER BY ENTRY_DT DESC LIMIT 1";
		$resHis=mysql_query($sqlHis,$db);
		$rowHis=mysql_fetch_assoc($resHis);
		if($rowHis['ENTRYBY']==$profileArr[$i]['ALLOTED_TO'])
			delete_profile_form_bucket($profileArr[$i]['PROFILEID'],$db);
	}
}	
?>
