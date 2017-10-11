<?php

$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include_once(JsConstants::$docRoot.'/../crontabs/connect.inc');
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$db = connect_db();
$db_slave = connect_slave();

$sql = "SELECT PICTUREID,PROFILEID,ProfilePic120Url FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE OriginalPicUrl!='' AND ProfilePic120Url!='' AND SCREEN_BIT='0000000' AND ThumbailUrl IS null AND UPDATED_TIMESTAMP > DATE_SUB(NOW(),INTERVAL 6 HOUR)";

$res=mysql_query($sql,$db_slave) or die(mysql_error($db_slave)); 
while($row = mysql_fetch_array($res)){
    $picID = $row['PICTUREID'];
    $profileid = $row['PROFILEID'];
    $pic120 = $row['ProfilePic120Url'];
    $manipulator = new ImageManipulator();	
    $newDimensions = array('w'=>'60','h'=>'60');
    $completeUrl = PictureFunctions::getCloudOrApplicationCompleteUrl($pic120);
    $newImage = $manipulator->resize($completeUrl,$newDimensions,true);
    $pictureObj = new NonScreenedPicture();
    //get save url for resized pic
    $picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['ThumbailUrl'],$picID,$profileid,'jpeg','nonScreened');
    $manipulator->save($newImage,$picSaveUrl,'jpeg');
    //get display url to update entry in DB
    $dbSaveUrl= $pictureObj->getDisplayPicUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['ThumbailUrl'],$picID,$profileid,'jpeg','nonScreened');
    $sql2 = "UPDATE newjs.PICTURE_FOR_SCREEN_NEW SET ThumbailUrl = '".$dbSaveUrl."' WHERE PICTUREID = ".$picID;
    mysql_query($sql2,$db) or die(mysql_error($db));
}
