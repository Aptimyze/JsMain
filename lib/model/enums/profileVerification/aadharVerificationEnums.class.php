<?php

class aadharVerificationEnums
{
	const AADHAR_CONSENT = "I, the holder of Aadhaar number, hereby give my consent to Baldor Technologies Private Limited, to obtain my Aadhaar number, name, date of birth, address and demographic data for authentication with UIDAI. Baldor Technologies Private Limited has informed me that my identity information would only be used for a background check or a verification of my identity and has also informed me that my biometrics will not be stored/ shared and will be submitted to CIDR only for the purpose of authentication. I have no objection if reports generated from such background check are shared with relevant third parties.";

	const TYPE = "aadhaar_verification";
	const GROUPID = "20f46a37-e9d3-4d02-82f5-fce23abbf12d";
	const TASKID = "438d9786-2762-43f8-961f-4bd499d783d7";
	public static $fieldsToCheck ="AADHAR_NO,REQUEST_ID,VERIFY_STATUS";
	const URLTOHIT = "https://api.idfy.com/v2/tasks";
	public static $aadharHeaderArr = array(
    	'apikey:786c16d4-9b01-42bd-a64d-b8d046abb52b',
    	'Content-Type:application/json',
		);

	const NOTVERIFIED = "N";
	const AADHARLENGTH = 12;
	const IMPROPERFORMAT = "Aadhaar Id is not in proper format";
	const NOTVERIFIEDMSG = "Name on the profile does not match with name on the Aadhaar number provided";
	const STATUSPENDINGMSG = "Status Pending";
	const AADHARVERIFIED = "Aadhaar number is verified";
	public static $headerArrForStatus = array('apiKey:786c16d4-9b01-42bd-a64d-b8d046abb52b');
	CONST COMPLETED = "completed";
	CONST EXACTMATCH = "exact";
	CONST VERIFIED = "Y";
	CONST ALREADYVERIFIED = "This Aadhaar number is already verified for another profile on Jeevansathi.";
	CONST ALREADYVERIFIEDBYSAME = "Your Aadhaar is already verified.";
}
