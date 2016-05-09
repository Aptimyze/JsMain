<?php
        include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
        $t = new lime_test(9, new lime_output_color());

        include_once(dirname(__FILE__)."/../../../web/jsadmin/connect.inc");
        $dbS = connect_db();
        

        $t->diag("Creating Test Case data");
        //****************************************************************************
        // DATA CREATION
        include_once(dirname(__FILE__)."/data/profilePic.php");
        
        
        $t->diag("Starting Test");
	//pictureServiceObj = new PictureService($profileObj[0]);
	//$profilePic = $pictureServiceObj->getProfilePic();
	// PC
        $t->is($pictureServiceObj->deletePhoto($nonScreenedAlbum[0]->getPICTUREID(),$profileid), 0, 'ProfilePic Deletion - Other Pic Exist - NOT DELETED - PC');
        // NOT NEW MOBILE
        $t->is($pictureServiceObj->deletePhoto($nonScreenedAlbum[0]->getPICTUREID(),$profileid,'newMobile'), 1, 'ProfilePic Deletion - Other Pic Exist - DELETED - New Mobile Site');
        
        $t->is($pictureServiceObj->deletePhoto($nonScreenedAlbum[1]->getPICTUREID(),$profileid), 1, '2nd Non Screened Pic Deleted Successfully');
        
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[1]->getPICTUREID(),$profileid), 1, 'screened album 1 Pic Deleted Successfully');
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[2]->getPICTUREID(),$profileid), 1, 'screened Profile Pic - OTHER PIC EXIST - NOT Deleted');
        
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[3]->getPICTUREID(),$profileid), 1, 'screened album 2 Pic Deleted Successfully');
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[4]->getPICTUREID(),$profileid), 1, 'screened album 3 Pic Deleted Successfully');
        
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[5]->getPICTUREID(),$profileid), 1, 'screened album 4 Pic Deleted Successfully');
        //print_r($screenedAlbum[0]);die;
        $t->is($pictureServiceObj->deletePhoto($nonScreenedAlbum[0]->getPICTUREID(),$profileid), 1, 'non-screened PROFILE PIC Deleted Successfully');
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[0]->getPICTUREID(),$profileid), 1, 'screened PROFILE PIC Deleted Successfully');
        
       
        $sqls = array("DELETE FROM `newjs`.`PICTURE_FOR_SCREEN_NEW` WHERE PROFILEID=144111",
        "DELETE FROM `newjs`.`PICTURE_NEW` WHERE PROFILEID=144111",
        "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667345, 144111, 0, NULL, NULL, 0x323031352d30312d30362031333a34323a3239, '', '', '', 'JS/uploads/ScreenedImages/thumbnail96/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', 'JS/uploads/ScreenedImages/searchPic/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', 'JS/uploads/ScreenedImages/profilePic235/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', '')",
	"REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667283, 144111, 1, NULL, NULL, 0x323031352d30312d30352031353a33363a3335, 'JS/uploads/ScreenedImages/newMainPic/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', NULL, '', 'JS/uploads/ScreenedImages/thumbnail96/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', '', '', '', '', '', 'JS/uploads/ScreenedImages/mainPic/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '')",
            "UPDATE `newjs`.`JPROFILE` SET HAVEPHOTO='Y',PHOTOSCREEN='0',PHOTODATE='2015-04-01 18:52:12' WHERE PROFILEID='".$profileid."'"
	);
        foreach($sqls as $key=>$sql)
		$result=mysql_query($sql,$dbS) or die("1 ".mysql_error());
        $profileObj[0] = LoggedInProfile::getInstance('newjs_master',$profileid);
        $profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
        
        $screenedAlbum=$pictureServiceObj->getScreenedPhotos('album');
        
        $t->is($pictureServiceObj->deletePhoto($screenedAlbum[0]->getPICTUREID(),$profileid), 0, 'screened PROFILE PIC NOT DELETED- OTHER PICs EXIST');

        $t->diag("Test Completed");
?>	
