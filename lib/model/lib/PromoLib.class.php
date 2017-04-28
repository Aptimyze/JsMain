<?php

class PromoLib
{
	public static $baseDate = '2017-04-28';
	public static $daysToShowPromo = 4;
	
	public static function showPromo($promoToBeShown,$profileId,$loginObj)
	{ return true;
		if($promoToBeShown == "chatPromo")
		{
			self::ChatPromo($profileId,$loginObj);
		}

	}


	private function ChatPromo($profileId,$loginObj)
	{

		$obj = new MOBILE_API_APP_LOGIN_PROFILES();

		$isUserEligible = $obj->ifUserIsEligible($profileId);
		//var_dump($isUserEligible); die('asas');
		if($isUserEligible != false)
		{
			if($isUserEligible['APP_TYPE'] == "I")
				return false;
			else if($isUserEligible['APP_TYPE'] == "A")
			{
				if(strtotime(now) - strtotime(self::$baseDate) < 3600*24*7)
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