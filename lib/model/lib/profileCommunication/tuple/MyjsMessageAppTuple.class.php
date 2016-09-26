<?php
/**
 * @brief This class is child class of tuple for tuple having big profile pic thumbnail
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class MyjsMessageAppTuple extends Tuple {
    /* It defines all the fields and icons for a particular tuple */
    static public function init() {
        self::$fields =Array("USERNAME", "ThumbailUrl","LAST_MESSAGE","COUNT","NATIVE_CITY","NAME_OF_USER");
    }
    /* This function returns fields and icons configurations of the tuple*/
    static public function getFields() {
        self::init();
        return self::$fields;
    }
}
?>
