<?php

/**
 * desktopRegister6 class
 *
 * @author Kunal Verma
 * @package jeevansathi
 * @subpackage registration
 */
class desktopRegister6 extends registrationBaseClass {
 
  /*
   * Declaring Memeber Varibales
   */

  /*
   * Declaring and Defining Member Function
   */
   
  public function __construct($objController) {
    //Assign Controller for template configuration
    $this->objController = $objController;
    
    //Assign Request Param
    $this->request = $this->objController->getRequest();
  }
  
  /*
   * preProcess
   * Forward to phone Verification page
   */
  public function preProcess() {
    $this->request->setParameter('fromReg','1');
    $this->objController->forward('profile','dpp');
    die;
  }

  public function submit() {
    
  }
    
  public function preSubmit() {
    ;
  }
  
  public function isSubmit() {
    return false;
  }

}
