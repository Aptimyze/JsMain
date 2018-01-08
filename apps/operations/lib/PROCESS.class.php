<?php
class PROCESS
{
	private $processName;	
	private $method;
	private $subMethod;
	private $limit;
	private $executive;
	private $exceed;
	private $disposition;
	private $executives;
	private $days;
	private $username;
	private $profiles;
	private	$startDate;	
	private $endDate;
	private $curDate;
	private $idAllot;
	private $maxDays;
	private $totalRecord;
	private $level;
	private $scoreLowerLimit;
	private $maxAllotNo;
	private $state;
	private $city;
	private $center;
	private $profileCities;
	private $specialCities;
	private $allCenters;
	private $leadIdSuffix;
	private $campaignCntArr;
	private $score;
	private $discount_status;
	private $remainingTransferrableLimit;
	private $transferredProfilesCount;
	private $limitArr;
	private $everPaidProfiles;
	private $phoneVerifiedProfiles;
	private $agentInfo;
	private $specialCityList;
	private $restIndCities;
	private $optinArrLevelBased;
	private $discountArrLevelBased;
	private $profileArrLevelBased;
	private $secondLevel;

        public function setSecondLevel($secondLevel)
        {
                $this->secondLevel =$secondLevel;
        }
        public function getSecondLevel()
        {
                return $this->secondLevel;
        }
        public function setAgentDetails($agentInfo)
	{
                $this->agentInfo =$agentInfo;
        }
        public function getAgentDetails()
	{
                return $this->agentInfo;
        }
        public function setSpecialCityList($specialCityList)
	{
                $this->specialCityList =$specialCityList;
        }
        public function getSpecialCityList()
	{
                return $this->specialCityList;
        }
        public function setRestIndCities($restIndCities)
	{
                $this->restIndCities =$restIndCities;
        }
        public function getRestIndCities()
	{
                return $this->restIndCities;
        }
        public function setProfileArrLevelBased($profileArrLevelBased)
	{
                $this->profileArrLevelBased =$profileArrLevelBased;
        }
        public function getProfileArrLevelBased()
	{
                return $this->profileArrLevelBased;
        }
        public function setDiscountArrLevelBased($discountArrLevelBased)
	{
                $this->discountArrLevelBased =$discountArrLevelBased;
        }
        public function getDiscountArrLevelBased()
	{
                return $this->discountArrLevelBased;
        }
        public function setOptinArrLevelBased($optinArrLevelBased)
	{
                $this->optinArrLevelBased =$optinArrLevelBased;
        }
        public function getOptinArrLevelBased()
	{
                return $this->optinArrLevelBased;
        }
	public function setProcessName($name)
	{
		$this->processName=$name;
	}
	public function setMethod($meth)
	{
		$this->method=$meth;
	}
	public function setSubMethod($subMeth)
	{
		$this->subMethod=$subMeth;
	}
	//for disposition	
	public function setLimit($limit)
	{
		$this->limit=$limit;
	}
	//for dispo
	public function setExecutive($exe)
	{
		$this->executive=$exe;
	}
	//for dispo
	public function setExceed($exceed)
	{
		$this->exceed=$exceed;
	}
	public function setDisposition($dispo)
	{
		$this->disposition=$dispo;
	}
	//for sales
	public function setExecutives($executives)
	{
		$this->executives=$executives;
	}
	public function setTransferredProfilesCount($count){
		$this->transferredProfilesCount = $count;
	}
	public function setRemainingTransferrableLimit($count){
		$this->remainingTransferrableLimit = $count;
	}
	//for sales renewal
	public function setDays($days)
	{
		$this->days=$days;
	}
	//for manual nolongerworking
	public function setUsername($username)
	{
		$this->username=$username;
	}
	public function setProfiles($profiles)
	{
		$this->profiles=$profiles;
	}
        public function setEverPaidPool($profiles)
        {
                $this->everPaidProfiles=$profiles;
        }
        public function setPhoneVerifiedProfiles($profiles)
        {
                $this->phoneVerifiedProfiles=$profiles;
        }
        public function setStartDate($startDate)
        {
                $this->startDate=$startDate;
        }
	public function setEndDate($endDate)
	{
		$this->endDate=$endDate;
	}
	public function setCurDate($curDate)
	{
		$this->curDate=$curDate;
	}
	public function setIdAllot($id_arr)
	{
		$this->idAllot=$id_arr;
	}
	public function setMaxSalesDays($maxSalesDays)
	{
		$this->maxSalesDays=$maxSalesDays;
	}

	public function setLevel($level)
        {
                $this->level=$level;
        }
	public function setScoreLowerLimit($score)
	{
		$this->scoreLowerLimit=$score;
	}
        public function setTotalRecord()
        {
                return $this->totalRecord;
        }
	public function setMaxAllotNo($maxAllot)
	{
		$this->maxAllotNo=$maxAllot;
	}
	public function setState($state)
	{
		$this->state=$state;
	}
	public function setCity($city)
	{
		$this->city=$city;
	}
	public function setCenter($center)
        {
                $this->center=$center;
        }
	public function setSpecialCities($cities)
        {
                $this->specialCities=$cities;
        }
	public function setProfileCities($centers)
	{
		$this->profileCities=$centers;
	}
	public function setAllCentersArray($locations)
	{
		$this->allCenters=$locations;
	}
        public function setPrivilege($privilege)
        {
                $this->privilege=$privilege;
        }
	public function setLeadIdSuffix($leadIdSuffix)	
	{
		$this->leadIdSuffix=$leadIdSuffix;
	}
	public function setCampaignCntArr($campaignCntArr)
	{
		$this->campaignCntArr=$campaignCntArr;
	}
        public function setScore($score)
        {
                $this->score=$score;
        }
        public function setDiscountStatus($status)
        {
                $this->discount_status=$status;
        }
	public function setLimitArr($limitArr)
	{
		$this->limitArr=$limitArr;
	}

	// Get functions 
	public function getLeadIdSuffix()
	{
		return $this->leadIdSuffix;
	}
	public function getCampaignCntArr()
	{	
		return $this->campaignCntArr;
	}
        public function getScore()
        {
                return $this->score;
        }
        public function getPrivilege()
        {
                return $this->privilege;
        }
	public function getProcessName()
	{
		return $this->processName;
	}
	public function getMethod()
	{       
        	return $this->method;
	}
	public function getSubMethod()
	{
        	return $this->subMethod;
	}
	public function getLimit()
	{
		return $this->limit;
	}
	public function getExecutive()
	{
		return $this->executive;
	}
	public function getTransferredProfilesCount(){
		return $this->transferredProfilesCount;
	}
	public function getRemainingTransferrableLimit(){
		return $this->remainingTransferrableLimit;
	}
	public function getExceed()
	{
		return $this->exceed;
	}
	public function getDisposition()
	{
		return $this->disposition;
	}
	public function getExecutives()
	{
		return $this->executives;
	}
	public function getDays()
	{
		return $this->days;
	}	
	public function getUsername()
	{
		return $this->username;
	}
	public function getProfiles()
	{
		return $this->profiles;	
	}
        public function getEverPaidPool()
        {
                return $this->everPaidProfiles;
        }
        public function getPhoneVerifiedProfiles()
        {
                return $this->phoneVerifiedProfiles;
        }
        public function getStartDate()
        {
                return $this->startDate;
        }
	public function getEndDate()
	{
		return $this->endDate;
	}
	public function getCurDate()
	{
		return $this->curDate;
	}
	public function getIdAllot()
	{
		return $this->idAllot;
	}
	public function getMaxSalesDays()
	{
		return $this->maxSalesDays;
	}
	public function getLevel()
	{
		return $this->level;
	}
	public function getScoreLowerLimit()
	{
		return $this->scoreLowerLimit;
	}
        public function getTotalRecord()
        {
                return $this->totalRecord;
        }
	public function getMaxAllotNo()
	{
		return $this->maxAllotNo;
	}
	public function getState()
	{
		return $this->state;
	}
	public function getCity()
	{
		return $this->city;
	}
	public function getCenter()
        {
                return $this->center;
        }
	public function getSpecialCities()
	{
		return $this->specialCities;
	}
	public function getProfileCities()
	{
		return $this->profileCities;
	}
	public function getAllCentersArray()
        {
                return $this->allCenters;
        }
	public function getDiscountStatus()
        {
                return $this->discount_status;
        }
	public function getLimitArr()
	{
		return $this->limitArr;
	}
}
?>
