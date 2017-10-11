<?php
class jsValidatorPartner extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach (ErrorHelp::getErrorArrayByField($options['type']) as $key=>$msg) {
    	$this->addMessage($key,$msg);
    }
    $this->addOption('large',$options[large]);

    $this->addOption("small",$options[small]);
    $this->addOption("type");
    	
    	//$small=$this->getOption("small");
  }
  
  protected function doClean($value)
  {
		$small=$this->getOption("small");
		$large=$this->getOption("large");
		
    if($small>$large && $large!=19)
    {
			throw new sfValidatorError($this, strtolower($this->getOption("type"))."_err", array('value' => $small));
    }    
  }
}
