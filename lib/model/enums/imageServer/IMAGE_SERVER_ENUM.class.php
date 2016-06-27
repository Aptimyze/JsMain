<?php 
/* this  classs list all the server options on which picture can be uploaded  
*/
class IMAGE_SERVER_ENUM
{
	public static $appPicUrl   = "JS" ;
        public static $cloudUrl    = "CL" ;
	public static $cloudArchiveUrl ="AR";
	
	public static function getImageServerEnum($pid,$withSlash='')
	{
		if(JsConstants::$usePhotoDistributed)
		{
			
			$str= JsConstants::$photoServerShardingEnums[$pid%count(JsConstants::$photoServerShardingEnums)];	
			if($withSlash!='')
				$str = "/".$str;
			return $str;
		}
		return "";

	}
	
	public static function getDefaultEnum()
	{
		return JsConstants::$photoServerShardingEnums[0];
	}
}
?>
