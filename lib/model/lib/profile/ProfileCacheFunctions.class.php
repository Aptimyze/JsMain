<?php
class ProfileCacheFunctions 
{
	
	public static function isCommandLineScript()
	{
		return false;
		return (php_sapi_name() === ProfileCacheConstants::COMMAND_LINE);
	}
	public static function getDecoratedKey($key)
	{
		return ProfileCacheConstants::PROFILE_CACHE_PREFIX.trim($key);
	}
	private function getRawKey($decoratedKey)
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
                                if(is_array($prefix)){
                                        $pre = $prefix[$k].$prefixDelimiter;
                                }
                                if(substr($k,0,strlen($pre))==$pre)
                                {
                                        $array[substr($k,strlen($pre))] = $v;
                                        unset($array[$k]);
                                }
                        }
                }
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
		return $arrFields;
	}
        public static function getFinalFieldsArrayWithPrefix($storeName,$fields){
//                print_r($fields);die;
//                echo $storeName;die;
                $allStoreFields =self::getColumnArr($storeName) ;
                $prefix = self::getStorePrefix($storeName);
                if($storeName == ""){
                        $demandedFields = array();
                        foreach($allStoreFields as $k=>$fieldArray){
                                $Fld = self::getRelevantFields($fields, $k, $fieldArray);
                                $demandedFields1 = self::getRelevantKeysName($Fld,$prefix[$k],'',ProfileCacheConstants::KEY_PREFIX_DELIMITER);
                                $demandedFields = array_merge($demandedFields,$demandedFields1);
                        }
                }else{
                        $fields = self::getRelevantFields($fields, $storeName, $allStoreFields);
                        $demandedFields = self::getRelevantKeysName($fields,$prefix,'',ProfileCacheConstants::KEY_PREFIX_DELIMITER);
                }
                return $demandedFields;
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
			$array = ProfileCacheConstants::$arrHashSubKeys;
		} 
		else if (is_string($arrFields) && $arrFields != ProfileCacheConstants::ALL_FIELDS_SYM) 
		{
			$array = explode(',',$arrFields);
			foreach($array as $k=>$v)
			{
				$array[$k] = trim($v);
			}
			$array = array_intersect(ProfileCacheConstants::$arrHashSubKeys, $array);
		}
		if(count(array_diff(array_unique($arrFields),$array)))
		{
			self::logThis(LoggingEnums::LOG_INFO, "Relevant Field in not present in cache : ".print_r(array_diff(array_unique($arrFields),$array),true));
		}
        return $array;
    }

}
