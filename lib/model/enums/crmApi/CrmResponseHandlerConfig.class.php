<?php
class CrmResponseHandlerConfig
{
	public static $EXPIRED_AGENT = array("message"=>"Your account has expired, please contact your manager","statusCode"=>'2');
    public static $AGENT_LOGIN_FAILURE = array("message"=>"Invalid login details provided","statusCode"=>'1');
    public static $AGENT_LOGIN_SUCCESS=array("message"=>"login successful","statusCode"=>'0');
    public static $INVALID_BACKEND_CHANNEL=array("message"=>"Invalid channel for request","statusCode"=>'4');
    public static $LOGOUT_AGENT = array("message"=>"Please login to continue.","statusCode"=>'3');
    public static $AGENT_AUTHENTICATION_FAILURE = array("message"=>"Please login again to continue.","statusCode"=>'3');
    public static $NO_SYNC_DATA = array("message"=>"No files to sync","statusCode"=>'1');
    public static $CRM_SYNC_SUCCESS =  array("message"=>"Sync successful","statusCode"=>'0');
    public static $CRM_SYNC_FAILURE =  array("message"=>"Sync Failed","statusCode"=>'1');
    public static $INVALID_USERNAME = array("message"=>"Invalid Username","statusCode"=>'1');
    public static $VALID_USERNAME = array("message"=>"Valid Username", "statusCode"=>'0');
    public static $MISSING_PARAM = array("message" => "Missing Parameter", "statusCode" => '1');
    public static $EDIT_PROFILE_SUCCESS = array("message" => "Edit successful", "statusCode" => '0');
    public static $INVALID_FILE_FORMAT = array("message" => "Invalid file,please upload a valid file", "statusCode" => '1');
    public static $CRM_LOGOUT_SUCCESS =  array("message"=>"logout successful","statusCode"=>'0');
    public static $CRM_LOGOUT_FAILURE =  array("message"=>"logout failed,please try again","statusCode"=>'1');
    public static $CRM_SUCCESS=array("message"=>"Successful","statusCode"=>'0');
    public static $CRM_FAILURE=array("message"=>"Failed","statusCode"=>'1');
    public static $INVALID_VISIT_REQUEST=array("message"=>"Duplicate visit request","statusCode"=>'2');
}
?>