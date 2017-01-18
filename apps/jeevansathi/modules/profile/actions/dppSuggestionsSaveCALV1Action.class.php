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
		$this->hIncomeDol = $this->getFieldMapLabels("hincome_dol",'',1);
		$this->hIncomeRs = $this->getFieldMapLabels("hincome",'',1);
		$calLayer = 1;

		//Call to get the data filled in dpp
		ob_start();
		$request->setParameter('sectionFlag','dpp');
		$request->setParameter("internal","1");
		$jsonData = sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditV1");

		$output = ob_get_contents();
		ob_end_clean();
		
		$decodedData = json_decode($output);
		$dppSaveData = json_decode($request->getParameter("dppSaveData"));			
     		
		$dppDataArr = $this->getDppFilledData($decodedData);		
		$finalArr = $this->getFinalSubmitData($dppSaveData,$dppDataArr);

		ob_start();
		//$request->setParameter('sectionFlag','dpp');
		$request->setParameter("fromBackend",false);
		$request->setParameter("editFieldArr",$finalArr);
		$jsonData = sfContext::getInstance()->getController()->getPresentationFor("profile", "apieditdppv1");

		$output = ob_get_contents();		
		ob_end_clean();		
		return sfView::NONE;
	}

	//This function appends the values of the dpp selected from the CAL to the already set values in the dpp
	public function getFinalSubmitData($dppSaveData,$dppDataArr)
	{
		foreach($dppSaveData as $key=>$value)
		{
			if(array_key_exists($value->type, $dppDataArr))
			{
				if($dppDataArr[$value->type] == "DM")
				{
					$finalDppArr["P_".$value->type] = implode(",",$value->data);
				}
				elseif($value->type == "AGE")
				{
					foreach($value->data as $k=>$v)
					{
						$finalDppArr["P_".$k] = $v;
					}
				}
				elseif ($value->type == "INCOME")
				{
					foreach($value->data as $k=>$v)
					{
						if($k == "LRS" ||$k == "HRS")
						{
							$finalDppArr["P_".$k] = array_search($v,$this->hIncomeRs);
						}
						else
						{
							$finalDppArr["P_".$k] = array_search($v,$this->hIncomeDol);
						}						
					}
				}
				else
				{
					foreach($value->data as $k=>$v)
					{
						if(strpos($dppDataArr[$value->type],$v) === false && strpos($appendValues,$v) === false)
						{
							$appendValues.= ",".$v;
						}
					}
					$finalDppArr["P_".$value->type] = $dppDataArr[$value->type].$appendValues;
					unset($appendValues);
				}
			}
		}
		return $finalDppArr;
	}
	//This function gets labels from FieldMapLib depending on $labels,$value,$returnArr
	public function getFieldMapLabels($label,$value,$returnArr='')
	{
		return FieldMap::getFieldlabel($label,$value,$returnArr);
	}

	public function getDppFilledData($decodedData)
	{
		foreach($decodedData as $key=>$value)
		{
			if(in_array($value->key,DppAutoSuggestEnum::$SUGGESTION_FIELDS))
			{				
				$dppDataArr[substr($value->key,2)] = $value->value;
			}
		}

		return $dppDataArr;
	}
}
?>