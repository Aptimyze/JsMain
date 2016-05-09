<?php
class NEWJS_FILTER extends TABLE{
       

        

        public function __construct($dbname="")
        {
			$dbname=$dbname?$dbname:"newjs_master";

			parent::__construct($dbname);
        }
	public function fetchEntry($profileid)
	{
		try
		{
			$res=null;
			if($profileid)
			{
				$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID=:PROFILEID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
				}
			}
			else
				throw new jsException("error in filter class no profileid");
				
			return $res;	
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	/**
	  * This function returns details from newjs.FILTERS for an array of profileids passed.
	**/

	public function fetchFilterDetailsForMultipleProfiles($profileIdArr)
	{
		if(is_array($profileIdArr))
		{
			foreach($profileIdArr as $key=>$pid)
			{
				if($key == 0)
					$str = ":PROFILEID".$key;
				else
					$str .= ",:PROFILEID".$key;
			}
			$sql = "SELECT * FROM newjs.FILTERS WHERE PROFILEID IN ($str) ";
			$res=$this->db->prepare($sql);
			unset($pid);
			foreach($profileIdArr as $key=>$pid)
			{
				$res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
			}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['PROFILEID']] = $row;
			}
			return $result;
		}
	}
	
	/**
	  * This function update detail into newjs.FILTERS filter for a profileid.
	**/

	public function updateFilters($profileId,$updStr)
	{
		try
		{
		
			$res=null;
			if($profileId && $updStr)
			{
				$sql="update newjs.FILTERS set $updStr where PROFILEID=:profileId";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileId",$profileId,PDO::PARAM_INT);
				
				$prep->execute();
				if($prep->rowCount())
				return true;
				else
				return false;
			}
			else
				throw new jsException("error in filter class either no profileid or no updStr");
			
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	/**
	  * This function insert row into newjs.FILTERS filter for a profileid.
	**/

	public function insertFilterEntry($profileId,$updStr)
	{
		try
		{
			$res=null;
			if($profileId && $updStr)
			{
				$sql="insert ignore into newjs.FILTERS set PROFILEID=:profileId,$updStr";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileId",$profileId,PDO::PARAM_INT);
				$prep->execute();
				if($prep->rowCount())
				return true;
				else
				return false;
			}
			else
				throw new jsException("error in filter class either no profileid or no updStr");
			
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	/*
	  * This function fetches the row depending upon some conidtion newjs.FILTERS filter for a profileid.
	**/

	public function fetchFilterDetails($profileId,$whrStr="",$selectStr="*")
	{
		try
		{
			$res=null;
			if($profileId)
			{
				$sql="select $selectStr from newjs.FILTERS where PROFILEID=:profileId ".$whrStr;
				
				
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileId",$profileId,PDO::PARAM_INT);
				
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
					return $res;
				}
			}
			else
				throw new jsException("error in filter class either no profileid or no updStr");
			
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
        public function setAllFilters($profile)
        {
                if(!$profile )
                        throw new jsException("","PROFILEID IS BLANK IN newjs_FILTERS.class.php");
                try
                {
                        $sql="REPLACE INTO newjs.FILTERS (PROFILEID,AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE,INCOME,COUNT,HARDSOFT) VALUES(:PROFILEID,:AGE,:MSTATUS,:RELIGION,:CASTE,:COUNTRY_RES,:CITY_RES,:MTONGUE,:INCOME,:COUNT,:HARDSOFT)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profile, PDO::PARAM_INT);
                        $prep->bindValue(":AGE", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":MSTATUS", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":RELIGION", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":CASTE", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":COUNTRY_RES", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":CITY_RES", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":MTONGUE", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":INCOME", "Y", PDO::PARAM_STR);
                        $prep->bindValue(":COUNT", 1, PDO::PARAM_INT);
                        $prep->bindValue(":HARDSOFT", "Y", PDO::PARAM_STR);
                        $prep->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return false;
        }

}
?>
