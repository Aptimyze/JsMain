<?php
class jsValidatorHandicapped extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('COUNTRY_CITY') as $key=>$msg)
    	$this->addMessage($key,$msg);  	
  	 $this->addOption('handicapped');
  	 $this->addOption('natureOfhandicapped');
	$this->addOption('fieldName');
   // $this->addOption('min_length');
   // $this->addOption('max_length');
  }
  
  protected function doClean($value)
  {
	 $handicapped = $this->getOption('handicapped');
	 $natureOfhandicapped = $this->getOption('natureOfhandicapped');
	 $fieldName = $this->getOption('fieldName');
		
		if(($handicapped =="" && $natureOfhandicapped !=""))
		{
			$natureOfhandicapped="";
			//throw new sfValidatorError($this,'please provide valid handicapped and nature of handicapped');
		}
		
		if($fieldName=="NATURE_HANDICAP"){
			 if ($handicapped !== '1' && $handicapped !== '2') { //bugid 63425 added server side checks
				$natureOfhandicapped=null;
				}
			$field_map_handicap_name=ObjectiveFieldMap::getFieldMapKey("NATURE_HANDICAP");
		}
		else
			$field_map_handicap_name=ObjectiveFieldMap::getFieldMapKey("HANDICAPPED");
		$choices=@array_keys(FieldMap::getFieldLabel($field_map_handicap_name,'',1));
		if(!in_array($value,$choices))
		{
			throw new sfValidatorError($this,'please provide a valid value for Handicap');
		}
		if($fieldName=="NATURE_HANDICAP")
			return 	$natureOfhandicapped;
		else
			return $value;
  }	
}
