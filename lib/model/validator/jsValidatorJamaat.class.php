<?php

class jsValidatorJamaat extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('caste');
  }
  
  protected function doClean($value)
  {
    $clean = (string) $value;
  	$caste = $this->getOption('caste');
  	if($clean)
	{
		if($caste=='152')
			$field_map_jamaat_name=ObjectiveFieldMap::getFieldMapKey("JAMAAT");
		else
			throw new sfValidatorError($this,'Jamaat is not allowed for '.FieldMap::getFieldLabel('caste',$caste));
		$choices=@array_keys(FieldMap::getFieldLabel($field_map_jamaat_name,'',1));
		if(!in_array($clean,$choices))
		{
			throw new sfValidatorError($this,'Please provide a valid value of jamaat for '.FieldMap::getFieldLabel('caste',$caste));
		}
	}
        else if($caste == '152')
            throw new sfValidatorError($this,'Jamaat is mandatory for '.FieldMap::getFieldLabel('caste',$caste));
      return $clean;
  }
  
  protected function isEmpty($value)
  {
    return 0;
  }
 }






