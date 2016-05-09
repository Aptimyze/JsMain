<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

include("connect.inc");
$path=$_SERVER['DOCUMENT_ROOT'];
include($path."/classes/globalVariables.Class.php");
include($path."/classes/Mysql.class.php");



$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
//$today="2008-06-15";
list($year1,$month1,$day1)=explode('-',$today);

$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";

//Sharding on CONTACTS done by Neha
global $noOfActiveServers,$slave_activeServers;

//print_r($slave_activeServers);


$mysqlObj=new Mysql;
for($i=0;$i<$noOfActiveServers;$i++)
{
	$sql="SELECT SENDER,RECEIVER FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where SENDER=PROFILEID AND SERVERID='$i' AND TIME between '$date1' and '$date2'";
	$myDbName=$slave_activeServers[$i];
        $myDb=$mysqlObj->connect("$myDbName");
        $result=$mysqlObj->executeQuery($sql,$myDb) or die("1 ".mysql_error1($db2));
	while($myrow=$mysqlObj->fetchArray($result))
	{
		$sender[]=$myrow['SENDER'];
		$receiver[]=$myrow['RECEIVER'];
	}

}
$j=0;
$len=count($sender);


//End

$db2=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);


while($len>$j)
{
	$profileids="'".$sender[$j]."','".$receiver[$j]."'";
	$j++;
	$sql2="SELECT SUBSCRIPTION,MTONGUE,COUNTRY_RES,PROFILEID from newjs.JPROFILE where PROFILEID IN ($profileids) ";
	$result2=mysql_query($sql2,$db2) or die("2 ".mysql_error1($db2));
	while($myrow2=mysql_fetch_array($result2))
	{
		$options='SUBSCRIPTION';
		if(strstr($myrow2[$options],'D') && strstr($myrow2[$options],'F'))
			$myrow2[$options]='Evalue';
		elseif( strstr($myrow2[$options],'D') )
			$myrow2[$options]='Eclassified';
		elseif( strstr($myrow2[$options],'F') )
			$myrow2[$options]='Erishta';
		else		
			$myrow2[$options]='Free';
		
		if($myrow2['PROFILEID']==$sender[$j])
			$sender_s=$myrow2[$options];
		else
			$receiver_s=$myrow2[$options];
		$options='MTONGUE';
                if($myrow2['PROFILEID']==$sender[$j])
                        $sender_m=$myrow2[$options];
                else
                        $receiver_m=$myrow2[$options];
		$options='COUNTRY_RES';
                if($myrow2['PROFILEID']==$sender[$j])
                        $sender_c=$myrow2[$options];
                else
                        $receiver_c=$myrow2[$options];
	}			
		$subscription[$sender_s][$receiver_s]++;

		$mtongue[$sender_m][$receiver_m]++;
		$mton_send[$sender_m]++;
		$mton_receive[$receiver_m]++;
		$mton_tot++;
	
		$country_res[$sender_c][$receiver_c]++;
		$country_send[$sender_c]++;
		$country_receive[$receiver_c]++;
		$count_tot++;

}


if(is_array($subscription))
foreach ($subscription as $key =>$value)
{
	if($key=='Evalue')
		$key=0;
	elseif($key=='Eclassified')
		$key=1;
	elseif($key=='Erishta')
		$key=2;
	elseif($key=='Free')
		$key=3;
	foreach ($value as $key2 =>$value2)
	{
		if($key2=='Evalue')
			$key2=0;
		elseif($key2=='Eclassified')
			$key2=1;
		elseif($key2=='Erishta')
			$key2=2;
		elseif($key2=='Free')
			$key2=3;
		$subscription_final[$key][$key2]=$value2;
		$sub_send[$key]+=$value2;
		$sub_receive[$key2]+=$value2;
		$sub_tot+=$value2;

	}
}


//	print_r($mtongue);	echo "<br>";
//	print_r($country_res);	echo"<br>";
//	print_r($subscription);	echo "<br>";
//	print_r($paid);echo "<br>";
//	print_r($subscription_final);	echo "<br>";


$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);


if(is_array($mtongue))
{
	$sql1="INSERT INTO MIS.CONTACT_BREAKDOWN_MTONGUE(DATE,SENDER,RECEIVER,COUNT) VALUES ";
	foreach($mtongue as $key=>$value)
	{
		foreach($value as $k=>$val)
		{
			$sql1.= "('$today','$key','$k','$val'),";

		}
	}
	$sql1=substr($sql1,0,-1);
	mysql_query($sql1,$db) or die("3 ".mysql_error1($db));
}

if(is_array($country_res))
{
	$sql1="INSERT INTO MIS.CONTACT_BREAKDOWN_COUNTRY(DATE,SENDER,RECEIVER,COUNT) VALUES ";
	foreach($country_res as $key=>$value)
	{
		foreach($value as $k=>$val)
		{
			$sql1.= "('$today','$key','$k','$val'),";

		}
	}
	$sql1=substr($sql1,0,-1);
	mysql_query($sql1,$db) or die("4 ".mysql_error1($db));
}       

if(is_array($subscription_final))
{
	$sql1="INSERT INTO MIS.CONTACT_BREAKDOWN_SUBSCRIPTION (DATE,SENDER,RECEIVER,COUNT) VALUES ";
	foreach($subscription_final as $key=>$value)
	{
		foreach($value as $k=>$val)
		{
			$sql1.= "('$today','$key','$k','$val'),";

		}
	}
	$sql1=substr($sql1,0,-1);
	mysql_query($sql1,$db) or die("5 ".mysql_error1($db));
}       

mail("neha.verma@jeevansathi.com,puneet.makkar@jeevansathi.com","Jeevansathi Contact breakdown script ran successfully ".date('Y-m-d'),"Jeevansathi Contact breakdown script ran successfully ".date('Y-m-d'));

function mysql_error1($db)
{
	mail("neha.verma@jeevansathi.com,puneet.makkar@jeevansathi.com","Jeevansathi Error in contact breakdown from LIVE",mysql_error($db));
}


?>
