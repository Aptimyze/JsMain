<?php
/**
 * astroCompatibility
 * To check conditions of astro compatibility and accordingly send a sample or actual report to the user. Non symfony code (check_horoscope_compatibility) is being converted to symfony in this code.
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   27th March 2017
 */
class astroCompatibilityV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		//get login data
		$this->loginData = $request->getAttribute("loginData");		
		//parameters obtained from api
		$otherProfilechecksum = $request->getParameter("otherProfilechecksum");
		$subscription = $this->loginData["SUBSCRIPTION"];
		
		$profilechecksumArr = explode("i",$otherProfilechecksum);
		$otherProfileId = $profilechecksumArr[1];
		$sendMail = $request->getParameter("sendMail");
		$sampleReport = $request->getParameter("sampleReport");
		$otherUsername = $request->getParameter("username");
		$gender = $request->getParameter("gender");
		$loggedInProfileId = $this->loginData["PROFILEID"];

		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$this->loginData['PROFILEID'])
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			return SfView::NONE;			
		}

		//if gender of two profiles is same
		if($gender == $this->loginData["GENDER"])
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$successArr["MESSAGE"]  = "Astro Compatibility cannot be matched with the same gender";
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			return SfView::NONE;
		}

		//if sample astro Report is to be sent
		if($sampleReport && $sendMail)
		{				
			$astroObj = new astroReport();

			//to check if the key already exists (i.e. the person has already been sent a sample mail inside the last 6 hours)
			$flag = $astroObj->getSampleAstroFlag($loggedInProfileId);

			//if the mail has been sent
			if($flag)
			{
				$successArr["MESSAGE"] = "Sample Mail Sent";				
			}
			else //if the mail has not been sent
			{
				$url = JsConstants::$imgUrl."/images/sampleAstro.pdf";
				$file = file_get_contents($url);
				$successArr = $astroObj->sendAstroMail(1848,$otherUsername,$otherProfileId,$file,"sample",$loggedInProfileId);
				$astroObj->setSampleReportFlag($loggedInProfileId);				
			}
			unset($astroObj);
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			return SfView::NONE;
		}

		$profileIdArr = array("0"=>$loggedInProfileId,"1"=>$otherProfileId);

		$astroObj = ProfileAstro::getInstance();
		$astroData = $astroObj->getAstroDetails($profileIdArr,'',"Y");
		
		if(!is_array($astroData[$loggedInProfileId]))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$successArr["MESSAGE"]  = "No Astro Details Found";
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			return SfView::NONE;
		}
		else
		{
			if($sendMail  && $subscription)
			{
				$astroObj = new astroReport();
				$flag = $astroObj->getActualReportFlag($loggedInProfileId,$otherProfileId);					
				if($flag)
				{
					$successArr["MESSAGE"] = "Actual Report Sent";
				}
				else
				{
					$count = $astroObj->getNumberOfActualReportSent($loggedInProfileId);					
					if($count >= "100")
					{
						$successArr["MESSAGE"] = "Actual Report Sent";	
					}
					else
					{
						$astroDataSelf = $astroData[$this->loginData['PROFILEID']];
						$astroDataOther = $astroData[$otherProfileId];

						if($this->loginData['GENDER']=='M')
						{
							$urlToVedic="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_CompatibilityReport_Matchstro.dll?CompareTwoPeople_And_GenerateReport?".$this->loginData['USERNAME'].":".$astroDataSelf['MOON_DEGREES_FULL'].":".$astroDataSelf['MARS_DEGREES_FULL'].":".$astroDataSelf['VENUS_DEGREES_FULL'].":".$astroDataSelf['LAGNA_DEGREES_FULL'].":".$astroDataOther['MOON_DEGREES_FULL'].":".$astroDataOther['MARS_DEGREES_FULL'].":".$astroDataOther['VENUS_DEGREES_FULL'].":".$astroDataOther['LAGNA_DEGREES_FULL'].":".$otherUsername;
						}
						else
						{
							$urlToVedic="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_CompatibilityReport_Matchstro.dll?CompareTwoPeople_And_GenerateReport?".$otherUsername.":".$astroDataOther['MOON_DEGREES_FULL'].":".$astroDataOther['MARS_DEGREES_FULL'].":".$astroDataOther['VENUS_DEGREES_FULL'].":".$astroDataOther['LAGNA_DEGREES_FULL'].":".$astroDataSelf['MOON_DEGREES_FULL'].":".$astroDataSelf['MARS_DEGREES_FULL'].":".$astroDataSelf['VENUS_DEGREES_FULL'].":".$astroDataSelf['LAGNA_DEGREES_FULL'].":".$this->loginData['USERNAME'];
						}						
						$file=PdfCreation::PdfFile($urlToVedic);					
						if($file)
						{
							PdfCreation::setResponse("astroCompatibility-".$otherUsername.".pdf",$file);
							$successArr = $astroObj->sendAstroMail(1839,$otherUsername,$otherProfileId,$file,"actual",$loggedInProfileId);
							$successArr["STATUS"] = "1";
							$astroObj->setActualReportFlag($loggedInProfileId,$otherProfileId);
							$astroObj->addDataForActualReport($loggedInProfileId);
							if($count == "0")
							{
								$astroObj->setExpiryTime($loggedInProfileId);
							}
						}
						else
						{
							$successArr = $astroObj->sendAstroMail(1850,$otherUsername,$otherProfileId,$file,"actual",$loggedInProfileId,"noData");
						}	
						
					}
				}
				
				unset($astroObj);
				if(MobileCommon::isApp())
				{
					$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
					$apiResponseHandlerObj->setResponseBody($successArr);
					$apiResponseHandlerObj->generateResponse();
				}
				
				return SfView::NONE;
				
			}
			return SfView::NONE;
		}
		return SfView::NONE;

	}
}
