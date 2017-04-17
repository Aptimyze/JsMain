<?php
class userplane_recentusers extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="newjs_masterRep")
        {
			parent::__construct($dbname);
        }
	public function replacedata($pid)
	{
		try {
			/*
			$time=date("Y-m-d G:i:s");
			$sql="replace into userplane.recentusers(userID,lastTimeOnline) values(:PID,:TIME)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$pid, PDO::PARAM_INT);
			$prep->bindValue(":TIME",$time, PDO::PARAM_STR);
			$prep->execute();
			*/
			
		}
		catch (Exception $e) {
	            throw new jsException($e);
                }
	}
	public function DeleteRecord($pid)
	{
		try {
			/*
                        $pid=intval($pid);
                        $sql="delete from userplane.recentusers where userID=:PID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PID",$pid, PDO::PARAM_INT);
                        $prep->execute();
			*/

                }
                catch (Exception $e) {
                    throw new jsException($e);
                }
	}
	public function fetchOnlineProfiles($userArr)
        {
		try 
		{
			$userArr = implode(',', $userArr);
			$sql="select userID from userplane.recentusers where userID in ($userArr)";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC)){
				$res[] = $row['userID'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $res;
	}
        public function isOnline($profileid)
        {
                try
                {
                        $sql="select userID from userplane.recentusers where userID=:pid";
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
		
}
?>
