<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db =connect_db();
$db2 =connect_737();

/*
$sqlj="SELECT USER FROM jsadmin.UPSELL_AGENT";
$resj=mysql_query($sqlj) or die(mysql_error());
while($rowj = mysql_fetch_array($resj))
        $allot_to_array[] = $rowj['USER'];
$upsell_agent=implode("','",$allot_to_array);
*/
//$sql_crm ="select PROFILEID from incentive.MAIN_ADMIN WHERE STATUS NOT IN('P','S') AND ALLOTED_TO NOT IN ('$upsell_agent')";

$sql_crm ="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE STATUS IN('C','F','FO','R')";
$res_crm = mysql_query($sql_crm,$db2) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{

        $profileid =$row_crm['PROFILEID'];
        $sql1 ="SELECT PROFILEID from billing.SERVICE_STATUS where PROFILEID='$profileid' AND ACTIVATED='Y' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' order by ID DESC LIMIT 1";
        $res1 = mysql_query($sql1,$db) or logError($sql1);
        if($row1 = mysql_fetch_array($res1))
	{
		echo $sql3 ="update incentive.MAIN_ADMIN set STATUS='P' where PROFILEID='$profileid'";
		echo "<br>".
		mysql_query($sql3,$db) or logError($sql3);
	}
}
?>
