<?php
/**
 * PaidPrivilege contains get, update methods to calculate individual Paid profile Privileges.
 * 
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-09 nitesh.s $
 */

class PaidPrivilege extends Privilege
{
	/**
   *
   * This holds Privilege Array for the Paid profileid supplied
   *
   * @access private
   * @var array
   */
	private $privilegeArray;
/**
 * This function used to initilaize the Paid Privilege Class object.
 * @return  void
 * @access public
 */	
	public function __construct()
	{
	}
	
/**
* calculate Privilege of a Paid Member, calls the getPrivilege function of {@link Privilege class}
* @param ContactHandler 
* @uses NEWJS_CONTACT_PRIVILEGE
* @uses JSADMIN_VIEW_CONTACTS_LOG::alreadyContact()
* @return void
* @access public
*/
	public function updatePrivilege(ContactHandler $contactHandler)
	{		
		$dbVIEW_CONTACTS_OBJ= new JSADMIN_VIEW_CONTACTS_LOG();
		$this->privilegeArray= $this->getPrivilege($contactHandler);
		if($this->privilegeArray[0]['CONTACT_DETAIL']['VISIBILITY']=='P')
		{
			$alreadyContactFlag=$dbVIEW_CONTACTS_OBJ->alreadyContact($contactHandler->getViewer()->getPROFILEID(),$contactHandler->getViewed()->getPROFILEID());
					
			if($alreadyContactFlag)
				$this->privilegeArray[0]['CONTACT_DETAIL']['VISIBILITY']='Y';
			else
				$this->privilegeArray[0]['CONTACT_DETAIL']['VISIBILITY']='N';
		}		
	}
	
/**
* Returns Privilege Array of a Paid Member
* @return PrivilegeArray
* @access public
*/			
			
	public function getPrivilegeArray()
	{
		return $this->privilegeArray;
		
	}
	
	
	
	
	
}
		
