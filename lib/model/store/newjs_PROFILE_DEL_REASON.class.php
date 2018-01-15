<?php
class NEWJS_PROFILE_DEL_REASON extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function profileDeletionData($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT PROFILE_DEL_DATE,DEL_REASON,SPECIFIED_REASON FROM newjs.PROFILE_DEL_REASON WHERE PROFILEID = :PROFILEID ORDER BY PROFILE_DEL_DATE ASC";
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
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
		public function getSuccessStoriesCount()
        {
			try 
			{
					$sql="SELECT SQL_CACHE count(*) AS CNT FROM newjs.PROFILE_DEL_REASON WHERE DEL_REASON = 1";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res= $result;
					}
					return $res;			
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
	public function Replace($username,$delete_reason,$specify_reason,$profileid)
	{if(!$specify_reason)
		{
			$specify_reason="No Reason";
		}
		try{
			$now=date("Y-m-d H:i:s");
			$sql = "INSERT IGNORE INTO newjs.PROFILE_DEL_REASON(USERNAME,DEL_REASON,SPECIFIED_REASON,PROFILE_DEL_DATE,PROFILEID) VALUES(:USERNAME,:delete_reason,:specify_reason,:now,:profileid)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":delete_reason",$delete_reason,PDO::PARAM_STR);
			$prep->bindValue(":specify_reason",$specify_reason,PDO::PARAM_STR);
			$prep->bindValue(":now",$now,PDO::PARAM_STR);
			$prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
			$prep->execute();
			
		}
		catch(PDOException $e)
		{	
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
		
}
?>
