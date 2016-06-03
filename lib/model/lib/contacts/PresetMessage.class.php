<?php
/**
 *CLASS PresetMessage
 * We gives user the priviledge of having some preset messages for users who 
 * tries to make any contact or accept/decline. This class provide him/her the correct preset
 * message based on type of contact they have b/w them
 * 
 * <code>
    * ***Fetch accept preset message
    *  PresetMessage::getAcceptMes($profileObj)
    * 
		* ***Fetch decline preset Message
		* 
		* PresetMessage::getDeclineMes($profileObj)
		* 
		* ***Fetch Eoi preset message
		* 
		* PresetMessage::getEoiMes($profileObj)
		* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */

class PresetMessage
{
	/**
	 * constant
	 */
		const USERNAME="USERNAME";
		const TYPE_OF_POST="TYPE_OF_POST";
		const SONS="son's";
		const DAUGHTERS="daughter's";
		const MALE="M";
		const PARENTS="2";
		const HISHER="HISHER";
	/**
	 * Attributes
	 */
/**
 * return who is the action performer[son/daugher]
 * @param Profile $profileObj
 * @return String 
 * @throws JsException
 */
	public static function type_of_post($profileObj)
	{
		if(!($profileObj instanceof  Profile))
			throw new JsException("",Messages::NO_PROFILE_OBJ);
		
		if($profileObj->getGENDER()==self::MALE)
				return self::SONS;
		else
				return self::DAUGHTERS;
				
	}
/**
 * return accept preset message
 * @param Profile $profileObj
 * @return String 
 * @throws JsException
 */
	public static function getAcceptMes($profileObj)
	{
		if(!($profileObj instanceof  Profile))
			throw new JsException("",Messages::NO_PROFILE_OBJ);
			
		$message="";
		if($profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())//If paid
		{
				
				/*if($profileObj->getRELATION()==self::PARENTS)//If Parent
					$message=Messages::getMessage(Messages::ACCEPT_PRESET_PAID_PARENT);
				else*/
					$message=Messages::getMessage(Messages::ACCEPT_PRESET_PAID_SELF);
		}
		else
			$message=Messages::getMessage(Messages::ACCEPT_PRESET_FREE,array(self::USERNAME=>$profileObj->getUSERNAME()));
		return $message;
				
	}
/**
 * return decline preset messages
 * @param Profile $profileObj
 * @return String 
 * @throws JsException
 */
	public static function getDeclineMes($profileObj)
	{
		if(!($profileObj instanceof  Profile))
			throw new JsException("",Messages::NO_PROFILE_OBJ);
			
		$message="";
		//If Paid and parent
		/*if($profileObj->getRELATION()==self::PARENTS && $profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
		{
				$message=Messages::getMessage(Messages::DECLINE_PRESET_PAID_PARENT,array(self::TYPE_OF_POST=>self::type_of_post($profileObj)));
		}
		else*/
			$message=Messages::getMessage(Messages::DECLINE_PRESET_FREE_PAID);

		return $message;
	}
/**
 * return EOI preset messages
 * @param Profile $profileObj
 * @return String 
 * @throws JsException
 */
	public static function getEoiMes($profileObj)
	{
		if(!($profileObj instanceof  Profile))
			throw new JsException("",Messages::NO_PROFILE_OBJ);
			
		$message="";
		if($profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
		{
				/*if($profileObj->getRELATION()==self::PARENTS)
					$message=Messages::getMessage(Messages::EOI_PRESET_PAID_PARENT,array(self::TYPE_OF_POST=>self::type_of_post($profileObj),self::USERNAME=>$profileObj->getUSERNAME()));
				else*/
					$message=Messages::getMessage(Messages::EOI_PRESET_PAID_SELF,array(self::USERNAME=>$profileObj->getUSERNAME()));
		}
		else
			$message=Messages::getMessage(Messages::EOI_PRESET_FREE,array(self::USERNAME=>$profileObj->getUSERNAME()));
		
		return $message;
	}
	public static function getSystemPreset($profileObj)
	{
		if(!($profileObj instanceof  Profile))
                        throw new JsException("",Messages::NO_PROFILE_OBJ);
		
		$message="";
		if($profileObj->getGENDER()=="M")
			$hisher="his";
		else
			$hisher="her";
			
		$message=Messages::getMessage(Messages::EOI_SYSTEM_PRESET_FREE,array(self::HISHER=>$hisher));
		return $message;

	}
	
	public static function getPresentMessage($profileObj,$actionType)
	{
		switch($actionType){
			case ContactHandler::ACCEPT:
				$draft = self::getAcceptMes($profileObj);
				break;
			case ContactHandler::DECLINE:
				$draft = self::getDeclineMes($profileObj);
				break;
			case ContactHandler::INITIATED:
			case ContactHandler::REMINDER:
				$draft = self::getEoiMes($profileObj);
				break;
			default:
				$darft = self::getSystemPreset($profileObj);
				break;
		}
		return $draft;
	}	
}
?>
