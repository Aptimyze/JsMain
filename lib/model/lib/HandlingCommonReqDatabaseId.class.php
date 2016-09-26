<?
/*
* This class will generate the radom-id for database so that we can use that same id across 
*/
Class HandlingCommonReqDatabaseId
{
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
			$decryptObj= new Encrypt_Decrypt();
			$decryptedAuthChecksum=$decryptObj->decrypt($authChecksum);
			$obj = AuthenticationFactory::getAuthenicationObj(null);
			$loginData=$obj->fetchLoginData($decryptedAuthChecksum);
			$reqId = $loginData["SQL_CONNECTION"];
			if(!$reqId)
				$reqId = rand(1,2);
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
