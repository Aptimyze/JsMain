<?php

class ResponseHandlerConfig
{
	//search
	public static $SEARCH_SAVED = array("message" => "Successfull", "statusCode" => '0');
	public static $SEARCH_SAVED_MAX_REACHED = array("message" => "Maximum number of saved searches reached.", "statusCode" => '1');
	public static $SEARCH_SAVED_ALREADY_EXISTS = array("message" => "A search with the same name already exists", "statusCode" => '1');
	public static $SEARCH_O_RESULTS = array("message" => "No matches found. Please search again with relaxed criteria.", "statusCode" => '1');
	public static $SEARCH_O_RESULTS_I = array("message" => "No results found.\n Kindly broaden your search criteria and try again.", "statusCode" => '1');
	public static $SEARCH_EXPIRED_SEARCHID = array("message" => "Results have changed since last time you searched. Kindly perform your search again.", "statusCode" => '10');

	//search
	public static $MATCHALERT_TOGGLE = array("message" => "Successfull", "statusCode" => '0');

	// access_token
	public static $NO_ACCESS_TOKEN = array("message" => "No access_token provided.", "statusCode" => '1');
	public static $INVALID_ACCESS_TOKEN = array("message" => "Invalid access_token provided.", "statusCode" => '3');

	//picture
	public static $PICTURE_NO_PHOTO = array("message" => "No photo present.", "statusCode" => '1');

	//Common
	public static $SUCCESS = array("message" => "Successful", "statusCode" => '0');
	public static $FAILURE = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');
	public static $SERVICE_UNAVAILABLE = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');
	public static $HTTP_CODE_MESSAGE_NOT_SET = array("message" => "HTTP code and message not set", "statusCode" => '1');
	public static $IGNORED_MESSAGE = array("message" => "You have reached the maximum block/ignore limit per user on Jeevansathi. Please unblock some profiles to further use this functionality", "statusCode" => '1');
	public static $LOGOUT_PROFILE = array("message" => "Please login to continue", "statusCode" => '9');

	//Authentication Variables:
	public static $INVALID_URL = array("message" => "Something went wrong. Please try again later.", "statusCode" => '3');
	public static $POST_PARAM_INVALID = array("message" => "Something went wrong. Please try again later.", "statusCode" => '4');


	//New Client Registration

	public static $AUTH_KEY_GENERATED = array("message" => "Auth Key Generated", "statusCode" => '0');
	public static $UNIDENTIFIED_IDENTIFIER = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');

	public static $APP_REG_VERIFIED = array("message" => "App Registration Verified", "statusCode" => '0');
	public static $APP_REG_FAILED = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');

	//public static $APP_REG_PAGE2_NOT_LOGGEDIN=array("message"=>"Not loggedin","statusCode"=>'1');
	public static $UNKNOWN_REG_REQUEST = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');

	//created for authenication
	public static $IP_NOT_VALID = array("message" => "Sorry, This APP is not supported in your country", "statusCode" => '11');
	public static $DEVICE_DISABLED = array("message" => "Something went wrong. Please try again later.", "statusCode" => '11');

	public static $INVALID_AUTHTOKEN_KEY = array("message" => "Something went wrong. Please try again later.", "statusCode" => '11');
	public static $INVALID_API_KEY_REVERSE = array("message" => "Something went wrong. Please try again later.", "statusCode" => '11');
	public static $INVALID_AUTH_TOKEN = array("message" => "Something went wrong. Please try again later.", "statusCode" => '11');
	public static $BLANK_AUTHOKEN = array("message" => "Something went wrong. Please try again later.", "statusCode" => '11');

	public static $DEVICE_AUTHENICATED = array("message" => "Authenicated", "statusCode" => '0');

	/********************END OF AUTHENICATION VARIABLES***/

	public static $BLANK_EMAIL_PASSWORD = array("message" => "Please provide login details", "statusCode" => '1');

	/****************LOGIN AUTHENICATION****************/
	public static $LOGIN_SUCCESS = array("message" => "login succesful", "statusCode" => '0');
	public static $LOGIN_FAILURE_USERNAME = array("message" => "You can login only with your Email ID", "statusCode" => '1');
	public static $LOGIN_FAILURE_ACCESS = array("message" => "Login details provided were not correct", "statusCode" => '1');
	public static $LOGIN_FAILURE_MISSING = array("message" => "Missing information", "statusCode" => '1');
	public static $LOGIN_FAILURE_DELETED = array("message" => "Profile with this email address has been deleted - please contact customer care", "statusCode" => '1');

	public static $GENDER_NOT_PRESENT = array("message" => "There is a problem with your account. Please contact customer care", "statusCode" => '1');


	/*******END OF LOGIN AUTHENICATION VARIABLES*******/

	/*********************Forgot Password ends here*****/
	//public static $FLOGIN_EMAIL_NOTPRESENT=array("message"=>"Email doesnot exist","statusCode"=>'2');
	public static $FLOGIN_EMAIL_ERR = array("message" => "The email address or phone number provided by you is not in our records", "statusCode" => "1");
	public static $FLOGIN_EMAIL_DELETED = array("message" => "Profile with this email address has been deleted - please contact customer care.", "statusCode" => "1");
	public static $FLOGIN_EMAIL_SUCCESS = array("message" => "Link to reset your password has been sent to your registered Email Id and Mobile Number. The link will be valid for next 24 hours.", "statusCode" => "0");
	public static $FLOGIN_EMAIL_SMSLIMIT_SUCCESS = array("message" => "Link to reset your password has been sent to your registered Email Id. The link will be valid for next 24 hours.", "statusCode" => "0");
	public static $FLOGIN_PHONE_ERR = array("message" => "There are multiple profiles against this phone number, please enter Email Address of account or contact customer care.", "statusCode" => "1");

	/********************Forgot password ends here******/
	//Only For Logging purpose
	public static $AUTHKEY_ERROR = array("message" => "Blank Authkey", "statusCode" => '1');
	public static $DB_NO_RECORD_ERROR = array("message" => "No record found for this auth key", "statusCode" => '1');

	// No Profile Error API Response
	public static $NO_PROFILE_ERROR = array("message" => "", "statusCode" => '1');

	//Change password
	public static $PASSWORD_NOT_MATCH = array("message" => "Current password is not correct", "statusCode" => "1");
	public static $PASSWORD_CURRENT_EMPTY = array("message" => "Current password cannot be blank", "statusCode" => "1");
	public static $PASSWORD_NEW_EMPTY = array("message" => "New password cannot be blank", "statusCode" => "1");
	public static $PASSWORD_CHANGE = array("message" => "Password changed successfully", "statusCode" => "0");
	public static $ISD_BLANK = array("message" => "Provide the country code", "statusCode" => "1");
	public static $ISD_INVALID = array("message" => "Provide a valid country code", "statusCode" => "1");
	public static $PHONE_BLANK = array("message" => "Provide a mobile number", "statusCode" => "1");
	public static $PHONE_INVALID = array("message" => "Provide a valid mobile number", "statusCode" => "1");
	public static $PHONE_INVALID_INPUT = array("message" => "Provide a valid phone number", "statusCode" => "1");
	public static $DISPLAY_PHONE_SCREEN = array("message" => "Display phone screen", "statusCode" => '8');
	public static $PHONE_JUNK = array("message" => "Phone number banned due to terms of use violation", "statusCode" => "1");
	//incomplete
	public static $INCOMPLETE_USER =  array("message"=>"incomplete Profile","statusCode"=>'7');
	public static $APP_DOWN =  array("message"=>"Site temporarily down","statusCode"=>'45');
	
        public static $USER_ALREADY_REGISTERED =  array("message"=>"User exist with same email and password. Redirecting to home page","statusCode"=>'0');
        
    //Browser Notification
    public static $BROWSER_ID_INSERT_SUCCESS = array("message"=>"Browser Id Inserted Successfully","statusCode"=>'1');
    public static $BROWSER_ID_INSERT_FAILURE = array("message"=>"Browser Id Not Inserted","statusCode"=>'0');
    public static $BROWSER_ID_INVALID_PARAM = array("message"=>"Missing Input Parameters","statusCode"=>'0');
    public static $BROWSER_NOTIFICATION_SUCCESS = array("message"=>"Success","statusCode"=>'1');
    public static $BROWSER_NOTIFICATION_FAILURE = array("message"=>"No Message Available","statusCode"=>'1');
    public static $BROWSER_NOTIFICATION_INVALID_PARAM = array("message" => "Invalid parameter","statusCode"=>'1');
    public static $BROWSER_NOTIFICATION_INVALID_CHANNEL = array("message" => "Invalid Channel", "statusCode" => '1');
    //profileChecksum not provided for verification documents
    public static $PROVIDE_PROFILECHECKSUM = array("message"=>"profileChecksum not provided","statusCode"=>'0');

    //Guna Score
    public static $ZERO_GUNA_MATCHES = array("message"=>"No Guna Score Matches","statusCode"=>'1');
    public static $NO_COMMUNICATION_HISTORY = array("message"=>"No Communication History","statusCode"=>'1');

    // Captcha Not verified
	public static $CAPTCHA_UNVERIFIED = array("message"=>"Please click the box 'I'm not a robot'","statusCode"=>'1');
	public static $PHONE_INVALID_SUCCESS = array("message"=>"Successfull","statusCode"=>'0');
	public static $PHONE_INVALID_NO_OPTION_SELECTED = array("message"=>"Please provide a valid reason","statusCode"=>'1');
        
    public static $NO_EMAILTYPE=  array("message"=>"No Email Type Passed","statusCode"=>'1');
  	public static $ALTERNATE_EMAIL_SUCCESS=  array("message"=>"A link has been sent to your email id {email}, click on the link to verify email.","statusCode"=>'0');
  	public static $ALTERNATE_EMAIL_ID_NOT_FOUND=  array("message"=>"No email ID found for the given user.","statusCode"=>'0');
	public static $PEAK_LOAD_FAILURE = array("message"=>"This operation cannot be done at site peak load","statusCode"=>'0');

	// Report Abuse
	public static $ABUSE_ATTEMPTS_OVER = array("message"=>"You cannot report abuse against the same person more than twice.","statusCode"=>'1');
	//Report Invalid

	 public static $SAME_NUMBER_INVALID_TWICE = array("message"=>"You cannot report the same number Invalid again.","statusCode" => '1');
     
     // Report Abuse
	public static $ABUSE_ATTACHMENT_ERROR = array("message"=>"Error in attachment.","statusCode"=>'1');
}

?>


