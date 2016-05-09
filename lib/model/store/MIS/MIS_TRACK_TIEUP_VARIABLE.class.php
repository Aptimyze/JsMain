<?php
/*This class is used to insert in TRACK_TIEUP_VARIABLE table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class MIS_TRACK_TIEUP_VARIABLE extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** Function insert added by Nitesh
        This function is used to insert record in the TRACK_TIEUP_VARIABLE table.
        * @param  $profileId Int
        * 
        **/
	public function insert($adnetwork,$account,$campaign,$adgroup,$keyword_tieup,$match,$lmd,$id){
               
                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "INSERT INTO MIS.TRACK_TIEUP_VARIABLE VALUES('',:adnetwork,:account,:campaign,:adgroup,:keyword_tieup,:match,:lmd,:id,:now)";
					$res = $this->db->prepare($sql);
					
				  	$res->bindValue(":id", $id, PDO::PARAM_INT);
				  	$res->bindValue(":adnetwork", $adnetwork, PDO::PARAM_STR);
				  	$res->bindValue(":account", $account, PDO::PARAM_STR);
				  	$res->bindValue(":campaign", $campaign, PDO::PARAM_STR);
				  	$res->bindValue(":adgroup", $adgroup, PDO::PARAM_STR);
				  	$res->bindValue(":keyword_tieup", $keyword_tieup, PDO::PARAM_STR);
				  	$res->bindValue(":match", $match, PDO::PARAM_STR);
				  	$res->bindValue(":lmd", $lmd, PDO::PARAM_STR);
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
