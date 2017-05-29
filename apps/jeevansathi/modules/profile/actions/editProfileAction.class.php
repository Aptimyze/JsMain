<?php
/**
 * Edit Profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Anurag
 * @version    SVN: $Id: dppAction.class.php  $
 */
class editProfileAction extends sfAction {
	public $data;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter;
	public $filter_prof;
	public $spammer;
	public $paid;
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function execute($request) {
		//Contains login credentials
		global $smarty, $data, $CALL_NOW;
		$this->loginData = $data = $request->getAttribute("loginData");
		//Contains loggedin Profile information;
		new ProfileCommon($this->loginData,1);
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail();
		$this->loginProfile->setNullValueMarker("-");
		$this->profileId = $this->loginProfile->getPROFILEID();
		$this->GENDER = $this->loginProfile->getGENDER();
		//Smarty assign
		$this->smarty = $smarty;
		//Get Caste Label
		$this->casteLabel = JsCommon::getCasteLabel($this->loginProfile);
		$this->relgionSelf = $this->loginProfile->getDecoratedReligion();
		//Assign Label to layer
	    $this->if_no_script=$request->getParameter("if_no_script");
		$this->FROM_FTO=$request->getParameter("from_fto");
		$this->congrat_msg=$request->getParameter("congrat_msg");
		$this->prev_url=$request->getParameter("prev_url");
		$response = $this->getResponse();
		if($this->if_no_script){
			$CMDSubmit='';
		}
		else
			$CMDSubmit=$request->getParameter("CMDsubmit");
		$this->EditWhat = $request->getAttribute("EditWhat");
		$this->SITE_URL = sfConfig::get("app_site_url");
		$this->IMG_URL = sfConfig::get("app_img_url");
		include_once (sfConfig::get("sf_web_dir") . "/profile/functions_edit_profile.php");
		//Handle Individual Ajax requests
		if ($request->getParameter("Only_city")) {
			$result = create_city_drop_and_isd($request->getParameter("Country_code"));
			return $this->renderText($result);
		}
		if ($request->getParameter("Only_city2")) {
			$result = get_stdcode_of_city($request->getParameter("City_code"));
			return $this->renderText($result);
		}
		if ($request->getParameter("Junkcheck")) {
			$junk = chkJunkNumberList($request->getParameter("phone"), $request->getParameter('type'));
			if ($junk) return $this->renderText("J" . $request->getParameter('type'));
			else return $this->renderText('NJ');
		}
		if ($request->getParameter("verify_email")) {
			$result = verify_email($request->getParameter("email_id"), $this->profileId);
			return $this->renderText($result);
		}
		if ($request->getParameter("Cancel")||!$request->getParameter("flag")) {
			echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$this->SITE_URL/profile/viewprofile.php?ownview=1\"</head><body></body></html>";
			die;
		}
		$this->flag = $request->getParameter("flag");
		//Open intermediate layer if editing is done in one profile
		if ($this->flag == "INTM") {
			$this->oldFlag = $request->getParameter("oldFlag");
			$this->editMessage = $this->getEditMessage($this->oldFlag);
			$templateName = "edit_profile_intermediate";
		} else {
			$component = EditComponentFactory::createComponent($this->flag);
			$component->setLoginProfile($this->loginProfile);
			$component->setActionObject($this);
			//If it is form submit then
			//If it is form submit then
			if ($CMDSubmit) {
				$component->submit();
				$component->after_submit();
				die;
			} else {
				//if coming from horoscope layer and need to open next layer
				if ($request->getParameter('nextLayer') && $this->flag == "CUH") {
					$response->setSlot("submitAction", "horoscope_upload.php");
					$response->setSlot("submitButton", "Save");
				} else $response->setSlot("submitAction", "editProfile");
				$response->setSlot("LayerHeading", $component->getLayerHeading());
				$response->setSlot("onSubmit", $component->getOnSubmitJs());
				$component->display();
				$this->hiddenInput = $this->getPartial("edit_profile_hidden_input");
			}
			$templateName = $component->getTemplateName();
			//  print_r($this->smarty->getTemplateVars()); die;
			ProfileCommon::old_smarty_assign($this);
			//	echo "y";die;
			$this->setLayout("layoutEdit");
		}
		$this->setTemplate($templateName);
	}
	/**
	 * Returns message that will be displayed in the intermediate layer after editing layer
	 * @param EditWhat takes edit flag
	 * @returns Displaying message
	 * */
	function getEditMessage($EditWhat) {
		$relation = $this->loginProfile->getRELATION();
		$gender = $this->loginProfile->getGENDER();
		if ($relation == 1) {
			$yourself = "yourself";
			$your = "your";
		} else {
			if ($gender == 'F') {
				$yourself = "her";
				$your = "her";
			} else {
				$yourself = "him";
				$your = "his";
			}
		}
		$this->your = $your;
		$this->yourself = $yourself;
		switch ($EditWhat) {
			case 'PBI':
				$EditLabel = "$your basic information";
			break;
			case 'PRE':
				$EditLabel = "$your religion and ethnicity";
			break;
			case 'PEO':
				$EditLabel = "$your education and occupation";
			break;
			case 'PFD':
				$EditLabel = "$your family details";
			break;
			case 'PMF':
				$EditLabel = "about $yourself";
			break;
			case 'CUH':
				$EditLabel = "$your astro/kundali details";
			break;
			case 'PLA':
				$EditLabel = "$your lifestyle and attributes";
			break;
			case 'PCI':
				$EditLabel = "$your contact information";
			break;
			case 'PHI':
				$EditLabel = "$your hobbies and interests";
			break;
		}
		return $EditLabel;
	}
}
?>
