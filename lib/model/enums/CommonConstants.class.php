<?php

class CommonConstants{
	
const SHOW_CONSENT_MSG=true;
const HELP_NUMBER_INR = "1-800-419-6299";
const HELP_NUMBER_NRI = "+91-120-4393500";
const showHelpScreensJSPC=true;
const DUPLICATE_NUMBER_CONSENT=true;
const AddLink = "https://www.youtube.com/embed/hhxFaXaidvA?rel=0";
const ONLINE_USER_EXPIRY =900;
const ONLINE_USER_LIST ='online_user';
const ONLINE_USER_KEY ='online_';
const contactMailersCC =  true;
public static  $CONSENT_MSG_TEXT = array('We would like to inform you that as per your account settings you have agreed to receive calls from our customer support team, even though your number is registered in NCPR.','Please note that you can change your preference from the ‘Alert Manager Settings’ page on the Desktop site.');
public static  $CONSENT_MSG_API = 'phone/consentConfirm';
public static $REFRESH_INTERVAL_RATE = 15000; //15 secs
}
