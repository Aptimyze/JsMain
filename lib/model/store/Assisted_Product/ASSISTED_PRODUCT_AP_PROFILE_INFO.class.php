<?php
/*
This class is used to send queries to AP_PROFILE_INFO table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_PROFILE_INFO extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the profile id's which are LIVE  
	@param 
	@return an array of profileid 
	*/
	public function getAPProfiles()
	{
		try
		{
			$sql = "SELECT PROFILEID FROM Assisted_Product.AP_PROFILE_INFO WHERE STATUS='LIVE' AND SEND='Y' order by PROFILEID DESC";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($result=$res->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[]=$result;
			}
			return $profiles;
		}
		catch(PDOException $e)
        {
			throw new jsException($e);
        }
		return null;
	}
        
	/*
	This function fetches the profile id's which are LIVE after checking with temp table which store
         * already sent records in case of cron failure ordering them with last login date
	@return an array of profileid 
	*/
    public function getAPProfilesResumed()
    {
      try {
        $sql = "SELECT I.PROFILEID FROM Assisted_Product.AP_PROFILE_INFO AS I LEFT JOIN Assisted_Product.AP_PROFILE_INFO_LOG AS L ON I.PROFILEID = L.PROFILEID LEFT JOIN newjs.JPROFILE AS J ON I.PROFILEID = J.PROFILEID  WHERE I.STATUS='LIVE' AND I.SEND='Y' AND L.PROFILEID IS NULL AND J.ACTIVATED!='D' and J.activatedkey=1 order by J.LAST_LOGIN_DT DESC";
        $res = $this->db->prepare($sql);
        $res->execute();
        while($result=$res->fetch(PDO::FETCH_ASSOC)) {
          $profiles[]=$result;
        }
        return $profiles;
      }
      catch(PDOException $e) {
        throw new jsException($e);
      }
      return null;
    }
	public function Delete($pid)
	{
		try{
			$sql="DELETE FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID=:profileid";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
}
?>
