<?php
include_once("CrawlerClassesCommon.php");
include_once("regularExpressions.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class CrawlerSite
{
	private $siteId;
	private $siteURL;
	private $toBeCrawled;
	private $actionParameters;
	private $actionSequence;
	private $priority;
	private $db;

	public function CrawlerSite($parameters=array())
	{
		$this->siteId=$parameters["SITE_ID"];
		$this->siteURL=$parameters["SITE_URL"];
		$this->toBeCrawled=$parameters["TO_BE_CRAWLED"];
	}

	public static function getActiveSites($siteId)
	{
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('crawler');
		$sql="SELECT * FROM crawler.crawler_sites WHERE TO_BE_CRAWLED='Y' AND SITE_ID=$siteId ORDER BY PRIORITY";
		$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching sites details  ".mysql_error()));
		if($mysqlObj->numRows($res))
		{
			while($row=$mysqlObj->fetchAssoc($res))
			{
				$siteArr[]=new CrawlerSite($row);
			}
			return $siteArr;
		}
		unset($mysqlObj);
	}

	public function setActionSequence($action)
	{
		if($action && $this->siteId)
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			$sql="SELECT LOGIN_REQUIRED,PAID_LOGIN_REQUIRED,RESULTS_PER_PAGE FROM crawler.crawler_sites_actions WHERE SITE_ID='$this->siteId' AND ACTION='$action'";
			$res=$mysqlObj->executeQuery($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Error while fetching action parameters   ".mysql_error()));
			if($mysqlObj->numRows($res))
			{
				$row=$mysqlObj->fetchAssoc($res);
				$this->actionParameters=$row;

				if($this->actionParameters["PAID_LOGIN_REQUIRED"]=='Y')
					$paid_login=1;
				elseif($this->actionParameters["LOGIN_REQUIRED"]=='Y')
					$login=1;
				if($this->actionParameters["RESULTS_PER_PAGE"])
					$pagination=1;

				if($paid_login)
					$this->actionSequence[]='paid_login';
				elseif($login)
					$this->actionSequence[]='login';
				$this->actionSequence[]=$action;
				if($pagination)
					$this->actionSequence[]=$action.'_pagination';
				if($paid_login || $login)
					$this->actionSequence[]='logout';
			}
		}
	}

	public function getActionSequence()
	{
		return $this->actionSequence;
	}
		

	public function getSITE_ID()
	{
		return $this->siteId;
	}

	public function getPaginationParameter($pageNo)
	{
		switch($this->siteURL)
		{
			case "www.shaadi.com" :
				return ($pageNo-1) * $this->actionParameters["RESULTS_PER_PAGE"];

			case "www.bharatmatrimony.com" :
				return ($pageNo-1) * 10 + 1;

			case "www.simplymarry.com" :
				return $pageNo;
//				return "searchResultForm%3Asearchincid%3A_id429idx".$pageNo;
		}
	}

	public function getPaginationParameter2($pageNo)
	{
		switch($this->siteURL)
                {
			case "www.simplymarry.com" :
				return $pageNo;
//				return "idx".$pageNo;

		}
	}

	public function isPagination()
	{
		if($this->actionParameters["RESULTS_PER_PAGE"])
			return true;
	}

	public function getActionId($responseText,$action)
	{
		global $regularExpressions;
		global $debugCounter;
		switch($action)
		{
			case 'search':
				switch($this->siteURL)
				{
					case 'www.shaadi.com' :
						$position1=strpos($responseText,"PG_SEARCHRESULTS_ID = ");
                                                $position2=strpos($responseText,";PG_CONTROLLER");
						$position1+=strlen("PG_SEARCHRESULTS_ID = ")+1;
						$length=$position2-$position1-1;
						$actionId=substr($responseText,$position1,$length);
						if($actionId)
							return $actionId;
						else
						{
							$competitionFieldName="searchActionId";
							$noMatch=1;
						}
						break;

					case 'www.simplymarry.com' :
						$pattern = $regularExpressions[$this->siteId]["searchActionId"];
						preg_match($pattern,$responseText,$regs);
						if($regs[0])
						{
							$matches1=explode("search",$regs[0]);
//							$matches2=explode(">",$matches1[0]);
//							if($matches2[1])
								return urlencode($matches1[1]);
						}
						else
						{
							$competitionFieldName="searchActionId";
							$noMatch=1;
						}			
						break;
				}
		}
		if($noMatch)
		{
			$crawlerErrorHandlerObj=new CrawlerErrorHandler('',$action,$this->siteId,$competitionFieldName,$competitionFieldLabel);
			$crawlerErrorHandlerObj->logNoValueFound(1);
		}
	}

	public function getActionId2($responseText,$action)
        {

                global $regularExpressions;
                global $debugCounter;
                switch($action)
                {
                        case 'search':
                                switch($this->siteURL)
                                {
					case 'www.simplymarry.com' :
                                                $pattern = $regularExpressions[$this->siteId]["searchActionId2"];
                                                preg_match($pattern,$responseText,$regs);
                                                if($regs[0])
                                                {
                                                        $matches1=explode("value=",$regs[0]);
                                                        $matches2=explode("/>",$matches1[1]);
							$matches2[0]=trim($matches2[0],'"');
                                                        if($matches2[1])
                                                                return urlencode($matches2[0]);
                                                }
                                                else
                                                {
                                                        $competitionFieldName="searchActionId2";
                                                        $noMatch=1;
                                                }
                                                break;
				}
				break;
		}
		if($noMatch)
                {
                        $crawlerErrorHandlerObj=new CrawlerErrorHandler('',$action,$this->siteId,$competitionFieldName,$competitionFieldLabel);
                        $crawlerErrorHandlerObj->logNoValueFound(1);
                }
	}

	
	public function getNoOfResults($content,$action)
	{
		global $regularExpressions;
		global $debugCounter;
		switch($action)
                {
                        case 'search':
                                switch($this->siteURL)
                                {
                                        case 'www.shaadi.com' :
						$pattern=$regularExpressions[$this->siteId]["noOfSearchResults"];
						preg_match($pattern,$content,$regs);
						if($regs[0])
						{
							$regs[0]=trim($regs[0],")");
							$regs[0]=trim($regs[0],"(");
							$resultsArr=explode(" ",$regs[0]);
							$result=trim($result,"+");
							$result=trim($resultsArr[0]);
							if(is_numeric($result))
								return $result;
						}
						else
                                                {
                                                        $competitionFieldName="noOfSearchResults";
                                                        $noMatch=1;
                                                }
						break;
					
					case 'www.bharatmatrimony.com' :
						$pattern=$regularExpressions[$this->siteId]["noOfSearchResults"];
						preg_match($pattern,$content,$regs);
						if($regs[0])
						{
							$resultsArr=explode("~",$regs[0]);
							$result=$resultsArr[1];
							if(is_numeric($result))
								return $result;
						}
						else
                                                {
                                                        $competitionFieldName="noOfSearchResults";
                                                        $noMatch=1;
                                                }
						break;

					case 'www.simplymarry.com' :
						$pattern=$regularExpressions[$this->siteId]["noOfSearchResults"];
						preg_match($pattern,$content,$regs);
						if($regs[0])
						{
							$resultsArr=explode(">",$regs[0]);
							$result=explode("results",$resultsArr[1]);
							$result[0] = str_replace("+","",$result[0]);
							$result[0] = str_replace("About","",$result[0]);
							$result[0]=trim($result[0]);
							if(is_numeric($result[0]))
								return $result[0];
						}
						else
                                                {
                                                        $competitionFieldName="noOfSearchResults";
                                                        $noMatch=1;
                                                }
						break;
                                }
                }
		if($noMatch)
                {
                        $crawlerErrorHandlerObj=new CrawlerErrorHandler('',$action,$this->siteId,$competitionFieldName,$competitionFieldLabel);
			$crawlerErrorHandlerObj->logNoValueFound(1);
                }
	}
	public function getNoOfPages($noOfResults)
        {
		global $debugCounter;
		if($this->actionParameters["RESULTS_PER_PAGE"])
		{
			if($noOfResults%$this->actionParameters["RESULTS_PER_PAGE"])
				$noOfPages=(int)($noOfResults/$this->actionParameters["RESULTS_PER_PAGE"]+1);
			else
				$noOfPages=(int)($noOfResults/$this->actionParameters["RESULTS_PER_PAGE"]);
			return $noOfPages;
		}
        }
	public function writeResults($crawlerSessionId='',$searchGender='',$action='',$resp='',$pg='',$url='',$data='')
	{
		global $regularExpressions;
		global $debugCounter;
		switch($action)
		{
			case 'search':
				switch($this->siteURL)
				{
					case 'www.simplymarry.com' :
						$pattern=$regularExpressions[$this->siteId]["searchPageProfileId"];
						preg_match_all($pattern,$resp,$regs);
						$profileArr=array();
						if(is_array($regs) && count($regs))
						{
							foreach($regs as $matchArr)
							{
								foreach($matchArr as $match)
								{
									$profile=explode("\"",$match);
									if($profile)
									{
										$profileArr[]=$profile[2];
										$urlPattern=$regularExpressions[$this->siteId]["detailPageUrlParams1"].$profile[2].$regularExpressions[$this->siteId]["detailPageUrlParams2"];
										preg_match($urlPattern,$resp,$detailPageUrlParams);
										$detailPageUrlParams = explode("\"",$detailPageUrlParams[0]);
										$detailPageParams[]=urlencode($detailPageUrlParams[0]);
										$namePattern = explode("/",$detailPageUrlParams[0]);
										$namePosition = sizeof($namePattern) - 1;
										$namePattern[$namePosition]=str_replace("-"," ",$namePattern[$namePosition]);
										$name[]=$namePattern[$namePosition];
									}
								}

								if(is_array($profileArr) && count($profileArr))
								{
									foreach($profileArr as $key=>$profile)
										$valueStringArr[]="('$crawlerSessionId','$this->siteId','$profile','$searchGender','N','$detailPageParams[$key]','$name[$key]')";
									$insertQuery="INSERT IGNORE INTO crawler.crawler_search_results(SEARCH_ID,SITE_ID,COMPETITION_ID,GENDER,DETAIL_VIEW_PARSED,DETAIL_PAGE_PARAMS_SM,NAME) VALUES ";
									$insertQuery.=implode(",",$valueStringArr);
									echo "\n".$insertQuery;
									$mysqlObj=new Mysql;
									$db=$mysqlObj->connect('crawler');
									$mysqlObj->executeQuery($insertQuery,$db);
									if($mysqlObj->affectedRows()==0)
									{
										$sql1="INSERT INTO crawler.crawler_search_duplicate_pages VALUES($this->siteId,$crawlerSessionId,CURDATE())";
										$mysqlObj->executeQuery($sql1,$db);
									}
									else
									{
										$noOfResultsEntered = $mysqlObj->affectedRows();
										$sql2 = "UPDATE crawler.crawler_no_of_search_results SET NO_OF_RESULTS_ENTERED = NO_OF_RESULTS_ENTERED + $noOfResultsEntered WHERE DATE=CURDATE() AND SITE_ID=".$this->siteId;
										$mysqlObj->executeQuery($sql2,$db);
										if($mysqlObj->affectedRows() == 0)
										{
											$sql3 = "INSERT INTO crawler.crawler_no_of_search_results(SITE_ID,DATE,NO_OF_RESULTS_ENTERED) VALUES(".$this->siteId.",CURDATE(),$noOfResultsEntered)";
											$mysqlObj->executeQuery($sql3,$db);
										}
									}
									unset($mysqlObj);
									return 'U';
								}
							}
						}
						else
							$unexpectedResponse=1;
						break;

					case 'www.shaadi.com' :
						$pattern=$regularExpressions[$this->siteId]["searchPageProfileId"];
						preg_match_all($pattern,$resp,$regs);
						$profileArr=array();
						if(is_array($regs) && count($regs))
						{
							foreach($regs as $matchArr)
							{
								foreach($matchArr as $match)
								{
									$profile=explode("%3D",$match);
									if($profile)
									{
										if(!in_array($profile[1],$profileArr) && $profile[1]!='')
											$profileArr[]=$profile[1];
									}
								}
								if(is_array($profileArr) && count($profileArr))
								{
									foreach($profileArr as $profile)
										$valueStringArr[]="('$crawlerSessionId','$this->siteId','$profile','$searchGender','N')";
									$insertQuery="INSERT IGNORE INTO crawler.crawler_search_results(SEARCH_ID,SITE_ID,COMPETITION_ID,GENDER,DETAIL_VIEW_PARSED) VALUES ";
									$insertQuery.=implode(",",$valueStringArr);
									echo "\n".$insertQuery;
									$mysqlObj=new Mysql;
									$db=$mysqlObj->connect('crawler');
									$mysqlObj->executeQuery($insertQuery,$db);
									if($mysqlObj->affectedRows()==0)
									{
										$sql1="INSERT INTO crawler.crawler_search_duplicate_pages VALUES($this->siteId,$crawlerSessionId,CURDATE())";
										$mysqlObj->executeQuery($sql1,$db);
									}
									else
									{
echo "\n\n";
										$noOfResultsEntered = $mysqlObj->affectedRows();
echo										$sql2 = "UPDATE crawler.crawler_no_of_search_results SET NO_OF_RESULTS_ENTERED = NO_OF_RESULTS_ENTERED + $noOfResultsEntered WHERE DATE=CURDATE() AND SITE_ID=".$this->siteId;
										$mysqlObj->executeQuery($sql2,$db);
										if($mysqlObj->affectedRows() == 0)
										{
echo "\n\n";
echo											$sql3 = "INSERT INTO crawler.crawler_no_of_search_results(SITE_ID,DATE,NO_OF_RESULTS_ENTERED) VALUES(".$this->siteId.",CURDATE(),$noOfResultsEntered)";
											$mysqlObj->executeQuery($sql3,$db);
										}
									}
									unset($mysqlObj);
									return 'U';
								}
							}
						}
						else
							$unexpectedResponse=1;
						break;

					case 'www.bharatmatrimony.com' :
						$pattern=$regularExpressions[$this->siteId]["searchPageProfileId"];
						preg_match_all($pattern,$resp,$regs);
                                                $profileArr=array();
                                                if(is_array($regs) && count($regs))
                                                {
                                                        foreach($regs as $matchArr)
                                                        {
                                                                foreach($matchArr as $match)
                                                                {
                                                                        $profile=explode(":",$match);
									$profile[1]=trim($profile[1],'"');
                                                                        if($profile)
                                                                        {
                                                                                if(!in_array($profile[1],$profileArr))
                                                                                        $profileArr[]=$profile[1];
                                                                        }
                                                                }
                                                                if(is_array($profileArr) && count($profileArr))
                                                                {
                                                                        foreach($profileArr as $profile)
                                                                                $valueStringArr[]="('$crawlerSessionId','$this->siteId','$profile','$searchGender','N')";
                                                                        $insertQuery="INSERT IGNORE INTO crawler.crawler_search_results(SEARCH_ID,SITE_ID,COMPETITION_ID,GENDER,DETAIL_VIEW_PARSED) VALUES ";
                                                                        $insertQuery.=implode(",",$valueStringArr);
                                                                        echo "\n".$insertQuery;
                                                                        $mysqlObj=new Mysql;
                                                                        $db=$mysqlObj->connect('crawler');
                                                                        $mysqlObj->executeQuery($insertQuery,$db);
									if($mysqlObj->affectedRows()==0)
									{
										$sql1="INSERT INTO crawler.crawler_search_duplicate_pages VALUES($this->siteId,$crawlerSessionId,CURDATE())";
										$mysqlObj->executeQuery($sql1,$db);
									}
									else
									{
										$noOfResultsEntered = $mysqlObj->affectedRows();
										$sql2 = "UPDATE crawler.crawler_no_of_search_results SET NO_OF_RESULTS_ENTERED = NO_OF_RESULTS_ENTERED + $noOfResultsEntered WHERE DATE=CURDATE() AND SITE_ID=".$this->siteId;
										$mysqlObj->executeQuery($sql2,$db);
										if($mysqlObj->affectedRows() == 0)
										{
											$sql3 = "INSERT INTO crawler.crawler_no_of_search_results(SITE_ID,DATE,NO_OF_RESULTS_ENTERED) VALUES(".$this->siteId.",CURDATE(),$noOfResultsEntered)";
											$mysqlObj->executeQuery($sql3,$db);
										}
									}
                                                                        unset($mysqlObj);
                                                                        return 'U';
                                                                }
                                                        }
                                               }
						else
							$unexpectedResponse=1;
                                               break;
				}
				break;
			case 'contact_detail_view':
			case 'detail_view':
				$mysqlObj=new Mysql;
				$db=$mysqlObj->connect('crawler');
				$uploadValues='';
				$competitionProfile=new CrawlerCompetitionProfile(array("COMPETITION_ID"=>$pg,"SITE_ID"=>$this->siteId));
				$sql="SELECT COMPETITION_FIELD_NAME,JS_FIELD_NAME,MAPPING_REQUIRED FROM crawler.crawler_JS_competition_field_name_mapping WHERE SITE_ID='$this->siteId' AND ACTION='$action'";		
				$res=$mysqlObj->executeQuery($sql,$db);
				if($mysqlObj->numRows($res))
				{ 
					while($row=$mysqlObj->fetchAssoc($res))
					{  
				//	echo "\n".$row["COMPETITION_FIELD_NAME"]; 
						$noMap='';
						$noMatch='';
						$competitionFieldName='';
						$competitionFieldLabel='';
						$pattern=$regularExpressions[$this->siteId][$row["COMPETITION_FIELD_NAME"]];
						if($pattern)
						{
							$match='';
							$regs='';
							preg_match($pattern,$resp,$regs);
							if($regs[0])
							{
								switch($this->siteURL)
								{
									case 'www.shaadi.com' :
										if($row["COMPETITION_FIELD_NAME"]=='Gender')
											$split=explode(" of ",$regs[0]);
										elseif($row["COMPETITION_FIELD_NAME"]=='se')
										{
//											$split=explode("value=",$regs[0]);
											$split=explode("=",$regs[0]);
											$split[1]=trim($split[1],"\"");
										}
										elseif($row["COMPETITION_FIELD_NAME"]=='Mobile')
										{
											$split=explode("+91-",$regs[0]);
										}
										elseif($row["COMPETITION_FIELD_NAME"]=='Landline')
										{
											$landline=explode("+91-",$regs[0]);
											$split=explode("-",$landline[1]);
                                                                                        if(!$split[1] && $split[0])
                                                                                                $split[1]=$split[0];
										}									
										elseif($row["COMPETITION_FIELD_NAME"]=='STD')
										{
											$landline=explode("+91-",$regs[0]);
                                                                                        $split=explode("-",$landline[1]);
                                                                                        if($split[1] && $split[0])
											{
												$split[0]=ltrim($split[0],"0");
                                                                                                $split[1]="0".$split[0];
											}
                                                                                        else
                                                                                                unset($split);
										}

										elseif($row["COMPETITION_FIELD_NAME"] == 'Age' || $row["COMPETITION_FIELD_NAME"] == 'Height')
										{ 
											$arr1 = explode("\">",$regs[0]);
											$val[0] = str_replace(" ","",$arr1[2]);
											$val = explode("/",$val[0]);
											if($row["COMPETITION_FIELD_NAME"] == 'Age')
												$split[1] = $val[0];
											elseif($row["COMPETITION_FIELD_NAME"] == 'Height')
											{   
												$value = explode("(",$val[1]);
												$split[1] = $value[0];
											}
										}
										elseif($row["COMPETITION_FIELD_NAME"] == 'se')
										{
											$split = explode("=",$regs[0]);
										}
										elseif($row["COMPETITION_FIELD_NAME"] != 'Mobile' && $row["COMPETITION_FIELD_NAME"] != 'Landline' && $row["COMPETITION_FIELD_NAME"] != 'STD')
										{
											$arr1 = explode("</li>",$regs[0]);
											$val = explode(">",$arr1[1]);
											if($row["COMPETITION_FIELD_NAME"] == 'Time of Birth')
											{
												$split1=explode(" ",$val[1]);
												$split[1] = trim($split1[0]);
											}
											elseif($row["COMPETITION_FIELD_NAME"] == 'City of Birth' || $row["COMPETITION_FIELD_NAME"] == 'Country of Birth')
											{
												if(!strpos($regs[0],'Screening'))
												{    
													$split1=explode(",",$val[1]);
													if($row["COMPETITION_FIELD_NAME"] == 'City of Birth')
                                                                                                		$split[1] = $split1[0];
                                                                                        		elseif($row["COMPETITION_FIELD_NAME"] == 'Country of Birth')
                                                                                                          	$split[1]=$split1[1];
                                                                                                }

												else
													$split[1]=NULL;
											}
											elseif($row["COMPETITION_FIELD_NAME"] != 'Gender')
												$split[1] = $val[1];
										}
										
										$match=trim($split[1]);
										if($row["COMPETITION_FIELD_NAME"]=='Date of Birth')
										{  
											$split = explode("</li>",$regs[0]);
											$split1 = explode(">",$split[1]);
											$match=getFormattedDate(trim($split1[1]),'dd-mmm-yyyy');
										}
 
										if($row["COMPETITION_FIELD_NAME"]=='Country of Residence')
										{	
											$countryResArr=explode(",",$match);
											$match=trim($countryResArr[1]);
										}

										if($row["COMPETITION_FIELD_NAME"]=='Current Residence')
										{
											$matches = explode(',',$match);
											$match = trim($matches[0]);
										}
										if($row["COMPETITION_FIELD_NAME"]=='No. of Sisters'||$row["COMPETITION_FIELD_NAME"]=='No. of Brothers')
                                                                                {
                                                                                        $split = explode("</li>",$regs[0]);
                                                                                        $split1 = explode("\">",$split[1]);
											$noComment=explode(" ",$split1[1]);
											$match=trim($noComment[0]);
                                                                                }
										 if($row["COMPETITION_FIELD_NAME"]=='Gothra')
                                                                                { 
                                                                                        $split = explode("</li>",$regs[0]);
											$split1= explode("\">",$split[1]);
											$match = trim($split1[1]);
                                                                                }
										if($row["COMPETITION_FIELD_NAME"] == 'Name')
                                                                                {  
                                                                                        $arr1 = explode("</",$regs[0]);
                                                                                        $split = explode(">",$arr1[0]);
                                                                                        $match=trim($split[1]);
                                                                                }
										


										break;
										
									case 'www.bharatmatrimony.com' :
										if($row["COMPETITION_FIELD_NAME"]=='Age')	
										{
											$split=explode(" ",$regs[0]);
											$match=trim($split[0]);
										}	
										elseif($row["COMPETITION_FIELD_NAME"]=='Phone')
										{
											$pos = strpos($regs[0],"-");
											$match = substr($regs[0],$pos+1,strlen($regs[0]));
										}
										elseif($row["COMPETITION_FIELD_NAME"]=='Marital status' || $row["COMPETITION_FIELD_NAME"]=='Gothra')
										{
											$split=explode("</div>",$regs[0]);
											$matches=explode(">",$split[1]);
											$match=trim($matches[1]);
										}
/*
										elseif($row["COMPETITION_FIELD_NAME"]=='BM_COMMUNITY')
										{
											$split=explode("-",$regs[0]);
											$split1=explode(".",$split[1]);
											$matches=explode("Matrimony",$split1[0]);
											$matches[0]=strtolower($matches[0]);
											$match = trim($matches[0]);

//											$regs[0]=strtolower($regs[0]);
											$split=explode(".",$regs[0]);
											$matches=explode("matrimony",$split[1]);
											$match = trim($matches[0]);
										}
*/
										else
										{
											$split=explode("</div>",$regs[0]);
											$matches=explode(">",$split[1]);
											$match=trim($matches[1]);
											if($row["COMPETITION_FIELD_NAME"]=='No. of Brother(s)' || $row["COMPETITION_FIELD_NAME"]=='No. of Sister(s)' || $row["COMPETITION_FIELD_NAME"]=='Height')
											{
												$matches2=explode("/",$match);
												$match=trim($matches2[0]);
												if($row["COMPETITION_FIELD_NAME"]=='Height')
												{
													$match=str_replace("&nbsp;Ft&nbsp;","' ",$match);
													$match=str_replace(" &nbsp;In",'"',$match);
												}
											}
											if($row["COMPETITION_FIELD_NAME"]=='Caste' || $row["COMPETITION_FIELD_NAME"]=='Sub Caste')
                                                                                        {
                                                                                                $matches2=explode(" / ",$match);
												if($row["COMPETITION_FIELD_NAME"]=='Caste')
												{
													$matches3=explode("(Caste No Bar)",$matches2[0]);
	                                                                                                $match=trim($matches3[0]);
												}
												elseif($row["COMPETITION_FIELD_NAME"]=='Sub Caste')
													$match=trim($matches2[1]);
												else
													$match=trim($matches2[0]);
                                                                                        }

										}
										break;
									
									case 'www.simplymarry.com' :
										if($row["COMPETITION_FIELD_NAME"]=='Age')
                                                                                {
                                                                                        $split=explode("<p>",$regs[0]);
											$split2 = explode("(",$split[1]);
											$split2[0]=str_replace("\n","",$split2[0]);
											$split2[0]=str_replace(">","",$split2[0]);
                                                                                        $match=trim($split2[0]);
                                                                                }
										elseif($row["COMPETITION_FIELD_NAME"]=='Height')
										{
                                                                                        $split=explode("/",$regs[0]);
											$match = trim($split[3]);
										}
										elseif($row["COMPETITION_FIELD_NAME"]=='Date of Birth')
                                                                                {
                                                                                        $split=explode("span",$regs[0]);
											$split2=str_replace("(","",$split[1]);
											$split2=str_replace(")","",$split2);
											$split2=str_replace("/","",$split2);
											$split2=str_replace(">","",$split2);
											$split2=str_replace("<","",$split2);
//											$match = date("d-m-Y", JSstrToTime($split2));
											$match = date("Y-m-d", JSstrToTime($split2));
                                                                                }
										elseif($row["COMPETITION_FIELD_NAME"]=='City of Residence' || $row["COMPETITION_FIELD_NAME"]=='Country of Residence')
										{
											$split=explode("/",$regs[0]);
											$split[5]=str_replace("\n","",$split[5]);
											$split[5]=str_replace("<","",$split[5]);
											$split[5]=str_replace(">","",$split[5]);
											$split2 = explode(",",$split[5]);
											if($row["COMPETITION_FIELD_NAME"]=='City of Residence')
											{
												$match = trim($split2[0]);
											}
											elseif($row["COMPETITION_FIELD_NAME"]=='Country of Residence')
											{
												$match = trim($split2[1]);
											}
										}
										elseif($row["COMPETITION_FIELD_NAME"]=='Marital Status' || $row["COMPETITION_FIELD_NAME"]=='Religion' || $row["COMPETITION_FIELD_NAME"]=='Caste' || $row["COMPETITION_FIELD_NAME"]=='Mother Tongue' || $row["COMPETITION_FIELD_NAME"]=='Smoking' || $row["COMPETITION_FIELD_NAME"]=='Drinking' || $row["COMPETITION_FIELD_NAME"]=='Eating Habits' || $row["COMPETITION_FIELD_NAME"]=='Time of Birth' || $row["COMPETITION_FIELD_NAME"]=='Education' || $row["COMPETITION_FIELD_NAME"]=='City of Birth' || $row["COMPETITION_FIELD_NAME"]=='Country of Birth' || $row["COMPETITION_FIELD_NAME"]=='Sub Caste' || $row["COMPETITION_FIELD_NAME"]=='Brothers' || $row["COMPETITION_FIELD_NAME"]=='Sisters')
                                                                                {
                                                                                        $split=explode("span",$regs[0]);
											$split2 = explode(">",$split[1]);
											$split3 = explode("<",$split2[1]);
											if($row["COMPETITION_FIELD_NAME"]=='Brothers')
											{
												$split4 = explode('brother',$split3[0]);
												$match=trim($split4[0]);
											}
											elseif($row["COMPETITION_FIELD_NAME"]=='Sisters')
											{
												$split4 = explode('sister',$split3[0]);
												$match=trim($split4[0]);
											}
											else
												$match=trim($split3[0]);
                                                                                }
										else
										{

											if($action=='contact_detail_view')
											{
												if($row["COMPETITION_FIELD_NAME"]=='Mobile')
												{
													$regs[0] = str_replace("\n","",$regs[0]);
													$split = explode("<span>",$regs[0]);
													$split2 = explode("-",$split[1]);
													$match = trim($split2[1]);
												}
												elseif($row["COMPETITION_FIELD_NAME"]=='Email')
												{
													$split = explode(">",$regs[0]);
													$split2 = explode("<",$split[2]);
													$match = trim($split2[0]);
												}
											}
										}
										if($row["COMPETITION_FIELD_NAME"]=='Time of Birth' && $match)
										{
											$matches=explode(":",$match);
											$match=$matches[0].":".$matches[1];
										}
								}
								
							}
							else
                                                        {
                                                                $competitionFieldName=$row["COMPETITION_FIELD_NAME"];
                                                                $noMatch=1;
                                                        }
							if($match || $match=='0')
							{
								$competitionProfileVariable=strtolower($row["JS_FIELD_NAME"]);
								echo "\n $competitionProfileVariable    $match";
								if($row["MAPPING_REQUIRED"]=='Y' && $row["JS_FIELD_NAME"])
								{
									if($competitionProfileVariable=='country_birth')
										$tableName="crawler.crawler_JS_competition_country_res_values_mapping";
									else
										$tableName="crawler.crawler_JS_competition_".$competitionProfileVariable."_values_mapping";
									$sqlMapping="SELECT JS_FIELD_VALUE FROM $tableName WHERE COMPETITION_FIELD_LABEL=\"".addslashes(stripslashes($match))."\" AND SITE_ID=\"$this->siteId\"";
									$resMapping=$mysqlObj->executeQuery($sqlMapping,$db);
									if($mysqlObj->numRows($resMapping))
									{
										$rowMapping=$mysqlObj->fetchAssoc($resMapping);
										$uploadValues[$competitionProfileVariable]=$rowMapping["JS_FIELD_VALUE"];
										
									}
									else
									{
										$competitionFieldLabel=$match;
										$competitionFieldName=$row["COMPETITION_FIELD_NAME"];
		                                                                $noMap=1;
									}
								}
								else
 
								{
									if(strtoupper($match)=="NOT SPECIFIED")
										$uploadValues[$competitionProfileVariable]="";
									elseif(stripos(strtoupper($match),"SCRIPT")>0 || stripos(strtoupper($match),"SPAN")>0)
									{
										$uploadValues[$competitionProfileVariable]="";
										$errorMessage="Script Error found for field". competitionProfileVariable. " and Competition Id ". $uploadValues[$searchPageProfileId];
										mail("reshu.rajput@jeevansathi.com" , "Crawler Script Error","$errorMessage");
									}
									else
										$uploadValues[$competitionProfileVariable]=$match;
								//echo "\n $pg     $competitionProfileVariable     $uploadValues[$competitionProfileVariable]";
								}
							}
							else
							{
								$competitionFieldName=$row["COMPETITION_FIELD_NAME"];
                                                                $noMatch=1;
							}
							if($noMatch || $noMap)
							{
								$crawlerErrorHandlerObj=new CrawlerErrorHandler($crawlerSessionId,$action,$this->siteId,$competitionFieldName,$competitionFieldLabel);
								if($noMatch)
									$crawlerErrorHandlerObj->logNoValueFound(1);
								elseif($noMap)
									$crawlerErrorHandlerObj->logNoValueFound('',1);
								unset($crawlerErrorHandlerObj);
							}
						}
					}

					if(is_array($uploadValues))
					{
						if($action=='detail_view')
							$uploadValues["detail_view_parsed"]='Y';
						elseif($action=='contact_detail_view')
							$uploadValues["contact_details_parsed"]="Y";
						$competitionProfile->uploadCompetitionProfileDetails($uploadValues);
						unset($mysqlObj);
						unset($competitionProfile);
						return 'U';
					}					
					else
					{
						$crawlerErrorHandlerObj=new CrawlerErrorHandler($crawlerSessionId,$action,$this->siteId);
						$crawlerErrorHandlerObj->logUnexpectedResponse($resp,$url,$data);
						unset($crawlerErrorHandlerObj);
						if($action=='contact_detail_view')
						{
							$competitionProfile->updateContactDetailsParsingStatus();
						}
						elseif($action == 'detail_view')
						{
							$competitionProfile->updateDetailViewParsingStatus();
						}

					}
				}//end of case for detail views
				break;
		}//end of switch
		if($action=='search')
		{
			if($unexpectedResponse)
			{
				$crawlerErrorHandlerObj=new CrawlerErrorHandler($crawlerSessionId,$action,$this->siteId);
				$crawlerErrorHandlerObj->logUnexpectedResponse($resp,$url,$data);
				unset($crawlerErrorHandlerObj);
			}
		}
		unset($mysqlObj);
		unset($competitionProfile);
		return 1;
	}
}
?>
