<?php
/**
 * @class SearchParamters
 * @brief This class list the possible search paramters and setters and getters functions.
 * @author Lavesh Rawat
 * @created 2012-06-01
 */
class SearchParamters
{
	private $ID;
	protected $GENDER;
	protected $CASTE;
	protected $MTONGUE;
	protected $LAGE;
	protected $HAGE;
	protected $HAVEPHOTO;
	protected $PHOTO_VISIBILITY_LOGGEDIN;
	protected $MANGLIK;
	protected $MSTATUS;
	protected $HAVECHILD;
	protected $LHEIGHT;
	protected $HHEIGHT;
	protected $BTYPE;
	protected $COMPLEXION;
	protected $DIET;
	protected $SMOKE;
	protected $DRINK;
	protected $HANDICAPPED;
	protected $OCCUPATION;
	protected $COUNTRY_RES;
	protected $CITY_RES;
	protected $RES_STATUS;
	protected $EDU_LEVEL;
	protected $EDU_LEVEL_NEW;
	protected $KEYWORD;
	protected $DATE;
	protected $PHOTOBROWSE;
	protected $ONLINE;
	protected $SORT_LOGIC;
	protected $INCOME;
	protected $ROW_COUNT;
	protected $RANK_ID;
	protected $PROFILEID;
	protected $SEARCH_TYPE;
	protected $SUBSCRIPTION;
	protected $RECORDCOUNT;
	protected $PAGECOUNT;
	protected $KEYWORD_TYPE;
	protected $CASTE_DISPLAY;
	protected $RELATION;
	protected $NEWSEARCH_CLUSTERING;
	protected $BREAD_CRUMB;
	protected $INCOME_CLUSTER_MAPPING;
	protected $OCCUPATION_CLUSTER_MAPPING;
	protected $EDUCATION_CLUSTER_MAPPING;
	protected $RELIGION;
	protected $ORIGINAL_SID;
	protected $CASTE_MAPPING;
	protected $HOROSCOPE;
	protected $SPEAK_URDU;
	protected $HIJAB_MARRIAGE;
	protected $SAMPRADAY;
	protected $ZARATHUSHTRI;
	protected $AMRITDHARI;
	protected $CUT_HAIR;
	protected $MATHTHAB;
	protected $WORK_STATUS;
	protected $HIV;
	//protected $HANDICAP;			TO BE REMOVED - ANAND
	protected $NATURE_HANDICAP;
	protected $FREE_CONTACT;
	protected $NEW_PROFILE;
	protected $LIVE_PARENTS;
	protected $SUBCASTE;
	protected $WEAR_TURBAN;
	protected $MEM_LOOK_ME;
	protected $LINCOME;
	protected $HINCOME;
	protected $LINCOME_DOL;
	protected $HINCOME_DOL;
	protected $EDUCATION_GROUPING;
	protected $sortArr;
	protected $noRelaxParams;
	protected $ignoreProfiles;
	protected $showedProfiles;
	protected $whereParams;
	protected $rangeParams;
	protected $onlineProfiles;
	protected $WIFE_WORKING;
	private $onlineSearchFlag = 'O';
	private $PROFILE_ADDED;
	private $MATCHALERTS_DATE_CLUSTER;
	protected $LENTRY_DT;
	protected $HENTRY_DT;
	protected $LAST_LOGIN_DT;	
	protected $noOfResults;	
	protected $PHOTO_DISPLAY;
	protected $PRIVACY;
	protected $FL_ATTRIBUTE;
	protected $HIV_IGNORE;
       	protected $MANGLIK_IGNORE;
        protected $MSTATUS_IGNORE;
        protected $HANDICAPPED_IGNORE;	
	protected $LVERIFY_ACTIVATED_DT;	
	protected $HVERIFY_ACTIVATED_DT;	
	protected $showFilteredProfiles;	
        protected $alertsDateConditionArr;
        protected $attemptConditionArr;
        protected $STATE_SELECTED;
        protected $CITY_RES_SELECTED;
        protected $CITY_INDIA_SELECTED;
        protected $FSO_VERIFIED;
        protected $INCOME_SORTBY;
        protected $NATIVE_STATE;
        protected $NATIVE_CITY;
        protected $SHOW_RESULT_FOR_SELF;
        protected $LAST_LOGIN_SCORE;
        protected $TRENDS_DATA;
        protected $IS_VSP; // check for VSP Search
        
        public function __construct()
	{
		$this->whereParams = SearchConfig::$searchWhereParameters;
		$this->rangeParams = SearchConfig::$searchRangeParameters;
	}

	public function setter($arrayValuePair)
	{
		foreach($arrayValuePair as $k=>$v)
		{
                        $functionName = 'set'.$k;
			if(method_exists($this,$functionName))
                                $this->{"set" . $k}($v);
		}
	}

	/* Getter and Setter functions*/
	public function setGENDER($GENDER) 
	{ 
		$validInput = SearchInputValidation::validateInput("GENDER",$GENDER);
		if($validInput)
			$this->GENDER = $GENDER;
	}
	public function getGENDER() { return $this->GENDER; }
        
	/* Getter and Setter functions*/
	public function setLAST_LOGIN_SCORE($LAST_LOGIN_SCORE) {
			$this->LAST_LOGIN_SCORE = $LAST_LOGIN_SCORE;
	}
	public function getLAST_LOGIN_SCORE() { return $this->LAST_LOGIN_SCORE; }

        public function setNATIVE_STATE($NATIVE_STATE) 
	{ 
		$validInput = SearchInputValidation::validateInput("NATIVE_STATE",$NATIVE_STATE);
		if($validInput)
			$this->NATIVE_STATE = $NATIVE_STATE;
	}
	public function getNATIVE_STATE() { return $this->NATIVE_STATE; }
        
        public function setNATIVE_CITY($NATIVE_CITY) 
	{ 
		$validInput = SearchInputValidation::validateInput("NATIVE_CITY",$NATIVE_CITY);
		if($validInput)
			$this->NATIVE_CITY = $NATIVE_CITY;
	}
	public function getNATIVE_CITY() { return $this->NATIVE_CITY; }
        
        
	public function setCASTE($CASTE,$setCaste='') 
	{
		if(is_array($CASTE))
			$validInput = SearchInputValidation::validateInput("CASTE",implode(",",$CASTE));
		else
			$validInput = SearchInputValidation::validateInput("CASTE",$CASTE);

                if($validInput)
		{
			if($setCaste)
				$this->CASTE = $CASTE;
			else
			{
				$revampCasteObj = new RevampCasteFunctions;
				$allCastes = $revampCasteObj->getAllCastes($CASTE,1); 
				if(is_array($allCastes))
					$this->CASTE = implode(",",$allCastes);
				else
					$this->CASTE = '';
				if(is_array($CASTE))
					$this->CASTE_DISPLAY = implode(",",$CASTE);
				else
					$this->CASTE_DISPLAY = $CASTE;
				unset($revampCasteObj);
				unset($allCastes);
			}
		}
	}
	public function getCASTE() { return $this->CASTE; }
	public function setMTONGUE($MTONGUE) 
	{ 
		$validInput = SearchInputValidation::validateInput("MTONGUE",$MTONGUE);
                if($validInput)
			$this->MTONGUE = $MTONGUE; 
	}
	public function getMTONGUE() { return $this->MTONGUE; }
	public function setLAGE($LAGE) 
	{ 
		$validInput = SearchInputValidation::validateInput("LAGE",$LAGE);
                if($validInput)
			$this->LAGE = $LAGE; 
	}
	public function getLAGE() { return $this->LAGE; }
	public function setHAGE($HAGE) 
	{ 
		$validInput = SearchInputValidation::validateInput("HAGE",$HAGE);
                if($validInput)
			$this->HAGE = $HAGE; 
	}
	public function getHAGE() { return $this->HAGE; }
        public function setHAVEPHOTO($HAVEPHOTO) 
	{ 
		$validInput = SearchInputValidation::validateInput("HAVEPHOTO",$HAVEPHOTO);
                if($validInput)
			$this->HAVEPHOTO = $HAVEPHOTO; 
	}
        public function getHAVEPHOTO() { return $this->HAVEPHOTO; }
        public function setPHOTO_VISIBILITY_LOGGEDIN($PHOTO_VISIBILITY_LOGGEDIN)
        {
                $validInput = SearchInputValidation::validateInput("PHOTO_VISIBILITY_LOGGEDIN",$PHOTO_VISIBILITY_LOGGEDIN);
                if($validInput)
                        $this->PHOTO_VISIBILITY_LOGGEDIN = $PHOTO_VISIBILITY_LOGGEDIN;
        }
        public function getPHOTO_VISIBILITY_LOGGEDIN() { return $this->PHOTO_VISIBILITY_LOGGEDIN; }
	public function setMANGLIK($MANGLIK) 
	{ 
                // append Dont know to search string if 'not manglik' is selected. Exclude cluster search by APPLY_ONLY_CLUSTER enum.
                if(strstr($MANGLIK,"N") && !strstr($MANGLIK,"D") && !strstr($MANGLIK,SearchTypesEnums::APPLY_ONLY_CLUSTER)){
                        $MANGLIK .= ',D';
                }
		$validInput = SearchInputValidation::validateInput("MANGLIK",$MANGLIK);
                if($validInput){
			$this->MANGLIK = $MANGLIK; 
                }
	}
	public function getMANGLIK() { return $this->MANGLIK; }
	public function setMSTATUS($MSTATUS) 
	{ 
		$validInput = SearchInputValidation::validateInput("MSTATUS",$MSTATUS);
                if($validInput)
			$this->MSTATUS = $MSTATUS; 
	}
	public function getMSTATUS() { return $this->MSTATUS; }
        public function setHAVECHILD($HAVECHILD) 
	{ 
		$validInput = SearchInputValidation::validateInput("HAVECHILD",$HAVECHILD);
                if($validInput)
			$this->HAVECHILD = $HAVECHILD;
	}
        public function getHAVECHILD() { return $this->HAVECHILD; }
	public function setLHEIGHT($LHEIGHT) 
	{ 
		$validInput = SearchInputValidation::validateInput("LHEIGHT",$LHEIGHT);
                if($validInput)
			$this->LHEIGHT = $LHEIGHT; 
	}
	public function getLHEIGHT() { return $this->LHEIGHT; }
	public function setHHEIGHT($HHEIGHT) 
	{ 
		$validInput = SearchInputValidation::validateInput("HHEIGHT",$HHEIGHT);
                if($validInput)
			$this->HHEIGHT = $HHEIGHT; 
	}
	public function getHHEIGHT() { return $this->HHEIGHT; }
	public function setBTYPE($BTYPE) 
	{ 
		$validInput = SearchInputValidation::validateInput("BTYPE",$BTYPE);
                if($validInput)
			$this->BTYPE = $BTYPE; 
	}
	public function getBTYPE() { return $this->BTYPE; }
	public function setCOMPLEXION($COMPLEXION) 
	{ 
		$validInput = SearchInputValidation::validateInput("COMPLEXION",$COMPLEXION);
                if($validInput)
			$this->COMPLEXION = $COMPLEXION; 
	}
	public function getCOMPLEXION() { return $this->COMPLEXION; }
	public function setDIET($DIET) 
	{ 
		$validInput = SearchInputValidation::validateInput("DIET",$DIET);
                if($validInput){
                        if($DIET && !strstr($DIET,SearchConfig::_doesntMatterValue) && !strstr($DIET,SearchConfig::_nullValueAttributeLabel) && !strstr($DIET,SearchTypesEnums::APPLY_ONLY_CLUSTER))   
                                $this->DIET = $DIET.",".SearchConfig::_nullValueAttributeLabel;
                        else  
                                $this->DIET = $DIET;
                }
	}
	public function getDIET() { return $this->DIET; }
	public function setSMOKE($SMOKE) 
	{ 
		$validInput = SearchInputValidation::validateInput("SMOKE",$SMOKE);
                if($validInput){
                        if($SMOKE && !strstr($SMOKE,SearchConfig::_doesntMatterValue) && !strstr($SMOKE,SearchConfig::_nullValueAttributeLabel))   
                                $this->SMOKE = $SMOKE.",".SearchConfig::_nullValueAttributeLabel;
                        else  
                                $this->SMOKE = $SMOKE;
                }
	}
	public function getSMOKE() { return $this->SMOKE; }
	public function setDRINK($DRINK) 
	{ 
		$validInput = SearchInputValidation::validateInput("DRINK",$DRINK);
                if($validInput){
                        if($DRINK && !strstr($DRINK,SearchConfig::_doesntMatterValue) && !strstr($DRINK,SearchConfig::_nullValueAttributeLabel))   
                                $this->DRINK = $DRINK.",".SearchConfig::_nullValueAttributeLabel;
                        else  
                                $this->DRINK = $DRINK; 
                }
	}
	public function getDRINK() { return $this->DRINK; }
	public function setHANDICAPPED($HANDICAPPED) 
	{ 
		$validInput = SearchInputValidation::validateInput("HANDICAPPED",$HANDICAPPED);
                if($validInput){
                    if(strstr($HANDICAPPED,SearchConfig::_noneValueHandicapped) && !strstr($HANDICAPPED,SearchConfig::_nullValueAttributeLabel))   
			$this->HANDICAPPED = $HANDICAPPED.",".SearchConfig::_nullValueAttributeLabel;
                    else  
			$this->HANDICAPPED = $HANDICAPPED;
                    
                }
	}
	public function getHANDICAPPED() { return $this->HANDICAPPED; }
	public function setOCCUPATION($OCCUPATION) 
	{ 
		$validInput = SearchInputValidation::validateInput("OCCUPATION",$OCCUPATION);
                if($validInput)
			$this->OCCUPATION = $OCCUPATION; 
	}
	public function getOCCUPATION() { return $this->OCCUPATION; }
        public function setOCCUPATION_IGNORE($OCCUPATION_IGNORE) 
	{ 
		$validInput = SearchInputValidation::validateInput("OCCUPATION_IGNORE",$OCCUPATION_IGNORE);
                if($validInput)
                    $this->OCCUPATION_IGNORE = $OCCUPATION_IGNORE;
	}
	public function getOCCUPATION_IGNORE() {   return $this->OCCUPATION_IGNORE; }
	public function setCOUNTRY_RES($COUNTRY_RES) 
	{
		$validInput = SearchInputValidation::validateInput("COUNTRY_RES",$COUNTRY_RES);
                if($validInput)
		{
			if($COUNTRY_RES == '51')
				$this->INDIA_NRI = 1;
			$this->COUNTRY_RES = $COUNTRY_RES; 
		}
	}
	public function getCOUNTRY_RES() { return $this->COUNTRY_RES; }
	public function setCITY_RES($CITY_RES,$fromCityForStateFunction = '',$noMapping="") 
	{ 
		$validInput = SearchInputValidation::validateInput("CITY_RES",$CITY_RES);
                if($validInput)
			$this->CITY_RES = $CITY_RES; 
		if(!$noMapping)
		{
                	if($this->getSTATE() && $this->CITY_RES && !$fromCityForStateFunction)
		 	       $this->setCityForState();
		}
	}
	public function getCITY_RES() { return $this->CITY_RES; }
	public function setCITY_RES_SELECTED($CITY_RES) 
	{ 
		$validInput = SearchInputValidation::validateInput("CITY_RES",$CITY_RES);
                if($validInput)
			$this->CITY_RES_SELECTED = $CITY_RES; 
	}
	public function getCITY_RES_SELECTED() { return $this->CITY_RES_SELECTED; }
	public function setRES_STATUS($RES_STATUS) 
	{ 
		$validInput = SearchInputValidation::validateInput("RES_STATUS",$RES_STATUS);
                if($validInput)
			$this->RES_STATUS = $RES_STATUS; 
	}
	public function getRES_STATUS() { return $this->RES_STATUS; }
	public function setEDU_LEVEL($EDU_LEVEL) 
	{ 
		$validInput = SearchInputValidation::validateInput("EDU_LEVEL",$EDU_LEVEL);
                if($validInput)
			$this->EDU_LEVEL = $EDU_LEVEL; 
	}
	public function getEDU_LEVEL() { return $this->EDU_LEVEL; }
	public function setKEYWORD($KEYWORD) 
	{ 
                $KEYWORD = preg_replace("/[^a-zA-Z0-9 ]+/", "", html_entity_decode($KEYWORD, ENT_QUOTES));
		$validInput = SearchInputValidation::validateInput("KEYWORD",$KEYWORD);
                if($validInput)
			$this->KEYWORD = $KEYWORD; 
	}
	public function getKEYWORD() { return $this->KEYWORD; }
	public function setDATE($DATE) 
	{ 
		$validInput = SearchInputValidation::validateInput("DATE",$DATE);
                if($validInput)
			$this->DATE = $DATE; 
	}
	public function getDATE() { return $this->DATE; }
	public function setPHOTOBROWSE($PHOTOBROWSE) 
	{ 
		$validInput = SearchInputValidation::validateInput("PHOTOBROWSE",$PHOTOBROWSE);
                if($validInput)
			$this->PHOTOBROWSE = $PHOTOBROWSE; 
	}
	public function getPHOTOBROWSE() { return $this->PHOTOBROWSE; }
	public function setONLINE($ONLINE) 
	{ 
		$validInput = SearchInputValidation::validateInput("ONLINE",$ONLINE);
                if($validInput)
			$this->ONLINE = $ONLINE; 
	}
	public function getONLINE() { return $this->ONLINE; }
	public function setSORT_LOGIC($SORT_LOGIC) 
	{ 
		$validInput = SearchInputValidation::validateInput("SORT_LOGIC",$SORT_LOGIC);
                if($validInput)
			$this->SORT_LOGIC = $SORT_LOGIC; 
	}
	public function getSORT_LOGIC() { return $this->SORT_LOGIC; }
	public function setINCOME($INCOME) 
	{ 
		$validInput = SearchInputValidation::validateInput("INCOME",$INCOME);
                if($validInput)
			$this->INCOME = $INCOME; 
	}
	public function getINCOME() { return $this->INCOME; }

	public function setINCOME_SORTBY($INCOME_SORTBY)
        {
        	$this->INCOME_SORTBY = $INCOME_SORTBY;
        }
        public function getINCOME_SORTBY() { return $this->INCOME_SORTBY; }

	public function setROW_COUNT($ROW_COUNT) 
	{ 
		$validInput = SearchInputValidation::validateInput("ROW_COUNT",$ROW_COUNT);
                if($validInput)
			$this->ROW_COUNT = $ROW_COUNT; 
	}
	public function getROW_COUNT() { return $this->ROW_COUNT; }
	public function setRANK_ID($RANK_ID) 
	{ 
		$validInput = SearchInputValidation::validateInput("RANK_ID",$RANK_ID);
                if($validInput)
			$this->RANK_ID = $RANK_ID; 
	}
	public function getRANK_ID() { return $this->RANK_ID; }
	public function setPROFILEID($PROFILEID) 
	{ 
		$validInput = SearchInputValidation::validateInput("PROFILEID",$PROFILEID);
                if($validInput)
			$this->PROFILEID = $PROFILEID; 
	}
	public function getPROFILEID() { return $this->PROFILEID; }
	public function setSEARCH_TYPE($SEARCH_TYPE) 
	{ 
		$validInput = SearchInputValidation::validateInput("SEARCH_TYPE",$SEARCH_TYPE);
                if($validInput)
			$this->SEARCH_TYPE = $SEARCH_TYPE; 
	}
	public function getSEARCH_TYPE() { return $this->SEARCH_TYPE; }
	public function setSUBSCRIPTION($SUBSCRIPTION) 
	{ 
		$validInput = SearchInputValidation::validateInput("SUBSCRIPTION",$SUBSCRIPTION);
                if($validInput)
			$this->SUBSCRIPTION = $SUBSCRIPTION; 
	}
	public function getSUBSCRIPTION() { return $this->SUBSCRIPTION; }
	public function setRECORDCOUNT($RECORDCOUNT) 
	{ 
		$validInput = SearchInputValidation::validateInput("RECORDCOUNT",$RECORDCOUNT);
                if($validInput)
			$this->RECORDCOUNT = $RECORDCOUNT; 
	}
	public function getRECORDCOUNT() { return $this->RECORDCOUNT; }
	public function setPAGECOUNT($PAGECOUNT) 
	{ 
		$validInput = SearchInputValidation::validateInput("PAGECOUNT",$PAGECOUNT);
                if($validInput)
			$this->PAGECOUNT = $PAGECOUNT; 
	}
	public function getPAGECOUNT() { return $this->PAGECOUNT; }
	public function setEDU_LEVEL_NEW($EDU_LEVEL_NEW) 
	{ 
		$validInput = SearchInputValidation::validateInput("EDU_LEVEL_NEW",$EDU_LEVEL_NEW);
                if($validInput)
			$this->EDU_LEVEL_NEW = $EDU_LEVEL_NEW; 
	}
	public function getEDU_LEVEL_NEW() { return $this->EDU_LEVEL_NEW; }
	public function setKEYWORD_TYPE($KEYWORD_TYPE) 
	{ 
		$validInput = SearchInputValidation::validateInput("KEYWORD_TYPE",$KEYWORD_TYPE);
                if($validInput)
			$this->KEYWORD_TYPE = $KEYWORD_TYPE; 
	}
	public function getKEYWORD_TYPE() { return $this->KEYWORD_TYPE; }
	public function setCASTE_DISPLAY($CASTE_DISPLAY) 
	{ 
		$validInput = SearchInputValidation::validateInput("CASTE_DISPLAY",$CASTE_DISPLAY);
                if($validInput)
			$this->CASTE_DISPLAY = $CASTE_DISPLAY; 
	}
	public function getCASTE_DISPLAY() { return $this->CASTE_DISPLAY; }
	public function setRELATION($RELATION) 
	{ 
		$validInput = SearchInputValidation::validateInput("RELATION",$RELATION);
                if($validInput)
			$this->RELATION = $RELATION; 
	}
	public function getRELATION() { return $this->RELATION; }
	public function setNEWSEARCH_CLUSTERING($NEWSEARCH_CLUSTERING) 
	{ 
		$validInput = SearchInputValidation::validateInput("NEWSEARCH_CLUSTERING",$NEWSEARCH_CLUSTERING);
                if($validInput)
			$this->NEWSEARCH_CLUSTERING = $NEWSEARCH_CLUSTERING; 
	}
	public function getNEWSEARCH_CLUSTERING() { return $this->NEWSEARCH_CLUSTERING; }
	public function setBREAD_CRUMB($BREAD_CRUMB) 
	{ 
		$validInput = SearchInputValidation::validateInput("BREAD_CRUMB",$BREAD_CRUMB);
                if($validInput)
			$this->BREAD_CRUMB = $BREAD_CRUMB; 
	}
	public function getBREAD_CRUMB() { return $this->BREAD_CRUMB; }
	public function setINCOME_CLUSTER_MAPPING($INCOME_CLUSTER_MAPPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("INCOME_CLUSTER_MAPPING",$INCOME_CLUSTER_MAPPING);
                if($validInput)
			$this->INCOME_CLUSTER_MAPPING = $INCOME_CLUSTER_MAPPING; 
	}
	public function getINCOME_CLUSTER_MAPPING() { return $this->INCOME_CLUSTER_MAPPING; }
	public function setOCCUPATION_CLUSTER_MAPPING($OCCUPATION_CLUSTER_MAPPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("OCCUPATION_CLUSTER_MAPPING",$OCCUPATION_CLUSTER_MAPPING);
                if($validInput)
			$this->OCCUPATION_CLUSTER_MAPPING = $OCCUPATION_CLUSTER_MAPPING; 
	}
	public function getOCCUPATION_CLUSTER_MAPPING() { return $this->OCCUPATION_CLUSTER_MAPPING; }
	public function setEDUCATION_CLUSTER_MAPPING($EDUCATION_CLUSTER_MAPPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("EDUCATION_CLUSTER_MAPPING",$EDUCATION_CLUSTER_MAPPING);
                if($validInput)
			$this->EDUCATION_CLUSTER_MAPPING = $EDUCATION_CLUSTER_MAPPING; 
	}
	public function getEDUCATION_CLUSTER_MAPPING() { return $this->EDUCATION_CLUSTER_MAPPING; }
	public function setRELIGION($RELIGION) 
	{ 
		$validInput = SearchInputValidation::validateInput("RELIGION",$RELIGION);
                if($validInput)
			$this->RELIGION = $RELIGION; 
	}
	public function getRELIGION() { return $this->RELIGION; }
	public function setORIGINAL_SID($ORIGINAL_SID) 
	{ 
		$validInput = SearchInputValidation::validateInput("ORIGINAL_SID",$ORIGINAL_SID);
                if($validInput)
			$this->ORIGINAL_SID = $ORIGINAL_SID; 
	}
	public function getORIGINAL_SID() { return $this->ORIGINAL_SID; }
	public function setCASTE_MAPPING($CASTE_MAPPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("CASTE_MAPPING",$CASTE_MAPPING);
                if($validInput)
			$this->CASTE_MAPPING = $CASTE_MAPPING; 
	}
	public function getCASTE_MAPPING() { return $this->CASTE_MAPPING; }
	public function setHOROSCOPE($HOROSCOPE) 
	{ 
		$validInput = SearchInputValidation::validateInput("HOROSCOPE",$HOROSCOPE);
                if($validInput)
			$this->HOROSCOPE = $HOROSCOPE; 
	}
	public function getHOROSCOPE() { return $this->HOROSCOPE; }
	public function setSPEAK_URDU($SPEAK_URDU) 
	{ 
		$validInput = SearchInputValidation::validateInput("SPEAK_URDU",$SPEAK_URDU);
                if($validInput)
			$this->SPEAK_URDU = $SPEAK_URDU; 
	}
	public function getSPEAK_URDU() { return $this->SPEAK_URDU; }
	public function setHIJAB_MARRIAGE($HIJAB_MARRIAGE) 
	{ 
		$validInput = SearchInputValidation::validateInput("HIJAB_MARRIAGE",$HIJAB_MARRIAGE);
                if($validInput)
			$this->HIJAB_MARRIAGE = $HIJAB_MARRIAGE; 
	}
	public function getHIJAB_MARRIAGE() { return $this->HIJAB_MARRIAGE; }
	public function setSAMPRADAY($SAMPRADAY) 
	{ 
		$validInput = SearchInputValidation::validateInput("SAMPRADAY",$SAMPRADAY);
                if($validInput)
			$this->SAMPRADAY = $SAMPRADAY; 
	}
	public function getSAMPRADAY() { return $this->SAMPRADAY; }
	public function setZARATHUSHTRI($ZARATHUSHTRI) 
	{ 
		$validInput = SearchInputValidation::validateInput("ZARATHUSHTRI",$ZARATHUSHTRI);
                if($validInput)
			$this->ZARATHUSHTRI = $ZARATHUSHTRI; 
	}
	public function getZARATHUSHTRI() { return $this->ZARATHUSHTRI; }
	public function setAMRITDHARI($AMRITDHARI) 
	{ 
		$validInput = SearchInputValidation::validateInput("AMRITDHARI",$AMRITDHARI);
                if($validInput)
			$this->AMRITDHARI = $AMRITDHARI; 
	}
	public function getAMRITDHARI() { return $this->AMRITDHARI; }
	public function setCUT_HAIR($CUT_HAIR) 
	{ 
		$validInput = SearchInputValidation::validateInput("CUT_HAIR",$CUT_HAIR);
                if($validInput)
			$this->CUT_HAIR = $CUT_HAIR; 
	}
	public function getCUT_HAIR() { return $this->CUT_HAIR; }
	public function setMATHTHAB($MATHTHAB) 
	{ 
		$validInput = SearchInputValidation::validateInput("MATHTHAB",$MATHTHAB);
                if($validInput)
			$this->MATHTHAB = $MATHTHAB; 
	}
	public function getMATHTHAB() { return $this->MATHTHAB; }
	public function setWORK_STATUS($WORK_STATUS) 
	{ 
		$validInput = SearchInputValidation::validateInput("WORK_STATUS",$WORK_STATUS);
                if($validInput)
			$this->WORK_STATUS = $WORK_STATUS; 
	}
	public function getWORK_STATUS() { return $this->WORK_STATUS; }
	public function setHIV($HIV) 
	{ 
		$validInput = SearchInputValidation::validateInput("HIV",$HIV);
                if($validInput)
			$this->HIV = $HIV; 
	}
	public function getHIV() { return $this->HIV; }
	public function setNATURE_HANDICAP($NATURE_HANDICAP) 
	{ 
		$validInput = SearchInputValidation::validateInput("NATURE_HANDICAP",$NATURE_HANDICAP);
                if($validInput)
			$this->NATURE_HANDICAP = $NATURE_HANDICAP; 
	}
	public function getNATURE_HANDICAP() { return $this->NATURE_HANDICAP; }
	public function setFREE_CONTACT($FREE_CONTACT) 
	{ 
		$validInput = SearchInputValidation::validateInput("FREE_CONTACT",$FREE_CONTACT);
                if($validInput)
			$this->FREE_CONTACT = $FREE_CONTACT; 
	}
	public function getFREE_CONTACT() { return $this->FREE_CONTACT; }
	public function setNEW_PROFILE($NEW_PROFILE) 
	{ 
		$validInput = SearchInputValidation::validateInput("NEW_PROFILE",$NEW_PROFILE);
                if($validInput)
			$this->NEW_PROFILE = $NEW_PROFILE; 
	}
	public function getNEW_PROFILE() { return $this->NEW_PROFILE; }
	public function setLIVE_PARENTS($LIVE_PARENTS) 
	{ 
		$validInput = SearchInputValidation::validateInput("LIVE_PARENTS",$LIVE_PARENTS);
                if($validInput)
			$this->LIVE_PARENTS = $LIVE_PARENTS; 
	}
	public function getLIVE_PARENTS() { return $this->LIVE_PARENTS; }
	public function setSUBCASTE($SUBCASTE) 
	{ 
		$validInput = SearchInputValidation::validateInput("SUBCASTE",$SUBCASTE);
                if($validInput)
			$this->SUBCASTE = $SUBCASTE; 
	}
	public function getSUBCASTE() { return $this->SUBCASTE; }
	public function setWEAR_TURBAN($WEAR_TURBAN) 
	{ 
		$validInput = SearchInputValidation::validateInput("WEAR_TURBAN",$WEAR_TURBAN);
                if($validInput)
			$this->WEAR_TURBAN = $WEAR_TURBAN; 
	}
	public function getWEAR_TURBAN() { return $this->WEAR_TURBAN; }
	public function setMEM_LOOK_ME($MEM_LOOK_ME) 
	{ 
		$validInput = SearchInputValidation::validateInput("MEM_LOOK_ME",$MEM_LOOK_ME);
                if($validInput)
			$this->MEM_LOOK_ME = $MEM_LOOK_ME; 
	}
	public function getMEM_LOOK_ME() { return $this->MEM_LOOK_ME; }
	public function setLINCOME($LINCOME) 
	{ 
		$validInput = SearchInputValidation::validateInput("LINCOME",$LINCOME);
                if($validInput)
			$this->LINCOME = $LINCOME; 
	}
	public function getLINCOME() { return $this->LINCOME; }
	public function setHINCOME($HINCOME) 
	{ 
		$validInput = SearchInputValidation::validateInput("HINCOME",$HINCOME);
                if($validInput)
			$this->HINCOME = $HINCOME; 
	}
	public function getHINCOME() { return $this->HINCOME; }
	public function setLINCOME_DOL($LINCOME_DOL) 
	{ 
		$validInput = SearchInputValidation::validateInput("LINCOME_DOL",$LINCOME_DOL);
                if($validInput)
			$this->LINCOME_DOL = $LINCOME_DOL; 
	}
	public function getLINCOME_DOL() { return $this->LINCOME_DOL; }
	public function setHINCOME_DOL($HINCOME_DOL) 
	{ 
		$validInput = SearchInputValidation::validateInput("HINCOME_DOL",$HINCOME_DOL);
                if($validInput)
			$this->HINCOME_DOL = $HINCOME_DOL; 
	}
	public function getHINCOME_DOL() { return $this->HINCOME_DOL; }
        public function setFSO_VERIFIED($FSO_VERIFIED) 
	{ 
		$validInput = SearchInputValidation::validateInput("FSO_VERIFIED",$FSO_VERIFIED);
                if($validInput)
			$this->FSO_VERIFIED = $FSO_VERIFIED;
	}
	public function getFSO_VERIFIED() { return $this->FSO_VERIFIED; }

	/*
	* The logic for this function : all values which are less than choosen value should also need to be considered.
	* example if value choosen is 3 then the value should be mapped to "1,2,3".
	* Special case of online member is also there.
	*/
	public function setLAST_ACTIVITY($LAST_ACTIVITY) 
	{
		$validInput = SearchInputValidation::validateInput("LAST_ACTIVITY",$LAST_ACTIVITY);
                if($validInput)
		{
			if($LAST_ACTIVITY && $LAST_ACTIVITY!=$this->onlineSearchFlag)
			{
				$temp = explode(",",$LAST_ACTIVITY);
				$max = max($temp);
				if($max==$this->onlineSearchFlag)
				{
					arsort($temp);
					$keys = array_keys($temp);
					$max = $keys[1];
				}

				for($i=$max;$i>=0;$i--)
					$temp[]=$i;
				$temp = array_unique($temp);
			}
			if(strstr($LAST_ACTIVITY,$this->onlineSearchFlag))
			{
				$this->setONLINE($this->onlineSearchFlag);
				if(is_array($temp))
				foreach($temp as $v)
					if($v!=$this->onlineSearchFlag)
						$temp1[] = $v;
				if(!$temp1)
					$LAST_ACTIVITY = '';
				else
					$LAST_ACTIVITY = implode(",",$temp1);
			}
			else
			{
				if(is_array($temp))
					$LAST_ACTIVITY = implode(",",$temp);
				$this->setONLINE('');
			}
			$this->LAST_ACTIVITY = $LAST_ACTIVITY; 
		}
	}

	/*
	* The logic for this function : all values which are less than choosen value should also need to be considered.
	* example if value choosen is 3 then the value should be mapped to "1,2,3".
	*/
	public function setPROFILE_ADDED($x)
	{
		$validInput = SearchInputValidation::validateInput("PROFILE_ADDED",$x);
                if($validInput)
		{
			if($x)
			{
				$temp = explode(",",$x);
				$max = max($temp);
				unset($temp);
				while($max>0)
				{
					$temp[] = $max;
					$max--;
				}
				$new = implode(",",$temp);
				$this->PROFILE_ADDED = $new;
			}
			if(is_array($temp))
				$new = implode(",",$temp);
			$this->PROFILE_ADDED = $new;
		}
	}

	public function setMATCHALERTS_DATE_CLUSTER($x)
	{
		$validInput = SearchModuleInputValidate::validateMATCHALERTS_DATE_CLUSTER($x);
                if($validInput)
		{
			if($x=='ALL' || $x=='All')
				$new = $x;
			else
			{
				if($x)
				{
					$temp = explode(",",$x);
					$max = max($temp);
					unset($temp);
					while($max>0)
					{
						$temp[] = $max;
						$max--;
					}
					$new = implode(",",$temp);
					$this->MATCHALERTS_DATE_CLUSTER = $new;
				}

				if(is_array($temp))
					$new = implode(",",$temp);
			}		
			$this->MATCHALERTS_DATE_CLUSTER = $new;
		}
	}
        public function getMATCHALERTS_DATE_CLUSTER(){return $this->MATCHALERTS_DATE_CLUSTER;}
        public function getPROFILE_ADDED(){return $this->PROFILE_ADDED;}
	public function getLAST_ACTIVITY() { return $this->LAST_ACTIVITY; }
	public function setCASTE_GROUP($CASTE_GROUP) 
	{ 
		$validInput = SearchInputValidation::validateInput("CASTE_GROUP",$CASTE_GROUP);
                if($validInput)
			$this->CASTE_GROUP = $CASTE_GROUP; 
	}
	public function getCASTE_GROUP() { return $this->CASTE_GROUP; }
	public function setEDUCATION_GROUPING($EDUCATION_GROUPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("EDUCATION_GROUPING",$EDUCATION_GROUPING);
                if($validInput)
			$this->EDUCATION_GROUPING = $EDUCATION_GROUPING; 
	}
	public function getEDUCATION_GROUPING() { return $this->EDUCATION_GROUPING; }
	public function setMARRIED_WORKING($MARRIED_WORKING) 
	{ 
		$validInput = SearchInputValidation::validateInput("MARRIED_WORKING",$MARRIED_WORKING);
                if($validInput)
			$this->MARRIED_WORKING = $MARRIED_WORKING; 
	}
	public function getMARRIED_WORKING() { return $this->MARRIED_WORKING; }
	public function setGOING_ABROAD($GOING_ABROAD) 
	{ 
		$validInput = SearchInputValidation::validateInput("GOING_ABROAD",$GOING_ABROAD);
                if($validInput)
			$this->GOING_ABROAD = $GOING_ABROAD; 
	}
	public function getGOING_ABROAD() { return $this->GOING_ABROAD; }
	public function setCITY_INDIA($CITY_INDIA) 
	{ 
		$validInput = SearchInputValidation::validateInput("CITY_INDIA",$CITY_INDIA);
                if($validInput)
			$this->CITY_INDIA = $CITY_INDIA; 
	}
        public function setCITY_INDIA_SELECTED($CITY_INDIA) 
	{ 
		$validInput = SearchInputValidation::validateInput("CITY_INDIA",$CITY_INDIA);
                if($validInput)
			$this->CITY_INDIA_SELECTED = $CITY_INDIA; 
	}
	public function getCITY_INDIA() { return $this->CITY_INDIA; }
        public function getCITY_INDIA_SELECTED() { return $this->CITY_INDIA_SELECTED; }
	public function setSTATE($STATE,$fromCityForStateFunction = '',$noMapping="") 
	{ 
		$validInput = SearchInputValidation::validateInput("STATE",$STATE);
                if($validInput)
			$this->STATE = $STATE; 
			
		if(!$noMapping)
		{
	                if($this->getCITY_RES() && $this->STATE && !$fromCityForStateFunction)
        	            $this->setCityForState();
		}
	}
	public function getSTATE() { return $this->STATE; }
	public function setSTATE_SELECTED($STATE) 
	{ 
		$validInput = SearchInputValidation::validateInput("STATE",$STATE);
                if($validInput)
			$this->STATE_SELECTED = $STATE; 
                }    
	public function getSTATE_SELECTED() { return $this->STATE_SELECTED; }
	public function setOCCUPATION_GROUPING($OCCUPATION_GROUPING) 
	{ 
		$validInput = SearchInputValidation::validateInput("OCCUPATION_GROUPING",$OCCUPATION_GROUPING);
                if($validInput)
			$this->OCCUPATION_GROUPING = $OCCUPATION_GROUPING; 
	}
	public function getOCCUPATION_GROUPING() { return $this->OCCUPATION_GROUPING; }
	public function setINDIA_NRI($INDIA_NRI) 
	{ 
		$validInput = SearchInputValidation::validateInput("INDIA_NRI",$INDIA_NRI);
                if($validInput)
			$this->INDIA_NRI = $INDIA_NRI; 
	}
	public function getINDIA_NRI(){ return $this->INDIA_NRI;}
	public function setVIEWED($VIEWED) 
	{ 
		$validInput = SearchInputValidation::validateInput("VIEWED",$VIEWED);
                if($validInput)
			$this->VIEWED = $VIEWED; 
	}
	public function getVIEWED(){ return $this->VIEWED;}
	public function setSORTING_CRITERIA($SORTING_CRITERIA) 
	{ 
		$validInput = SearchInputValidation::validateInput("SORTING_CRITERIA",$SORTING_CRITERIA);
                if($validInput)
			$this->SORTING_CRITERIA = $SORTING_CRITERIA; 
	}
	public function getSORTING_CRITERIA(){ return $this->SORTING_CRITERIA;}
	public function setSORTING_CRITERIA_ASC_OR_DESC($SORTING_CRITERIA_ASC_OR_DESC) 
	{ 
		$validInput = SearchInputValidation::validateInput("SORTING_CRITERIA_ASC_OR_DESC",$SORTING_CRITERIA_ASC_OR_DESC);
                if($validInput)
			$this->SORTING_CRITERIA_ASC_OR_DESC = $SORTING_CRITERIA_ASC_OR_DESC; 
	}
	public function getSORTING_CRITERIA_ASC_OR_DESC(){ return $this->SORTING_CRITERIA_ASC_OR_DESC;}
	public function getFL_ATTRIBUTE(){ return $this->FL_ATTRIBUTE;}
	public function setFL_ATTRIBUTE($FL_ATTRIBUTE)
	{ 
		$validInput = SearchInputValidation::validateInput("FL_ATTRIBUTE",$FL_ATTRIBUTE);
                if($validInput)
			$this->FL_ATTRIBUTE = $FL_ATTRIBUTE;
	}
	public function setNoRelaxParams($noRelaxParams) 
	{ 
		$validInput = SearchInputValidation::validateInput("NoRelaxParams",$noRelaxParams);
                if($validInput)
			$this->noRelaxParams = $noRelaxParams; 
	}
	public function getNoRelaxParams(){ return $this->noRelaxParams;}
	public function setIgnoreProfiles($ignoreProfiles) 
	{ 
		$validInput = SearchInputValidation::validateInput("IgnoreProfiles",$ignoreProfiles);
                if($validInput)
			$this->ignoreProfiles = $ignoreProfiles; 
	}
	public function getIgnoreProfiles(){ return $this->ignoreProfiles;}
	public function setProfilesToShow($showedProfiles) 
	{ 
		$validInput = SearchInputValidation::validateInput("ProfilesToShow",$showedProfiles);
                if($validInput)
			$this->showedProfiles = $showedProfiles; 
	}
	public function getProfilesToShow(){ return $this->showedProfiles;}
	public function setOnlineProfiles($onlineProfiles) 
	{ 
		$validInput = SearchInputValidation::validateInput("OnlineProfiles",$onlineProfiles);
                if($validInput)
			$this->onlineProfiles = $onlineProfiles; 
	}
	public function getOnlineProfiles(){ return $this->onlineProfiles;}
	public function setID($ID) 
	{ 
		$validInput = SearchInputValidation::validateInput("ID",$ID);
                if($validInput)
			$this->ID = $ID; 
	}
	public function getID() { return $this->ID; }
	public function setWhereParams($x)
	{
		$validInput = SearchInputValidation::validateInput("WhereParams",$x);
                if($validInput)
			$this->whereParams = $x;
	}
	public function getWhereParams(){return $this->whereParams;}
	public function setRangeParams($x)
	{
		$validInput = SearchInputValidation::validateInput("RangeParams",$x);
                if($validInput)
			$this->rangeParams = $x;
	}
	public function getRangeParams(){return $this->rangeParams;}
	public function setWIFE_WORKING($x)
	{
		$validInput = SearchInputValidation::validateInput("WIFE_WORKING",$x);
                if($validInput)
			$this->WIFE_WORKING = $x;
	}
        public function getWIFE_WORKING(){return $this->WIFE_WORKING;}
	public function setLENTRY_DT($x)
	{
		$validInput = SearchInputValidation::validateInput("LENTRY_DT",$x);
                if($validInput)
			$this->LENTRY_DT = $x;
	}
        public function getLENTRY_DT(){return $this->LENTRY_DT;}
	public function setHENTRY_DT($x)
	{
		$validInput = SearchInputValidation::validateInput("HENTRY_DT",$x);
                if($validInput)
			$this->HENTRY_DT = $x;
	}
        public function getHENTRY_DT(){return $this->HENTRY_DT;}
	public function setLAST_LOGIN_DT($x)
	{
		$validInput = SearchInputValidation::validateInput("LAST_LOGIN_DT",$x);
                if($validInput)
		{
			if($x && $x!="0000-00-00")
			{
				$this->LAST_LOGIN_DT = $x;
				$this->setLENTRY_DT($x."T00:00:00Z");
				$this->setHENTRY_DT(date("Y-m-d")."T00:00:00Z");
			}
		}
	}
        public function getLAST_LOGIN_DT(){return $this->LAST_LOGIN_DT;}
        
        public function setLLAST_LOGIN_DT($x)
	{
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
		$this->LLAST_LOGIN_DT = $x;
	}
        public function getLPAID_ON(){return $this->LPAID_ON;}
        
        public function setLPAID_ON($x)
	{
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
		$this->LPAID_ON = $x;
	}
        public function getLLAST_LOGIN_DT(){return $this->LLAST_LOGIN_DT;}
        public function setHLAST_LOGIN_DT($x)
	{
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
		$this->HLAST_LOGIN_DT = $x;
	}
        
        public function getHPAID_ON(){return $this->HPAID_ON;}
        public function setHPAID_ON($x)
	{
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
		$this->HPAID_ON = $x;
	}
        public function getHLAST_LOGIN_DT(){return $this->HLAST_LOGIN_DT;}

	public function setNoOfResults($x)
	{
		$validInput = SearchInputValidation::validateInput("NoOfResults",$x);
                if($validInput)
			$this->noOfResults = $x;
	}
        public function getNoOfResults(){return $this->noOfResults;}

        public function getPHOTO_DISPLAY(){return $this->PHOTO_DISPLAY;}
	public function setPHOTO_DISPLAY($x)
	{
		$validInput = SearchInputValidation::validateInput("PHOTO_DISPLAY",$x);
                if($validInput)
			$this->PHOTO_DISPLAY = $x;
	}
        public function getPRIVACY(){return $this->PRIVACY;}
	public function setPRIVACY($x)
	{
		$validInput = SearchInputValidation::validateInput("PRIVACY",$x);
                if($validInput)
			$this->PRIVACY = $x;
	}
	public function getHIV_IGNORE(){return $this->HIV_IGNORE;}
        public function setHIV_IGNORE($x)
	{
		$validInput = SearchInputValidation::validateInput("HIV_IGNORE",$x);
                if($validInput)
			$this->HIV_IGNORE = $x;
	}
        public function getMANGLIK_IGNORE(){return $this->MANGLIK_IGNORE;}
        public function setMANGLIK_IGNORE($x)
	{
		$validInput = SearchInputValidation::validateInput("MANGLIK_IGNORE",$x);
                if($validInput)
			$this->MANGLIK_IGNORE = $x;
	}
        public function getMSTATUS_IGNORE(){return $this->MSTATUS_IGNORE;}
        public function setMSTATUS_IGNORE($x)
	{
		$validInput = SearchInputValidation::validateInput("MSTATUS_IGNORE",$x);
                if($validInput)
			$this->MSTATUS_IGNORE = $x;
	}
        public function getHANDICAPPED_IGNORE(){return $this->HANDICAPPED_IGNORE;}
        public function setHANDICAPPED_IGNORE($x)
	{
		$validInput = SearchInputValidation::validateInput("HANDICAPPED_IGNORE",$x);
                if($validInput)
			$this->HANDICAPPED_IGNORE = $x;
	}
        public function setLVERIFY_ACTIVATED_DT($x)
        {
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
		$this->LVERIFY_ACTIVATED_DT = $x;
	}
        public function getLVERIFY_ACTIVATED_DT(){return $this->LVERIFY_ACTIVATED_DT;}
        public function setHVERIFY_ACTIVATED_DT($x)
        {
		if(!$x)
			;
		elseif(substr($x,0,10)=='0000-00-00')
			$x ='';
		else
		{
			if($x && $x!="0000-00-00" && !strpos($x,"Z"))
				$x = str_replace(" ","T",$x)."Z";
		}
        	$this->HVERIFY_ACTIVATED_DT = $x;
        }
        public function getHVERIFY_ACTIVATED_DT(){return $this->HVERIFY_ACTIVATED_DT;}
        public function setNewTagJustJoinDate($x){$this->newTagJustJoinDate = $x;}
        public function getNewTagJustJoinDate(){return $this->newTagJustJoinDate;}
        public function setShowFilteredProfiles($x){$this->showFilteredProfiles = $x;}
        public function getShowFilteredProfiles(){return $this->showFilteredProfiles;}
        public function getAlertsDateConditionArr(){return $this->alertsDateConditionArr;}
	public function setAlertsDateConditionArr($x){$this->alertsDateConditionArr = $x;}
        public function setKUNDLI_DATE_CLUSTER($x){$this->KUNDLI_DATE_CLUSTER=$x;}
        public function getKUNDLI_DATE_CLUSTER(){return $this->KUNDLI_DATE_CLUSTER;}
        public function getAttemptConditionArr(){return $this->attemptConditionArr;}
	public function setAttemptConditionArr($x){$this->attemptConditionArr = $x;}
        public function getVisitorsDateConditionArr(){return $this->visitorsDateConditionArr;}
	public function setVisitorsDateConditionArr($x){$this->visitorsDateConditionArr = $x;}
        
        public function getToSortByPhotoVisitors(){return $this->sortByPhotoVisitors;}
	public function setToSortByPhotoVisitors($x){$this->sortByPhotoVisitors = $x;}

        public function getDisplayCity(){return $this->displayCity;}
        public function getDisplayState(){return $this->displayState;}
	/* Getter and Setter public functions*/
        
        public function setCityForState(){
            
            $city_arr = explode(",",$this->getCITY_RES());
            $state_arr = explode(",",$this->getSTATE());

		if(!$this->displayCity)
			$this->displayCity = $this->getCITY_RES();
		if(!$this->displayState)
			$this->displayState = $this->getSTATE();
            
            if($state_arr){
                foreach ($state_arr as $k=>$stateVal){
                    if(FieldMap::getFieldLabel("state_CITY","",1)[$stateVal]){

                        $city_from_state =  $this->cityStateConversion("",$stateVal); 
                        array_push($city_from_state, $stateVal.'000'); // 00 for native place native city field if not filled and only native state is present
                        $city_arr = array_merge($city_arr,$city_from_state);
                        $city_arr = array_unique($city_arr);
                    }

                }
            }
            

            if($city_arr)
            {
                foreach ($city_arr as $k=>$cityVal){
                    if(FieldMap::getFieldLabel("city_india","",1)[$cityVal]){
                        $state_from_city =  $this->cityStateConversion($cityVal);
                        if(is_array($state_arr) && is_array($state_from_city)){
                                $state_arr = array_merge($state_arr,$state_from_city);
                                $state_arr = array_unique($state_arr);
                        }
                    }
                }

            }
            
            if(is_array($state_arr))
                    $this->setSTATE(implode(",",$state_arr),1);
            else
                    $this->setSTATE($state_arr,1);
            if(is_array($city_arr))
            {
                    $this->setCITY_INDIA(implode(",",array_unique($city_arr)));
                    $this->setCITY_RES(implode(",",array_unique($city_arr)),1);
            }
            else
            {
                    $this->setCITY_INDIA($city_arr);
                    $this->setCITY_RES($city_arr,1);
            }
        }
        private function cityStateConversion($city = '', $state = '') {
                if ($city) {
                        $city = explode(",", $city);
                        foreach ($city as $key => $value) {
                                $state[$key] = substr($value, 0, 2);
                        }
                        $state = array_unique($state);
                        return $state;
                } elseif ($state) {
                        $cityList = FieldMap::getFieldLabel("state_CITY", $state);
                        $cityList=explode(",",$cityList);
                        return $cityList;
                }
                return NULL;
        }
        
        
      public function setSHOW_RESULT_FOR_SELF($SHOW_RESULT_FOR_SELF='') 
			{ 
					$this->SHOW_RESULT_FOR_SELF = $SHOW_RESULT_FOR_SELF; 
			}
			public function getSHOW_RESULT_FOR_SELF() { return $this->SHOW_RESULT_FOR_SELF; }
        public function setTRENDS_DATA($TRENDS_DATA='') 
        { 
                $this->TRENDS_DATA = $TRENDS_DATA; 
        }
        public function getTRENDS_DATA() { return $this->TRENDS_DATA; }
        public function setIS_VSP($IS_VSP=0) {$this->IS_VSP = $IS_VSP;}
        public function getIS_VSP() { return $this->IS_VSP; }
}
