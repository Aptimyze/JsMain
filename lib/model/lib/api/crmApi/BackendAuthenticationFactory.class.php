<?php
class BackendAuthenticationFactory
{
	/**
	  * Executes constructor
	  *
	  * @param none
	**/
	public function __construct()
	{
	}

	/**
	  * return BackendAuthenicationObj according to channel
	  *
	  * @param none
	  * @return BackendAuthenicationObj
	**/
    public static function getBackendAuthenicationObj()
    {  
		if(MobileCommon::isCrmApp()=="A")
			$authenticationObj=new BackendAppAuthentication();
		else if(MobileCommon::isCrmDesktop())
			$authenticationObj=new BackendWebAuthentication();
		else
			$authenticationObj = null; 
        return $authenticationObj;
    }        
}
