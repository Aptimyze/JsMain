<?php
Abstract class BackendApiAuthentication
{
	//private $request;
	protected $loginData;
	protected $lastLoggedInOffset = "- 15 days";  //user should be last logged in within 15 days
	protected $accountExpiryCheck = true;
	protected $agentIP;       //agent ip
	protected $sessionTimeoutLimit = 14400;  //default login session timeout
	protected $isApp=false;   //app channel or not
	protected $trackLogin=true;  //allow login tracking
	protected $maintainSession=false;  //maintain login session---not for app
	protected $backendObj;
	protected $_KEY = "Radha Swami";
	protected $_SUBKEY = "all is well";
	protected $encryptSeparator="______";
	protected $useCrmMemcache = true;  //use memcache for CONNECT queries

	/**
	  * Executes Constructor
	  *
	  * @param $request A request object
	**/
	public function __construct(/*$request*/)
	{
		//$this->request=$request;
		$this->crmApiCommonFuncObj = new crmApiCommonFunctions($this->_KEY,$this->_SUBKEY,$this->encryptSeparator);
		$this->agentIP = FetchClientIP();
		$this->useCrmMemcache = crmCommonConfig::$useCrmMemcache;

	}

	/**
	  * Executes backend login using passed credentials
	  *
	  * @param $loginCredentials
	  * @return $loginData
	**/
	public function backendLogin($loginCredentials=array())
	{	
		$this->backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_master","jsadmin_CONNECT"=>"newjs_master"),$this->useCrmMemcache);
		//get login details
		$this->loginData = $this->backendObj->getLoginDetails($loginCredentials);

		//check if agent has not logged in within last defined offest(i.e. expired account)
		if($this->loginData)
		{
			$this->loginData["username"] = $loginCredentials["USERNAME"];
			$this->loginData["privilege"] = explode("+",$this->loginData["privilege"]);
			$this->loginData["accessibleLinks"] = fsoInterfaceDisplay::$linksMapping;
			$expired = 0;
			if($this->accountExpiryCheck==true)
			{
				$expired = $this->backendObj->checkExpiredLoginForAgent($this->loginData,$this->lastLoggedInOffset);
			}
			if($expired)
			{
				/*$params = array("ACTIVE"=>'N');
				if($this->loginData['active']=='Y')
					$this->backendObj->updateAgentDetailsInPSWRDS($params,$this->loginData['agentid']);*/
				unset($this->loginData);
				$this->loginData["expired_account"] = 1;
			}
			else
			{
				$this->loginData["expired_account"] = 0;
				if($this->isApp==true)
					$this->loginData[AUTHCHECKSUM] = $this->crmApiCommonFuncObj->encryptAppendTime($this->createAgentAuthChecksum());			

				//maintain session if not app
				if($this->maintainSession==true)
				{
					//delete all expired accounts from jsadmin.CONNECT table if memcache not used
					$this->backendObj->deleteExpiredLoginSession($this->sessionTimeoutLimit);
					//create login session
					$this->maintainLoginSessionForAgent(); 
				}
				
				//record login time if not app
				if($this->trackLogin==true)
				{
					$this->recordAgentLoginHistory();
				}
			}
			unset($this->loginData["active"]);
			unset($this->loginData["last_login_dt"]);
		}
		return $this->loginData;
	}

	/**
	  * record login history of agent
	  *
	  * @param none
	**/
	private function recordAgentLoginHistory()
	{
		//update current agent login details
		$params = array("LAST_LOGIN_DT"=>date('Y-m-d H:i:s'));
		$this->backendObj->updateAgentDetailsInPSWRDS($params,$this->loginData['agentid']);
	}

	/**
	  * maintain login session for agent
	  *
	  * @param none
	**/
	public function maintainLoginSessionForAgent()
	{
		$params = array("USER"=>$this->loginData['agentid'],"IPADDR"=>$this->agentIP);
		//update login session
		$last_inserted_id = $this->backendObj->createLoginSessionForAgent($params);
		$this->loginData["cid"]=Encrypt_Decrypt::encryptIDUsingMD5($last_inserted_id);
	}

	 /*
	**** @function: create authChecksum for agents
	*/ 
	public function createAgentAuthChecksum()
	{
		$checksum=Encrypt_Decrypt::encryptIDUsingMD5($this->loginData["agentid"]);
		$authChecksum="ID=".$this->crmApiCommonFuncObj->js_encrypt($checksum);
		$authChecksum.=":RE=".$this->loginData["agentid"];
		$authChecksum.=":US=".$this->loginData["username"];
	    //$authChecksum.=":PR=".$this->loginData["privilege"];
		$authChecksum.=":TM=".time();
		return $authChecksum;
	}

	/*
	**** @function: authenticate agent via authchecksum
	*/ 
	public function authenticateAgent($authChecksum=null)
	{
		if(!$authChecksum)
			$authChecksum=sfContext::getInstance()->getRequest()->getParameter("AUTHCHECKSUM");

		if(strlen($authChecksum)==0 || !$authChecksum)
		{
			return null;
		}
		$decryptObj= new Encrypt_Decrypt();
		$decryptedauthChecksum=$decryptObj->decrypt($authChecksum);
		$loginData=$this->fetchAgentLoginData($decryptedauthChecksum);
		if($loginData["agentid"] && $this->crmApiCommonFuncObj->js_decrypt($loginData[CHECKSUM]))
		{
			$this->loginData = $this->IsAgentAlive($loginData);
			if($this->loginData)
				$this->loginData[AUTHCHECKSUM] = $this->crmApiCommonFuncObj->encryptAppendTime($this->createAgentAuthChecksum());
			return $this->loginData;
		}
		else
			return null;
	}  

	
	/*
	**** @function: fetchLoginData
	*/
	public function fetchAgentLoginData($checksum)
	{
		
		if($checksum)
		{
			$temp=$this->crmApiCommonFuncObj->explode_assoc('=',':',$checksum);
						
			$data["agentid"]=$temp['RE'];
			$data["username"]=$temp['US'];
			//$data["privilege"]=$temp['PR'];
			$data["CHECKSUM"]=$temp['ID'];
			$data["time"]=$temp['TM'];

			return $data;
		}
		return null;
	}

	/*
	* @function: IsAlive
	* check whether agent is still active or not
	* @param array loginData
	*/
	public function IsAgentAlive($loginData)
	{
		$loggedInAgentObj=LoggedInAgent::getInstance("newjs_master");
		$loggedInAgentObj->getDetail($loginData['agentid'],"","");
		
		//If any changes Found then logout agent
		if($loggedInAgentObj->getACTIVE()=='N')
		{
			return null;
		}
		else
		{
			$loginData["privilege"] = explode("+",$loggedInAgentObj->getPRIVILAGE());
			return $loginData;
		}
	}
		
}
?>
