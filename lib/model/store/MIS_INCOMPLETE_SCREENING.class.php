<?php
/**
 * MIS_INCOMPLETE_SCREENING
 * 
 * This class handles all database queries to calulate incomplete marked profileid's from new screening module
 * @package    FTO phase2
 * @author     Nitesh Sethi
 * @created    2013-03-26
 */
class MIS_INCOMPLETE_SCREENING extends TABLE{
       
/**
* @fn __construct
* @brief Constructor function
* @param $dbName - Database name to which the connection would be made
*/
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
  
/**
 * @fn getIncompeleteScreeningProfileId
 * @brief fetches results from MIS_INCOMPLETE_SCREENING
 * @param profileId to be searched
 * @return true if PROFILEID is found in the table
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */    
        public function getIncompeleteScreeningProfileId($profileId)
        { 
			try{
															
				$sql="SELECT count(*) CNT FROM MIS.INCOMPLETE_SCREENING where PROFILEID=:profileId";
				$prep=$this->db->prepare($sql);
				
				$prep->bindValue(":profileId", $profileId, PDO::PARAM_INT);
				
				$prep->execute();
				$result = $prep->fetch(PDO::FETCH_ASSOC);
			return (($result['CNT']) ? 1 : 0);
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
}
?>
