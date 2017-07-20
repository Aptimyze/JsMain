<?php

/**
 * jsexclusive actions.
 *
 * @package    jeevansathi
 * @subpackage jsexclusive
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jsexclusiveActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeMenu(sfWebRequest $request) {
        //Get Count for each option 
        $agent = $request['name'];
        //Counter for welcome calls
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $this->welcomeCallsCount = $exclusiveServicingObj->getWelcomeCallsCount($agent);
    }

    public function executeWelcomeCalls(sfWebRequest $request) {

        $agent = $request['name'];
        //Get all clients here
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $this->welcomeCallsProfiles = $exclusiveServicingObj->getClientsForWelcomeCall('CLIENT_ID', $agent, 'ASSIGNED_DT');
        $this->welcomeCallsProfilesCount = count($this->welcomeCallsProfiles);
    }

    public function executeWelcomeCallsPage2(sfWebRequest $request) {

        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];
        $this->profileChecksum= JsOpsCommon::createChecksumForProfile($this->client);
        //Get all clients here
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
    }
    
    public function executeSetClientServiceDay(sfWebRequest $request) {

        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];
        $submit = $request['submit'];
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $serviceDayArr = $exclusiveServicingObj->getServiceDay($this->client);
        $this->serviceDay = $serviceDayArr[0];
        $this->serviceDaySetDate = $serviceDayArr[1];
        if($submit){
            $this->serviceDay = $request['serviceDay'];
            $this->serviceDaySetDate = date('Y-m-d');
            $exclusiveServicingObj->setServiceDay($this->client,$this->serviceDay);
        }
        
        
    }

    public function executeUploadBiodata(sfWebRequest $request) {
/* Key for:
 * $this->invalidFile:    1- Size exceeds 5 MB
 *                  2- Invalid file type
 *                  3- General Processing error
 */
        //Location where file will be uploaded and downloaded from
        $fileLocation = "/var/www/html/branch2/web/uploads/temp/"; 
        //Max size of file allowed
        $maxsize = 5 * 1024 * 1024;
        //Allowed File Types:
        $allowedExtension=array('pdf','rtf','doc','docx','jpg','jpeg','txt');
        $this->allowedExtension = implode(", ", $allowedExtension);
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];
        $action = $request['useraction'];
        if ($action == "") {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata[0];
            $biodataUploadDate = $biodata[1];
            if ($biodataLocation == NULL || $biodataLocation == "") {
                $this->freshUpload = true;
            } else {
                $this->freshUpload = false;
            }
        } else if ($action == 'deleteBioData') {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata[0];
            if(unlink($biodataLocation)){
                $this->deleteStatus = $exclusiveServicingObj->deleteBioData($this->client);
                $this->freshUpload = true;
            }else{
                $this->invalidFile = 3;
                $this->freshUpload = false;
            }
        } else if ($action == 'viewBioData') {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata[0];
            $ext = end(explode('.', $biodataLocation));
            $file = "BioData-$this->client.".$ext;
            $xlData=file_get_contents($biodataLocation);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            echo $xlData;
            die;
        } else if ($action == 'uploadBioData') {
            $upload = $request->getParameter('upload');
            $fileParam = $request->getFiles('uploaded_csv');
            $csvUpload = $request->getParameter($csvUpload);
            if ($upload == 'Upload') {
                $fileTemp = $fileParam['tmp_name'];
                $fileName = $fileParam['name'];
                $fileType = $fileParam['type'];
                $filesize = $_FILES["photo"]["size"];
                $ext = end(explode('.', $fileName));
                if ($filesize > $maxsize)
                    $this->invalidFile = 1;
                if (!in_array($ext, $allowedExtension)) {
                    $this->invalidFile = 2;
                } else {
                    $location = $fileLocation . $_FILES['uploaded_csv']['name'];
                    if (move_uploaded_file($_FILES['uploaded_csv']['tmp_name'], $location)) {
                        $exclusiveServicingObj->setBioDataLocation($this->client, $location);
                        $this->uploadSuccess = true;
                        $this->freshUpload = false;
                    } else {
                        $this->invalidFile = 3;
                    }
                }
            }
        }
    }

}
