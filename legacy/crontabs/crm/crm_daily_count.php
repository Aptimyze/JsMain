<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$ts=time();
$yday=date("Y-m-d",$ts);
$ts+=24*60*60;
$today=date("Y-m-d",$ts)." 23:59:59";

$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%'";
$res=mysql_query($sql) or logError($sql);
while($row=mysql_fetch_array($res))
{
	$name=$row['USERNAME'];

        $sql =" SELECT COUNT(*) as ocnt from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name'  AND ORDERS IN ('Y','N') AND STATUS NOT IN ('F','C','P')";
        $result= mysql_query($sql) or logError($sql);
        $myrow=mysql_fetch_array($result);
        $ocnt=$myrow['ocnt'];

        $sql =" SELECT COUNT(*) as ofcnt from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND ORDERS IN ('Y','N') AND STATUS='F' AND FOLLOWUP_TIME<='$today'";
        $result= mysql_query($sql) or logError($sql);
        $myrow=mysql_fetch_array($result);
        $ocnt+=$myrow['ofcnt'];

        $sql =" SELECT COUNT(*) as nfcnt from incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name' AND STATUS='F' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND AGE>=25 AND SUBSCRIPTION=''";
        $result= mysql_query($sql) or logError($sql);
        $myrow=mysql_fetch_array($result);
        $nfcnt=$myrow['nfcnt'];

        $sql =" SELECT COUNT(*) as ncnt FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND STATUS='N' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND FOLLOWUP_TIME<>0 AND AGE>=25 AND SUBSCRIPTION='' AND ACTIVATED IN ('Y','H')";
        $result=mysql_query($sql) or logError($sql);
        $myrow = mysql_fetch_array($result);
        $ncnt = $myrow['ncnt'];

	$sql="SELECT COUNT(*) as ecnt FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name' AND STATUS='N' AND FOLLOWUP_TIME<='$yday 23:59:59' AND FOLLOWUP_TIME<>0 AND AGE>=25 AND SUBSCRIPTION=''";
        $result=mysql_query($sql) or logError($sql);
        $myrow = mysql_fetch_array($result);
        $carrycnt = $myrow['ecnt'];

	$sql="UPDATE MIS.CRM_DAILY_COUNT SET EMPTY_COUNT='$carrycnt' WHERE FOLLOW_DT='$yday' AND ALLOTED_TO='$name'";
	mysql_query($sql) or logError($sql);

	$sql="INSERT INTO MIS.CRM_DAILY_ALLOT (ALLOTED_TO,ASSIGN,FOLLOW,PAYMENT,CARRY,ENTRY_DT) VALUES ('$name','$ncnt','$nfcnt','$ocnt','$carrycnt','$today') ";
	mysql_query($sql) or logError($sql);
}
?>
