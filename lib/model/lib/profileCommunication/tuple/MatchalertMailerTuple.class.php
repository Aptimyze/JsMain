<?php
/**
 * @brief This class is child class of tuple for matchalert mailer
 * @author Reshu Rajput
 * @created 20 May 2014
 */
class MatchalertMailerTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("PROFILEID", "USERNAME", "GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "SUBCASTE", "EDUCATION", "INCOME", "CITY","SearchPicUrl","YOURINFO","ACTIVATED","edu_level_new");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
