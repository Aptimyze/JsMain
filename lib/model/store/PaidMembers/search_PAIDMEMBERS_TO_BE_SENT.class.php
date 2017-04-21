<?php
/**
* This class with populate the ids for which we need to send the mailers.
*/
class search_PAIDMEMBERS_TO_BE_SENT extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"newjs_slave";
		parent::__construct($dbname);
	}

	/**
	* Empty The table
	*/
        public function truncateTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE search.PAIDMEMBERS_TO_BE_SENT";
			$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
			//add mail/sms
                        throw new jsException($e);
                }
        }

	/**
	* Populate the table as per conditiob "conditionNew"
	* @param conditionNew
	*/
        public function populateTables($profilesIds)
        {
                try
                {
			$sql="INSERT IGNORE INTO search.PAIDMEMBERS_TO_BE_SENT(PROFILEID) VALUES ";
                        $sqlArr = array();
                        foreach($profilesIds as $profileId){
                                $sqlArr[]= "($profileId)";
                                if(count($sqlArr) == 1000){
                                        $sqlExe = $sql.implode(",",$sqlArr);
                                        $res = $this->db->prepare($sqlExe);
                                        $res->execute();
                                        unset($res);
                                        unset($sqlArr);
                                }
                        }
                        if(count($sqlArr) >0){
                                $sqlExe = $sql.implode(",",$sqlArr);
                                $res = $this->db->prepare($sqlExe);
                                $res->execute();
                                unset($sqlArr);
                        }
                }
                catch (PDOException $e)
                {
			//add mail/sms
                        throw new jsException($e);
                }
        }


	/**
	* Fetch 
	* @param 
	*/
	public function fetch($totalScript="1",$currentScript="0",$limit="")
	{
		try
		{
			$result = NULL;
			$sql = "SELECT PROFILEID FROM search.PAIDMEMBERS_TO_BE_SENT WHERE PROFILEID%:TOTAL_SCRIPT=:SCRIPT AND IS_CALCULATED=:STATUS";
			if($limit)
                                $sql.= " limit 0,:LIMIT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                        $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                        $prep->bindValue(":STATUS",'N',PDO::PARAM_STR);
                        if($limit)
                                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                        $prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[]["PROFILEID"]= $row["PROFILEID"];
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
	* update
	* @param pid
	*/
	public function update($pid)
	{
		try
		{
			$result = NULL;
			$sql = "UPDATE search.PAIDMEMBERS_TO_BE_SENT SET IS_CALCULATED='Y' WHERE PROFILEID=:PID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PID",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
        }
        public function countMails()
        {
                try{
                        $sql = "SELECT count(*) as CNT FROM search.PAIDMEMBERS_TO_BE_SENT";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row['CNT'];
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;
        }
}
