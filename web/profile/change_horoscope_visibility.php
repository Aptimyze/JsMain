<?php
include("connect.inc");
$db=connect_db();
$data=authenticated($checksum);
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if($data)
	$profileid=$data['PROFILEID'];  //getting the profileid
$sql="update newjs.JPROFILE set SHOW_HOROSCOPE = '$horo_display', MOD_DT = NOW() where newjs.JPROFILE.PROFILEID='$profileid'";
$result=mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
JProfileUpdateLib::getInstance()->removeCache($profileid);
echo $horo_display;
?>
