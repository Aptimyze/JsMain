<?php
class jsValidatorLandline extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('PHONE_RES') as $key=>$msg)
    	$this->addMessage($key,$msg);  	
  	
    $this->addOption('min_length');
    $this->addOption('max_length');
  }
  
  protected function doClean($value)
  {
	 $value['landline']=ltrim($value['landline'],0);
    if(count(array_filter($value)) && isset($value['landline']) && !empty($value['landline']))
    {
    	if (!preg_match('/^[+]?[0-9]+$/', $value['isd']))
      	{
        	throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $value));
      	}
    	foreach (array('std', 'landline') as $key)
		{
			if (!preg_match('/^[0-9]+$/', $value[$key]))
			{
				throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $value));
			}
		}
		if(in_array($value['isd'],array('0','91','+91')))
			$value['std'] = ltrim($value['std'],'0');
		$clean =  $value['std'].$value['landline'];  	
		$length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);
		
		if (in_array($value['isd'],array('0','91','+91')) && $this->hasOption('max_length') && $length > $this->getOption('max_length'))
		{
			throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean, 'err_phone_invalid' => $this->getOption('max_length')));
		}
		elseif (!in_array($value['isd'],array('0','91','+91')) && $length >14)
		{
		  throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean));
		}
		if (in_array($value['isd'],array('0','91','+91')) && $this->hasOption('min_length') && $length < $this->getOption('min_length'))
		{
		  throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean, 'err_phone_invalid' => $this->getOption('min_length')));
		}
		elseif (!in_array($value['isd'],array('0','91','+91')) && $length <6)
		{
		  throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean));
		}
	}
	if($value['isd']=='0')
    	$value['isd']='+91';
    else
		$value['isd'] = ltrim($value['isd'],'0');
    if(strpos($value['isd'],'+')===false)
		$value['isd']="+".$value['isd'];
	$arr=explode('+',$value['isd']);
	$value['isd']=$arr[1];
	$value['std'] = ltrim($value['std'],'0');
        if($value['landline']!='')
	{       
                $valueWithStd = $value['std'].$value['landline'];
                if($value['isd'])
                    $valueToCheck = $value['isd'].$valueWithStd;
                else
                    $valueToCheck = $valueWithStd;
		$negativeProfileListObj = new incentive_NEGATIVE_LIST;
		$negativeMobile = $negativeProfileListObj->checkEmailOrPhone("PHONE_NUM",$valueToCheck);
		if($negativeMobile)
		{
			throw new sfValidatorError($this, 'err_landline_revoke', array('value' => $value['mobile']));
		}
	}
    return $value;
  }
}
