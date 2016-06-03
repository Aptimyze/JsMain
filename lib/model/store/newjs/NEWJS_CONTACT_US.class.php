<?php


class NEWJS_CONTACT_US extends TABLE
{
    public function __construct($szDBName="")
    {
        parent::__construct($szDBName);
    }

    public function fetch_All_Contact(&$arrRef_Result)
    {
        try{
            
            $sql = "SELECT * from CONTACT_US";

            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            
            $arrRef_Result = $pdoStatement->fetchAll();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function fetchPrintBillData($center)
    {
        try{
            
            $sql = "SELECT ADDRESS, PHONE, MOBILE FROM newjs.CONTACT_US WHERE NAME LIKE '%$center%'";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }    
}
?>
