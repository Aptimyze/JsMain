<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(16, new lime_output_color());
/*
		$loggedInProfile = new Profile('',144111);	
		$otherProfile = new Profile('',3188208);
	  $fields = "*";
    $loggedInProfile->getDetail("", "", $fields);
    $otherProfile->getDetail("", "", $fields);
		$contact = new Contacts($loggedInProfile, $otherProfile);
		$contact->setTYPE('I');
    $contactHandlerObj = new ContactHandler($loggedInProfile,$otherProfile,"EOI",$contact,"N","POST");
*/
    $memcache = ContactsMemcache::getInstance(350);/**/
    $before = $memcache->getMemcacheData();
    echo "BEFORE \n";
    print_r($before);/*
    $memcache->setTodayInitiatedByMe(1);
    $memcache->setTotalContactsMade(1);
    $memcache->setMonthInitiatedByMe(1);
    $memcache->setWeekInitiatedByMe(1);
    $memcache->setContactsCountMadeAfterDCMLiveDate(1);
    $memcache->updateMemcacheData();
    $after = $memcache->getMemcacheData();
    echo "AFTER \n";
    print_r($after);*/
