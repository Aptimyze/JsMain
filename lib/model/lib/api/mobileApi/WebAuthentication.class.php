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
	public function setCrmAdminAuthchecksum($checksum,$loginData)
	{
            
		$epid_arr=explode("i",$checksum);
        $profileid=$epid_arr[1];
       
        if($loginData['PROFILEID']) // HERE WE EXPECT ALL THE NECESSARY DATA FIELDS .. TO KNOW THE SAME REFER TO $this->createAuthChecksum() 
            $this->loginData=$loginData;
        else 
        {
                $dbJprofile=new JPROFILE();
			
		$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
			
		$this->loginData=$dbJprofile->get($profileid,"PROFILEID",$paramArr);
		
        }
                $this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum());
		$this->removeLoginCookies();
		$this->setcookies($this->loginData,'','');
		return $this->loginData;
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
		if(!$this->authenticate(null,0,'Y')){
			
			$dbJprofile=new JPROFILE();
				
			$paramArr='PROFILEID,DTOFBIRTH,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,PASSWORD,PHONE_MOB,EMAIL';
				
			$this->loginData=$dbJprofile->get($profileid,"PROFILEID",$paramArr);
			$this->RecentUserEntry();
			$this->insert_into_login_history($this->loginData["PROFILEID"]);
			$this->loginTracking($this->loginData[PROFILEID],"M",'',$loc);			
			$this->loginData[AUTHCHECKSUM]=$this->encryptAppendTime($this->createAuthChecksum());
			$this->removeLoginCookies();
			$this->setcookies($this->loginData,'','');
			return $this->loginData;
		}
	}
}
?>
