<?php
/*This class is used to insert in INF_USER_PIN table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class INFOVISION_INF_USER_PIN extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the INF_USER_PIN table.
        * @param  $profileId INT
        @param $vpin INT
        * 
        **/
	public function insert($id,$vpin){
               
                if(!$id )
                        throw new jsException("","PROFILEID IS BLANK IN insert() OF INFOVISION_INF_USER_PIN.class.php");

                try
                {
					$sql = "INSERT IGNORE INTO infovision.INF_USER_PIN (PROFILEID,VPIN) VALUES (:id,:vpin)";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $id, PDO::PARAM_INT);	
				  	$res->bindValue(":vpin", $vpin, PDO::PARAM_INT);		  	
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
