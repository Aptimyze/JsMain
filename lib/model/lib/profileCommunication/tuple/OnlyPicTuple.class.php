<?php
/**
 * @brief This class is child class of tuple for tuple having only thumbnail pic 
 * @author Reshu Rajput
 * @created 2013-12-13
 */
class OnlyPicTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields = Array("PROFILECHECKSUM","ThumbailUrl");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
