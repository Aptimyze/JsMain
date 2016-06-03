<?php
/**
 *CLASS ContactError
 * Fetch relevant Array of functions that need to be
 * called based on the type of contact user has
 * 
 * <code>
    * ***Fetch array of error function to call
    *  PresetMessage::getAcceptMes($profileObj)
    * 
		* ***Fetch decline preset Message
		* 
		* $contactErrorObj=new ContactError();
		* $contactErrorObj->getErrorValues(ContactHandler $contactHandlerObj);
		* 
		* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class ContactError
{
	public  $errorData;
	/**
	* Initialize errorData variale.
	* @throws JsException
	*/
 	public function  __construct()
	{
		$this->errorData = ContactEngineMap::getFieldLabel("error_data","",1);
		
	}
		/**
 * Fetch array of functions name that need to be called
 * @param ContactHandler $contactHandlerObj
 * @throws JsException
 */
	public function getErrorValues($contactHandlerObj)
	{
		try{
			
		$tempArr['SENDER_RECEIVER']=$contactHandlerObj->getContactInitiator();
		$tempArr['CONTACT_TYPE']=$contactHandlerObj->getContactObj()->getTYPE();
		$tempArr['ENGINE_TYPE'] = $contactHandlerObj->getEngineType();
		
		$error = array();
		foreach($this->errorData as $val)
		{
			$arr = array_diff_assoc($tempArr,$val);
			if(empty($arr))
				 $error[]=$val['ERROR'];
		}
		
		return $error;
	}
	catch(Exception $ex)
	{
		throw new JsException("Error in contact error");
	}
	}
}
?>
