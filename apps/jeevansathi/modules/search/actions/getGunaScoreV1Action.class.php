<?php
/**
 * getGunaScoreV1Action
 * This api is used to get guna scores for SRP listings and it sends an array with   * key as profilechecksum and value as the corresponding guna score in response
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   27th April 2016
 */

class getGunaScoreV1Action extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$loggedInDetails = $loggedInProfileObj->getDetail("","","*");
		$profileId = $loggedInDetails["PROFILEID"];
		if(!$profileId)
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		$gender = $loggedInDetails["GENDER"];
		$caste = $loggedInDetails["CASTE"];
		$profilechecksumArr = $request->getParameter('profilechecksumArr');
		
		//$diffGender is the variable which tells if the search was made of the opposite gender
		$diffGender = $request->getParameter('diffGender');
		if($diffGender)
		{
			$gunaData = $this->getGunaScoreForSearch($profileId,$caste,$profilechecksumArr,$gender);
			if(is_array($gunaData))
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$apiResponseHandlerObj->setResponseBody(array("gunaScores"=>$gunaData));
				$apiResponseHandlerObj->generateResponse();
			}
			else
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$ZERO_GUNA_MATCHES);
				$apiResponseHandlerObj->generateResponse();
				die;
			}

		}
		else
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		return SfView::NONE;
	}

	//This function calls the gunsScore.class.php and returns $gunaData
	public function getGunaScoreForSearch($profileId,$caste,$profilechecksumArr,$gender)
	{
		$gunaScoreObj = new gunaScore();
		$gunaData = $gunaScoreObj->getGunaScore($profileId,$caste,$profilechecksumArr,$gender);
		unset($gunaScoreObj);
		return($gunaData);
	}

}

?>