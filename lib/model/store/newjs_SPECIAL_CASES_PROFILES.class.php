<?php
class NEWJS_SPECIAL_CASES_PROFILES extends TABLE
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
        public function getProfiles($type,$gender)
        {
			
			try 
			{
				
				$sql_2="SELECT SQL_CACHE S.PROFILEID FROM newjs.SPECIAL_CASES_PROFILES AS S WHERE DISEASE = :type AND GENDER = :gender";
				$prep=$this->db->prepare($sql_2);
				$prep->bindValue(":type",$type,PDO::PARAM_STR);
				$prep->bindValue(":gender",$gender,PDO::PARAM_STR);
				$prep->execute();
				
				while ($result = $prep->fetch(PDO::FETCH_NUM)) 
				{
					$records[] = $result[0];
				}
				
				
				
				return $records;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
}		
?>