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
		if($this->privilegeArray[0]['COMMUNICATION']['MESSAGE']=='P')
		{ 
			$dbName = JsDbSharding::getShardNo($contactHandler->getViewer()->getPROFILEID());
			$dbMessageLogObj=new NEWJS_MESSAGE_LOG($dbName);
			$messages=$dbMessageLogObj->getPaidMemberCommunicationHistory($contactHandler->getViewer()->getPROFILEID(),$contactHandler->getViewed()->getPROFILEID());
			$presetMessage[] = str_ireplace("{{USERNAME}}",$contactHandler->getViewer()->getUSERNAME(),Messages::ACCEPT_PRESET_PAID_SELF);
			$presetMessage[] = str_ireplace("{{USERNAME}}",$contactHandler->getViewer()->getUSERNAME(),Messages::ACCEPT_PRESET_FREE);
			$presetMessage[] = str_ireplace("{{USERNAME}}",$contactHandler->getViewed()->getUSERNAME(),Messages::ACCEPT_PRESET_PAID_SELF);
			$presetMessage[] = str_ireplace("{{USERNAME}}",$contactHandler->getViewed()->getUSERNAME(),Messages::ACCEPT_PRESET_FREE);
			
			foreach($messages as$k=>$val)
			{
				$message=$val['MESSAGE'];
				$messageCmp = trim(html_entity_decode($message,ENT_QUOTES));
				if(!in_array($messageCmp,$presetMessage))
				{
					$this->privilegeArray[0]['COMMUNICATION']['MESSAGE']="Y";
				}
				else{
					$message = null;
				}
			}
			if($this->privilegeArray[0]['COMMUNICATION']['MESSAGE']=='P'){
				
				$dbObj = new newjs_CHAT_LOG($dbName);
				if($dbObj->getChatCount($contactHandler->getViewer()->getPROFILEID(),$contactHandler->getViewed()->getPROFILEID()))
					$this->privilegeArray[0]['COMMUNICATION']['MESSAGE']='Y';
				else
					$this->privilegeArray[0]['COMMUNICATION']['MESSAGE']='N';
			}
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
		
