<?php
/**
 * ProfileFieldsLogging
 * 
 * This class logs the error in dev log when a Profile member field is incorrectly called from profile objet
 * @package    jeevansathi
 * @author     nitesh sethi
 * @created    12-02-2013
 */

class ProfileFieldsLogging{

        /**
	 * @fn callFieldStack
	 * @brief print stack trace if the called field doesnit exist in fields array.
	 * @return void
	*/
	public static function callFieldStack($stacktrace) {
		return;
		 $i = 1;
		 $str="Profile Field ERROR ::\n";
        foreach($stacktrace as $node) {
            $str.= "$i  ".$node['file']." : ".$node['function'] ."(" .$node['line'].")\n";
            $i++;	        					        
        }
        sfContext::getInstance()->getLogger()->info($str);		
    } 
}
