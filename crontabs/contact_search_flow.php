<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
//Threshold Limits for weekdays and weekends for placing an alert to the stakeholders about any deviations

$MIN_ACCEPTANCE_THRESHOLD_WEEKDAYS="13000";
$MIN_ACCEPTANCE_THRESHOLD_WEEKEND="14500";

$MIN_INITIATE_THRESHOLD_WEEKDAYS="150000";
$MIN_INITIATE_THRESHOLD_WEEKEND="170000";

//Sharding On Contacts done by Lavesh Rawat
$flag_using_php5=1;
include "connect.inc";

$db=connect_db();
$ddl=connect_ddl();
mysql_query("set session wait_timeout=1000",$db);
mysql_query("set session wait_timeout=1000",$ddl);
$backtime=mktime(0,0,0,date("m"),date("d")-1,date("Y")); // To get the time for previous days
$backdate=date("Y-m-d",$backtime);

//weekday weekend calculation for threshold alerts
$yesterdayTimestamp=time()-1*24*60*60;
$dayoftheweek = date( "w", $yesterdayTimestamp);
//echo $dayoftheweek1 = date( "Y-m-d", $yesterdayTimestamp);die;

$sql="TRUNCATE TABLE MIS.TRACK_CONTACTSEARCH_FLOW_TEMP";
mysql_query($sql,$ddl) or die("$sql".mysql_error($ddl));

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $mysqlObj=new Mysql;
        $myDbName=getActiveServerName($activeServerId);
        $myDbName=getActiveServerName($activeServerId,'master');
        $myDb=$mysqlObj->connect("$myDbName");

        $sql="SELECT COUNT(*) AS COUNT ,S.SEARCH_TYPE AS SOURCE ,DATE(C.TIME) DATE,C.TYPE AS TYPE   FROM newjs.CONTACTS AS C left join MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW AS S on S.CONTACTID=C.CONTACTID   
 			WHERE SENDER%3 =$activeServerId AND DATE(C.TIME) = '$backdate' GROUP BY S.SEARCH_TYPE ,DATE(C.TIME),C.TYPE";
        $result = $mysqlObj->executeQuery($sql,$myDb);
        while($row=$mysqlObj->fetchArray($result))
        {
                @mysql_ping($db);
                $count=$row['COUNT'];
                $source=$row['SOURCE'];
                $date=$row['DATE'];
                $type=$row['TYPE'];

                $sql_insert="INSERT INTO MIS.TRACK_CONTACTSEARCH_FLOW_TEMP(COUNT,SOURCE,DATE,TYPE) VALUES($count,'$source','$date','$type')";
                mysql_query($sql_insert,$db) or die("$sql_insert".mysql_error($db));
        }
}

@mysql_ping($db);        
//deleting data from table to avoid duplicate enteries in case of rerun of cron.
$sql_delete="DELETE FROM MIS.TRACK_CONTACTSEARCH_FLOW where DATE='$backdate'";
mysql_query($sql_delete,$db) or die("$sql_delete".mysql_error($db));
        
$sql="SELECT SUM(COUNT) AS COUNT, SOURCE , DATE , TYPE FROM MIS.TRACK_CONTACTSEARCH_FLOW_TEMP GROUP BY SOURCE , DATE , TYPE";
$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));
while($row=mysql_fetch_array($result))
{
        $count=$row['COUNT'];
        $source=$row['SOURCE'];
        $date=$row['DATE'];
        $type=$row['TYPE'];
        $sql_insert="INSERT INTO MIS.TRACK_CONTACTSEARCH_FLOW(ID,COUNT,SOURCE,DATE,TYPE) VALUES('',$count,'$source','$date','$type')";
        mysql_query($sql_insert,$db) or die("$sql_insert".mysql_error($db));
}

$sql="TRUNCATE TABLE MIS.TRACK_CONTACTSEARCH_FLOW_TEMP";
mysql_query($sql,$ddl) or die("$sql".mysql_error($ddl));

$sqlThreshhold="SELECT SUM( COUNT ) AS CNT ,TYPE FROM  MIS.TRACK_CONTACTSEARCH_FLOW WHERE DATE ='$backdate' group by TYPE";
$resultThreshhold=mysql_query($sqlThreshhold,$db) or die("$sqlThreshhold".mysql_error($db));
while($row=mysql_fetch_array($resultThreshhold))
{
        $count=$row['CNT'];
        $type=$row['TYPE'];
        $alertMsg="";

        if($type=="A")
		{
			if($dayoftheweek!=0 && $dayoftheweek!=6)
			{
				if($count<$MIN_ACCEPTANCE_THRESHOLD_WEEKDAYS)
					$alertMsg="Accept count = ".$count;
			}					
			else
			{
				if($count<$MIN_ACCEPTANCE_THRESHOLD_WEEKEND)
					$alertMsg="Accept count = ".$count;
			}
		}
		if($type=="I")
		{
			if($dayoftheweek!=0 && $dayoftheweek!=6)
			{
				if($count<$MIN_INITIATE_THRESHOLD_WEEKDAYS)
					$alertMsg="EOI count = ".$count;				
			}					
			else
			{
				if($count<$MIN_INITIATE_THRESHOLD_WEEKEND)
					$alertMsg="EOI count = ".$count;
			}
		}
		if($finalMessage)
		{
			if($alertMsg)
				$finalMessage.=" and ".$alertMsg;
		}
		else
			$finalMessage=$alertMsg;
}

if($finalMessage!="")
{
	if($dayoftheweek!=0 && $dayoftheweek!=6)
		$sub=$finalMessage." weekday Limit Crossed";
	else
		$sub=$finalMessage." weekend Limit Crossed";

	send_email("nitesh.s@jeevansathi.com,nitesh.s@jeevansathi.com,jsprod@jeevansathi.com",$sub,$sub,"noreply@jeevansathi.com","","","","","");
}

?>
