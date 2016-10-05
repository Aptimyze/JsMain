<?php
/*********************************************************************************************
* FILE NAME   	: upsellProcessDialerUpdate.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/

//Open connection at JSDB
$db_master = mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server at ".$start);
$db_js_157 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);

//Connection at DialerDB
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");

$campaignName	='UPSELL_JS';
$action		='UPSELL';
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
	mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer,1);

	// delete profiles
	deleteProfiles($db_master,$profileStr);

	foreach($profilesArr as $key=>$profileid){	
		$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaignName','DIAL_STATUS=0',now(),'$action')";
        	mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));
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
