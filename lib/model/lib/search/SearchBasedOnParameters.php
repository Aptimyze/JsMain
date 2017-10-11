<?php
/*
This class handles the cases when search() function of legacy code is called.
*/
class SearchBasedOnParameters extends SearchParamters
{
	private $pid;

	public function __construct($loggedInProfileObj="")
	{
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			$this->pid = $loggedInProfileObj->getPROFILEID();
		parent::__construct();
	}

	/*
	This function sets the parameters passed into the search function of legacy code
	@params - An array where index is the variable and value has the corresponding value
	*/	
	public function getSearchCriteria($paramArr)
	{
		if($paramArr["STATE"])
		{
			if(is_array($paramArr["STATE"]))
				$this->setSTATE(implode(",",$paramArr["STATE"]));
			else
				$this->setSTATE($paramArr["STATE"]);
		}
		if($paramArr["NATURE_HANDICAP"])
		{
			if(is_array($paramArr["NATURE_HANDICAP"]))
				$this->setNATURE_HANDICAP(implode(",",$paramArr["NATURE_HANDICAP"]));
			else
				$this->setNATURE_HANDICAP($paramArr["NATURE_HANDICAP"]);
		}
		if($paramArr["HIV"])
		{
			if(is_array($paramArr["HIV"]))
				$this->setHIV(implode(",",$paramArr["HIV"]));
			else
				$this->setHIV($paramArr["HIV"]);
		}
		if($paramArr["CITY_RES"])
		{
			if(is_array($paramArr["CITY_RES"]))
				$this->setCITY_RES(implode(",",$paramArr["CITY_RES"]));
			else
				$this->setCITY_RES($paramArr["CITY_RES"]);
		}
		if($paramArr["OCCUPATION"])
		{
			if(is_array($paramArr["OCCUPATION"]))
				$this->setOCCUPATION(implode(",",$paramArr["OCCUPATION"]));
			else
				$this->setOCCUPATION($paramArr["OCCUPATION"]);
		}
		if($paramArr["COUNTRY_RES"])
		{
			if(is_array($paramArr["COUNTRY_RES"]))
				$this->setCOUNTRY_RES(implode(",",$paramArr["COUNTRY_RES"]));
			else
				$this->setCOUNTRY_RES($paramArr["COUNTRY_RES"]);
		}
		if($paramArr["GENDER"])
		{
			if(is_array($paramArr["GENDER"]))
				$this->setGENDER(implode(",",$paramArr["GENDER"]));
			else
				$this->setGENDER($paramArr["GENDER"]);
		}
		if($paramArr["RELIGION"])
		{
			if(is_array($paramArr["RELIGION"]))
				$this->setRELIGION(implode(",",$paramArr["RELIGION"]));
			else
				$this->setRELIGION($paramArr["RELIGION"]);
		}
		if($paramArr["CASTE"])
		{
			$this->setCASTE($paramArr["CASTE"]);
		}
		if($paramArr["MTONGUE"])
		{
			if(is_array($paramArr["MTONGUE"]))
				$this->setMTONGUE(implode(",",$paramArr["MTONGUE"]));
			else
				$this->setMTONGUE($paramArr["MTONGUE"]);
		}
		if($paramArr["LAGE"])
		{
			if(is_array($paramArr["LAGE"]))
                		$this->setLAGE(implode(",",$paramArr["LAGE"]));
			else
                		$this->setLAGE($paramArr["LAGE"]);
		}
		if($paramArr["HAGE"])
		{
			if(is_array($paramArr["HAGE"]))
                		$this->setHAGE(implode(",",$paramArr["HAGE"]));
			else
                		$this->setHAGE($paramArr["HAGE"]);
		}
		if($paramArr["HAVEPHOTO"])
		{
			if(is_array($paramArr["HAVEPHOTO"]))
				$this->setHAVEPHOTO(implode(",",$paramArr["HAVEPHOTO"]));
			else
				$this->setHAVEPHOTO($paramArr["HAVEPHOTO"]);
		}
		if($paramArr["MANGLIK"])
		{
			if(is_array($paramArr["MANGLIK"]))
				$this->setMANGLIK(implode(",",$paramArr["MANGLIK"]));
			else
				$this->setMANGLIK($paramArr["MANGLIK"]);
		}
		if($paramArr["MSTATUS"])
		{
			if(is_array($paramArr["MSTATUS"]))
				$this->setMSTATUS(implode(",",$paramArr["MSTATUS"]));
			else
				$this->setMSTATUS($paramArr["MSTATUS"]);
		}
		if($paramArr["INCOME"])
		{
			if(is_array($paramArr["INCOME"]))
				$this->setINCOME(implode(",",$paramArr["INCOME"]));
			else
				$this->setINCOME($paramArr["INCOME"]);
		}
		if($paramArr["EDU_LEVEL_NEW"])
		{
			if(is_array($paramArr["EDU_LEVEL_NEW"]))
				$this->setEDU_LEVEL_NEW(implode(",",$paramArr["EDU_LEVEL_NEW"]));
			else
				$this->setEDU_LEVEL_NEW($paramArr["EDU_LEVEL_NEW"]);
		}
		if($paramArr["PRIVACY"])
		{
			if(is_array($paramArr["PRIVACY"]))
				$this->setPRIVACY(implode(",",$paramArr["PRIVACY"]));
			else
				$this->setPRIVACY($paramArr["PRIVACY"]);
		}
		if($paramArr["PHOTO_DISPLAY"])
		{
			if(is_array($paramArr["PHOTO_DISPLAY"]))
				$this->setPHOTO_DISPLAY(implode(",",$paramArr["PHOTO_DISPLAY"]));
			else
				$this->setPHOTO_DISPLAY($paramArr["PHOTO_DISPLAY"]);
         	}
		if($paramArr["IGNORE_PROFILES"])
		{
			$ignoreProfiles = $paramArr["IGNORE_PROFILES"];
			$this->setIgnoreProfiles($ignoreProfiles);
		}
		if($paramArr["SHOW_PROFILES"])
		{
			$showProfiles = $paramArr["SHOW_PROFILES"];
			$this->setProfilesToSHow($showProfiles);
		}
		if($paramArr["KEYWORD"])
		{
			if(is_array($paramArr["KEYWORD"]))
				$this->setKEYWORD(implode(",",$paramArr["KEYWORD"]));
			else
				$this->setKEYWORD($paramArr["KEYWORD"]);
		}
		if($paramArr["KEYWORD_TYPE"])
		{
			if(is_array($paramArr["KEYWORD_TYPE"]))
				$this->setKEYWORD_TYPE(implode(",",$paramArr["KEYWORD_TYPE"]));
			else
				$this->setKEYWORD_TYPE($paramArr["KEYWORD_TYPE"]);
		}
		if($paramArr["LENTRY_DT"])
                {
                       	$this->setLENTRY_DT($paramArr["LENTRY_DT"]);
                }
		if($paramArr["HENTRY_DT"])
                {
                       	$this->setHENTRY_DT($paramArr["HENTRY_DT"]);
                }
		if($paramArr["IS_VSP"])
                {
                       	$this->setIS_VSP(1);
                }
                if($paramArr["PARTNER_MTONGUE"]){
                    $this->PARTNER_MTONGUE = $paramArr["PARTNER_MTONGUE"];
                }
                if($paramArr["PARTNER_CASTE"]){
                    $this->setPARTNER_CASTE($paramArr["PARTNER_CASTE"]);
                }
                if($paramArr["PARTNER_RELIGION"]){
                    $this->setPARTNER_RELIGION($paramArr["PARTNER_RELIGION"]);
                }
                if($paramArr["PARTNER_CASTE"]){
                    $this->setPARTNER_CASTE($paramArr["PARTNER_CASTE"]);
                }
                if($paramArr["PARTNER_COUNTRYRES"]){
                    $this->setPARTNER_COUNTRYRES($paramArr["PARTNER_COUNTRYRES"]);
                }
                if($paramArr["PARTNER_BTYPE"]){
                    $this->setPARTNER_BTYPE($paramArr["PARTNER_BTYPE"]);
                }
                if($paramArr["PARTNER_COMP"]){
                    $this->setPARTNER_COMP($paramArr["PARTNER_COMP"]);
                }
                if($paramArr["PARTNER_ELEVEL_NEW"]){
                    $this->setPARTNER_ELEVEL_NEW($paramArr["PARTNER_ELEVEL_NEW"]);
                }
                if($paramArr["PARTNER_INCOME"]){
                    $this->setPARTNER_INCOME($paramArr["PARTNER_INCOME"]);
                }
                if($paramArr["PARTNER_OCC"]){
                    $this->setPARTNER_OCC($paramArr["PARTNER_OCC"]);
                }
                if($paramArr["LPARTNER_LAGE"]){
                    $this->setLPARTNER_LAGE($paramArr["LPARTNER_LAGE"]);
                }
                if($paramArr["HPARTNER_LAGE"]){
                    $this->setHPARTNER_LAGE($paramArr["HPARTNER_LAGE"]);
                }
                if($paramArr["LPARTNER_HAGE"]){
                    $this->setLPARTNER_HAGE($paramArr["LPARTNER_HAGE"]);
                }
                if($paramArr["HPARTNER_HAGE"]){
                    $this->setHPARTNER_HAGE($paramArr["HPARTNER_HAGE"]);
                }
                if($paramArr["LPARTNER_LHEIGHT"]){
                    $this->setLPARTNER_LHEIGHT($paramArr["LPARTNER_LHEIGHT"]);
                }
                if($paramArr["HPARTNER_LHEIGHT"]){
                    $this->setHPARTNER_LHEIGHT($paramArr["HPARTNER_LHEIGHT"]);
                }
                if($paramArr["LPARTNER_HHEIGHT"]){
                    $this->setLPARTNER_HHEIGHT($paramArr["LPARTNER_HHEIGHT"]);
                }
                if($paramArr["HPARTNER_HHEIGHT"]){
                    $this->setHPARTNER_HHEIGHT($paramArr["HPARTNER_HHEIGHT"]);
                }
                if($paramArr["PARTNER_MSTATUS"]){
                    $this->setPARTNER_MSTATUS($paramArr["PARTNER_MSTATUS"]);
                }
                if($paramArr["PARTNER_CITYRES"]){
                    $this->setPARTNER_CITYRES($paramArr["PARTNER_CITYRES"]);
                }
                if($paramArr["PARTNER_DRINK"]){
                    $this->setPARTNER_DRINK($paramArr["PARTNER_DRINK"]);
                }
                if($paramArr["PARTNER_SMOKE"]){
                    $this->setPARTNER_SMOKE($paramArr["PARTNER_SMOKE"]);
                }
                if($paramArr["PARTNER_DIET"]){
                    $this->setPARTNER_DIET($paramArr["PARTNER_DIET"]);
                }
                if($paramArr["PARTNER_HANDICAPPED"]){
                    $this->setPARTNER_HANDICAPPED($paramArr["PARTNER_HANDICAPPED"]);
                }
                if($paramArr["PARTNER_MANGLIK"]){
                    $this->setPARTNER_MANGLIK($paramArr["PARTNER_MANGLIK"]);
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
