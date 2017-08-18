<?php
class FTO_FTO_STATES extends TABLE
{
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from FTO_CURRENT_STATE_LOG
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

        public function getFTOState($id)
        {
                if(!$id)
                        throw new jsException("","no STATE_ID passed");
                try
                {
//                        $fields = $fields?$fields:$this->getFields();//Get columns to query
                        $sqlSelectDetail = "SELECT STATE,SUBSTATE FROM FTO.FTO_STATES WHERE STATUS='Y' AND STATE_ID=:STATE_ID";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$resSelectDetail->bindValue(":STATE_ID",$id,PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        if($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                                $StateArray = $rowSelectDetail;
                        return $StateArray;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }
	public function getFTOStateID($state,$subState='')
	{
                if(!$state)
                        throw new jsException("","STATE or SUBSTATE not passed");
		try
		{
                        $sqlSelectDetail = "SELECT STATE_ID FROM FTO.FTO_STATES WHERE STATUS='Y' AND STATE=:STATE";
			if($subState)
				$sqlSelectDetail.=" AND SUBSTATE=:SUBSTATE";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$resSelectDetail->bindValue(":STATE",$state,PDO::PARAM_STR);
			if($subState)
				$resSelectDetail->bindValue(":SUBSTATE",$subState,PDO::PARAM_STR);
                        $resSelectDetail->execute();
			$numberOfRows=$resSelectDetail->rowCount();
			if($numberOfRows==1||$numberOfRows==0)
			{
				if($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
					$return	=	$rowSelectDetail['STATE_ID'];
			}
			else
			{
	                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
					$return[]	=	$rowSelectDetail['STATE_ID'];
			}
			return $return;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
	public function getFTOStateIDArrayForSubStates($subStates)
	{
                if(!is_array($subStates))
                        throw new jsException("","STATE or SUBSTATE not passed");
		try
		{
			foreach($subStates as $k=>$subState)
			{
				$str.=" :SUBSTATE".$k.",";
			}
			$str = substr($str,0,-1);
                        $sqlSelectDetail = "SELECT STATE_ID FROM FTO.FTO_STATES WHERE STATUS='Y' AND SUBSTATE IN (".$str.")";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
			foreach($subStates as $k=>$subState)
			{
				$resSelectDetail->bindValue(":SUBSTATE".$k,$subState,PDO::PARAM_STR);
			}
                        $resSelectDetail->execute();
			$numberOfRows=$resSelectDetail->rowCount();
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
				$return[]	=	$rowSelectDetail['STATE_ID'];
			return $return;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
}
?>
