<?php

/***************************************************************************************
	Script for Deleting Marriage Buraue Profiles from Jprofile and from all Places.
	
****************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

$db= connect_slave();
$db2=connect_db();

$sql="SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE SOURCE LIKE 'mb%' AND ACTIVATED !='D'";
$res= mysql_query($sql,$db) or die(mysql_error1($db,$sql));

if(mysql_num_rows($res))
{
       while($row=mysql_fetch_array($res))
       {
		$profileid = $row['PROFILEID'];
		$username = $row['USERNAME'];
		$date= date("Y-F-j"); 

		$sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',MOD_DT=now() where PROFILEID='$profileid'";
		$res1= mysql_query($sql,$db2) or die(mysql_error($db2,$sql));

		$sql="UPDATE newjs.JPROFILE SET ACTIVATED = 'D' WHERE PROFILEID =$profileid";
		$res1= mysql_query($sql,$db2) or die(mysql_error($db2,$sql));


		$sql= "INSERT INTO jsadmin.MARK_DELETE (PROFILEID,STATUS,DATE,M_DATE,REASON,COMMENTS,ENTRY_BY) VALUES('$profileid','D',now(),'','Marriage bureau services being discontinued','','Tech')";
		$res1= mysql_query($sql,$db2) or die(mysql_error($db2,$sql));

		// Inserting into Jsadmin.DELETED_PROFILES for Showing the Reason

		$sql= "INSERT INTO jsadmin.DELETED_PROFILES (PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME) VALUES('$profileid','$username','Others','Marriage bureau services being discontinued during Revamp','Tech','$date')";
		$res1= mysql_query($sql,$db2) or die(mysql_error($db2,$sql));

		// Upto Here 

		$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null ";
		$cmd = "/usr/bin/php -q ".$path;
		passthru($cmd);
       }
}

?>

