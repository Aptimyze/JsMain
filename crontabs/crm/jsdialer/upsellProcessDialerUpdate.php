<?php
/*********************************************************************************************
* FILE NAME   	: upsellProcessDialerUpdate.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$dialerLogObj =new DialerLog();

//Open connection at JSDB
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$campaignName	='UPSELL_JS';
$action		='STOP';
$date7DayBefore =date("Y-m-d",time()-2*24*60*60)." 00:00:00";

// Update Dial status before 7 days
$sql= "UPDATE incentive.SALES_CSV_DATA_UPSELL SET DIAL_STATUS=0 WHERE CSV_ENTRY_DATE<'$date7DayBefore' AND DIAL_STATUS>0";
mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));

$profilesArr    =fetchProfiles($db_master);
$profileStr     =implode(",",$profilesArr);

if($profileStr!='')
{
	// Set dial status=0 for upsell campaign
	$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE PROFILEID IN ($profileStr) AND Dial_Status=1";
	mssql_query($query1,$db_dialer) or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);

	// delete profiles
	deleteProfiles($db_master,$profileStr);

	foreach($profilesArr as $key=>$profileid){	
		$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaignName','Dial_Status=0',now(),'$action')";
        	mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
	}
}

// mail added
$to="manoj.rana@naukri.com";
$sub="Dialer updates of Upsell Process.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$profileStr,$from);

// Fetch profile with dial status 0
function fetchProfiles($db_master)
{
        $profileArr =array();
        $sql= "SELECT PROFILEID FROM incentive.SALES_CSV_DATA_UPSELL WHERE DIAL_STATUS=0";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
        while($myrow = mysql_fetch_array($res))
                $profileArr[] = $myrow["PROFILEID"];
        return $profileArr;
}
// Delete profiles
function deleteProfiles($db_master,$profileStr)
{
	$sql= "delete FROM incentive.SALES_CSV_DATA_UPSELL WHERE DIAL_STATUS=0 AND PROFILEID IN ($profileStr)";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
}
?>
