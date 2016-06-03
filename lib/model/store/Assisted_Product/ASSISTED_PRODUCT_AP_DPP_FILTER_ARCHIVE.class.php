<?php
/*
This class is used to  all db related queries i AP_DPP_FILTER_ARCHIVE table.
*/
class AP_DPP_FILTER_ARCHIVE extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the full data corresponding to a profileid and status
	@param 1) profileId 2) status
	@return array
	*/
	public function fetchCurrentDPP($profileid,$status='LIVE',$whrStr="")
	{
		if($profileid)
		{
			try
			{
				$sql = "SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID=:profileid ". $whrStr;
				if($status)
					$sql.=" AND STATUS IN(:status)";
				$sql.=" ORDER BY DPP_ID DESC LIMIT 1";
				$res = $this->db->prepare($sql);
				$res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
				if($status)
				$res->bindValue(":status", $status, PDO::PARAM_STR);
				$res->execute();
				if($result = $res->fetch(PDO::FETCH_ASSOC))
					return $result;
				return '';
				
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		else
		{
			throw new jsException("","PROFILEID IS BLANK IN AP_DPP_FILTER_ARCHIVE store class function fetchCurrentDpp");
		}
	}
		
		/*
	This function updates the full data corresponding to a profileid and some conciditon
	@param 1) profileId 2) update string 30 where condition string
	@return 
	*/
	public function updateDPP($profileid,$updStr,$whrStr="")
	{
		if($profileid && $updStr)
		{
			try
			{
				$sql = "UPDATE Assisted_Product.AP_DPP_FILTER_ARCHIVE SET ". $updStr ." WHERE PROFILEID=:profileid ".$whrStr;
				
				$res = $this->db->prepare($sql);
				$res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
				$res->execute();
				
				
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		else
		{
			throw new jsException("","PROFILEID OR update string  IS BLANK IN AP_DPP_FILTER_ARCHIVE store class function update dPP ");
		}
	}
}
?>
