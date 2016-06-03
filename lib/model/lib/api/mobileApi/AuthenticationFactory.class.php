<?php
class AuthenticationFactory
{

	public function __construct()
	{
	}
    public static function getAuthenicationObj($rememberMe)
    {
		if((MobileCommon::isNewMobileSite() || MobileCommon::isDesktop() || MobileCommon::isMobile() ||  MobileCommon::isAppWebView()) && !MobileCommon::isApp())
			$authenticationObj=new WebAuthentication($rememberMe);
		else
			$authenticationObj=new AppAuthentication($rememberMe);
        
        return $authenticationObj;
    }        
}
