<?php
class jsValidatorManglik extends sfValidatorBase
{
  CONST MUSLIM= 2;
  CONST CHRISTIAN = 3;
  CONST PARSI = 5; 
  CONST JEWISH = 6;
  CONST OTHER = 8;
  protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('religion');
  }
  
  protected function doClean($value)
  {
    //to check if manglik value is appearing for non-manglik religion
    if(($religion ==jsValidatorManglik::MUSLIM || $religion ==jsValidatorManglik::PARSI || $religion ==jsValidatorManglik::JEWISH || $religion ==jsValidatorManglik::CHRISTIAN || $religion ==jsValidatorManglik::OTHER) && $value)
    {
		$value = "";
    }
	
	return $value;
  }
  protected function isEmpty($value)
  {
    return in_array($value, array(null,array()), true);
  }
}

