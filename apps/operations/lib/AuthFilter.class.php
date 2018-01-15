<?php
class AuthFilter extends sfFilter
{
        public function execute ($filterChain)
        {
		$context = $this->getContext();
		$request = $context->getRequest();
		$isCrmApp = $request->getParameter("crmAppApi");
		$enable_login = sfConfig::get('mod_'.strtolower($request->getParameter('module')).'_'.$request->getParameter('action').'_enable_login');
		if($enable_login!=='off' && !$isCrmApp)
		{
			if($this->isFirstCall())
			{
				$status = false;
				$authenticationLoginObj= BackendAuthenticationFactory::getBackendAuthenicationObj();
				if($request->getParameter("dialer_check")==1 || $request->getParameter("curlReq")==1)
				{
					if(isset($_COOKIE["CRM_LOGIN"]))
						$checksum = $_COOKIE["CRM_LOGIN"];
					else
						$checksum = $_GET["cid"];
				}
				else
					$checksum = null;
				$data = $authenticationLoginObj->authenticateAgent($checksum);
				if($data)
				{
					$request->setParameter("cid",$data["cid"]);
					$request->setParameter("name",$data["name"]);
					$request->setAttribute("cid",$data["cid"]);
					$request->setAttribute("name",$data["name"]);
					$request->setParameter("authFailure",0);
					$status = true;
				}
				if($status==false) //timed out/logged out case
				{
					$request->setParameter("authFailure",1);
					$context->getController()->forward("static","index");//Login page
					throw new sfStopException();
				}
			}
		}
		else if($isCrmApp)
		{		
			if($this->isFirstCall())
			{
				$login=false;
				$authenticationLoginObj= BackendAuthenticationFactory::getBackendAuthenicationObj();
				if(!$authenticationLoginObj)
				{
					$respObj = ApiResponseHandler::getInstance();
					$respObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_BACKEND_CHANNEL);
					$respObj->generateResponse();
					die();
				}
				$data=$authenticationLoginObj->authenticateAgent(null);
				$request->setAttribute('loginData', $data);
				$request->setAttribute('operatorName',$data["username"]);
				if ($data && $data['agentid']) 
				{
					$login = true;
				}
				if($login==false)
				{
					$respObj = ApiResponseHandler::getInstance();
					$respObj->setHttpArray(CrmResponseHandlerConfig::$LOGOUT_AGENT);
					$respObj->generateResponse();
					die();
				}
				else if($login==true)
				{	
					if($data[AUTHCHECKSUM])
					{
						$request->setAttribute("AUTHCHECKSUM",$data[AUTHCHECKSUM]);
					}
					else
					{
						$respObj = ApiResponseHandler::getInstance();
						$respObj->setHttpArray(CrmResponseHandlerConfig::$AGENT_AUTHENTICATION_FAILURE);
						$respObj->generateResponse();
						die();
					}
				}
			}
		}
		$filterChain->execute();
	}
}
