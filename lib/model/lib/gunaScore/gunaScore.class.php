<?php
/*
 * Class gunaScore 
 * This class verifies conditions and calls the third party api to fetch guna scores 
 * and accordingly returns the gunaScoreArr with key as profilechecksum and value as * guna score
 */

class gunaScore
{		
		/*This function is called by the gunaScoreApi and it verifies conditions and 
		 * and accordingly fetches and returns the gunaScoreArr  
		 */

        public function getGunaScore($profileId,$caste,$profilechecksumArr,$gender,$haveProfileArr='')
        {	$parentValueArr = gunaScoreConstants::$parentValues;
        	$searchIdArr = array();
        	$profilechecksumArr = explode(",",$profilechecksumArr);
        	//To convert profilechecksum to profileId array
        	foreach($profilechecksumArr as $val)
        	{
        		$profileid = ($haveProfileArr=='1')?$val:JsCommon::getProfileFromChecksum($val);
        		$searchIdArr[] = $profileid;
        		$flipIdArr[$val] = $profileid;
        	}
        	//FlippedSearchIdArr used to map gunaScore to profilechecksum
        	$this->flippedSearchIdArr = array_flip($flipIdArr);
					
        	$parent = $this->getParent($caste);
        	if(in_array($parent, $parentValueArr))
        	{
        			$astroDetails = $this->getAstroDetailsForIds($profileId,$searchIdArr);
        			//uses $artroDetails data to compile $logged_astro_details and compstring[]
        			if(is_array($astroDetails))
        			{
        				foreach($astroDetails as $key=>$myAstroData)
        				{
        					$astro_pid1=$myAstroData["PROFILEID"];
        					if($astro_pid1)
        					{
        						$lagna=$myAstroData['LAGNA_DEGREES_FULL'];
        						$sun=$myAstroData['SUN_DEGREES_FULL'];
        						$mo=$myAstroData['MOON_DEGREES_FULL'];
        						$ma=$myAstroData['MARS_DEGREES_FULL'];
        						$me=$myAstroData['MERCURY_DEGREES_FULL'];
        						$ju=$myAstroData['JUPITER_DEGREES_FULL'];
        						$ve=$myAstroData['VENUS_DEGREES_FULL'];
        						$sa=$myAstroData['SATURN_DEGREES_FULL'];
        						if($gender=="M")
        						{
        							$loggedInGender = gunaScoreConstants::MALE_VALUE;
        							$otherGender = gunaScoreConstants::FEMALE_VALUE;
        						}
        						else
        						{
        								$loggedInGender = gunaScoreConstants::FEMALE_VALUE;
        								$otherGender = gunaScoreConstants::MALE_VALUE;
        						}
        						if($astro_pid1 == $profileId)
        						{
        							$logged_astro_details="$astro_pid1:$loggedInGender:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
        						}
        						else
        						{
        							$compstring[]="$astro_pid1:$otherGender:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa@";
        						}
        					}
        				}
        			}
        			if($logged_astro_details && is_array($compstring))
                                {	
                                        $compstringAlteredArr = array();
                                        if(sizeof($compstring)>=gunaScoreConstants::BATCH_NO)
                                        {
                                               $compstringAlteredArr = array_chunk($compstring, gunaScoreConstants::BATCH_NO);
                                        }
                                        if(!empty($compstringAlteredArr))
                                        {
                                                $gunaData_1 = $this->thirdPartyVendorCall($logged_astro_details,$compstringAlteredArr[0]);
                                                $gunaData_2 = $this->thirdPartyVendorCall($logged_astro_details,$compstringAlteredArr[1]);
                                                $gunaData = array_merge($gunaData_1,$gunaData_2);
                                                return $gunaData;
                                        }
                                        else
                                        {
                                                $gunaData = $this->thirdPartyVendorCall($logged_astro_details,$compstring);
                                                return ($gunaData);
                                        }
                                        
                                }
                                else
                                {
                                  return;
                          }
        	}
        	else
        	{
        		return;
        	}
        }

        //This function uses caste and calls NEWJS_CASTE to fetch the Parent
        public function getParent($caste)
        {
        	$casteObj = new NEWJS_CASTE();
        	$parent = $casteObj->getParentIfSingle($caste);
        	unset($casteObj);
        	return $parent;
        }

        //This function uses loggedin user profileId and $searchIdArr to call NEWJS_ASTRO to fetch astroDetails
        public function getAstroDetailsForIds($profileId,$searchIdArr)
        {
        	$searchIdArr[]=$profileId;
        	$newjsAstroObj = ProfileAstro::getInstance(SearchConfig::getSearchDb());
			$astroData=$newjsAstroObj->getAstroDetails($searchIdArr,"PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL",1);
			unset($newjsAstroObj);
			return $astroData;
        }

        //This is a third party vendor call where a curl request is made to fetch guna scores corresponding to the data supplied which is then converted into desired form and returned in $gunaData
        public function thirdPartyVendorCall($logged_astro_details,$compstring)
        {	
					$gunaData = array();
        	$compstring = implode(",",$compstring);
        	$url = "https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?".$logged_astro_details."&".$compstring;
        	$fresult = CommonUtility::sendCurlGetRequest($url,4000);
		if($fresult)
		{
	        	$fresult = explode(",",substr($fresult,(strpos($fresult,"<br/>")+5)));
		}
		if(is_array($fresult))
		{
			foreach($fresult as $key=>$val)
			{
				$subject = $val;
				$guna_pid = strstr($val,':',true);
				$pattern = gunaScoreConstants::PATTERN;
				preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
				$matches[1][0]=intval($matches[1][0]);
				foreach($this->flippedSearchIdArr as $pid=>$profchecksum)
				{
					if($guna_pid == $pid)
					{
						$gunaData[$key][$profchecksum]=$matches[1][0];
					}
				}
			}
		}
        	return($gunaData);
        }
}
