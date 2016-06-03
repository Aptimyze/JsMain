<?php
/**
 * @brief This class is child class of tuple for tuple having small thumbnail
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class SmallIconTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields = Array("PROFILEID", "USERNAME", "GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "SUBCASTE", "EDUCATION", "INCOME", "CITY", "IS_ALBUM", "SHOW_HOROSCOPE", "PHONE_FLAG", "SUBSCRIPTION","ACTIVATED","IS_ALBUM_TEXT","ASTRO_DETAILS","ThumbailUrl","edu_level_new");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
