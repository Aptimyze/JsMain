<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   15th September 2016
 */

class dppSuggestionsCALV1Action extends sfActions 
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
		$calLayer = 1;
		ob_start();
		$request->setParameter('sectionFlag','dpp');
		$request->setParameter("internal","1");
		$jsonData = sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditV1");

		$output = ob_get_contents();
		ob_end_clean();
		$decodedData = json_decode($output);
		foreach($decodedData as $key=>$value)
		{
			if(in_array($value->key,DppAutoSuggestEnum::$SUGGESTION_FIELDS) && $value->value!="DM")
			{
				$dppDataArr[$key]["type"] = substr($value->key,2);
				$dppDataArr[$key]["data"] = explode(",",$value->value);
			}
		}
		// foreach($dppDataArr as $key=>$val)
		// {
		// 	if($val["type"] == "AGE")
		// 	{
		// 		$dppDataArr[$key]["data"] = array_combine(DppAutoSuggestEnum::$keyReplaceAgeArr,$val["data"]);
		// 	}
		// 	if($val["type"] == "INCOME")
		// 	{
		// 		$dppDataArr[$key]["data"] = array_combine(DppAutoSuggestEnum::$keyReplaceIncomeArr,$val["data"]);
		// 	}
		// }
		//print_r($dppDataArr);die;		
		$percentileFields = DppAutoSuggestEnum::$TRENDS_FIELDS;
		$profileId = $this->loggedInProfileObj->getPROFILEID();
		$dppSuggestionsObj = new dppSuggestions();
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
		//Trends arr is fetched from twoWayMatches.Trends table
		$trendsArr = $dppSuggestionsObj->getTrendsArr($profileId,$percentileFields,$trendsObj);
		unset($trendsObj);
		
		foreach($dppDataArr as $key=>$val)
		{
			foreach($val as $key1=>$val1)
			{									
				$type = $val["type"];				
				if($key1 == "data")
				{	
					$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type,$val1,$calLayer);
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