<?php

/**
 * negativeTreatmentAction
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Manoj
 */
class negativeTreatmentAction extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeNegativeTreatment(sfWebRequest $request)
    {

        $this->cid  = $request->getParameter('cid');
        $this->name = $request->getParameter('name');
        $submit     = $request->getParameter('submit');

        $negativeTreatmentObj = new negativeTreatment();

        if ($submit == 'Submit') {
            $this->dataArr = $request->getParameter('dataArr');
            $negVal        = $this->dataArr['negativeVal'];
            $negType       = $this->dataArr['negativeType'];
            $comment       = trim($this->dataArr['comment']);
            $key           = 'negativeVal';

            // Error handling condition
            if ($negType == 'EMAIL') {
                $emailStatus = $this->checkEmail($negVal);
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
                $phoneValidate = $this->checkPhoneNumber($negVal);
                if (!$phoneValidate) {
                    $errorArr[$key] = 1;
                } else {
                    $this->dataArr['negativeVal'] = $phoneValidate;
                }

            }
            if (!$comment) {
                $errorArr['comment'] = 1;
            }

            if (count($errorArr) > 0) {
                // Error handling
                $this->errorArr = $errorArr;
            } else {
                // Success
                $submitSuccess        = true;
                $this->successMessage = true;
            }

        }
        if ($submitSuccess) {
            if ($negType == 'PROFILEID') {
                $negativeVal = $this->profileid;
            } else {
                $negativeVal = $this->dataArr['negativeVal'];
            }

            $negativeTreatmentObj->addToNegative($negType, $negativeVal, $comment);

        }
        // Default page conditions
        $this->negativeTypeDropdown = array("PHONE_NUM" => "Phone Number", "EMAIL" => "Email", "PROFILEID" => "Username");

    }
    public function checkEmail($email)
    {
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email)) {
            return 1;
        }

        return;
    }
    public function checkPhoneNumber($phoneNumber)
    {
        $phoneNumber = substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "", $phoneNumber), -15);
        $phoneNumber = ltrim($phoneNumber, 0);
        $totLength   = strlen($phoneNumber);
        if ($totLength < 6 || $totLength > 14) {
            return false;
        }

        if (!is_numeric($phoneNumber)) {
            return false;
        }

        return $phoneNumber;

    }

}
