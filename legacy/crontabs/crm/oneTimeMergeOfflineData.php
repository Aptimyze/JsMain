<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");
$db=connect_db();
$db_slave=connect_737();

$sqlA="SELECT * FROM newjs.OFFLINE_REGISTRATION";
$resA=mysql_query($sqlA,$db_slave) or die(mysql_error());
while($rowA=mysql_fetch_array($resA))
{
	$pid=$rowA["PROFILEID"];
	$exe=$rowA["EXECUTIVE"];
	$edate=$rowA["DATE"];

	$sql1="UPDATE newjs.JPROFILE SET CRM_TEAM='offline' WHERE PROFILEID='$pid'";
	mysql_query($sql1,$db) or die(mysql_error());

	$sql2="UPDATE billing.PURCHASES SET SALES_TYPE='offline' WHERE PROFILEID='$pid'";
        mysql_query($sql2,$db) or die(mysql_error());

	$sql3="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME) VALUES ('$pid','$exe','$edate')";
        mysql_query($sql3,$db) or die(mysql_error());
}

$sqlB="(SELECT PROFILEID FROM incentive.MANUAL_ALLOT WHERE COMMENTS='inactive 30 days profile') UNION (SELECT PROFILEID FROM incentive.MANUAL_ALLOT WHERE COMMENTS='inactive 45 days profile') UNION (SELECT PROFILEID FROM incentive.MANUAL_ALLOT WHERE COMMENTS='incomplete profile')";
$resB=mysql_query($sqlB,$db_slave) or die(mysql_error());
while($rowB=mysql_fetch_array($resB))
{
        $proid=$rowB["PROFILEID"];

	$sql4="DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$proid'";
        mysql_query($sql4,$db) or die(mysql_error());

	$sql5="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='Y' WHERE PROFILEID='$proid'";
        mysql_query($sql5,$db) or die(mysql_error());

	$sql6="DELETE FROM incentive.MANUAL_ALLOT WHERE PROFILEID='$proid'";
        mysql_query($sql6,$db) or die(mysql_error());
}

$sqlC="SELECT DISTINCT(EXECUTIVE) FROM newjs.OFFLINE_REGISTRATION";
$resC=mysql_query($sqlC,$db_slave) or die(mysql_error());
while($rowC=mysql_fetch_array($resC))
{
        $exec=$rowC["EXECUTIVE"];

        $sql7="UPDATE jsadmin.PSWRDS SET PRIVILAGE = CONCAT( PRIVILAGE, '+OFF' ) WHERE USERNAME = '$exec'";
        mysql_query($sql7,$db) or die(mysql_error());
}
?>
