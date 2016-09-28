<?php
class jsException extends PDOException{
        public function __construct($exceptionObj = "", $message = null, $trace=0, $code=0) {
        
		if($message){
			$this->message = $message;
			parent::__construct(self::getCustomMessage($this, $trace),$code);			
						jsException::log($message);
		}
		else{
			 parent::__construct(self::getCustomMessage($exceptionObj, $trace),$code);
			if($exceptionObj)
			{     		
				jsException::log($exceptionObj->getMessage()."\n".$exceptionObj->getTraceAsString());
			}
		}
		self::checkCE();
		// code for exception object. 
    if ( $exceptionObj && $code === 0 )
    {
      $code = $exceptionObj->getCode();
    }
		if ( $exceptionObj != "")
		{
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$exceptionObj,array(LoggingEnums::MESSAGE => $message));
		}
		else
		{
			$this->message = $message;
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$this);
		}
        }
        static function checkCE()
        {
                if(Messages::getCeCalled())
                        {
                                $table='<div class="ce_357">
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs15">
An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.
</div>  
<script>        
</script>       
</div>  
';
			
                        }

        }

	static function log($message){
		sfContext::getInstance()->getLogger()->err($message);
	}

	static function nonCriticalError($message){
		$message = "nonCriticalError:".$message;
		self::log($message);
	}

	static function getCustomMessage($exceptionObj, $trace){
		if($trace) return $exceptionObj;
		else return $exceptionObj->getMessage()." in ".$exceptionObj->getFile().": ".$exceptionObj->getLine();
	}
	
}
?>
