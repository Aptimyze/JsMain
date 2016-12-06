<?php
/*
 * Visitors.php
 * 
 * Pankaj Khandelwal <pankaj.khandelwal@jeevansathi.com>
 * 
 * @brief This class is used to handle all functionalities related to visitor alert
 * @author Pankaj Khandelwal
 * @created 2013-11-18
 */
class Visitors
{
	private $profile;
	private $skipProfile;
	private $visitorsProfile;
	public function __construct($profile)
	{
		if (!isset($profile))
			throw ("No Profile id or object is provided in Visitor.class.php");
		if ($profile instanceof Profile)
			$this->profile = $profile;
		else
			$this->profile = new Profile('', $profile);
	}
	private function extractProfileId($value)
	{
		return $value["VIEWER"];
	}
	public function getVisitorProfile($page="",$profileCount="",$infoTypenav="",$memcacheObjToSetAllVisitorsKey="")
	{
		$skipContactedType = SkipArrayCondition::$VISITOR;
		$skipProfileObj    = SkipProfile::getInstance($this->profile->getPROFILEID());
		$skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
		$viewLogObj        = new VIEW_LOG_TRIGGER();
		$visitorsProfile   = $viewLogObj->getViewLogData($this->profile->getPROFILEID(), $skipProfile);
		if (!is_array($visitorsProfile))
			return null;
		else{
			//$func                        = function($ar){return $ar['VIEWER'];};
			$profileIdArr                = array_map(array($this,"extractProfileId"), $visitorsProfile);
			$negativeListObj             = new INCENTIVE_NEGATIVE_TREATMENT_LIST();
			$parameters['FLAG_VIEWABLE'] = 'N';
			$profileIdArr                = $negativeListObj->removeNegativeIdsFromList($parameters, $profileIdArr);
			if (!empty($profileIdArr))
			{
				$multipleProfileObj         = new ProfileArray;
				$fieldList                  = "AGE,HEIGHT,MANGLIK,MSTATUS,CASTE,RELIGION,MTONGUE,COUNTRY_RES,INCOME,PROFILEID,OCCUPATION,EDU_LEVEL_NEW,CITY_RES";
				$profileIdArr1["PROFILEID"] = implode(",", $profileIdArr);
				$this->visitorsProfile      = $multipleProfileObj->getResultsBasedOnJprofileFields($profileIdArr1, '', '', $fieldList, "JPROFILE", "", "");
                            if($infoTypenav["matchedOrAll"]!="A")
                                $this->passInVisitors();
                            
			}
			if ($infoTypenav["matchedOrAll"]=="A") 
                            $this->filterCheck();
                        
                        $this->reverseFilterCheck();
		}
		if (is_array($this->visitorsProfile)) {
			foreach ($this->visitorsProfile as $key => $value) {
				$returnProfiles[$value->getPROFILEID()] =  $visitorsProfile[$value->getPROFILEID()];
				unset($returnProfiles[$value->getPROFILEID()]["VIEWER"]);
			}
		}
		$returnProfiles = $this->sortArray($returnProfiles);
                
                if($memcacheObjToSetAllVisitorsKey)
                    $memcacheObjToSetAllVisitorsKey->setVISITORS_ALL(count($returnProfiles));
                
		if(($page || $profileCount) && is_array($returnProfiles))
		{
			$count = count($returnProfiles);
			$profileMemcacheObj = new ProfileMemcacheService($this->profile);
			$memcacheCount = $profileMemcacheObj->get("VISITOR_ALERT");
			$diff = $count-$memcacheCount;
			if($diff!=0)
			{
				$profileMemcacheObj->update("VISITOR_ALERT",$diff);
				$profileMemcacheObj->updateMemcache();

			}
			$offset = $page*$profileCount;
			$returnProfiles = array_slice($returnProfiles,$offset,$profileCount,true);
		}
		return $returnProfiles;
	}
	public function passInVisitors()
	{//education,occupation,city to be added, gender to be removed
		include_once(JsConstants::$docRoot . "/profile/connect_functions.inc");
		include_once(JsConstants::$docRoot . "/commonFiles/connect_dd.inc");
		$this->profile->getDetail('', '', "GENDER,INCOME,PRIVACY");
                $partnetProfile = new PartnerProfile($this->profile);
                $partnetProfile->getDppCriteria();
                $lage = $partnetProfile->getLAGE();
                $hage = $partnetProfile->getHAGE();
                if (!$lage || !$hage)
                        $no_age = 1;
                $lheight = $partnetProfile->getLHEIGHT();
                $hheight = $partnetProfile->getHHEIGHT();
                if (!$lheight || !$hheight)
                        $no_height = 1;
                $manglik = explode(",", $partnetProfile->getMANGLIK());
                $mstatus = explode(",", $partnetProfile->getMSTATUS());
                $caste   = explode(",", $partnetProfile->getCASTE());
                if ($caste)
                        $all_caste = get_all_caste($caste);
                $religion   = explode(",", $partnetProfile->getRELIGION());
                $community  = explode(",", $partnetProfile->getMTONGUE());
                $country    = explode(",", $partnetProfile->getCOUNTRY_RES());
                $par_income = explode(",", $partnetProfile->getINCOME());
                $city = explode(",", $partnetProfile->getCITY_RES());
                $occupation = explode(",", $partnetProfile->getOCCUPATION());
                $education = explode(",", $partnetProfile->getEDU_LEVEL_NEW());
                if(count($par_income)>0 && $par_income[0]!="")
                {
                        foreach($par_income as $key=>$val)
                                $act_income[]=get_income_sortby_new($val,'','F');
                                //$act_income[]=$income_arr[$val];
                }
                else
                        $act_income[]=get_income_sortby_new($this->profile->getINCOME(),'','F');
                $act_income = explode(",",$act_income[0]);
                foreach ($this->visitorsProfile as $key => $profile) {
                        $allow         = 1;
                        $oth_age       = $profile->getAGE();
                        $oth_height    = $profile->getHEIGHT();
                        $oth_manglik   = $profile->getMANGLIK();
                        $oth_mstatus   = $profile->getMSTATUS();
                        $oth_caste     = $profile->getCASTE();
                        $oth_religion  = $profile->getRELIGION();
                        $oth_community = $profile->getMTONGUE();
                        $oth_country   = $profile->getCOUNTRY_RES();
                        $oth_income    = $profile->getINCOME();
                        $oth_prof      = $profile->getPROFILEID();
                        $oth_edu      = $profile->getEDU_LEVEL_NEW();
                        $oth_occ      = $profile->getOCCUPATION();
                        $oth_city      = $profile->getCITY_RES();

                        if (!($oth_age >= $lage && $oth_age <= $hage)) {
                                $allow = 0;
                        }
                        if ($allow == 1) {
                                if (!($oth_height >= $lheight && $oth_height <= $hheight))
                                        $allow = 0;
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($mstatus, $oth_mstatus);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($country, $oth_country);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($all_caste, $oth_caste);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($religion, $oth_religion);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($community, $oth_community);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($manglik, $oth_manglik);
                        }
                        if ($allow == 1 && $this->profile->getGENDER() == 'F')
                                if (is_array($act_income)) {
                                                $allow = $this->check_in_array($act_income, $oth_income);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($city, $oth_city);
                        }
                        if ($allow == 1) {
                                $allow = $this->check_in_array($occupation, $oth_occ);
                        }if ($allow == 1) {
                                $allow = $this->check_in_array($education, $oth_edu);
                        }
                        
                        if ($allow != 1) {
                                unset($this->visitorsProfile[$key]);
                        }
                }
	}
	function check_in_array($array, $value)
	{
		if (count($array) > 0)
			if (!(count($array) == 1 && $array[0] == "" && $array[0] != 'DM'))
			{
				if (!in_array($value, $array))
					return 0;
			}
		return 1;
	}
	public function filterCheck()
	{
                if(is_array($this->visitorsProfile))
		foreach ($this->visitorsProfile as $key=>$profile) {
			$filtercheck = UserFilterCheck::getInstance($profile,$this->profile);
			if ($filtercheck->getFilteredContact($action = "VISIT"))
				unset($this->visitorsProfile[$key]);
		}
        }
        public function reverseFilterCheck()
	{
                if(is_array($this->visitorsProfile))
                foreach ($this->visitorsProfile as $key=>$profile) {
                    if($profile->getPRIVACY()=='F'){
			$filtercheck = UserFilterCheck::getInstance($this->profile,$profile);
			if ($filtercheck->getFilteredContact($action = "VISIT"))
				unset($this->visitorsProfile[$key]);
                        }
                    }
		}
	
	public function sortArray($returnProfiles)
	{
		if(is_array($returnProfiles))
		{
			foreach($returnProfiles as $key=>$value)
			{
				$arr[$value["TIME"]][] = $key;
			}
			krsort($arr);
			
			foreach($arr as $key=>$value)
			{
				foreach($value as $key1=>$value1)
				{
					$returnArray[$value1] = $returnProfiles[$value1];
				}
			}
		}
		return $returnArray;
	}
}
?>
