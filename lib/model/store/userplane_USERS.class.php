<?php
class USERPLANE_USERS extends TABLE{
       

        
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function isOnline($profileid)
        {
		try 
		{
			$sql="select userID from userplane.users where userID=:pid";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":pid", $profileid, PDO::PARAM_INT);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
				return true;
			return false;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function get($profileIdStr,$giveResultsInKey='0',$SearchParamtersObj='')
	{
		try 
		{
			$hh  = "00";
			$min =SearchConfig::$jsOnline ;
			if($min>=60)
			{
				$hh = $min/60;
				if(strlen($hh)==1)
					$hh="0".$hh;
				$min = $min%60;
				if(strlen($min)==1)
					$min="0".$min;
			}

			if($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='M' && $giveResultsInKey='spaceSeperator')
			{
				$sql = "select A.userID from userplane.users A , newjs.SEARCH_MALE B where A.userID=B.PROFILEID";
				if(SearchConfig::$jsOnline>0)
					$sql.=" AND lastTimeOnline>SUBTIME(now(),'$hh:$min:00')";
				$flag=1;
			}
			elseif($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='F' && $giveResultsInKey='spaceSeperator')
			{
				$sql = "select A.userID from userplane.users A , newjs.SEARCH_FEMALE B where A.userID=B.PROFILEID";
				if(SearchConfig::$jsOnline>0)
					$sql.=" AND lastTimeOnline>SUBTIME(now(),'$hh:$min:00')";
				$flag=1;
			}
			else
				$sql = "SELECT userID FROM userplane.users";

			if($profileIdStr)
			{
				if($flag)
					$sql.=" AND userID IN ($profileIdStr)";
				else
					$sql.=" WHERE userID IN ($profileIdStr)";
			}
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($giveResultsInKey=='1')
					$resultArr[$row['userID']] = 1;
				elseif($giveResultsInKey=='spaceSeperator')
					$resultArr.=$row['userID']." ";
				else
					$resultArr[] = $row['userID'];
			}
			return $resultArr;
		}
		catch(PDOException $e)
                {
                        jsException::nonCriticalError("lib/model/store/userplane_USERS.class.php(2)-->.$sql".$e);
                        return '';
                }
	}

	public function getNew($profileIdStr,$giveResultsInKey='0',$SearchParamtersObj='')
	{
		try 
		{
			if($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='M' && $giveResultsInKey='spaceSeperator')
			{
				$table = "newjs.SEARCH_MALE";
			}
			elseif($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='F' && $giveResultsInKey='spaceSeperator')
			{
				$table = "newjs.SEARCH_FEMALE";
			}
			if($table)
			{
				$sql = "select PROFILEID FROM $table where ";
				$sql.="PROFILEID IN ($profileIdStr)";
				$res = $this->db->prepare($sql);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$resultArr.=$row['PROFILEID']." ";
				}
			}
			else
			{
				//LAVESH : return space seperator.
			}
			return $resultArr;
		}
		catch(PDOException $e)
                {
                        jsException::nonCriticalError("lib/model/store/userplane_USERS.class.php(2)-->.$sql".$e);
                        return '';
                }
	}
}
?>
