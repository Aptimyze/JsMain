<?php
/**
 * This class perform Sort By Relevance expression
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @copyright 2012 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2012-07-27
*/
class SortByRelevanceStrategy extends SearchSort implements SortStrategyInterface
{
	private $sameCasteScore = 200;
	private $relatedCasteSameReligionSore = 125;
	private $relatedCasteDiffReligionSore = 75;
	private $diffCasteDiffReligionScore = -100; 
	private $sameMtongueScore = 100;
	private $relatedAllHindiScore = 50;
	private $sameIncomeSore = 100;
	private $positiveIncomeScore = 90;
	private $negativeIncomeScore = -100;

        /*
        * @constructor
        * @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        */
	public function __construct($SearchParamtersObj,$loggedInProfileObj='')
	{
		$this->SearchParamtersObj = $SearchParamtersObj;
		$this->loggedInProfileObj = $loggedInProfileObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj);
		parent::isFilterSorting($loggedInProfileObj);
	}

	
	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	*/
	public function getSortString()
	{
		$counter = 0;
		global $_COOKIE;
		if(!$this->loggedInProfileObj->getPROFILEID())
		{
			$profileid = $_COOKIE["ISEARCH"]; 
			if($profileid)
			{
				$this->loggedInProfileObj = Profile::getInstance('newjs_master',$profileid);
				$this->loggedInProfileObj->getDetail("","","MTONGUE,RELIGION,GENDER,AGE,CASTE,RELIGION,INCOME");
			}
		}

		/*Filter sorting to be given max preference*/
		if(SearchConfig::$filteredRemove && parent::getFilterSort())
		{
			$sortString[$counter] = parent::getFilterSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
                        $counter++;
			$this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:".parent::getFilterSortScore());
		}

                /* When Photos is searched , visible photos will be given more prefernce. */
                $photoSort = parent::getPhotoSort();
                if($photoSort)
                {
                        $sortString[$counter] = parent::getPhotoSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
                        $counter++;
                }       

		if($this->loggedInProfileObj->getPROFILEID()!='')
		{
			/* Caste+Religion Score Section */
			$myCaste = $this->loggedInProfileObj->getCASTE();
			$myReligion = $this->loggedInProfileObj->getRELIGION();
			$myGender = $this->loggedInProfileObj->getGENDER();

			if($myReligion && $myCaste)
			{
				$CasteSuggest = new CasteSuggest;
				$mappedCaste = $CasteSuggest->getSuggestedCastes($myCaste,2);

				$rel_caste[] = $this->diffCasteDiffReligionScore; //Assign Initial

				/* same caste */
				$rel_caste[] = "if(tf(CASTE,$myCaste),$this->sameCasteScore,0)";


				/* Same rc-caste same religion*/
				if(is_array($mappedCaste))	
					foreach($mappedCaste as $k => $v)
						$rel_caste[] = "if(and(tf(CASTE,$v),tf(RELIGION,$myReligion)),$this->relatedCasteSameReligionSore,0)";


				/* c. Else Related Caste, Different Religion:*/
				$tempScore=$this->relatedCasteDiffReligionSore-$this->diffCasteDiffReligionScore;
				if(is_array($mappedCaste))
					foreach($mappedCaste as $k => $v)
						$rel_caste[] = "if(tf(CASTE,$v), if(tf(RELIGION,$myReligion),0,$tempScore),0)";

				/* d. Else Non Related Caste, Same Religion: 0 */
				//---do nothing---
					
				/* e. Else Non Related Caste, Different Religion:*/
				//--taken care by -ve value

				/*counter for -ve value*/	
				$tempScore=-($this->diffCasteDiffReligionScore);
				$rel_caste[] = "if(tf(RELIGION,$myReligion),$tempScore,0)";

				$cnt = count($rel_caste);
				for($i=0;$i<$cnt;$i++)
				{
					if($i>0)
					{
						$st = "sum(";	
						$end = ")";
					}
					$j=$i+1;
					if($cnt>$j)
						$middle.=$rel_caste[$i].",";
					else
						$middle.=$rel_caste[$i];
				}
				$fl[] =  $st.$middle.$end;
			}
			unset($rel_caste);


			/* mtongue Score Section */
			$myMtongue = $this->loggedInProfileObj->getMTONGUE();
			if($myMtongue)
			{
				$mtongueScore[] = "if(tf(MTONGUE,$myMtongue),$this->sameMtongueScore,0)";	
				$tempArr = FieldMap::getFieldLabel('allHindiMtongues','',1);
				$tempArr1 = FieldMap::getFieldLabel('allHindiRelatedMtongues','',1);

				$conideredMtongue[] = $myMtongue;
				if(in_array($myMtongue,$tempArr))
				{
					foreach($tempArr as $k => $v)
						if($v!=$myMtongue)
						{
							$conideredMtongue[] = $v;
							$mtongueScore[] = "if(tf(MTONGUE,$v),$this->sameMtongueScore,0)";
						}
				}

				foreach($tempArr1 as $k=>$v)
				{
					if(strstr($v,$myMtongue))
					{
						$temp = explode(",",$v);
						foreach($temp as $kk=>$vv)
							if(!in_array($vv,$conideredMtongue))
								$mtongueScore[] = "if(tf(MTONGUE,$vv),$this->relatedAllHindiScore,0)";
					}
				}
                                $fl[] = parent::get_solr_string($mtongueScore);
			}
			unset($mtongueScore);
			unset($tempArr);
			unset($tempArr1);


			/* income Score Section */
			$myIncome = $this->loggedInProfileObj->getINCOME();
		        $incomeMappingObj = new IncomeMapping();
			$myIncome = $incomeMappingObj->getSortedIncome($myIncome);
			unset($incomeMappingObj);
			if($myIncome)
			{
				$lessInc= $myIncome-1;
				$moreInc= $myIncome+1;
				$incScore[] = "if(tf(INCOME_SORTBY,$myIncome),$this->sameIncomeSore,0)";
				if($myGender=='M')
				{
					$incScore[] = "map(INCOME_SORTBY,0,$lessInc,$this->positiveIncomeScore,0)";
					$incScore[] = "map(INCOME_SORTBY,$moreInc,100,$this->negativeIncomeScore,0)";
				}
				else
				{
					$incScore[] = "map(INCOME_SORTBY,$moreInc,100,$this->positiveIncomeScore,0)";
					$incScore[] = "map(INCOME_SORTBY,0,$lessInc,$this->negativeIncomeScore,0)";
				}
                                parent::get_solr_string($incScore);
                                $fl[] = parent::get_solr_string($incScore);
                        }
			unset($incScore);
			unset($tempArr1);
                        $sum_pfScore = "PROFILE_FRESHNESS_SCORE";
                        
                        if($fl){
                                $sop = parent::get_solr_string($fl);
                                /*
                                $sop1  = "if(max(sub($sop,280),0),sum(div($sop,400),55)";
                                $sop2  = ",if(max(sub($sop,175),0),sum(div($sop,275),40)";
                                $sopF = $sop1.$sop2.",0))";
                                */
                                $sopF = "div($sop,50)";
                        }
                        else
                                $sopF = 1;
                        
			$final = "sum($sopF,$sum_pfScore)";
			
			$sortString[$counter] = $final;
			$sortAscOrDesc[$counter] = $this->sortByDesc;
			$counter++;
			$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
			$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
		}
	}
}
