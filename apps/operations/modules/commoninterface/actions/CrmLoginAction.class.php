<?php

// crm login action.
// @package    operations
// @subpackage commoninterface
// @author     Ankita Gupta

class CrmLoginAction extends sfActions
{

	/**
	  * Executes index action
	  *
	  * @param sfRequest $request A request object
	**/
	function execute($request){
		
		//it starts zipping
		$zipIt = 0;
		if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			$zipIt = 1;
		if($zipIt)   
			ob_start("ob_gzhandler");
		// csv Generation url start
		if(!$httpReferer)
			$httpReferer =$_SERVER['HTTP_REFERER'];
		if(strstr("$httpReferer",'processName'))
			$csvGenerationUrl=true;

		$username = $request->getParameter("username");
		//set api post params
		$request->setParameter("USERNAME",$username);
		$request->setParameter("PASSWORD",$request->getParameter("password"));
		$request->setParameter("fromInternal",1);

		//fetch login api response		
	    ob_start();    
	    sfContext::getInstance()->getController()->getPresentationFor('crmApi','ApiCrmAuthenticateV1');
		$jsonResponse = ob_get_contents(); 		
		$response = json_decode($jsonResponse,true);		
		ob_end_clean();
		
		if($response["responseStatusCode"]== CrmResponseHandlerConfig::$AGENT_LOGIN_SUCCESS["statusCode"])
		{
			if($request->getParameter("from_dialer_inbound")=='Y' || $request->getParameter("from_dialer")=='Y' || $request->getParameter("from_dialer_phone")=='Y')
				$dialer = true;
			else
				$dialer = false;
			if(in_array('OB', $response["privilege"]))
			{
				$dom = "";
				setcookie("OPERATOR",$response["username"],0,"/",$dom);
			}
			$this->setLoginCookies($response["cid"],$response["username"],$dialer);
			if($csvGenerationUrl)
                header("Location: ".$httpReferer."&name=".$response["username"]."&cid=".$response["cid"]);
            else
            {
            	if($request->getParameter("authFailure")==1)
            		header("Location:".$httpReferer);
            	else
            	{
            		if($httpReferer==JsConstants::$siteUrl."/mis/")
            			header("Location:".JsConstants::$siteUrl."/mis/mainpage.php");
            		else
						header("Location:".JsConstants::$siteUrl."/jsadmin/mainpage.php");
            	}
			}
			die;	
		}
		else
		{
			$this->EXPIRE = $request->getParameter("EXPIRE");
			$this->INVALID = $request->getParameter("INVALID");
			$this->username = $username;
			if($csvGenerationUrl)
				$this->httpReferer = $httpReferer;
			$this->forward("static","index");
		}
		if($zipIt)  
			ob_end_flush();
	}

	private function setLoginCookies($cid,$name,$dialer=false)
	{
		$dom = "";
		$timeout = 14400;
		$name = preg_replace('/[^A-Za-z0-9\. -_]/', '', $name);
		setcookie("CRM_NOTIFICATION_AGENTID",$cid,time() + $timeout,"/",$dom);
		setcookie("CRM_NOTIFICATION_AGENT",$name,time() + $timeout,"/",$dom);
		if($dialer==true)
		{
			setcookie("CRM_LOGIN",$cid,time() + $timeout,"/",$dom);
		}
	} 
}
?>
