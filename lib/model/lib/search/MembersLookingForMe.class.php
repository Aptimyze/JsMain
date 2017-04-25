<?php
class MembersLookingForMe extends SearchParamters
{
	private $loggedInProfileObj;
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

	public function __construct($loggedInProfileObj)
	{
		parent::__construct();
		$this->loggedInProfileObj = $loggedInProfileObj;
		if($this->loggedInProfileObj && $this->loggedInProfileObj->getPROFILEID())
                {
			if($this->loggedInProfileObj->getGENDER()=="M")
				$this->lAgeMin = 21;
			elseif($this->loggedInProfileObj->getGENDER()=="F")
				$this->lAgeMin = 18;
		}
	}
	
	public function getSearchCriteria($searchId='')
	{
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

		if($this->loggedInProfileObj && $this->loggedInProfileObj->getPROFILEID())
		{
			$membersLookingForMeSearchParametersArr = explode(",",SearchConfig::$possibleMembersLookingForMeSearchParameters);
			foreach($membersLookingForMeSearchParametersArr as $k=>$v)
			{
				if($v=="PARTNER_COUNTRYRES")
					$value = $this->loggedInProfileObj->getCOUNTRY_RES();
				elseif($v=="PARTNER_CITYRES")
					$value = $this->loggedInProfileObj->getCITY_RES();
				elseif($v=="PARTNER_COMP")
					$value = $this->loggedInProfileObj->getCOMPLEXION();
				elseif($v=="PARTNER_OCC")
					$value = $this->loggedInProfileObj->getOCCUPATION();
				elseif($v=="PARTNER_MANGLIK"){
                                        if(!$this->loggedInProfileObj->getMANGLIK()){
                                                $value = 'N';
                                        }else{
                                                $value = $this->loggedInProfileObj->getMANGLIK();
                                        }
                                }elseif($v=="PARTNER_ELEVEL_NEW"){
					$value = $this->loggedInProfileObj->getEDU_LEVEL_NEW();
                                        $ugPg = $this->loggedInProfileObj->getEducationDetail(1,SearchConfig::getSearchDb());
                                        if(!empty($ugPg)){
                                                if($ugPg["PG_DEGREE"])
                                                      $value .= " "  .$ugPg["PG_DEGREE"];
                                                if($ugPg["UG_DEGREE"])
                                                      $value .= " "  .$ugPg["UG_DEGREE"];
                                        }
                                }elseif($v=="PARTNER_LAGE" || $v=="PARTNER_HAGE")
					$value = $this->loggedInProfileObj->getAGE();
				elseif($v=="PARTNER_LHEIGHT" || $v=="PARTNER_HHEIGHT")
					$value = $this->loggedInProfileObj->getHEIGHT();
				elseif($v=="PARTNER_INCOME"){
                                        eval('$value = $this->loggedInProfileObj->get'.substr($v,8).'();');
                                        $imObj = new IncomeMapping;
                                        $incomeArray = $imObj->getLowerIncomes($value);
                                        $incomeArray = $imObj->removeNoIncome($incomeArray);
                                        unset($imObj);
                                        $value = implode(" ",$incomeArray);
                                }else
					eval('$value = $this->loggedInProfileObj->get'.substr($v,8).'();');
			
				if($value)
				{
					if($v=="PARTNER_LAGE")
					{
						eval ('$this->setL'.$v.'('.$this->lAgeMin.');');
						eval ('$this->setH'.$v.'('.$value.');');
					}
					elseif($v=="PARTNER_HAGE")
					{
						eval ('$this->setL'.$v.'('.$value.');');
						eval ('$this->setH'.$v.'('.$this->hAgeMax.');');
					}
					elseif($v=="PARTNER_LHEIGHT")
					{
						eval ('$this->setL'.$v.'('.$this->lHeightMin.');');
						eval ('$this->setH'.$v.'('.$value.');');
					}
					elseif($v=="PARTNER_HHEIGHT")
					{
						eval ('$this->setL'.$v.'('.$value.');');
						eval ('$this->setH'.$v.'('.$this->hHeightMax.');');
					}
					else
						eval ('$this->set'.$v.'("'.$value.',99999");');
				}
			}
			
			if($this->loggedInProfileObj->getGENDER()=="M")
				$this->setGENDER("F");
			elseif($this->loggedInProfileObj->getGENDER()=="F")
				$this->setGENDER("M");
                        
                        $channel =  SearchChannelFactory::getChannel();
                        $this->stype =  $channel::getSearchTypeMembersLookingForMe();
			
                        $this->setSEARCH_TYPE($this->stype);
			$this->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$membersLookingForMeWhereParameters);
			$this->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters);
		}
		else
                {
                        $context = sfContext::getInstance();
                        $context->getController()->forward("static", "logoutPage"); //Logout page
                        throw new sfStopException();
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
