<?php

/**
 * negativeHandlerAction
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Manoj
 */
class negativeHandlerAction extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeNegativeHandler(sfWebRequest $request)
    {
	$this->actionType	=$request->getParameter('actionType');	
        $this->cid  		=$request->getParameter('cid');
        $this->name 		=$request->getParameter('name');
        $submit     		=$request->getParameter('submit');

        $negativeTreatmentObj = new negativeTreatment();

        if ($submit) {
            $this->dataArr = $request->getParameter('dataArr');
            $negVal        = $this->dataArr['negativeVal'];
            $negType       = $this->dataArr['negativeType'];
            $key           = 'negativeVal';

            // Error handling condition
            if ($negType == 'EMAIL') {
                $emailStatus = $negativeTreatmentObj->checkEmail($negVal);
                if ($emailStatus) {
                    $errorArr[$key] = 1;
                }

            } elseif ($negType == 'PROFILEID') {
                if ($negVal) {
                    $this->profileid = $negativeTreatmentObj->getProfileId($negVal);
                }

                if (!$this->profileid) {
                    $errorArr[$key] = 1;
                }

            } elseif ($negType == 'PHONE_NUM') {
                $phoneValidate = $negativeTreatmentObj->checkPhoneNumber($negVal);
                if (!$phoneValidate) {
                    $errorArr[$key] = 1;
                } else {
                    $this->dataArr['negativeVal'] = $phoneValidate;
                }

            }
            if (count($errorArr) > 0) {
                // Error handling
                $this->errorArr = $errorArr;
            } else {
                // Success handling
		if($submit=='Delete')
			$deleteAction =true;
		elseif($submit=='Fetch')
			$fetchAction =true;
                $this->successMessage = true;
            }

        }
        // Execution on delete action 
        if ($deleteAction) {
            if ($negType == 'PROFILEID') {
                $negativeVal = $this->profileid;
            } else {
                $negativeVal = $this->dataArr['negativeVal'];
            }
	    $removedStatus =$negativeTreatmentObj->removeProfileFromNegative($negType, $negativeVal);	
	    if($removedStatus){
		$this->msgContent ="Profile removed from negative list";
	    }	
	    else{			
	        $this->msgContent =$negType."- ".$dataVal." not found in negative list";
            }
        }
	// Execution on fetch action
        else if($fetchAction){
           if ($negType == 'PROFILEID') {
                $negativeVal = $this->profileid;
            } else {
                $negativeVal = $this->dataArr['negativeVal'];
            }
	    $checkArr =$negativeTreatmentObj->fetchProfileDetailsFromNegative($negType, $negativeVal);	
	    if(is_array($checkArr)){
		$dataVal =$checkArr[$negType];		
		$comment =$checkArr['COMMENTS'];
		$this->msgContent =$negType."- ".$dataVal." found in Negative List with reason: ".$comment;	
            }
	    else{
		$this->msgContent =$negType."- ".$negativeVal." not found in negative list";
            }	
        }

        // Default page conditions to show dropdown
        $this->negativeTypeDropdown = array("PHONE_NUM" => "Phone Number", "EMAIL" => "Email", "PROFILEID" => "Username");
    }

}
