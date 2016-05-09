<?php
/* 
* This class is used for mysql locking
* @author Lavesh Rawat
*/
class MYSQL_LOCK extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/**
	* This function will get the lock
        * @param lockName name of lock
	* @param timedout lock timedout
	**/
	public function get($lockName,$timedout)
	{
		try
		{
			$sql="SELECT GET_LOCK(:name,:time)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":name",$lockName,PDO::PARAM_STR);
			$res->bindValue(":time",$timedout,PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_BOTH);
			return $row[0];
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/**
	* This function will release the lock
        * @param lockName name of lock
	**/
	public function release($lockName)
	{
		try
		{
			$sql="SELECT RELEASE_LOCK(:name)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":name",$lockName,PDO::PARAM_STR);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_BOTH);
			return $row[0];
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
