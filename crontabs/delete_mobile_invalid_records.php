<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	exit;
	/**
	* This file delete records from tables containing entries of invalid phones whose phone no. is verified.
	* @tables : newjs.REPORT_INVALID_PHONE, incentive.INVALID_PHONE
	**/
	include("connect.inc");
	$db=connect_db();

	$mobileStr ="";
	$profileidStr="";
	// time of the provious day
	$time =date("Y-m-d",strtotime("-1 days"));

	// select mobile number which are verified
	$sql_1 ="SELECT MOBILE from newjs.MOBILE_VERIFICATION_SMS where SUBSTRING(ENTRY_DT,1,10)='$time'";
	$result =mysql_query($sql_1) or die($sql_1);
	$mobileArr =array();
	while($row=mysql_fetch_array($result))
	{
		$mobileArr[]= "'".$row['MOBILE']."'";
	}		
	$mobileStr =implode(",",$mobileArr);	

	if(!$mobileStr)
		return;
	// select profileids from JPROFILE for verified mobile number
	$sql_2 ="SELECT PROFILEID from newjs.JPROFILE where `PHONE_MOB` IN($mobileStr)";
        $result =mysql_query($sql_2) or die($sql_2);
        $profileidArr =array();
        while($row_2=mysql_fetch_array($result))
        {
                $profileidArr[]="'".$row_2['PROFILEID']."'";
        }
	$profileidStr =implode(",",$profileidArr);

	if(!$profileidStr)
		return; 	
	// delete records from table incentive.INVALID_PHONE 	
        $sql_3 ="DELETE from incentive.INVALID_PHONE where `PROFILEID` IN($profileidStr)";
        mysql_query($sql_3) or die($sql_3);

	// delete records from table jsadmin.REPORT_INVALID_PHONE
        $sql_4 ="DELETE from jsadmin.REPORT_INVALID_PHONE where `SUBMITTEE` IN($profileidStr)";
        mysql_query($sql_4) or die($sql_4);
	
	
?>
