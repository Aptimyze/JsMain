<?php
/**
 * Test Case to check profilePic setter function
 * @author - Akash
 * @Last Modified - 30 Mar 2015
 * @execute - ./symfony test:unit updateHavePhoto
 */

/**
 * TEST CASES
 * No picture Exists
 * 1. New Photo Added - Have Photo Updated to U
 * 2. Other New Photo Added Have photo Remains same - PhotoDate And PhotoScreen changes
 * 3. Photo is deleted and underscreening status sent for remaing photo - Have photo - U
 * 4. Photo Is deleted - No other Photo Exist so HavePhoto- N
 * 5. PhotoScreened - PhotoScreen value changes to 1
 * 6. Photo To be Screened - PhotoScreen value changes to 0
 */


/**
 * Including Bootstrap is optional, but it makes the test file an independent PHP script that you can execute without the symfony command line
 */
include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(9, new lime_output_color());


/**
 * Database Connection
 */
include_once(dirname(__FILE__)."/../../../web/jsadmin/connect.inc");
$dbS = connect_db();



$t->diag("Creating Test Case data - No Pic Exist- HAVE PHOTO ''");
//****************************************************************************
// DATA CREATION
include_once(dirname(__FILE__)."/data/updateHavePhoto.php");


$t->diag("Starting Test");
$pictureServiceObj->updateHavePhoto('add');
$t->is($profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,PHOTOSCREEN")["HAVEPHOTO"], 'Y', 'Photo added- have Photo Updated to U');
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
