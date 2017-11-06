<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//$j = new JPROFILE();
//$x  = $j->get("7936732","PROFILEID","*",array("activatedKey"=>1));
//print_R($x);

//$j = ProfileEducation::getInstance();
//$x  = $j->getProfileEducation("7936732");
//print_R($x);

//$j = ProfileNativePlace::getInstance();
//$x  = $j->getRecord("7936732");
//print_R($x);
//
//$j = ProfileAstro::getInstance();
//$x  = $j->getAstros("7936732");
//print_R($x);

//$j = ProfileFSO::getInstance();
//$x  = $j->check("7936732");
//print_R($x);

//$j = new ProfileAUTO_EXPIRY();
//$x  = $j->getDate("7936732");
//print_R($x);

$j = new ProfileContact();
$x  = $j->getArray(array("PROFILEID"=>"9474668"),"","","*");
//$x  = $j->getProfileContacts("9474668");
print_R($x);

?>