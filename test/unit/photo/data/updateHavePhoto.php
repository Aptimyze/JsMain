<?php

$profileid = '144111';
        
        $sqls = array("DELETE FROM `newjs`.`PICTURE_FOR_SCREEN_NEW` WHERE PROFILEID=144111",
        "DELETE FROM `newjs`.`PICTURE_NEW` WHERE PROFILEID=144111",
        "UPDATE `newjs`.`JPROFILE` SET HAVEPHOTO='',PHOTOSCREEN='1',PHOTODATE='2015-04-01 18:52:12' WHERE PROFILEID='".$profileid."'"
        );
        foreach($sqls as $key=>$sql)
		$result=mysql_query($sql,$dbS) or die("1 ".mysql_error());
	
	$profileObj[0] = LoggedInProfile::getInstance('newjs_master',$profileid);
        $profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
	
	
	$pictureServiceObj = new PictureService($profileObj[0]);
	//$album = $pictureServiceObj->getAlbum();
	$nonScreenedAlbum = $pictureServiceObj->getNonScreenedPhotos('album');
	$screenedAlbum = $pictureServiceObj->getScreenedPhotos('album');
	$album = $pictureServiceObj->getAlbum();

