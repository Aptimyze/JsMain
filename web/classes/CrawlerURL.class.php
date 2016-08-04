<?php
include_once("CrawlerClassesCommon.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class CrawlerURL
{
	private $URLId;
	private $parameters;
	private $method;
	private $loggedInReqd;
	private $paidLoggedInReqd;
	private $baseURL;
	private $URL;
	private $siteId;
	private $do;

	public function CrawlerURL($siteId='',$do='',$community='')
	{
		if($siteId && $do)
		{
			$this->siteId=$siteId;
			$this->do=$do;
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			if($do=='paid_login')
				$do='login';
			if($do == 'contact_detail_view' && $siteId==2)
			{
				$community = trim($community);
				$sql="SELECT * FROM crawler.crawler_sites_urls WHERE SITE_ID='$siteId' AND ACTION='$do' AND URL LIKE '%$community%'";
			}
			else
			{
				$sql="SELECT * FROM crawler.crawler_sites_urls WHERE SITE_ID='$siteId' AND ACTION='$do'";
			}
			$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching url   ".mysql_error()));
			unset($query);
			if($mysqlObj->numRows($res))
			{
				$row=$mysqlObj->fetchAssoc($res);
				$this->URLId=$row["URL_ID"];
				$this->baseURL=$row["URL"];
				if($this->URLId == 8 || $this->URLId == 9) //modified by prinka
					$this->baseURL.=time(); //(max 13 digit no needed) modified by prinka
				$this->method=$row["REQUEST_METHOD"];
			}
			$sql="SELECT * FROM crawler.crawler_sites_urls_parameters WHERE URL_ID='$this->URLId'";
			$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching url parameters   ".mysql_error()));
			if($mysqlObj->numRows($res))
			{
				while($row=$mysqlObj->fetchAssoc($res))
				{
					//Converting first character to lower case so object name can be formed
					if($row["PARENT_CLASS"])
						$row["PARENT_CLASS"]{0}=strtolower($row["PARENT_CLASS"]{0});
					$this->parameters[]=$row;
				}
			}
		}
	}
	
	public function getURLParameters()
	{
		return $this->parameters;	
	}

	public function setURLParametersValues($objectsRequired)
	{
		if($this->parameters)
		{
			foreach($this->parameters as $key=>$parameter)
			{
				if(!$parameter["VALUE"] && $parameter["PARENT_CLASS"] && !$parameter["DYNAMIC"])
				{
					if($parameter["FIELD_NAME"])
					{
						$objectName=$parameter["PARENT_CLASS"]."Obj";
						$functionName="get".$parameter["FIELD_NAME"];
						if($objectsRequired[$objectName])
						{
							$parameterValue=$objectsRequired[$objectName]->$functionName();
							if($parameter["MAPPING_REQUIRED"])
							{
								$mysqlObj=new Mysql;
								$db=$mysqlObj->connect('crawler');
								if($parameter["FIELD_NAME"]=='LHEIGHT' || $parameter["FIELD_NAME"]=='HHEIGHT')
									$tableName='height';
								else
									$tableName=strtolower($parameter["FIELD_NAME"]);
								$sqlParamValue="SELECT COMPETITION_FIELD_VALUE FROM crawler.crawler_JS_competition_".$tableName."_values_mapping WHERE SITE_ID='$this->siteId' AND JS_FIELD_VALUE='$parameterValue'";
								$resParamValue=$mysqlObj->executeQuery($sqlParamValue,$db);
								if($mysqlObj->numRows($resParamValue))
								{
									$rowParamValue=$mysqlObj->fetchAssoc($resParamValue);
									$this->parameters[$key]["VALUE"]=$rowParamValue["COMPETITION_FIELD_VALUE"];
								}
								unset($mysqlObj);
							}
							else
								$this->parameters[$key]["VALUE"]=$parameterValue;
						}
					}
				}
				elseif($parameter["VALUE"])
				{
					$this->parameters[$key]["VALUE"]=$parameter["VALUE"];
				}
			}	
		}		
	}

	public function formCrawlURL()
	{
		$this->URL="http://".$this->baseURL;
	}

	public function getURL()
	{
		if($this->URL)
			return $this->URL;
	}

	public function getMethod()
	{
		if($this->method)
			return $this->method;
	}
	
	public function getDo()
	{
		if($this->do)
			return $this->do;
	}
}	
?>
