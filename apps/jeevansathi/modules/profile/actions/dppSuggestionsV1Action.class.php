<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   15th September 2016
 */

class dppSuggestionsV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$this->loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$percentileFields = DppAutoSuggestEnum::$TRENDS_FIELDS;
		$profileId = $this->loggedInProfileObj->getPROFILEID();
		$dppSuggestionsObj = new dppSuggestions();
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_masterRep");
		//Trends arr is fetched from twoWayMatches.Trends table
		$trendsArr = $dppSuggestionsObj->getTrendsArr($profileId,$percentileFields,$trendsObj);
		unset($trendsObj);
		$data = $request->getParameter("Param");		
		$decodedData = json_decode($data);
		
		foreach($decodedData as $key=>$val)
		{
			foreach($val as $key1=>$val1)
			{									
				$type  = $val->type;
				if($key1 == "data")
				{											
					$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type,$val1);
				}					
			}
		}
		if(is_array($finalArr))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody(json_encode($finalArr));
		}
		else
		{
			$errorArr["ERROR"] = "Something went wrong";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;
	}	
}
?>