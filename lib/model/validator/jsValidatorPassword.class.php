<?php
class jsValidatorPassword extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('PASSWORD') as $key=>$msg)
    {	
    	$this->addMessage($key,$msg);
    }
    $this->addOption('min_length');
    $this->addOption('email');
  }
  
  protected function doClean($value)
  {
	$email = $this->getOption('email');
    $username = substr($email,0,strpos($email, '@'));
  	$commonoWords = array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890"); 
     
    $clean = (string)$value;
    if(in_array(strtolower($value),$commonoWords))
    {
      throw new sfValidatorError($this, 'err_pass_common', array('value' => $value));
    }
    $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);	

    if ($this->hasOption('min_length') && $length < $this->getOption('min_length'))
    {
      throw new sfValidatorError($this, 'err_pass_invalid', array('value' => $value));
    }
    if($clean == $username || $clean == $email)
    	throw new sfValidatorError($this,'err_pass_email', array('value' => $value));
  	return trim($clean);
  }
}
