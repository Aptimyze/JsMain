<?php

class billing_UPGRADE_ORDERS extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
    $this->PROFILEID_BIND_TYPE = "INT";
    $this->ORDERID_BIND_TYPE = "STR";
    $this->BILLID_BIND_TYPE = "INT";
    $this->OLD_BILLID_BIND_TYPE = "INT";
    $this->DEACTIVATED_STATUS_BIND_TYPE = "STR";
    $this->UPGRADE_STATUS_BIND_TYPE = "STR";
    $this->ENTRY_DT_BIND_TYPE = "STR";
    $this->MEMBERSHIP_BIND_TYPE = "STR";
    $this->REASON_BIND_TYPE = "STR";
  }

	/**
     * Function to insert upgrade order entry into UPGRADE_ORDERS table
     *
     * @param   $params
     * @return  none
     */ 
    public function addOrderUpgradeEntry($params=array())
    {
        try
        {
            if(is_array($params) && count($params)>0){
                $keysArr = array_keys($params);
                $keysStr = implode(",", $keysArr);
                $valuesStr = ":".implode(",:", $keysArr);
                $sql = "INSERT INTO billing.UPGRADE_ORDERS(".$keysStr.") VALUES (".$valuesStr.")" ;
                $res = $this->db->prepare($sql);
                foreach ($params as $key => $value) {
                  $res->bindValue(":".$key,$value,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
                }
                $res->execute();
                return $this->db->lastInsertId();
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }


  /**
     * Function to update upgrade order entry into UPGRADE_ORDERS table
     *
     * @param   $orderid,$params
     * @return  none
     */ 
    public function updateOrderUpgradeEntry($orderid,$params=array())
    {
        try
        {
            if(is_array($params) && count($params)>0){
                foreach ($params as $key => $value) {
                  $updateStr .= "".$key."=:".$key.",";
                }
                $updateStr = substr($updateStr, 0,-1);
                $sql = "UPDATE billing.UPGRADE_ORDERS SET ".$updateStr." WHERE ORDERID=:ORDERID";
                $res=$this->db->prepare($sql);
                foreach ($params as $k => $v) {
                  $res->bindValue(":".$k,$v,constant('PDO::PARAM_'.$this->{$k.'_BIND_TYPE'}));
                }
                $res->bindValue(":ORDERID",$orderid,PDO::PARAM_STR);
                $res->execute();
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }



  /**
     * Function to check whether entry with given order id exists or not
     *
     * @param   $orderid,$profileid(optional)
     * @return  $details
     */ 
  public function isUpgradeEntryExists($orderid,$profileid="")
  {
    try
    { 
        if(!$profileid){
          $profileid = "";
        }
        $sql = "SELECT * FROM billing.UPGRADE_ORDERS WHERE ORDERID = :ORDERID";
        if($profileid != ""){
          $sql .= " AND PROFILEID = :PROFILEID";
        }
        $res=$this->db->prepare($sql);
        $res->bindValue(":ORDERID", $orderid,constant('PDO::PARAM_'.$this->{'ORDERID_BIND_TYPE'}));
        $res->bindValue(":PROFILEID", $profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
        if($row){
          return $row;
        }
        else{
          return null;
        }
    }
    catch(PDOException $e)
    {
        throw new jsException($e);
    }
  }
  /**
     * Function to check whether entry with given order id exists or not
     *
     * @param   $orderid,$profileid(optional)
     * @return  $details
     */ 
  public function getAllEntries()
  {
    try
    { 
        $sql = "SELECT GROUP_CONCAT(BILLID) AS BILLIDSTR FROM billing.UPGRADE_ORDERS WHERE UPGRADE_STATUS = 'DONE' AND BILLID <> 0";
        $res=$this->db->prepare($sql);
        $res->execute();
        if($row=$res->fetch(PDO::FETCH_ASSOC)){
          return $row["BILLIDSTR"];
        }
        return null;
    }
    catch(PDOException $e)
    {
        throw new jsException($e);
    }
  }
  
  
  /**
     * Function to update upgrade order entry into UPGRADE_ORDERS table
     *
     * @param   id,$params
     * @return  none
     */ 
    public function updateOrderUpgradeEntryById($id,$params=array())
    {
        try
        {
            if(is_array($params) && count($params)>0){
                foreach ($params as $key => $value) {
                  $updateStr .= "".$key."=:".$key.",";
                }
                $updateStr = substr($updateStr, 0,-1);
                $sql = "UPDATE billing.UPGRADE_ORDERS SET ".$updateStr." WHERE ID=:ID";
                $res=$this->db->prepare($sql);
                foreach ($params as $k => $v) {
                  $res->bindValue(":".$k,$v,constant('PDO::PARAM_'.$this->{$k.'_BIND_TYPE'}));
                }
                $res->bindValue(":ID",$id,PDO::PARAM_STR);
                $res->execute();
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
