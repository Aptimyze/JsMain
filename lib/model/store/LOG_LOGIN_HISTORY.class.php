<?php
class LOG_LOGIN_HISTORY extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function loginHistory($pid,$sqlFoundRows='',$limit='',$limitStart='')
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT";
					if($sqlFoundRows !='')
						$sql.= " SQL_CALC_FOUND_ROWS ";	
					$sql.=" IPADDR,CONVERT_TZ(TIME,'SYSTEM','right/Asia/Calcutta')  as TIME FROM newjs.LOG_LOGIN_HISTORY WHERE PROFILEID=:PROFILEID UNION Select IPADDR,CONVERT_TZ(TIME,'SYSTEM','right/Asia/Calcutta')  as TIME FROM newjs.LOG_LOGIN_HISTORY_TEMP WHERE PROFILEID=:PROFILEID  ORDER BY TIME DESC";
					if($limit!='')
					{
						if($limitStart=="")
							$limitStart = 0;
						$sql.= " LIMIT :LIMIT_START , :LIMIT";
					}

					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					if($limit != '')
					{
						$prep->bindValue(":LIMIT_START",$limitStart,PDO::PARAM_INT);
						$prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
					}
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					if($sqlFoundRows !='')
					{
						$prep = $this->db->prepare('SELECT FOUND_ROWS() as CNT');
                        			$prep->execute();
                        			if($result = $prep->fetch(PDO::FETCH_ASSOC))
							$res["FOUND_ROWS"] = $result['CNT'];
					}
					//print_r($res);
					return $res;
				}	
			}
			catch(PDOException $e)
			{
			
				throw new jsException($e);
			}
		}
	
	
		
		
}
?>
