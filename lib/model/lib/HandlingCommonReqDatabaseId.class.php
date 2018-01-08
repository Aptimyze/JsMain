<?php
/*
* This class will generate the radom-id for database so that we can use that same id across 
*/
Class HandlingCommonReqDatabaseId
{
	public static $modulesWhereMasterMasterDone = array("search/matchalerts","/search/partnermatches","/search/justjoined","/api/v1/search/perform","/api/v1/notification","/api/v3/notification","/search/perform","/search/verifiedMatches","/search/twoway","/search/reverseDpp","/search/search","/search/ViewSim","/search/saveSearch","/search/Advanced","/search/advance","/search/populate","/search/MobSim","/search/visitor","/search/guna","/search/kund","/search/shortlisted","/search/topSearchBand","/profile/search.php","/search/quick","/search/lastSearchResults","/search/savedSearches","/search/mySaveSearchId","/api/hamburgerDetails","/common/engagementcount","/api/versionupgrade");

	public static function isMasterMasterDone(){
		foreach(self::$modulesWhereMasterMasterDone as $v){
			if(stristr($_SERVER["REQUEST_URI"],$v))
				return true;
		}
		return false;
	}


	/**
	* This function will set the dbId which will be used across request
	* @return $reqId int
	*/
	private static function setId()
	{
		$request=sfContext::getInstance()->getRequest();
		$reqId=  $request->getAttribute('CommonReqDatabaseId');
		if(!$reqId)
		{
			/*
			$reqId = $request->getcookie('CommonReqDatabaseId');
			if(!$reqId)
			{
				$reqId = rand(1,2);
				setcookie('CommonReqDatabaseId',$reqId,time() + 10000000000, "/");
			}
			*/
			$authChecksum=sfContext::getInstance()->getRequest()->getParameter("AUTHCHECKSUM");
			if(!$authChecksum)
				$authChecksum=$_COOKIE[AUTHCHECKSUM];
			$decryptObj= new Encrypt_Decrypt();
			$decryptedAuthChecksum=$decryptObj->decrypt($authChecksum);
			$obj = AuthenticationFactory::getAuthenicationObj(null);
			$loginData=$obj->fetchLoginData($decryptedAuthChecksum);
			$pid = $loginData["PROFILEID"];
			if($pid)
			{
				if($pid%4<2)
					$reqId = 1;
				else
					$reqId = 2;
			}
			if(!$reqId)
				$reqId = rand(1,2);
			$reqId=2;
		}
		$request->setAttribute('CommonReqDatabaseId',$reqId);
		return $reqId;
	}

	/**
	* This function will return the dbId
	* @return $reqId int
	*/
	public static function getId()
	{
		$request=sfContext::getInstance()->getRequest();
		$reqId=  $request->getAttribute('CommonReqDatabaseId');
		if(!$reqId)
		{
			self::setId();
			$reqId=  $request->getAttribute('CommonReqDatabaseId');
		}
		return $reqId;
	}


	/**
	* This function will return the dbname
	* @param  name string current db name used.
	* @return dbname string
	*/
	public static function mapIdToServer($name="")
	{
		if(!strstr($_SERVER["REQUEST_URI"],"api/v1/social/getAlbum") && !strstr($_SERVER["REQUEST_URI"],"api/v1/social/getMultiUserPhoto"))
		{
			if(self::isMasterMasterDone()===false)
				return $name;
		}
		$id = self::getId();
		switch($id)
		{
			case "1":
				switch($name)
				{
					case "newjs_master":
						return "newjs_master";
					case "newjs_masterRep":
						return "newjs_master";
					case "":
						return "newjs_master";
					default:
						return $name;
				}
				break;
			case "2":
				switch($name)
				{
					case "newjs_master":
						return "newjs_masterRep";
					case "newjs_masterRep":
						return "newjs_masterRep";
					case "":
						return "newjs_masterRep";
					default:
						return $name;
				}
				break;
			default:	
				return $name;
				break;
		}
	}
}
