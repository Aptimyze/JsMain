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
		
		$trendsArr = $dppSuggestionsObj->getTrendsArr($profileId,$percentileFields);
		//---- write redis code---
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
		$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);
		//print_R($trendsArr);die;
				//JsMemcache::getInstance()->remove($profileId,$trendsArr);
		$data = $request->getParameter("Param");
		$decodedData = json_decode($data);
		//print_R($decodedData);die;
		foreach($decodedData as $key=>$val)
		{
			foreach($val as $key1=>$val1)
			{									
				$type = $val->type;
				if($key1 == "data")
				{											
					$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type,$val1);
				}
								
			}
		}		
		print_r($finalArr);die;
		if(is_array($finalArr))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody(json_encode($finalArr));
			$apiResponseHandlerObj->generateResponse();
		}
		else
		{
			//write failure code
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