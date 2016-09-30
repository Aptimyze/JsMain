<?php
class EditOnFtoContactConfirmation{
	private $_profile;
	private $_unfilled_layers;
	public $current_layer;
	/** Constructor.
	 * @param LoggedInProfile $profile. Takes an object of profile.
	 * @returns void
	 * */
	function __construct($profile,$from_mailer=''){
		//If profile object is provided then use it otherwise create profile object
		if($profile instanceOf Profile)
			$this->_profile=$profile;
		else{
			$fields='FAMILY_INCOME,FATHER_INFO,MOTHER_OCC,PARENT_CITY_SAME,GENDER,BTIME,CITY_BIRTH,COUNTRY_BIRTH,RELIGION,T_BROTHER,T_SISTER,WEIGHT,HAVE_CAR,OWN_HOUSE,HAVE_JEDUCATION';
			$this->_profile=new LoggedInProfile("",$profile);
			$this->_profile->getDetail($profile,"",$fields);
		}
		$this->layerToShow($from_mailer);
	}	
	/**As per logic mentioned in trac#1451. 
	 * The definition for Education and Occupation being filled is that any 3 out of the following fields are filled:
	 * School Name
	 * College Name
	 * PG College Name
	 * Organization Name
	 * The definition for “Horoscope Details” to be filled is either:
	 * a) All Birth Details are provided helping us to automatically generate a horoscope
	 * OR
	 * b) The user manually uploads a soft copy of a horoscope
	 * The definition for “Family Details” to be filled is that any 3 of the following fields is filled:
	 * Family Income
	 * Father’s Occupation
	 * Mother’s Occupation
	 * No. of Brothers
	 * No. of Sisters
	 * Living with parents
	 * The definition of completion for "Lifestyle/Attributes?" is that all of the following attributes are specified:
	 * Own House
	 * Own Car
	 * Weight
	 * Spoken Languages (it is assumed to be filled if at least one language is selected)
	 * we will ask the user to fill one of the categories of information which are not filled as per the logic mentioned by selecting one of them randomly.
	 * @return array of strings that represent layers
	 *
	 * */
	public function getUnfilledLayers(){
		//Education and Occupation related layer	
		//Fetch education other details
		$education=$this->_profile->getEducationDetail();
		$filled_count=0;
		foreach(array('SCHOOL','COLLEGE','PG_COLLEGE') as $field)
			if($education->$field && $education->$field!='-')
				$filled_count++;
		if($this->_profile->getCOMPANY_NAME() && $this->_profile->getCOMPANY_NAME()!='-')
			$filled_count++;
		if($filled_count<3)
			$result[]='PEO';
		//Horoscopre Details Related
	    if($this->_profile->getRELIGION()==Religion::HINDU||$this->_profile->getRELIGION()==Religion::JAIN){
		$horo_db= ProfileAstro::getInstance();
		if($horo_db->getIfHoroPresent($this->_profile->getPROFILEID()) || ($this->_profile->getBTIME() && $this->_profile->getCITY_BIRTH() && $this->_profile->getCOUNTRY_BIRTH()))
			$horo_yes=true;
		if(!$horo_yes)
			$result[]='CUH';
		}
		//Family Details related
		$filled_count=0;
		foreach(array('FAMILY_INCOME','FATHER_INFO','MOTHER_OCC','PARENT_CITY_SAME') as $field){
			$fn_name="get$field";
			if($this->_profile->$fn_name()&& $this->_profile->$fn_name()!='-')
				$filled_count++;
		}
		if($this->_profile->getT_BROTHER()!=='')
			$filled_count++;
		if($this->_profile->getT_SISTER()!=='')
			$filled_count++;
		if($filled_count<3)
			$result[]='PFD';

		//Lifestyle Attributes related
		$unfilled_life_atr=false;
		foreach(array('WEIGHT','HAVE_CAR','OWN_HOUSE' ) as $field){
			$fn_name="get$field";
			if(!$this->_profile->$fn_name()||$this->_profile->$fn_name()=='-'){
				$unfilled_life_atr=true;break;
			}
		}
		if(!$unfilled_life_atr){
		$hobbies=$this->_profile->getHobbies();
		if(!$hobbies->LANGUAGE)
			$unfilled_life_atr=true;
		}
		if($unfilled_life_atr)
			$result[]='PLA';
		$this->_unfilled_layers=$result;
		return $result;
	}
	function layerToShow($from_mailer=''){
		$this->getUnfilledLayers();
		$count=count($this->_unfilled_layers);
		if($count){
			if($from_mailer){//if called from mailer, then show CUH if unfilled else randomly select one from unfilled
				if(in_array("CUH", $this->_unfilled_layers)) {
					$this->current_layer='CUH';
				}
			}
			if(!$this->current_layer){
				$number=rand(0,$count-1);
				$this->current_layer=$this->_unfilled_layers[$number];
			}
		}
		return $this->current_layer;
	}
	function toOpenLayer(){
		$random=rand(1,5);
		if($random==1)
			return true;
		else
			return false;
	}
	function getLinkToShowHref($from_where=""){
		//Default layer to be shown is Lifestyle and attribute layer.
		if(!$this->current_layer)
			$this->current_layer='PLA';
		$uri=urlencode($_SERVER['REQUEST_URI']);
                if($from_where)
			$redirect="from_where=$from_where";
			$redirect.="&prev_url=$uri";
      return "/profile/editProfile?flag=".$this->current_layer."&width=700&from_fto=1&$redirect"; 
	}
	function getLinkToShowText(){
		switch($this->current_layer){
			case 'CUH':
				$text="Upload your horoscope";
				break;
			case 'PEO':
				$text="Fill your Education & Occupation details";
				break;
			case 'PFD':
				$text='Tell more about your family';
				break;
			default:
				$text='Tell more about yourself';
				break;
		}
		return $text;
	}
}
