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
     * @param   $fields,$assigned flag
     * @return  array of rows
     */ 
   public function getExclusiveMembers($fields="*",$assigned=false,$orderBy="")
  {
    try
    {
        $sql = "SELECT ".$fields." FROM billing.EXCLUSIVE_MEMBERS";
        if($assigned==true)
            $sql = $sql." WHERE ASSIGNED = 'Y'";
        else
            $sql = $sql." WHERE ASSIGNED = 'N'";
        if($orderBy)
          $sql = $sql." ORDER BY ".$orderBy." DESC";
        $res = $this->db->prepare($sql);
        $res->execute();
        while($result=$res->fetch(PDO::FETCH_ASSOC))
        {
            $rows[$result["BILL_ID"]] = $result;
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
  public function updateExclusiveMemberAssignment($pid,$assigned_to=NULL,$assigned_dt="0000-00-00")
  {
    try
    {
      if($pid)
      {
        $sql = "UPDATE billing.EXCLUSIVE_MEMBERS SET ASSIGNED_TO=:ASSIGNED_TO,ASSIGNED_DT=:ASSIGNED_DT,ASSIGNED=:ASSIGNED WHERE PROFILEID=:PROFILEID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
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
}
?>