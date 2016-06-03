<?php
class jsValidatorCaste extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('religion');
  }
  
  protected function doClean($value)
  {
    $clean = (string) $value;
  	$religion = $this->getOption('religion');
  	if(!$religion)
  	{
		throw new sfValidatorError($this,'please provide a religion value for caste');
	}
    $dbObj = new NEWJS_CASTE();
    if($religion == "5" || $religion == "6" || $religion == "7" || $religion == "8"||$religion=="10")
    {
		$clean = $dbObj->getCastesOfParent($religion);
    }
    else
    {
		$casteArr=$dbObj->getCastesOfParent($religion);
	}
    if($clean=="")
    {
		throw new sfValidatorError($this, 'required', array('value' => $value));
    }
    else 
	{
		$field_map_caste_name=ObjectiveFieldMap::getFieldMapKey("CASTE");
		$choices=@array_keys(FieldMap::getFieldLabel($field_map_caste_name,'',1));
		if(!in_array($clean[0],$choices))
		{
			throw new sfValidatorError($this,'please provide a valid value for caste');
		}
		elseif(is_array($casteArr))
		{
			if(!in_array($clean,$casteArr))
			{
				throw new sfValidatorError($this,'please provide a valid value of caste for the religion provided');
			}
		}
	}
	if(is_array($clean))
        return $clean[0];
	else
		return $clean;
		
  }
  
  protected function isEmpty($value)
  {
    return 0;
  }
 }

