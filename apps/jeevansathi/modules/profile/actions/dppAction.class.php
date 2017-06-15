<?php
/**
 * Desired Partner Profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Jaiswal
 * @version    SVN: $Id: dppAction.class.php  $
 */
class dppAction extends sfAction {
	public $data;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter;
	public $filter_prof;
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function execute($request) {
		
                if($request->getParameter("isSubmit")){
                    //Update Score
                    $this->UpdateScore($request);
                    //Update regcount
                    $this->UpdateRegCount();
                    $this->SendMatchAlertsCount();
                    //redirect to home page
                    $this->redirect("/social/addPhotos?fromReg=1");
                }
		//Contains login credentials
		global $smarty, $data;
		$this->loginData = $data = $request->getAttribute("loginData");
                if(MobileCommon::isNewMobileSite()){
                        $this->forward("profile","edit");
                }
		//Contains loggedin Profile information;
		new ProfileCommon($this->loginData,1);
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail();
                $screeningMessage = "<br><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
		$this->loginProfile->setScreeningMessage($screeningMessage);
		
		$screeningMessage = "<br><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
		$this->loginProfile->setScreeningMessage($screeningMessage);
		$this->profileId = $this->loginProfile->getPROFILEID();
		$this->casteLabel = JsCommon::getCasteLabel($this->loginProfile);

    if($request->getParameter("allowLoginfromBackend")){
				$this->fromBackend=1;
				$this->cid=$request->getParameter("fromBackend");
				
		}
    
    
    //MatchAlert Tracking////////////////////
		$this->logic_used = $request->getParameter("logic_used");
		$this->clicksource = $request->getParameter("clicksource");
		ProfileCommon::matchAlertTrackingForDPP($request,$this->loginProfile,"INSERT",MatchAlert_DPP_Tracking::DESKTOP_VIEW);
		//////////////////////////////////////////
    
    //If JSPC Revamp then show JSPC Revamp work else
		if($this->setMyLayout($request)){
			return;
		}
		
    //Smarty assign
		$this->smarty = $smarty;
		$this->request = $request;
		$this->apEditMsg = $request->getParameter("apEditMsg");
		$this->EditWhatNew = $request->getParameter("EditWhatNew");
		$this->dpartner = $this->getJPartner();
		//To include google api in layout.tpl set editPage slot
                
                $params["Version"]="V1";
		$params["Channel"]= "PC";
		$params["SearchType"]= "matchalerts";
		$params["Count"]=10;
                $MAsubHeading = SearchTitleAndTextEnums::getSubHeading($params);
                
                $profileID = $this->profileId;
                $matchAlertObj = new MatchAlerts();
                $subHeadingArr = $matchAlertObj->getMatchAlertHeading($profileID,$MAsubHeading);

                $this->subHeading = $subHeadingArr["Heading"];
                $this->subHeadingLinkText = $subHeadingArr["subHeading"];
                $this->subHeadingLogic = $subHeadingArr["Logic"];
                                
		$response = $this->getResponse();
		$response->setSlot("editPage", 1);
		
		//To show natue handicap
		$handicap_str = $this->dpartner->getDecoratedHANDICAPPED();
		if (strstr($handicap_str, 'Physically Handicapped from birth') || strstr($handicap_str, 'Physically Handicapped due to accident')) $this->show_nhandicap = 1;
		ProfileCommon::old_smarty_assign($this);
		//This will open intermediate layer
		if ($request->getParameter("flag") == "INTM") {
			$this->flag = "INTM";
		//for showing intermediate layer. If called after login do not open intermediale layer
			if(!$request->getParameter('CALL_ME'))$this->oldFlag = $request->getParameter("oldFlag");
		}
    
    	
	}
	function getJPartner() {
		$mysqlObj = new Mysql;
		$profileId = $this->profileId;
		if ($profileId) {
			$myDbName = getProfileDatabaseConnectionName($profileId, '', $mysqlObj);
			$myDb = $mysqlObj->connect("$myDbName");
		}
		$jpartnerObj = new JPartnerDecorated;
		if (in_array("T", explode(",", $this->loginProfile->getSUBSCRIPTION()))) {
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			$APeditID = $this->request->getParameter("APeditID");
			$partnerWhrCond = " AND CREATED_BY='ONLINE'";
			$jpartnerObj_ap = new JPartnerDecorated("Assisted_Product.AP_TEMP_DPP");
			$jpartnerObj_ap->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
			if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
				unset($jpartnerObj_ap);
				$partnerWhrCond2 = " AND STATUS='LIVE' ORDER BY DPP_ID DESC LIMIT 1";
				$jpartnerObj_ap_live = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
				$jpartnerObj_ap_live->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond2);
				$partnerWhrCond3 = " AND ROLE='ONLINE' AND ONLINE='Y' AND CREATED_BY='ONLINE'";
				$jpartnerObj_ap = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
				if ($jpartnerObj_ap_live->isPartnerProfileExist($myDb_ap, $mysqlObj)) $partnerWhrCond3.= " AND DPP_ID>'" . $jpartnerObj_ap_live->getDPP_ID() . "'";
				$partnerWhrCond3.= " ORDER BY DPP_ID DESC LIMIT 1";
				$jpartnerObj_ap->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond3);
				if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) $jpartnerObj_ap = $jpartnerObj_ap_live;
				else $this->apEditMsg=1;
			}
			else
				$this->apEditMsg=1;
			if ($jpartnerObj_ap && $jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
				$jpartnerObj = $jpartnerObj_ap;
				if (!$APeditID) $APeditID = $jpartnerObj_ap->getDPP_ID();
				$APoperator = $jpartnerObj_ap->getCREATED_BY();
				$this->APonlineString = "APeditID=$APeditID&APoperator=$APoperator";
			} //If DPP is not there in Archive or temp then get dpp from newjs.Jpartner
			else $jpartnerObj->setPartnerDetails($profileId, $myDb, $mysqlObj);
		} elseif ($this->userType == UserType::AP_EXECUTIVE && $editId = $this->request->getParameter("editID")) {
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			if ($this->action->request->getParameter("edited")) {
				$partnerWhrCond = " AND CREATED_BY='" . $this->action->request->getParameter("matchPointOperator") . "'";
				$jpartnerObj = new JPartnerDecorated("Assisted_Product.AP_TEMP_DPP");
			} else {
				$partnerWhrCond = "AND DPP_ID='" . $editId . "'";
				$jpartnerObj = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
			}
			$jpartnerObj->setPartnerDetails($this->action->request->getParameter("matchPointPID"), $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
		} else
		//If dpp is getting edited by non assisted user
		$jpartnerObj->setPartnerDetails($profileId, $myDb, $mysqlObj);
		return $jpartnerObj;
	}

	 /*
		* This function sets the basic layout of the page by fetching data from DPPConstants class and 
		*	passing it on to be included in the partial.
		* @param $request-request object
		*
		*/
	private function setMyLayout($request){

		if($request->getParameter('oldjspc') != null)
			return false;
		$this->religion = $this->loginProfile->getRELIGION();
		$this->underScreeningMessage = "<br><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
   	        $this->fromReg = $request->getParameter('fromReg');
		$this->arrOut = array();
		//SetLayout by fetching data from DPPConstants class
                $this->getResponse()->setSlot("optionaljsb9Key",Jsb9Enum::jsRegPage6Url);
                //get existing user filled data for dpp
                $this->existingData = $this->getExistingUserData($request);
                
                //get data for filters
                $this->filterArr = $this->getFilterStatus($request);
                $this->sourcename = $this->loginProfile->getSOURCE(); 
                $groupname = $request->getParameter('groupname');
                $pixelcodeObj = new PixelCodeHandler($groupname,'','JSPCR6',$this->loginProfile);
                $this->pixelcode = $pixelcodeObj->getPixelCode();
		$this->groupname = $groupname;
                //for editWhatNew parameters
                $this->EditWhatNew = $request->getParameter("EditWhatNew");
                if(!in_array($this->EditWhatNew,DPPConstants::$editWhatNewEnumArr))
                  $this->EditWhatNew = "";
                $this->editWhatNewMap = json_encode(DPPConstants::$editWhatNewArr);
		foreach(DPPConstants::$keyMap as $key1=>$val1){
			$this->arrOut[$key1] = array(
				"title" => DPPConstants::$titleArray[$key1],
				"editId" => DPPConstants::$editArray[$key1],
				"sectionId" => DPPConstants::$sectionIdArray[$key1],
				"fieldArray"=>array(),
				);

			foreach($val1 as $valName=>$value){

					$this->arrOut[$key1]["fieldArray"][$value] = array(
						"label" =>DPPConstants::$fieldNameArray[$key1][$value],
						"type" =>DPPConstants::$fieldTypeArray[$key1][$value],
						"chosen" =>DPPConstants::$fieldChosenArray[$key1][$value],
						"dropDownMap" => DPPConstants::$fieldDropDownMapArray[$key1][$value],
						"filter" => DPPConstants::$fieldFilterArray[$key1][$value],
						"idName" => DPPConstants::$idNameArray[$key1][$value],
            "fieldId"=> DPPConstants::$prefilledKeyArray[$key1][$value],
						);
                                        //loop to match keys of this array and filter array
                                        foreach($this->filterArr[FILTER] as $ky=>$vl){
                                          if(DPPConstants::$fieldFilterArray[$key1][$value]["FILTER_MAP"]==$vl[key] && $vl[val]){
                                            $this->arrOut[$key1]["fieldArray"][$value]["filter"]["FILTER_VALUE"]=$vl[val];
                                          }
                                        }
                                        //loop to match keys of this array and dpp array
                                        foreach($this->existingData as $ky=>$vl){
                                          if(DPPConstants::$prefilledKeyArray[$key1][$value]==$vl[key]){
                                            $this->arrOut[$key1]["fieldArray"][$value]["prefilledMap"]=$ky;


                                          }
                                         if($vl['key'] == "P_MATCHCOUNT")
                                         {
                                         	$this->mutualMatchCount = $vl["value"];
                                         }

                                        }

		}
										                     //to change caste into sect based on Religion of LoggedInProfile
                                         if($this->religion == "2" || $this->religion == "3"){
                                           $this->arrOut["RELIGION_ETHINICITY"]["fieldArray"]["CASTE"]["label"] ="Sect";		                           
                                           $this->arrOut["RELIGION_ETHINICITY"]["fieldArray"]["CASTE"]["filter"]["FILTER_HINT_TEXT"] = "Only profiles with the specified Sect(s) will be able to contact you ";
			                 }  							
                                         
                 }
		 $this->dropDownData = $this->getDropDowns($request);
     $this->casteDropDown = json_encode($this->getCasteValues(),true);
     
     $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
    $cnt_logic = $newjsMatchLogicObj->getPresentLogic($this->loginProfile->getPROFILEID(),MailerConfigVariables::$oldMatchAlertLogic);
    if($cnt_logic>0)
            $this->toggleMatchalerts = "dpp";
    else
            $this->toggleMatchalerts = "new";
     
     if(isset($this->fromReg)){
     	//added this for caching
        $nameOfUserOb=new NameOfUser();        
        $nameOfUserArr = $nameOfUserOb->getNameData($this->profileId);
        $this->name = $nameOfUserArr[$this->profileId]["NAME"];
        
      /*$name_pdo = new incentive_NAME_OF_USER();
      $this->name        = $name_pdo->getName($this->profileId);*/
      unset($nameOfUserOb);   
     }
     
     $this->setTemplate("_jspcDpp/jspcDpp");
	   return true;
	}
		/*
		 * This function fetches the data to be shown in all dropdown options using 
		 * FieldMap::getFieldLabel. 
		 * @param $request-request object
		 * @return returns an array containing data for all drop down fields 
		 */
	private function getDropDowns($request){
		$this->fetchingData = array();
		$request->setParameter("l",'height_json,p_mstatus,dpp_country,p_religion,p_mtongue,p_mtongue,p_manglik,p_education,p_occupation_grouping,p_diet,p_smoke,p_drink,p_complexion,p_btype,p_challenged,p_nchallenged,dpp_city');
		$request->setParameter("actionCall","1");
		ob_start();
		$fieldValues = sfContext::getInstance()->getController()->getPresentationFor("static","getFieldData");
		
		$this->staticFields = json_decode(ob_get_contents(),true);		
		ob_end_clean();
		foreach(DPPConstants::$alterFeildDataStructureArray as $key=>$val){
			$this->fetchingData=FieldMap::getFieldLabel($val,'',1);
			
			if($val == "hincome")
				$this->fetchingData["0"] = "No Income";	
			$this->staticFields[$val] = $this->changeDataFormat($this->fetchingData,$val);
		}
		$this->staticFields["age"] = $this->alterAgeArray();
		$this->staticFields["height_json"] = $this->orderHeightValues();
                $this->staticFields["p_mstatus"] = $this->updateMStatus($this->staticFields["p_mstatus"]);		
		foreach($this->staticFields as $k=>$v)
		{
			if($v[0][0][0]=="Select" || $v[0][0]["S0"]=="Select")
			{
				unset($this->staticFields[$k][0][0]);
			}
		}
		return $this->staticFields;
	}
	/* This function changes the format of the array into the requried format so as to make all data 
	 * have a common structure
	 * @param $inputArray- array passed from getDropDowns() to be changed is passed on as input
	 * @return $outputArray- returns the array in the required format
	 */
	private function changeDataFormat($inputArray,$val){
	  $i=0;
	  if($val == "hincome_dol"){
	  	$outputArr[0]=array(0=>"No Income");
	  	$i++;
	  }
	  foreach($inputArray as $key=>$value)
	  {  
			  $outputArr[$i]=array($key=>$value);
			  $i++;
	  }
	  return array($outputArr);
	}
	/* This function creates the drop down data for age in the required format
	 * @return age data array in the desired format
	 * 
	 */
	private function alterAgeArray(){
                $gender = $this->loginProfile->getGENDER();
		$c=0;
                $lowerAge = 18;
                if($gender == "F")
                  $lowerAge = 21;
		for($i=$lowerAge;$i<=70;$i++){
			$arr[0][$c]=array($i=>$i);
			$c++;
		}
		return $arr;
	}
        /*
         * function to fetch data already filled for a user from API
         * @param - request object
         * @return - returns an array with dpp data
         */
        private function getExistingUserData($request){
          ob_start();
          $request->setParameter("sectionFlag","dpp");
          $request->setParameter("internal",1);
          $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile","ApiEditV1");
          $this->dppData = json_decode(ob_get_contents(),true);
          ob_end_clean();
	  foreach($this->dppData as $k=>$v)
	  {
		if($v['key']=="P_CASTE")
		{
			if($v['value']=="DM")
				$this->dppData[$k]['label_val']="Doesn't Matter";
			break;
		}
	  }          
          return $this->dppData;
        }
        /*
         * function to fetch filter status data from Filter API
         * @param - request object
         * @return - returns an array with filter data
         */
        private function getFilterStatus($request){
          ob_start();
          $request->setParameter("internal",1);
          $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile","ApiEditFilterV1");
          $this->filterData = json_decode(ob_get_contents(),true);
          ob_end_clean();
          return $this->filterData;
        }
        
        private function getCasteValues()
        {
		  $arr=FieldMap::getFieldLabel("religion_caste",'',1);
		  $casteArr=FieldMap::getFieldLabel("caste_without_religion",'',1);
		  foreach(DPPConstants::$removeCasteFromDppArr as $k=>$v)
		  {
			unset($casteArr[$v]);
		  }
		  $casteObj = new NEWJS_CASTE;
		  $caste_arr = $casteObj->getTopSearchBandCasteData();
		  $caste_arr = $this->unsetOtherCaste($caste_arr);
		  foreach($caste_arr as $key=>$val)
		  {
			$parent = $val["PARENT"];
			if(array_key_exists($parent,$arr) && $val["ISALL"]!="Y")
			{
				$label = ($casteArr[$val["VALUE"]]?$casteArr[$val["VALUE"]]:$val["LABEL"]).(($val["ISGROUP"]=="Y")?"- All":"");
				$Arr[$parent][0][]=array($val["VALUE"]=>$label);
			}
		  }
		  return $Arr;
        }
  private function unsetOtherCaste($arr)
  {
	foreach($arr as $k=>$v)
	{
		if(in_array($v['VALUE'],DPPConstants::$removeCasteFromDppArr))
			unset($arr[$k]);
	}
	return $arr;
  }      
   /*
   * Function to fromat and order height values in desired order
   */
  private function orderHeightValues() {
    $c=0;
    for($x=0;$x<=11;$x++) {
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x];
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x+12];
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x+24];
    }
    $heightOrdered[0][$c] = $this->staticFields["height_json"][0][36];
    return $heightOrdered;
  }
  
  /*
   * Function to remove or show Married option in list
   */
  private function updateMStatus($mstatusArr) {
    $i=0;
    foreach($mstatusArr[0] as $k=>$v){
      foreach($v as $kk=>$vv){
        if($kk != "M" || $this->toSetMarriedOpt())
          $arrToReturn[0][$i++] = array($kk=>$vv);
      }
    }
    return $arrToReturn;
  }
  private function toSetMarriedOpt(){
    $userGender = $this->loginProfile->getGENDER();
    $userReligion = $this->religion;
    if($userReligion == '2' && $userGender == 'F')
      return true;
    return false;
  }

 //  /*
 // 	 * function to set whether to show manglik or not based on the religion of logged in profile
 //  */
	// private function showManglik(){
	// 	 $showManglikField = false;
	// 	if($this->religion == "1" || $this->religion == "4" || $this->religion == "7" || $this->religion == "9")
	// 		$showManglikField = true;
	// 	return $showManglikField; 
	// }
  
    private function UpdateRegCount($status="Y") {

        $dbObj = new MIS_REG_COUNT;
        $dbObj->updateEntryRegPage("PAGE5", $status, LoggedInProfile::getInstance()->getPROFILEID());
    }
    private function SendMatchAlertsCount() {

        $pid = LoggedInProfile::getInstance()->getPROFILEID();
        $matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT();
        $data = $matchalerts_MATCHALERTS_TO_BE_SENT->fetchLastRecord();
        if($data){
                $matchalerts_MATCHALERTS_TO_BE_SENT->insertIntoMatchAlertsTempTable("main", $pid);
        }else{
                $matchalerts_MATCHALERTS_TO_BE_SENT->insertIntoMatchAlertsTempTable("temp", $pid);
        }
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
