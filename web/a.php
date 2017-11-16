<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

//JPROFILE
//$j = new JPROFILE();
//$x  = $j->get("9474668","PROFILEID","*",array("activatedKey"=>1));
//$x  = $j->getArray(array("PROFILEID"=>"9474668,144111"),"","","*");
//$x  = $j->edit(array("MSTATUS"=>"N"),"9474668");
//$x  = $j->edit(array("MSTATUS"=>"S"),"9474668","PROFILEID","activatedKey = 1");
//print_R($x);die;


// jprofile education
//$j = ProfileEducation::getInstance();
//$x  = $j->getProfileEducation("9474668");
//$x  = $j->update("9474668",array("PG_COLLEGE"=>"'AIIMS'"));
//$x  = $j->getProfileEducation(array("9474668","144111"),1);
//print_R($x);die;

// native place
//$j = ProfileNativePlace::getInstance();
//$x  = $j->InsertRecord(array("PROFILEID"=>"947466822","NATIVE_COUNTRY"=>"128"));
//$x  = $j->getRecord("947466811");
//$x  = $j->getNativeDataForMultipleProfiles(array("9474668","144111"));
//$x  = $j->UpdateRecord("9474668",array("NATIVE_CITY"=>"AR01"));
//print_R($x);die;

//CONTACT
//$j = new ProfileContact();
//$x  = $j->getArray(array("PROFILEID"=>"9474668,144111"),"","","*");
//$x  = $j->getProfileContacts("9474668");
//$x  = $j->updateAltMobile("9474668","");
//$x  = $j->update("9474668",array("ALT_MOBILE"=>"9999999999","LINKEDIN_URL"=>"test"));
//print_R($x);die;

//FSO
//$j = ProfileFSO::getInstance();
//$x  = $j->check("9474668");
//$x  = $j->delete("9474668");
//$x  = $j->insert("9474668");
//print_R($x);die;

//Profile Expiry
//$j = new ProfileAUTO_EXPIRY();
//$x  = $j->getDate("9474668");
//$x  = $j->replace("9474668","P","2020-11-09 11:46:28");
//print_R($x);die;

//Jhobby
//$j = new JHOBBYCacheLib();
//$x  = $j->getArray(array("PROFILEID"=>"9474668"),"","","*");
//$x  = $j->getUserHobbies("9474668");
//$x  = $j->getUserHobbiesApi("9474668");
//$x  = $j->update("9474668",array("FAV_MOVIE"=>"jab we met"));
//print_R($x);die;


//$j = new ProfileFilter();
//$x  = $j->updateField("COUNTRY_RES",array(array("PROFILEID"=>"144111"),array("PROFILEID"=>"9474668")));
//$x  = $j->updateRecord("9474668");
//$x  = $j->fetchEntry("947466811");
//$x  = $j->updateFilters("144111","HARDSOFT='N',MSTATUS='N'");
//$x  = $j->insertRecord("947466811",array("AGE"=>"N","MSTATUS"=>"N","RELIGION"=>"N","COUNTRY_RES"=>"N","MTONGUE"=>"N","CASTE"=>"N","CITY_RES"=>"N","INCOME"=>"N","COUNT"=>"1","HARDSOFT"=>"N"));
//$x  = $j->fetchFilterDetailsForMultipleProfiles(array("9474668","144111"));
//LEFT setAllFilters insertRecord
//print_R($x);die;

//Aadhaar
//$j = new aadharVerification();
//$x  = $j->getAadharDetails("9474668");
// $x  = $j->resetAadharDetails("9474668");
//$x  = $j->updateVerificationStatus("9474668","Y");
//$x  = $j->insertAadharDetails("9474668","ZZSA7290","2017-11-09 11:46:28","876789876545","9234879gb34");
//print_R($x);die;

// your old info
//$j = new ProfileYourInfoOld();
//$x = $j->getAboutMeOld("9474668");
//print_r($x);die;


// Alerts
//$j = new JprofileAlertsCache();
//$x = $j->insertRecord(array("PROFILEID"=>"947466811","MEMB_MAILS"=>"U")); // if half data is passed than half data set in cache on select again query
//$x = $j->insertNewRow("947466822");
//$x = $j->update("9474668","MEMB_MAILS","U");
//$x = $j->commonFunctionForSelect("947466822","*");
//$x = $j->getAllSubscriptionsArr(array("9474668","94746681"));
//print_r($x);die;
/********************DONE**************************/

//$j = ProfileAstro::getInstance();
//$x  = $j->getAstroDetails(array("144111"),"");
//$x  = $j->getAstros("144111");
//$x  = $j->getIfAstroDetailsPresent("144111");
//print_R($x);die;
//$j = ProfileAstro::getInstance();
//$x  = $j->getAstros("9474668");
//print_R($x);


//Direct cache hitting
//$objProCacheLib = ProfileCacheLib::getInstance();
//$result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $profileidArray,$fields,__CLASS__);
//print_r($result);die;
$pf = ProfileCacheLib::getInstance();
$aa = $pf->cacheThis("PROFILEID", "9474668",array("USERNAME"=>"tttt"),"");
//print_r($aa);
//$aa = $pf->get("PROFILEID", "9474668", "*","ProfileEducation");
//print_r($aa);
//$aa = $pf->get("PROFILEID", "9474668", "USERNAME,AGE,ALT_MOBILE,PG_COLLEGE,CITY_BIRTH,AUTO_EXPIRY_DATE","");
//print_r($aa);

//$j = new ProfileContact();
//$pid = "3777";
//$x  = $j->getArray(array("PROFILEID"=>$pid),"","","*");
////$x  = $j->getProfileContacts("9474668");
//print_R($x);
//ProfileCacheLib::getInstance()->removeFieldsFromCache($pid,"ProfileContact");
?>