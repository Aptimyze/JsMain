<?php 
/***************************************************************************************************************************
Filename    : service_on_time_process.php
Description : Maintain the data in admin and log table and check the profiles should handled on time.
Created By  : Vibhor Garg
Created On  : 21 May 2008
****************************************************************************************************************************/
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
include_once("../connect.inc");
$db=connect_db();

$start_time=date("Y-m-d H:i:s");

array($profile_arr);

$sql_select = "SELECT PROFILEID FROM incentive.SERVICE_ADMIN WHERE CALL_STATUS=3";
$res_select = mysql_query($sql_select,$db) or $msg .= "\n$sql_select \nError :".mysql_error();
while($row_select=mysql_fetch_assoc($res_select))
{
        $profile_arr[]=$row_select['PROFILEID'];
}
if(count($profile_arr)>0)
{
        $profile_str=implode("','",$profile_arr);
        $sql_insert = "INSERT INTO incentive.SERVICE_ADMIN_LOG (SELECT * FROM incentive.SERVICE_ADMIN WHERE PROFILEID IN ('$profile_str'))";
        mysql_query($sql_insert,$db) or $msg .= "\n$sql_insert \nError :".mysql_error();

        $sql_delete = "DELETE FROM incentive.SERVICE_ADMIN WHERE PROFILEID IN ('$profile_str')";
        mysql_query($sql_delete,$db) or $msg .= "\n$sql_delete \nError :".mysql_error();
}

$yesterday = date("Y-m-d",time()-(86400));

$sql1 = "SELECT PROFILEID,FOLLOWUP_DT FROM incentive.SERVICE_ADMIN WHERE HANDLED_DT='$yesterday' AND CALL_STATUS=0";
$res1 = mysql_query($sql1,$db) or $msg .= "\n$sql1 \nError :".mysql_error();
while($row1=mysql_fetch_assoc($res1))
{
	$fdate=$row1['FOLLOWUP_DT'];
	if($fdate=='')
        	$hprofiles[]=$row1['PROFILEID'];
	elseif($fdate<=$yesterday)
		$hprofiles[]=$row1['PROFILEID'];
}

$sql2 = "SELECT PROFILEID,FOLLOWUP_DT FROM incentive.SERVICE_ADMIN WHERE FEEDBACK_DT='$yesterday' AND CALL_STATUS=1";
$res2 = mysql_query($sql2,$db) or $msg .= "\n$sql2 \nError :".mysql_error();
while($row2=mysql_fetch_assoc($res2))
{
        $fdate=$row['FOLLOWUP_DT'];
        if($fdate=='')
                $fprofiles[]=$row2['PROFILEID'];
        elseif($fdate<=$yesterday)
                $fprofiles[]=$row2['PROFILEID'];
}

$sql3 = "SELECT PROFILEID,FOLLOWUP_DT FROM incentive.SERVICE_ADMIN WHERE RECONVINCE_DT='$yesterday' AND CALL_STATUS=2";
$res3 = mysql_query($sql3,$db) or $msg .= "\n$sql3 \nError :".mysql_error();
while($row3=mysql_fetch_assoc($res3))
{
        $fdate=$row3['FOLLOWUP_DT'];
        if($fdate=='')
                $rprofiles[]=$row3['PROFILEID'];
        elseif($fdate<=$yesterday)
                $rprofiles[]=$row3['PROFILEID'];
}

for($i=0;$i<count($hprofiles);$i++)
{
	$not_ontime_profiles[]=$hprofiles[$i];
}
for($i=0;$i<count($fprofiles);$i++)
{
        $not_ontime_profiles[]=$fprofiles[$i];
}
for($i=0;$i<count($rprofiles);$i++)
{
        $not_ontime_profiles[]=$rprofiles[$i];
}
if(count($not_ontime_profiles)>0)
{
        $not_ontime_profiles_str=implode("','",$not_ontime_profiles);
	$sql_update = "UPDATE incentive.SERVICE_ADMIN SET ON_TIME='N' WHERE PROFILEID IN ('$not_ontime_profiles_str')";
	mysql_query($sql_update,$db) or $msg .= "\n$sql_update \nError :".mysql_error();
}

$end_time=date("Y-m-d H:i:s");

$from="From:JeevansathiCrm@jeevansathi.com";

$msg.="\n List of Profiles Ids shifted\n\n".$profile_str;
$msg.="\n List of Profiles Ids marked not handled on time\n\n".$not_ontime_profiles_str;
$msg.="\n Start time : $start_time";
$msg.="\n End time : $end_time";
mail("vibhor.garg@jeevansathi.com,sriram.viswanathan@jeevansathi.com","Finally handled records removed from process","$msg",$from);

?>
