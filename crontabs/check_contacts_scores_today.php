<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/thumb_identification_array.inc");

global $mysqlObj;
$mysqlObj=new Mysql;

$db=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$db2=connect_db();

$ts=time();
$today=date("Y-m-d",$ts);
list($year1,$month1,$day1)=explode('-',$today);
$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";

for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$slave_activeServers[$serverId];
	$myDb=$mysqlObj->connect($myDbName);
	$sql="SELECT SENDER,RECEIVER,TYPE from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TIME between '$date1' and '$date2' and PROFILEID=SENDER and SERVERID='$serverId'";
        $res=$mysqlObj->executeQuery($sql,$myDb);
	while($row=mysql_fetch_assoc($res))
	{
		$profileid1=$row["SENDER"];
		$profileid2=$row["RECEIVER"];
		$sqlDetails="SELECT PROFILEID,GENDER,USERNAME,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME FROM newjs.JPROFILE WHERE PROFILEID IN('$profileid1','$profileid2')";
                $resDetails=mysql_query_decide($sqlDetails) or logError($sqlDetails,$db);
		while($rowDetails=mysql_fetch_assoc($resDetails))
		{
			if($rowDetails["PROFILEID"]==$profileid1)
			{
				$trend1[]=calculate_user_trend($rowDetails);
				$gender1=$rowDetails["GENDER"];
			}
			if($rowDetails["PROFILEID"]==$profileid2)
			{
				$trend2[]=calculate_user_trend($rowDetails);
				$gender2=$rowDetails["GENDER"];
			}
	
		}
		$data["PROFILEID"]=$profileid1;
                $data["GENDER"]=$gender1;
		$forwardScore=getting_reverse_trend($trend2,0);
		$data["PROFILEID"]=$profileid2;
		$data["GENDER"]=$gender2;
		$reverseScore=getting_reverse_trend($trend1,0);
		$sqlInsert="INSERT INTO twowaymatch.DAILY_CONTACT_TRENDS(SENDER,RECEIVER,DATE,TYPE,FORWARD_SCORE,REVERSE_SCORE) VALUES('$profileid1','$profileid2','$today','$row[TYPE]','$forwardScore','$reverseScore')";
		mysql_query_decide($sqlInsert,$db2) or logError($sqlInsert,$db2);
		unset($trend1);
		unset($trend2);
	}
}
?>
