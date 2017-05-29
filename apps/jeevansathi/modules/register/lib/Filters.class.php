<?php
/**
 *CLASS Filters
 * This class will handle the all the functionalites related to filters set by an user
 * <BR>
 * How to call this file<BR> 
 * <code>
	* $filterObj=new Filters(336);
	* $filterObj->setDPP();
	* $filterObj->getFilters();
* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   registration Revamp
 * @author   Nitesh
 * @copyright 2013 
  */
Class Filters
{
	private $profileId;
	private $filterLAge;
	private $filterHAge;
	private $filterMStatus;
	private $filterReligion;
	private $filterCaste;
	private $filterCountry;
	private $filterCity;
	private $filterMTongue;
	private $filterIncome;
	private $FilterArr;
	private $fromPage;
	
	/*
	 * set FIlters Obj
	 * @param profiledId ,sourcePage from whr it is called
	 */
	function __construct($profileId,$fromPage="")
	{
		
		if($profileId=="")
		return "profileid cannot be null";
		else
		$this->setProfileId($profileId);
		
		if($fromPage)
		$this->setFromPage($fromPage);
		
	}
	//SETTERS
	
	/**
	* set profileId attribute of Filters class
	* @return 
	 * @param profileId int
	 */
	function setProfileId($profileId)
	{
		$this->profileId=$profileId;
	}
	
	/**
	* set fromPage attribute of Filters class
	* @return 
	 * @param fromPage str
	 */
	function setFromPage($fromPage)
	{
		$this->fromPage=$fromPage;
	}
	/**
	* set filterLAge attribute of Filters class
	* @return 
	 * @param filterLAge str
	 */
	function setFilterLAge($filterLAge)
	{
		$this->filterLAge=$filterLAge;
	}
	
	/**
	* set filterHAge attribute of Filters class
	* @return 
	 * @param filterHAge string
	 */
	function setFilterHAge($filterHAge)
	{
		$this->filterHAge=$filterHAge;
	}
	/**
	* set filterMStatus attribute of Filters class
	* @return 
	 * @param filterMStatus string
	 */
	function setFilterMStatus($filterMStatus)
	{
		$this->filterMStatus=$filterMStatus;
	}
	/**
	* set SourcePage attribute of Filters class
	* @return 
	 * @param sourcePage string
	 */
	function setFilterReligion($filterReligion)
	{
		$this->filterReligion=$filterReligion;
	}
	/**
	* set filterCaste attribute of Filters class
	* @return 
	 * @param filterCaste string
	 */
	function setFilterCaste($filterCaste)
	{
		$this->filterCaste=$filterCaste;
	}
	/**
	* set filterCountry attribute of Filters class
	* @return 
	 * @param filterCountry string
	 */
	function setFilterCountry($filterCountry)
	{
		$this->filterCountry=$filterCountry;
	}
	/**
	* set filterCity attribute of Filters class
	* @return 
	 * @param filterCity string
	 */
	function setFilterCity($filterCity)
	{
		$this->filterCity=$filterCity;
	}
	/**
	* set filterMTongue attribute of Filters class
	* @return 
	 * @param filterMTongue string
	 */
	function setFilterMTongue($filterMTongue)
	{
		$this->filterMTongue=$filterMTongue;
	}
	/**
	* set filterIncome attribute of Filters class
	* @return 
	 * @param filterIncome string
	 */
	function setFilterIncome($filterIncome)
	{
		$incomeStr=str_replace("</br>","&nbsp;&nbsp;&nbsp;",$filterIncome);
		$this->filterIncome=$incomeStr;
	}
	
	/**
	* set filterArr attribute of Filters class
	* @return 
	 * @param filterArr string
	 */
	function setFilterArr($filterArr)
	{
		$this->filterArr=$filterArr;
	}
	
	
	//GETTERS

	/**
	* return profileId attribute of Filters Object
	* @return profileId string
	*
	*/
	function getProfileId()
	{
		return $this->profileId;
	}
	
	/**
	* get filterLAge attribute of Filters class
	* @return   filterLAge str
	 */
	function getFilterLAge()
	{
		return $this->filterLAge;
	}
	
	/**
	* get filterHAge attribute of Filters class
	* @return   filterHAge string
	 */
	function getFilterHAge()
	{
		return $this->filterHAge;
	}
	/**
	* get filterMStatus attribute of Filters class
	* @return   filterMStatus string
	 */
	function getFilterMStatus()
	{
		return $this->filterMStatus;
	}
	/**
	* get SourcePage attribute of Filters class
	* @return   sourcePage string
	 */
	function getFilterReligion()
	{
		return $this->filterReligion;
	}
	/**
	* get filterCaste attribute of Filters class
	* @return   filterCaste string
	 */
	function getFilterCaste()
	{
		return $this->filterCaste;
	}
	/**
	* get filterCountry attribute of Filters class
	* @return   filterCountry string
	 */
	function getFilterCountry()
	{
		return $this->filterCountry;
	}
	/**
	* get filterCity attribute of Filters class
	* @return   filterCity string
	 */
	function getFilterCity()
	{
		return $this->filterCity;
	}
	/**
	* get filterMTongue attribute of Filters class
	* @return   filterMTongue string
	 */
	function getFilterMTongue()
	{
		return $this->filterMTongue;
	}
	
	/**
	* get filterIncome attribute of Filters class
	* @return   filterIncome string
	 */
	function getFilterIncome()
	{
		return $this->filterIncome;
	}
	
	/**
	* get filterArr attribute of Filters class
	* @return filterArr string
	 */
	function getFilterArr()
	{
		return $this->filterArr;
	}
	
	
	/**
	* Setting Current Dpp of all the Filters fields of the user.
	* @return
	 * @param $profileIdfrompage and source attribute should be set before calling this fucntion
	 */
	
	function currentFilters()
	{
		$dbFilters= new ProfileFilter();
		$this->setFilterArr($dbFilters->fetchEntry($this->profileId));
	}
	/**
	 * Setting Current Dpp of all the Filters fields of the user.
	 * @profileid profileid of user
	 * @return 
	 *         null if jpartner not found.
	 */
	public function setDpp()
	{
		$paramStr=SourceTrackingEnum::$filterFieldsStr;
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		$jpartnerObj=new JPartnerDecorated();
		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($this->profileId,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
		$jpartnerObj->setPartnerDetails($this->profileId,$myDb,$mysqlObj,$paramStr);
		$request=sfContext::getInstance()->getRequest();	
		
		//PARTNER_LAGE,PARTNER_HAGE:
		
			//LAGE
		$this->setFilterLAge($jpartnerObj->getDecoratedLAGE());
		if($this->filterLAge=='')
		{
			if($jpartnerObj->getGender()=='M')
				$this->setFilterLAge(21);
			else
				$this->setFilterLAge(18);
		}
		 	
			//HAGE
		$this->setFilterHAge($jpartnerObj->getDecoratedHAGE());
		if($this->filterHAge=='')
			$this->setFilterHAge(70);
		
		//PARTNER_MSTATUS:		
		$this->setFilterMstatus($jpartnerObj->getDecoratedPARTNER_MSTATUS());
				
		//PARTNER_CITY:
		$this->setFilterCity($jpartnerObj->getDecoratedPARTNER_CITYRES());
		
		//PARTNER_CASTE
		$this->setFilterCaste($jpartnerObj->getDecoratedPARTNER_CASTE());
		
		//PARTNER_INCOME
		$this->setFilterIncome($jpartnerObj->getDecoratedPARTNER_INCOME());
		
		//RELIGION
		$this->setFilterReligion($jpartnerObj->getDecoratedPARTNER_RELIGION());
		
		//PARTNER_COUNTRY
		$this->setFilterCountry($jpartnerObj->getDecoratedPARTNER_COUNTRYRES());
		
		//PARTNER_MTONGUE
		$this->setFilterMtongue($jpartnerObj->getDecoratedPARTNER_MTONGUE());
		//print_r($this);
	}
	
	function submitFilters($actionParamsArr,$crmbackArr,$assistedProductOnline)
	{
		if($actionParamsArr["Submit"])
		{		
			if($actionParamsArr["selectId"])
			{
				$filterParam=array("age_filter"=>"AGE", "city_res_filter"=>"CITY_RES","country_res_filter"=>"COUNTRY_RES", "mtongue_filter"=>"MTONGUE","caste_filter"=>"CASTE","mstatus_filter"=>"MSTATUS","income_filter"=>"INCOME","religion_filter"=>"RELIGION");
				$possArr = array("Y","N");

				$updStr="";
				
				//$submit=$actionParamsArr["submit"]
				unset($actionParamsArr["Submit"]);
				//$selectId=$actionParamsArr["selectId"]
				unset($actionParamsArr["selectId"]);
				$noFilter=$actionParamsArr["noFilter"];
				unset($actionParamsArr["noFilter"]);
				$NOT_UPDATE_HARDSOFT=$actionParamsArr["NOT_UPDATE_HARDSOFT"];
				unset($actionParamsArr["NOT_UPDATE_HARDSOFT"]);
				
				$dbFilters= new ProfileFilter();
				
				foreach($actionParamsArr as $key=>$val)
				{
					if(!in_array($val,$possArr))
					{
						include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
						ValidationHandler::getValidationHandler("","Filters.class.php");
						$val = 'N';
					}

					if($filterParam[$key])
					{			
						$updStr.="$filterParam[$key]="."'$val',";
						if($assistedProductOnline)
						$updStrAP.="$filterParam[$key]_FILTER="."'$val',";
					}
					elseif($key=="Submit")
						return($key."A_E");
				}

				if(count($actionParamsArr)===1)
				{	
						$spanid=substr($updStr,0,strpos($updStr,'=',0)).'_';
				}
				if($noFilter){
					$updStr="COUNT=COUNT+5,HARDSOFT='Y'";
					
				}
				else
				{
					if($NOT_UPDATE_HARDSOFT)
					{
						$hardSoftStr="HARDSOFT=HARDSOFT";
					}
					else
					{
						$hardSoftStr="HARDSOFT='Y'";
					}
				}
				$affectedRows=$dbFilters->updateFilters($this->profileId,$updStr.$hardSoftStr);
				if(!$affectedRows)
				{
					$hardSoftStr="HARDSOFT='Y'";
					$dbFilters->insertFilterEntry($this->profileId,$updStr.$hardSoftStr);
				}
			}
			// added To maintain a log of changes made to Filters set by member by BackeEnd User
			if($crmbackArr["admin"]=='admin')
			{
				$comments="INITIAL FILTERS SET :";	
				$comments.="<br>"." AGE :".$this->filterArr["AGE"];
				$comments.="<br>"." MARITAL STATUS :".$this->filterArr["MSTATUS"];
				$comments.="<br>"." RELIGION :".$this->filterArr["RELIGION"];
				$comments.="<br>"." COMMUNITY :".$this->filterArr["MTONGUE"];
				$comments.="<br>"." COUNTRY :".$this->filterArr["COUNTRY_RES"];
				$comments.="<br>"." CITY :".$this->filterArr["CITY_RES"];
				$comments.="<br>"." CASTE :".$this->filterArr["CASTE"];
				$comments.="<br>"." INCOME :".$this->filterArr["INCOME"];
				$comments.="<br>"."MODIFIED FILTERS  :";

				if ($actionParamsArr["age_filter"]!= $this->filterArr["AGE"])
					$comments.="<br>"." Changed Age From"."<b>".$this->filterArr["AGE"]."</b>"." to "."<b>".$actionParamsArr["age_filter"]."</b>";
				if ($actionParamsArr["mstatus_filter"]!= $this->filterArr["MSTATUS"])
					$comments.="<br>"." Changed Marital Status From "."<b>".$this->filterArr["MSTATUS"]."</b>"." to "."<b>".$actionParamsArr["mstatus_filter"]."</b>";
				if ($actionParamsArr["religion_filter"]!= $this->filterArr["RELIGION"])
					$comments.="<br>"." Changed Religion Filter From "."<b>".$this->filterArr["RELIGION"]."</b>"." to "."<b>".$actionParamsArr["religion_filter"]."</b>";
				if ($actionParamsArr["mtongue_filter"]!= $this->filterArr["MTONGUE"])
					$comments.="<br>"." Changed Community Filter From "."<b>".$this->filterArr["MTONGUE"]."</b>"." to "."<b>".$actionParamsArr["mtongue_filter"]."</b>";
				if ($actionParamsArr["country_filter"]!= $this->filterArr["COUNTRY_RES"])
					$comments.="<br>"." Changed Country Filter From "."<b>".$this->filterArr["COUNTRY_RES"]."</b>"." to "."<b>".$actionParamsArr["country_filter"]."</b>";
				if ($actionParamsArr["city_filter"]!= $this->filterArr["CITY_RES"])
					$comments.="<br>"." Changed City Filter From "."<b>".$this->filterArr["CITY_RES"]."</b>"." to "."<b>".$actionParamsArr["city_filter"]."</b>";
				if ($actionParamsArr["caste_filter"]!=$this->filterArr["CASTE"])
					$comments.="<br>"." Changed Caste Filter From "."<b>".$this->filterArr["CASTE"]."</b>"." to "."<b>".$actionParamsArr["caste_filter"]."</b>";
				if ($actionParamsArr["income_filter"]!=$this->filterArr["INCOME"])
					$comments.="<br>"." Changed Income Filter From "."<b>".$this->filterArr["INCOME"]."</b>"." to "."<b>".$actionParamsArr["income_filter"]."</b>";
				$crmuser = CommonFunction::getCrmUserName($crmbackArr["cid"]);
				
				if (!$crmbackArr["company"])
					$COMPANY = 'JS';
				else
					$COMPANY =$crmbackArr["company"];
				
				$dbProfileChangeLog= new PROFILECHANGE_LOG();
				$dbProfileChangeLog->insertChangesDone($crmuser,$this->profileId,$comments,$COMPANY);
			}
			

				//For AP users Handling
			if($assistedProductOnline)
			{
				$dbApDppFilterArchive= new AP_DPP_FILTER_ARCHIVE();
				
				$whrStr="AND STATUS NOT IN('OBS')";
				$dbApDppFilterArchive->updateDPP($this->profileId,trim($updStrAP,","),$whrStr);
				
				$whrStr="";
				$dbApTempDpp= new ASSISTED_PRODUCT_AP_TEMP_DPP();
				$dbApTempDpp->updateDPP($this->profileId,trim($updStrAP,","),$whrStr);
			}
				
			if($spanid)
			return($spanid);
			else
			return($this->filterArr["FILTERID"]);
		}
	}
	
	
}


?>
