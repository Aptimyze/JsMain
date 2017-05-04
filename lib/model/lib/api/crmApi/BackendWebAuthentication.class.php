<?php
class BackendWebAuthentication extends BackendApiAuthentication
{
	public function __construct()
	{
		parent::__construct();
		$this->isApp=false;
		$this->trackLogin = true; //login time tracking for web
		$this->maintainSession=true;
	} 

	/*
	**** @function: authenticate agent via checksum(cid in web)
	*/ 
	public function authenticateAgent($cid=null)
	{

		$this->backendObj = new backendActionsLib(array("jsadmin_CONNECT"=>"newjs_master"),$this->useCrmMemcache); 
		if(!$cid)
			$cid = preg_replace('/[^A-Za-z0-9\. -_]/', '',$_COOKIE["CRM_NOTIFICATION_AGENTID"]);
		$name = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENT"]);
		$name = htmlentities($name);
		$cid = htmlentities($cid);
		
		if(strlen($cid)==0 || !$cid)
		{
			return null;
		}
		list($md, $userno)=explode("i",$cid);
		if(md5($userno)==$md)
		{
			$userDetails = $this->backendObj->fetchSessionDetailsBySessionID($userno);
			if($userDetails)
			{
				//if (time()-$userDetails["TIME"] < $this->sessionTimeoutLimit)
				if(1)
				{
					$this->backendObj->updateAgentSessionTime($userno);
					$this->setLoginCookies($cid,$name);
					$output = array("name"=>$name,"cid"=>$cid,"expired"=>0);
					return $output;
				}
				else
				{
					//delete expired session entry from memcache
					$this->backendObj->deleteAgentLoginSession($userno);
					//unset login cookies
					$this->unsetLoginCookies();
					return null;
				}
			}
			else
				return null;
		}
		return null;
	} 

	public function unsetLoginCookies()
	{
		$dom="";
		@setcookie("CRM_NOTIFICATION_AGENTID",'',0,"/",$dom);
		@setcookie("CRM_NOTIFICATION_AGENT",'',0,"/",$dom);
	} 

	public function setLoginCookies($cid,$name)
	{
		$dom = "";
		@setcookie("CRM_NOTIFICATION_AGENTID",$cid,time() + $this->sessionTimeoutLimit,"/",$dom);
		@setcookie("CRM_NOTIFICATION_AGENT",$name,time() + $this->sessionTimeoutLimit,"/",$dom);
	} 

	 
}
?>
