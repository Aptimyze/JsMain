<?php
class jsDatabaseException extends Exception {
  public function __construct($message = null, $code = 0) {
    parent::__construct($message, $code);
    LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$this,array(LoggingEnums::TYPE_OF_ERROR=>LoggingEnums::PDO_EXCEPTION));	
  }
}
?>
