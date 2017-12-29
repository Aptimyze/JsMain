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
  	$commonoWords = array("jeevansathi","matrimony","password","marriage","vibhor1234","omsairam","jaimatadi","abcd1234","Parvezkk","priyanka","Jeevansathi@123","pytw2560","waheguru","jeevansathi123","js123456","Jeevansathi.com","India@123","P@ssw0rd","ABHIshek","pass@123","jeevan123","welcome@123","Mayank2463","Welcome123","abc123","password123","qwertyuiop","india123","Password@123","nehaavyan123","abcd@1234","pd592001","shaadi@123","YASU4333","Krishna","Jeevan@123","Radhika02","anik.singh","JABALPUR123","qwerty","sairam","SINGH4345","rahul123","sachin","rahul@123","iloveyou","ganesh","saibaba","jeevansaathi","harekrishna","hariom","himanshu","shaadi123","pooja123","singh123","qwerty123","kareenakhan23","sonu1234","sunita","deepak","abcdefgh","sanjay","mummypapa","chaman111","Qwerty@123","priyanka123","Kaushal69sc@gmail.com","goodluck","rajkumar","rajusohel","pankaj"); 
     
    $clean = (string)$value;
    if(is_numeric($value))
      throw new sfValidatorError($this, 'err_pass_only_numeric', array('value' => $value));
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
