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
    $mobileNumberExceptionArr = array("9643102628","8800470788");
    
    $source = $_SERVER['HTTP_REFERER'];
    if(strpos($source,"viewprofile") !== false)
    {
      $source = "EDIT";
    }
    elseif(strpos($source,"registration") !== false)
    {
      $source = "REG";
    }
    $this->loginProfile=LoggedInProfile::getInstance();
    $this->profileId = $this->loginProfile->getPROFILEID();
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
			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/loggingIgnoredEmailAndPhone.txt",$valueToCheck."\t".date("Y-m-d H:i:s")."\t".$source."\n",FILE_APPEND);
			throw new sfValidatorError($this, 'err_phone_revoke', array('value' => $value['mobile']));
		}

		//adding check to ensure that more than 2 primary numbers are not present
		//the check is not to be implied if it is a test machine and they are using the '9999999999' number
		if((!(JsConstants::$whichMachine =="test" && ($value['mobile']==9999999999 || $value['mobile']==9999999991 || $value['mobile']==8527006813))) && !in_array($value["mobile"],$mobileNumberExceptionArr))
		{
			//check not to be implied for alternate mobile number
			if($this->getOption('altMobile')!=1)
			{	
				$detailArr = $this->getDetailArr($value['mobile'],$value['isd']);
        		//if count of profiles is greater than 2. check if profile is dummy and accordingly show error
				if(count($detailArr)>=2)
				{
					$dummyUserObj = new jsadmin_PremiumUsers;
					$dummyCount = $dummyUserObj->countDummy($detailArr);
					unset($dummyUserObj);
					if((count($detailArr)-$dummyCount)>=2)
					{
						throw new sfValidatorError($this, 'err_two_phone_num_exist', array('value' => $value['mobile']));
					}
				}
			}
		}
	}
	return $value;
  }
  
  protected function isEmpty($value)
  {
	  return in_array($value, array(null,''), true);
  }

  public function getDetailArr($mobile,$isd)
  {
  	$jprofileObj = JPROFILE::getInstance('newjs_master');
  	$lastLoginDate = CommonUtility::makeTime(date('Y-m-d', strtotime("-1 year")));
  	$valueArray = array("activatedKey"=>1,"MOB_STATUS"=>"Y","INCOMPLETE"=>"N","PHONE_MOB"=>$mobile,"ISD"=>$isd);
  	$greaterThanArray = array("LAST_LOGIN_DT"=>$lastLoginDate);
  	if($this->profileId)
  	{
  		$excludeArray  = array("ACTIVATED"=>"'D'","PROFILEID"=>$this->profileId);
  	}
  	else
  	{
  		$excludeArray  = array("ACTIVATED"=>"'D'");
  	}	
  	$detailArr = $jprofileObj->getArray($valueArray,$excludeArray,$greaterThanArray,'PROFILEID','','','','','','','','');
  	unset($jprofileObj);
  	return $detailArr;
  }
}
