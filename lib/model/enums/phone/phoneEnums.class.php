<?php
//used for channels through which website is accessed

class phoneEnums
{
	public static $virtualNoForLeads  = array (8506896060,8506876060,8506936060,8506846060,8506816060);
	public static $OTPSMSLimit  = 5;
	public static $OTPHoursLimit  = 24;
	public static $OTPTrialLimit  = 5;
	public static $OTPMessage  = "Use {OTP} as the code to verify your phone number on Jeevansathi";
public static $OTPMessageForDeletion  = "Use {OTP} as the code to delete your profile (This message will be checked for PAT !)";

//This mapping array is used to add reason and other reason incase of a user marks a phone number invalid.
	public static $mappingArrayReportInvalid = array("Switched off / Not reachable","Not an account holder's phone"," Already married/engaged"," Not picking up","Other","The number does not exist");

}

?>
