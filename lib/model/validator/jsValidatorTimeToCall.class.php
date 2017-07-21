<?php
class jsValidatorTimeToCall extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('TIME_TO_CALL') as $key=>$msg)
    	$this->addMessage($key,$msg);  	
  	
   // $this->addOption('min_length');
   // $this->addOption('max_length');
  }
  
  protected function doClean($value)
  {
        if($value['start_am_pm'] =="a.m.")
                $value['start_am_pm'] = "am";
        if($value['start_am_pm']=="p.m.")
                $value['start_am_pm'] = "pm";
        if($value['time_to_call_end']=="a.m.")
                $value['time_to_call_end'] = "am";
        if($value['time_to_call_end']=="p.m.")
                $value['time_to_call_end'] = "pm";

    if(count(array_filter($value)) && isset($value['time_to_call_start']) && !empty($value['time_to_call_start']) && isset($value['start_am_pm']) && !empty($value['start_am_pm']))
    {
		if($value['time_to_call_start']>12 || $value['time_to_call_start']<1)
			throw new sfValidatorError($this, 'time_to_call_err', array('value' => $value['time_to_call_start']));
		
		if($value['start_am_pm'] !="am" && $value['start_am_pm']!="pm")
			throw new sfValidatorError($this, 'time_to_call_err', array('value' => $value['start_am_pm']));

		$time_to_call=$value['time_to_call_start']." ".$value['start_am_pm'];
	}
	else if(count(array_filter($value)) && isset($value['time_to_call_end']) && !empty($value['time_to_call_end']) && isset($value['end_am_pm']) && !empty($value['end_am_pm']))
	{
		if($value['time_to_call_end']>12 || $value['time_to_call_end']<1)
			throw new sfValidatorError($this, 'time_to_call_err', array('value' => $value['time_to_call_end']));
		if($value['end_am_pm'] !="am" && $value['end_am_pm']!="pm")
			throw new sfValidatorError($this, 'time_to_call_err', array('value' => $value['end_am_pm']));
		$time_to_call=$value['time_to_call_end']." ".$value['end_am_pm'];
	}
	else
	{
		 throw new sfValidatorError($this, 'time_to_call_err');
	}
	unset($value);
	return $time_to_call;
  }
}
