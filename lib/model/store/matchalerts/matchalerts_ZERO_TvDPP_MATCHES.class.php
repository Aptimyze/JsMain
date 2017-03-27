<?php

/* 
 * this store is used for operations in table matchalerts_ZERO_TvDPP_MATCHES
 */

class matchalerts_ZERO_TvDPP_MATCHES extends TABLE {
    public function __construct($dbname="")
	{
			$dbname = $dbname?$dbname:"matchalerts_slave";
			parent::__construct($dbname);
	}
    /*this function returns entry date for a profileid
    * @param - profileid
    * @return - date(entry)
    */
    public function getEntryDateForProfile($profileId){
        try{
            if($profileId){
                $sql="SELECT ENTRY_DATE FROM matchalerts.ZERO_TvDPP_MATCHES WHERE PROFILEID = :PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileId ,PDO::PARAM_INT);
                $prep->execute();
                $entryDate = $prep->fetch(PDO::FETCH_ASSOC);
                return $entryDate['ENTRY_DATE'];
            }
        }
        catch (PDOException $ex) {
            throw new jsException($ex);
        }
    }
    
    /*this function entry of a profileid
    * @param - profileid
    */
    public function deleteEntryOfProfile($profileId){
        try{
            if($profileId){
                $sql="DELETE FROM matchalerts.ZERO_TvDPP_MATCHES WHERE PROFILEID = :PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileId ,PDO::PARAM_INT);
                $prep->execute();
            }
        }
        catch (PDOException $ex) {
            throw new jsException($ex);
        }
    }
    
    /*this function inserts entry of a profileid
    * @param - profileid
    */
    public function insertEntryOfProfile($profileId,$date){
        try{
            if($profileId){
                $sql="INSERT INTO matchalerts.ZERO_TvDPP_MATCHES VALUES (:PROFILEID,:DATE)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileId ,PDO::PARAM_INT);
                $prep->bindValue(":DATE", $date ,PDO::PARAM_STR);
                $prep->execute();
            }
        }
        catch (PDOException $ex) {
            throw new jsException($ex);
        }
    }
}
