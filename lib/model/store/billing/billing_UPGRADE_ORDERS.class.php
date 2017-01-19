<?php

class billing_UPGRADE_ORDERS extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
    $this->PROFILEID_BIND_TYPE = "INT";
    $this->ORDERID_BIND_TYPE = "STR";
    $this->BILLID_BIND_TYPE = "INT";
    $this->OLD_SERVICEID_BIND_TYPE = "STR";
    $this->OLD_BILLID_BIND_TYPE = "INT";
    $this->DEACTIVATED_STATUS_BIND_TYPE = "STR";
    $this->UPGRADE_STATUS_BIND_TYPE = "STR";
    $this->ENTRY_DT_BIND_TYPE = "STR";
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
                $sql = "INSERT IGNORE INTO billing.UPGRADE_ORDERS(".$keysStr.") VALUES (".$valuesStr.")" ;
                $res = $this->db->prepare($sql);
                foreach ($params as $key => $value) {
                  $res->bindValue(":".$key,$value,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
                }
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
                $keysArr = array_keys($params);
                $keysStr = implode(",", $keysArr);
                $valuesStr = ":".implode(",:", $keysArr);
                $updateStr = "";
                foreach ($params as $key => $value) {
                  $updateStr .= "".$key."=:".$key.",";
                }
                $updateStr = substr($updateStr, 0,-1);
                $sql = "INSERT INTO billing.UPGRADE_ORDERS (".$keysStr.") VALUES (.".$valuesStr.") ON DUPLICATE KEY UPDATE ".$updateStr;
                $res=$this->db->prepare($sql);
                foreach ($params as $k => $v) {
                  $res->bindValue(":".$k,$v,constant('PDO::PARAM_'.$this->{$k.'_BIND_TYPE'}));
                }
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
        $sql = "SELECT * FROM billing.UPGRADE_ORDERS WHERE ORDERID = :ORDERID";
        if($profileid != ""){
          $sql .= " AND PROFILEID = :PROFILEID";
        }
        $res=$this->db->prepare($sql);
        $res->bindValue(":ORDERID", $orderid,constant('PDO::PARAM_'.$this->{'ORDERID_BIND_TYPE'}));
        $res->bindValue(":PROFILEID", $entryStart,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
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
}
?>
