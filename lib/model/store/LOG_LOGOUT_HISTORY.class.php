<?php
class LOG_LOGOUT_HISTORY extends TABLE{
       

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function logoutHistory($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT IPADDR,CONVERT_TZ(TIME,'SYSTEM','right/Asia/Calcutta')  as TIME FROM LOG_LOGOUT_HISTORY WHERE PROFILEID=:PROFILEID ORDER BY TIME DESC";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					return $res;
				}	
			}
			catch(PDOException $e)
			{
				
				throw new jsException($e);
			}
		}
	public function insert($pid,$ip,$currentTime='')
	{
		try{
			$logTime=$currentTime ? $currentTime :  date("Y-m-d H:i:s");
                        $sql="insert into LOG_LOGOUT_HISTORY(PROFILEID,IPADDR,`TIME`) values (:PID,:IP,:TIME)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PID",$pid,PDO::PARAM_INT);
			$prep->bindValue(":IP",$ip,PDO::PARAM_STR);
			$prep->bindValue(":TIME",$logTime,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
                        {

                                throw new jsException($e);
                        }
	}
	
	
		
		
}
?>
