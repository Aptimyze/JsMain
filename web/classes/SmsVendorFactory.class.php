<?php
/**
 * @class SmsVendorFactory
 * @brief Venfor Factory for returning various Vendor class objects. By default set as air2web
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/SMS_MODULE#New_vendor_-_air2web
 * @author Tanu Gupta
 * @created 2012-05-21
 */

if(!$_SERVER['DOCUMENT_ROOT']) $_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot; //For crons. will be different in dev/test/live mode.
include($_SERVER['DOCUMENT_ROOT']."/classes/SmsAir2Web.class.php");
class SmsVendorFactory{
        /**
         * @fn getSmsVendor
         * @brief returns vendor object
         * @param $vendor - sms vendor name, if not specified, sets as air2web
         */
	static public function getSmsVendor($vendor){
		if(!$vendor) $vendor = "air2web";
		if($vendor == "air2web"){
			return new SmsAir2Web;
		}
	}
}
?>
