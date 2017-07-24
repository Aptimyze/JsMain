<?php

class privacySettingsEnums
{
	public static $jprofileFields= array("Mobile_No","Landline_Number","Photo_Privacy","Profile_Visibility");
	public static $jprofileContactFields= array("Alternate_Number");
	public static $ProfileVisibilityLabel = "Profile_Visibility";
	public static $PhotoSettingLabel = "Photo_Privacy";
	public static $validPhotoPrivacyValues = array("A","C");
	public static $MobileSettingLabel = "Mobile_No";
	public static $LandlineSettingLabel = "Landline_Number";
	public static $jprofileFieldsToUpdate= array("Mobile_No"=>"SHOWPHONE_MOB","Landline_Number"=>"SHOWPHONE_RES","Photo_Privacy"=>"PHOTO_DISPLAY");
}