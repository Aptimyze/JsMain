<?php
class AppProfilesHandler
{
	public static $res=array();
	public static $startPointer=array();
	public static $allProfilesDone=array();
	public static function getProfiles($notificationKey,$count, $restart = false,$noOfScripts,$currentScript,$osType='ALL')
	{
		if(self::$allProfilesDone[$notificationKey][$currentScript] && !$restart)
			return;
		if((!isset(self::$res[$notificationKey][$currentScript]))|| $restart)
		{
			$regIdObj = new MOBILE_API_REGISTRATION_ID('newjs_masterRep');
			if(array_key_exists($notificationKey, NotificationEnums::$appVersionCheck))
			{
				$appVersion = NotificationEnums::$appVersionCheck[$notificationKey];
			}
			else
			{
				$appVersion = NotificationEnums::$appVersionCheck["DEFAULT"];
			}
			if($osType=='AND' || $osType=='ALL')
				$appVersionAnd =$appVersion['AND'];
			if($osType=='IOS' || $osType=='ALL')
				$appVersionIos =$appVersion['IOS'];

			self::$res[$notificationKey][$currentScript] = $regIdObj->getResObj($noOfScripts,$currentScript,$appVersionAnd,$appVersionIos);
			self::$startPointer[$notificationKey][$currentScript] = 0;
			self::$allProfilesDone[$notificationKey][$currentScript] = false;
		}
		if(isset(self::$res[$notificationKey][$currentScript]))
		{
			for($i=0;$i<$count;$i++)
			{
				self::$startPointer[$notificationKey][$currentScript]+=$i;
				if($row = self::$res[$notificationKey][$currentScript]->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS,self::$startPointer[$notificationKey][$currentScript]))
					$detailArr[] = $row['PROFILEID'];
				else
				{
					self::$allProfilesDone[$notificationKey][$currentScript]=true;
					break;
				}
			}
			return $detailArr;
		}
	}
}
