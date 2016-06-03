<?php
/*This class is used to get values from CASTE_COMMUNITY_MAPPING table
 * @created 2013-07-04
*/
class NEWJS_CASTE_COMMUNITY_MAPPING extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
        /**This function is used to insert record in the NAMES table.
        * @param $caste_com String
        * 
        **/
	public function getMap($caste_com){
               
                if(!$caste_com )
                        throw new jsException("","caste_com IS BLANK IN getMap() OF NEWJS_CASTE_COMMUNITY_MAPPING.class.php");

                try
                {
					$sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING where CASTE_COMMUNITY=:caste_com";
					$res = $this->db->prepare($sql);	
				  	$res->bindValue(":caste_com", $caste_com, PDO::PARAM_STR);		  	
					$res->execute();
					$row = $res->fetch(PDO::FETCH_ASSOC);
					return $row['MAP'];
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
}
?>
