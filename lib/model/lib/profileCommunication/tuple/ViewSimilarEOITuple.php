<?php
/**
 * @brief This class is child class of tuple for tuple having View Similar profile details pic thumbnail
 * @author Akash Kumar
 * @created 15 July 2014
 */
class ViewSimilarEOITuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("USERNAME", "PROFILEID", "IS_ALBUM","GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "CASTE", "INCOME", "CITY_RES", "EDU_LEVEL_NEW", "HAVEPHOTO", "PHOTO_DISPLAY", "IS_PHOTO_REQUESTED", "PROFILECHECKSUM", "LAST_LOGIN_DT", "IS_BOOKMARKED","edu_level_new","NATIVE_CITY");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
