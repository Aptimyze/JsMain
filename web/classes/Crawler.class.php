<?php
include_once("CrawlerClassesCommon.php");
class Crawler
{
	private $crawlerURLObjArr;
	private $crawlerSiteObj;
	private $crawlerPriorityCommunityObj;
	private $crawlerUserObj;
	private $response;
	private $action;
	//specific id given by site to the action for pagination purposes
	private $actionId;
	private $actionId2;
	private $pageNo;
	private $errorMessage;
	private $noOfPages;
	private $noOfResults;


	private $urlProxyUS="http://us.proxymesh.com:31280";
	private $urlProxyUK="http://uk.proxymesh.com:31280";
	private $urlProxyUserPwd="kevinbond:kevinbond";
//	private $urlProxyUserPwd="andrewpascal:andrewpascal";


	public function Crawler($crawlerSiteObj='',$crawlerURLObjArr='',$crawlerPriorityCommunityObj='',$crawlerUserObj='',$action='')
	{
		$this->crawlerSiteObj=$crawlerSiteObj;
		$this->crawlerURLObjArr=$crawlerURLObjArr;
		$this->crawlerPriorityCommunityObj=$crawlerPriorityCommunityObj;
		$this->crawlerUserObj=$crawlerUserObj;
		$this->action=$action;
		$this->pageNo=0;
	}

	public function crawl()
	{
		if(is_array($this->crawlerURLObjArr))
		{
			global $sleepTime;
			global $errorReporting;
			global $debugCounter;
			/*$connection=0;
			CrawlerConnection::switchConnection('LAN',0);
			$connection=CrawlerConnection::switchConnection('DATA_CARD');*/
			if(1)
			//if($connection)
			{
				$handle = curl_init();
				$useCookies=1;
				foreach($this->crawlerURLObjArr as $key=>$crawlerURLObj)
				{
					$doDone=0;
					$URL=$crawlerURLObj->getURL();
					$do=$crawlerURLObj->getDo();
					$pagination=$this->crawlerSiteObj->isPagination();
					while(!$doDone)
					{
						$crawlerResponse='';
						$errNo='';
						$errMsg='';
						$responseIndex='';
						$stop=0;
						if($URL)
						{
							$parameters=$crawlerURLObj->getURLParameters();
							$data='';
							$dataString='';
							if($do==$this->action)
							{
								if($pagination && !$this->pageNo && $this->crawlerSiteObj->getSITE_ID()=='3')
									$this->pageNo=0;
								elseif($pagination && !$this->pageNo)
									$this->pageNo=1;
							}
							elseif($do==$this->action.'_pagination')
							{
								if(!$this->noOfPages || !is_numeric($this->noOfPages))
								{
									break;
								}
								$this->pageNo++;
							}
							else
								$this->pageNo=0;
							if(is_array($parameters))
							{
								foreach($parameters as $parameter)
								{
									if($parameter["DYNAMIC"])
									{
										if($parameter["FIELD_NAME"] && $parameter["PARENT_CLASS"])
										{
											$functionName="get".$parameter["FIELD_NAME"];
											if($parameter["PARENT_CLASS"]=='crawler')
											{
												if($functionName == 'getpageNo')
													$parameter["VALUE"]=$this->$functionName($this->crawlerSiteObj->getSITE_ID());
												else
													$parameter["VALUE"]=$this->$functionName();
											}
										}
									}
									if(($parameter["FIELD_NAME"]=='actionId' || $parameter["FIELD_NAME"]=='actionId2') && !$parameter["VALUE"])
									{
										echo "\n no action id";
										$stop=1;
									}
									
									if($parameter["PARAMETER"])
										$data[]="$parameter[PARAMETER]=".$parameter["VALUE"];
								}
								if(is_array($data))
									$dataString=implode($data,"&");
								if($stop)
								{
									echo "\nstopping url loop";
									break;
								}
							}

							if($this->action == 'search' && $this->crawlerSiteObj->getSITE_ID()=='3')
							{
								$dataString = str_replace('parameter_temp=','',$dataString);
								$dataString = urldecode($dataString);
							}

							if($crawlerURLObj->getMethod()=='POST')
							{
								curl_setopt($handle, CURLOPT_POST      ,1);
								if($this->crawlerSiteObj->getSITE_ID()=='3' && $this->action == 'search')
                                                                        $dataString .= "&motherTongues=PUNJABI&motherTongues=MARATHI&motherTongues=GUJARATI&motherTongues=BIHARI&motherTongues=URDU&motherTongues=ORIYA&motherTongues=MARWARI&motherTongues=ENGLISH&motherTongues=SINDHI&motherTongues=AWADHI&motherTongues=BHOJPURI&motherTongues=BRIJ&motherTongues=BADAGA&motherTongues=CHATISGARHI&motherTongues=DHIVEHI&motherTongues=DOGRI&motherTongues=GARHWALI&motherTongues=GARO&motherTongues=HARYANVI&motherTongues=HIMACHALI_PAHARI&motherTongues=JAINTIA&motherTongues=KANAUJI&motherTongues=KHANDESI&motherTongues=KHASI&motherTongues=KONKANI&motherTongues=KOSHALI&motherTongues=KUMOANI&motherTongues=MAGAHI&motherTongues=MAITHILI&motherTongues=RAJASTHANI&motherTongues=OTHERS";
								if($dataString)
									curl_setopt($handle, CURLOPT_POSTFIELDS    ,$dataString);	
							}
							else
							{

								if($dataString)
									$URL=$crawlerURLObj->getURL()."?".$dataString;	

								if($this->crawlerSiteObj->getSITE_ID()=='3')
								{
									if($this->action == 'search' && $this->crawlerSiteObj->getSITE_ID()=='3')
									{
										$URL = $crawlerURLObj->getURL();
										preg_match('/\/.{0,}run/',$dataString,$urlParam);
										$URL.=$urlParam[0];
										$dataString = str_replace("&".$urlParam[0],'',$dataString);
$dataString = str_replace("?sortBy",'sortBy',$dataString); //remove
										$URL.='?'.$dataString;
/*
										$URL = str_replace('search?','search',$URL);
										$URL = str_replace('run&','run',$URL);
										$page = explode('page=',$URL);
										$page = explode('&',$page[1]);
										if(strpos($URL,'page='))
										{
											$URL = str_replace('page='.$page[0].'&','',$URL);
											$URL.= '&page='.$page[0];
										}
*/
									}
									elseif($this->action == 'detail_view' && $this->crawlerSiteObj->getSITE_ID()=='3')
									{
										$URL = str_replace('?detailViewParams=','',$URL);
										$URL = urldecode($URL);
									}
									elseif($this->action == 'contact_detail_view' && $this->crawlerSiteObj->getSITE_ID()=='3')
									{
										$URL = str_replace('?userId=','/',$URL);
										$URL = urldecode($URL);
									}
								
//									if($do==$this->action."_pagination")
										curl_setopt($handle, CURLOPT_POST      ,0);//commented for allowing proxy
//									else
//										curl_setopt($handle, CURLOPT_POST      ,1);//added for allowing proxy
								}
								else
										curl_setopt($handle, CURLOPT_POST      ,1);//added for allowing proxy

							}
							echo "\nurl is ".$URL;
							echo "\ndatastring is   ".$dataString;

							curl_setopt($handle, CURLOPT_RETURNTRANSFER    , true);
							curl_setopt($handle, CURLOPT_HEADER, 1);
							curl_setopt($handle, CURLOPT_MAXREDIRS        , 5);
							curl_setopt($handle, CURLOPT_FOLLOWLOCATION    , true);
							//curl_setopt($handle, CURLOPT_INTERFACE, 'ppp0');
							curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 20);
							curl_setopt($handle, CURLOPT_USERAGENT        , 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4');
							curl_setopt($handle,CURLOPT_ENCODING , 'gzip,deflate');	
							if($useCookies)
							{
								$siteId=$this->crawlerSiteObj->getSITE_ID();
								$filename= "cookiefiles_".$siteId.".txt";

								if($do!='login' && $do!='paid_login')
									curl_setopt($handle,CURLOPT_COOKIEFILE,$_SERVER["DOCUMENT_ROOT"]."/crontabs/crawler/".$filename);
								else
								{
									curl_setopt($handle,CURLOPT_COOKIEJAR,$_SERVER["DOCUMENT_ROOT"]."/crontabs/crawler/".$filename);
								}
							}


							/// adding proxy 
							/*
							$arr[0]="$this->urlProxyUS";
							$arr[1]="$this->urlProxyUK";
//							$random = array_rand($arr,1);
//							$proxyUrl = $arr[$random];

							if($this->crawlerSiteObj->getSITE_ID() == 2)
								$proxyUrl=$arr[0]; //setting only US proxy for BM
							elseif($this->crawlerSiteObj->getSITE_ID() == 1)
								$proxyUrl=$arr[1]; //setting only UK proxy for shaadi
							*/

							$proxyUrl="$this->urlProxyUS";

//							curl_setopt($handle,CURLOPT_PROXY , $proxyUrl);
//							curl_setopt($handle,CURLOPT_PROXYUSERPWD , $this->urlProxyUserPwd);
							/// proxy


							curl_setopt($handle, CURLOPT_URL, $URL);
							/*if($sleepTime)
								sleep($sleepTime);*/

							$sleepVal = rand(5,10);
							sleep($sleepVal);

							if($do==$this->action || $do==$this->action."_pagination")
							{
								/*$siteId=$this->crawlerSiteObj->getSITE_ID();
								//$communityId=$this->crawlerPriorityCommunityObj->getCOMMUNITY_ID();
								//$key=$this->pageNo;
								if(file_exists($_SERVER['DOCUMENT_ROOT']."/crontabs/crawler/response/".$siteId."_contact_detail_".$key.".htm"))
								{
									$fp=fopen($_SERVER['DOCUMENT_ROOT']."/crontabs/crawler/response/".$siteId."_contact_detail_".$key.".htm","rb");
									$crawlerResponse=fread($fp,filesize($_SERVER['DOCUMENT_ROOT']."/crontabs/crawler/response/".$siteId."_contact_detail_".$key.".htm"));								
									fclose($fp);
								}
								else
								{
									$errNo=1;
									$errMsg="no_data";
								}*/
								$crawlerResponse=curl_exec($handle);

								$errNo=curl_errno($handle);

								if($errNo)
									$errMsg=curl_error($handle);
								if($this->action=='search')
									$responseIndex=$this->pageNo;
								elseif($this->action=='detail_view' || $this->action=='contact_detail_view')
									$responseIndex=$key;
								if(!$errNo)
								{
									$this->response[$do][$responseIndex]=array("response"=>$crawlerResponse,
														"url"=>$URL,
														"data"=>$dataString
														);
								}
								else
								{
									$this->response[$do][$responseIndex]=array("error"=>1,
														"errMsg"=>$errMsg,
														"url"=>$URL,
														"data"=>$dataString			
														);
								}	
								if((!$this->actionId || !$this->noOfResults) && $pagination)
								{
									$content=$this->response[$do][$this->pageNo]["response"];
									if($content)
									{
										if(!$this->actionId)
											$this->actionId=$this->crawlerSiteObj->getActionId($content,$this->action);
//										if($this->crawlerSiteObj->getSITE_ID()=='3')
//											$this->actionId2=$this->crawlerSiteObj->getActionId2($content,$this->action);
										echo "\naction id  ".$this->actionId;
										if(!$this->noOfResults)
										{
											$this->noOfResults=$this->crawlerSiteObj->getNoOfResults($content,$this->action);
											echo "\n no of results   ".$this->noOfResults;
											if(is_numeric($this->noOfResults))
												$this->noOfPages=$this->crawlerSiteObj->getNoOfPages($this->noOfResults);
											echo "\nno of pages   ".$this->noOfPages;
											if($this->crawlerSiteObj->getSITE_ID()=='3')
											{
												if($this->noOfPages > 100)
													$this->noOfPages=100;
											}
											elseif($this->noOfPages > 15)
												$this->noOfPages=15;
										}
									}
								}
							}
							else
									//1;
								curl_exec($handle);
							if($do==$this->action.'_pagination')
							{
//								if($this->pageNo==$this->noOfPages)
								if($this->pageNo>=$this->noOfPages)
									$doDone=1;
							}
							else
								$doDone=1;
						}
					}
				}
				curl_close($handle);
			}
			else
				$errorReporting["FAILED_SESSIONS"]++;
			
			$userViewed=0;

			if(is_array($this->response))
			{
				foreach($this->response as $do => $doResponse)
				{
					foreach($doResponse as $pg=>$resp)
					{
						/*$siteId=$this->crawlerSiteObj->getSITE_ID();
						$communityId=$this->crawlerPriorityCommunityObj->getCOMMUNITY_ID();
						$fp=fopen($_SERVER['DOCUMENT_ROOT']."/crontabs/crawler/response/search_".$siteId."_".$communityId."_".$pg.".htm","wb");
						fwrite($fp,$resp["response"]);
						fclose($fp);*/
						if($resp["error"])
						{
							$siteId=$this->crawlerSiteObj->getSITE_ID();
							$crawlerSessionId=$this->updateCrawlerActivity($pg,1,$resp);
							$errorReporting["CRAWL_ERROR"][$siteId][$do][]=$crawlerSessionId;
						}
						else
						{
							$crawlerSessionId=$this->updateCrawlerActivity($pg);
							if($this->crawlerPriorityCommunityObj)
								$searchGender=$this->crawlerPriorityCommunityObj->getGENDER();

							if($this->crawlerSiteObj->getSITE_ID() != '3' || ($this->crawlerSiteObj->getSITE_ID() == '3' && $do != 'search'))
								$uploaded=$this->crawlerSiteObj->writeResults($crawlerSessionId,$searchGender,$this->action,$resp["response"],$pg,$resp["url"],$resp["data"]);
							if($uploaded=='U' && $this->action=='contact_detail_view')
								$userViewed++;

						}
					}
				}
			}
			if($userViewed>0)
				$this->crawlerUserObj->addViewedContacts($userViewed);
			if($userViewed>0 && ($this->crawlerUserObj->getNoOfCanViewContacts()-$userViewed <=3)) //Modified by prinka
				$this->crawlerUserObj->markUserAsInvalid();//mark user as invalid
		}
		return 1;
	}

	public function updateCrawlerActivity($pg,$error=0,$response='')
	{
		if($this->action)
		{
			switch($this->action)
			{
				case 'search' :
					$insertValues["SITE_ID"]=$this->crawlerSiteObj->getSITE_ID();
					$insertValues["COMMUNITY_ID"]=$this->crawlerPriorityCommunityObj->getCOMMUNITY_ID();
					//$insertValues["ACCOUNT_ID"]=$this->crawlerUserObj->getACCOUNT_ID();
					$insertValues["PAGE_NO"]=$pg;
					$tableName="crawler.crawler_search_history";
					break;
			
				case 'detail_view':
					$insertValues["SITE_ID"]=$this->crawlerSiteObj->getSITE_ID();
					$insertValues["ACCOUNT_ID"]=$this->crawlerUserObj->getACCOUNT_ID();
					$insertValues["COMPETITION_ID"]=$pg;
					$tableName="crawler.crawler_detail_view_history";
					break;

				case 'contact_detail_view':
					$insertValues["SITE_ID"]=$this->crawlerSiteObj->getSITE_ID();
                                        $insertValues["ACCOUNT_ID"]=$this->crawlerUserObj->getACCOUNT_ID();
                                        $insertValues["COMPETITION_ID"]=$pg;
					$insertValues["CONTACT_DETAIL_VIEW"]="Y";
                                        $tableName="crawler.crawler_detail_view_history";
                                        break;
			}
			if($error)
			{
				$insertValues["CRAWL_ERROR"]='Y';
				$insertValues["CRAWL_ERROR_MESSAGE"]=addslashes(stripslashes($response["errMsg"]));
				$insertValues["URL"]=addslashes(stripslashes($response["url"]));
				$insertValues["DATA"]=addslashes(stripslashes($response["data"]));
			}
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			if($tableName && is_array($insertValues))
			{
				$fieldList='';
				$valueList='';
				$sql="INSERT INTO $tableName";
				foreach($insertValues as $field=>$value)
				{
					$fieldList.="$field,";
					$valueList.="\"$value\",";
				}
				$fieldList.="TIME";
				$valueList.="NOW()";
				$sql.=" ($fieldList) VALUES($valueList)";
				$mysqlObj->executeQuery($sql,$db);
				$crawlerSessionId=$mysqlObj->insertId();
				echo "\n".$sql;
				//$crawlerSessionId=5;
				unset($mysqlObj);
				return $crawlerSessionId;
			}
		}
	}

	public function getpageNo($siteId)
	{
		if($siteId == 3)
			return $this->crawlerSiteObj->getPaginationParameter($this->pageNo-1);
		else
			return $this->crawlerSiteObj->getPaginationParameter($this->pageNo);
	}

	public function getpageNo2()
	{
		return $this->crawlerSiteObj->getPaginationParameter2($this->pageNo);
	}
	
	public function getactionId()
	{
		return $this->actionId;
	}

	public function getactionId2()
	{
		return $this->actionId2;
	}
}
?>
