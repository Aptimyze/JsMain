<?php
/*This class is used to insert in REG_LEAD table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class MIS_REG_HOME extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
        /** Function insert added by Nitesh
        This function is used to insert record in the REG_HOME table.
        * @param  $profileId Int
        * @param $source String
        * 
        **/
	
        public function insert($profileId,$source)
        {
				if(!$profileId || !$source)
                        throw new jsException("","PROFILEID  OR SOURCE IS BLANK IN insertRecord() OF MIS_REG_HOME.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "INSERT INTO MIS.REG_HOME (DATE,SOURCEID,PROFILEID) VALUES(:now,:source,:profileId)";
					$res = $this->db->prepare($sql);
				  
					$res->bindValue(":source", $source, PDO::PARAM_STR);
					$res->bindValue(":now", $now, PDO::PARAM_STR);
					$res->bindValue(":profileId", $profileId, PDO::PARAM_INT);
					$res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
