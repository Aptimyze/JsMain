<?php

/**
 * screening action
 *
 * @package    jeevansathi
 * @subpackage Document Screening
 * @author     Bhavana Kadwal
 * @version    
 */
class screeningActions extends sfActions {

        const DEFAULT_AVOID_REFRESH_TIME = 2;

        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeIndex(sfWebRequest $request) {
                
        }

        public function executeScreenDocument(sfWebRequest $request) {
                $this->execName = $name;
                $this->cid = $request->getParameter("cid");
                $this->name = $request->getAttribute('name');
                $this->execName = $this->name;
                $inputArr = $request->getParameterHolder()->getAll();

                //start: memcache functionality implemented to avoid user refreshing the page
                if ($_GET['skipMemcache'] != 1) {
                        $key = "PROFILE_VERIFICATION_" . $this->name;

                        if (JsMemcache::getInstance()->get($key)) {
                                JsMemcache::getInstance()->set($key, $this->name, 5);
                                //exit("Please refresh after 5 seconds.");
                        } else
                                JsMemcache::getInstance()->set($key, $this->name, 5);
                }
                $arr = $request->getParameterHolder()->getAll();

                //Allotment
                $objDoc = new ProfileDivorcedDocumentScreening();
                $profileObj = new Operator;
                $fetchProfileAllocatinArr = $objDoc->fetchProfileToAllot($this->name);
                $infoArr = array();
                if($fetchProfileAllocatinArr)
                {
                        $pid = $fetchProfileAllocatinArr["PROFILEID"];
                        $profileObj->getDetail($pid,"PROFILEID",'USERNAME,MSTATUS');
                        $this->username = $profileObj->getUSERNAME();	
                        $this->prevMstatus = $profileObj->getMSTATUS();	
                        if($fetchProfileAllocatinArr["updateAllotTime"])
				$objDoc->allotProfile($pid,$this->name);
                        
                        $infoObj = new CriticalInfoChangeDocUploadService();
                        $infoArr = $infoObj->getDocumentsList($pid);
                        $this->profileid = $pid;
                }else{
                        $this->noProfileFound = 1;
                }
                
                if (!empty($infoArr)) {
                        $this->documentURL = PictureFunctions::getCloudOrApplicationCompleteUrl($infoArr["DOCUMENT_PATH"]);
                        $urlOri = PictureFunctions::getCloudOrApplicationCompleteUrl($infoArr["DOCUMENT_PATH"],true);
                        $contentType = "image";
                        $fileExt = explode(".",$urlOri);
                        if(end($fileExt) == 'pdf' || end($fileExt) == 'PDF'){
                        		$contentType = "pdf";
                        }
                        $this->contentType = $contentType;
                        $this->documentPath = $infoArr["DOCUMENT_PATH"];
                } else {
                        $this->documentURL = "";
                        $this->documentPath = "";
                }
        }
        public function executeUploadScreenDocument(sfWebRequest $request){
                $pid= $_POST["profileid"];
                $name= $_POST["username"];
                $documentPath= $_POST["docPath"];
                $status = "F";
                if($_POST["docVerified"] == "APPROVE"){
                        $status = "Y";
                }
                $cDocObj = new CriticalInfoChangeDocUploadService();
                $cDocObj->updateStatus($pid,$status);
                if($status == "Y"){
                        $jprofileObj = new JPROFILE();
                        $paramArr = array("MSTATUS"=>"D");
                        $jprofileObj->edit($paramArr, $pid, "PROFILEID");
                }
                $logObj = new CRITICAL_INFO_DOC_SCREENED_LOG();
                $logObj->insert($pid, $name, $status, $documentPath);
                
                $changedFieldsObj = new newjs_CRITICAL_INFO_CHANGED();
                $changedFieldsObj->updateStatus($pid, "Y");
                $objDoc = new ProfileDivorcedDocumentScreening();
                $objDoc->del($pid,$name);
                
                $mailer = new CriticalInformationMailer($pid,array("MSTATUS"=>"D","PREV_MSTATUS"=>$_POST["prevMstatus"]));
                $mailer->sendSuccessFailMailer($status);
                $this->redirect('/operations.php/screening/screenDocument?name='.$name.'&cid='.$_POST["cid"]);
        }

}

?>
