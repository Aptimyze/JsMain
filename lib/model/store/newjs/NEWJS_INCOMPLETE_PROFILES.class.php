<?php
/*This class is used to insert in INCOMPLETE_PROFILES table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class NEWJS_INCOMPLETE_PROFILES extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the INCOMPLETE_PROFILES table.
        * @param  $profileId Int
        @param $lang String
        * 
        **/
	public function insert($id){
               
                if(!$id )
                        throw new jsException("","PROFILEID IS BLANK IN insert() OF NEWJS_INCOMPLETE_PROFILES.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES(:id,:now)";
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
