<?php

/**
 * Store class for the table picture.COVER_PHOTO
 *
 * @author nitish
 */
class COVER_PHOTO extends TABLE{
    
    public function __construct($dbname = null) {
        parent::__construct($dbname);
    }
    
    /*Function to select cover photo for the given profileid
     * @input: profileid
     * @output: photoid
     */ 
    public function selectCoverPhoto($profileid){
        try{
            $sql = "SELECT PHOTOID FROM PICTURE.COVER_PHOTO WHERE PROFILEID = :PROFILEID";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $pdoStatement->execute();
            while($result = $pdoStatement->fetch(PDO::FETCH_ASSOC)){
                $photoid = $result['PHOTOID'];
            }
            return $photoid;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*Function to insert cover photo id for the given profileid
     * @input: profileid, photoid
     * @output: success or failure
     */
    public function insertCoverPhoto($profileid, $photoid){
        try{
            $sql = "REPLACE INTO PICTURE.COVER_PHOTO (PROFILEID, PHOTOID) VALUES (:PROFILEID, :PHOTOID)";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $pdoStatement->bindValue(":PHOTOID",$photoid,PDO::PARAM_STR);
            $pdoStatement->execute();
            $count = $pdoStatement->rowCount();
            if($count > 0)
                return true;
            else
                return false;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
