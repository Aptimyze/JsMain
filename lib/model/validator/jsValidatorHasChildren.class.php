<?php
class jsValidatorHasChildren extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('mstatus');
  }
  
  protected function doClean($value)
  {
    $mstatus = $this->getOption('mstatus');
    if($mstatus !='N' && !$value)
    {
		throw new sfValidatorError($this, ErrorHelp::$ERR_REQUIRED[havechild], array('value' => $value));
    }
	if($mstatus=="N" && $value)
	{
		$value='';
	}
	
	return $value;
  }
  protected function isEmpty($value)
  {
    return in_array($value, array(null,array()), true);
  }
}
