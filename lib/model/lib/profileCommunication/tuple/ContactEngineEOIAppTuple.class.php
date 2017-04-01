<?php
/**
 * @brief This class is child class of tuple for tuple having big profile pic thumbnail
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class ContactEngineEOIAppTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("USERNAME", "GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "INCOME", "CITY", "IS_ALBUM", "SUBSCRIPTION","ACTIVATED","IS_ALBUM_TEXT","SearchPicUrl","MESSAGE","edu_level_new","MOBPHOTOSIZE","NATIVE_CITY","NAME_OF_USER");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
