<?php
/**
 * Abstract Privilege contains get and abstract methods to calculate individual profile Privileges.
 * 
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-09 nitesh.s $
 */

abstract class Privilege{
	
	
	const N="NOCONTACT";
	const I="INITIATE_CONTACT";
	const R = "SEND_REMINDER";  
	const E="EOI_CANCEL";
	const A="ACCEPT";
	const D="DECLINE";
	const C="CANCEL";
	const CONTACT_DETAIL="CONTACT_DETAIL";
	const CALL_DIRECT="CALL_DIRECT";
	const M="COMMUNICATION";
	
/**

* Returns Privilege of a user by calling getPrivilege of NEWJS_CONTACT_PRIVILEGE class
* @param  ContactHandler
* @param NEWJS_CONTACT_PRIVILEGE
* @uses NEWJS_CONTACT_PRIVILEGE::getPrivilege()
* @return PrivilegeArray
* @access public
*/		
		
	public function getPrivilege(ContactHandler $contactHandler)
	{
			//$contactPrivilegeArray= $dbPrivilegeObj->getPrivilege($contactHandler);
			$contactPrivilegeArray=ContactPrivilege::getPrivilegeArray($contactHandler);
			return $contactPrivilegeArray;
	}
/**
* abstract function every base class contains the respective definition
* @param ContactHandler
* @return void
* @access public
*/
	abstract public function updatePrivilege(ContactHandler $contactHandler);
	
}
		
