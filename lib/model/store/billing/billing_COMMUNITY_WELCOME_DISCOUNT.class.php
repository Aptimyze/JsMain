<?php

class billing_COMMUNITY_WELCOME_DISCOUNT extends TABLE{
    
    public function __construct($dbName = "") {
        parent::__construct($dbName);
    }
    
    public function startTransaction()
	{
		$this->db->beginTransaction();
	}
    
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}
    
    public function markAllInactive(){
        try{
            $sql = "UPDATE billing.COMMUNITY_WELCOME_DISCOUNT SET ACTIVE = :ACTIVE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ACTIVE","N",PDO::PARAM_INT);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function insertCommunityWiseDiscount($params){
        if($params && is_array($params)){
            try{
                $sql = "INSERT INTO billing.COMMUNITY_WELCOME_DISCOUNT (COMMUNITY,CATEGORY_ID,DISCOUNT,ENTRY_BY,ENTRY_DT,ACTIVE) VALUES (:COMMUNITY, :CATEGORY_ID, :DISCOUNT, :ENTRY_BY, :ENTRY_DT, :ACTIVE)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":COMMUNITY",$params["COMMUNITY"],PDO::PARAM_INT);
                $res->bindValue(":CATEGORY_ID",$params["CATEGORY_ID"],PDO::PARAM_INT);
                $res->bindValue(":DISCOUNT",$params["DISCOUNT"],PDO::PARAM_INT);
                $res->bindValue(":ENTRY_BY",$params["ENTRY_BY"],PDO::PARAM_STR);
                $res->bindValue(":ENTRY_DT",$params["ENTRY_DT"],PDO::PARAM_STR);
                $res->bindValue(":ACTIVE",$params["ACTIVE"],PDO::PARAM_INT);
                $res->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
    
    public function getActiveCommunityWiseDiscount(){
        try{
            $sql = "SELECT * FROM billing.COMMUNITY_WELCOME_DISCOUNT WHERE ACTIVE = :ACTIVE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ACTIVE","Y",PDO::PARAM_STR);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $result[$row["CATEGORY_ID"]][$row["COMMUNITY"]] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getActiveGroupByCategories(){
        try{
            $sql = "SELECT * from billing.COMMUNITY_WELCOME_DISCOUNT WHERE ACTIVE = :ACTIVE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ACTIVE","Y",PDO::PARAM_INT);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $result[$row["CATEGORY_ID"]][]=$row["COMMUNITY"];
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>