<?php
include_once("CrawlerClassesCommon.php");
class CrawlerErrorHandler
{
	private $historyId;
	private $action;
	private $siteId;
	private $competitionFieldName;
	private $competitionFieldLabel;

	public function CrawlerErrorHandler($historyId='',$action='',$siteId='',$competitionFieldName='',$competitionFieldLabel='')
	{
		$this->historyId=$historyId;
		$this->action=$action;
		$this->siteId=$siteId;
		$this->competitionFieldName=$competitionFieldName;
		$this->competitionFieldLabel=$competitionFieldLabel;
	}
	

	public function logNoValueFound($match='',$mapping='')
	{
		global $errorReporting;
		if($this->competitionFieldName && $this->siteId)
		{
			if(!$mapping || $this->competitionFieldLabel)
			{
				$mysqlObj=new Mysql;
				$db=$mysqlObj->connect('crawler');
				$insertValues["COMPETITION_FIELD_NAME"]=$this->competitionFieldName;
				$insertValues["SITE_ID"]=$this->siteId;
				if($this->historyId)
					$insertValues["HISTORY_ID"]=$this->historyId;
				if($this->action)
					$insertValues["ACTION"]=$this->action;
				if($match)
				{
					$insertValues["NO_MATCH"]="Y";
					$errorReporting["NO_REG_MATCH"][$this->siteId][$this->historyId][]=$this->competitionFieldName;
				}
				elseif($mapping)
				{
					$insertValues["NO_MAP"]="Y";
					if($this->competitionFieldLabel)
						$insertValues["COMPETITION_FIELD_LABEL"]=$this->competitionFieldLabel;
					$errorReporting["NO_MAPPING"][$this->siteId][$this->historyId][$this->competitionFieldName][]=$this->competitionFieldLabel;
				}
				$sql="INSERT INTO crawler.crawler_no_mapping ";
				$fields='';
				$values='';
				foreach($insertValues as $field=>$value)
				{
					$fields[]=$field;
					$values[]=addslashes(stripslashes($value));
				}
				$sql.="(".implode(",",$fields).") VALUES(\"".implode("\",\"",$values)."\")";
				echo "\n$sql";
				$res=$mysqlObj->executeQuery($sql,$db);
				unset($mysqlObj);
			}
		}
	}

	public function logUnexpectedResponse($response,$url,$data)
	{
		if($this->historyId && $this->action)
		{
			if($url)
				$url=addslashes(stripslashes($url));
			if($data)
				$data=addslashes(stripslashes($data));
			if($this->action=='detail_view' || $this->action=='contact_detail_view')
			{
				$tableName="crawler_detail_view_history";
				$fieldName="DETAIL_VIEW_ID";
			}
			elseif($this->action=='search')
			{
				$tableName="crawler_search_history";
				$fieldName="SEARCH_ID";
			}
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			$sql="UPDATE $tableName SET UNEXPECTED_RESPONSE='Y',URL=\"$url\",DATA=\"$data\" WHERE $fieldName='$this->historyId'";
			echo "\n$sql";
			$res=$mysqlObj->executeQuery($sql,$db);
			$sql="DELETE FROM crawler_no_mapping WHERE ACTION='$this->action' AND HISTORY_ID='$this->historyId'";
			echo "\n$sql";
                        $res=$mysqlObj->executeQuery($sql,$db);
			global $errorReporting;
			$errorReporting["UNEXPECTED_RESPONSE"][$this->siteId][$this->action][]=$this->historyId;
			unset($errorReporting["NO_MAP"][$this->siteId][$this->historyId]);
			unset($errorReporting["NO_REG_MATCH"][$this->siteId][$this->historyId]);
			global $errorResponseFileBasePath;
			$fileName=$this->action."_".$this->historyId.".htm";
			$fp=fopen($errorResponseFileBasePath."/$fileName","wb");
			fwrite($fp,$response);
			fclose($fp);
		}
	}
}
?>
