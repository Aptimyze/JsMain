<?php

#include_once("MessageVariables.class.php");
//include_once("ProfileHandler.class.php");

class VariableProcessingClassFactory {
  public static function createObject($var_object) {

    $object = null;
    switch($var_object->getVariableProcessingClassCode()) {
      case 2:
        $object = new ProfileHandler($var_object);
        break;

      case 3: 
        $object = new LinkHandler($var_object);
        break;

      case 4: 
        $object = new PhotoHandler($var_object);
        break;

      case 5: 
        $object = new FtoHandler($var_object);
        break;

      case 6: 
        $object = new CrmHandler($var_object);
        break;

      default:
        throw new TypeNotDefinedException('Processing Class Code: ' . var_dump($var_object).$var_object->getVariableProcessingClassCode() . ' not defined.');
        break;
    }
    return $object;
  }
}
