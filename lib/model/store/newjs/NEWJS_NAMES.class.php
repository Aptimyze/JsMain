<?php
/*This class is used to insert in NAMES table
 * @author Nitesh Sethi
 * @created 2013-07-04
*/
class NEWJS_NAMES extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the NAMES table.
        * @param $username String
        * 
        **/
	public function insert($username){
               
                if(!$username )
                        throw new jsException("","username IS BLANK IN insert() OF NEWJS_NAMES.class.php");

                try
                {
					$sql = "INSERT INTO newjs.NAMES VALUES (:username)";
					$res = $this->db->prepare($sql);	
				  	$res->bindValue(":username", $username, PDO::PARAM_STR);		  	
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
