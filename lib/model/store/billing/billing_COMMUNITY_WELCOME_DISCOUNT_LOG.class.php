<?php


class billing_COMMUNITY_WELCOME_DISCOUNT_LOG extends TABLE
{
    
	public function __construct($dbname=""){
        parent::__construct($dbname);
    }

    public function addEntry($profileid,$discount,$community,$entryDt){
        try{
            $sql = "INSERT IGNORE INTO billing.COMMUNITY_WELCOME_DISCOUNT_LOG (PROFILEID,DISCOUNT,COMMUNITY,ENTRY_DT) VALUES(:PROFILEID,:DISCOUNT,:COMMUNITY,:ENTRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":DISCOUNT", $discount, PDO::PARAM_INT);
            $res->bindValue(":COMMUNITY", $community, PDO::PARAM_INT);
            $res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_INT);
            $res->execute();
        }
        catch(Exception $e){
            throw new jsException($e);
        }
    }
}
?>
