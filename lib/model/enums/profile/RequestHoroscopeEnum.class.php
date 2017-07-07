<?php

class RequestHoroscopeEnum {

        public static $SUCCESS = array("message" => "Successful", "statusCode" => '0');
        public static $REQUEST_SENT = array("message" => "Your request to #USERNAME# for a horoscope has been made.", "statusCode" => '0');
        public static $FAILURE = array("message" => "Something went wrong. Please try again later.", "statusCode" => '1');
        public static $PROFILE_NOT_EXISTS = array("message" => "Profile not exists please try later", "statusCode" => '1');
        public static $ADD_YOUR_HOROSCOPE = array("message" => "You can add your own horoscope to your profile", "statusCode" => '1');
        public static $UPLOAD_HOROSCOPE_DETAILS = array("message" => "Please upload your horoscope details", "statusCode" => '1');
        public static $BUY_ASTRO_SERVICE = array("message" => "Buy Astro Compatability Services", "statusCode" => '1');
        public static $ALREADY_REQUESTED = array("message" => "You have already made request to #USERNAME# for a Horoscope.", "statusCode" => '1');
        public static $SAMEGENDER_ERROR = array("message" => "Sorry! You cannot request #USERNAME# for a Horoscope as your gender is the same as that of #USERNAME#.", "statusCode" => '1');
        public static $FILTERED_ERROR = array("message" => "Sorry! You cannot request #USERNAME# for a Horoscope as you have been filtered out by #USERNAME#.", "statusCode" => '1');

        public static function getErrorByField($field, $search = "", $replace = "") {
                $var_name = $field;
                $errMsg = self::$$var_name;
                $errMsg["message"] = str_replace($search, $replace, $errMsg['message']);
                return $errMsg;
        }

}
?>


