<?php
class test_Top10_CommunityModelRecommendation extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {               
                        if(JsConstants::$whichMachine == "matchAlert")
                            $dbname = $dbname?$dbname:"newjs_local111";
			parent::__construct($dbname);
        }
        public function fetchProfiles($val)
        {
			try 
			{
				if($val)
				{
                                    $sql="SELECT partnerid FROM test.Top10_CommunityModelRecommendation  WHERE PROFILEID=:RECEIVERID";
					
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":RECEIVERID", $val, PDO::PARAM_INT);
					$prep->execute();
					$row = $prep->fetch(PDO::FETCH_ASSOC);
					return $row['partnerid'];
				}
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				jsException::nonCriticalError($e);
			}
		}
		
		
}
?>




