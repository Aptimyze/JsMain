<?php

class PromoLib
{
	
	public static function showPromo($promoToBeShown,$channel,$profileId,$loginObj)
	{
		if($promoToBeShown == "chatPromo")
		{
			$this->ChatPromo($channel,$profileId,$loginObj);
		}

	}


	private function ChatPromo($channel,$profileId,$loginObj)
	{
		$baseDate = '2017-04-28';
		$obj = new MOBILE_API_APP_LOGIN_PROFILES();

		$isIOSUser = $obj->ifIOSUser($profileId);

		if($isIOSUser)
			return;

		$loggedInLast7daysAndroid = $obj->loggedInAndroidLastNDays($profileId,7);
		if($loggedInLast7daysAndroid)
			return;

		
		//$isKeySet = $cachObj->keyExist('CHAT_PROMO_ANDROID_'.$profileId);

		if($_COOKIE['CHAT_PROMO_ANDROID'] == '1')
		{
			if($_COOKIE['DAY_CHECK_CHAT_PROMO'] == '1')
			{
				return;
			}
			else
			{
				setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");

				if($channel == "JSPC")
				$this->forward("promotions","ChatPromoJSPC");
				else if($channel == "JSMS")
				$this->forward("promotions","ChatPromoJSMS");
			}
		}
		else
		{
			$date1 = new DateTime($baseDate);
			$date2 = new DateTime();
			$interval = $date1->diff($date2);

			if($interval->h <= 96)
			{
				setcookie('CHAT_PROMO_ANDROID_', '1', time() + 3600*$interval, "/");
				setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");

				if($channel == "JSPC")
				$this->forward("promotions","ChatPromoJSPC");
				else if($channel == "JSMS")
				$this->forward("promotions","ChatPromoJSMS");
			}
			else
			{
				$activatedOn = $loginObj->getVERIFY_ACTIVATED_DT();
				if((strtotime(now) - strtotime($activatedOn)) < 86400)
				{
					$daysToShowPromo = 4;
					setcookie('CHAT_PROMO_ANDROID_', '1', time() + 3600*$daysToShowPromo, "/");
					setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");

				if($channel == "JSPC")
				$this->forward("promotions","ChatPromoJSPC");
				else if($channel == "JSMS")
				$this->forward("promotions","ChatPromoJSMS");

				}


			}
		}
		
	}




}