<?php
/**
 * EditDPP actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Jaiswal
 * @version    SVN: $Id: dppAction.class.php  $
 */
class editDppAction extends sfAction {
	public $data;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter_prof;
	public $spammer;
	public $myDb;
	public $mysqlObj;
	public $request;
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function execute($request) {
		//Contains login credentials
		global $smarty, $data;
		$this->loginData = $data = $request->getAttribute("loginData");
		//Contains loggedin Profile information;
		new ProfileCommon($this->loginData);
		$this->loginProfile = LoggedInProfile::getInstance();
		if($request->getParameter("matchPointCID"))
			$this->loginProfile->getDetail($request->getParameter("matchPointPID"));
		else
			$this->loginProfile->getDetail();
		$this->request = $request;
		$profileId = $this->loginProfile->getPROFILEID();
		//Smarty assign
		$this->smarty = $smarty;
	    $this->if_no_script=$request->getParameter("if_no_script");
		$response = $this->getResponse();
		$fsubmit = $request->getParameter("fsubmit");
		if ($fsubmit){
			$flag = $request->getParameter("val_flag");
			if($this->if_no_script){
				$fsubmit='';
				if($request->getParameter("CMDsubmit"))
					$flag="PPA";
			}
		}
		else $flag = $request->getParameter("flag");
		if ($request->getParameter("from_filter")) {
			$flag = "FILTER";
			$this->filter = $request->getParameter("filter");
		}
		$this->formFlag = $flag;
		$this->from_reg=$request->getParameter("from_reg");
		$logic_used = $request->getParameter("logic_used");
		$clicksource = $request->getParameter("clicksource");
		//Open intermediate layer if editing is done in one profile
		if ($flag == "INTM") {
			$this->oldFlag = $request->getParameter("oldFlag");
			$templateName = "edit_dpp_partner_intermediate";
		} else {
			$this->mysqlObj = new Mysql;
			$myDbName = getProfileDatabaseConnectionName($profileId, '', $mysqlObj);
			$this->myDb = $this->mysqlObj->connect("$myDbName");
			include_once (sfConfig::get("sf_web_dir") . "/profile/functions_edit_dpp.php");
			$component = EditDppComponentFactory::createDppComponent($flag, $this);
			//If it is form submit then
			if ($fsubmit) {
				$component->submit();
			} else {
				$response = $this->getResponse();
				if ($flag == "PPA") $response->setSlot("submitAction", "edit_profile.php?clicksource=$clicksource&logic_used=$logic_used");
				else $response->setSlot("submitAction", "edit_dpp.php?clicksource=$clicksource&logic_used=$logic_used");
				$response->setSlot("LayerHeading", $component->getLayerHeading());
				$response->setSlot("onSubmit", $component->getOnSubmitJs());
				$component->display();
				$this->hiddenInput = $this->getPartial("edit_dpp_hidden_input");
			}
			$templateName = $component->getTemplateName();
			//Do all smarty legacy assign
			ProfileCommon::old_smarty_assign($this);
			$this->setLayout("layoutEdit");
		}
		$this->setTemplate($templateName);
	}
	/**
	 * Fields that are assigned Does not matter values are not to be set
	 * This function unset all fields that contains DM
	 *
	 */
	public function getPostParameter($paramName) {
		$paramValue = $this->request->getPostParameter($paramName);
		if (is_array($paramValue)) {
			if (array_search("DM", $paramValue)!==false) $paramValue = "";
		}
		return $paramValue;
	}
}
?>
