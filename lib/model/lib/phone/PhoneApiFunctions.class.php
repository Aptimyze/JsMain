<?php

class PhoneApiFunctions
{
	public static $interval = '5';
	public static $frequency = '12';
        public static $phoneTitle = "Verify Your Number";
        public static $phoneMessage = "Phone verification is mandatory to use Jeevansathi.com";
        public static function phoneVerifyProcessMessage($virtualNumber,$isd='')
        {
			return "Just give missed call on ".$virtualNumber." from any of the numbers given below";
        }
        public static $phoneButtonIndiaText = "Give a missed call";
        public static $phoneButtonInternationalText = "Give a missed call";
	public static $labelMobile = "Mobile Number";
	public static $labelAlternateMobile = "Alternate Mobile Number";
	public static $helpline = "18004196299";
	public static $helpEmail = "help@jeevansathi.com";
	public static $errorBottomMessage = "Working hours: From 9 AM to 9 PM (IST) on all days";
	public static $OTPTrialsOverMsg = "You have reached the maximum number of attempts for Verification code. You can also <b>verify by giving us a missed call</b>";
	public static $serviceTimeText="(from 9 AM to 9 PM (IST) on all days)";



	public static $consentMessage1="We would like to inform you that by verifying the above number you are agreeing to receive calls from the customer support team of Jeevansathi, even though your number is registered with the NCPR."; 
 	public static $consentMessage2="Please note that you can change your preference from the Alert Manager Settings page on the Desktop site any time."; 	 
}
