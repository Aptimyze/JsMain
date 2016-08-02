<?php

class jsValidatorWhiteList extends sfValidatorBase
{
	protected function configure($arrOptions = array(), $arrMessages = array())
	{
		$this->addOption('FieldMapLabel',$arrOptions[FieldMapLabel]);
		$this->addOption('Value',$arrOptions[Value]);
		$this->addOption('FieldName',$arrOptions[FieldName]);
		$this->addOption('isHobby',$arrOptions[isHobby]);
	}
	
	protected function doClean($value)
	{		
		$szFMLabel = $this->getOption("FieldMapLabel");
		$arrValues = $this->getOption("Value");

		
		if(!is_array($arrValues) && is_string($arrValues) && stripos($arrValues,",") != false)
		{
			// String representing input separated by commas 
			$arrValues = explode(",",$arrValues);
			
			foreach($arrValues as $key => $val)
			{
				$arrValues[$key] = trim($val);
			}
		}
		else if(is_string($arrValues))
		{
			$arrTemp[] = $arrValues;
			$arrValues =  $arrTemp;
		}
		if($this->getOption("isHobby"))
			$arrMap = HobbyLib::getHobbyLabel($szFMLabel,'',1);
		else
			$arrMap = FieldMap::getFieldLabel($szFMLabel,'',1);
		
		foreach($arrValues as $key=>$val)
		{
			if(!array_key_exists($val,$arrMap))
			{	
                                $szFieldName = $this->getOption('FieldName');
                                if($val == '431' && $szFieldName == "P_CASTE"){ // Check added to prevent caste "Gurjar" submit from IOS Will be removed once changed in ios app
                                        throw new sfValidatorError($this,'One of the selected caste is no longer present in system, please select an alternative caste.', array('value' => $val));
                                }else{
                                        throw new sfValidatorError($this,ErrorHelp::getDPP_ERROR($szFieldName), array('value' => $val));
                                }
			}
		}
		return $value;
	}
	
	protected function isEmpty($value)
	{
		return in_array($value, array(null,''), true);
	}
}
?>
