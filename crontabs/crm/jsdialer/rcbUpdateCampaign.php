<?php
/*********************************************************************************************
* FILE NAME   	: paidCampaignProcess.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
*********************************************************************************************/
include("MysqlDbConstants.class.php");

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$campaignName	='OB_JS_RCB';
$action		='OB_JS_RCB';
$date2DayBefore =date("Y-m-d H:i:s",time()-58*60*60);

$profilesArr    =fetchProfiles($db_master);
$profileStr     =implode(",",$profilesArr);

if($profileStr!=''){
	// Set dial status=0 for paid campaign
	$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE CSV_ENTRY_DATE<'$date2DayBefore'";
	mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer,1);
	deleteProfiles($db_master,$profileStr);

	foreach($profilesArr as $key=>$profileid){	
		$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaignName','DIAL_STATUS=0',now(),'$action')";
        	mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
	}
}

// mail added
$to="manoj.rana@naukri.com";
$sub="Dialer updates of RCB Campaign Process.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$profileStr,$from);

// Fetch profile with dial status 0
function fetchProfiles($db_js)
{
        $profileArr =array();
        $sql= "SELECT PROFILEID FROM incentive.SALES_CSV_DATA_RCB WHERE DIAL_STATUS=0";
        $res=mysql_query($sql,$db_js) or die($sql.mysql_error($db_js));
        while($myrow = mysql_fetch_array($res))
                $profileArr[] = $myrow["PROFILEID"];
        return $profileArr;
}
function deleteProfiles($db_master,$profiles)
{
        $sql= "delete FROM incentive.SALES_CSV_DATA_RCB WHERE DIAL_STATUS=0 AND PROFILEID IN ($profiles)";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
}

// Error logging
function logerror($sql="",$db="",$ms)
{
        $today=@date("Y-m-d h:m:s");
        $filename="logerror.txt";
        if(is_writable($filename)){
                if (!$handle = fopen($filename, 'a')){
                        echo "Cannot open file ($filename)";
                        exit;
                }
                fwrite($handle,"\n\nQuery : $sql \t Error : " .mssql_get_last_message(). " \t $today");
                fclose($handle);
        }
        else{
                echo "The file $filename is not writable";
        }
}
?>
