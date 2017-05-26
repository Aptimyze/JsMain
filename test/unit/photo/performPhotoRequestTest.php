<?php
//./symfony test:unit updateHavePhoto
        include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
        $t = new lime_test(9, new lime_output_color());

        include_once(dirname(__FILE__)."/../../../web/jsadmin/connect.inc");
        $dbS = connect_db();
        

        $t->diag("Creating Test Case data - No Pic Exist- HAVE PHOTO ''");
        //****************************************************************************
        // DATA CREATION
        include_once(dirname(__FILE__)."/data/updateHavePhoto.php");
        
        
        $t->diag("Starting Test");
	
        
        $pictureServiceObj->updateHavePhoto('add');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"], 'Y', 'Photo added- have Photo Updated to U');
        
        $pictureServiceObj->updateHavePhoto('add');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["HAVEPHOTO"], 'U', 'Photo added- have Photo remains U and PhotoDate and screen Updated');
        
        $pictureServiceObj->updateHavePhoto('del','U');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["HAVEPHOTO"], 'U', 'Photo deleted- Have Photo Updated as underscreening - U');
        
        $pictureServiceObj->updateHavePhoto('del','N');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["HAVEPHOTO"], 'N', 'Photo deleted- Have Photo Updated as No Photo - N');
        
        $pictureServiceObj->updateHavePhoto('screen','1');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["PHOTOSCREEN"], 1, 'Photo Screened- PhotoScreen Updated to 1');
        
        $pictureServiceObj->updateHavePhoto('screen','0');
        $t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["PHOTOSCREEN"], 0, 'Photo to be Screened- PhotoScreen Updated to 0');
        
        
        $t->diag("Test Completed");
?>	
