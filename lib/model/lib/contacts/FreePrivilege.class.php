<?php
/**
 * FreePrivilege contains get, update methods to calculate individual profile Privileges.
 * 
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-09 nitesh.s $
 */

class FreePrivilege extends Privilege
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
* calculate Privilege of a Free Member, calls the getPrivilege of Privilege class
* @param ContactHandler 
* @uses NEWJS_CONTACT_PRIVILEGE
* @return void 
* @access public
*/
	public function updatePrivilege(ContactHandler $contactHandler)
	{		
		$this->privilegeArray= $this->getPrivilege($contactHandler);
				
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
		
