<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");


	include("connect.inc");

	connect_db();

	logTime();
	$today=date("Y-m-d");
	$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
	
	//$sql="SELECT PROFILEID FROM JPROFILE WHERE ACTIVATED='H'";
	if($res=mysql_query($sql))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$profileid=$row['PROFILEID'];
			$arrFields = array('ACTIVATED'=>'PREACTIVATED');
			$exrtaWhereCond = "ACTIVATE_ON<='$today'";
			$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
			// $sql="UPDATE JPROFILE SET ACTIVATED=PREACTIVATED WHERE PROFILEID='$profileid' AND ACTIVATE_ON<='$today'";
			// mysql_query($sql) or logError($sql);
		}
	}
	else
		logError($sql);
	logTime();
?>
