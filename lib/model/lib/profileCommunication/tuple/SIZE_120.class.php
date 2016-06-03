<?php
/**
 * @brief This class is child class of tuple for tuple having big profile pic thumbnail with no username
 * @author Reshu Rajput
 * @created 2013-12-13
 */
class SIZE_120 extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields = Array("PROFILEID", "GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "SUBCASTE", "EDUCATION", "INCOME", "IS_ALBUM", "ACTIVATED","IS_ALBUM_TEXT","ProfilePic120Url");

    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
