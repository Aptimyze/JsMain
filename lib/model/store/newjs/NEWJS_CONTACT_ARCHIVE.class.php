<?php

/*This class is used to insert in CONTACT_ARCHIVE table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class NEWJS_CONTACT_ARCHIVE extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    /** Function insert added by Nitesh
     This function is used to insert record in the NEWJS_CONTACT_ARCHIVE table.
     * @param  $profileId Int
     *
     *
     */
    public function insert($id, $field) {
        
        if (!$id || !$field) throw new jsException("", "PROFILEID or field IS BLANK IN insertRecord() OF NEWJS_CONTACT_ARCHIVE.class.php");
        
        try {
            $now = date("Y-m-d G:i:s");
            $sql = "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES(:id,:field)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":id", $id, PDO::PARAM_INT);
            $res->bindValue(":field", $field, PDO::PARAM_STR);
            $res->execute();
            return $this->db->lastInsertId();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function fetchData($profileid,$field='EMAIL') {
        
        try {
            $sql = "SELECT * FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID=:PROFILEID AND FIELD=:FIELD";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":FIELD", $field, PDO::PARAM_STR);
            $res->execute();
            if($result = $res->fetch(PDO::FETCH_ASSOC))
            {
                return $result;
            }
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
