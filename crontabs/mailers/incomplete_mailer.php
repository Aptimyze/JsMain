<?php
	include("../config.php");
	include($_SERVER[DOCUMENT_ROOT]."/profile/connect.inc");
	$protect_obj=new protect;
	$db=connect_slave();

	if (date("d")=='10' || date("d")=='25')
	{
		$sql="SELECT SERVICE_MESSAGES,GENDER,PROFILEID,USERNAME,EMAIL,PASSWORD FROM newjs.JPROFILE WHERE INCOMPLETE='Y' AND ACTIVATED!='D' AND ENTRY_DT > DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
	}
	else
	{
		$yesterday = date("Y-m-d", mktime(0,0,0,date("m"), date("d") - 1, date("Y")));
		$sql="SELECT SERVICE_MESSAGES,GENDER,USERNAME,PROFILEID,EMAIL,PASSWORD FROM newjs.JPROFILE WHERE INCOMPLETE='Y' AND ACTIVATED!='D' AND ENTRY_DT BETWEEN '$yesterday 00:00:00' and '$yesterday 23:59:59'";
	}

	$from="info@jeevansathi.com";
	$subject="Build your complete profile";
	$result=mysql_query($sql) or logError($sql);
	while($myrow=mysql_fetch_array($result))
	{
		$service=$myrow['SERVICE_MESSAGES'];
		if($service=='S')
		{
			$to=$myrow['EMAIL'];
			$PROFILECHECKSUM=md5($myrow['PROFILEID'])."i".$myrow['PROFILEID'];
			$smarty->assign("CHECKSUM",$PROFILECHECKSUM);
			$echecksum=$protect_obj->js_encrypt($PROFILECHECKSUM,$to);
			$smarty->assign("echecksum",$echecksum);

			$username=$myrow['USERNAME'];
			
			$password=$myrow['PASSWORD'];
			$smarty->assign("gender",$myrow['GENDER']);
			$smarty->assign("username",$username);
			$smarty->assign("email",$to);
			$smarty->assign("password",$password);
			$smarty->assign("myprofilechecksum",$PROFILECHECKSUM);
			$msg=$smarty->fetch("incomplete_mailer.htm");
			send_email($to,$msg,$subject,$from);
		}
	}
?>
