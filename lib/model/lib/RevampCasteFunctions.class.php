<?php
/**
This class has all the functions related to caste revamp.
**/
class RevampCasteFunctions
{
	private $casteGroupArray;

	public function __construct()
	{
		$this->casteGroupArray = FieldMap::getFieldLabel("caste_group_array",1,1);
		$this->casteGroupsFromCasteArray = FieldMap::getFieldLabel("castegroup_from_caste_array",1,1);
	}

	//$output_param = 1 means return array 
	//$output_param = 0 means return string
	public function getAllCastes($caste,$output_param="")
	{
		if (is_array($caste))
			$caste_values = implode("','",$caste);
		elseif(strstr($caste,",") &&  !strstr($caste,"','"))
			$caste_values = str_replace(",","','",$caste);
		elseif($caste)
			$caste_values = $caste;	
		else
			return NULL;
	
		$caste_values = trim($caste_values,"'");
		$caste_values = trim($caste_values,"\"");

		$caste_values = "'".$caste_values."'";

		$casteObj = new NEWJS_CASTE;
		$output = $casteObj->getAllData($caste_values);	
		
		if (is_array($output))
        	foreach($output as $k=>$v)
        	{
						
                	if($v["ISALL"]=="Y")
                	{
				$totalCaste = $casteObj->getCastesOfParent($v["PARENT"]);

				if($totalCaste && is_array($totalCaste))
				{
                        		foreach($totalCaste as $v)
                                		$casteArr[]=$v;
				}
                	}
                	elseif($v["ISGROUP"]=="Y")
                	{
				$casteStr = $this->casteGroupArray[$k];
				if($casteStr)
					$totalCaste = explode(",",$casteStr);

				if($totalCaste && is_array($totalCaste))
				{
                        		foreach($totalCaste as $val)
                        		{
                                		$casteArr[]=$val;
                                		if(array_key_exists($val,$this->casteGroupArray))
																		{
																			$casteStrTemp = $this->casteGroupArray[$val];
																			$casteArr = array_merge($casteArr,explode(",",$casteStrTemp));
																		}
																		
                             }
				}
                	}
                	else
                        	$casteArr[]=$k;
        	}
        	
					
        	if($casteArr && is_array($casteArr))
                	$output =  array_unique($casteArr);
		else
			$output = "";

		if($output_param)
		{
			return $output;
		}
		else
		{
			if ($output && is_array($output))
				return implode("','",$output);
			else
				return "";
		}
	}

	//This function is used to check if a particular caste belongs to any group
	public function isPartOfGroup($caste)
	{
		foreach ($this->casteGroupArray as $k=>$v)
		{
			$casteArr = explode(",",$v);
			foreach ($casteArr as $kk=>$vv)
			{
				if ($vv == $caste || $k == $caste)
					return 1;
			}
		}
		return 0;
	}

	//This function is used to find all possible parents (Group/Religion) of a particular caste
	function getCasteParent($caste)
	{
		$casteObj = new NEWJS_CASTE;
                $output = $casteObj->getAllData($caste);

		foreach($output as $k=>$v)
		{
                	if($v["ISALL"]=='Y')
                	{
                        	//print "I am a Religion"; 
                        	$castes[] = $caste;
                	}
                	else if($v["ISGROUP"]=='Y')
                	{
                        	//print "I am a Group";
				$parentCaste = $casteObj->getCastesOfParent($v["PARENT"]);
                                $castes[] = $caste;
                                $castes[] = $parentCaste;
                	}
                	else
                	{
                        	//print "I am a Sub Group";
				$parentCaste = $casteObj->getCastesOfParent($v["PARENT"]);
                                $castes[] = $caste;
                                $castes[] = $parentCaste;
			
				foreach ($this->casteGroupArray as $k=>$v)
                		{
                        		$casteArr = explode(",",$v);
                        		foreach ($casteArr as $kk=>$vv)
                        		{
                                		if ($vv == $caste || $k == $caste)
                                		{
                                        		$castes[] = $k;
                                        		break;
                                		}
                        		}
				}
				$castes = array_unique($castes);
                	}
		}
        	return $castes;
	}

	//This function returns all members of a group to which the given caste belongs.
	public function showGroupMembers($caste)
	{
		foreach ($this->casteGroupArray as $k=>$v)
		{
			$casteArr = explode(",",$v);
			foreach ($casteArr as $kk=>$vv)
			{
				if ($vv == $caste || $k == $caste)
				{
					$groupArr[] = $v;
					break;
				}
			}
		}
		if (count($groupArr))
		{
			$str = implode(",",$groupArr);
			$tempArr = explode(",",$str);
			sort($tempArr);
			$tempArr = array_unique($tempArr);
			return implode(",",$tempArr);
		}
		else
			return 0;
	}

	/**
	* This function check if two caste are same or if thet have common parent.
	*/
	public function sameGrpOrCaste($caste1,$caste2)
	{
		if($caste1 && $caste2)
		{
			if($caste1==$caste2)
				return 1;
			$str1 = $this->casteGroupsFromCasteArray[$caste1];
			if($str1)
				$arr1 = explode(",",$str1);
			else
				return 0;

			$str2 = $this->casteGroupsFromCasteArray[$caste2];
			if($str2)
				$arr2 = explode(",",$str2);
			else
				return 0;
			$inter = array_intersect($arr1,$arr2);
			if(is_array($inter) && $inter[0]!='')
				return 1;
		}
		return NULL;
	}

	/*This function check if all castes belong to same parent
	*@param casteString : string of comma separated caste values
	*/
	public function getParentIfSingle($casteString)
	{
		$casteObj = new NEWJS_CASTE(SearchConfig::getSearchDb());
                $output = $casteObj->getParentIfSingle($casteString);
		return $output;
	}

}
?>
