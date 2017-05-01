<?php

class PromoLib
{
	public static $baseDate = '2017-04-28';
	public static $daysToShowPromo = 4;
	
	public static function showPromo($promoToBeShown,$profileId,$loginObj)
	{ 
		if($promoToBeShown == "chatPromo")
		{
			self::ChatPromo($profileId,$loginObj);
		}

	}


	private function ChatPromo($profileId,$loginObj)
	{

		if($loginObj->getACTIVATED() == 'U')
			return false;

		$str = ($_SERVER['HTTP_USER_AGENT']);
		(preg_match_all('/Android ([0-9])(\.[0-9]){1}/', $str, $matches));
		$val = explode(' ', $matches[0][0]);

		if($val[1] < 4)
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
			$date1 = new DateTime(self::$baseDate);
			$date2 = new DateTime();
			$interval = $date1->diff($date2);

			if($interval->h <= 96)
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