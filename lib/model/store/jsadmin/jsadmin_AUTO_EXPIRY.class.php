<?php
class jsadmin_AUTO_EXPIRY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function replace($pid,$type,$date)
	{
		try{

		$sql = "replace into jsadmin.AUTO_EXPIRY set PROFILEID=:PID,TYPE=:TYPE,DATE=:expireDt";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PID", $pid, PDO::PARAM_INT);
		$res->bindValue(":TYPE", $type, PDO::PARAM_STR);
		$res->bindValue(":expireDt", $date, PDO::PARAM_STR);
		$res->execute();
        JsCommon::logFunctionCalling(__CLASS__,__FUNCTION__);
        return true;
		}
		catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	public function isAlive($pid,$time)
	{
		$pid=intval($pid);
		if($pid)
		{
			try{

				$sql = "select PROFILEID from jsadmin.AUTO_EXPIRY WHERE PROFILEID =:PID AND DATE_SUB(DATE,interval 2 second) > :TIME";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PID",$pid,PDO::PARAM_INT);
				$prep->bindValue(":TIME",$time,PDO::PARAM_STR);
				$prep->execute();
                JsCommon::logFunctionCalling(__CLASS__,__FUNCTION__);
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
					return false;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		return true;
	}
        
	public function getDate($pid)
	{
		$pid=intval($pid);
		if($pid)
		{
			try{

				$sql = "select DATE from jsadmin.AUTO_EXPIRY WHERE PROFILEID =:PID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PID",$pid,PDO::PARAM_INT);
				$prep->execute();
                JsCommon::logFunctionCalling(__CLASS__,__FUNCTION__);
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
					return $result['DATE'];
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		return 0;
	}

        
        
                        }
?>
