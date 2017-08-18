<?php
class jsValidatorCountryCity extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    foreach(ErrorHelp::getErrorArrayByField('COUNTRY_CITY') as $key=>$msg)
    	$this->addMessage($key,$msg);  	
  	 $this->addOption('city');
  	 $this->addOption('country');
  	 $this->addOption('fieldName');
   // $this->addOption('min_length');
   // $this->addOption('max_length');
  }
  
  protected function doClean($value)
  {
	 $city = $this->getOption('city');
	 $country = $this->getOption('country');
	 $fieldName = $this->getOption('fieldName');
		
		if($country !=51 && $country !=128)
			$city="";
		if($city=="" && $country=="")
		{
			throw new sfValidatorError($this,'please provide both country and city');
		}
		if($city && ($country==51 || $country==128))
		{
			if($country==51)
			{
				$field_map_city_name=ObjectiveFieldMap::getFieldMapKey("CITY_INDIA");
			}
			else if($country==128)
			{
				$field_map_city_name=ObjectiveFieldMap::getFieldMapKey("CITY_USA");
			}
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_city_name,'',1));

			//for city usa need to exclude indian cities
			/*if($country==128)
			{
				foreach($choices as $key=>$val)
				{
					if(!ctype_digit($val))
					{
						unset($choices[$key]);
					}
				}
			}*/
			if(!in_array($city,$choices))
			{	
				if($country==51)
					throw new sfValidatorError($this,'please provide a valid city value for country India');
				if($country==128)
					throw new sfValidatorError($this,'please provide a valid city value for country USA');
			}
		}
		else if ($city=="" && ($country==51))
		{
			//if($country==51)
					throw new sfValidatorError($this,' city value Blank for country India');
			//if($country==128)
			//		throw new sfValidatorError($this,'city value Blank for country USA');
		}
		else if($country)
		{
			$field_map_country_name=ObjectiveFieldMap::getFieldMapKey("COUNTRY_RES");
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_country_name,'',1));
			if(!in_array($country,$choices))
			{
				throw new sfValidatorError($this,'please provide a valid value for country');
			}
		}

		if($fieldName=="city")
			return $city;
		elseif($fieldName=="country")
			return $country;
  }
}
