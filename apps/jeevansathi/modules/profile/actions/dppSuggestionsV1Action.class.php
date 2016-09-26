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
		$suggestedValueCountArr = array();
		//---- write redis code---
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
		$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);

		//JsMemcache::getInstance()->remove($profileId,$trendsArr);
		$data = $request->getParameter("Param");
		$decodedData = json_decode($data);
		foreach($decodedData as $key=>$val)
		{
			foreach($val as $key1=>$val1)
			{									
				$type = $val->type;
				if($key1 == "data")
				{					
					if($type == "CASTE")
					{
						$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type);	
					}
					if($type == "MTONGUE") 
					{
						$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type);	
					}
					if($type == "EDUCATION")
					{
						$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type);	
					}
					if($type == "OCCUPATION")
					{
						$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type);	
					}
					if($type == "CITY")
					{
						//This has a different condition
						//$finalArr = $this->getDppSuggestions($trendsArr,$type);	
					}
					
				}
								
			}
		}		
		if(is_array($finalArr))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody(json_encode($finalArr));
			$apiResponseHandlerObj->generateResponse();
		}

		// if(!$request->getParameter('profilechecksum'))
		// {
		// 	$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		// 	$apiResponseHandlerObj->generateResponse();
		// 	die;
		// }
		return sfView::NONE;
	}	
}
?>