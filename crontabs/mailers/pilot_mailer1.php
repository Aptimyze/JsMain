<?php

/************************************************************************************************************************
  * FILENAME           : pilot_mailer1.php
  * DESCRIPTION        : Mail will be send for the pre-acceptance EOI    
  ***********************************************************************************************************************/

include_once("../profile/connect.inc");
include_once("../profile/contact.inc");

// Connection setting
$db_master	=connect_db();
$db       	=connect_slave();

// Configuration Setting
$execName 		='Mona';	// configurable
$execPhone 		='9911991167';	// configurable
$smarty->relative_dir	="mailer/";
$from			='info@jeevansathi.com';
$sub			='Connect with your perfect match in just Rs. 299';
$mail_status 		=false;
$dateExpiry             ='25th Jan 2012';	// configurable	
//dateExpiry		=date("jS M Y", JSstrToTime("+7 days"));

$smarty->assign("execName",$execName);
$smarty->assign("execPhone",$execPhone);
$smarty->assign("dateExpiry",$dateExpiry);

$sql1 ="SELECT SENDER,RECEIVER from mailer.PA_MAILER_POOL";
$res1= mysql_query($sql1,$db) or die(mysql_error_log($db,$sql1));
while($row1=mysql_fetch_array($res1))
{
	$sender      	=$row1['SENDER'];
	$receiver    	=$row1['RECEIVER'];
	$email 		=displayResultSet($sender,$receiver);
	$msg		=$smarty->fetch('pilot_mailer1.htm');

	//$email='manoj.rana@naukri.com';
	$mail_status 	=send_email($email,$msg,$sub,$from);
	if($mail_status){
		$sql ="update mailer.PA_MAILER_POOL SET SENT='Y' where SENDER='$sender' AND RECEIVER='$receiver'";
		mysql_query($sql,$db_master) or die(mysql_error_log($db,$sql));
	}
}

function displayResultSet($sender,$receiver)
{
        global $smarty,$IMG_URL,$PHOTO_URL;
        $start          =0;
        $jsadmin        =1;
        $data_3d         =array();

        $data_3d[0]     =array("PROFILEID"=>$sender);
        $data_3d[1]     =array("PROFILEID"=>$receiver);
        $resultSet      =get_profile_details_all($data_3d,$start,$jsadmin);
        $senderArr      =$resultSet[$sender];
        $receiverArr    =$resultSet[$receiver];

        $senderUsername         =$senderArr['NAME'];
        $senderGender           =$senderArr['GENDER'];
        $senderAge              =$senderArr['AGE'];
        $senderHeight           =$senderArr['HEIGHT'];
        $senderReligion         =$senderArr['RELIGION'];
        $senderCaste            =$senderArr['CASTE'];
        $senderMTongue          =$senderArr['MTONGUE'];
        $senderEdu              =$senderArr['EDUCATION'];
        $senderOccupation       =$senderArr['OCCUPATION'];
        $senderIncome           =$senderArr['INCOME'];
        $senderLocation         =$senderArr['RESIDENCE'];
        $senderPhotoVersion     =$senderArr['version'];
        $senderPhotoChecksum    =$senderArr['PHOTOCHECKSUM'];

        $receiverUsername       =$receiverArr['NAME'];
        $receiverRelation       =$receiverArr['RELATION'];
        $receiverGender         =$receiverArr['GENDER'];
        $receiverMobile         =$receiverArr['PHONE_MOB'];
        $receiverResidence      =$receiverArr['PHONE'];
        $receiverSTD            =$receiverArr['STD'];
        $receiverISD            =$receiverArr['ISD'];
        $receiverEmail          =$receiverArr['EMAIL'];
        $receiverPhotoVersion   =$receiverArr['version'];
        $receiverPhotoChecksum  =$receiverArr['PHOTOCHECKSUM'];

        // Photo manipulation
        $sender_photo   ="$PHOTO_URL/profile/photo_serve100.php?version=$senderPhotoVersion&profileid=$senderPhotoChecksum&photo=PROFILEPHOTO";
        $receiver_photo ="$PHOTO_URL/profile/photo_serve100.php?version=$receiverPhotoVersion&profileid=$receiverPhotoChecksum&photo=PROFILEPHOTO";

        $receiverEmail =wordwrap($receiverEmail,18,"<br />\n",true);
        if($receiverResidence && $receiverSTD)
                $receiverResidence =$receiverSTD.$receiverResidence;

        if($senderGender=='F'){
                $genderText1 ='her';
                $genderText2 ='she';
        }
        else if($senderGender=='M'){
                $genderText1 ='him';
                $genderText2 ='he';
        }

        if($receiverRelation ==1){
                $genderText3 ='I';
                $genderText4 ='me';
        }
        else{
                $genderText3 ='we';
                $genderText4 ='us';
        }

        $smarty->assign("senderUsername",$senderUsername);
        $smarty->assign("senderAge",$senderAge);
        $smarty->assign("senderHeight",$senderHeight);
        $smarty->assign("senderReligion",$senderReligion);
        $smarty->assign("senderCaste",$senderCaste);
        $smarty->assign("senderMTongue",$senderMTongue);
        $smarty->assign("senderEdu",$senderEdu);
        $smarty->assign("senderOccupation",$senderOccupation);
        $smarty->assign("senderIncome",$senderIncome);
        $smarty->assign("senderLocation",$senderLocation);
	$smarty->assign("sender_photo",$sender_photo);
        $smarty->assign("genderText1",$genderText1);
        $smarty->assign("genderText2",$genderText2);
        $smarty->assign("genderText3",$genderText3);
        $smarty->assign("genderText4",$genderText4);

        $smarty->assign("receiverUsername",$receiverUsername);
        $smarty->assign("receiverGender",$receiverGender);
        $smarty->assign("receiverMobile",$receiverMobile);
        $smarty->assign("receiverResidence",$receiverResidence);
        $smarty->assign("receiverSTD",$receiverSTD);
        $smarty->assign("receiverISD",$receiverISD);
        $smarty->assign("receiverEmail",$receiverEmail);
        $smarty->assign("receiver_photo",$receiver_photo);
	return $receiverEmail;
}

function mysql_error_log($db,$sql)
{
	mail("manoj.rana@naukri.com"," Error in pay-per-use Mailer(pilot_mailer1.php)",$sql - mysql_error($db));
}

?>
