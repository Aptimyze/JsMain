<?php
/**
* This class will perform operations related to crteria specified for mailer of jeevansathi.com
*/
class SearchQueryJs
{
	private $searchQuerySpecs;

	/*
	* constructor..
	* @param specs criteria specified.
	* @param id mailerid(unique) of a search.
	*/
	public function __construct($specs='',$id='')
	{
		$this->searchQuerySpecs = $specs;
		if(!$specs && $id)
			$this->searchQuerySpecs = $this->showMailerSpecs($id,'toLowerCase');
	}


	/**
	* This function will count the number of profiles available based on search criteria specified..
	* @param specs criteria specified.
	*/
	public function getExpectedMailsCount()
	{
		try
		{
			$this->convertSearchParametersToRequiredFomat();
			$cnt = $this->countQuery();
			if($cnt > $this->searchQuerySpecs['upper_limit'] && $this->searchQuerySpecs['upper_limit'])
				$cnt = $this->searchQuerySpecs['upper_limit'];
	 		return $cnt;
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

	}


	/**
	* Logs the search criteia in the table..
	*/
	public function logSearchCriteria()
	{
		$mailer_spec = new mmmjs_MAILER_SPECS_JS;
		$mailer_spec->insertEntry($this->searchQuerySpecs);
	}


	/** 
	* This function convert search paratmeter to requrired format..
	**/
	public function convertSearchParametersToRequiredFomat()
	{
		foreach($this->searchQuerySpecs as $key => $value)
		{
			if(is_array($value))
				$this->searchQuerySpecs[$key] = $this->ArraytoString($value);
		}
	}

	/**
	* This function will count the number of results based on search criteria specified.
	* @return $count count of search results specified.
	*/
	private function countQuery()
	{	
		$res = $this->fetchBySearchQuery('COUNT(*) as cnt');
		$count = $res[0]['cnt'];
		return $count;
	}

	/**
	* This function will return the query to be fired for dump
	* @return $count count of search results specified.
	*/
	public function getMysqlDumpQuery($fields)
	{
		$query = $this->fetchBySearchQuery($fields,1,'ordering');
		return $query;
	}

	/**
	* This function return the columns specified and fetch resuts based on search criteria specified.
	* fields columns to be fetched.
	*/
	private function fetchBySearchQuery($fields,$returnOnlySql='',$ordering='')
	{
		$valueArray = array();
		$like = array();

		$valfields = array('gender', 'caste', 'manglik', 'mtongue', 'mstatus', 'havechild', 'btype', 'complexion', 'diet', 'smoke', 'drink', 'handicapped', 'occupation', 'country_res', 'city_res','country_birth', 'res_status', 'edu_level', 'relation', 'havephoto', 'incomplete', 'showphone_res', 'showphone_mob');
		$datefields = array('entry_dt1', 'entry_dt2', 'modify_dt1', 'modify_dt2', 'lastlogin_dt1', 'lastlogin_dt2');
		$lessfields = array('max_age' => 'AGE', 'max_height' => 'HEIGHT', 'entry_dt2' => 'ENTRY_DT', 'modify_dt2' => 'MOD_DT', 'lastlogin_dt2' => 'LAST_LOGIN_DT'); 
		$greaterfields = array('min_age' => 'AGE', 'min_height' => 'HEIGHT', 'entry_dt1' => 'ENTRY_DT', 'modify_dt1' => 'MOD_DT', 'lastlogin_dt1' => 'LAST_LOGIN_DT'); 
		$like_no_like_fields = array('subscription' => 'SUBSCRIPTION');



		/* from form input=> forming arrays where keys are column names of jprofile */
		foreach( $this->searchQuerySpecs as $key => $value)
		{
			if($this->searchQuerySpecs[$key] && in_array(strtolower($key), $valfields) && $value)
				$valueArray[strtoupper($key)] = $this->searchQuerySpecs[$key];
		}
		if(array_key_exists("HAVEPHOTO",$valueArray) && $valueArray['HAVEPHOTO']=="N")
		{
			$excludeArray['HAVEPHOTO']="'Y'";
			unset($valueArray['HAVEPHOTO']);
		}
		if($this->searchQuerySpecs['type'] == 'S')
			$valueArray['SERVICE_MESSAGES'] = 'S';
		else
			$valueArray['PROMO_MAILS'] = 'S';			
		/* from form input=> forming arrays where keys are column names of jprofile */

		if($this->searchQuerySpecs["caste"])
		{
			$revampcaste = new RevampCasteFunctions;
 			$temp = $revampcaste->getAllCastes($this->searchQuerySpecs['caste'], 1);
			$valueArray['CASTE'] = implode(",",$temp);
			unset($temp);
		}

		if($this->searchQuerySpecs['lincome'] || $this->searchQuerySpecs['lincome_dol'] || $this->searchQuerySpecs['lincome']=='0' || $this->searchQuerySpecs['lincome_dol']=='0')
		{
			$rArr["minIR"] = $this->searchQuerySpecs["lincome"];
			$rArr["maxIR"] = $this->searchQuerySpecs["hincome"];
			if($rArr["minIR"]=='0' && $rArr["maxIR"]=='0')
				unset($rArr);
			$dArr["minID"] = $this->searchQuerySpecs["lincome_dol"];
			$dArr["maxID"] = $this->searchQuerySpecs["hincome_dol"];
			if($dArr["minID"]=='0' && $dArr["maxID"]=='0')
				unset($dArr);
			$param='';
			if(($rArr["minIR"] || $rArr["minIR"]=='0') && ($dArr["minID"] || $dArr["minID"]=='0'))
				$param=1;

			if($rArr["minIR"] || $rArr["minIR"]=='0' || $dArr["minID"] || $dArr["minID"]=='0')
			{
				$IncomeMapping =  new IncomeMapping($rArr,$dArr);	
				$tempIncome = implode(",",$IncomeMapping->getAllIncomes($param));
                                if($tempIncome!=15)
                                	$valueArray['INCOME'] = $tempIncome;

			}
		}
		foreach($datefields as $value)
		{
			$this->searchQuerySpecs[$value] = $this->changeDateFormat($this->searchQuerySpecs[$value]);
		}
		
		foreach($lessfields as $key => $value)
		{
			if($this->searchQuerySpecs[$key])
				if($this->searchQuerySpecs[$key] != '0000-00-00')
					$lessThanEqualArrayWithoutQuote[$value] = "'".$this->searchQuerySpecs[$key]."'";
		}
		foreach($greaterfields as $key => $value)
		{
			if($this->searchQuerySpecs[$key])
				if($this->searchQuerySpecs[$key] != '0000-00-00')
					$greaterThanEqualArrayWithoutQuote[$value] = "'".$this->searchQuerySpecs[$key]."'";
		}
		foreach($like_no_like_fields as $key => $value)
		{
			if($this->searchQuerySpecs[$key])
			{
				if($this->searchQuerySpecs[$key]=='N')
					$nolike[$value] = 'F';
				else
					$like[$value] = $this->searchQuerySpecs[$key];
					
			}
		}

		$jprofile = new Jprofile('matchalerts_slave_localhost');

		if($returnOnlySql)
			$fields1='returnOnlySql';
		else
			$fields1=$fields;
		$res = $jprofile->getArray($valueArray, $excludeArray, $greaterThanArray="",$fields1,$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote, $lessThanEqualArrayWithoutQuote, $like,$nolike,"(ACTIVATED!='D')");
		if($returnOnlySql)
		{
			$res = str_replace('returnOnlySql',$fields,$res);
			if($ordering)
			{
                		if($this->searchQuerySpecs['upper_limit'])
	                	{       
        	                	$orderfields = array("MOD_DT", "ENTRY_DT", "LAST_LOGIN_DT");
	                	        foreach($orderfields as $value)
        	                	        if(array_key_exists( $value, $greaterThanEqualArrayWithoutQuote))
                	                	        $orderBy = " $value DESC"; 
	                	        if($orderBy)
        	                	        $res.=" ORDER BY ".$orderBy;

					if(!$this->dumpForTesting)
					{
						if($this->searchQuerySpecs['upper_limit'])
							$res.=" LIMIT ".$this->searchQuerySpecs['upper_limit'];	
					}
	                	}
				if($this->dumpForTesting)
					$res.= " LIMIT 1";
			}
		}
		return $res;
	}

        /**
        * This function will dump the data into table(associated with the mailerid)
        * @param id int mailerId
        */
	public function createDump($mailerId,$testCase='')
	{
		try
		{
			if($testCase)
				$this->dumpForTesting=1;

			$query = $this->createDumpQuery($mailerId);
        	        $individual = new Individual_Mailers;
			$individual->populateTableBasedOnMysqlQuery($mailerId,$query);
			$individual->updateUserNames($mailerId);
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}


        /**
        * This function will return the query to be fired for creating dump.
        * @param id int mailer-id
        * @return query
        */
        private function createDumpQuery($id)
        {
		try
		{
			/*
	                $mailerspec = SearchQueryFactory::getObject('J','',$id);
        	        $query = $this->expectedProfilesCount =  $mailerspec->getMysqlDumpQuery('PROFILEID, EMAIL, USERNAME, PHONE_MOB');
			*/
        	        $query = $this->getMysqlDumpQuery('PROFILEID, EMAIL, USERNAME, PHONE_MOB');
                	return $query;
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }


	public function showMailerSpecs($id,$toLowerCase='')
	{
		try
		{
			$mailer_spec = new mmmjs_MAILER_SPECS_JS;
			$res = $mailer_spec->retrieveEntry($id);

			if(!$res)
				return NULL;
			if(!$toLowerCase)
				return $res;

			foreach($res as $k=>$v)
				if($k!='ID')
					$res1[strtolower($k)] = $v;
			return $res1;
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}

	/**
	* This function will map range to income specified.
	* ?????????? chk new income
	*/ 
	private function mapIncome()
	{
		if(($this->searchQuerySpecs['lincome'] && $this->searchQuerySpecs['hincome'])  && ($this->searchQuerySpecs['lincome_dol'] && $this->searchQuerySpecs['hincome_dol']))
		{	$incomeMapping = new IncomeMapping(array("minIR" => $this->searchQuerySpecs['lincome'], "maxIR" => $this->searchQuerySpecs['hincome']), array("minID" => $this->searchQuerySpecs['lincome_dol'], "maxID" => $this->searchQuerySpecs['hincome_dol']), "B"); //complete it
			$income = $incomeMapping->getAllIncomes(1);
		}
		else if($this->searchQuerySpecs['lincome'] && $this->searchQuerySpecs['hincome'])
		{	$incomeMapping = new IncomeMapping(array("minIR" => $this->searchQuerySpecs['lincome'], "maxIR" => $this->searchQuerySpecs['hincome']),'',"R");
			$income = $incomeMapping->getAllIncomes();				
		}
		else if($this->searchQuerySpecs['lincome_dol'] && $this->searchQuerySpecs['hincome_dol'])
		{	$incomeMapping = new IncomeMapping('', array("minID" => $this->searchQuerySpecs['lincome_dol'], "maxID" => $this->searchQuerySpecs['hincome_dol']),"D");
			$income = $incomeMapping->getAllIncomes();
		}
		$x = implode(",",$income);
		return $x;
		//return $this->ArraytoString($income);
	}


	/**
	* change the date formar as per mmm requiremnet..
	*/
	private function changeDateFormat($date)
	{
		if ($date != NULL)
		{
			$x = explode('/', $date);
			//$y = "'".$x[2].'-'.$x[0].'-'.$x[1]."'";
			$y = $x[2].'-'.$x[0].'-'.$x[1];
			$y = trim($y,'-');
			return $y;
		}
		return $date;
	}


	/**
	* This function will convert array to string.
	*/
	private function ArraytoString($arr)
	{	
		if(is_array($arr))
		{	$str = "";
			foreach($arr as $key => $value)
			{
				if($value != '')
				{	if(is_string($value))
						$str.=','.$value;
					else
						$str.=','.'$value';
				}
			}	
			return substr($str, 1);
		}
		return $arr;
	}
}
?>
