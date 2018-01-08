<?php
/*This class is used to insert in JSADMIN_ASSIGNLOG_101 table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class JSADMIN_ASSIGNLOG_101 extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the JSADMIN_ASSIGNLOG_101 table.
        * @param  $profileId Int
        * 
        **/
	public function insert($id,$operator){
               
                if(!$id || !$operator)
                        throw new jsException("","PROFILEID or operator IS BLANK IN insert() OF NEWJS_CONTACT_ARCHIVE.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "insert into jsadmin.ASSIGNLOG_101 (`PROFILEID`,`OPERATOR`,`DATE`) values(:id,:operator,:now)";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $id, PDO::PARAM_INT);	
				  	$res->bindValue(":operator", $operator, PDO::PARAM_STR);	
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
