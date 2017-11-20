<?php
/**
 * realSearchAction
 * This is honey pot url to detect web scraper ips
 * @package    jeevansathi
 * @subpackage api
 * @author     Reshu Rajput
 * @date	   11th Oct 2017
 */
class realSearchAction extends sfActions
{ 	
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	//Member Functions
	public function execute($request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();		
		$loginData=$request->getAttribute("loginData");
		$profileId = 0;
			$email ="";
		if(is_array($loginData) && !empty($loginData))
		{
			$profileId = $loginData["PROFILEID"];
			$email = $loginData["EMAIL"];
		}
		$ip=$this->getRealIpAddr();
			$scraper = new GEOIP_SCRAPPERIPS("newjs_masterRep");
				$scraper->insertTrackingData($ip,$profileId,$email);	
					
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			die;
		}
		
	
	
	function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
}
