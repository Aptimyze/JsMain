<?php
/**
 *CLASS ContactPrivilege
 * The Privilege users share b/w them
 * 
 *<BR>
 * How to call this file<BR> 
 * <code>
    * $priv=ContactPrivilege::getPrivilegeArray(ContactHandler $contactHandlerObj)
* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author   Nitesh
 * @copyright 2012 
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class ContactPrivilege
{
	/**
	* Return privilege that user share b/w them
	* @return Array <format>array("MESSAGE"=>"N","DROPDOWN"=>"N")</format>
	*/
	public static function getPrivilegeArray($contactHandlerObj)
	{
		$privilegeData = ContactEngineMap::getFieldLabel("privilege_data","",1);
		
		// variables :

		$loggedInProfileObj=$contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$otherProfileObj=$contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$senderReciever=$contactHandlerObj->getContactInitiator();
		$contactStatus=$contactHandlerObj->getContactObj()->getTYPE();
		$engineType=$contactHandlerObj->getEngineType();
		
		$j=0;
//		echo $contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()." ".$contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()." ".$contactHandlerObj->getContactInitiator()." ".$contactHandlerObj->getContactObj()->getTYPE()." ".$contactHandlerObj->getEngineType();
		foreach($privilegeData as $key=>$val)
		{
			if($val['LOGGEDINPROFILE']==$loggedInProfileObj &&
			 $val['OTHERPROFILE']==$otherProfileObj &&
			  $val['SENDER_RECIEVER']==$senderReciever &&
			   $val['CONTACT_STATUS']==$contactStatus &&
			   $val['CONTACT_TYPE']==$engineType)
			   {
				$arr[0][$val['ACTION_TYPE']][$val['PRIVILEGE']] = $val['ALLOWED'];
						if($val['ACTION_TYPE']!=$actionType)
						{
							$actionType=$val['ACTION_TYPE'];
							$arr[1][$j]=$actionType;
							$j++;
						}
				}
		}
	return $arr;	
	}
}
