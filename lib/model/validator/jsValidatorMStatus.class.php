<?php
class jsValidatorMStatus extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('MSTATUS') as $key=>$msg)
    	$this->addMessage($key,$msg);
  }
  
  protected function doClean($value)
  {
	$request=sfContext::getInstance()->getRequest();
	if(is_array($request->getParameter('reg')))
	{
		$regArr = $request->getParameter('reg');
		$haveChildren = $regArr['havechild'];
		$gender = $regArr['gender'];
		$religion = $regArr['religion'];
	}
	elseif(is_array($request->getParameter('editFieldArr')))
	{
		$editFieldArr = $request->getParameter('editFieldArr');
		$gender = $editFieldArr['GENDER'];
                if(!$gender){
                        $gender = LoggedInProfile::getInstance()->getGENDER();
                }
		$religion = $editFieldArr['RELIGION'];
                if(!$religion){
                        $religion = LoggedInProfile::getInstance()->getRELIGION();
                }
                if($value == "D" && !isset($editFieldArr["MSTATUS_PROOF"])){
                        throw new sfValidatorError($this, ErrorHelp::$ERR_REQUIRED[mstatus_proof]);	
                }
	}
	elseif(is_array($request->getParameter('formValues')))
	{
		$regArr = $request->getParameter('formValues');
		$haveChildren = $regArr['havechild'];
		$gender = LoggedInProfile::getInstance()->getGENDER();
		$religion = $regArr['religion'];
	}
    $clean = (string) $value;
    /*if($gender=="" || $religion=="")
    {
		if(!$gender)
			throw new sfValidatorError($this, 'mstatus_error_gender', array('value' => $value));
		if(!$religion)
			throw new sfValidatorError($this, 'mstatus_error_religion', array('value' => $value));
    }*/
    if($value=='M' && $gender=='F')
    {
		throw new sfValidatorError($this, ErrorHelp::$ERR_MSTATUS[2]['msg']);			
    }
    elseif($value =='M' && $gender=='M' && $religion!=2)
    {
		throw new sfValidatorError($this, ErrorHelp::$ERR_MSTATUS[3]['msg']);				
    }    
	return $clean;
  }
}
