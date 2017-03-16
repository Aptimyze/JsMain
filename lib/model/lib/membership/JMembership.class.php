<?php

if (JsConstants::$whichMachine != 'matchAlert') {
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/connect_functions.inc");
}

include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Memcache.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/globalVariables.Class.php");

class JMembership extends Membership
{
    
    public function getRemainingContactsForUser($profileid,$extraFields="") {
        $contactsAllotedObj = new jsadmin_CONTACTS_ALLOTED();
        $contacts = $contactsAllotedObj->getRemainingContactsForProfile($profileid,$extraFields);
        return $contacts;
    }
    
    public function fetchNRIStatus($ipAddress) {
		$geoIpCountry = $_SERVER['GEOIP_COUNTRY_CODE'];
		if(!empty($geoIpCountry)){
			if($geoIpCountry == 'IN'){
				return false;	
			} else {
				return true;
			}
		} else {
				return false;
		}
    }
}
?>
