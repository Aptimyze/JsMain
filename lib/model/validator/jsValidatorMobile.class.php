<?php
class jsValidatorMobile extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('PHONE_MOB') as $key=>$msg)
    	$this->addMessage($key, $msg);
    $this->addOption('min_length');
    $this->addOption('max_length');
     $this->addOption('altMobile');
    $this->addOption('landline');
  }
  
  protected function doClean($value)
  {
    $phone = ltrim($this->getOption('landline'),0);
    $value['mobile']=ltrim($value['mobile'],0);
	if(!(strlen($phone)>0 && empty($value['mobile'])) && !(empty($value['mobile']) && $this->getOption('altMobile')==1))
    {
		// elements must be either empty or a number
		foreach (array('isd', 'mobile') as $key)
		{
		  if (isset($value[$key]) && !preg_match('/^[+]?[0-9]+$/', $value[$key]) && !empty($value[$key]))
		  {
			throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value[$key]));
		  }
		}
		if (!preg_match('/^[0-9]+$/', $value['mobile']))
		{
		  throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value));
		}
		if($value['isd']=='')
		{
			throw new sfValidatorError($this, 'err_mobile_isd', array('value' => $value));
		}

		$clean = (string) $value['mobile'];
		$length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);
		
		if (in_array($value['isd'],array('0','91','+91')) && $this->hasOption('max_length') && $length > $this->getOption('max_length'))
		{
		  throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value['mobile'], 'err_mobile_length' => $this->getOption('max_length')));
		}
		elseif (!in_array($value['isd'],array('0','91','+91')) && $length >14)
		{
		  throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value['mobile']));
		}

		if (in_array($value['isd'],array('0','91','+91')) && $this->hasOption('min_length') && $length < $this->getOption('min_length'))
		{
		  throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value['mobile'], 'err_mobile_length' => $this->getOption('min_length')));
		}
		elseif (!in_array($value['isd'],array('0','91','+91')) && $length < 6)
		{
		  throw new sfValidatorError($this, 'err_mobile_invalid', array('value' => $value['mobile']));
		}
	}
    if($value['isd']=='0')
    	$value['isd']='+91';
    else
	$value['isd']=ltrim($value['isd'],'0');
    if(strpos($value['isd'],'+')===false)
		$value['isd']="+".$value['isd'];
	$arr=explode('+',$value['isd']);
	$value['isd']=$arr[1];
	
	//ISD value check
	$isdval=trim(trim($value['isd']),"+");
        $isdkeys=@array_keys(FieldMap::getFieldLabel("isdcode",'',1));
        if(!in_array($isdval,$isdkeys))
       	throw new sfValidatorError($this,'err_isd_code', array('value' => $value));
	if($value['mobile']!='')
	{       
                if($value['isd'])
                    $valueToCheck = $value['isd'].$value['mobile'];
                else
                    $valueToCheck = $value['mobile'];
		$negativeProfileListObj = new incentive_NEGATIVE_LIST;
		$negativeMobile = $negativeProfileListObj->checkEmailOrPhone("PHONE_NUM",$valueToCheck);
		if($negativeMobile)
		{
			throw new sfValidatorError($this, 'err_phone_revoke', array('value' => $value['mobile']));
		}
	}

	return $value;
  }
  
  protected function isEmpty($value)
  {
	  return in_array($value, array(null,''), true);
  }
}
