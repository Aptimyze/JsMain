<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$pf = ProfileCacheLib::getInstance();
//$aa = $pf->removeFieldsFromCache("144111", "ProfileAstro",ProfileCacheConstants::ALL_FIELDS_SYM);
//$aa = $pf->get("PROFILEID", "144111","*","JPROFILE");
//$aa = $pf->get("PROFILEID", "144111","*","ProfileEducation");
//$aa = $pf->get("PROFILEID", "144111","*");
$aa = $pf->get("PROFILEID", "144111","*","ProfileAstro");
print_r($aa);die;
?>