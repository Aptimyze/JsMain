<?php

/******************************************************************************************************
 * FILE NAME   : ivr_verify.php
 * DESCRIPTION : This Script will Verify Landline Phone Numbers for all Eligible Indian Users. 
 * DATE        : 31st October 2008
 * MADE BY     : Anurag Gautam
 ******************************************************************************************************/

include ("connect.inc");
$db=connect_slave();
$db2=connect_db();


/* $land='91-5368-4325678'; //$land='4532367';   // Landline Number will come in this variable. $verf='50090002';  // Verification Number will come in this variable. $new=explode("-",$land);$isd=$new[0]; $std='0'.$new[1];$phone=$new[2]; */

$CsStdcode;
$CsNumber;
$AuthNumber;
$isd='91';

$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE JPROFILE.PHONE_RES='$CsNumber' AND JPROFILE.STD='$CsStdcode' AND JPROFILE.ISD='$isd'";
$result=mysql_query($sql,$db);
while($row=mysql_fetch_array($result))
{
	$id=$row['PROFILEID'];
}

if($id)
{
 	$sql="SELECT COUNT(*) AS CNT FROM newjs.LANDLINE_VERIFICATION_IVR WHERE LANDLINE_VERIFICATION_IVR.VERF_CODE='$AuthNumber' AND  PROFILE_ID='$id'";
	$result=mysql_query($sql,$db);
	while($row=mysql_fetch_array($result))
	{
		$cnt=$row['CNT'];
	}
	if($cnt)
	{
		echo "verified";
		$sql1="UPDATE newjs.LANDLINE_VERIFICATION_IVR SET VERF_STATUS='Y',ENTRY_DATE=NOW() WHERE PROFILE_ID='$id'" ;
		mysql_query($sql1,$db2) or die (mysql_error($db2,$sql1));
	}
	else
	{
		echo "invalidcode";
	}
}
else
{
	echo "notregistered";
}

/*
$sql="SELECT PROFILE_ID FROM newjs.LANDLINE_VERIFICATION_IVR,newjs.JPROFILE WHERE JPROFILE.PHONE_RES='$CsNumber' AND JPROFILE.STD='$CsStdcode' AND JPROFILE.ISD='$isd' AND  LANDLINE_VERIFICATION_IVR.VERF_CODE='$AuthNumber' AND LANDLINE_VERIFICATION_IVR.PROFILE_ID=JPROFILE.PROFILEID";
$result=mysql_query($sql,$db) or die (mysql_error($db,$sql));
while($row=mysql_fetch_array($result))
{
    $id=$row['PROFILE_ID'];
    $sql="UPDATE newjs.LANDLINE_VERIFICATION_IVR SET VERF_STATUS='Y',ENTRY_DATE=NOW() WHERE PROFILE_ID='$id'" ;
    mysql_query($sql,$db) or die (mysql_error($db,$sql));
}
*/

?>
