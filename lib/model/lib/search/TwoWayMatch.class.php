<?php
/**
* This file will be handle two way match requirements.
* Two way match is combination of forward(limited params) + reverse (limited params)
* @author Lavesh Rawat
* @package Search
* @subpackage SearchTypes
* @copyright 2014 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2014-08-01
*/
class TwoWayMatch extends SearchParamters
{
        private $lAgeMin;
        private $hAgeMax = 70;
        private $lHeightMin = 1;
        private $hHeightMax = 37;
        private $PARTNER_MTONGUE;
        private $PARTNER_CASTE;
        private $PARTNER_RELIGION;
        private $PARTNER_COUNTRYRES;
        private $PARTNER_BTYPE;
        private $PARTNER_COMP;
        private $PARTNER_ELEVEL_NEW;
        private $PARTNER_INCOME;
        private $PARTNER_OCC;
        private $LPARTNER_LAGE;
        private $HPARTNER_LAGE;
        private $LPARTNER_HAGE;
        private $HPARTNER_HAGE;
        private $LPARTNER_LHEIGHT;
        private $HPARTNER_LHEIGHT;
        private $LPARTNER_HHEIGHT;
        private $HPARTNER_HHEIGHT;
        private $PARTNER_MSTATUS;
        private $PARTNER_CITYRES;
        private $PARTNER_DRINK;
        private $PARTNER_SMOKE;
        private $PARTNER_DIET;
        private $PARTNER_HANDICAPPED;
        private $PARTNER_MANGLIK;

	private $forwardParamsFemaleLoggedIn = array('GENDER','LHEIGHT','HHEIGHT','LAGE','HAGE','MSTATUS','COUNTRY_RES','CITY_RES','DIET','SMOKE','DRINK','HANDICAPPED','NATURE_HANDICAP','CASTE','RELIGION','MTONGUE','MANGLIK','OCCUPATION','OCCUPATION_GROUPING','INCOME','EDU_LEVEL_NEW');
	private $forwardParamsMaleLoggedIn = array('GENDER','LHEIGHT','HHEIGHT','LAGE','HAGE','MSTATUS','COUNTRY_RES','CITY_RES','DIET','SMOKE','DRINK','HANDICAPPED','NATURE_HANDICAP','CASTE','RELIGION','MTONGUE','MANGLIK','OCCUPATION','OCCUPATION_GROUPING','EDU_LEVEL_NEW','INCOME');

	private $reverseParamsFemaleLoggedIn = array('PARTNER_MSTATUS','PARTNER_COUNTRYRES','PARTNER_HANDICAPPED','PARTNER_RELIGION','PARTNER_CASTE','LPARTNER_LAGE','HPARTNER_LAGE','LPARTNER_HAGE','HPARTNER_HAGE','LPARTNER_LHEIGHT','HPARTNER_LHEIGHT','LPARTNER_HHEIGHT','HPARTNER_HHEIGHT','PARTNER_ELEVEL_NEW','PARTNER_MANGLIK','PARTNER_INCOME');
	private $reverseParamsMaleLoggedIn = array('PARTNER_MSTATUS','PARTNER_COUNTRYRES','PARTNER_HANDICAPPED','PARTNER_RELIGION','PARTNER_CASTE','LPARTNER_LAGE','HPARTNER_LAGE','LPARTNER_HAGE','HPARTNER_HAGE','LPARTNER_LHEIGHT','HPARTNER_LHEIGHT','LPARTNER_HHEIGHT','HPARTNER_HHEIGHT','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_ELEVEL_NEW');

	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj)
        {
		parent::__construct();
                $this->possibleSearchParamters = SearchConfig::$possibleSearchParamters;
		$this->loggedInProfileObj = $loggedInProfileObj;
               	$this->pid =  $this->loggedInProfileObj->getPROFILEID();

		if($this->loggedInProfileObj->getGENDER()=='F')
		{
			$this->forwardParams = $this->forwardParamsFemaleLoggedIn;
			$this->reverseParams = $this->reverseParamsFemaleLoggedIn;
		}
		else
		{
			$this->forwardParams = $this->forwardParamsMaleLoggedIn;
			$this->reverseParams = $this->reverseParamsMaleLoggedIn;
		}

		if(!$this->pid)
		{
			$context = sfContext::getInstance();
			$context->getController()->forward("static", "logoutPage"); //Logout page
			throw new sfStopException();
		}
        }

	/*
	* This function will set the criteria for forward and reverse search.
	*/
	public function getSearchCriteria($searchId='')
	{
                if(!$searchId)
		{
			$forwardCriteria = PredefinedSearchFactory::getSetterBy('PartnerProfile',$this->loggedInProfileObj);
			$forwardCriteria->getDppCriteria();
			foreach($this->forwardParams as $k=>$v)	
			{
				eval('$tempVal = $forwardCriteria->get'.$v.'();');
				if($tempVal)
					eval('$this->set'.$v.'("'.$tempVal.'");');
			}
		}


		$reverseCriteria = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$this->loggedInProfileObj);
		$reverseCriteria->getSearchCriteria();
		foreach($this->reverseParams as $k=>$v)	
		{
			eval('$tempVal = $reverseCriteria->get'.$v.'();');
			if($tempVal)
				eval('$this->set'.$v.'("'.$tempVal.'");');
		}
                $channel =  SearchChannelFactory::getChannel();
		$this->stype =  $channel::getSearchTypeTwoWayMatches();
		
                $this->setSEARCH_TYPE($this->stype);
		$this->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$membersLookingForMeWhereParameters);
		$this->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters);

                if($searchId)
                {
                        $paramArr['ID'] = $searchId;
                        $SEARCHQUERYobj = new SEARCHQUERY(SearchConfig::getSearchDb());
                        $arr = $SEARCHQUERYobj->get($paramArr,SearchConfig::$possibleSearchParamters);

                        if(is_array($arr[0]))
                        {
                                foreach($arr[0] as $field=>$value)
                                {
                                        if(strstr(SearchConfig::$possibleSearchParamters,$field))
                                                eval ('$this->set'.$field.'($value);');
                                }
                                unset($arr);
                        }
                }
	}

	public function getPARTNER_MTONGUE() { return $this->PARTNER_MTONGUE; }
        public function setPARTNER_MTONGUE($x) { $this->PARTNER_MTONGUE = $x; }
        public function getPARTNER_CASTE() { return $this->PARTNER_CASTE; }
        public function setPARTNER_CASTE($x) { $this->PARTNER_CASTE = $x; }
        public function getPARTNER_RELIGION() { return $this->PARTNER_RELIGION; }
        public function setPARTNER_RELIGION($x) { $this->PARTNER_RELIGION = $x; }
        public function getPARTNER_COUNTRYRES() { return $this->PARTNER_COUNTRYRES; }
        public function setPARTNER_COUNTRYRES($x) { $this->PARTNER_COUNTRYRES = $x; }
        public function getPARTNER_BTYPE() { return $this->PARTNER_BTYPE; }
        public function setPARTNER_BTYPE($x) { $this->PARTNER_BTYPE = $x; }
        public function getPARTNER_COMP() { return $this->PARTNER_COMP; }
        public function setPARTNER_COMP($x) { $this->PARTNER_COMP = $x; }
        public function getPARTNER_ELEVEL_NEW() { return $this->PARTNER_ELEVEL_NEW; }
        public function setPARTNER_ELEVEL_NEW($x) { $this->PARTNER_ELEVEL_NEW = $x; }
        public function getPARTNER_INCOME() { return $this->PARTNER_INCOME; }
        public function setPARTNER_INCOME($x) { $this->PARTNER_INCOME = $x; }
        public function getPARTNER_OCC() { return $this->PARTNER_OCC; }
        public function setPARTNER_OCC($x) { $this->PARTNER_OCC = $x; }
        public function getLPARTNER_LAGE() { return $this->LPARTNER_LAGE; }
        public function setLPARTNER_LAGE($x) { $this->LPARTNER_LAGE = $x; }
        public function getHPARTNER_LAGE() { return $this->HPARTNER_LAGE; }
        public function setHPARTNER_LAGE($x) { $this->HPARTNER_LAGE = $x; }
        public function getLPARTNER_HAGE() { return $this->LPARTNER_HAGE; }
        public function setLPARTNER_HAGE($x) { $this->LPARTNER_HAGE = $x; }
        public function getHPARTNER_HAGE() { return $this->HPARTNER_HAGE; }
        public function setHPARTNER_HAGE($x) { $this->HPARTNER_HAGE = $x; }
	public function getLPARTNER_LHEIGHT() { return $this->LPARTNER_LHEIGHT; }
        public function setLPARTNER_LHEIGHT($x) { $this->LPARTNER_LHEIGHT = $x; }
        public function getHPARTNER_LHEIGHT() { return $this->HPARTNER_LHEIGHT; }
        public function setHPARTNER_LHEIGHT($x) { $this->HPARTNER_LHEIGHT = $x; }
        public function getLPARTNER_HHEIGHT() { return $this->LPARTNER_HHEIGHT; }
        public function setLPARTNER_HHEIGHT($x) { $this->LPARTNER_HHEIGHT = $x; }
        public function getHPARTNER_HHEIGHT() { return $this->HPARTNER_HHEIGHT; }
        public function setHPARTNER_HHEIGHT($x) { $this->HPARTNER_HHEIGHT = $x; }
        public function getPARTNER_MSTATUS() { return $this->PARTNER_MSTATUS; }
        public function setPARTNER_MSTATUS($x) { $this->PARTNER_MSTATUS = $x; }
        public function getPARTNER_CITYRES() { return $this->PARTNER_CITYRES; }
        public function setPARTNER_CITYRES($x) { $this->PARTNER_CITYRES = $x; }
        public function getPARTNER_DRINK() { return $this->PARTNER_DRINK; }
        public function setPARTNER_DRINK($x) { $this->PARTNER_DRINK = $x; }
        public function getPARTNER_SMOKE() { return $this->PARTNER_SMOKE; }
        public function setPARTNER_SMOKE($x) { $this->PARTNER_SMOKE = $x; }
        public function getPARTNER_DIET() { return $this->PARTNER_DIET; }
        public function setPARTNER_DIET($x) { $this->PARTNER_DIET = $x; }
        public function getPARTNER_HANDICAPPED() { return $this->PARTNER_HANDICAPPED; }
        public function setPARTNER_HANDICAPPED($x) { $this->PARTNER_HANDICAPPED = $x; }
        public function getPARTNER_MANGLIK() { return $this->PARTNER_MANGLIK; }
        public function setPARTNER_MANGLIK($x) { $this->PARTNER_MANGLIK = $x; }
}
?>
