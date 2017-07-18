<?php
/**
 * downloadHoroscope
 * this class is called when download horoscope is clicked and it creates a pdf file and downloads it on the system
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   16th Feb 2017
 */
class downloadHoroscopeV1Action extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{				
		$this->loginData = $request->getAttribute("loginData");
		$profileId = $this->loginData["PROFILEID"];
		$username = $this->loginData["USERNAME"];
		$gender = $request->getParameter("GENDER");
		$viewUsername = $request->getParameter("view_username");
		if(strlen($gender) > 1)
		{
			$gender = $gender[0];
		}
		
		if(!$profileId || ($this->loginData["GENDER"] != $gender))
		{
			$sameGender = 0;
		}		
		else
		{
			$sameGender = 1;
		}
		$channel = MobileCommon::getChannel();		
		$today = date("Y-m-d H:i:s");
		if($viewUsername)
		{
			$url = JsConstants::$siteUrl."/profile/horoscope_astro.php?SAMEGENDER=&FILTER=&ERROR_MES=&view_username=".$viewUsername."&SIM_USERNAME=".$request->getParameter("SIM_USERNAME")."&type=Horoscope&checksum=&profilechecksum=".$request->getParameter("otherprofilechecksum")."&randValue=890&GENDERSAME=".$sameGender;		
			$file=PdfCreation::PdfFile($url);		
			PdfCreation::setResponse("horoscope_".$viewUsername.".pdf",$file);
			 
			$horoscopeDownloadTrackingObj = new NEWJS_HOROSCOPE_DOWNLOAD_TRACKING("newjs_master");
			$horoscopeDownloadTrackingObj->insertDownloadTracking($today,$channel,$username,$viewUsername);
		}
		
		return sfView::NONE;
	}
}