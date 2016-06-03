<?php
class TEMP_ALLOCATION_BUCKET extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}
	public function truncate()
	{
		$sql="TRUNCATE TABLE incentive.TEMP_ALLOCATION_BUCKET";
		$prep = $this->db->prepare($sql);
		$prep->execute();
		
	}	
	//returns profiles with  
	public function fetchProfiles($deAllMethodObj)
	{
		try
		{	
			$sql = "SELECT PROFILEID FROM incentive.TEMP_ALLOCATION_BUCKET WHERE EXEC=:EXE AND DISP=:DISPOSITION ORDER BY LAST_DISP_DT LIMIT :EXCEED";
		        $prep = $this->db->prepare($sql);
         		$prep->bindValue(":EXE",$deAllMethodObj->getUsername(),PDO::PARAM_STR);
         		$prep->bindValue(":DISPOSITION",$deAllMethodObj->getDisposition(),PDO::PARAM_STR);
         		$prep->bindValue(":EXCEED",$deAllMethodObj->getExceed(),PDO::PARAM_INT);
         		$prep->execute();
         		while($result=$prep->fetch(PDO::FETCH_ASSOC))
         		{
                 		$profileid[]=$result['PROFILEID'];
         		}
 		}       
 		catch(Exception $e)
 		{
         		throw new jsException($e);
 		}
		return $profileid;


	}
	public function insertProfiles($profile)
	{
		try
		{
			$sql="INSERT IGNORE INTO incentive.TEMP_ALLOCATION_BUCKET (PROFILEID,DISP,LAST_DISP_DT,EXEC) VALUES (:PROFILEID,:WILL_PAY,:ENTRY_DT,:ALLOTED_TO)";
			$prep = $this->db->prepare($sql);
        		$prep->bindValue(":PROFILEID",$profile['PROFILEID'],PDO::PARAM_STR);
			$prep->bindValue(":WILL_PAY",$profile['WILL_PAY'],PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$profile['ENTRY_DT'],PDO::PARAM_STR);
			$prep->bindValue(":ALLOTED_TO",$profile['ALLOTED_TO'],PDO::PARAM_STR);
        		$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}

	}
	public function fetchFinalExecutives($processObj)
        {
                try
                {
                        $sql="SELECT EXEC,count(*) as CNT FROM incentive.TEMP_ALLOCATION_BUCKET GROUP BY EXEC HAVING CNT>:LIMIT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":LIMIT",$processObj->getLimit(),PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $executives[] = $result['EXEC'].":".$result['CNT'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $executives;
        }
}	
?>
