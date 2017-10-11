<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   24th Jan 2017
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
		$this->loginProfile = LoggedInProfile::getInstance('newjs_master');	
		$this->hIncomeDol = $this->getFieldMapLabels("hincome_dol",'',1);
		$this->hIncomeRs = $this->getFieldMapLabels("hincome",'',1);
		$calLayer = 1;

		$apiProfileSectionObj=  ApiProfileSections::getApiProfileSectionObj($this->loginProfile);		
		
		//created obj of EditDetails
		$editDetailsObj = new EditDetails();
		$jpartnerObj=$editDetailsObj->getJpartnerObj($this);
		$this->loginProfile->setJpartner($jpartnerObj);

		//This decoded data is an array and not an object. Therefore, is_array checks need to be applied and forloops need to be altered
		$decodedData =  $editDetailsObj->getDppValuesArr($apiProfileSectionObj,'1');
				
		$dppSaveData = json_decode($request->getParameter("dppSaveData"));		
		$dppDataArr = $this->getDppFilledData($decodedData);
		$this->incomeDppArr = explode(",",$dppDataArr["INCOME"]);		
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
		if(is_array($dppSaveData))
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
							if($k == "LRS" || $k == "HRS")
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
						$dppDataTypeArr = array();
						$dppDataTypeArr = explode(",",$dppDataArr[$value->type]);
						$appendArr = array();
						foreach($value->data as $k=>$v)
						{
							if(!in_array($v,$dppDataTypeArr) && !in_array($v,$appendArr))
							{
								$appendValues.= ",".$v;
								$appendArr[]=$v;
							}
						}
						$finalDppArr["P_".$value->type] = $dppDataArr[$value->type].$appendValues;
						unset($appendValues);
					}
				}
			}
		}
		if(!array_key_exists("P_LRS", $finalDppArr) && array_key_exists("P_LDS", $finalDppArr))
		{
			$finalDppArr["P_LRS"] = $this->incomeDppArr[0];
			$finalDppArr["P_HRS"] = $this->incomeDppArr[1];
		}
		else if (!array_key_exists("P_LDS", $finalDppArr) && array_key_exists("P_LRS", $finalDppArr))
		{
			$finalDppArr["P_LDS"] = $this->incomeDppArr[2];
			$finalDppArr["P_HDS"] = $this->incomeDppArr[3];
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
		if(MobileCommon::isNewMobileSite())
		{
			if(is_array($decodedData))
			{
				foreach($decodedData as $key=>$value)
				{
					if(is_array($value))
					{
						foreach($value as $k1=>$v1)
						{
							if($k1 == DppAutoSuggestEnum::$OnClickLabel)
							{
								if(is_array($v1))
								{
									foreach($v1 as $k2=>$v2)
									{
										if(in_array($v2["key"],DppAutoSuggestEnum::$SUGGESTION_FIELDS))
										{
											if(in_array($v2["key"],DppAutoSuggestEnum::$incomeFieldJSMS))
											{									
												$incomeArr[] = $v2["value"];
											}
											else
											{
												$dppDataArr[substr($v2["key"],2)] = $v2["value"];
											}								
										}
									}
								}										
							}
						}
					}						
				}
			}			
			$incomeStr = implode(",",$incomeArr);
			$dppDataArr["INCOME"] = $incomeStr;
		}
		else
		{
			if(is_array($decodedData))
			{
				foreach($decodedData as $key=>$value)
				{
					if(in_array($value["key"],DppAutoSuggestEnum::$SUGGESTION_FIELDS))
					{				
						$dppDataArr[substr($value["key"],2)] = $value["value"];
					}
				}
			}			
		}	
		return $dppDataArr;
	}
}
?>
