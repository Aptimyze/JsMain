<?php
class jsValidatorReligion extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('caste');
  }
  
  protected function doClean($value)
  {
    $clean = (string) $value;
  	$caste = $this->getOption('caste');
  	if(!$caste)
  	{
		if(!($clean == "5" || $clean == "6" || $clean == "7" || $clean == "8"||$clean=="10"))
		{
			throw new sfValidatorError($this,'please provide a caste value for religion');
		}
		
	}
	$field_map_religion_name=ObjectiveFieldMap::getFieldMapKey("RELIGION");
	$choices=@array_keys(FieldMap::getFieldLabel($field_map_religion_name,'',1));
	if(!in_array($clean,$choices))
	{
		throw new sfValidatorError($this,'please provide a valid value for Religion');
	}
     return $clean;
  }
  
  protected function isEmpty($value)
  {
    return 0;
  }
 }

