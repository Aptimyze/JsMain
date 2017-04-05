<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AlterSeenV1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
	function execute($request){
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")!=="accept")
		{
			$this->loginData    = $request->getAttribute("loginData");
			//Contains logined Profile information;
			$this->loginProfile = LoggedInProfile::getInstance();
      if($this->loginProfile->getAGE()== "")
        $this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
			
			if ($this->loginProfile->getPROFILEID()) {
				$this->userProfile = $request->getParameter('profilechecksum');
				if ($this->userProfile) {
					
					$this->Profile = new Profile();
					$profileid     = JsCommon::getProfileFromChecksum($this->userProfile);
					$this->Profile->getDetail($profileid, "PROFILEID");
					$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					$type = $this->contactObj->getTYPE();
					$sender = $this->contactObj->getSenderObj()->getPROFILEID();
					$mypid = $this->loginProfile->getPROFILEID();
					$fromSym=1;
					if(($sender==$mypid && ($type=='A' OR $type=='D')) || ($sender!=$mypid && ($type=='I' )))
						$updatecontact=1;
					if($mypid!=$profileid && $this->loginProfile->getGENDER()!=$this->Profile->getGENDER())
					{
        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/alterAction.txt",var_export($_SERVER,true)."\n",FILE_APPEND);
						include(sfConfig::get("sf_web_dir")."/profile/alter_seen_table.php");
					}
				}
			}
		}
		die;
	}
}
