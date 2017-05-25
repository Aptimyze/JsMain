<?php

/**
 * Test Case to check profilePic setter function
 * @author - Akash
 * @Last Modified - 30 Mar 2015
 * @execute - ./symfony test:unit profilePicTest
 */

/**
 * TEST CASES
 * 1. No Picture Exist 
 * 2. Already existing NonScreenedPic profilepic set as ProfilePic
 * 3. Other NonScreenedPic set as ProfilePic
 * 4. NonScreened Pic is already screened and selected as profile Pic
 * 5. NonScreened Pic is selected as profile Pic
 * 6. Screened Pic Set as profilePic
 * 7. Screened Pic Set as profilePic
 * 8. NonScreenedPic set as ProfilePic
 * 9. Screened Pic Set as profilePic
 * 10. Screened TO NonScreened Pic Set as profilePic
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


$t->diag("Creating Test Case data");

/**
 *  DATA CREATION
 */
include_once(dirname(__FILE__)."/data/profilePic.php");


/**
 *  Starting Test
 */
$t->diag("Starting Test");


$noProfilePic="";
$t->is($pictureServiceObj->setProfilePic($noProfilePic), 1, 'No Picture Exist');
/**
 * NON-Screened PIC as PROFILE PIC ALREADY EXISTS
 * Non Screened to be set as profilepic
 */
$t->is($pictureServiceObj->setProfilePic($nonScreenedAlbum[0]), 1, 'Same NonScreenedPic set as ProfilePic');
$t->is($pictureServiceObj->setProfilePic($screenedAlbum[0]), 1, 'NonScreenedPic set as ProfilePic');
$t->is($pictureServiceObj->setProfilePic($nonScreenedAlbum[0]), 1, 'NonScreened Pic is already screened and selected as profile Pic');
$t->is($pictureServiceObj->setProfilePic($nonScreenedAlbum[1]), 1, 'NonScreened Pic is selected as profile Pic');
//Screened to be set as profilepic
$t->is($pictureServiceObj->setProfilePic($screenedAlbum[1]), 1, 'Screened Pic Set as profilePic');

/**
 * NOn Screened pic NOT a PROFILE PIC ALREADY EXISTS
 */
$t->is($pictureServiceObj->setProfilePic($nonScreenedAlbum[0]), 1, 'NonScreenedPic set as ProfilePic');
$t->is($pictureServiceObj->setProfilePic($screenedAlbum[0]), 1, 'Screened Pic Set as profilePic');

$t->is($pictureServiceObj->setProfilePic($nonScreenedAlbum[0]), 1, 'Screened TO NonScreened Pic Set as profilePic');

$t->diag("Testing Completed");

?>	
