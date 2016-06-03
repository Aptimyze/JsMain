<?php
/**
 * @class MobileCommonFunctions
 * Demarcates different functions of mobile site revamp for edit profile and detailed profile
  * */
class MobileCommonFunctions {
	
//*************************NEW FUNCTIONS************************
	public static function getInfo($string,$key)
	{
		$limit=sfConfig::get("app_info_limit_mob");
		if(strlen($string)>$limit)
		{
			$val=str_replace('"',"'",$string);
			$infr_arr=explode('|', wordwrap($val, $limit, '|'));
			if($infr_arr[0]){
				if (strrpos($infr_arr[0],'<b') !== false || strrpos($infr_arr[0],'<') !== false ){
					$key2=substr($infr_arr[0],0,strrpos($infr_arr[0],'<'));
				}
				else
					$key2=$infr_arr[0];
			}				
			$temp[$key."1"]=str_replace("\r","",str_replace("\n","",(nl2br($key2))));
		}
		else
		{
			$temp[$key."1"]="";
		}
		$temp[$key]=str_replace("\r","",str_replace("\n","",addslashes(($string))));
		if(is_array($temp))
			return $temp;
		else
			return $temp;
	}	
	
}
?>
