<?php
class page4Action extends sfAction {
    /** Executes Registration page 4
     *
     */
    public function execute($request) {
		$request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[4]);
      $this->forward('register','regPage');
        //Jsb9 page load time tracking page4
		$this->loginData = $request->getAttribute("loginData");
		if(!$this->loginData[PROFILEID])
			$this->forward("register","page1");
		$profileid=$this->loginData[PROFILEID];
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID");
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage4Url);
        $this->SITE_URL = sfConfig::get("app_site_url");
        // display spoken languages
        $langObj = new NEWJS_LANGUAGES();
        $allLangArr = $langObj->getAllLanguages();
        foreach ($allLangArr as $key) {
            $mtongueArr = explode(",", $key["MTONGUE_VAL"]);
            if (in_array($mtongue, $mtongueArr) && $mtongueArr[0] != "") {
                $selected = "1";
                $language_v[] = $key["VALUE"];
            } else $selected = "";
            $language[] = array("LABEL" => $key["LABEL"], "VALUE" => $key["VALUE"], "SELECTED" => 1);
        }
        
        $this->LANGUAGE = $language;
        if (count($language_v) > 1) $this->LANGUAGEstr = implode($language_v, "','");
        else $this->LANGUAGEstr = "'" . $language_v[0] . "'";
		//Get sugar lead data if it is sugar lead
		$record_id=$request->getParameter('record_id');
	//	$record_id="beb4f6e1-09d0-c33b-5603-4f7d74a6ba5d";
		if($record_id){
			$contact=$this->getSugarLeadData($record_id);
		}
		//Sugarlead code ends
        $this->form = new PageForm(array('hiv' => 'N', 'showmessenger' => 'Y', 'showaddress' => 'N','contact'=>$contact), array("page" => 'DP4', 'request' => $request), '');
        $this->IS_FTO_LIVE = FTOLiveFlags::IS_FTO_LIVE;
        
        //Get array of name of fields that are in this page
        $field_array = RegEditFields::getFieldArray('DP4');
        $this->errMsg = ErrorHelp::getErrorArray($field_array);
        
        if ($request->getParameter('submit_page4')) {
            $this->form->bind($request->getParameter('reg'));
             if ($this->form->isValid()) {
				 if($request->getParameter('language_arr') && count($request->getParameter('language_arr')))
					$this->loginProfile->editHobby(array('HOBBY'=>implode(',',$request->getParameter('language_arr'))));
                $this->form->updateData($profileid);
                //DPP Auto Suggestor implemenation :
                $dppObj=new DppAutoSuggest($this->loginProfile);
				$profileFieldArr=array("HANDICAPPED");
				$dppObj->insertJpartnerDPP($profileFieldArr);
				//End of DPP auto Suggestor
                $this->forward("register", "page5");
            }
			 else{
					RegistrationMisc::logServerSideValidationErrors("DP4",$this->form);
				 }
        }
    }
	function getSugarLeadData($record_id){
		$sugar_obj= new sugarcrm_leads();
		$sugar_row1=$sugar_obj->getLeadDataById($record_id);
		$sugar_row2=$sugar_obj->getLead_CstmDataById($record_id);
		if($sugar_row1)
			    {
					$street=$sugar_row1['primary_address_street'];
					if($street)
						$contact_address = $contact_address . $street . "\n";
					$city=$sugar_row1['primary_address_city'];
					if($city)
						$contact_address = $contact_address . $city . "\n";
					$state=$sugar_row1['primary_address_state'];
					if($state)
						$contact_address = $contact_address . $state . "\n";
					$postalcode=$sugar_row1['primary_address_postalcode'];
					if($postalcode)
						$contact_address = $contact_address . $postalcode . "\n";
			 	}   
		        if($sugar_row2){   
					$pobox=$sugar_row2['p_o_box_no_c'];
					if($pobox)
						$contact_address = $contact_address . $pobox . "\n";
				}
		return $reg_param[contact]=$contact_address;
	}
}
?>
