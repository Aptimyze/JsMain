<?php
class ProfileCacheFunctions 
{
	private function allowSetFromCommandLine($getOrset=""){
                if(ProfileCacheConstants::ALLOW_CLI_SET == 1 || $getOrset == "get"){
                        return true;
                }
                return false;
        }
	public static function isCommandLineScript($getOrset="")
	{
                //if($this->allowSetFromCommandLine($getOrset)){
                        return false;
                //}
		return (php_sapi_name() === ProfileCacheConstants::COMMAND_LINE);
	}
	public static function getDecoratedKey($key)
	{
		return ProfileCacheConstants::PROFILE_CACHE_PREFIX.trim($key);
	}
	public static function getRawKey($decoratedKey)
	{
		$prefix = ProfileCacheConstants::PROFILE_CACHE_PREFIX;
		if (substr($decoratedKey, 0, strlen($prefix)) == $prefix)
		{
			$profileId = substr($decoratedKey, strlen($prefix));
		}
		return $profileId;
	}
	public static function validateCriteria($szCriteria)
	{
		return (ProfileCacheConstants::CACHE_CRITERIA == strtoupper($szCriteria));
	}

	public static function getStorePrefix($storeName)
	{
		if($storeName && array_key_exists($storeName,ProfileCacheConstants::$prefixMapping))
		{
			return ProfileCacheConstants::$prefixMapping[$storeName];
		}else if($storeName == ""){
                        return ProfileCacheConstants::$prefixMapping;
                }
		return false;
	}

	public static function getRelevantKeysName($array,$prefix='',$suffix='',$prefixDelimiter='',$suffixDelimiter='')
        {
                if(is_array($array)&&($suffix||$prefix))
                {
                        foreach($array as $k=>$v)
                        {
                                $array[$k]=$prefix.$prefixDelimiter.$v.$suffixDelimiter.$suffix;
                        }
                }
                return $array;
        }
	public static function getRelevantKeysNameWithValues($array,$prefix='',$suffix='',$prefixDelimiter='',$suffixDelimiter='')
        {
                if(is_array($array)&&($suffix||$prefix))
                {
                        foreach($array as $k=>$v)
                        {
                                $array[$prefix.$prefixDelimiter.$k.$suffixDelimiter.$suffix]=$v;
                                unset($array[$k]);
                        }
                }
                return $array;
        }

	public static function getOriginalKeysNameWithValues($array,$prefix='',$suffix='',$prefixDelimiter='',$suffixDelimiter='')
        {
                if(!is_array($prefix)){
                        $pre = $prefix.$prefixDelimiter;
                }
                if(is_array($array)&&($prefix))
                {
                        foreach($array as $k=>$v)
                        {
                                if(strpos($k, $prefixDelimiter) !== false){
                                        $ky = explode($prefixDelimiter,$k);
                                        $ky = $ky[1];
                                        $array[$ky] = $v;
                                        unset($array[$k]);
                                }else{
                                        $ky = $k;
                                }
                                //print_r($ky);die;
                                /*if(is_array($prefix)){
                                        $pre = $prefix[$k].$prefixDelimiter;
                                }
                                
                                if(substr($k,0,strlen($pre))==$pre)
                                {
                                        $array[substr($k,strlen($pre))] = $v;
                                        unset($array[$k]);
                                }*/
                        }
                }
                //echo "dsfsfsd";print_r($array);die;
                $suf = $suffixDelimiter.$suffix;
                if(is_array($array)&&($suffix))
                {
                        foreach($array as $k=>$v)
                        {
                                if(substr($k,-strlen($suf))==$suf)
                                {
                                        $array[substr($k,0,(strlen($k)-strlen($suf)))] = $v;
					unset($array[$k]);
                                }
                        }
                }

                return $array;
        }

        public static function processArrayWhereClause(&$arrData,$arrExtraWhereClause)
        {
                if(!is_array($arrExtraWhereClause) || 0 === count($arrExtraWhereClause))
                        return;

                foreach ($arrExtraWhereClause as $col => $val)
                {
                        if($arrData[$col] != $val)
                        {
                                $arrData = false;
                                break;
                        }
                }
                return;
        }


	public static function logThis($enLogType,$Var)
	{
		if ((false === ProfileCacheConstants::ENABLE_PROFILE_CACHE_LOGS)||($enLogType > ProfileCacheConstants::LOG_LEVEL)) 
		{
			return false;
		}
		$logManager = LoggingManager::getInstance(ProfileCacheConstants::PROFILE_LOG_PATH);

		switch ($enLogType) 
		{
			case LoggingEnums::LOG_INFO:
				$logManager->logThis(LoggingEnums::LOG_INFO,$Var);
				break;
			case LoggingEnums::LOG_DEBUG:
				$logManager->logThis(LoggingEnums::LOG_DEBUG,$Var);
				break;
			case LoggingEnums::LOG_ERROR:
				$logManager->logThis(LoggingEnums::LOG_ERROR,$Var);
				break;
			default:
				break;
		}
		return true;
	}

	public static function calculateResourceUsages($st_Time='', $preMsg="",$postMessage="")
	{
		$end_time = microtime(TRUE);
		$var = memory_get_usage(true);
		if ($var < 1024)
			$mem =  $var." bytes";
		elseif ($var < 1048576)
			$mem =  round($var/1024,2)." kilobytes";
		else
			$mem = round($var/1048576,2)." megabytes";
		$timeTaken = $end_time - $st_Time;
		$usages = "{$preMsg} Memory usages : {$mem} & Time taken : {$timeTaken} {$postMessage}";
		ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, $usages);
	}
	public static function createNewTime()
	{
		return microtime(TRUE);
	}

	public static function getColumnArr($storeName)
	{
		if(!$storeName){
			foreach(ProfileCacheConstants::$storeKeys as $k=>$val){
                                $arrFields[$k] = array_merge(ProfileCacheConstants::$$val);
                        }
                        return $arrFields;
                }
                
		if($storeName == "JPROFILE") 
		{
			$arrFields = ProfileCacheConstants::$arrJProfileColumns;
		} 
		else if(false !== stristr($storeName, "EDUCATION")) 
		{
			$arrFields = ProfileCacheConstants::$arrJProfile_EducationColumns;
		}
		else if(false !== stristr($storeName, "NATIVE")) 
		{
			$arrFields = ProfileCacheConstants::$arrNativePlaceColumns;
		}
		else if(false !== stristr($storeName, "ASTRO")) 
		{
			$arrFields = ProfileCacheConstants::$arrAstroDetailsColumns;
		}
		elseif (false !== stristr($storeName, "Contact")) 
		{
			$arrFields = ProfileCacheConstants::$arrJProfileContact;
		}
		else if(false !== stristr($storeName, "HOBBY")) 
		{
			$arrFields = ProfileCacheConstants::$arrJHobbyColumns;
		}
		else if(false !== stristr($storeName, "Alerts")) 
		{
			$arrFields = ProfileCacheConstants::$arrJProfileAlertsColumn;
		}
		else if (false !== stristr($storeName, "YOUR_INFO_OLD") )
		{
			$arrFields = ProfileCacheConstants::$arrOldYourInfo;
		}
		else if(false !== stristr($storeName, "ProfileAUTO_EXPIRY")) 
		{
			$arrFields = ProfileCacheConstants::$arrAutoExpiry;
		}
		else if(false !== stristr($storeName, "FILTER")) 
		{
			$arrFields = ProfileCacheConstants::$arrProfileFilter;
		}
                else if(false !== stristr($storeName, "Jpartner")) 
		{
			$arrFields = ProfileCacheConstants::$arrJpartnerColumns;
		}
		return $arrFields;
	}
        public static function getFinalFieldsArrayWithPrefix($storeName,$fields){
                $allStoreFields =self::getColumnArr($storeName) ;
                $prefix = self::getStorePrefix($storeName);
                $demanField = explode(",",$fields);
                if($storeName == ""){
                        $demandedFields = array();
                        foreach($allStoreFields as $k=>$fieldArray){
                                $Flds = self::getColumnArr($k);
                                $intersect = array_intersect($demanField, $Flds);
                                if(count($intersect)>0){
                                        $demandedFields1 = self::getRelevantKeysName($intersect,$prefix[$k],'',ProfileCacheConstants::KEY_PREFIX_DELIMITER);
                                        $demandedFields = array_merge($demandedFields,$demandedFields1);
                                        $demanField = array_diff($demanField, $intersect);
                                        $fields = implode(",",$demanField);
                                        if(trim($fields) == ""){
                                                break;
                                        }
                                }
                        }
                }else{
                        $fields = self::getRelevantFields($fields, $storeName, $allStoreFields);
                        $demandedFields = self::getRelevantKeysName($fields,$prefix,'',ProfileCacheConstants::KEY_PREFIX_DELIMITER);
                }
                return array_unique($demandedFields);
        }
        /**
     * @param $arrFields
     * @return array
     */
	public static function getRelevantFields($arrFields, $storeName="",$allStoreFields='')
	{
//IN USE
		if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM && strlen($storeName)) 
		{
			$array = $allStoreFields;
		} 
		else if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM) 
		{
			$array = ProfileCacheFunctions::getAllSubKeys();
		} 
		else if (is_string($arrFields) && $arrFields != ProfileCacheConstants::ALL_FIELDS_SYM) 
		{
			$array = explode(',',$arrFields);
			foreach($array as $k=>$v)
			{
				$array[$k] = trim($v);
			}
			$array = array_intersect(ProfileCacheFunctions::getAllSubKeys(), $array);
		}
		if(is_array($arrFields) && count(array_diff(array_unique($arrFields),$array)))
		{
			self::logThis(LoggingEnums::LOG_INFO, "Relevant Field in not present in cache : ".print_r(array_diff(array_unique($arrFields),$array),true));
		}
        return $array;
    }
    public static function getPrefixedFieldForDbData($fieldsArray,$storeName=""){
            $prefix = self::getStorePrefix($storeName);
            $delim = ProfileCacheConstants::KEY_PREFIX_DELIMITER;
            if(is_array($prefix)){
            }else{
                    foreach($fieldsArray as $ky=>$value){
                            $fieldsArray[$prefix.$delim.$ky] = $value;
                            unset($fieldsArray[$ky]);
                    }
            }
            return($fieldsArray);
    }

    public static function setNotFilledArray($storeName,$profileId){
                $dummyResult = array();
                if($storeName != ""){
                        $dummyFields = self::getColumnArr($storeName) ;
                        foreach($dummyFields as $field){
                               $dummyResult[$field] = ProfileCacheConstants::NOT_FILLED;
                        }
                        $dummyResult["PROFILEID"] = $profileId;
                }
                return $dummyResult;
    }
    
    public static function getAllSubKeys(){
        return array_unique(array_merge(ProfileCacheConstants::$arrJProfileColumns,ProfileCacheConstants::$arrJProfileContact,ProfileCacheConstants::$arrJpartnerColumns,ProfileCacheConstants::$arrJProfile_EducationColumns,ProfileCacheConstants::$arrNativePlaceColumns,ProfileCacheConstants::$arrAstroDetailsColumns,ProfileCacheConstants::$arrFSOColumns,ProfileCacheConstants::$arrJProfileAlertsColumn,ProfileCacheConstants::$arrAUTO_EXPIRY,ProfileCacheConstants::$arrJHobbyColumns,ProfileCacheConstants::$arrOldYourInfo,ProfileCacheConstants::$arrAutoExpiry,ProfileCacheConstants::$arrProfileFilter,ProfileCacheConstants::$arrAadharVerifyColumns));
    }

}
