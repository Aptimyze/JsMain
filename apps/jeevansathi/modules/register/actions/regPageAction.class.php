<?php

class regPageAction extends sfAction {
    
  public function execute($request) {
    $pageName = $request->getParameter("page");
    $this->pageObj = RegistrationFactory::initiateClass($this);
    $this->pageObj->process();
    if($request->getParameter("incompleteUser"))
      $this->getResponse()->setTitle("Jeevansathi.com | Complete your profile");
    $this->form = $this->pageObj->getForm();
    $this->formName = RegistrationEnums::$templateForm[$pageName];
  } 
}
