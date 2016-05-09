<?php

class FtoHandler implements VariableHandler{
  
  private $_lru;
  private $_var_object;
  private $_profile_id;
  private $_token_name;

  public function __construct($var_object) {
	  $this->_lru = new Cache(LRUObjectCache::getInstance());
	  $this->_var_object=$var_object;
	  $this->_token_name=$var_object->getVariableName();
	  if($this->_token_name!='FTO_WORTH'){
	  $this->_profile_id=$var_object->getParam('profileid');
	  
	  $this->ftoStateObj=$this->_lru->get($this->_profile_id)->getPROFILE_STATE()->getFTOStates();
	  }
  }

  public function getActualValue() {
	switch($this->_token_name){
	case 'FTO_END_DAY':	
		$op = $this->ftoStateObj->getExpiryDay();
		break;
	case 'FTO_END_DAY_SINGLE_DOUBLE_DIGIT':	
		$op = $this->ftoStateObj->getExpiryDay(1);
		break;
	case 'FTO_END_MONTH':
		$op=$this->ftoStateObj->getExpiryMonth();
		break;
	case 'FTO_END_MONTH_UPPERCASE':
		$op=$this->ftoStateObj->getExpiryMonth(1);
		break;
	case 'FTO_END_YEAR':
		$op=$this->ftoStateObj->getExpiryYear();
		break;
	case 'FTO_END_DAY_SUFFIX':
		$op=$this->ftoStateObj->getExpiryDaySuffix();
		break;
	case 'FTO_START_DAY':
		$op = $this->ftoStateObj->getEntryDay();
		break;
	case 'FTO_START_MONTH':
		$op = $this->ftoStateObj->getEntryMonth();
		break;
	case 'FTO_START_YEAR':
		$op = $this->ftoStateObj->getEntryYear();
		break;
	case 'FTO_START_DAY_SUFFIX':
		$op = $this->ftoStateObj->getEntryDaySuffix();
		break;
	case 'FTO_WORTH':
		$op = FTOLiveFlags::FTO_WORTH;	//CHECK
		break;
	case 'FTO_START_DAY_SINGLE_DOUBLE_DIGIT':	
		$op = $this->ftoStateObj->getEntryDay(1);
		break;
	}
    return $op;
  }
}
