<?php
class AppAuthentication extends ApiAuthentication
{

	public function __construct()
	{
		$this->isMobile=true;
		$this->isNotApp=0;
		$this->trackLogin=ApiCommon::getTrackLoginFlag(sfContext::getInstance()->getRequest()->getParameter("moduleName"),sfContext::getInstance()->getRequest()->getParameter("actionName"),sfContext::getInstance()->getRequest()->getParameter("version"));
	}
    public function loginFromReg()
    {
        $_COOKIE[AUTHCHECKSUM] = "";
        $this->authenticate();
    }
	public function setPaymentGatewayAuthchecksum($profileid)
	{
        $dbJprofile=new JPROFILE();
		$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
		if($profileid){
			$this->loginData=$dbJprofile->get($profileid,"PROFILEID",$paramArr);
			$this->rememberMe=true;
			$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum("",$backendCheck));
			$this->setcookies($this->loginData,'','');
			return $this->loginData;
		}
		else
		{
			$this->removeLoginCookies();
			return null;
		}
	}
	
	public function createFacebookAuthCheckum($email)
	{
		$dbJprofile=new JPROFILE();
		$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
		if($email){
			$this->loginData=$dbJprofile->get($email,"EMAIL",$paramArr);
			$this->rememberMe=false;
			$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum("",$backendCheck));
			$this->setcookies($this->loginData,'','');
			return $this->loginData;
		}
		else
		{
			$this->removeLoginCookies();
			return null;
		}
	}
	        
}
?>
