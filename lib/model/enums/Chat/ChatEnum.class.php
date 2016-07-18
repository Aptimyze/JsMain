<?php

class ChatEnum {
    public static $loggedOutProfile = array("message"=>"Logged out profile","statusCode"=>'0');
    public static $userExists = array("message"=>"User Exists","statusCode"=>'0');
    public static $newUserCreated = array("message"=>"New user created","statusCode"=>'0');
    public static $userCreationError = array("message"=>"Error creating user","statusCode"=>'1');
    public static $error = array("message"=>"Some Error Occurred","statusCode"=>'1');
    public static $invalidFormat = array("message"=>"Invalid format of jid","statusCode"=>'1');
}
