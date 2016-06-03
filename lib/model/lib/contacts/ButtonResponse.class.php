<?php
Class ButtonResponse
{
	private $buttonResponseObj;
	public function __construct($loginProfile='', $otherProfile='', $page='', $contactHandler = "")
	{
		if(MobileCommon::isNewMobileSite() || MobileCommon::isApp()=='I')
			$this->buttonResponseObj = new ButtonResponseJSMS($loginProfile, $otherProfile, $page, $contactHandler);
		elseif(MobileCommon::isAPP()=="A")
			$this->buttonResponseObj = new ButtonResponseApi($loginProfile, $otherProfile, $page, $contactHandler );
		else
			$this->buttonResponseObj = new ButtonResponseFinal($loginProfile, $otherProfile, $page, $contactHandler );
	}
	public function __call($functionName,$arguements)
	{
		if($this->buttonResponseObj)
			return call_user_func_array(array($this->buttonResponseObj,$functionName),$arguements);
	}
	public static function __callStatic($functionName,$arguements)
        {
		if(MobileCommon::isNewMobileSite() || MobileCommon::isApp()=='I')
			return call_user_func_array(array("ButtonResponseJSMS",$functionName),$arguements);
		elseif(MobileCommon::isAPP()=="A")
			return call_user_func_array(array("ButtonResponseApi",$functionName),$arguements);
		else
			return call_user_func_array(array("ButtonResponseFinal",$functionName),$arguements);

        }

}
