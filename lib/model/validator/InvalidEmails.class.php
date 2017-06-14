<?php

class InvalidEmails {

    private static $array = array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
    
    private static $sendMailArray = array("dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
    private static $mesArray = array("no", "none", "messenger id", "messenger", "gmail", "facebook", "gmail.com", "yahoo", "no id", "google", "rediffmail", "rediff", "na", "nil", "any", "good", "non", "yes", "later", "hello", "hindi", "orkut", "skype", "love", "airtel", "nothing", "face book", "i love you", "google talk");


    public static function getInvalidEmailArr() {
        return self::$array;
    }
    public static function getInvalidMessengerArr() {
		return self::$mesArray;
    }
    public static function getInvalidSendMailArr() {
		return self::$sendMailArray;
    }
}


?>
