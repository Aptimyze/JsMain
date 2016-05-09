<?php
/*********************************************************************************************
* FILE NAME   	: salesCampaignDialerProcess.php 
* DESCRIPTION 	: Capture sales campaign details for mailer sending 
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/

//Open connection at JSDB
$db_master 	= mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server at ".$start);
$db_js 		= mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to js server at ".$start);
//Connection at DialerDB
$db_dialer 	= mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");

$campaignArr	=array('IB_Sales','IB_Service');
$startDt 	=date("Y-m-d",time()-24*60*60)." 00:00:00";
$endDate	=date("Y-m-d",time()-24*60*60)." 23:59:59";
$mailStatus	='N';

// Truncate Table
$sqlTrunc ="truncate table incentive.SALES_CAMPAIGN_PROFILE_DETAILS";
mysql_query($sqlTrunc,$db_master) or die($sqlTrunc.mysql_error($db_master));

foreach($campaignArr as $key=>$campaignName){	

	$squery1 = "select distinct(PHONE_NO1) as PHONE_NO from easy.dbo.ct_$campaignName where Last_call_date between '$startDt' and '$endDate'";
	$sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
	while($srow1 = mssql_fetch_array($sresult1))
	{
		$phoneNo =$srow1["PHONE_NO"];
		$phoneNo =phoneNumberCheck($phoneNo);

		if($phoneNo){
			$profileArr =getProfileDetails($phoneNo,$db_js);
			foreach($profileArr as $key=>$profileid){
				$js_query = "INSERT ignore into incentive.SALES_CAMPAIGN_PROFILE_DETAILS(`PROFILEID`,`PHONE_NO`,`CAMPAIGN`,`MAIL_SENT`) VALUES ('$profileid','$phoneNo','$campaignName','$mailStatus')";
	                	mysql_query($js_query,$db_master) or die($js_query.mysql_error($db_master));
			}
		}

	}
}
// mail added
$to="manoj.rana@naukri.com";
$sub="Sales Campaign Details Update";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$profileStr,$from);

// Phone Number Validate
function phoneNumberCheck($phoneNumber)
{
	$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
        $phoneNumber    =ltrim($phoneNumber,0);
        if(!is_numeric($phoneNumber))
                return false;
        return $phoneNumber;
}
// Fetch profiles
function getProfileDetails($phoneNo, $db_slave)
{
	$profileArr =array();
        $sql= "SELECT PROFILEID,ACTIVATED FROM newjs.JPROFILE WHERE PHONE_MOB='$phoneNo' OR PHONE_WITH_STD='$phoneNo'";
        $res=mysql_query($sql,$db_slave) or die($sql.mysql_error($db_slave));
        while($myrow = mysql_fetch_array($res)){
		if($myrow['ACTIVATED']!='D')
	                $profileArr[] = $myrow["PROFILEID"];
	}

	if(count($profileArr)==0){
	        $sql1= "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE='$phoneNo'";
	        $res1=mysql_query($sql1,$db_slave) or die($sql1.mysql_error($db_slave));
	        while($myrow1 = mysql_fetch_array($res1)){

	                $pid = $myrow1["PROFILEID"];
			$sql2= "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID='$pid' AND ACTIVATED!='D'";
			$res2=mysql_query($sql2,$db_slave) or die($sql2.mysql_error($db_slave));
			if($myrow2 = mysql_fetch_array($res2)){
				$profileArr[] =$myrow2["PROFILEID"];
			}
		}
	}
        return $profileArr;
}
?>
