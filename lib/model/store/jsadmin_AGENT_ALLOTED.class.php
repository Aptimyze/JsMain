<?php
class AGENT_ALLOTED extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}

	public function getLastAgentAlloted($method,$center='')
	{
		try
		{
			$sql="SELECT USER FROM jsadmin.AGENT_ALLOTED where METHOD=:METHOD";
			if($center)
				$sql .=" AND UPPER(CENTER)=UPPER(:CENTER)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":METHOD",$method,PDO::PARAM_STR);
			if($center)
				$prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
        			$lastExecutive = $result['USER'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $lastExecutive;

	}
	public function updateLastAllotedAgent($allotTo,$method,$center='')
	{
		try
                {
			$allotTo =trim($allotTo);
                        $sql="UPDATE jsadmin.AGENT_ALLOTED SET USER=:USER WHERE METHOD=:METHOD";
			if($center)
				$sql .=" AND UPPER(CENTER)=UPPER(:CENTER)";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":USER",$allotTo,PDO::PARAM_STR);
			$prep->bindValue(":METHOD",$method,PDO::PARAM_STR);
			if($center)
				$prep->bindValue(":CENTER",$center,PDO::PARAM_STR);	
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
        public function getAllocationLimitForCenter($center,$method='')
        {
                try
                {
                        $sql="SELECT ALLOCATION_LIMIT from jsadmin.AGENT_ALLOTED WHERE UPPER(CENTER)=UPPER(:CENTER)";
			if($method)
				$sql .=" AND METHOD=:METHOD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
			if($method)
				$prep->bindValue(":METHOD",$method,PDO::PARAM_STR);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $allocationLimit = $result['ALLOCATION_LIMIT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $allocationLimit;

        }
        public function getLocalityLimit($method)
        {
                try
                {
                        $sql="SELECT CENTER,ALLOCATION_LIMIT from jsadmin.AGENT_ALLOTED WHERE METHOD=:METHOD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":METHOD",$method,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
                                $allocationLimit 	=$result['ALLOCATION_LIMIT'];
				$center 		=$result['CENTER'];
				$allocLimitArr[$center]=$allocationLimit;
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $allocLimitArr;
        }
	public function updateAllocationLimit($limit,$center,$method)
	{
                try
                {
                        $sql="UPDATE jsadmin.AGENT_ALLOTED SET ALLOCATION_LIMIT=:ALLOCATION_LIMIT WHERE UPPER(CENTER)=UPPER(:CENTER) AND METHOD=:METHOD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":METHOD",$method,PDO::PARAM_STR);
			$prep->bindValue(":ALLOCATION_LIMIT",$limit,PDO::PARAM_STR);
                        $prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}	

}
?>
