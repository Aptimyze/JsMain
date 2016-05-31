<?php
/**
 * @brief This class is child class of tuple for tuple having contactEngine InterestViewed and NoMesage
 * @author Reshu Rajput
 * @created 2016-05-09
 */
class ContactEngineInterestViewedNoMesageTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("USERNAME", "GENDER", "AGE", "HEIGHT", "RELIGION", "MTONGUE", "OCCUPATION", "HAVEPHOTO", "PHOTO_DISPLAY", "CASTE", "INCOME", "CITY", "IS_ALBUM", "SUBSCRIPTION","ACTIVATED","IS_ALBUM_TEXT","SearchPicUrl","edu_level_new","MOBPHOTOSIZE","INTEREST_VIEWED_DATE");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
