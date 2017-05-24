<?php
/*This class is used to insert in ASSIGNED_101 table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class JSADMIN_ASSIGNED_101 extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the JSADMIN_ASSIGNED_101 table.
        * @param  $profileId Int
        * @param operator String
        * 
        **/
	public function replace($id,$operator){
               
                if(!$id || !$operator)
                        throw new jsException("","PROFILEID or operator IS BLANK IN insertRecord() OF JSADMIN_ASSIGNED_101.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "REPLACE INTO jsadmin.ASSIGNED_101 (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) values(:id,:operator,:now)";
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
