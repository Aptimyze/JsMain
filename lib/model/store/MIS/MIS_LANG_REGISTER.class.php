<?php
/*This class is used to insert in LANG_REGISTER table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class MIS_LANG_REGISTER extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the LANG_REGISTER table.
        * @param  $profileId Int
        @param $lang String
        * 
        **/
	public function insert($id,$lang){
               
                if(!$id )
                        throw new jsException("","PROFILEID IS BLANK IN insert() OF LANG_REGISTER.class.php");

                try
                {
					$sql = "INSERT INTO MIS.LANG_REGISTER VALUES ('',:id,:lang)";
					$res = $this->db->prepare($sql);
				  	$res->bindValue(":id", $id, PDO::PARAM_INT);	
				  	$res->bindValue(":lang", $lang, PDO::PARAM_STR);		  	
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
