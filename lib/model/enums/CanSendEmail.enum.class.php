<?php
class CanSendEnums
{
	public static $channelTypeToFieldMap = array(
//key in this is mapped with alertType/emailtype
//if this array has keys as integers it means those values are mapped with MailerGroup of EmailSender 
		"EMAIL"=>array(
			"MATCHALERT"  => "PERSONAL_MATCHES",
			"11"  => "PHOTO_REQUEST_MAILS",
			"29"  => "MEMB_MAILS",
			"27" => "SERVICE_MAILS",//top 8 mailers
			"22" => "SERVICE_MAILS",   //phone verification mailer
			"5" => "CONTACT_ALERT_MAILS", //eoi mailers
			"SAVED_SEARCH" => "PERSONAL_MATCHES"
			));
	public static $fieldMap = array(
			"PERSONAL_MATCHES"=>array("TABLE_CLASS"=>"JPROFILE","NOT_ALLOWED_VALUE"=>"U"),
			"MEMB_MAILS"=>array("TABLE_CLASS"=>"newjs_JPROFILE_ALERTS","NOT_ALLOWED_VALUE"=>"U"),
			"PHOTO_REQUEST_MAILS"=>array("TABLE_CLASS"=>"newjs_JPROFILE_ALERTS","NOT_ALLOWED_VALUE"=>"U"),
			"SERVICE_MAILS"=>array("TABLE_CLASS"=>"newjs_JPROFILE_ALERTS","NOT_ALLOWED_VALUE"=>"U"),
			"CONTACT_ALERT_MAILS"=>array("TABLE_CLASS"=>"newjs_JPROFILE_ALERTS","NOT_ALLOWED_VALUE"=>"U")
		);
	public static $channelEnums = array(
			"EMAIL"=>"EMAIL"
			);
}
