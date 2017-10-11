<?php

class Sender
{
	public $profileId;
	public $score;
	
	//added by manoranjan
	public $age;
	public $caste;
	public $mtongue;
	public $height;
	public $edu_level;
	public $occupation;
	public $city_res;
	public $country_res;
	public $mstatus;
	public $manglik;
	public $entry_dt;
	
	public function __construct($profileID,$score)
        {
	        //get receiver profile
		$this->score=$score;
		$this->profileId=$profileID;
        }
  	public function getProfileId()
	{
		return $this->profileId;
	}
	public function setProfileId($profileid)
	{
		$this->profileId=$profileid;
	}
	
	public function getScore()
	{
		return $this->score;
	}
	public function setScore($score)
	{
		$this->score=$score;
	}
	
	//added by manoranjan 
	public function getAge()
	{
		return $this->age;
	}
	public function setAge($age)
	{
		$this->age=$age;
	}
	
	public function getCaste()
	{
		return $this->caste;
	}
	public function setCaste($caste)
	{
		$this->caste=$caste;
	}
	
	
	public function getMtongue()
	{
		return $this->mtongue;
	}
	public function setMtongue($mtongue)
	{
		$this->mtongue=$mtongue;
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	public function setHeight($height)
	{
		$this->height=$height;
	}
	
	public function getEdu_level()
	{
		return $this->edu_level;
	}
	public function setEdu_level($edu_level)
	{
		$this->edu_level=$edu_level;
	}
	
	public function getOccupation()
	{
		return $this->occupation;
	}
	public function setOccupation($occupation)
	{
		$this->occupation=$occupation;
	}
	
	public function getCity_res()
	{
		return $this->city_res;
	}
	public function setCity_res($city_res)
	{
		$this->city_res=$city_res;
	}
	
	
	public function getCountry_res()
	{
		return $this->country_res;
	}
	public function setCountry_res($country_res)
	{
		$this->country_res=$country_res;
	}
	
	public function getMstatus()
	{
		return $this->mstatus;
	}
	public function setMstatus($mstatus)
	{
		$this->mstatus=$mstatus;
	}
	
	
	public function getManglik()
	{
		return $this->manglik;
	}
	public function setManglik($manglik)
	{
		$this->manglik=$manglik;
	}
	
	
	public function getEntry_dt()
	{
		return $this->entry_dt;
	}
	public function setEntry_dt($entry_dt)
	{
		$this->entry_dt=$entry_dt;
	}
	
	
}
?>
