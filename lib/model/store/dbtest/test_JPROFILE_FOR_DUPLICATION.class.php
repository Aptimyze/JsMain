<?php
/**
 * JPROFILE_FOR_DUPLICATION
 * 
 * This class handles database queries to test_JPROFILE_FOR_DUPLICATION
 * 
 * @author     Reshu Rajput
 * @created    26-06-2013
 */

class test_JPROFILE_FOR_DUPLICATION extends TABLE{

        private static $instance;

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database name to which the connection would be made
         */

        public function __construct($dbname="")
        {
                parent::__construct($dbname); //To connect to the database
        }

	 /**
         * @fn getInstance
         * @brief fetches the instance of the class
         * @param $dbName - Database name to which the connection would be made
         * @return instance of this class
         */
        public static function getInstance($dbName='')
        {
                if(!isset(self::$instance))
                {
                        $class = __CLASS__;
                        self::$instance = new $class($dbName);
                }
                return self::$instance;
        }

	/**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from JPROFILE_FOR_DUPLICATION
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
        * @param $orderby string FIELDS ASC/DESC
        * @param $limit string 1/2/3 
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

        public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="")
        {
                if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
                        $sqlSelectDetail = "SELECT $fields FROM test.JPROFILE_FOR_DUPLICATION WHERE ";
                        $count = 1;
                        if(is_array($valueArray))
                        {
                                foreach($valueArray as $param=>$value)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $param IN ($value) ";
                                        else
                                                $sqlSelectDetail.=" AND $param IN ($value) ";
                                        $count++;
                                }
                        }
			if(is_array($excludeArray))
                        {
                                foreach($excludeArray as $excludeParam => $excludeValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
                                        else
                                                $sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
                                        $count++;
                                }
                        }
                        if(is_array($greaterThanArray))
                        {
                                foreach($greaterThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam > '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam > '$gValue' ";
                                        $count++;
                                }
                        }
                        if(is_array($greaterThanEqualArrayWithoutQuote))
                        {
                                foreach($greaterThanEqualArrayWithoutQuote as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam >= $gValue ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam >= $gValue ";
                                        $count++;
                                }
                        }
			 if(is_array($lessThanArray))
                        {
                                foreach($lessThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam < '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam < '$gValue' ";
                                        $count++;
                                }
                        }
                        if($orderby)
                        {
                                $sqlSelectDetail.=" order by $orderby ";
                        }
                        if($limit)
                        {
                                $sqlSelectDetail.=" limit $limit ";
                        }
                        $sqlSelectDetail;
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                        $resSelectDetail->execute();
                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $rowSelectDetail;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		                return NULL;
        }
	
	 /**
        This function is used to delete entries from test.JPROFILE_FOR_DUPLICATION where last login date is before given date.
        * @param  lastLoginDate date before which all the entries will be deleted 
        * @return rowCount int numbers of rows get deleted, NULL if error
        **/
        public function del($lastLoginDate)
        {
		 if(!$lastLoginDate)
                        throw new jsException("","no where condition passed in JprofileForDuplication store class");
                try
		{
                	$sql = "DELETE FROM test.JPROFILE_FOR_DUPLICATION where LAST_LOGIN_DT < :LASTLOGINDATE ";
                	$res = $this->db->prepare($sql);
                       	$res->bindParam("LASTLOGINDATE", $lastLoginDate, PDO::PARAM_STR);
                	$res->execute();
               		return $res->rowCount();
        	}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
}
