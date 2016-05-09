<?php
/*This class is used to insert in REDIFF_SRCH_REG table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class MIS_REDIFF_SRCH_REG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the REDIFF_SRCH_REG table.
        * @param  $profileId Int
        * 
        **/
	public function insert($id){
               
                if(!$id )
                        throw new jsException("","PROFILEID IS BLANK IN insert() OF REDIFF_SRCH_REG.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "INSERT INTO MIS.REDIFF_SRCH_REG (PROFILEID,ENTRY_DT) VALUES (:id,:now)";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $id, PDO::PARAM_INT);	
				  	$res->bindValue(":now", $now, PDO::PARAM_STR);		  	
					$res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
}
?>
