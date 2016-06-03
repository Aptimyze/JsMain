<?php
class JprofileParamsAllowed
{
	public static $GENDER = array("M","F");
	public static $ACTIVATED = array("Y","N","H","D","U");
	public static $INCOMPLETE = array("Y","N");
	public static $SUBSCRIPTION = array("D","F","R","B","T","I","A","L");
	public static function __callStatic($method,$params)
	{
		$varName = strtoupper($method);
		if(!isset(JprofileParamsAllowed::$$varName))
			return false;
		switch($varName)
		{
			case SUBSCRIPTION:
				if(is_array($params[0]))
				{
					foreach($params[0] as $x=>$y)
					{
						$paramArr = explode(",",$y);
						foreach($paramArr as $k=>$v)
						{
							if(!in_array($v,JprofileParamsAllowed::$$varName))
								return false;
						}
					}
				}
				return true;
			default:
				return in_array($params[0],JprofileParamsAllowed::$$varName);
				break;
		}
	}
	public static function getSubscriptionFromPaid($paid)
	{
		if($paid=="evalue")
			return array("F,D","D,F","D");
		elseif($paid=="erishta")
			return array("F");
		else
			return;
	}
}
