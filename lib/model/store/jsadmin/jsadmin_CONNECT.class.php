<?php
class jsadmin_CONNECT extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function fetchUser($id)
	{
		try
		{
			$sql="select USER from jsadmin.CONNECT where ID=:ID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ID",$id,PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$user=$result['USER'];

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $user;
	}

        /*public function fetchAgentSessionIDByUserID($userId)
        {
                try
                {
                        $sql="select ID from jsadmin.CONNECT where USER=:USER";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":USER",$userId,PDO::PARAM_INT);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $id=$result['ID'];

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $id;
        }*/
	public function deleteRowWithId($id)
	{
		try
                {
                        $sql="DELETE from jsadmin.CONNECT where ID=:ID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ID",$id,PDO::PARAM_INT);
                        $ret=$prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $ret;
	}

	//Function made by prinka
	public function findUser($userno)
        {
                $sql= "select * from jsadmin.CONNECT where ID=:USERNO";
                $res=$this->db->prepare($sql);
                $res->bindValue(":USERNO", $userno, PDO::PARAM_INT);
                $res->execute();
                $count=0;
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                        $count++;
                        $arr['TIME']=$row['TIME'];
                        $arr['USER']=$row['USER'];
                }
                if($count)
                        return $arr;
                else
                        return 0;
        }
	
	//Function made by prinka
	public function updateUserTime($userno)
        {
                $tm = time();
                $sql = "update jsadmin.CONNECT set TIME=$tm where ID=:USERNO";
                $res=$this->db->prepare($sql);
                $res->bindValue(":USERNO", $userno, PDO::PARAM_INT);
                $res->execute();
        }

        //delete expired login sessions
        public function deleteExpiredLoginSession($expiryOffset)
        {
                $diff = time()-$expiryOffset;
                $sql = "DELETE FROM jsadmin.CONNECT WHERE TIME < :TIME";
                $res=$this->db->prepare($sql);
                $res->bindValue(":TIME", $diff, PDO::PARAM_STR);
                $res->execute();
        }

        //updates login session details for agent
        public function updateLoginSessionForAgent($params)
        {
                $sql = "UPDATE jsadmin.CONNECT SET TIME=:TIME,IPADDR = :IPADDR WHERE ID=:ID";
                $res=$this->db->prepare($sql);
                $res->bindValue(":ID", $params["ID"], PDO::PARAM_INT);
                $res->bindValue(":IPADDR", $params["IPADDR"], PDO::PARAM_STR);
                $res->bindValue(":TIME", time(), PDO::PARAM_STR);
                $res->execute();
                return $this->db->lastInsertId();
        }

        //creates login session details for agent
        public function createLoginSessionForAgent($params)
        {
                
                $sql = "INSERT INTO jsadmin.CONNECT(`ID`, `USER`, `IPADDR`, `TIME`) VALUES(NULL,:USER,:IPADDR,:TIME)";
                $res=$this->db->prepare($sql);
                $res->bindValue(":USER", $params["USER"], PDO::PARAM_INT);
                $res->bindValue(":IPADDR", $params["IPADDR"], PDO::PARAM_STR);
                $res->bindValue(":TIME", time(), PDO::PARAM_STR);
                $res->execute();
                return $this->db->lastInsertId();
        }
}
?>
