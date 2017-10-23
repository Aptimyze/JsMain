<?

//include_once('ProcessingClassInterface.class.php');
//include_once('CacheableInterface/Cache.class.php');
//include_once('CacheableInterface/LRUObjectCache.class.php');

class ProfileHandler implements VariableHandler{

  private $__callbacks;
  private $__profile_obj;
  private $__cache;
  private $_var_object;

  public function __construct($var_object) {
	  $this->_var_object=$var_object;
    $this->__profile_obj = null;
    $this->__lru = new Cache(LRUObjectCache::getInstance());
    $this->__callbacks = 
      array(
        'USERNAME' => 'getUSERNAME',
        'GENDER' => 'getDecoratedGender',
        'CASTE' => 'getDecoratedCaste', // similarly extending for other tokens.
        'PROFILEID' => 'getPROFILEID',
        'WEIGHT' => 'getDecoratedWeight',
        'OCCUPATION' => 'getDecoratedOccupation',
        'MSTATUS' => 'getDecoratedMaritalStatus',
        'MTONGUE' => 'getDecoratedCommunity',
        'INCOME' => 'getDecoratedIncomeLevel',
        'RELIGION' => 'getDecoratedReligion',
        'HEIGHT' => 'getDecoratedHeight',
        'AGE' => 'getAGE',
        'CITY' => 'getDecoratedCity',
	'COUNTRY' => 'getDecoratedCountry',
        'EDUCATION' => 'getDecoratedEducation',
        'RES_STATUS' => 'getDecoratedRstatus',
        'GOING_ABROAD' => 'getDecoratedSettlingAbroad',
        'FAMILY_INCOME' => 'getDecoratedFamilyIncome',
        'COMPANY_NAME' => 'getDecoratedCompany',
        'OWN_HOUSE' => 'getDecoratedOwnHouse',
        'HAVE_CAR' => 'getDecoratedHaveCar',
        'FAVOURITE_MOVIE' => 'getFavouriteMovie',
        'FAVOURITE_BOOK' => 'getFavouriteBook',
        'FAVOURITE_TV_SHOW' => 'getFavouriteTVShow',
        'COLLEGE_NAME' => 'getCollegeName',
        'PGCOLLEGE_NAME' => 'getPGCollegeName',
        'SCHOOL_NAME' => 'getSchoolName',
        'SIBLINGS_INFO' => 'getSiblings',
        'MOBILE_NUMBER' =>'getPHONE_MOB',
      	'LANDLINE_NUMBER'=>'getPHONE_RES',
        'CITY_SMALL'=>'getDecoratedCity',
      	'OCCUPATION_SMALL'=>'getDecoratedOccupation',
      	'GOTHRA'=>'getDecoratedGothra',
      	'NAKSHATRA'=>'getAstroKundali',
      	'YOURINFO'=>'getDecoratedYourInfo',
      	'FAMILYINFO'=>'getDecoratedFamilyInfo',
      	'FAMILY_VALUES'=>'getDecoratedFamilyValues',
      	'FAMILY_TYPE'=>'getDecoratedFamilyType',
      	'MOTHER_OCCUPATION'=>'getDecoratedMotherOccupation',
      	'FAMILY_STATUS'=>'getDecoratedFamilyStatus',
      	'LIVING_WITH_PARENTS'=>'getDecoratedLiveWithParents',
      	'FAMILY_BACK'=>'getFAMILY_BACK',
      	'FATHER'=>'getFATHER_INFO',
      	'EDUCATION_DETAIL'=>'getEDUCATION',
      	'JOB_INFO'=>'getDecoratedJobInfo',
      	'EMAIL'=>'getEMAIL',
      	'PASSWORD'=>'getPASSWORD',
      	'SUBSCRIPTION'=>'getSUBSCRIPTION',
        );
  }
  /*
     public function setParams($params = array()) {
     if (isset($params) && is_array($params)) {
     $this->__profile_id = $params['PROFILEID'];
     $this->__token = $params['TOKEN'];
     $this->__profile_obj = $this->__lru->get($this->__profile_id); //new Profile("", $this->__profile_id);
     }
     }*/

  public function getActualValue() {
	  global $do_not_send;
	  if(!trim($this->_var_object->getParam("profileid"))){
		  $do_not_send=true;
		  return;
	  }
	  
    $token = strtoupper($this->_var_object->getVariableName());
    $this->__profile_obj = $this->__lru->get($this->_var_object->getParam("profileid"));
    $this->__profile_obj->setNullValueMarker("-");
    if (isset($token)) {
    	switch($token)
    	{
    		case "CONTACT_NUMBER":
    			return $this->getPhone();
    			break;
		case "NAME_PROFILE":
			$InouObj = new incentive_NAME_OF_USER;
			$name = $InouObj->getName($this->_var_object->getParam("profileid"));
			if($name)
				return $name;
			else
				return $this->__profile_obj->getUSERNAME();
			break;
    case "NAME_OTHER_PROFILE":
        $this->__receiver_profile_obj = $this->__lru->get($this->_var_object->getParam("receiver_id"));
        if($this->__receiver_profile_obj)
        {
          $nameOfUserObj= new NameOfUser();
          $otherProfileObjArr[]=$this->__profile_obj;
          $nameOfUser=$nameOfUserObj->showNameToProfiles($this->__receiver_profile_obj,$otherProfileObjArr);
        }
        if($nameOfUser && $nameOfUser[$this->__profile_obj->getPROFILEID()]["SHOW"]==true)
        {
          if($nameOfUser[$this->__profile_obj->getPROFILEID()]['NAME'])
            return $nameOfUser[$this->__profile_obj->getPROFILEID()]['NAME'];
        }
        return $this->__profile_obj->getUSERNAME();
    break;
		case "RELIGION_CASTE_OR_SECT_LABEL":
			if($this->__profile_obj->getRELIGION() == 2 || $this->__profile_obj->getRELIGION() == 3)
				return "Religion &amp; Sect";
			else
				return "Religion &amp; Caste";
			break;
		case "CITY_WITH_COUNTRY":
			$output = $this->__profile_obj->getDecoratedCity();
			if($this->__profile_obj->getDecoratedCity() && $this->__profile_obj->getDecoratedCountry())
				$output = $output.", ";
			$output = $output.$this->__profile_obj->getDecoratedCountry();
			return $output;
			break;
		case "RELIGION_CASTE_VALUE_TEMPLATE":
			if($this->__profile_obj->getRELIGION())
			{
				$temp = explode(":",$this->__profile_obj->getDecoratedCaste());
				$caste = $temp[1];
				unset($temp);
			}
			else
				$caste = $this->__profile_obj->getDecoratedCaste();
			$output = $this->__profile_obj->getDecoratedReligion();
			if($this->__profile_obj->getDecoratedReligion() && $caste)
				$output = $output.", ";
			$output = $output.$caste;
			return $output;
			break;
    case "RELIGION_CASTE_VALUE_TEMPLATE_2":
      if($this->__profile_obj->getRELIGION())
      {
        $temp = explode(":",$this->__profile_obj->getDecoratedCaste());
        $caste = $temp[1];
        unset($temp);
      }
      else
        $caste = $this->__profile_obj->getDecoratedCaste();
      $output = $this->__profile_obj->getDecoratedReligion();
      if($this->__profile_obj->getDecoratedReligion() && $caste)
        $output = $output.", ";
      $output = $output.$caste;
      return $this->__truncatedString($output,20);
      break;  
		case "CITY_SMALL":
		case "OCCUPATION_SMALL":
			return $this->__truncatedString(call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token))),16);
			break;
		case "YOURINFO":
			$infoC= call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token)));
			$info = substr( $infoC, 0, strrpos( substr( $infoC, 0, 120), ' ' ) );
                        $info =htmlspecialchars($info, ENT_QUOTES, 'UTF-8');
                        if($info)
                        {
                                if($this->__profile_obj->getGENDER() =='M')
                                        $gen =  "him";
                                else
                                        $gen = "her";
                                $str = "<strong>About ".$gen."</strong>: ";
                                return $str.$info."...";
                        }
                        else return ;
			break;
		case "MTONGUE_SMALL":
    		$mtongue=$this->__profile_obj->getDecoratedCommunity();
			if(strlen($mtongue) <= 16)
				return $mtongue;
			else
			{
				return FieldMap::getFieldLabel("community_small", $this->__profile_obj->getMTONGUE());
			}
			break;
		case "GOTHRA":
		if($this->__profile_obj->getRELIGION() == 1)
				return call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token)));
			else
				return "-";
			break;
		case "NAKSHATRA":
		if($this->__profile_obj->getRELIGION() == 1)
				return call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token)))->nakshatra;
			else
				return "-";
			break;
	case "HE_SHE":
		  if($this->__profile_obj->getGENDER() =='M')
			  return "she";
		  else 
			  return "he";
		  break;
	case "HIS_HER":
		  if($this->__profile_obj->getGENDER() =='M')
			  return "her";
		  else 
			  return "his";
		  break;
		  case "PAIDSTATUS":
		if($this->__profile_obj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="EVALUE")
			$str = "eValue";
		elseif ($this->__profile_obj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="ERISHTA")
      $str = "eRishta";
    elseif ($this->__profile_obj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="JSEXCLUSIVE")
      $str = "JsExclusive";
    
		if($str)
		return "| ".$str;
		break;
		case "ABOUTPROFILE":
			$info =$this->__truncatedString(call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token))),160);
			if($info)
			{
				if($this->__profile_obj->getGENDER() =='M')
					$gen =  "him";
				else 
					$gen = "her"; 	
				$str = "<b>About ".$gen."</b> : ";
				return $str.$info."...";
			}
			else 
			return ;
		break;
    		default:
    			    	
      if (method_exists($this->__profile_obj, $this->__getTokenCallback($token))) {
        return call_user_func(array($this->__profile_obj, $this->__getTokenCallback($token)));
      } else {
        echo $token. " method Doesn't Exist \n";
        //Call custom made functions. The design has to be thought.
      }
    }
  }
  }

  private function __getTokenCallback($token) {
    return $this->__callbacks[$token];
  }

  public function registerCallback($token, $callback) {
    $this->__callbacks[strtoupper($token)] = $callback;
  }

  public function getContents() {
    $this->__lru->getContents();
  }

  public function getCacheStats() {
    return $this->__lru->getCacheStats();
  }
  public function getPhone()
  {
  	return $this->__profile_obj->getPHONE_MOB()?$this->__profile_obj->getPHONE_MOB():$this->__profile_obj->getPHONE_RES();
  	
  }
  private function __truncatedString($str,$length)
  {
  	if(strlen($str) <= $length)
		return $str;
	else
		return substr($str, 0,$length-2)."...";
  }
}

/*$profile = new ProfileHandler;
  $profile->setParams(array('PROFILEID' => '388023', 'TOKEN' => 'USERNAME'));
  $profile->getTokenValue('username');
  $profile->getTokenValue('gender');
 */
