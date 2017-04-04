<?php
/**
* Base Class of search sorting for common functions and variables
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-02-01
*/
class SearchSort
{
	/**
	* @access protected
	* @var String $sortByAsc 
	*/
        protected $sortByAsc = 'asc';
	/**
        * @access protected
        * @var String $sortByDesc 
        */
        protected $sortByDesc = 'desc';
	/**
        * @access private
        * @var String $photoSort
        */
	private $photoSort;
	/**
        * @access private
        * @var String $filterSort
        */
	private $filterSort;
	/**
        * @access private
        * @var String $filterSortScore
        */
	private $filterSortScore;
        
        private $reverseSortStr;
        
        /**
         * Paid Member Sorting in logged out case
         * @var type string
         */
        private $paidSortStr;
        
        /**
         * JsBoost Member Sorting
         * @var type string
         */
        private $jsBoostSortStr;
	/**
	* When Photos is searched , visible photos will be given more prefernce.
	* @access public 
	* @param SearchParamters $SearchParamtersObj
	* @param mixed $loggedInProfileObj
	* @param String/bool $usePhotoSorting : Flag if photo sorting is required
	*/
	public function isPhotoSorting($SearchParamtersObj,$loggedInProfileObj='',$usePhotoSorting='')
	{
		if($SearchParamtersObj->getHAVEPHOTO()=='Y' || $usePhotoSorting)		
		{
			if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
				$this->photoSort = "PHOTO_VISIBILITY_LOGGEDIN";
			else
				$this->photoSort = "PHOTO_VISIBILITY_LOGGEDOUT";
		}
	}

	/**
	* getter for photoSort
	* @return String $this->photoSort 
	*/
	public function getPhotoSort()
	{
		return $this->photoSort;
	}

	/**
        * getter for sortByDesc
        * @return String $this->sortByDesc 
        */
	public function getPhotoSortDesc(){return $this->sortByDesc;}

	/**
        * getter for sortByAsc
        * @return String $this->sortByAsc 
        */
	public function getPhotoSortAsc(){return $this->sortByAsc;}

	/**
	* Function to set filter sort and filter sort score
	* @access public
 	* @param LoggedInProfile $loggedInProfileObj
	*/
	public function isFilterSorting($loggedInProfileObj='')
	{
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()!='')
		{
			$this->filterSort = '';
			$this->filterSortScore = '';

			if($loggedInProfileObj->getAGE())
			{
				$this->filterSort = $this->filterSort."if(and(tf(AGE_FILTER,Y),if(and(if(abs(sub(min(PARTNER_LAGE,".$loggedInProfileObj->getAGE()."),PARTNER_LAGE)),0,1),if(abs(sub(max(PARTNER_HAGE,".$loggedInProfileObj->getAGE()."),PARTNER_HAGE)),0,1)),0,1)),1,0),";
			}
			if($loggedInProfileObj->getMSTATUS())
			{
				$this->filterSort = $this->filterSort."if(and(tf(MSTATUS_FILTER,Y),if(tf(PARTNER_MSTATUS,".$loggedInProfileObj->getMSTATUS()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getRELIGION())
			{
				$this->filterSort = $this->filterSort."if(and(tf(RELIGION_FILTER,Y),if(tf(PARTNER_RELIGION,".$loggedInProfileObj->getRELIGION()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getCASTE())
			{
				$this->filterSort = $this->filterSort."if(and(tf(CASTE_FILTER,Y),if(tf(PARTNER_CASTE,".$loggedInProfileObj->getCASTE()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getCOUNTRY_RES())
			{
				$this->filterSort = $this->filterSort."if(and(tf(COUNTRY_RES_FILTER,Y),if(tf(PARTNER_COUNTRYRES,".$loggedInProfileObj->getCOUNTRY_RES()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getCITY_RES())
			{
				$this->filterSort = $this->filterSort."if(and(tf(CITY_RES_FILTER,Y),if(tf(PARTNER_CITYRES,".$loggedInProfileObj->getCITY_RES()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getMTONGUE())
			{
				$this->filterSort = $this->filterSort."if(and(tf(MTONGUE_FILTER,Y),if(tf(PARTNER_MTONGUE,".$loggedInProfileObj->getMTONGUE()."),0,1)),1,0),";
			}
			if($loggedInProfileObj->getINCOME())
			{
				$this->filterSort = $this->filterSort."if(and(tf(INCOME_FILTER,Y),if(tf(PARTNER_INCOME_FILTER,".$loggedInProfileObj->getINCOME()."),0,1)),1,0),";
			}
			if($this->filterSort)
			{
				$this->filterSort = rtrim($this->filterSort,",");
				$this->filterSort = "sum(".$this->filterSort.")";
			}

			if($loggedInProfileObj->getAGE())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(AGE_FILTER,Y),if(and(if(abs(sub(min(PARTNER_LAGE,".$loggedInProfileObj->getAGE()."),PARTNER_LAGE)),0,1),if(abs(sub(max(PARTNER_HAGE,".$loggedInProfileObj->getAGE()."),PARTNER_HAGE)),0,1)),0,1)),1,0),";
			}
			if($loggedInProfileObj->getMSTATUS())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(MSTATUS_FILTER,Y),if(tf(PARTNER_MSTATUS,".$loggedInProfileObj->getMSTATUS()."),0,1)),2,0),";
			}
			if($loggedInProfileObj->getRELIGION())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(RELIGION_FILTER,Y),if(tf(PARTNER_RELIGION,".$loggedInProfileObj->getRELIGION()."),0,1)),4,0),";
			}
			if($loggedInProfileObj->getCASTE())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(CASTE_FILTER,Y),if(tf(PARTNER_CASTE,".$loggedInProfileObj->getCASTE()."),0,1)),8,0),";
			}
			if($loggedInProfileObj->getCOUNTRY_RES())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(COUNTRY_RES_FILTER,Y),if(tf(PARTNER_COUNTRYRES,".$loggedInProfileObj->getCOUNTRY_RES()."),0,1)),16,0),";
			}
			if($loggedInProfileObj->getCITY_RES())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(CITY_RES_FILTER,Y),if(tf(PARTNER_CITYRES,".$loggedInProfileObj->getCITY_RES()."),0,1)),32,0),";
			}
			if($loggedInProfileObj->getMTONGUE())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(MTONGUE_FILTER,Y),if(tf(PARTNER_MTONGUE,".$loggedInProfileObj->getMTONGUE()."),0,1)),64,0),";
			}
			if($loggedInProfileObj->getINCOME())
			{
				$this->filterSortScore = $this->filterSortScore."if(and(tf(INCOME_FILTER,Y),if(tf(PARTNER_INCOME_FILTER,".$loggedInProfileObj->getINCOME()."),0,1)),128,0),";
			}
			if($this->filterSortScore)
			{
				$this->filterSortScore = rtrim($this->filterSortScore,",");
				$this->filterSortScore = "sum(".$this->filterSortScore.")";
			}
		}
	}

	/**
        * getter for filterSort
        * @return String $this->filterSort 
        */
	public function getFilterSort()
        {
                return $this->filterSort;
        }

	/**
        * getter for filterSortScore
        * @return String $this->filterSortScore
        */
	public function getFilterSortScore()
        {
                return $this->filterSortScore;
        }

	/**
	* Function to get solr readable format from array
	* @access public
	* @param array $arr : Array to make into solr readable format
	* @return String $fl
	*/ 
        public function get_solr_string($arr)
        {
                $cnt = count($arr);
                for($i=0;$i<$cnt;$i++)
                {
                        if($i>0)
                        {
                                $st.= "sum(";
                                $end.= ")";
                        }
                        $j=$i+1;
                        if($cnt>$j)
                                $middle.=$arr[$i].",";
                        else
                                $middle.=$arr[$i];
                }
                $fl =  $st.$middle.$end;
                return $fl;
        }
        public function getReverseDppSort(){
                return $this->reverseSortStr;
        }/**
         * This function generates reverse Dpp String
         * @param type $loggedInProfileObj
         * @param type $sortLastLogin require sorting on the basis of Last Login Score.
         */
        protected function setReverseDppSorting($loggedInProfileObj = '', $sortLastLogin = 0) {
                $sortArray = array();
                // Login time condition
                $sortLogin = '';
                if ($sortLastLogin == 1)
                        $sortLogin = "LAST_LOGIN_SCORE"; // User who have logged in in last 15 days will be given 100 score

                $doesntMatterValue = 99999;
                
                $cityStateArr = $this->setCityStateToBeMatched($loggedInProfileObj);

                if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() != '') {
                        if ($loggedInProfileObj->getCASTE()) {
                                $sortArray[] = "or(tf(PARTNER_CASTE," . $loggedInProfileObj->getCASTE() . "),tf(PARTNER_CASTE," . $doesntMatterValue . "))";
                        }
                        if ($loggedInProfileObj->getMANGLIK()) {
                                if(strstr($loggedInProfileObj->getMANGLIK(),"N")){
                                        $sortArray[] = "or(tf(PARTNER_MANGLIK," . $loggedInProfileObj->getMANGLIK() . "),tf(PARTNER_MANGLIK," . $doesntMatterValue . "))";
                                }else{
                                        $sortArray[] = "tf(PARTNER_MANGLIK," . $loggedInProfileObj->getMANGLIK() . ")"; 
                                }
                        }
                        if ($loggedInProfileObj->getAGE()) {
                                $sortArray[] = "and(if(abs(sub(min(PARTNER_LAGE," . $loggedInProfileObj->getAGE() . "),PARTNER_LAGE)),0,1),if(abs(sub(max(PARTNER_HAGE," . $loggedInProfileObj->getAGE() . "),PARTNER_HAGE)),0,1))";
                        }
                        if ($loggedInProfileObj->getMSTATUS()) {
                                $sortArray[] = "or(tf(PARTNER_MSTATUS," . $loggedInProfileObj->getMSTATUS() . "),tf(PARTNER_MSTATUS," . $doesntMatterValue . "))";
                        }
                        if ($loggedInProfileObj->getRELIGION()) {
                                $sortArray[] = "or(tf(PARTNER_RELIGION," . $loggedInProfileObj->getRELIGION() . "),tf(PARTNER_RELIGION," . $doesntMatterValue . "))";
                        }
                        if ($loggedInProfileObj->getCOUNTRY_RES()) {
                                $sortArray[] = "or(tf(PARTNER_COUNTRYRES," . $loggedInProfileObj->getCOUNTRY_RES() . "),tf(PARTNER_COUNTRYRES," . $doesntMatterValue . "))";
                        }
                        if($cityStateArr['state'] && $cityStateArr['nativeCity'])
                            $sortArray[] = "or(tf(PARTNER_CITYRES," . $cityStateArr['nativeCity'] . "),tf(PARTNER_STATE," . $cityStateArr['state'] . "),tf(PARTNER_CITYRES," . $doesntMatterValue . "))";
                        elseif($cityStateArr['state'] && $cityStateArr['nativeState'])
                            $sortArray[] = "or(tf(PARTNER_STATE," . $cityStateArr['state'] . "),tf(PARTNER_STATE," . $cityStateArr['nativeState'] . "),tf(PARTNER_STATE," . $doesntMatterValue . "))";
                        elseif($cityStateArr['nativeCity'] && $cityStateArr['city'])
                            $sortArray[] = "or(tf(PARTNER_CITYRES," . $cityStateArr['nativeCity'] . "),tf(PARTNER_CITYRES," . $cityStateArr['city'] . "),tf(PARTNER_CITYRES," . $doesntMatterValue . "))";
                        elseif($cityStateArr['nativeState'] && $cityStateArr['city'])
                            $sortArray[] = "or(tf(PARTNER_STATE," . $cityStateArr['nativeState'] . "),tf(PARTNER_CITYRES," . $cityStateArr['city'] . "),tf(PARTNER_CITYRES," . $doesntMatterValue . "))";
                        elseif($cityStateArr['city'])
                            $sortArray[] = "or(tf(PARTNER_CITYRES," . $cityStateArr['city'] . "),tf(PARTNER_CITYRES," . $doesntMatterValue . "))";
                        elseif($cityStateArr['state'])
                            $sortArray[] = "or(tf(PARTNER_STATE," . $cityStateArr['state'] . "),tf(PARTNER_CITYRES," . $doesntMatterValue . "))";
                        if ($loggedInProfileObj->getMTONGUE()) {
                                $sortArray[] = "or(tf(PARTNER_MTONGUE," . $loggedInProfileObj->getMTONGUE() . "),tf(PARTNER_MTONGUE," . $doesntMatterValue . "))";
                        }
                        if ($loggedInProfileObj->getEDU_LEVEL_NEW()) {
                                        $sortArray[] = "or(tf(PARTNER_ELEVEL_NEW," . $loggedInProfileObj->getEDU_LEVEL_NEW() . "),tf(PARTNER_ELEVEL_NEW," . $doesntMatterValue . "))";
                        } 
                        if ($loggedInProfileObj->getINCOME()) {
                                        $sortArray[] = "or(tf(PARTNER_INCOME_FILTER," . $loggedInProfileObj->getINCOME() . "),tf(PARTNER_INCOME_FILTER," . $doesntMatterValue . "))";
                        }
                }
                if (!empty($sortArray)) {
                        $brace = '';
                        $strCondition = '';
                        foreach ($sortArray as $arr) {
                                $strCondition .= "if(" . $arr . ",";
                                $brace .= ",0)";
                        }
                        $strCondition .= "1" . $brace;
                        if($sortLastLogin == 1)
                                $strCondition = "sum(" . $sortLogin . "," . $strCondition . ")";
                        else
                                $strCondition = "sum(". $strCondition . ")";
                } else {
                        $strCondition = $sortLogin;
                }
                $this->reverseSortStr = $strCondition;
        }
        
        //this function returns values for state,city,nativestate,nativeCity
        protected function setCityStateToBeMatched($loggedInObj){
            $profileId = $loggedInObj->getPROFILEID();
            $nativePlaceObj = ProfileNativePlace::getInstance("newjs_masterRep");
            $nativeData = $nativePlaceObj->getNativeData($profileId);
            $nativeState = $nativeData['NATIVE_STATE'];
            $nativeCity = $nativeData['NATIVE_CITY'];
            
            if(strlen($loggedInObj->getCITY_RES())==2){
                $response['state'] = $loggedInObj->getCITY_RES();
            }
            else
                $response['city'] = $loggedInObj->getCITY_RES();
            
            if($nativeState && $nativeCity)
                $response['nativeCity'] = $nativeCity;
            
            elseif($nativeState){
                $response['nativeState'] = $nativeState;
            }
            return $response;
        }
        public function isPaidSorting($loggedInProfileObj){
                if (!$loggedInProfileObj || $loggedInProfileObj->getPROFILEID() == '') {
                        $this->paidSortStr = "if(tf(SUBSCRIPTION,F),1,if(tf(SUBSCRIPTION,X),1,0))";
                }
        }
        public function getPaidSorting(){
                return $this->paidSortStr;
        }
        
        public function isJsBoostSorting($loggedInProfileObj){
                if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() != '') {
                        foreach(SearchConfig::$jsBoostSubscription as $subscription){
                                $this->jsBoostSortStr .=  "if(tf(SUBSCRIPTION,".$subscription."),1,";
                        }
                        $this->jsBoostSortStr .= "0))";
                }
        }
        public function getJsBoostSorting(){
                return $this->jsBoostSortStr;
        }
}
?>
