<?php
function get_device_info($userAgent)
{
 	include('js_wurfl-config.inc');
	$device = $wurflManager->getDeviceForUserAgent($userAgent);
	$result=array();
	$result['mobileBrowser']=$device->getCapability('is_wireless_device');
	$result['is_tablet']=$device->getCapability('is_tablet');
	$result['phone_call_string']=$device->getCapability('wml_make_phone_call_string');
	$result['cookie_support']=$device->getCapability('cookie_support');
	$result['wta_phonebook']=$device->getCapability('wta_phonebook');
	unset($device);
	return $result;
}
