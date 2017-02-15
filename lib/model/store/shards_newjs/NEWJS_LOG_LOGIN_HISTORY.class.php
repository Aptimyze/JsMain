<?php
class NEWJS_LOG_LOGIN_HISTORY extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function loginHistory($pid)
        {
			if(!$pid)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
			try 
			{ 
					$sql="SELECT IPADDR,CONVERT_TZ(TIME,'SYSTEM','right/Asia/Calcutta')  as TIME FROM newjs.LOG_LOGIN_HISTORY WHERE PROFILEID=:PROFILEID ORDER BY TIME DESC";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					//print_r($res);
					return $res;
			
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
		
		public function insertIntoLogLoginHistory($pid,$ip,$currentTime='')
        {
			if(!$pid)
				throw new jsException("","VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
			try 
			{
					$logTime=$currentTime ? $currentTime : date("Y-m-d H:i:s");
					$sql="INSERT INTO LOG_LOGIN_HISTORY(PROFILEID,IPADDR,TIME) values (:profileid,:ip,:logTime)";
					
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
					$prep->bindValue(":ip",$ip,PDO::PARAM_STR);
					$prep->bindValue(":logTime",$logTime,PDO::PARAM_STR);
					return $prep->execute();
					
			}
			catch(PDOException $e)
			{			
				throw new jsException($e);
			}
		}
		
	
		
		
}
?>
