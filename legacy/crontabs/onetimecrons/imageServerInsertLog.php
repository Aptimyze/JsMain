<?php

include_once(JsConstants::$docRoot."/jsadmin/connect.inc"); 
include_once(JsConstants::$docRoot."/profile/SymfonyPictureFunctions.class.php");

ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

$db = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$sql="SELECT * FROM newjs.PICTURE_NEW WHERE MainPicUrl LIKE 'JS/%' OR ProfilePicUrl LIKE 'JS/%' OR ThumbailUrl LIKE 'JS/%' OR Thumbail96Url LIKE 'JS/%' OR SearchPicUrl LIKE 'JS/%' AND UPDATED_TIMESTAMP<'2013-09-05' ORDER BY PICTUREID ASC";

$result=mysql_query($sql,$db) or die("1 ".mysql_error());
$pictureIds = array();
$mainPic = array();
$profilePic = array();
$thumbnail = array();
$thumnail96 = array();
$searchPic = array();
$i=0;
while($myrow=mysql_fetch_array($result))
{
	$pictureIds[$i] = $myrow["PICTUREID"]; 
	$mainPic[$i] = $myrow["MainPicUrl"];
	$profilePic[$i] = $myrow["ProfilePicUrl"];
	$thumbnail[$i] = $myrow["ThumbailUrl"];
	$thumnail96[$i] = $myrow["Thumbail96Url"];
	$searchPic[$i] = $myrow["SearchPicUrl"];
        $i++;
}
$maxPictureId = $pictureIds[$i-1];
$screenedPictureObj= new ScreenedPicture(); 
$result=$screenedPictureObj->insertBulkImageServerLog($pictureIds,$mainPic,$profilePic,$thumbnail,$thumnail96,$searchPic);
?>
