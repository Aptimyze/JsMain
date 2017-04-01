<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");


	include("connect.inc");

	connect_db();

	logTime();
	$today=date("Y-m-d");
	$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
	
	$sql="SELECT PROFILEID,PREACTIVATED FROM JPROFILE WHERE ACTIVATED='H' AND ACTIVATE_ON <= '$today'";
	if($res=mysql_query($sql))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$profileid=$row['PROFILEID'];
			$preactivated = $row['PREACTIVATED'];
			$arrFields = array('ACTIVATED'=>$preactivated);
			$exrtaWhereCond = "ACTIVATE_ON<='$today'";
			$res1 = $jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
			// $sql="UPDATE JPROFILE SET ACTIVATED=PREACTIVATED WHERE PROFILEID='$profileid' AND ACTIVATE_ON<='$today'";
			//mysql_query($sql) or logError($sql);
			if(false !== $res1) {
				$argv[1] = $profileid;
				include_once(JsConstants::$docRoot."/profile/retrieveprofile_bg.php");
			}
		}
	}
	else
		logError($sql);
	logTime();
?>
