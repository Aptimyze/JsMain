<?php
/**
 * @brief This class is child class of tuple for tuple having featured Profile data
 * @author Sanyam Chopra
 * @created 2016-08-08
 */
class featuredProfileDetailsTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("USERNAME", "GENDER","EMAIL", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "INCOME", "CITY", "IS_ALBUM", "SUBSCRIPTION","ACTIVATED","IS_ALBUM_TEXT","SearchPicUrl","edu_level_new","MOBPHOTOSIZE","INTEREST_VIEWED_DATE","SENT_MESSAGE","NATIVE_CITY");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
