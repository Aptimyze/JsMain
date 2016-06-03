<?php

class jsValidatorAge extends sfValidatorBase
{
	const MALE_MIN_AGE 		= 21;
	const FEMALE_MIN_AGE 	= 18;
	
	protected function configure($arrOptions = array(), $arrMessages = array())
	{
		$this->addOption('minAge',$arrOptions[minAge]);
		$this->addOption('maxAge',$arrOptions[maxAge]);
		$this->addOption('Gender',$arrOptions[Gender]);
	}
	
	protected function doClean($value)
	{
		
		$minAge = $this->getOption("minAge");
		$maxAge = $this->getOption("maxAge");
		$Gender = $this->getOption("Gender");
		
		$arrMale = array("M","MALE","1","m","Male","male");
		
		$allowedMinAge = in_array($Gender,$arrMale) ? self::MALE_MIN_AGE : self::FEMALE_MIN_AGE ;
		$szVal = $minAge . " to " . $maxAge;
    
    if (!is_numeric(intval($minAge)) || !is_numeric(intval($maxAge))) {
      throw new sfValidatorError($this,ErrorHelp::getDPP_ERROR('P_AGE'), array('value' => $szVal));
    }

    
		if($minAge < 0 || $maxAge < $minAge || $minAge < $allowedMinAge )
		{
			throw new sfValidatorError($this,ErrorHelp::getDPP_ERROR('P_AGE'), array('value' => $szVal));
		}
	}
}
?>
