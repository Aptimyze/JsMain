<?php
/**
 * This class perform all operation related to handling of search paramters.Peform operations like search , clusters. 
 * @author Lavesh Rawat
 * @created 2012-05-30
 */
class SphinxRequest implements RequestHandleInterface
{
	private $responseObj;
	private $searchParamtersObj;
	private $clientObj;
	private $searchedGenderMale='M';
	private $filters;
	private $results;
	private $filtersRange;
	private $sortOrder;
	private $offset;
	private $limit;
	private $intWhereArr;
	private $asciiWhereArr;
	private $rangeWhereArr;
	private $crcWhereArr;
	private $groupByFieldAr;
	private $clutserResults;
	private $clustersArr;

	/**
	* constructor of sphinx Request class
	* @param responseObj contains information about output type (array/xml/...) and engine used(sphinx/lucene/mysql..)
	* @param searchParamtersObj search paramters object.
	*/
	public function __construct($responseObj,$searchParamtersObj)
	{
		$this->responseObj = $responseObj;
		$this->searchParamtersObj = $searchParamtersObj;
		$this->clientObj = new SphinxClient();
		$this->clientObj();
		$this->offset = 0 ;
		$this->limit = 10; //100
	}

	/**
	* Using detail of server where sphinx is installed. ( ip / port) , a client object is created.
	*/	
	public function clientObj()
	{
		$this->clientObj->SetServer('172.16.3.185','3314');
	}

	/**
	* This function is used to get search results.
	* @param results_cluster string options are onlyClusters(calculate clusters only) /onlyResult(calculate results only)
	* @return responseObj object-array containing info like (ResultsArray / totalResults)
	*/	
	public function getResults($results_cluster='all')
	{
		$this->setIndexName();
		$this->setWhereCondition();

		if($results_cluster!='onlyClusters')	
		{
	                $this->clientObj->SetLimits ($this->offset,$this->limit);  // --> variable
        	        $this->clientObj->AddQuery ($this->queryString,$this->sphinxIndexName);
                	$this->results = $this->clientObj->RunQueries();
			$this->responseObj->getFormatedResults($this->results);
		}
		if($results_cluster!='onlyResults')
			$this->getClusters();

		return $this->responseObj;

	}


	/**
	* This function set the cluster results into responseObj
	*/
	function getClusters()
	{
		/**
		* array of Clusters to be displayed.
		*/
		$this->groupByFieldArr = array('LAST_ACTIVITY','RELATION','MSTATUS','HAVECHILD','HEIGHT','MTONGUE','RELIGION','CASTE','EDUCATION_GROUPING','INCOME','OCCUPATION_GROUPING','INDIA_NRI','MANGLIK','DIET','VIEWED','HAVEPHOTO','HOROSCOPE','HANDICAPPED','HIV','AGE','GOING_ABROAD','MARRIED_WORKING');

		$setLimitValue = 100;
		$this->clientObj->_maxmatches=$setLimitValue;
                $this->clientObj->SetLimits (0,50); 

		foreach($this->groupByFieldArr as $v)
		{
			if(!in_array($v,SearchConfig::$sliderClusters))
			{
				$this->clientObj->SetGroupBy($v, SPH_GROUPBY_ATTR,"@count desc");
				$this->clientObj->AddQuery ($this->queryString,$this->sphinxIndexName);	
			}
		}
		$this->clutserResults = $this->clientObj->RunQueries();
		$this->responseObj->getFormatedClusterResults($this->clutserResults,$this->groupByFieldArr,$this->searchParamtersObj);
	}


	/**
	* based on paramters(gender) we determine and set the index to be used for searching.
	*/
	public function setIndexName()
	{
		if($this->searchParamtersObj->getGENDER()==$this->searchedGenderMale)
		{
			$this->sphinxIndexName="SEARCH_MALE_KWD";
			$this->queryString = 'male';
		}
		else
		{
			$this->sphinxIndexName="SEARCH_FEMALE_KWD";
			$this->queryString = 'female';
		}
	}


	/**
	* Filtering condistion are set here by appending it to clientObj.
	* we have divided filtering conditions in 4 part : (1)Where conditions (2)Range conditions (3)ascii where conditions - columns having non integer values (4)crc - for strings
	*/
	public function setWhereCondition()
	{
		$this->intWhereArr = explode(",",SearchConfig::$sphinxWhereParameters);
		$this->asciiWhereArr = explode(",",SearchConfig::$sphinxAsciiWhereParameters);
		$this->rangeWhereArr = explode(",",SearchConfig::$sphinxRangeWhereParameters);
		$this->crcWhereArr = explode(",",SearchConfig::$sphinxCrcWhereParameters);

		if(is_array($this->intWhereArr))
		foreach($this->intWhereArr as $field)
		{
			eval('$value = $this->searchParamtersObj->get'.$field.'();');
			if($value)
			{
				$valueArr=explode(",",$value);
				$this->filters[$field]=$valueArr;
			}
		}


		if(is_array($this->asciiWhereArr))
		foreach($this->asciiWhereArr as $field)
		{
			eval('$value = $this->searchParamtersObj->get'.$field.'();');
			if($value) //chk for blank
			{
				$valueArr = explode(",",$value);
				$valueArr2[] =$this->stringArr_to_asciiArr($valueArr);
				$this->filters[$field] = $valueArr2[0];
				unset($valueArr);
				unset($valueArr2);
			}
		}


		if(is_array($this->crcWhereArr))
		foreach($this->crcWhereArr as $field)
		{
			eval('$value = $this->searchParamtersObj->get'.$field.'();');
			if($value)
			{
				$valueArr=explode(",",$value);
		                foreach($valueArr as $k=>$v)
	                        {
                 	               $cities[]=sprintf("%u",crc32($v));
        	                }
				$this->filters[$field]=$cities;
			}
		}


		if(is_array($this->rangeWhereArr))
		foreach($this->rangeWhereArr as $field)
		{
			eval('$lvalue = $this->searchParamtersObj->getL'.$field.'();');
			eval('$hvalue = $this->searchParamtersObj->getH'.$field.'();');
			if($lvalue && $hvalue)
			{
				$this->filtersRange[$field][0]=$lvalue;
				$this->filtersRange[$field][1]=$hvalue;
				$this->sortOrder[]="IF(INTERVAL($field,$lvalue,$hvalue)=1,1,0)";
			}
		}

                if(is_array($this->filters)) //-->where in
                        foreach($this->filters as $k=>$v)
                                $this->clientObj->SetFilter($k,$v);
                if(is_array($sphinxNotFilters)) //-->where not in 
                        foreach($sphinxNotFilters as $k=>$v)
                                $this->clientObj->SetFilter($k,$v,true);
                if(is_array($this->filtersRange)) // where between 
                        foreach($this->filtersRange as $k=>$v)
                                $this->clientObj->SetFilterRange ($k,$v[0],$v[1]);
		//$this->kachra();
	}

	/**
	* NEED TO MOVE
	* convert string value array to ascii
	*/
	function stringArr_to_asciiArr($arr,$skipp_number="")
	//Chk use of skipp_number in HAVECHILD
	{
		if(is_array($arr))
		{
			foreach($arr as $v)
			{
				if($skipp_number)
					$newarr[]=ord(substr($v,-1));
				else
					$newarr[]=ord($v);
			}
		}
		elseif($arr)
			$newarr[]=ord($arr);
		return $newarr;
	}
	/* set parameters for searching in sphinx */
	function kachra()
	{
		if(is_array($this->filtersRange))
			foreach($this->filtersRange as $k=>$v)
				$xxx[]="$k BETWEEN $v[0] AND $v[1]";

		if(is_array($this->filters))
		foreach($this->filters as $k=>$v)
		{
			if(in_array($k,$this->crcWhereArr))
			{
				$v=$this->searchParamtersObj->getCITY_RES();
				$vnew=explode(",",$v);
				$v=$vnew;
				unset($vnew);
				
			}
			if(in_array($k,$this->asciiWhereArr))
			{
				foreach($v as $val)
					$vnew[]=chr($val);
				$v=$vnew;
				unset($vnew);
			}
			$xxx[]=$k." IN ('".implode("','",$v)."')";
		}
		if(is_array($xxx))
//		echo $abc = implode(" AND ",$xxx);	
	}
}
