<?php

class editDppInterfaceAction extends sfActions {

	
        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeEditDppInterface(sfWebRequest $request) {
					
							$this->cid = $request->getParameter("cid");
              $profileChecksum = $request->getParameter('profileChecksum');
             
							if($profileID)
							{
								$this->profile = Operator::getInstance("", $profileID);
								
							}
							
							
							if($profileChecksum)
							{
								$tempPid = JsAuthentication::jsDecryptProfilechecksum($profileChecksum);
								if($tempPid)
									$this->profile = Operator::getInstance("",$tempPid);
								else
								{
									echo "Invalid ID";
									die;
								}
							}
												
						global $protect;
						JsCommon::oldIncludes();
						$protect = new protect();
						$protect->logout();
						$checksum = md5($this->profile->getPROFILEID()) . "i" . $this->profile->getPROFILEID();
						$echecksum = $protect->js_encrypt($checksum);
						$this->profileid = $this->profile->getPROFILEID();
						$this->autologinUrl="http://www.jeevansathi.com/profile/dpp?allowLoginfromBackend=1&profileChecksum=" . $profileChecksum . "&checksum=" . $checksum."&cid=".$this->cid;
						$this->redirect($this->autologinUrl);
				}	
}

?>
