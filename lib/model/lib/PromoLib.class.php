<?php

class PromoLib
{
	public static $baseDate = '2017-02-1 0:0:0';
	public static $daysToShowPromo = 4;
	
	public static function showPromo($promoToBeShown,$profileId,$loginObj)
	{ 
		if($promoToBeShown == "chatPromo")
		{
			$valToReturn = self::ChatPromo($profileId,$loginObj);
			return $valToReturn;
		}

	}


	private function ChatPromo($profileId,$loginObj)
	{

		if($loginObj->getACTIVATED() == 'U')
			return false;

		$str = ($_SERVER['HTTP_USER_AGENT']);
		(preg_match_all('/Android ([0-9])(\.[0-9]){1}/', $str, $matches));
		if($matches != NULL && $matches[0][0] != NULL)
		$val = explode(' ', $matches[0][0]);

		if($val != NULL && $val[1] < 4)
			return false;
		  
		$obj = new MOBILE_API_APP_LOGIN_PROFILES();

		$isUserEligible = $obj->ifUserIsEligible($profileId);
		
		if($isUserEligible != false)
		{
			if($isUserEligible['APP_TYPE'] == "I")
				return false;
			else if($isUserEligible['APP_TYPE'] == "A")
			{
				if(strtotime(now) - strtotime($isUserEligible['DATE']) < 3600*24*7)
					return false;
			}

		}

		if($_COOKIE['DAY_CHECK_CHAT_PROMO'] == '1')
		{  
			return false;
		}		
		else
		{  
			
			$interval = strtotime(now) - strtotime(self::$baseDate);

			if($interval < 24*4*3600)
			{  
				setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");
				return true;
			}
			else
			{
				$activatedOn = $loginObj->getVERIFY_ACTIVATED_DT();

				if((strtotime(now) - strtotime($activatedOn)) < 3600*24*self::$daysToShowPromo)
				{  
					setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");
					return true;
				}

				return false;
			}
		}
		
		
	}




}