<?php


class newjs_JPARTNER
{
        
        private $cacheClassJpartner;
        public function __construct($dbname="")
        {
                $this->JpartnerStoreObj = new newjsShard_JPARTNER($dbname);
                $this->cacheClassJpartner = "Jpartner";
        }

        /*
        * This Function is to udpate partner prefence into the table.
        * @param updateArr key-value pair for records to be updated.
        */
	public function addRecords($updateArr)
	{
                if($updateArr['PROFILEID'])
                    ProfileCacheLib::getInstance()->removeFieldsFromCache($updateArr['PROFILEID'],$this->cacheClassJpartner,"*");
                $this->JpartnerStoreObj->addRecords($updateArr);
	}

        /**
        This function is used to get dpp information (JPARTNER table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array dpp search paramters info. Return null in case of no matching rows found.
        **/
	public function get($paramArr=array(),$fields="*")
	{
                //this function removes As Clause of Mysql
                $arr = self::removeAsAndReturnFields($fields);
                
                $fields = $arr[0];
                //this array is to replace keys with those of AS clause
                $changeAtReturn = $arr[1];
                
                if($paramArr['PROFILEID']){
                    if(ProfileCacheLib::getInstance()->isCached(ProfileCacheConstants::CACHE_CRITERIA, $paramArr['PROFILEID'], $fields, $this->cacheClassJpartner)){
                        $result = ProfileCacheLib::getInstance()->get(ProfileCacheConstants::CACHE_CRITERIA, $paramArr['PROFILEID'], $fields, $this->cacheClassJpartner);
                        if (false !== $result) {
                            $myrow[0] = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
                        }
                    }
                    else{
                        $myrow =  $this->JpartnerStoreObj->get($paramArr,"*");
                        //Also Set in cache
                        ProfileCacheLib::getInstance()->updateCache($myrow, ProfileCacheConstants::CACHE_CRITERIA, $this->PROFILEID, $this->cacheClassJpartner);
                        
                        //This function filters only those keys which are asked for
                        $myrow[0] = self::getArrayWithRequiredFieldAndConditions($myrow[0],$fields);
                    }
                }
                
                //replacing keys of AS clause
                $myrow[0] = self::getChangedKeysArr($myrow[0],$changeAtReturn);
                
                return $myrow;
	}

	public function getDataForMultipleProfiles($profileIdArr,$fields="*")
	{
                $arr = self::removeAsAndReturnFields($fields);
                
                $fields = $arr[0];
                
                //this array is to replace keys with those of AS clause
                $changeAtReturn = $arr[1];
                $objProCacheLib = ProfileCacheLib::getInstance();
                $result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $profileIdArr,$fields,$this->cacheClassJpartner);
                if (is_array($result) && false !== $result) {
                    $bServedFromCache = true;
                    $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
                    foreach($result as $k=>$out){
                        if(in_array(ProfileCacheConstants::NOT_FILLED, $out)) {
                                unset($result[$k]);
                        }
                    }
                }
                
                if(is_array($result) && count($result)) {
                    $tempResult = array();
                    foreach($result as $k=>$v){
                        $tempResult[$v['PROFILEID']] = $v;
                        unset($result[$k]);
                    }
                    $result = $tempResult;
                }
                
                if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
                    print_r($result);die;
                    return $result;
                }
                
                //Get Records from Mysql
                $result = $this->JpartnerStoreObj->getDataForMultipleProfiles($profileIdArr,"*");  
                
                if(is_array($result) && count($result) !== count($profileIdArr)) {
                    $arrDataNotExist = array();
                    foreach($result as $key=>$val){
                        $arrDataNotExist[] = $val['PROFILEID'];
                    }
                    $arrDataNotExist = array_diff($profileIdArr, $arrDataNotExist);
                    if(!empty($arrDataNotExist)){
                        $dummyArray = array();
                        foreach($arrDataNotExist as $k => $v){
                                $data =  ProfileCacheFunctions::setNotFilledArray($this->cacheClassJpartner, $v);
                                $dummyArray[] = $data;
                        }
                    }
                }

                if(is_array($result) && count($result) && false === ProfileCacheFunctions::isCommandLineScript("set")) {
                    $objProCacheLib->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $result, $this->cacheClassJpartner);
                }

                if($dummyArray && is_array($dummyArray) && count($dummyArray) && false === ProfileCacheFunctions::isCommandLineScript("set")) {
                    $objProCacheLib->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $dummyArray, $this->cacheClassJpartner);
                }
                
                foreach($result as $key=>$val){
                    $result[$key] = self::getArrayWithRequiredFieldAndConditions($result[$key],$fields,"");
                    //replacing keys of AS clause
                    $result[$key] = self::getChangedKeysArr($result[$key],$changeAtReturn);
                }
                print_r($result);die;
                return $result;
	}
	public function getCount($where,$profileid)
	{
                $this->JpartnerStoreObj->getCount($where,$profileid);
	}
	public function UpdatePage5($partnerObj)
	{
                if($partnerObj->getPROFILEID())
                    ProfileCacheLib::getInstance()->removeFieldsFromCache($partnerObj->getPROFILEID(),$this->cacheClassJpartner,"*");
                $this->JpartnerStoreObj->UpdatePage5($partnerObj);
	}
	
	public function isDppSetByUser($profileId){
                if($paramArr['PROFILEID']){
                    if(ProfileCacheLib::getInstance()->isCached(ProfileCacheConstants::CACHE_CRITERIA, $profileId, ProfileCacheConstants::$prefixMapping['Jpartner'].'.DPP', $this->cacheClassJpartner)){
                        $result = ProfileCacheLib::getInstance()->get(ProfileCacheConstants::CACHE_CRITERIA, $profileId, ProfileCacheConstants::$prefixMapping['Jpartner'].'.DPP', $this->cacheClassJpartner);
                        if (false !== $result) {
                            $myrow = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
                        }
                    }
                    return $myrow['DPP'];
                }
                else{
                    return $this->JpartnerStoreObj->isDppSetByUser($profileId);
                }
	}
	public function selectPartnerCaste($p_caste,$offset,$limit)
	{
                $this->JpartnerStoreObj->selectPartnerCaste($p_caste,$offset,$limit);
	}
        
        
	public function updateCaste($profileid,$caste,$oldCaste)
	{
                $this->JpartnerStoreObj->updateIncomeValueForProfile($profileid,$hincome,$partnerIncome,$oldValue);
                
                //update in cache
                $arrToUpdateInCache['PARTNER_CASTE'] = $caste;
                ProfileCacheLib::getInstance()->updateCache($arrToUpdateInCache, ProfileCacheConstants::CACHE_CRITERIA, $profileid, $this->cacheClassJpartner);
	}	

	public function getDppDataForProfiles($limit,$offset,$whereCheck)
	{
                $this->JpartnerStoreObj->getDppDataForProfiles($limit,$offset,$whereCheck);
	}

	public function updateIncomeValueForProfile($profileId,$hincome,$partnerIncome,$oldValue)
	{
                $this->JpartnerStoreObj->updateIncomeValueForProfile($profileId,$hincome,$partnerIncome,$oldValue);
                
                //update in cache
                $arrToUpdateInCache['HINCOME'] = $hincome;
                $arrToUpdateInCache['PARTNER_INCOME'] = $partnerIncome;
                ProfileCacheLib::getInstance()->updateCache($arrToUpdateInCache, ProfileCacheConstants::CACHE_CRITERIA, $profileId, $this->cacheClassJpartner);
	}
	public function updateIncomeDollarValueForProfile($profileId,$lincomeDol,$partnerIncome,$oldValue)
	{
                
                $this->JpartnerStoreObj->updateIncomeDollarValueForProfile($profileId,$lincomeDol,$partnerIncome,$oldValue);
                
                //update in cache
                $arrToUpdateInCache['LINCOME_DOL'] = $lincomeDol;
                $arrToUpdateInCache['PARTNER_INCOME'] = $partnerIncome;
                ProfileCacheLib::getInstance()->updateCache($arrToUpdateInCache, ProfileCacheConstants::CACHE_CRITERIA, $profileId, $this->cacheClassJpartner);
	}
        
        public static function getArrayWithRequiredFieldAndConditions($completeArr,$paramsToFetch,$whereParams=''){
            $whereConditionTrue = 1;
            if($whereParams){
                $whereParamsArr = explode("AND",$whereParams);
                foreach($whereParamsArr as $key=>$val){
                    $condition = explode("=",$val);
                    if($completeArr[$condition[0]] != $condition[1])
                        $whereCondTrue = 0;
                }
            }
            if($whereConditionTrue){
                if($paramsToFetch != "*"){
                    $paramsArr = explode(",", $paramsToFetch);
                    foreach($paramsArr as $key=>$val){
                        $toReturn[$val] = $completeArr[$val];
                    }
                }
                else
                    $toReturn = $completeArr;
            }
            return $toReturn;
        }
        
        public static function removeAsAndReturnFields($fields){
            if(strstr($fields, " AS ")){
                $cArray = explode(",", $fields);
                foreach($cArray as $key=>$val){
                    if(strstr($val, " AS ")){
                        $asSeparate = explode(" AS ",$val);
                        $changeAtReturn[trim($asSeparate[0])] = trim($asSeparate[1]);
                        $fieldsNew .= ",".trim($asSeparate[0]);
                    }
                    else
                        $fieldsNew .= ",".trim($val);
                }
            }
            $retArr[0] = trim($fieldsNew,",");
            $retArr[1] = $changeAtReturn;
            return $retArr;
        }
        
        public static function getChangedKeysArr($completeArr,$fieldsToChange){
            foreach($fieldsToChange as $key=>$val){
                $completeArr[$val] = $completeArr[$key];
                unset($completeArr[$key]);
            }
            return $completeArr;
        }
}

