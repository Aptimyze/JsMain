<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");

$mysqlObj = new Mysql;
$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$db2=$mysqlObj->connect("master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$logic_used=array("1","2","3","4");
$js_user_active=array("N","Y");
$recommend=array("H","R","N","D");

//-------------
$todaydt=mktime(0,0,0,date("m"),date("d"),date("Y"));
$zerodt=mktime(0,0,0,01,01,2005);
$dt2=($todaydt-$zerodt)/(24*60*60);
$dt2=round($dt2,0);
$dt1=$dt2-1;

if($dt1 && $dt2)
{
 	$sql="UPDATE matchalerts.ZERONTvNT SET DATE='$dt1' WHERE DATE='$dt2'";
	mysql_query($sql,$db) or die(mysql_error().$sql);

	$sql="UPDATE matchalerts.ZERONTvT SET DATE='$dt1' WHERE DATE='$dt2'";
	mysql_query($sql,$db) or die(mysql_error().$sql);

	$sql="UPDATE matchalerts.ZEROTvNT SET DATE='$dt1' WHERE DATE='$dt2'";
	mysql_query($sql,$db) or die(mysql_error().$sql);

	$sql="UPDATE matchalerts.ZEROTvT SET DATE='$dt1' WHERE DATE='$dt2'";
	mysql_query($sql,$db) or die(mysql_error().$sql);
}
//-------------

$sql="SELECT COUNT(*) FROM matchalerts.MAILER_TEMP";
$result=mysql_query($sql,$db) or die(mysql_error().$sql);
$myrow=mysql_fetch_row($result);
$n_count=$myrow[0];
if($n_count==0)
{
	$sql="INSERT INTO matchalerts.MAILER_TEMP SELECT * FROM matchalerts.MAILER";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
}
$sql="TRUNCATE TABLE matchalerts.TRACK_TEMP_PIDS";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);

$sql="TRUNCATE TABLE matchalerts.TRACK_TEMP_PIDS_GROUP";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);

$today=date("Y-m-d");
$ts = time();
$ts-=1*24*60*60;//change 30 to 3
$dayz_bck1=date("Y-m-d",$ts);

// ------------ logic wise distribution----------------------------------
$sql="SELECT SUM(IF(USER1,1,0)),SUM(IF(USER2,1,0)),SUM(IF(USER3,1,0)),SUM(IF(USER4,1,0)),SUM(IF(USER5,1,0)),SUM(IF(USER6,1,0)),SUM(IF(USER7,1,0)),SUM(IF(USER8,1,0)),SUM(IF(USER9,1,0)),SUM(IF(USER10,1,0)),LOGIC_USED FROM matchalerts.MAILER_TEMP GROUP BY LOGIC_USED";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);
while($row=mysql_fetch_array($res))
{
        $sql1="INSERT INTO MATCHALERT_TRACKING.MA_SENT (DATE,NO_OF_RES1,NO_OF_RES2,NO_OF_RES3,NO_OF_RES4,NO_OF_RES5,NO_OF_RES6,NO_OF_RES7,NO_OF_RES8,NO_OF_RES9,NO_OF_RES10,TOTAL_MATCHES_SENT,LOGIC) VALUES ('$dayz_bck1',$row[0]-$row[1],$row[1]-$row[2],$row[2]-$row[3],$row[3]-$row[4],$row[4]-$row[5],$row[5]-$row[6],$row[6]-$row[7],$row[7]-$row[8],$row[8]-$row[9],$row[9],$row[0],$row[10])";
        mysql_query($sql1,$db2) or die(mysql_error($db2).$sql1);
}
/*
$sql="SELECT SUM(IF(USER1,1,0)),SUM(IF(USER2,1,0)),SUM(IF(USER3,1,0)),SUM(IF(USER4,1,0)),SUM(IF(USER5,1,0)),SUM(IF(USER6,1,0)),SUM(IF(USER7,1,0)),SUM(IF(USER8,1,0)),SUM(IF(USER9,1,0)),SUM(IF(USER10,1,0)) FROM matchalerts.MAILER_TEMP";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);
$row=mysql_fetch_row($res);

$sql="INSERT INTO MATCHALERT_TRACKING.MA_SENT (DATE,NO_OF_RES1,NO_OF_RES2,NO_OF_RES3,NO_OF_RES4,NO_OF_RES5,NO_OF_RES6,NO_OF_RES7,NO_OF_RES8,NO_OF_RES9,NO_OF_RES10,TOTAL_MATCHES_SENT) VALUES ('$dayz_bck1',$row[0]-$row[1],$row[1]-$row[2],$row[2]-$row[3],$row[3]-$row[4],$row[4]-$row[5],$row[5]-$row[6],$row[6]-$row[7],$row[7]-$row[8],$row[8]-$row[9],$row[9],$row[0])";
$res=mysql_query($sql,$db2) or die(mysql_error($db2).$sql);
*/
// ------------ logic wise distribution----------------------------------


for($i=1;$i<10;$i++)
{
	$sql="INSERT INTO matchalerts.TRACK_TEMP_PIDS SELECT USER$i FROM matchalerts.MAILER_TEMP WHERE USER$i>0";
	$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);
}
$sql="INSERT INTO matchalerts.TRACK_TEMP_PIDS_GROUP(PID,CNT) SELECT PID,COUNT(*) FROM matchalerts.TRACK_TEMP_PIDS GROUP BY PID";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);

$today=date("Y-m-d");
$ts = time();
$ts-=4*24*60*60;//change 30 to 3
$dayz_bck3=date("Y-m-d",$ts);

$sql="UPDATE matchalerts.TRACK_TEMP_PIDS_GROUP A , newjs.JPROFILE B SET A.DATE_DIFF_IN_3_DAYZ='Y' WHERE A.PID=B.PROFILEID AND B.ENTRY_DT>'$dayz_bck3'";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);


$sql="SELECT PID,CNT,DATE_DIFF_IN_3_DAYZ  FROM matchalerts.TRACK_TEMP_PIDS_GROUP";
$res=mysql_query($sql,$db) or die(mysql_error($db).$sql);
while($row=mysql_fetch_array($res))
{
	$pid=$row["PID"];
	$cnt=$row["CNT"];
	$diff=$row["DATE_DIFF_IN_3_DAYZ"];

	$sql1="UPDATE MATCHALERT_TRACKING.MA_HISTORY SET ";
	unset($sql1Arr);
	if($diff=='Y')
		$sql1Arr[]=" FIRST_THREE_DAYZ=FIRST_THREE_DAYZ+$cnt ";
	$sql1Arr[]="TOTAL=TOTAL+$cnt";
	$sql2=implode(",",$sql1Arr);

	$sql3=$sql1.$sql2." WHERE PID=$pid ";
	mysql_query($sql3,$db2) or die(mysql_error($db2).$sql3);

	if(mysql_affected_rows($db2)==0)
	{
		$sql4="INSERT INTO MATCHALERT_TRACKING.MA_HISTORY SET $sql2,PID=$pid";
		mysql_query($sql4,$db2) or die(mysql_error($db2).$sql4);
	}
}
@mysql_ping($db);
$sql_1="TRUNCATE TABLE matchalerts.MAILER_TEMP";
mysql_query($sql_1,$db) or die(mysql_error($db));

$tableArr[1]='ZERONTvNT';
$tableArr[2]='ZERONTvT';
$tableArr[3]='ZEROTvNT';
$tableArr[4]='ZEROTvT';
for($i=1;$i<5;$i++)
{
        $logic=$i;
        $table=$tableArr[$i];

        $sql_max="SELECT COUNT(*) FROM matchalerts.$table WHERE DATE=(SELECT MAX(DATE) FROM matchalerts.$table)";
        $res_max=mysql_query($sql_max,$db) or die(mysql_error($db).$sql);
        $row_max=mysql_fetch_row($res_max);

        $sql_max2="SELECT MAX(ID) FROM MATCHALERT_TRACKING.MA_SENT WHERE LOGIC=$logic";
        $res_max2=mysql_query($sql_max2,$db2) or die(mysql_error($db2).$sql_max2);
        $row_max2=mysql_fetch_row($res_max2);
        $id=$row_max2[0];

        $sql_1="UPDATE MATCHALERT_TRACKING.MA_SENT SET NO_OF_RES0=$row_max[0] where ID=$id";
        mysql_query($sql_1,$db2) or die(mysql_error($db2).$sql_1);
}
die;

foreach($logic_used as $logicval)
{
	foreach($js_user_active as $activeval)
	{
		foreach($recommend as $recommendval)
		{
			for($i=1;$i<=10;$i++)
			{

				$sql="SELECT COUNT(*) AS CNT FROM matchalerts.MAILER_TEMP WHERE LOGIC_USED='$logicval' AND RECOMEND_USER".$i."='$recommendval' AND IS_USER_ACTIVE='$activeval' AND USER".$i."<>0";
				$res=mysql_query($sql,$db) or die(mysql_error($db));
				if(mysql_num_rows($res))
				{
					$row=mysql_fetch_assoc($res);		
					$results[$logicval][$activeval][$recommendval]+=$row["CNT"];
				}
			}
		}
	}
}
$daystamp=mktime(0, 0, 0, date("m"), date("d")-1,date("Y"));
$day=date("Y-m-d",$daystamp);
$sql="SELECT COUNT(*) AS CNT FROM MIS.MATCHALERT_TRACKING_V2 WHERE LOGIC_USED='1' AND IS_USER_ACTIVE='Y' AND RECOMEND='H' AND ENTRY_DT='$day' AND MATCHALERTS_SENT>0";
$res=mysql_query($sql,$db) or die(mysql_error($db));
$row=mysql_fetch_assoc($res);
if($row["CNT"]!=0)
{
	$msg="Duplicate entry for LOGIC_USED, IS_USER_ACTIVE and RECOMEND combination in MATCHALERT_TRACKING_V2 on ".$day;
	$subject="Duplicate entry in MATCHALERT_TRACKING_V2";
	mail('lavesh.rawat@jeevansathi.com','',"$msg","$subject","matchalert@jeevansathi.com");
	exit;
}
foreach($results as $key1=>$val1)
{
	foreach($results[$key1] as $key2=>$val2)
	{
		foreach($results[$key1][$key2] as $key3=>$val3)
		{
			if($val3!=0)
			{
				$sql="UPDATE MIS.MATCHALERT_TRACKING_V2 SET MATCHALERTS_SENT='$val3' WHERE LOGIC_USED='$key1' AND IS_USER_ACTIVE='$key2' AND RECOMEND='$key3' AND ENTRY_DT='$day'";
				//mysql_query($sql,$db2) or die(mysql_error($db2));
				mysql_query($sql,$db2) or mail('lavesh.rawat@jeevansathi.com','',"$sql","matchalert_count.php");
				if(mysql_affected_rows()==0)
				{
					$sql="INSERT INTO MIS.MATCHALERT_TRACKING_V2(ENTRY_DT,LOGIC_USED,IS_USER_ACTIVE,RECOMEND,MATCHALERTS_SENT) VALUES('$day','$key1','$key2','$key3','$val3')";
					mysql_query($sql,$db2) or mail('lavesh.rawat@jeevansathi.com','',"$sql","matchalert_count.php");
					//mysql_query($sql,$db2) or die(mysql_error($db2));
				}
			}
		}
	}
}
@mysql_ping($db);
$sql_1="TRUNCATE TABLE matchalerts.MAILER_TEMP";
mysql_query($sql_1,$db) or die(mysql_error($db));

?>
