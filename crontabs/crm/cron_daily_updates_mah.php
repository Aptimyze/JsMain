<?php
echo $msg = "Start time #".@date('H:i:s');echo "\n";
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_mah.php
* DESCRIPTION 	: Change the data of the online dialing daily
* MADE DATE 	: 20 Mar, 2014
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
include("cron_daily_updates_functions.php");

//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to vario server");
$db_js_157 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to local server");
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js_157);

//Connection at DialerDB
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");

$campaign_name = 'MAH_JSNEW';
$start_from=0;

/*Stop non-eligible profiles*/
echo "/////////////////Part-1//////////////////"."\n";
for($i=$start_from;$i<10;$i++)
{
	$ignore_array = compute_ignore_array($i,$db_js);
	$vd_array = getVDdiscount($ignore_array,$db_js);
        stop_non_eligible_profiles($campaign_name,$i,$ignore_array,$db_dialer,$db_js_157,$vd_array);
	echo "DONE$i"."\n";
}

/*Update data of eligible profiles*/
echo "/////////////////Part-2//////////////////"."\n";
for($i=$start_from;$i<10;$i++)
{
	$eligible_array = compute_eligible_array($i,$db_js);
	$vd_array = getVDdiscount($eligible_array,$db_js);
	$loggedinWithin15days = loginWithin15Days($eligible_array,$db_js);
	$allotedArray = allotedArray($eligible_array,$db_js);
	$scoreArray = scoreArray($eligible_array,$db_js);
        update_data_of_eligible_profiles($campaign_name,$i,$eligible_array,$db_dialer,$vd_array,$loggedinWithin15days,$allotedArray,$scoreArray,$db_js_157);
	echo "DONE$i"."\n";
}
echo "\n";
echo $msg.="End time :".@date('H:i:s');
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Dialer updates of maharashtra done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
?>
