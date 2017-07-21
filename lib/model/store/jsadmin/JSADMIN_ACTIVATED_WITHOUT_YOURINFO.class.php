<?php
/*This class is used to insert in JSADMIN_ACTIVATED_WITHOUT_YOURINFO table
 * @author Ankit Shukla
 * @created 2017-07-18
*/
class JSADMIN_ACTIVATED_WITHOUT_YOURINFO extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Ankit
        This function is used to insert record in the JSADMIN_ACTIVATED_WITHOUT_YOURINFO table.
        * @param  $profileId Int
        * 
        **/
	public function insert($profileId){

                try
                {
					$now = date("Y-m-d H:i:s");
					$sql = "Insert IGNORE INTO jsadmin.ACTIVATED_WITHOUT_YOURINFO (`PROFILEID`,`ENTRY_DATE`) values(:id,:date,:now)";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $profileId, PDO::PARAM_INT);	
				  	$res->bindValue(":date", $now, PDO::PARAM_STR);			  	
					$res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
        
        /** Function DELETE added by Ankit
        This function is used to delete record from the JSADMIN_ACTIVATED_WITHOUT_YOURINFO table.
        * @param  $profileId Int
        * 
        **/
	public function delete($profileId){

                try
                {
					$now = date("Y-m-d H:i:s");
					$sql = "DELETE FROM jsadmin.ACTIVATED_WITHOUT_YOURINFO WHERE PROFILEID=:id";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $profileId, PDO::PARAM_INT);
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

