<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   15th September 2016
 */

class dppSuggestionsSaveCALV1Action extends sfActions 
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
		//print_r($decodedData);
		$sampleArr[0]["type"]="CASTE";
		$sampleArr[0]["data"][0] = "278";
        $sampleArr[0]["data"][1] = "407";
        $sampleArr[0]["data"][2] = "16";
        $sampleArr[0]["data"][3] = "408";

        $sampleArr[1]["type"]="EDUCATION";
		$sampleArr[1]["data"][0] = "18";
        $sampleArr[1]["data"][1] = "12";
        $sampleArr[1]["data"][2] = "16";
        $sampleArr[1]["data"][3] = "15";

        $sampleArr[2]["type"]="OCCUPATION";
		$sampleArr[2]["data"][0] = "10";
        $sampleArr[2]["data"][1] = "10";
        $sampleArr[2]["data"][2] = "28";
        $sampleArr[2]["data"][3] = "15";

        $sampleArr[3]["type"]="MTONGUE";
		$sampleArr[3]["data"][0] = "27";
        $sampleArr[3]["data"][1] = "14";
        $sampleArr[3]["data"][2] = "6";
        $sampleArr[3]["data"][3] = "20";
		
        $sampleArr[4]["type"]="CITY";
		$sampleArr[4]["data"][0] = "OR";
        $sampleArr[4]["data"][1] = "DE00";
        $sampleArr[4]["data"][2] = "UK06";
        $sampleArr[4]["data"][3] = "HP08";

        $sampleArr[5]["type"]="AGE";
        $sampleArr[5]["data"][0]="21";
        $sampleArr[5]["data"][1]="31";

        $sampleArr[6]["type"]="INCOME";
        $sampleArr[6]["data"][0]="2"; //0,1,2,3 for low high rs and low high dollars
        $sampleArr[6]["data"][1]="5";
        $sampleArr[6]["data"][2]="12";
        $sampleArr[6]["data"][3]="19";
     	//print_r($sampleArr);
		foreach($decodedData as $key=>$value)
		{
			if(in_array($value->key,DppAutoSuggestEnum::$SUGGESTION_FIELDS))
			{
				//$dppDataArr[$key]["type"] = substr($value->key,2);
				$dppDataArr[substr($value->key,2)] = $value->value;
			}
		}
		print_r($dppDataArr);
		foreach($sampleArr as $key=>$value)
		{
			if(array_key_exists($value["type"], $dppDataArr))
			{
				if($dppDataArr[$value["type"]] == "DM")
				{
					$finalDppArr["P_".$value["type"]] = implode(",",$value["data"]);
				}
				elseif($value["type"] == "AGE")
				{
					foreach($value["data"] as $k=>$v)
					{
						$finalDppArr[DppAutoSuggestEnum::$keyReplaceAgeArr[$k]] = $v;
					}
				}
				elseif ($value["type"] == "INCOME")
				{
					foreach($value["data"] as $k=>$v)
					{
						$finalDppArr[DppAutoSuggestEnum::$keyReplaceIncomeArr[$k]] = $v;
					}
				}
				else
				{
					foreach($value["data"] as $k=>$v)
					{
						if(strpos($dppDataArr[$value["type"]],$v) === false)
						{
							$appendValues.= ",".$v;
						}
					}
					$finalDppArr["P_".$value["type"]] = $dppDataArr[$value["type"]].$appendValues;
					unset($appendValues);
				}
			}
		}		
		print_r($finalDppArr);die;
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