<?php

/**
 * commoninterface actions.
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commoninterfaceActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function preExecute()
        {
        	    $request=sfContext::getInstance()->getRequest();
                $this->cid=$request->getParameter(cid);
                $this->user=JsOpsCommon::getcidname($this->cid);
		$this->mes=$request->getParameter(mes);
	$this->username=$premiumUser=$request->getParameter("puser");
	$this->dusername=$dummyUser=$request->getParameter("duser");

        }
  public function executeIndex(sfWebRequest $request)
  {
    $this->forwardTo('default', 'module');
  }
  public function executePremiumUser(sfWebRequest $request)
  {

  }
  public function executeGenerateAutologinLink(sfWebRequest $request)
  {
        $this->cid = $request->getAttribute("cid");
	$this->name = $request->getAttribute('name');
	if($request->getParameter("reset"))
	{
		$this->autologinUrl = '';
		$logData[0]['EXECUTIVE_NAME']=$this->name;
		$logData[0]['PROFILEID']=$request->getParameter('profileid');
		$logData[0]['IP']=FetchClientIP();
		$autologinTrackingObj=new MIS_AUTOLOGIN_TRACKING;
		$autologinTrackingObj->insert($logData);
	}
	elseif($request->getParameter("submit"))
	{
		$username = $request->getParameter("username");
		$email = $request->getParameter("email");

		$formArr = $request->getParameterHolder()->getAll();
		$this->profile = Operator::getInstance();
		if($username!='')
		{
			$this->username=$username;
			$this->profile->getDetail($username,'USERNAME','PROFILEID');
		}
		else
		{
			$this->email=$email;
			$this->profile->getDetail($email,'EMAIL','PROFILEID');
		}
		if($this->profile->getPROFILEID()==NULL || $this->profile->getPROFILEID()=='') //if invalid username
		{
			$this->error = 1;
		}
		else //if valid username was entered and profileid is obtained
		{
			$this->profileid = $this->profile->getPROFILEID();
			//global $protect;
			//JsCommon::oldIncludes();
			//$protect = new protect();
			//$protect->logout();
			$checksum = md5($this->profile->getPROFILEID()) . "i" . $this->profile->getPROFILEID();
		//	$echecksum = $protect->js_encrypt($checksum);
			$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj(null);
			$authenticationLoginObj->setTrackLogin(false);
			$authenticationLoginObj->setCrmAdminAuthchecksum($checksum);
			$this->autologinUrl = JsConstants::$siteUrl;//JsConstants::$siteUrl . "?echecksum=" . $echecksum . "&checksum=" . $checksum;
			//$this->profileid = $this->profile->getPROFILEID();
		}
	}
	$this->setTemplate('generateAutologin');
  }
  public function executeGenerateAutologin(sfWebRequest $request)
  {
        $name = $request->getAttribute('name');
        $this->cid = $request->getAttribute("cid");
        $username = $request->getParameter("username");
        $email = $request->getParameter("email");

        $formArr = $request->getParameterHolder()->getAll();
	$this->profile = Operator::getInstance();
	if($username!='')
	{
		$this->username=$username;
		$this->profile->getDetail($username,'USERNAME','PROFILEID');
	}
	else
	{
		$this->email=$email;
		$this->profile->getDetail($email,'EMAIL','PROFILEID');
	}
	if($this->profile->getPROFILEID()==NULL || $this->profile->getPROFILEID()=='') //if invalid username
	{
		$this->error = 1;
	}
	else //if valid username was entered and profileid is obtained
	{
                include_once(sfConfig::get("sf_web_dir"). "/classes/authentication.class.php");
		$protect = new protect();
		$checksum = md5($this->profile->getPROFILEID()) . "i" . $this->profile->getPROFILEID();
		$echecksum = $protect->js_encrypt($checksum);
		$this->autologinUrl = JsConstants::$siteUrl . "?echecksum=" . $echecksum . "&checksum=" . $checksum;
	}
	$this->setTemplate('generateAutologin');
  }
  public function executeUpdatePremiumUser(sfWebRequest $request)
  {
	$this->puser=$premiumUser=trim($request->getParameter("username"));
	$this->duser=$dummyUser=trim($request->getParameter("dusername"));
	//$remove=$request->getParameter("Remove");
	$add=$request->getParameter("Add");
	if((!$premiumUser && !$remove) || !$dummyUser)
	{
		if(!$premiumUser)
			$mes="Please fill premium User";
		else
			$mes="Please fill dummy User";
		$this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
	}
	if($premiumUser == $dummyUser)
	{
		$mes="Both users are same";
		 $this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
	}
	if($premiumUser)
	{
		$profile = Profile::getInstance();
		$profile->getDetail($premiumUser,"USERNAME","PROFILEID","RAW");
		if($profile->getPROFILEID()==null && !$remove)
		{
			$mes="Premium User $premiumUser doesnot exist ";
			$this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
		}
	
	$pID=$profile->getPROFILEID();
	}
	unset($profile);
	$profile = Profile::getInstance();
        $profile->getDetail($dummyUser,"USERNAME","PROFILEID","RAW");
        if($profile->getPROFILEID()==null)
        {
                $mes="Dummy User $dummyUser doesnot exist ";
                $this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
        }
	$dID=$profile->getPROFILEID();
	if($add)
	{
		$dbObj=new jsadmin_PremiumUsers;
		
		//check if dummy already exists in jsadmin.PremiumUsers
		if($dbObj->isDummy($dID))
		{
			$mes="Profile $dummyUser is already an existing dummy";
		}
		else
		{ 
			//activate JsExclusive for dummy and add record in table PremiumUsers on success
			$JsExlusiveActivatedFlag = $this->activateJsExcusiveForDummy($pID,$dID,$this->duser,$this->user);
			if($JsExlusiveActivatedFlag == true)
			{
				if(!$dbObj->AddUser($pID,$dID,date("Y-m-d H:i:s")))
					$mes ="Record already present";
				else
					$mes ="Successfully Added";
			}
			else
				$mes = "$premiumUser is not JsExclusive member";

		}
		$this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
	}
	/*if($remove)
	{
		$dbObj=new jsadmin_PremiumUsers;
		if(!$dbObj->RemoveUser($dID))
		{
			$mes=" No Record to delete";
		}
		else
			$mes="Successfully deleted";
		$this->forwardTo("commoninterface","premiumUser?mes=$mes&cid=".$this->cid);
	}*/
  }
  public function forwardTo($module,$action)
  {
    $url="/operations.php/$module/$action&puser=".$this->puser."&duser=".$this->duser;
    $this->redirect($url);
  }
  
  /**
  * activates JsExclusive for exclusive dummy profile
  *
  * @param $premiumProfileID,$dummyProfileID,$dummyUsername,$operatorName
  * @return true on success else false
  */
  public function activateJsExcusiveForDummy($premiumProfileID,$dummyProfileID,$dummyUsername,$operatorName)
  {
	include_once(JsConstants::$docRoot."/profile/connect_db.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
	connect_db();
    $billingObj = new billing_SERVICE_STATUS("newjs_slave"); 
    $activeServiceDetails =$billingObj->checkJsExclusiveServiceIDEver($premiumProfileID);
    list($mainServiceID,$mainServiceDuration) = sscanf($activeServiceDetails["SERVICEID"], "%[A-Z]%d");
    if($mainServiceID == 'X')
    {
        $jprofileObj = new JPROFILE('newjs_slave');
        $profileDetailsArray = array();
        $profileDetailsArray=$jprofileObj->get($dummyProfileID,"PROFILEID","GENDER,CITY_RES,CONTACT,PINCODE,EMAIL,PHONE_RES,PHONE_MOB");
		if($profileDetailsArray["CITY_RES"])
		{
			$cityNewObj= new newjs_CITY_NEW("newjs_slave");
			$result = $cityNewObj->getCityLabel($profileDetailsArray["CITY_RES"]);
			$cityName = $result["LABEL"];
		}
		else
			$cityName = "";
		$membershipObj = new Membership;
		
		//auto bill RB for dummy profile with Js Exclusive and Js Boost
		$servicesObj = new billing_SERVICES("newjs_slave");
		$RBServiceID = "T".$mainServiceDuration;
		$RBDetails = $servicesObj->fetchServiceDetails(array($RBServiceID));
		if(is_array($RBDetails) && is_array($RBDetails[$RBServiceID]) && $RBDetails[$RBServiceID]['ACTIVE'] == 'Y'){
			$autoActivateServices = $activeServiceDetails["SERVICEID"].",".$RBServiceID;
		}
		else{
			$autoActivateServices = $activeServiceDetails["SERVICEID"];
		}
		
		$membership_details["serviceid"] = $autoActivateServices;
		$membership_details["profileid"] = $dummyProfileID;
        $membership_details["custname"] = $dummyUsername;
		$membership_details["username"] = $dummyUsername;
		$membership_details["address"] = $profileDetailsArray["CONTACT"];
		$membership_details["gender"] = $profileDetailsArray["GENDER"];
		$membership_details["city"] = $cityName;
		$membership_details["entryby"] = $operatorName;
		$membership_details["pin"] = $profileDetailsArray["PINCODE"];
		$membership_details["email"] = $profileDetailsArray["EMAIL"];
		$membership_details["rphone"] = $profileDetailsArray["PHONE_RES"];
		$membership_details["mphone"] = $profileDetailsArray["PHONE_MOB"];
		$membership_details["curtype"] = "RS";
		$membership_details["deposit_date"] = date('Y-m-d');
		$serviceObj = new billing_SERVICES("newjs_slave");
		$priceRsDetails= $serviceObj->fetchServiceDetailForRupeesTrxn(explode(",",$membership_details["serviceid"]), 'desktop');
		
		$totalActualAmount = 0;
		foreach ($priceRsDetails as $k1 => $v1) {
			$totalActualAmount += $v1['PRICE'];
		}
		$membership_details["deposit_branch"] = "NOIDA";
		$membership_details["discount"] = $totalActualAmount;
		$membership_details["amount"] = 0;
		$membership_details["discount_type"] = "2";
		$membership_details["discount_reason"] = "100% disount for dummy profile";
		$membership_details["entry_from"] = "N";
		$membership_details["walkin"] = $operatorName;
		$pswrdsObj = new jsadmin_PSWRDS("newjs_slave");
		$membership_details["center"] = $pswrdsObj->getCenter($operatorName);
		$membership_details["source"] = "CASH";
		//$membership_details["ip"] = FetchClientIP();
		$membership_details["status"] = "DONE";
		$membership_details["mode"] = "CASH";
		$membership_details["obank"] = "Y";
		$membership_details["dol_conv_bill"]='N';
		//payment for JsExclusive for dummy
		$membershipObj->startServiceBackend($membership_details);
		$membershipObj->makePaid();
		return true;
    }
    else
    	return false;
  }
  /*Transfer VD entries from test.VD_UPLOAD_TEMP to billing.VARIABLE_DISCOUNT_TEMP table
  * @param: $params
  * MINI-VD Function
  */
  private function transferVDRecords($params)
  {
  	$uploadIncomplete = false;
	$tempObj = new billing_VARIABLE_DISCOUNT_TEMP('newjs_master');
	if($uploadIncomplete==false){
		$tempObj->truncateTable();
	}
	unset($tempObj);
  	$offset = 0;
	$limit = $params["limit"];    
	//get uploaded records(with limit and offset at a time)
	$vdObj = new VariableDiscount();
	$status = $vdObj->transferVDRecordsToTemp($limit,$offset);

   	if($status==uploadVD::EMPTY_SOURCE){
    		//show error message
		$this->forwardTo("commoninterface","uploadVD?NODATA=1&cid=".$this->cid);
    	}
    	if($status==uploadVD::INCOMPLETE_UPLOAD){
    		//show error message
		$this->forwardTo("commoninterface","uploadVD?INCOMPLETEUPLOAD=1&cid=".$this->cid);
    	}
  }

  /* function executeUploadVD
  * @param: request Object
 * MINI-VD STEP-0 
  */
  public function executeUploadVD(sfWebRequest $request)
  {
  		if($request->getParameter("SUCCESSFUL")){
			// successfully upoaded
  			$this->SUCCESSFUL = 1;
		}
  		else if($request->getParameter("UNAUTHORIZED")){
			// privilege based
  			$this->UNAUTHORIZED = 1;
		}
                else if($request->getParameter("NODATA")){
			// No data in temp table(test.VD_UPLOAD_TEMP)
                        $this->NODATA=1;
		}
  		else if($request->getParameter("INCOMPLETEUPLOAD")){
			//incomplete upload in temp table(billing.VARIABLE_DISCOUNT_TEMP)
  			$this->ERROR = 1;
		}
  		else if($request->getParameter("BACKGROUND_SCRIPT_FAILURE"))
  			$this->BACKGROUND_FAILURE=1;
  		else
  			$this->UPLOAD = 1;
  }

  /* function executeUpdateVDRecords
  * uploads data from table to VD tables
  * @param: request Object
  * MINI-VD STEP-1
  */
  public function executeUpdateVDRecords(sfWebRequest $request)
  {
  	include_once(JsConstants::$docRoot."/jsadmin/connect.inc");
	$privilage = explode("+",getprivilage($this->cid));
	if(in_array("IA",$privilage))
	{
		//Start -transfer records from client table to temp table
		//$params["limit"] = uploadVD::$RECORDS_SELECTED_PER_TRANSFER; //no of records picked at a time
		$params["limit"] =1000000;
		$this->transferVDRecords($params);
		//End -transfer records        

		// Background script execute to populate entries to Main VD tables
		passthru(JsConstants::$php5path." ".JsConstants::$alertSymfonyRoot."/symfony billing:populateVDEntriesFromTempTable > /dev/null &");
		/*if($out!=0){
			$message = "Error in running populateVDEntriesFromTempTable cron";
			$this->forwardTo("commoninterface","uploadVD?BACKGROUND_SCRIPT_FAILURE=1&cid=".$this->cid);
		}*/
		//show success message
		$this->forwardTo("commoninterface","uploadVD?SUCCESSFUL=1&cid=".$this->cid);
	}
	else{
		$this->forwardTo("commoninterface","uploadVD?UNAUTHORIZED=1&cid=".$this->cid);
	}	
  }

  public function executeWebServiceMonitoring(sfWebRequest $request)
  {

	  $url = JsConstants::$contactUrl . "/v1/contacts";
	 $url = $url . "/getloggingdata";
	  $result = CommonUtility::webServiceRequestHandler($url);
	  $date = date("Ymd");
	  if(is_array($result[$date."_CACHE_NOT_SET"])) {
		  foreach ($result[$date . "_CACHE_NOT_SET"] as $key => $value) {
			  $this->cache_not_set += $value;
		  }
	  }
	  if(is_array($result[$date."_SQL_QUERY"])) {
		  foreach ($result[$date . "_SQL_QUERY"] as $key => $value) {
			  $this->sql_query += $value;
		  }
	  }
	$this->cache_connection =  $result[$date."_CACHE_CONNECTION"];
	$this->cache_problem_sql =  $result[$date."_SQL_REQUEST"];
	$this->cache_inprocess = $result[$date."_INPROCESS_CACHE_REQUEST"];
	$this->create_cache_process =  $result[$date."_CREATE_CACHE_INPROCESS"];
	$this->total =  $result[$date."_Total"];
	$this->execption = $result[$date."_EXECPTION"];
	$this->setTemplate('webservicemonitor');
  }
  
  public function executeSelectGateway(sfWebRequest $request)
  {
    $this->cid = $request->getAttribute("cid");
    $this->name = $request->getAttribute('name');
    $this->newGateway = $request->getParameter('payment');  
    //$path = '../lib/model/enums/SelectGatewayRedirect.enum.class.php';
    //$content = htmlspecialchars(file_get_contents($path));
    //preg_match('/&quot;([^"]+)&quot;/', $content, $m);  
    $billingSelectedGateway = new billing_CURRENT_GATEWAY('newjs_master');
    $this->preSelectedGateway = JsMemcache::getInstance()->get('JS_PAYMENT_GATEWAY');
    $gatewayOption = SelectGatewayRedirect::$gatewayOptions;
    if(!in_array($this->preSelectedGateway,$gatewayOption) || $this->preSelectedGateway == ''){
        $this->preSelectedGateway = $billingSelectedGateway->fetchCurrentGateway();
    }
    if($request->getParameter('gatewaySubmit')){
        //$newContent = (str_replace($this->preSelectedGateway, $this->newGateway, $content));
        //file_put_contents($path, htmlspecialchars_decode($newContent));
        $billingSelectedGateway->setCurrentGateway($this->newGateway,$this->name);
        JsMemcache::getInstance()->set('JS_PAYMENT_GATEWAY',$this->newGateway);
        $this->preSelectedGateway = $this->newGateway;
        $this->message = "Gateway changed to ".$this->newGateway;
    }
  }
  public function executeGetChangeInfo(sfWebRequest $request) 
  {
    $formArr = $request->getParameterHolder()->getAll();
    $this->cid = $formArr['cid'];
    $this->errorMsg = "";
    $this->isSubmit =0;
    if ($formArr['submit']) {
            if($formArr["user_username"] == ""){
                    $this->errorMsg = "Please enter Username";
            }else{
                        $this->profile = Operator::getInstance();
			$this->username=$formArr["user_username"];
			$this->profile->getDetail($formArr["user_username"],'USERNAME','PROFILEID');
                        if($this->profile->getPROFILEID()==NULL || $this->profile->getPROFILEID()=='') //if invalid username
                        {
                                $this->errorMsg = "Wrong Username entered!";
                        }else{
                                $this->isSubmit =1;
                                $this->fieldsToGet = array("GENDER"=>"0", "RELIGION"=>"religion", "CASTE"=>"caste","PHONE_MOB"=>"0","EMAIL"=>"0","COUNTRY_RES"=>"country","CITY_RES"=>"city_india","MTONGUE"=>"community_small","INCOME"=>"income_level","EDU_LEVEL_NEW"=>"edu_level_new","OCCUPATION"=>"occupation","MANGLIK"=>"manglik","MSTATUS"=>"mstatus","DTOFBIRTH"=>"0","PRIVACY"=>"0","PHOTO_DISPLAY"=>"photo_privacy","SHOWPHONE_MOB"=>"privacy_option");
                                $this->fieldsToGetLabel = array("GENDER"=>"Gender","RELIGION"=>"Religion", "CASTE"=>"Caste","PHONE_MOB"=>"Phone Mobile","EMAIL"=>"Email","COUNTRY_RES"=>"country","CITY_RES"=>"City","MTONGUE"=>"Mtongue","INCOME"=>"Income","EDU_LEVEL_NEW"=>"Education","OCCUPATION"=>"Occupation","MANGLIK"=>"Manglik","MSTATUS"=>"Mstatus","DTOFBIRTH"=>"Date of Birth","PRIVACY"=>"Privacy","PHOTO_DISPLAY"=>"Photo Privacy","SHOWPHONE_MOB"=>"Show Mobile");
                                $editLogObj = new NEWJS_EDIT_LOG("crm_slave");
                                $changeData = $editLogObj->getDetails($this->profile->getPROFILEID(),implode(",",array_keys($this->fieldsToGet)));
                                $changedArray = array();
                                foreach($changeData as $k=>$data){
                                        $a = array_filter($data);
                                        if(empty($a) || (count($a) == 2 && $a["DTOFBIRTH"] == "0000-00-00")){
                                        }else{
                                                foreach($this->fieldsToGet as $ky=>$lbl){
                                                        if($lbl != "0" && $data[$ky] !="0" && $data[$ky] !=""){
                                                                if($lbl == "city_india"){
                                                                        if(substr($data[$ky], 2) == "OT"){
                                                                                $data[$ky] = FieldMap::getFieldLabel("state_india",substr($data[$ky],0,2))." Others";
                                                                        }else{
                                                                                $data[$ky] = FieldMap::getFieldLabel($lbl,$data[$ky]);
                                                                        }
                                                                }else{
                                                                        $data[$ky] = FieldMap::getFieldLabel($lbl,$data[$ky]);
                                                                }
                                                        }elseif($data[$ky] == "0"){
                                                                $data[$ky] = "";
                                                        }elseif($data[$ky] == "0000-00-00"){
                                                                $data[$ky] = "";
                                                        }
                                                }
                                                $changedArray[$k] = $data;
                                        }
                                }
                                $this->changedArray = $changedArray;
                        }
            }            
    }
  }
}
