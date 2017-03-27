<?php
include_once("CrawlerClassesCommon.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class CrawlerPriorityCommunity
{
	private $communityId;
	private $gender;
	private $lage;
	private $hage;
	private $countryRes;
	private $mtongue;
	private $lheight;
	private $hheight;
	private $mstatus;
	private $religion;
	private $toBeSearched;

	public static function getCommunityIdFromSearchId($searchIdArr)
	{
		if(is_array($searchIdArr) && count($searchIdArr))
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="SELECT COMMUNITY_ID,SEARCH_ID FROM crawler.crawler_search_history WHERE SEARCH_ID IN('".implode("','",$searchIdArr)."')";
			$res=$mysqlObj->executeQuery($sql,$db);
			while($row=$mysqlObj->fetchAssoc($res))
				$communityIdArr[$row["SEARCH_ID"]]=$row["COMMUNITY_ID"];
			unset($mysqlObj);
			return $communityIdArr;
		}	
	}

	public static function getCommunitiesFromId($idArr)
	{
		if(is_array($idArr) && count($idArr))
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="SELECT * FROM crawler.crawler_priority_communities WHERE COMMUNITY_ID IN('".implode("','",$idArr)."')";
			$res=$mysqlObj->executeQuery($sql,$db);
			while($row=$mysqlObj->fetchAssoc($res))
				$communityArr[$row["COMMUNITY_ID"]]=new CrawlerPriorityCommunity($row);
			unset($mysqlObj);
			return $communityArr;
		}
	}

	public static function getCommunitiesForCrawlingSearch($siteId)
	{
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('crawler');
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
		$sql="SELECT * FROM crawler.crawler_priority_communities WHERE TO_BE_SEARCHED='Y' AND SITE_ID = $siteId ";
		$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching priority communities to be searched    ".mysql_error()));
		if($mysqlObj->numRows($res))
		{
			while($row=$mysqlObj->fetchAssoc($res))
			{
				$communityArr[]=new CrawlerPriorityCommunity($row);
			}			
			return $communityArr;
		}
		unset($mysqlObj);
	}

	public function CrawlerPriorityCommunity($parameters)
	{
		$this->communityId=$parameters["COMMUNITY_ID"];
		$this->gender=$parameters["GENDER"];
		$this->lage=$parameters["LAGE"];
		$this->hage=$parameters["HAGE"];
		$this->countryRes=$parameters["COUNTRY_RES"];
		$this->mtongue=$parameters["MTONGUE"];
		$this->lheight=$parameters["LHEIGHT"];
		$this->hheight=$parameters["HHEIGHT"];
		$this->mstatus=$parameters["MSTATUS"];
		$this->religion=$parameters["RELIGION"];
		$this->toBeSearched=$parameters["TO_BE_SEARCHED"];
	}

	public function getGENDER()
	{
		return $this->gender;
	}

	public function getCOMMUNITY_ID()
	{
		return $this->communityId;
	}

	public function getLAGE()
	{
		return $this->lage;
	}

	public function getHAGE()
	{
		return $this->hage;
	}

	public function getCOUNTRY_RES()
	{
		return $this->countryRes;
	}
	
	public function getMTONGUE()
	{
		return $this->mtongue;
	}

	public function getLHEIGHT()
	{
		return $this->lheight;
	}
	
	public function getHHEIGHT()
	{
		return $this->hheight;
	}

	public function getMSTATUS()
	{
		return $this->mstatus;
	}

	public function getRELIGION()
	{
		return $this->religion;
	}

	public function getAGE_RANGE()
	{
		return $this->lage."-".$this->hage;
	}
}
?>
