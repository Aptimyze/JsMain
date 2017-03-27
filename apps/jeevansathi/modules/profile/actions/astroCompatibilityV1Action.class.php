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
		$profilechecksumArr = explode("i",$otherProfilechecksum);
		$otherProfileId = $profilechecksumArr[1];
		$sendMail = $request->getParameter("sendMail");
		$sampleReport = $request->getParameter("sampleReport");
		$otherUsername = $request->getParameter("username");
		$gender = $request->getParameter("gender");

		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$this->loginData['PROFILEID'])
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}

		//if gender of two profiles is same
		if($gender == $this->loginData["GENDER"])
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$successArr["MESSAGE"]  = "Astro Compatibility cannot be matched with the same gender";
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			die;
		}

		//if sample astro Report is to be sent
		if($sampleReport && $sendMail)
		{				
			$url = JsConstants::$imgUrl."/images/sampleAstro.pdf";
			$file = file_get_contents($url);
			$email_sender = new EmailSender(MailerGroup::ASTRO_COMPATIBILTY,1848); //1848 is the  mail id
			$emailTpl = $email_sender->setProfileId($this->loginData['PROFILEID']);
			$smartyObj = $emailTpl->getSmarty();
			$smartyObj->assign('otherUsername',$otherUsername);
			$smartyObj->assign('otherProfile',$otherProfileId);
			$email_sender->setAttachment($file);
			$email_sender->setAttachmentName("SampleAstroReport.pdf");
			$email_sender->setAttachmentType('application/pdf');
			$email_sender->send();			
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$successArr["MESSAGE"]  = "Sample Mail Sent";
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			die;
		}

		$profileIdArr = array("0"=>$this->loginData["PROFILEID"],"1"=>$otherProfileId);

		$astroObj = ProfileAstro::getInstance();
		$astroData = $astroObj->getAstroDetails($profileIdArr,'',"Y");
		
		if(!is_array($astroData[$this->loginData["PROFILEID"]]))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$successArr["MESSAGE"]  = "No Astro Details Found";
			$apiResponseHandlerObj->setResponseBody($successArr);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		else
		{
			if($sendMail)
			{
				$astroDataSelf = $astroData[$this->loginData['PROFILEID']];
				$astroDataOther = $astroData[$otherProfileId];

				if($this->loginData['GENDER']=='M')
				{
					$urlToVedic="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_CompatibilityReport_Matchstro.dll?CompareTwoPeople_And_GenerateReport?".$this->loginData['USERNAME'].":".$astroDataSelf['MOON_DEGREES_FULL'].":".$astroDataSelf['MARS_DEGREES_FULL'].":".$astroDataSelf['VENUS_DEGREES_FULL'].":".$astroDataSelf['LAGNA_DEGREES_FULL'].":".$astroDataOther['MOON_DEGREES_FULL'].":".$astroDataOther['MARS_DEGREES_FULL'].":".$astroDataOther['VENUS_DEGREES_FULL'].":".$astroDataOther['LAGNA_DEGREES_FULL'].":".$row['USERNAME'];
				}
				else
				{
					$urlToVedic="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_CompatibilityReport_Matchstro.dll?CompareTwoPeople_And_GenerateReport?".$row['USERNAME'].":".$astroDataOther['MOON_DEGREES_FULL'].":".$astroDataOther['MARS_DEGREES_FULL'].":".$astroDataOther['VENUS_DEGREES_FULL'].":".$astroDataOther['LAGNA_DEGREES_FULL'].":".$astroDataSelf['MOON_DEGREES_FULL'].":".$astroDataSelf['MARS_DEGREES_FULL'].":".$astroDataSelf['VENUS_DEGREES_FULL'].":".$astroDataSelf['LAGNA_DEGREES_FULL'].":".$this->loginData['USERNAME'];
				}
				$file=PdfCreation::PdfFile($urlToVedic);
				
				$email_sender = new EmailSender(MailerGroup::ASTRO_COMPATIBILTY,1839);
				$emailTpl = $email_sender->setProfileId($this->loginData['PROFILEID']);
				$smartyObj = $emailTpl->getSmarty();
				$smartyObj->assign('otherUsername',$otherUsername);
				$smartyObj->assign('otherProfile',$otherProfileId);
				$email_sender->setAttachment($file);
				$email_sender->setAttachmentName("astroCompatibility-".$otherUsername.".pdf");
				$email_sender->setAttachmentType('application/pdf');
				$email_sender->send();
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$successArr["MESSAGE"]  = "Astro Report Sent";
				$apiResponseHandlerObj->setResponseBody($successArr);
				$apiResponseHandlerObj->generateResponse();
				die;
			}
		}

	}
}