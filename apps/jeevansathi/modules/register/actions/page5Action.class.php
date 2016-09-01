<?php
class page5Action extends sfAction {
    /** Executes Registration page 5
     *
     */
    public function execute($request) {
        $request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[5]);
      $this->forward('register','regPage');
          //Contains login credentials
        $this->loginData = $request->getAttribute("loginData");
        if(!$this->loginData[PROFILEID])
		$this->forward("register","page1");

	//Jsb9 page load time tracking page5
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage5Url);
	
	$profileid=$this->loginData[PROFILEID];
        $this->SITE_URL = sfConfig::get("app_site_url");	
        $this->loginProfile = LoggedInProfile::getInstance();
        $this->loginProfile->getDetail($profileid, "PROFILEID");
        $this->PartnerObj = new PartnerProfile($this->loginProfile);
        $this->PartnerObj->getDppCriteria();
        $this->Gender = $this->loginProfile->getGENDER();
        $this->RECORD_ID = $request->getParameter("record_id");
        $this->REG_P6="1";
        $partnerField = new PartnerField();
        if (($request->getParameter('submitReg5'))) {
            $this->form = new PageForm(array(), array("page" => 'DP5', 'request' => $request));
            $this->form->bind($request->getParameter('reg'));
            if ($this->form->isValid()) {
                $this->CheckSkip($request);
                //Update Score
                $this->UpdateScore($request);
                //Update Partner
                $partnerField->UpdateDPP($request);
                //Update jprofile
                $this->form->updateData($profileid);
                //Update filter
                $this->UpdateFilter($partnerField);
                //Update regcount
                $this->UpdateRegCount();
                $this->RedirectOrForward($request);
                //die;
                //Update
                //insert record
                //print_r($this->form->getValues());
                
            } else {
				 
                $partnerField->setPage($this, $request);
            }
        } else {
				$AutoSuggestIncome= DppAutoSuggestValue::getAutoSuggestValue("INCOME",34,$this->loginProfile);
				
            $partnerField->setPage($this, $request);
            $this->form = new PageForm(array(p_lage => $partnerField->getP_LAGE(), p_hage => $partnerField->getP_HAGE(), p_lheight => $partnerField->getP_LHEIGHT(), p_hheight => $partnerField->getP_HHEIGHT(),p_lrs=>$AutoSuggestIncome["rsLIncome"],p_hrs=>$AutoSuggestIncome["rsHIncome"],p_lds=>$AutoSuggestIncome["doLIncome"],p_hds=>$AutoSuggestIncome["doHIncome"]), array("page" => 'DP5'));
            $this->RECORD_ID = $request->getParameter("record_id");
        }
    }
    private function RedirectOrForward($request) {
	if ($request->getParameter('skip_to_fto'))
		$this->UpdateRegCount("S");

	else if ($this->loginProfile->getGENDER() == "F")
	{
		if (MobileCommon::isMobile())
                        $this->redirect("/profile/mainmenu.php");
		else
			$this->forward("register", "page6");
	}
	else
	{
		$profilechecksum = CommonFunction::createChecksumForProfile($this->loginProfile->getPROFILEID());
		if ($request->getParameter('skip_to_fto')) 
		{
			//$parentUrl = "/fto/offer?fromReferer=0";
		}
		//else
		$parentUrl = "/social/addPhotos?from_registration=1";
			$this->redirect("$parentUrl");
		


	}
    }
    private function CheckSkip($request) {
        if ($this->loginProfile->getGENDER() == "F" && $request->getParameter(record_id)) {
            if ($request->getParameter(skip_to_next_page6)) {
                $this->forward("register", "page6");
            }
        }
    }
    private function UpdateRegCount($status="Y") {

        $dbObj = new MIS_REG_COUNT;
        $dbObj->updateEntryRegPage("PAGE5", $status, LoggedInProfile::getInstance()->getPROFILEID());
    }
    
    /**
     * UpdateFilter
     * @param type $partnerField
     */
    private function UpdateFilter($partnerField) 
    {
      $arrFilter = array();
      if ($partnerField->partnerObj->getPARTNER_MSTATUS()) {
        $arrFilter["MSTATUS"] = 'Y';
      } 
      if ($partnerField->partnerObj->getPARTNER_RELIGION()) {
        $arrFilter["RELIGION"] = 'Y';
      } 
      if ($partnerField->partnerObj->getPARTNER_CASTE()) {
        $arrFilter["CASTE"] = 'Y';
      } 

      if(count($arrFilter)) {
        $hardSoft="Y";
        $count=10;
      } else {
        $hardSoft="N";
        $count=0;
      }
      $arrFilter['HARDSOFT'] = $hardSoft;
      $arrFilter['COUNT'] = $count;

      $dbObj = new NEWJS_FILTER;
      $dbObj->insertRecord(LoggedInProfile::getInstance()->getPROFILEID(), $arrFilter);
    }
    private function UpdateScore($request) {
        $loginProfile = LoggedInProfile::getInstance();
        $score = ProfileScore::getProfileScore($loginProfile);
        $dbObj = new MIS_PROFILE_SCORE();
        if ($score && $loginProfile) $dbObj->insertEntry($loginProfile->getPROFILEID(), $score);
        $request->setParameter("profile_score", $score);
    }
}
?>
