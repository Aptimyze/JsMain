<?php
/**
 * This class perform Sort for ViewSimilar logic of detail page
 * @author Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2012-10-18
*/
class ViewSimilarSort extends SearchSort implements SortStrategyInterface
{
	/**
	* Constructor function
	* @access public
	* @constructor 
	* @param SearchParamters $SearchParamtersObj
	* @param LoggedInProfile $loggedInProfileObj logged in profile object
	*/
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
		$this->loggedInProfileObj = $loggedInProfileObj;
        }

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	* @access public
	*/
	public function getSortString()
	{
		$counter = 0;
		global $_COOKIE;	

		if(intval($_COOKIE["ISEARCH"]) || $this->loggedInProfileObj->getPROFILEID()) 
		{
			if($this->SearchParamtersObj->getGENDER()=='M')
			{
				$suggAlgoIncomeFilter = $this->loggedInProfileObj->getINCOME();
				$INCOME_DROP_PLUS4 = FieldMap::getFieldLabel('income_plus4','',1);
				$income_4_str = $INCOME_DROP_PLUS4[$suggAlgoIncomeFilter];

				if($income_4_str)
				{
					$income_4_strArr=explode(",",$income_4_str);
					foreach($income_4_strArr as $k=>$v)
					{
						$tempIncomeArray[]="if(tf(INCOME,$v),100,0)";
					}
					$tempIncomeArray = parent::get_solr_string($tempIncomeArray);
					$sortString[$counter] = $tempIncomeArray; 
					$sortAscOrDesc[$counter] = $this->sortByDesc;
					$counter++;
				}
			}

			$sortString[$counter] = "VIEW_SCORE_WITH_INACTIVE_PENALITY";
			$sortAscOrDesc[$counter] = $this->sortByDesc;
			$counter++;
		}
		else
		{
			if($this->SearchParamtersObj->getGENDER()=='M')
			{
				global $suggAlgoIncomeFilter;
				//$suggAlgoIncomeFilter = $this->loggedInProfileObj->getINCOME(); // ? income of viewed profile
				$INCOME_DROP_PLUS4 = FieldMap::getFieldLabel('income_plus4','',1);
				$income_4_str = $INCOME_DROP_PLUS4[$suggAlgoIncomeFilter];

				if($income_4_str)
				{
					$income_4_strArr=explode(",",$income_4_str);
					foreach($income_4_strArr as $k=>$v)
					{
						$tempIncomeArray[]="if(tf(INCOME,$v),100,0)";
					}
					$tempIncomeArray = parent::get_solr_string($tempIncomeArray);
					$sortString[$counter] = $tempIncomeArray; 
					$sortAscOrDesc[$counter] = $this->sortByDesc;
					$counter++;
				}
			}
			$sortString[$counter] = "NTIMES";
			$sortAscOrDesc[$counter] = $this->sortByDesc;
			$counter++;
		}


		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
