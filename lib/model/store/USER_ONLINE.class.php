<?php
class USER_ONLINE extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function isOnline($profileid)
        {
		try 
		{
			$sql="select USER from bot_jeevansathi.user_online where USER=:pid";
			
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":pid",$profileid,PDO::PARAM_INT);
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
	public function get($profileIdStr='',$giveResultsInKey='0',$SearchParamtersObj='')
	{
		try 
		{
			$hh  = "00";
			$min = SearchConfig::$gtalkOnline;
			if($min>=60)
			{
				$hh = $min/60;
				if(strlen($hh)==1)
					$hh="0".$hh;
				$min = $min%60;
				if(strlen($min)==1)
					$min="0".$min;
			}

			if($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='M' && $giveResultsInKey=='spaceSeperator')
			{
				$sql = "select USER from bot_jeevansathi.user_online A , newjs.SEARCH_MALE B where USER=B.PROFILEID";
				if(SearchConfig::$gtalkOnline>0)
					$sql.=" AND lastTimeOnline>SUBTIME(now(),'$hh:$min:00')";
				$flag=1;
			}
			elseif($SearchParamtersObj && $SearchParamtersObj->getGENDER()=='F' && $giveResultsInKey=='spaceSeperator')
			{
				$sql = "select USER from bot_jeevansathi.user_online A , newjs.SEARCH_FEMALE B where USER=B.PROFILEID";
				if(SearchConfig::$gtalkOnline>0)
					$sql.=" AND lastTimeOnline>SUBTIME(now(),'$hh:$min:00')";
				$flag=1;
			}
			else
				$sql = "SELECT USER FROM bot_jeevansathi.user_online";
			if($profileIdStr)
			{
				if($flag)
					$sql.=" AND USER IN ($profileIdStr)";
				else
					$sql.=" WHERE USER IN ($profileIdStr)";
			}
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($giveResultsInKey=='1')
					$resultArr[$row['USER']] = 1;
				elseif($giveResultsInKey=='spaceSeperator')
					$resultArr.=$row['USER']." ";
				else
					$resultArr[] = $row['USER'];
			}
			return $resultArr;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			//throw new jsException($e);
			jsException::nonCriticalError("USER_ONLINE.class.php(2)-->.$sql".$e);
                        return 0;
		}
	}
			
}
?>
