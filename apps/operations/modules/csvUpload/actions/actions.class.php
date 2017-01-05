<?php

/**
 * csvUpload actions.
 *
 * @package    jeevansathi
 * @subpackage csvUpload
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class csvUploadActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  	public function executeIndex(sfWebRequest $request)
  	{
    		$this->forward('default', 'module');
  	}
        public function executeUploadNotificationCsv(sfWebRequest $request){
                $this->cid = $request->getParameter('cid');
                $upload = $request->getParameter('upload');
                $fileParam = $request->getFiles('uploaded_csv');

                $agentAllocDetailsObj =new AgentAllocationDetails();
                $privilege   =$agentAllocDetailsObj->getprivilage($this->cid);
                $privilegeArr =explode("+", $privilege);

                if(!in_array("IA",$privilegeArr)){
                        $this->unAuthorized =1;
                }
                else if($upload =='Upload'){
                        $fileTemp = $fileParam['tmp_name'];
                        $fileName = $fileParam['name'];
                        $fileType = $fileParam['type'];

                        if(substr($fileName ,-3,3) != "csv"){
                                $this->invalidFile =1;
                        }
                        else{
                                $notificationCsvObj =new MOBILE_API_CSV_NOTIFICATION_TEMP('newjs_masterDDL');
                                $notificationCsvObj->truncate();
                                $status =$notificationCsvObj->insertRecord($fileTemp);
				if($status){
	                                $this->successful =1;
                                	// Execution in background to send CSV Notification
                                	$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony smsNotification:sendCsvNotifications >/dev/null &";
					$command = preg_replace('/[^A-Za-z0-9\. -_>&]/', '', $command);
                                	passthru($command);
				}
                        }
                }
                $this->setTemplate('notificationCsv');
        }

}
