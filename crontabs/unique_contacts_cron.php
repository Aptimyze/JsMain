<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**************************************************************************************************************************
FILE		: unique_contacts_cron.php
DESCRIPTION	: This file finds and stores the count of unique contacts initiated and count of unique contacts received.
CREATTED BY	: Sriram Viswanathan.
DATE		: 10th April 2007.
**************************************************************************************************************************/

$flag_using_php5=1;
include("connect.inc");

global $mysqlObj;
$mysqlObj=new Mysql;

$db = connect_slave();

//defining the start and end dates.
$time_stamp=time();

$time_stamp -= 2*24*60*60;
$prev_month = date("Y-m",$time_stamp);

$start_date = $prev_month."-01 00:00:00";
$end_date = $prev_month."-31 23:59:59";

//$start_date="2007-03-01 00:00:00";
//$end_date="2007-03-31 23:59:59";

//query to find count of unique user's who initiated the contact.

$sql="TRUNCATE sharding.UNIQUE_CONTACTS_CRON";
mysql_query($sql,$db) or logError($sql);

//Sharding of CONTACTS done by Sadaf
for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$slave_activeServers[$serverId];
	$myDb=$mysqlObj->connect("$myDbName");
	$sql="SELECT DISTINCT SENDER FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID=SENDER AND SERVERID='$serverId' AND TIME BETWEEN '$start_date' AND '$end_date' AND TYPE = 'I'";
	$mysqlRes=$mysqlObj->executeQuery($sql,$myDb);
	if($mysqlObj->numRows($mysqlRes))
	{
		if($mysqlObj->numRows($mysqlRes)>10000)
		{
			$mysqlNumRows=$mysqlObj->numRows($mysqlRes);
			$totalRows=$mysqlNumRows;
			$index=0;
			while($mysqlNumRows>10000)
			{
				unset($str);
				for($i=1;$i<=10000;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
					$str.="('".$myrow["SENDER"]."'),";
				}
				$str=rtrim($str,",");
				$sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
				mysql_query($sql,$db) or logError($sql);
				$mysqlNumRows-=10000;
				$index++;
			}
			if($mysqlNumRows>0)
			{
				unset($str);
				for($i=$index*10000+1;$i<=$totalRows;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
						$str.="('".$myrow["SENDER"]."'),";
				}
				$str=rtrim($str,",");
				$sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
					mysql_query($sql,$db) or logError($sql);
			}
		}
		else
		{
			unset($str);
			while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
			{
				$str.="('".$myrow["SENDER"]."'),";
			}
			$str=rtrim($str,",");
			$sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
			mysql_query($sql,$db) or logError($sql);
		}
	}
	unset($myDb);
	unset($myDbName);
}

$sql="SELECT COUNT(DISTINCT A.PROFILEID) AS COUNT,GENDER FROM sharding.UNIQUE_CONTACTS_CRON AS A LEFT JOIN newjs.JPROFILE AS B ON A.PROFILEID=B.PROFILEID GROUP BY GENDER";
$res=mysql_query($sql,$db) or logError($sql);
while($row = mysql_fetch_array($res))
{
	$gender = $row['GENDER'];
	if($gender == 'M')
		$m_unique_initiated = $row['COUNT'];
	elseif($gender == 'F')
		$f_unique_initiated=$row['COUNT'];
}

$sql="TRUNCATE sharding.UNIQUE_CONTACTS_CRON";
mysql_query($sql,$db) or logError($sql);

for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
        $myDbName=$slave_activeServers[$serverId];
        $myDb=$mysqlObj->connect("$myDbName");
        $sql="SELECT DISTINCT RECEIVER FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID=RECEIVER AND SERVERID='$serverId' AND TIME BETWEEN '$start_date' AND '$end_date' AND TYPE = 'I'";
        $mysqlRes=$mysqlObj->executeQuery($sql,$myDb);
        if($mysqlObj->numRows($mysqlRes))
        {
                if($mysqlObj->numRows($mysqlRes)>10000)
                {
                        $mysqlNumRows=$mysqlObj->numRows($mysqlRes);
                        $totalRows=$mysqlNumRows;
                        $index=0;
                        while($mysqlNumRows>10000)
                        {
                                unset($str);
                                for($i=1;$i<=10000;$i++)
                                {
                                        $myrow=$mysqlObj->fetchAssoc($mysqlRes);
                                        $str.="('".$myrow["RECEIVER"]."'),";
                                }
                                $str=rtrim($str,",");
                                $sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
                                mysql_query($sql,$db) or logError($sql);
                                $mysqlNumRows-=10000;
                                $index++;
                        }
			if($mysqlNumRows>0)
			{
				unset($str);
				for($i=$index*10000+1;$i<=$totalRows;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
						$str.="('".$myrow["RECEIVER"]."'),";
				}
				$str=rtrim($str,",");
				$sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
				mysql_query($sql,$db) or logError($sql);
			}
                }
                else
                {
                        unset($str);
                        while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
                        {
                                $str.="('".$myrow["RECEIVER"]."'),";
                        }
                        $str=rtrim($str,",");
                        $sql="INSERT INTO sharding.UNIQUE_CONTACTS_CRON(PROFILEID) VALUES $str";
                        mysql_query($sql,$db) or logError($sql);
                }
        }
        unset($myDb);
        unset($myDbName);
}

$sql="SELECT COUNT(DISTINCT A.PROFILEID) AS COUNT,GENDER FROM sharding.UNIQUE_CONTACTS_CRON AS A LEFT JOIN newjs.JPROFILE AS B ON A.PROFILEID=B.PROFILEID GROUP BY GENDER";
$res=mysql_query($sql,$db) or logError($sql);
while($row = mysql_fetch_array($res))
{
        $gender = $row['GENDER'];
        if($gender == 'M')
                $m_unique_received = $row['COUNT'];
        elseif($gender == 'F')
                $f_unique_received = $row['COUNT'];
}

$sql="TRUNCATE sharding.UNIQUE_CONTACTS_CRON";
mysql_query($sql,$db) or logError($sql);

mysql_close($db);

$db = connect_db();
//storing the count's
$sql_ins = "INSERT INTO MIS.UNIQUE_INITIATED_RECEIVED(GENDER,INITIATED,RECEIVED,ENTRY_DT) VALUES('M','$m_unique_initiated','$m_unique_received','$prev_month'),('F','$f_unique_initiated','$f_unique_received','$prev_month')";
mysql_query($sql_ins,$db) or logError($sql);
mysql_close($db);
?>
