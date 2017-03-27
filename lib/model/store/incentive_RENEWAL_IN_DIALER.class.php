<?php

class incentive_RENEWAL_IN_DIALER extends TABLE{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    public function insertProfile($profileid,$priority,$campaignType)
    {
        try
        {
            $now=date('Y-m-d',time());
            $sql = "INSERT IGNORE INTO incentive.RENEWAL_IN_DIALER (PROFILEID,PRIORITY,CAMPAIGN_TYPE,ENTRY_DATE) VALUES(:PROFILEID,:PRIORITY,:CAMPAIGN_TYPE,:ENTRY_DATE)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
            $prep->bindValue(":PRIORITY",$priority,PDO::PARAM_INT);
	    $prep->bindValue(":CAMPAIGN_TYPE",$campaignType,PDO::PARAM_STR);	
            $prep->bindValue(":ENTRY_DATE",$now,PDO::PARAM_STR);
            $prep->execute();
        }catch (Exception $ex)
        {
            throw new jsException($e);
        }
    }
    
    public function fetchProfiles()
    {
        try
        {
             $sql = "SELECT PROFILEID,ELIGIBLE,PRIORITY FROM incentive.RENEWAL_IN_DIALER";
             $prep = $this->db->prepare($sql);
             $prep->execute();
             while($res=$prep->fetch(PDO::FETCH_ASSOC))
             {
                 $profiles[]["PROFILEID"] = $res["PROFILEID"];
                 $profiles[]["PRIORITY"]  = $res["PRIORITY"];
                 $profiles[]["ELIGIBLE"]  = $res["ELIGIBLE"];
             }
        } catch (Exception $ex)
        {
            throw new jsException($e);
        }
        return $profiles;
    }
    
    public function updateRenewalDialerEligibility($profileid,$eligible)
    {
        try
        {
            $sql = "UPDATE incentive.RENEWAL_IN_DIALER SET ELIGIBLE=:ELIGIBLE WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":ELIGIBLE",$eligible,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex)
        {
            throw new jsException($e);
        }
    }
    
    public function getRenewalDialerProfileBasedOnJoins($tableName, $fields)  // $tableName = $databaseName.$tableName (fullname)
    {
        try
        {
            $sql = "SELECT ".$fields." FROM incentive.RENEWAL_IN_DIALER id JOIN ".$tableName." tb USING (PROFILEID)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            }
            return $res;
        } catch (Exception $ex)
        {
            throw new jsException($e); 
        }
    }
    
    public function fetchRenewalDialerProfiles()
    {
        try
        {
            $sql = "SELECT PROFILEID FROM incentive.RENEWAL_IN_DIALER";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($res=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[] = $res["PROFILEID"];
            }
        } catch (Exception $ex)
        {
            throw new jsException($e);
        }
        return $profiles;
    }
}
?>
