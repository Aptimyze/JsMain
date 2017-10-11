<?php

class billing_EXCLUSIVE_MEMBERS extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
  }

	/**
     * Function to insert profileid into EXCLUSIVE_MEMBERS table
     *
     * @param   $params
     * @return  none
     */ 
  public function addExclusiveMember($params)
  {
    try
    {
      if(is_array($params) && $params)
      {
        $sql = "INSERT IGNORE INTO billing.EXCLUSIVE_MEMBERS (ID,PROFILEID,ASSIGNED_TO,ASSIGNED,BILLING_DT,BILL_ID,ASSIGNED_DT) VALUES(NULL,:PROFILEID,:ASSIGNED_TO,:ASSIGNED,:BILLING_DT,:BILL_ID,:ASSIGNED_DT)";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $params["PROFILEID"], PDO::PARAM_INT);
        $res->bindValue(":ASSIGNED_DT", $params["ASSIGNED_DT"], PDO::PARAM_STR);
        $res->bindValue(":ASSIGNED_TO", $params["ASSIGNED_TO"], PDO::PARAM_STR);
        $res->bindValue(":ASSIGNED", $params["ASSIGNED"], PDO::PARAM_STR);
        $res->bindValue(":BILLING_DT", $params["BILLING_DT"], PDO::PARAM_STR);
        $res->bindValue(":BILL_ID", $params["BILL_ID"], PDO::PARAM_INT);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

    /**
     * Function to get profile details from EXCLUSIVE_MEMBERS table
     *
     * @param   $fields="*",$assigned=false,$orderBy="",$assignedTo="",
     *     $assignedGtDt="*",$formatBillIdWise=true
     * @return  array of rows
     */ 
   public function getExclusiveMembers($fields="*",$assigned=false,$orderBy="",$assignedTo="",$assignedGtDt="",$formatBillIdWise=true)
  {
    try
    {
        $sql = "SELECT ".$fields." FROM billing.EXCLUSIVE_MEMBERS";
        if($assigned==true)
          $sql = $sql." WHERE ASSIGNED = 'Y'";
        else
          $sql = $sql." WHERE ASSIGNED = 'N'";
        if(!empty($assignedTo)){
          $sql = $sql." AND ASSIGNED_TO = :ASSIGNED_TO";
        }
        if(!empty($assignedGtDt)){
          $sql = $sql." AND ASSIGNED_DT >= :ASSIGNED_DT";
        }
        if($orderBy)
          $sql = $sql." ORDER BY ".$orderBy." DESC";
        $res = $this->db->prepare($sql);
        if(!empty($assignedTo)){
          $res->bindValue(":ASSIGNED_TO", $assignedTo, PDO::PARAM_STR);
        }
        if(!empty($assignedGtDt)){
          $res->bindValue(":ASSIGNED_DT", $assignedGtDt, PDO::PARAM_STR);
        }
        $res->execute();
        while($result=$res->fetch(PDO::FETCH_ASSOC))
        {
          if($formatBillIdWise==true){
            $rows[$result["BILL_ID"]] = $result;
          }
          else{
            $rows[] = $result['PROFILEID'];
          }
        }
        return $rows;
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  /**
     * Function to assign/unassign exclusive member
     *
     * @param   $pid,$assigned_to,$assigned_dt
     * @return  none
     */ 
  public function updateExclusiveMemberAssignment($pid,$assigned_to=NULL,$assigned_dt="0000-00-00",$billid)
  {
    try
    {
      if($pid && $billid)
      {
        $sql = "UPDATE billing.EXCLUSIVE_MEMBERS SET ASSIGNED_TO=:ASSIGNED_TO,ASSIGNED_DT=:ASSIGNED_DT,ASSIGNED=:ASSIGNED WHERE PROFILEID=:PROFILEID AND BILL_ID=:BILL_ID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
        $res->bindValue(":BILL_ID", $billid, PDO::PARAM_INT);
        $res->bindValue(":ASSIGNED_TO", $assigned_to, PDO::PARAM_STR);
        $res->bindValue(":ASSIGNED_DT", $assigned_dt, PDO::PARAM_STR);
        if($assigned_to==NULL)
          $res->bindValue(":ASSIGNED", 'N', PDO::PARAM_STR);
        else
          $res->bindValue(":ASSIGNED", 'Y', PDO::PARAM_STR);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  /**
     * Function to remove exclusive member
     *
     * @param   $pid,
     * @return  none
     */ 
  public function removeExclusiveMemberEntry($pid)
  {
    try
    {
      if($pid)
      {
        $sql = "DELETE FROM billing.EXCLUSIVE_MEMBERS WHERE PROFILEID=:PROFILEID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
  public function archiveProfile($billid)
  {
    try
    {
      if($billid)
      {
        $sql = "INSERT INTO billing.EXCLUSIVE_MEMBERS_LOG SELECT * FROM billing.EXCLUSIVE_MEMBERS WHERE BILL_ID=:BILLID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
  public function deleteExclusiveEntry($billid)
  {
    try
    {
      if($billid)
      {
        $sql = "DELETE FROM billing.EXCLUSIVE_MEMBERS WHERE BILL_ID=:BILLID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
  public function getExclusiveMembersList($billingDate)
  {
    try
    {
        $sql = "SELECT * FROM billing.EXCLUSIVE_MEMBERS WHERE BILLING_DT<=:BILLING_DT";
        $res = $this->db->prepare($sql);
        $res->bindValue(":BILLING_DT", $billingDate, PDO::PARAM_STR);
        $res->execute();
        while($result=$res->fetch(PDO::FETCH_ASSOC))
            $rows[] = $result;
        return $rows;
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
	
}
?>
