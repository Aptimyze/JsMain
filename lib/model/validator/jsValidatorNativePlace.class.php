<?php

class jsValidatorNativePlace extends sfValidatorBase
{
	protected function configure($arrOptions = array(), $arrMessages = array())
	{
		$this->addOption('FieldMapLabel',$arrOptions[FieldMapLabel]);
		$this->addOption('Value',$arrOptions[Value]);
		$this->addOption('FieldName',$arrOptions[FieldName]);
	}
	
	protected function doClean($value)
	{		
		$szFMLabel = $this->getOption("FieldMapLabel");
		$arrValues = $this->getOption("Value");
		$szFieldName = $this->getOption("FieldName");
		
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
		
		$arrMap = FieldMap::getFieldLabel($szFMLabel,'',1);
		if(is_array($arrValues))
		{
			foreach($arrValues as $key=>$val)
			{
				if(!array_key_exists($val,$arrMap) && $val !=='0')
				{
					//$szFieldName = $this->getOption('FieldName');
					$value = '';
					//throw new sfValidatorError($this,'Native_szFieldName error', array('value' => $val));
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
