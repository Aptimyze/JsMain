<?php
class NEWJS_SEARCHFEMALE_TEXT extends TABLE
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
        public function getProfiles($type,$value)
        {
            try 
			{
				
				$sql="SELECT ST.PROFILEID FROM newjs.SEARCH_FEMALE_TEXT AS ST  WHERE ST.$type in ('$value')";
				$prep=$this->db->prepare($sql);
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
