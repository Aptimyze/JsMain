<?php

/**
 * bms actions.
 *
 * @package    jeevansathi
 * @subpackage bms
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bmsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('bms', 'trackTransactionDetails');
  }
/*
* Function to get transaction information by sale Id
*/
public function executeTrackTransactionDetails(sfWebRequest $request)
    {
        $formArr 	= $request->getParameterHolder()->getAll();
        $this->cid 	= $formArr['cid'];
        if($formArr['submit'])
        {
            $transactionIdValid = ValidationHandler::validateNumberNSpaces($formArr['transactionId']);
            if($transactionIdValid === false){
                $this->transactionId = $formArr['transactionId'];
                $this->error = 1;
            }else{
                $billingObj = new billing_REV_MASTER;
                $transactionData = $billingObj->getTransactionById($transactionIdValid);
                if($transactionData == 0){
                   $this->transactionId = $transactionIdValid;
                   $this->error = 1;
                }else{
                   $this->transactionData = $transactionData;
                }
            }
        }
    }
}
