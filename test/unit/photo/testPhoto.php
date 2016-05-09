<?php

	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	$t = new lime_test(1, new lime_output_color());
//	print_r($_SERVER[argv]);
//	$profileid = '144111';
        $profileid = '2351780';

        $profileObj[0] = Profile::getInstance('newjs_master',$profileid);
        $profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
//        $profileObj1=Profile::getInstance('newjs_master','3187885');
$profileObj1=LoggedInProfile::getInstance('newjs_master','3187885');
        $profileObj1->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
        $obj = new PictureArray();
//        $r = $obj->getProfilePhoto($profileObj,'','','','','','Y','');
//        print_r($r);
$t->is($obj->getProfilePhoto($profileObj,'','','','','','Y',''), 'filteredPhoto', 'final result');
?>	
