<?php
class jsValidatorSect extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('religion');
  }
  
  protected function doClean($value)
  {
    $clean = (string) $value;
  	$religion = $this->getOption('religion');
  	if($clean)
	{
		if(!$religion)
		{
			throw new sfValidatorError($this,'please provide a religion value for Sect');
		}
		if($religion==Religion::JAIN)
			$field_map_sect_name=ObjectiveFieldMap::getFieldMapKey("SECT_JAIN");
		elseif($religion==Religion::SIKH)
			$field_map_sect_name=ObjectiveFieldMap::getFieldMapKey("SECT_SIKH");
		elseif($religion==Religion::MUSLIM)
			$field_map_sect_name=ObjectiveFieldMap::getFieldMapKey("SECT_MUSLIM");
		elseif($religion==Religion::HINDU)
			$field_map_sect_name=ObjectiveFieldMap::getFieldMapKey("SECT_HINDU");
		else
			throw new sfValidatorError($this,'Sect is not allowed for '.FieldMap::getFieldLabel('religion',$religion));
		$choices=@array_keys(FieldMap::getFieldLabel($field_map_sect_name,'',1));
		if(!in_array($clean,$choices))
		{
			throw new sfValidatorError($this,'please provide a valid value for sect for '.FieldMap::getFieldLabel('religion',$religion));
		}
	}
      return $clean;
  }
  
  protected function isEmpty($value)
  {
    return 0;
  }
 }

