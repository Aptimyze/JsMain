<?php
/**
 * CLASS FTOPrivilege
 * FTOPrivilege class contains get, update methods to calculate individual FTO profile Privileges..
 * 
 *  * Code to fetch FTO profile Privilege
 * <code>
 * $privilegeObj=new FTOPrivilege();
 * //to fetch Privilege
 * $privilegeObj->updatePrivilege(ContactHandler $contactHandlerObj);
 * </code>
 * PHP versions 4 and 5	
 * @package   FTO
 * @author    Pankaj Khandelwal <pankaj.khandelwal@jeevansathi.com>
 * @copyright 2012 Pankaj Khandelwal
 * @version   SVN: 
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
 */

	class FTOPrivilege extends Privilege
	{
		/**
	 * Attributes
	 */
		private $privilegeArray;

/**
* updatePrivilege
* calculate Privilege of a FTO Member, calls the getPrivilege of Privilege class
* @param ContactHandler $contactHandler 

* @return array with privileges 
* @access public
*/
		
		public function updatePrivilege(ContactHandler $contactHandler)
		{
			$this->privilegeArray = $this->getPrivilege($contactHandler);
			if($this->privilegeArray[0]["COMMUNICATION"]["MESSAGE"] == 'P')
			{
				$writePrivilege = $this->getCorrectPrivilege($contactHandler);
				if($writePrivilege == true){
					$this->privilegeArray[0]["COMMUNICATION"]["MESSAGE"] = "Y";
				}
				else {
					$this->privilegeArray[0]["COMMUNICATION"]["MESSAGE"] = "N";
				}
			}
			if($this->privilegeArray[0]["CONTACT_DETAIL"]["VISIBILITY"] == 'P')
			{
				$viewPrivilege = $this->getCorrectPrivilege($contactHandler);
				if($viewPrivilege == true){
					$this->privilegeArray[0]["CONTACT_DETAIL"]["VISIBILITY"] = "Y";
				}
				else {
					$this->privilegeArray[0]["CONTACT_DETAIL"]["VISIBILITY"] = "N";				
				}
			}
		}
/**
* getPrivilegeArray
*  Returns Privilege of a FTO Member
* 

* @return Privilege Array
* @access public
*/		
		public function getPrivilegeArray()
		{
			return $this->privilegeArray;
		}
		
/**
*  getCorrectPrivilege
*  Returns true if FTO member has Privilege to perform action when status is accepted
* @param  ContactHandler !contactHandler

* @return boolen
* @access private
*/		
		private function getCorrectPrivilege (ContactHandler $contactHandler)
		{
			$FTOFlag = $contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState();
			$FTOSubState = $contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState();
			$FTOContactViewedObj = new FTO_FTO_CONTACT_VIEWED;
			if($FTOFlag == FTOStateTypes::FTO_ACTIVE || ($FTOFlag == FTOStateTypes::FTO_EXPIRED &&($FTOSubState!= E1 || $FTOSubState != E2)))
			{
				if($FTOContactViewedObj->getContactViewed($contactHandler->getViewer(),$contactHandler->getViewed()))
					$privilege = true;
				else
					$privilege = false;
			}
			else
				$privilege = false;
			return $privilege;
			
		}
	}
?>
