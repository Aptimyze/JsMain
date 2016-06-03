<?php
/**
* @brief This class is used to set search paramters based on user input from top search band / Quick Seach Band.
* @author : Lavesh Rawat
* @package Search
* @subpackage SearchBand
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2012-07-25
*/
class TopSearchBand extends SearchParamters
{	
	/**
	* @access private
	* @var String $marriedEarlier
	*/
	private $marriedEarlier = 'E';
	/**
	* @access private
	* @var String $neverMarried
	*/
	private $neverMarried = 'N';
	/**
	* @access private
	* @var String $stype
	*/
	private $stype;

	public function __construct()
        {
		parent::__construct();
                $channel =  SearchChannelFactory::getChannel();
                $this->stype =  $channel->getSearchTypeQuick();
        }
	/*
        * Sets SearchParamtersObj corresponding to the user search performed in quick search bar /top search band.
        * @access public
        * @param Array $request :request array
        */
	public function getSearchCriteria($request)
	{
		if($request->getParameter('MOBILE_SEARCH')=="Y")
			$this->stype = SearchTypesEnums::MobileSearchBand;
		else
			$this->stype = SearchTypesEnums::Quick;
			
		$searchParamsSetter['GENDER'] = $request->getParameter('gender');
		$searchParamsSetter['LAGE'] = $request->getParameter('lage');
		$searchParamsSetter['HAGE'] = $request->getParameter('hage');
		if($request->getParameter('religion'))
			$searchParamsSetter['RELIGION'] = $request->getParameter('religion');
		if($request->getParameter('caste'))
			$searchParamsSetter['CASTE'] = $request->getParameter('caste');
		$searchParamsSetter['SEARCH_TYPE'] = $this->stype;
		if($request->getParameter('Photos'))
			$searchParamsSetter['HAVEPHOTO'] = $request->getParameter('Photos');

		$mstatus = $request->getParameter('mstatus');
		if($mstatus == $this->marriedEarlier) 
		/* For Married Earlier , all options except never married should be selected*/
		{
			$mstatusArr = FieldMap::getFieldLabel('mstatus','',1);
			unset($mstatus);
			foreach($mstatusArr AS $k=>$v)
			{
				if($k!=$this->neverMarried)
					$mstatus[]=$k;
			}
		}

		if($mstatus)
		{
			if(is_array($mstatus))
				$searchParamsSetter['MSTATUS'] = implode(",",$mstatus);
			else
				$searchParamsSetter['MSTATUS'] = $mstatus;
		}

		if($request->getParameter('mtongue'))
			$searchParamsSetter['MTONGUE'] = $request->getParameter('mtongue');

		$city_country_resArr = $request->getParameter('location');
		if($city_country_resArr)
		{
                        if(array_key_exists($city_country_resArr,FieldMap::getFieldLabel("state_india","","true")))
                        {
                                $searchParamsSetter['STATE'] = $city_country_resArr;
                                $searchParamsSetter['COUNTRY_RES'] = 51;
                        }
			elseif(is_numeric($city_country_resArr))
				$searchParamsSetter['COUNTRY_RES'] = $city_country_resArr;
			else
				$searchParamsSetter['CITY_INDIA'] = $city_country_resArr;
		}
		if($request->getParameter('lheight'))
			$searchParamsSetter['LHEIGHT'] = $request->getParameter('lheight');
		if($request->getParameter('hheight'))
			$searchParamsSetter['HHEIGHT'] = $request->getParameter('hheight');
		
		if($request->getParameter('more_options_btn')=="Y")
		{
			if($request->getParameter('lincome'))
				$searchParamsSetter['LINCOME'] = $request->getParameter('lincome');
			if($request->getParameter('hincome'))
				$searchParamsSetter['HINCOME'] = $request->getParameter('hincome');

			if(($request->getParameter('lincome') || $request->getParameter('lincome')=='0') && ($request->getParameter('hincome') || $request->getParameter('hincome')=='0'))
			{
				$rArr["minIR"] = $request->getParameter('lincome');
                                $rArr["maxIR"] = $request->getParameter('hincome');
                                $incomeType = "R";
                                $incomeMappingObj = new IncomeMapping($rArr,"");
                                $incomeValues = $incomeMappingObj->getAllIncomes();
                                unset($incomeMappingObj);
                                $searchParamsSetter['INCOME'] = implode(",",$incomeValues);
			}

			if($request->getParameter('occupation'))
			{
				$searchParamsSetter['OCCUPATION_GROUPING'] = $request->getParameter('occupation');
				$searchParamsSetter['OCCUPATION'] = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",$request->getParameter('occupation'));
			}
			if($request->getParameter('education'))
			{
				$searchParamsSetter['EDUCATION_GROUPING'] = $request->getParameter('education');
				$searchParamsSetter['EDU_LEVEL_NEW'] = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",$request->getParameter('education'));
			}
			if($request->getParameter('diet'))
			{
				$searchParamsSetter['DIET'] = $request->getParameter('diet');
			}
		}
		$this->setter($searchParamsSetter);
	}
} 
?>
