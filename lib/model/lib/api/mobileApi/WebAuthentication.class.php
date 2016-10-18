<?php
class WebAuthentication extends ApiAuthentication
{

	public function __construct()
	{
		if(MobileCommon::isMobile() || MobileCommon::isNewMobileSite())
			$this->isMobile=true;
		$this->isNotApp=1;
		$this->trackLogin=ApiCommon::getTrackLoginFlag(sfContext::getInstance()->getRequest()->getParameter("moduleName"),sfContext::getInstance()->getRequest()->getParameter("actionName"),sfContext::getInstance()->getRequest()->getParameter("version"));
	}
    public function loginFromReg()
    {
        $_COOKIE[AUTHCHECKSUM] = "";
        $this->authenticate();
    }  
    public function setTrackLogin($flag)
    {
		$this->trackLogin=$flag;
	}
	public function removeAuthChecksum()
	{
		$_COOKIE[AUTHCHECKSUM] = "";
	}
	public function setCrmAdminAuthchecksum($checksum,$backendCheck)
	{
		$epid_arr=explode("i",$checksum);
        $profileid=$epid_arr[1];
        $dbJprofile=new JPROFILE();
		$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
		if($profileid){
			$this->loginData=$dbJprofile->get($profileid,"PROFILEID",$paramArr);
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
	public function decrypt($echecksum,$fromAutologin="N")
    {
        return $this->js_decrypt($echecksum,$fromAutologin);
    } 
    
    public function removeCookies()
    {
		$this->removeLoginCookies();
	}
	
	public function setAutologinAuthchecksum($checksum,$loc)
	{
			$epid_arr=explode("i",$checksum);
			$profileid=$epid_arr[1];
			$this->mailerProfileId=$profileid;
			//if(!$this->authenticate(null,0,'Y')){
			//print_r(sfContext::getInstance()->getRequest()->getAttribute("profileid"));die;
			//echo ['profileid'];die;
			if(sfContext::getInstance()->getRequest()->getAttribute('profileid')!=$this->mailerProfileId){
			//echo "ASD";die;
			$dbJprofile=new JPROFILE();
				
			$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
				
			$this->loginData=$dbJprofile->get($profileid,"PROFILEID",$paramArr);
			$this->RecentUserEntry();
			$this->insert_into_login_history($this->loginData["PROFILEID"]);
                        
                        if(strpos($loc,"linkFromSMS")!==false) // for autologin from sms 
                            $channel='S';
                        else 
                            $channel='M';
			$this->loginTracking($this->loginData[PROFILEID],$channel,'',$loc);			
			$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum());
			$this->removeLoginCookies();
			$this->setcookies($this->loginData,'','');
			return $this->loginData;
		}
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
}
?>
