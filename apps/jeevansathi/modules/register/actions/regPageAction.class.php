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

    if($this->sourcename && !$this->groupname)
    {
	$MIS_SOURCE =  new MIS_SOURCE;
	$arr = $MIS_SOURCE->getSourceGroup($this->sourcename);
	if($arr)
		$this->groupname = $arr["GROUPNAME"];
    }
   
  } 
}
