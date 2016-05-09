<?php
abstract class EditDppComponent implements EditComponent {
	protected $jpartner;
	protected $action;
	protected $userType;
	function __construct($action) {
		$this->action = $action;
		$action->loginProfile->getSUBSCRIPTION();
		if ($action->request->getParameter("matchPoint")) $this->userType = UserType::AP_EXECUTIVE;
		elseif (in_array("T", explode(",", $action->loginProfile->getSUBSCRIPTION()))) $this->userType = UserType::AP_USER;
	}
	public function submit() {
		$profileid = $this->action->loginProfile->getPROFILEID();
		$gender=$this->action->loginProfile->getGENDER();
		$this->profileid = $profileid;
		$this->flag = $this->action->request->getParameter("val_flag");
		$this->beforeSubmit();
		if(!$this->validateInputs())
			$this->action->forward("static","page500");
		$scase = $this->createUpdateQuery();
		//MatchAlert Tracking////////////////////
		ProfileCommon::matchAlertTrackingForDPP($this->action->request,$this->action->loginProfile,"UPDATE",MatchAlert_DPP_Tracking::DESKTOP_EDIT);
		//////////////////////////////////////////
		//If edited by assisted product executive
		if ($this->userType == UserType::AP_EXECUTIVE || $this->userType == UserType::AP_USER) include_once (sfConfig::get("sf_web_dir") . "/jsadmin/ap_dpp_common.php");
		if ($this->userType == UserType::AP_EXECUTIVE) {
			updateTemporaryDPP($scase, $this->action->request->getParameter("matchPointPID"), $this->action->request->getParameter("matchPointOperator"));
			header("Location: " . $SITE_URL . "/jsadmin/ap_dpp.php?cid=" . $this->action->request->getParameter("matchPointCID") . "&new=" . $this->action->request->getParameter("matchPointNew") . "&editedProfile=" . $this->action->request->getParameter("matchPointPID") . "&editID=" . $this->action->request->getParameter("editID") . "&pulledProfile=" . $this->action->request->getParameter("matchPointPulledProfile") . "&outOfQueue=" . $this->action->request->getParameter("outOfQueue"));
			die;
		}
		//If editted by a assisted product User(Subscription T)
		if ($this->userType == UserType::AP_USER) {
			$myDb_ap = $this->action->mysqlObj->connect("Assisted_Product");
			if (!(updateTemporaryDPP($scase, $profileid, 'ONLINE'))) {
				$jpartnerObj = new Jpartner("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
				if ($APeditID) {
					$partnerWhrCond = " AND DPP_ID='$APeditID'";
					$jpartnerObj->setPartnerDetails($profileid, $myDb_ap, $this->action->mysqlObj, "*", $partnerWhrCond);
				}
				if (!$jpartnerObj->isPartnerProfileExist()) {
					$jpartnerObj = new Jpartner();
					$jpartnerObj->setPartnerDetails($profileid, $this->action->myDb, $this->action->mysqlObj);
				}
				$editedValues = $this->getEditedValues();
				$jpartnerObj->setJpartnerUsingArray($editedValues);
				$createTempDPPValues = $jpartnerObj->getJpartnerArray();
				createTempDPP($createTempDPPValues);
			}
			$apEditMsg = 1;
			
			if ($this->action->filter) {
					
					header("Location: $SITE_URL/register/page6?cameFrom=EDITDPP");
					
			}
			else header("Location: $SITE_URL/profile/dpp?apEditMsg=$apEditMsg&flag=INTM&oldFlag=" . $this->flag);
			die;
		} else {
			if ($scase) $scase.= ",DPP='E',DATE=now()";
			$jpartnerObj = new Jpartner();
			$jpartnerObj->setPartnerDetails($profileid, $this->action->myDb, $this->action->mysqlObj);
			if(!$jpartnerObj->isPartnerProfileExist($this->action->myDb, $this->action->mysqlObj)){
			$jpartnerObj->setPROFILEID($profileid);
			if($gender=='M')
				$partner_gender= 'F';
			else
				$partner_gender='M';
			$jpartnerObj->setGENDER($partner_gender);
			}
			
			$jpartnerObj->updatePartnerDetails($this->action->myDb, $this->action->mysqlObj, $scase);
			if ($this->action->filter) {
				if($this->action->request->getParameter('from_reg'))
					header("Location: $SITE_URL/register/page6?REG_P6=1");
				else
				{
				if($this->action->request->getParameter("fromPage"))
					header("Location: $SITE_URL/register/page6?cameFrom=EDITDPP&fromPage=filter_redirect");
					else
					header("Location: $SITE_URL/register/page6?cameFrom=EDITDPP");
			}
			}
			else header("Location: $SITE_URL/profile/dpp?flag=INTM&oldFlag=" . $this->flag);
			die;
		}
		//If profile's Source is ofl_prof Then do following
		if (strtolower($this->action->loginProfile->getSOURCE()) == "ofl_prof") offlineBillingUpdate($this->action->loginProfile->getPROFILEID());
	}
	protected function beforeDisplay($profileid) {
		$this->action->frommatchalert = $this->action->request->getParameter("frommatchalert");
		$mysqlObj = new Mysql;
		if ($profileid) {
			$myDbName = getProfileDatabaseConnectionName($profileid, '', $mysqlObj);
			$myDb = $mysqlObj->connect("$myDbName");
		}
		$jpartnerObj = new Jpartner;
		if ($this->userType == UserType::AP_USER) {
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			$APeditID = $this->action->request->getParameter("APeditID");
			if ($APeditID) $jpartnerObj_ap = deleteTempIfAPEditId($profileid, $APeditID, $myDb_ap, $mysqlObj);
			if (!$jpartnerObj_ap) {
				$partnerWhrCond = " AND CREATED_BY='ONLINE'";
				$jpartnerObj_ap = new Jpartner("Assisted_Product.AP_TEMP_DPP");
				$jpartnerObj_ap->setPartnerDetails($profileid, $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
				if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
					unset($jpartnerObj_ap);
					$partnerWhrCond2 = " AND STATUS='LIVE' ORDER BY DPP_ID DESC LIMIT 1";
					$jpartnerObj_ap_live = new Jpartner("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
					$jpartnerObj_ap_live->setPartnerDetails($profileid, $myDb_ap, $mysqlObj, "*", $partnerWhrCond2);
					$partnerWhrCond3 = " AND ROLE='ONLINE' AND ONLINE='Y' AND CREATED_BY='ONLINE'";
					$jpartnerObj_ap = new Jpartner("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
					if ($jpartnerObj_ap_live->isPartnerProfileExist($myDb_ap, $mysqlObj)) $partnerWhrCond3.= " AND DPP_ID>'" . $jpartnerObj_ap_live->getDPP_ID() . "'";
					$partnerWhrCond3.= " ORDER BY DPP_ID DESC LIMIT 1";
					$jpartnerObj_ap->setPartnerDetails($profileid, $myDb_ap, $mysqlObj, "*", $partnerWhrCond3);
					if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) $jpartnerObj_ap = $jpartnerObj_ap_live;
				}
			}
			if ($jpartnerObj_ap && $jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
				$jpartnerObj = $jpartnerObj_ap;
				if (!$APeditID) $APeditID = $jpartnerObj_ap->getDPP_ID();
				$APoperator = $jpartnerObj_ap->getCREATED_BY();
				$this->action->APeditID = $APeditID;
				$this->action->APoperator = $APoperator;
			} //If DPP is not there in Archive or temp then get dpp from newjs.Jpartner
			else $jpartnerObj->setPartnerDetails($profileid, $myDb, $mysqlObj);
		} elseif ($this->userType == UserType::AP_EXECUTIVE && $this->action->request->getParameter("editID")) {
			$this->action->matchPoint = 1;
			$this->action->matchPointPID = $this->action->request->getParameter("matchPointPID");
			$this->action->matchPointSub = $this->action->request->getParameter("matchPointSub");
			$this->action->matchPointName = $this->action->request->getParameter("matchPointName");
			$this->action->matchPointOperator = $this->action->request->getParameter("matchPointOperator");
			$this->action->matchPointCID = $this->action->request->getParameter("matchPointCID");
			$this->action->matchPointNew = $this->action->request->getParameter("matchPointNew");
			$this->action->editID = $this->action->request->getParameter("editID");
			$this->action->edited = $this->action->request->getParameter("edited");
			$this->action->matchPointPulledProfile = $this->action->request->getParameter("matchPointPulledProfile");
			$this->action->outOfQueue = $this->action->request->getParameter("outOfQueue");
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			if ($this->action->edited) {
				$partnerWhrCond = " AND CREATED_BY='" . $this->action->matchPointOperator . "'";
				$jpartnerObj = new Jpartner("Assisted_Product.AP_TEMP_DPP");
			} else {
				$partnerWhrCond = "AND DPP_ID='" . $this->action->editID . "'";
				$jpartnerObj = new Jpartner("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
			}
			$jpartnerObj->setPartnerDetails($this->action->matchPointPID, $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
		} else
		//If dpp is getting edited by non assisted user
		$jpartnerObj->setPartnerDetails($profileid, $myDb, $mysqlObj);
		$this->jpartner = $jpartnerObj;
	}
	public function getFormAction() {
	}
	public function getJpartner() {
		return $this->jpartner;
	}
	public function setJpartner(Jpartner $partner) {
		$this->jpartner = $partner;
	}
	public function getOnSubmitJs() {
	}
}
