<?php


// This class is for sorting by photo visible criteria
class SortByVisitors extends SearchSort implements SortStrategyInterface
{
	private $usePhotoSorting=1;

        /**
	* Constructor function
        * @constructor
        * @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        */
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        { 
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj,$this->usePhotoSorting);
        }

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	*/
	public function getSortString()
	{
                $counter = 0;
                
                /* Photo Sortng */
		if($this->usePhotoSorting==1 && $this->SearchParamtersObj->getToSortByPhotoVisitors())
		{
			$sortString[$counter] = "HAVEPHOTO";
			$sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
			$counter++;
                	$photoSort = parent::getPhotoSort();
	                if($photoSort)
        	        {
                	        $sortString[$counter] = parent::getPhotoSort();
                        	$sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
	                        $counter++;
                	}       
		}
                else{
                    $tempArr = $this->SearchParamtersObj->getVisitorsDateConditionArr();
                    $sortString[$counter] = "sum(";

                    $count=0;
                    if(is_array($tempArr))		
                    foreach($tempArr as $k=>$v)
                    {
                            $sortString[$counter] .= 'if(tf(id,"'.$k.'"),'.$v.',0)';
                            $count++;
                            $sortString[$counter] .= ",";
                    }
                    $sortString[$counter] .= ")";
                    $sortAscOrDesc[$counter] = $this->sortByDesc;
                    $counter++;
                }
                
		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}