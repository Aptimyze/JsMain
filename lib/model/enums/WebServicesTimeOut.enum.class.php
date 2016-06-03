<?php
/**
 * User: Pankaj Khandelwal
 * Date: 13/04/16
 * Time: 9:38 AM
 * Web Service Request Time out Enums
 */



class WebServicesTimeOut
{
	public static $contactServiceTimeout = array(
		"getProfilelistContact"=>10,
		"viewer" =>10,
		"getcontactscount"=>20,
		"contactedProfile"=>50,
		"getcontactedprofilearray"=>50,
		"resultset"=>50,
		"updateseen"=>50,
		"viewerviewed"=>10,
		"update"=>10,
		"insert"=>10,
		"delete"=>10,
	);
}