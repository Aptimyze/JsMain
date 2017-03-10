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
		
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$request->getParameter('selfprofilechecksum'))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		
		$url = JsConstants::$siteUrl."/profile/horoscope_astro.php?SAMEGENDER=&FILTER=&ERROR_MES=&view_username=".$request->getParameter("view_username")."&SIM_USERNAME=".$request->getParameter("SIM_USERNAME")."&type=Horoscope&checksum=&profilechecksum=".$request->getParameter("otherprofilechecksum")."&randValue=890&GENDERSAME=".$request->getParameter("GENDERSAME");				
		
		$file=PdfCreation::PdfFile($url);
		PdfCreation::setResponse("myfile.pdf",$file);

		return sfView::NONE;
	}
}