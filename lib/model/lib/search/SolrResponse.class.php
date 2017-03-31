<?php
/*
 * Response of solr search is handled here.
 * @author Lavesh Rawat
 * @created 2012-07-10
 */
class SolrResponse implements ResponseHandleInterface
{
	private $resultType;
	private $responseEngine;
	private $totalResults;
	private $resultsArr;
	private $clusterArr;

	/* Getter functions*/
	function getTotalResults() 
	{ 
		return $this->totalResults; 
	}
	function getSearchResultsPidArr() 
	{ 
		return $this->searchResultsPidArr; 
	}
        function setSearchResultsPidArr($arr) 
	{ 
		$this->searchResultsPidArr=$arr; 
	}
        function setFeturedProfileArr($arr) 
	{ 
		$this->FeaturedResultsPidArr=$arr; 
	}
	function getResultsArr() 
	{ 
		return $this->resultsArr; 
	}
        function setResultsArr($result) 
	{ 
		$this->resultsArr=$result; 
	}
	function getUrlToSave()
	{ 
		return $this->urlToSave;
	}
	function getGroupingResults()
	{ 
		return $this->groupingResults;
	}
        function getGroupingResultsPidArr($sequenceOfOutput='')
        {
                if($sequenceOfOutput)
                {
                        foreach($sequenceOfOutput as $k=>$v)
                        {
                                foreach($v as $kk=>$vv)
                                {
                                        foreach($this->groupingResults[$k][$vv]['id'] as $kkk=>$vvv)
                                                $final[$k][] = $vvv;
                                }
                        }
			/*
			print_r($final);
			print_r($this->groupingResultsPidArr);
			*/
                        return $final;
                }
                else
                        return $this->groupingResultsPidArr;
        }

	function getClustersResults() 
	{ 
		return $this->clusterArr; 
	}
        function getFeturedProfileArr() 
	{ 
		return $this->FeaturedResultsPidArr; 
	}
	public function getResponseEngine()
	{
		return $this->responseEngine;	
	}
	public function getShowAllClustersOptions()
	{
		return $this->showAllClustersOptions;	
	}
	/* Getter functions*/


	/** 
	* Constructor class
	* @param resultType string type of results (example array / xml ..)
	*/
	public function __construct($resultType,$showAllClustersOptions)
	{
		$this->resultType = $resultType;
		$this->responseEngine = 'solr';
		$this->showAllClustersOptions = $showAllClustersOptions;
	}

	/**
	* format search results into a format as specified in constructor.
	* @param res solr-result-array 
	*/
	public function getFormatedResults($res,$urlToSave='',$searchParamtersObj='',$loggedInProfileObj='')
	{
		$res=unserialize($res);	        
		/*
		if($resultType=='array') only implemneted type is array
		*/
		if($res)
		{	
			if($searchParamtersObj && JsConstants::$whichMachine!="matchAlert")
			{
				$pid=0;
				$noOfRes=0;
				$stype=0;
				$time = $res["responseHeader"]["QTime"];	
				if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
					$pid = $loggedInProfileObj->getPROFILEID();
				$stype = $searchParamtersObj->getSEARCH_TYPE();
				if($searchParamtersObj->getNoOfResults())
					$noOfRes  = $searchParamtersObj->getNoOfResults();
				$dt = date("Y-m-d");

				if($dt=="2015-06-17" || $dt =="2015-06-18")
				{
					$channel= MobileCommon::getChannel();
					$search_SEARCH_SOLR_ANALYSIS = new search_SEARCH_SOLR_ANALYSIS;
			                $URL_PATH = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
					$search_SEARCH_SOLR_ANALYSIS->ins($pid,$stype,$noOfRes,$time,$URL_PATH,$channel);
				}
			}
			$this->totalResults = $res['response']['numFound'];
			$this->resultsArr = $res['response']['docs'];
			$this->clusterArr = $res['facet_counts']['facet_fields'];
			$this->urlToSave = $urlToSave;
                        
                        /**
                         * Solr Error Tracking
                         * JIRA  - JSM-455
                         */
                        if($res["error"]["msg"]){
                               $msgReqForDebug[]=$res;
                               $msgReqForDebug[]=$searchParamtersObj;
                               $msgReqForDebug[]=$loggedInProfileObj;
                               $subject="CITY_RES Solr error";
                               //SendMail::send_email("lavesh.rawat@gmail.com,akashkumardtu@gmail.com",print_r($msgReqForDebug,true),$subject);
                        }
                        
			if($res['grouped'])
			{
				foreach($res['grouped'] as $k=>$v)
				{
					foreach($v['groups'] as $kk=>$vv)
					{
						$val = $vv['groupValue'];
						$finalArr[$k][$val]['NUMFOUND'] = $vv['doclist']['numFound'];
						foreach($vv['doclist']['docs'] as $kkk=>$vvv)
						{
							$finalArr[$k][$val]['id'][] = $vvv['id'];
							$this->groupingResultsPidArr[$k][] = $vvv['id'];
						}
					}
				}
				$this->groupingResults = $finalArr;
				unset($finalArr);
			}
			if($res['response']['docs'])
				foreach($res['response']['docs'] as $v)
					$this->searchResultsPidArr[]=$v['id'];
		}
	}
}
