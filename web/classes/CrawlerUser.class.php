<?php
include_once("CrawlerClassesCommon.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class CrawlerUser
{
	private $siteId;
	private $accountId;
	private $userName;
	private $passWord;
	private $paid;
	private $userGender;
	private $noOfContactViewsAllowed;
	private $noOfContactsViewed;
	private $noOfCanViewContacts;
	private $active;
	private $bmCommunity;

	public static function getUsersFromId($idArr)
	{
		if(is_array($idArr))
		{
			foreach($idArr as $id)
				$accountArr[$id]=new CrawlerUser($id);
			return $accountArr;
		}
	}


	public function CrawlerUser($accountId='',$siteId='',$action='',$paid='',$userGender='', $religion='',$mtongue='',$lage='',$hage='')
	{
		if($accountId || $siteId)
		{
			$createUser=0;
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="SELECT * FROM crawler.crawler_competition_accounts WHERE ACTIVE='Y'";
			if($accountId)
				$sql.=" AND ACCOUNT_ID='$accountId'";
			if($siteId)
				$sql.=" AND SITE_ID='$siteId'";
			if($paid)
				$sql.=" AND PAID='Y'";
			if($userGender)
				$sql.=" AND GENDER='$userGender'";
			if($religion)
				$sql.=" AND RELIGION='$religion'";
			if($mtongue && $siteId <> 3 )
				$sql.=" AND MTONGUE='$mtongue'";
			if($lage && $hage)
			{
				if($userGender == 'M')
				{
					if($lage>40)
						$sql.=" AND AGE=36 ";
					else
						$sql.=" AND AGE>=$lage AND AGE<=$hage ";
				}
				elseif($userGender == 'F')
				{
					$sql.=" AND AGE>=$lage AND AGE<=$hage ";
				}
			}
			if($action=='contact_detail_view')
				$sql.=" AND  NO_OF_CONTACT_VIEWS_ALLOWED-NO_OF_CONTACT_DETAILS_VIEWED>3";
			$sql.=" ORDER BY RAND() ";
			echo "\n".$sql;
			$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching user accounts   ".mysql_error()));
			if($mysqlObj->numRows($res))
			{
				while($row=$mysqlObj->fetchAssoc($res))
				{
					if($action)
					{
						$tableName='';
						if($action=='contact_detail_view')
							$tableName="crawler_detail_view_history";
						else
							$tableName="crawler_".$action."_history";
						$sqlCheck="SELECT COUNT(*) AS COUNT FROM crawler.$tableName WHERE ACCOUNT_ID='$row[ACCOUNT_ID]' AND TIME>=CURDATE()";
						if($action=='contact_detail_view')
							$sqlCheck.=" AND CONTACT_DETAIL_VIEW='Y'";
						$resCheck=$mysqlObj->executeQuery($sqlCheck,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while checking user account  ".mysql_error()));
						if($rowCheck=$mysqlObj->fetchAssoc($resCheck))
						{
							if(1)
							//if(!$rowCheck["COUNT"])
							{
								$createUser=1;
								break;
							}
						}
					}
					else
					{
						$createUser=1;
						break;
					}
				}
			}
			elseif($action == 'detail_view' && $siteId == 3)
                        {
                                $sql = "SELECT * FROM crawler.crawler_competition_accounts WHERE ACTIVE='Y' AND SITE_ID='$siteId' AND GENDER='$userGender' AND RELIGION='$religion'  ORDER BY RAND()";
                                echo "\n".$sql;
                                $res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching user accounts   ".mysql_error()));
                                while($row=$mysqlObj->fetchAssoc($res))
                                {
                                        if($action)
                                        {
                                                $tableName='';
                                                if($action=='contact_detail_view')
                                                        $tableName="crawler_detail_view_history";
                                                else
                                                        $tableName="crawler_".$action."_history";
                                                $sqlCheck="SELECT COUNT(*) AS COUNT FROM crawler.$tableName WHERE ACCOUNT_ID='$row[ACCOUNT_ID]' AND TIME>=CURDATE()";
                                                if($action=='contact_detail_view')
                                                        $sqlCheck.=" AND CONTACT_DETAIL_VIEW='Y'";
                                                $resCheck=$mysqlObj->executeQuery($sqlCheck,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while checking user account  ".mysql_error()));
                                                if($rowCheck=$mysqlObj->fetchAssoc($resCheck))
                                                {
                                                        if(1)
                                                        //if(!$rowCheck["COUNT"])
                                                        {
                                                                $createUser=1;
                                                                break;
                                                        }
                                                }
                                        }
                                        else
                                        {
                                                $createUser=1;
                                                break;
                                        }
                                }
                        }

			if($createUser)
			{
				$this->siteId=$row["SITE_ID"];
				$this->accountId=$row["ACCOUNT_ID"];
				$this->userName=$row["USERNAME"];
				$this->passWord=$row["PASSWORD"];
				$this->paid=$row["PAID"];
				$this->noOfContactViewsAllowed=$row["NO_OF_CONTACT_VIEWS_ALLOWED"];
				$this->noOfContactsViewed=$row["NO_OF_CONTACT_DETAILS_VIEWED"];
				$this->active=$row["ACTIVE"];
				$this->noOfCanViewContacts=$row["NO_OF_CONTACT_VIEWS_ALLOWED"]-$row["NO_OF_CONTACT_DETAILS_VIEWED"]-3;
				$this->bmCommunity=$row['BM_COMMUNITY'];
			}
		}
	}
	
	public static function getUser($siteId,$paid,$contactDetails)
	{
	
	}


	public function getACCOUNT_ID()
	{
		return $this->accountId;
	}	

	public function getUSERNAME()
	{
		return $this->userName;
	}

	public function getPASSWORD()
	{
		return $this->passWord;
	}

	public function getPAID()
	{
		return $paid;
	}

	public function addViewedContacts($viewedContacts)
	{
		if(is_numeric($viewedContacts) && $viewedContacts>0)
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="UPDATE crawler.crawler_competition_accounts SET NO_OF_CONTACT_DETAILS_VIEWED=NO_OF_CONTACT_DETAILS_VIEWED + $viewedContacts WHERE ACCOUNT_ID='$this->accountId'";
			echo "\n".$sql;
			$mysqlObj->executeQuery($sql,$db);
			unset($mysqlObj);
		}
	}
	
	public function getNoOfCanViewContacts()
	{
		return $this->noOfCanViewContacts;
	}

	public function getBmCommunity()
	{
		return $this->bmCommunity;
	}

	public function markUserAsInvalid()
	{
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('crawler');
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
		$sql = "UPDATE crawler.crawler_competition_accounts SET ACTIVE='N' WHERE ACCOUNT_ID='$this->accountId'";
		echo "\n".$sql;
		$mysqlObj->executeQuery($sql,$db);
		unset($mysqlObj);
	}
}
?>
