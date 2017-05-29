<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/************
Author: @ESHA
weekly cron for saturday or sunday
description: http://devjs.infoedge.com/mediawiki/index.php/Tracking
************/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
connect_db();
//$to='esha.jain@jeevansathi.com';

$to='rohit.manghnani@jeevansathi.com';
$cc='tanu.gupta@brijj.com, esha.jain@jeevansathi.com';
$subject="Track duplicate emails";

$MAX_LIMIT_IP=1;
$MAX_LIMIT_EMAIL=2;
$LIMIT=1000;     //limit for max no of entried to be deleted at a time

$back_date_start=mktime(0,0,0,date("m"),date("d")-8,date("Y"));
$start=date("Y-m-d",$back_date_start);

$back_date_end=mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$end=date("Y-m-d",$back_date_end);

$flag_ip=0;
$flag_email=0;

$sql="SELECT COUNT(*) AS C_IP, IP FROM MIS.TRACK_DUPLICATE_EMAIL WHERE `TIME` BETWEEN '$start 00:00:00' AND '$end 23:59:59' AND FLAG='Y' GROUP BY IP HAVING COUNT(*)>=$MAX_LIMIT_IP";
$result=mysql_query_decide($sql) or logError("$sql");
if(mysql_num_rows($result)>0)
{
	$flag_ip=1;
	$ip_array=array();
	for($countIp=0;$row=mysql_fetch_array($result);$countIp++)
	{
                $ip_array[$countIp]["IP"]=$row["IP"];
                $ip_array[$countIp]["COUNT"]=$row['C_IP'];

		$sql1="SELECT COUNT(*) AS C_PAGE, PAGE FROM MIS.TRACK_DUPLICATE_EMAIL WHERE IP ='".$ip_array[$countIp]['IP']."' AND`TIME` BETWEEN '$start 00:00:00' AND '$end 23:59:59' AND FLAG='Y' GROUP BY PAGE";
		$result1=mysql_query_decide($sql1) or logError("$sql1");
		$ip_array[$countIp]["no"]=mysql_num_rows($result1);
		if($ip_array[$countIp]["no"]>0)
		{
			for($cPageIp=0;$row1=mysql_fetch_array($result1);$cPageIp++)
        		{
				$ip_page_list[$cPageIp]["COUNT"]=$row1["C_PAGE"];
				$ip_page_list[$cPageIp]["PAGE"]=$row1["PAGE"];
			}

		}

	}
}

$sql="SELECT COUNT(*) AS C_EMAIL, EMAIL FROM MIS.TRACK_DUPLICATE_EMAIL WHERE `TIME` BETWEEN '$start 00:00:00' AND '$end 23:59:59' AND FLAG='Y' GROUP BY EMAIL HAVING COUNT(*)>=$MAX_LIMIT_EMAIL";
$result=mysql_query_decide($sql) or logError("$sql");

if(mysql_num_rows($result)>0)
{
	$flag_email=1;
	$email_array=array();
	for($countEmail=0;$row=mysql_fetch_array($result);$countEmail++)
	{
		$email_array[$countEmail]["EMAIL"]=$row["EMAIL"];
		$email_array[$countEmail][COUNT]=$row['C_EMAIL'];

                $sql1="SELECT COUNT(*) AS C_PAGE, PAGE FROM MIS.TRACK_DUPLICATE_EMAIL WHERE EMAIL ='".$row['EMAIL']."' AND`TIME` BETWEEN '$start 00:00:00' AND '$end 23:59:59' AND FLAG='Y' GROUP BY PAGE";
                $result1=mysql_query_decide($sql1) or logError("$sql1");
		$email_array[$countEmail]["no"]=mysql_num_rows($result1);
                if($email_array[$countEmail]["no"]>0)
                {
                        for($cPageEmail=0;$row1=mysql_fetch_array($result1);$cPageEmail++)
                        {
                                $email_page_list[$cPageEmail][COUNT]=$row1["C_PAGE"];
                                $email_page_list[$cPageEmail]["PAGE"]=$row1["PAGE"];
                        }
                        
                }


	}
}

$msg="";
if($flag_ip==1)
{
	$msg.='<div style>
	      List of IPs possibly trying to track email ids :
	<br>
	<br>
	<br>
	<table width=80% border=2  cellpadding="8">
		<th>IP</th>
		<th>COUNT</th>
		<th>PAGE</th>
		<th> COUNT PER PAGE</th>';
	for($j=0;$j<$countIp;$j++)
	{
		$msg.='<tr align=left> <td rowspan='.$ip_array[$j]["no"].' >';
		$msg.= $ip_array[$j]["IP"];
		$msg.= '</td><td rowspan='.$ip_array[$j]["no"].' >';
		$msg.=$ip_array[$j][COUNT];
		$msg.='</td>';
		for($k=0;$k<$ip_array[$j]["no"];$k++)
		{
			if($k!=0)
				$msg.='<tr>';
			$msg.='<td>'.$ip_page_list[$k]["PAGE"].'</td>';
                        $msg.='<td>'.$ip_page_list[$k][COUNT].'</td></tr>';
		}


	}
	$msg.='</table><br>';
}

if($flag_email==1)
{
	$msg.='<div style>
	      List of email_ids possibly trying to track users :
	<br>
	<br>
        	<table width=80% border=2  cellpadding="8">
                	<th>Email</th>
                	<th>COUNT</th>
                	<th>PAGE</th>
                	<th> COUNT PER PAGE</th>';
        for($j=0;$j<$countEmail;$j++)
        {
                $msg.='<tr align=left> <td rowspan='.$email_array[$j]["no"].' >';
                $msg.= $email_array[$j]["EMAIL"];
                $msg.= '</td><td rowspan='.$email_array[$j]["no"].' >';
                $msg.=$email_array[$j][COUNT];
                $msg.='</td>';
                for($k=0;$k<$email_array[$j]["no"];$k++)
                {
                        if($k!=0)
                                $msg.='<tr>';
                        $msg.='<td>'.$email_page_list[$k]["PAGE"].'</td>';
                        $msg.='<td>'.$email_page_list[$k][COUNT].'</td></tr>';
                }


        }
$msg.='</table><br>
      <br>';
}


//}
if($msg)
{
$msg.='from ';
$msg.= $start;
$msg.=' to ';
$msg.= $end;
$msg.='.<br> <br></div>

<div align="left" >Regards,</div>
<div align="left" >JS</div>';
send_email($to,$msg,$subject,"",$cc);
}

////////cleanup

$sql="SELECT count(*) FROM MIS.TRACK_DUPLICATE_EMAIL WHERE `TIME`< '".$start."'";
$res=mysql_query_decide($sql) or logError($sql);
$row=mysql_fetch_array($res);

$total= $row["count(*)"];
$loop_count= ceil($total/$LIMIT);

for($i=0;$i<$loop_count;$i++)
{
        $sql1="DELETE from MIS.TRACK_DUPLICATE_EMAIL WHERE `TIME` <  '".$start."' LIMIT ".$LIMIT;
        $result1=mysql_query_decide($sql1) or logError($sql1);
}
?>
