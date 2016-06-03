<?php
/**
 * @brief This class is main factory class for all the profile communication related tuples
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class TupleFactory {
    /* This function will return the object of required module
     *@param tuple : tuple name
     *@return classobj : gets the class name from enum PROFILE_COMMUNICATION_ENUM and return its obj
    */
    public function getTuple($tuple) {
        $className = PROFILE_COMMUNICATION_ENUM_INFO::getClass($tuple);
        return new $className;
    }
}
?>
