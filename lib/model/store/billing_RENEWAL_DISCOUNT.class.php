<?php

class billing_RENEWAL_DISCOUNT extends TABLE {
  
  	public function __construct($dbname = "") {
    		parent::__construct($dbname);
  	}

    public function removedExpiredProfiles()
    {
        try
        {
                $expiry_dt = date("Y-m-d", time()-10*24*60*60);
                $sql="DELETE FROM billing.RENEWAL_DISCOUNT WHERE EXPIRY_DT<:EXPIRY_DT";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":EXPIRY_DT",$expiry_dt,PDO::PARAM_STR);
                $prep->execute();
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }
    public function insert($profileid, $discount, $expiry_dt)
    {
        try
        {
                $sql="INSERT IGNORE INTO billing.RENEWAL_DISCOUNT VALUES(:PROFILEID, :DISCOUNT, :EXPIRY_DT)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->bindValue(":DISCOUNT",$discount,PDO::PARAM_INT);
                $prep->bindValue(":EXPIRY_DT",$expiry_dt,PDO::PARAM_STR);
                $prep->execute();
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }
    public function getDiscount($profileid)
    {
        try
        {
                $sql="SELECT * FROM billing.RENEWAL_DISCOUNT WHERE PROFILEID=:PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
                $res = $prep->fetch(PDO::FETCH_ASSOC);
                return $res;
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }

    public function removeProfilesAfterDate($start)
    {
        try
        {
            $sql="DELETE FROM billing.RENEWAL_DISCOUNT WHERE EXPIRY_DT>=:START_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT",$start,PDO::PARAM_STR);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function getRenewalProfiles($profileStr)
    {
        try
        {
		$profileStr =trim($profileStr);		
                $sql="SELECT PROFILEID FROM billing.RENEWAL_DISCOUNT WHERE PROFILEID IN($profileStr)";
                $prep = $this->db->prepare($sql);
                $prep->execute();
                while($res = $prep->fetch(PDO::FETCH_ASSOC))
		{
		  $result[] =$res['PROFILEID'];	
		}
                return $result;
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }
}
