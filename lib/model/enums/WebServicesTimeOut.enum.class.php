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
		"getProfilelistContact"=>200,
		"viewer" =>100,
		"getcontactscount"=>300,
		"contactedProfile"=>300,
		"getcontactedprofilearray"=>300,
		"resultset"=>300,
		"updateseen"=>200,
		"viewerviewed"=>200,
		"update"=>200,
		"insert"=>200,
		"delete"=>200,
	);
}