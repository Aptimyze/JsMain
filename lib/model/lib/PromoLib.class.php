<?php

/*
	This Library is created for adding functions related to Promotions on the website. Just call the function showPromo() with the appropriate promo name 
	and add more checks in that function for further processing.
*/

class PromoLib
{
	private $baseDate = '2017-05-10 10:30:00';
	//Time in seconds for last 7 days. PHP version too old
	private $lastSevenDaysCheck = 604800;
	//Time in seconds for last 4 days. PHP version too old
	private $timeForPromo = 345600;

	public function showPromo($promoToBeShown,$profileId,$loginObj)
	{ 
		if($promoToBeShown == "chatPromo")
		{
			$valToReturn = self::ChatPromo($profileId,$loginObj);
			return $valToReturn;
		}

	}


	private function ChatPromo($profileId,$loginObj)
	{

		if($_COOKIE['DAY_CHECK_CHAT_PROMO'] == '1')
		{  
			return false;
		}

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
				if(strtotime(now) - strtotime($isUserEligible['DATE']) < $this->lastSevenDaysCheck)
					return false;
			}

		}		 

			$interval = strtotime(now) - strtotime($this->baseDate);
			
			if($interval > 0)
		{		
			if($interval < $this->timeForPromo)
			{  
				setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");
				return true;
			}
			else
			{
				$activatedOn = $loginObj->getVERIFY_ACTIVATED_DT();

				if((strtotime(now) - strtotime($activatedOn)) < $this->timeForPromo)
				{  
					setcookie('DAY_CHECK_CHAT_PROMO', '1', time() + 86400, "/");
					return true;
				}

				return false;
			}
		}
		
		return false;
	}




}