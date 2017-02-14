<?php
/**
* For every mailer, we create a new table.
* This store will handle queries for the above table.
*/
class mmmjs_INDIVIDUAL_MAILERS extends TABLE
{
	public function __construct($dbname="matchalerts_slave_localhost")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
	}

	/**
	* creates a mailer table of the format [mailerId]mailer
	* @param $tableName - name of the table
	* @throws PDO Exception 
	*/
	public function createTable($tableName)
	{
		try
		{
			$sql = "CREATE TABLE IF NOT EXISTS $tableName (`PROFILEID` bigint unsigned DEFAULT NULL, `EMAIL` varchar(100) DEFAULT NULL, `NAME` varchar(255) DEFAULT NULL, `PHONE` varchar(20) DEFAULT NULL, `SENT` tinyint(4) NOT NULL DEFAULT '0', UNIQUE KEY `PROFILEID` (`PROFILEID`), KEY `EMAIL` (`EMAIL`) ) ENGINE=MyISAM";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}
	}


	/**
	* insert entries in the table based on the query  
	* @param $arr - associative array tableName & query (dump to tableName and fetch from JProfile)
	* @throws PDO Exception 
	*/
	public function populateTableBasedOnSearchQuery($arr)
	{
		try
		{
			$tableName = $arr["tableName"];
			$query = $arr["query"];
			$sql = "INSERT IGNORE INTO $tableName (PROFILEID, EMAIL, NAME, PHONE) $query";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}		
	}


	/**
	* insert entries in the table based on the query  
	* @param $arr - associative array tableName & query (dump to tableName)
	* @throws PDO Exception 
	*/
	public function populateTableBasedOnArray($arr)
	{
		try
		{
			foreach($arr["dumpDataArr"] as $k=>$v)
				$tempArr[] = "('".implode("','",$v)."')";
			$values = implode(",",$tempArr);
			$tableName = $arr["tableName"];
			$keys = $arr["keys"];
			$sql = "INSERT IGNORE INTO $tableName ($keys) VALUES $values";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}		
	}

	/**
        * truncate table before populating data.  
        * @param $tableName
        * @throws PDO Exception 
        */

	public function truncateTable($tableName){
		try
		{
			$sql = "TRUNCATE TABLE $tableName";
                        $res = $this->db->prepare($sql);
                        $res->execute();
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}
	}

	/**
	* update the SENT colum of the mailer table  
	* @param $profileId 
	* @param $tableName
	* @throws PDO Exception 
	*/
	public function updateStatus($profileId, $tableName,$sent=1)
	{
		if($profileId && $tableName)
		{
			try
			{
				$sql = "Update $tableName set SENT=:sent where PROFILEID IN (:profileId)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":profileId", $profileId, PDO::PARAM_STR);
				$res->bindValue(":sent", $sent, PDO::PARAM_INT);
				$res->execute(); 
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
	}


	public function updateUserNames($tableName)
	{
		if(!$tableName)
			throw new jsException("", "No Table exists with this name");

		try
		{
			$sql = "UPDATE $tableName M ,incentive.NAME_OF_USER I set M.NAME = I.NAME WHERE M.PROFILEID = I.PROFILEID AND I.NAME<>''";
			$res = $this->db->prepare($sql);
			$res->execute();			
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}


	/**
	* get entries from the mailer table 
	* @param $tableName - name of the table
	* @param $limit - no of records to be retrieved
	* @param $totalStaggerTime - staggering period
	* @param $day - staggering day
	* @throws - PDO Exception 
	*/
	public function retrieve($tableName, $limit, $totalStaggerTime, $day)
	{
		if(!$tableName)
		{	
			throw new jsException("", "No Table exists with this name");
		}

		try
		{
			$sql = "SELECT PROFILEID, EMAIL, NAME, PHONE from $tableName WHERE SENT = '0' ";
			if($totalStaggerTime && isset($day) && $totalStaggerTime > 1)
			{	
				$sql.="AND PROFILEID % :totalStaggerTime <= :day ";
			}

			if($limit)
				$sql.="LIMIT $limit";

			$res = $this->db->prepare($sql);

			if($totalStaggerTime && isset($day) && $totalStaggerTime > 1)
			{
				$res->bindValue(":totalStaggerTime", $totalStaggerTime, PDO::PARAM_INT);
				$res->bindValue(":day", $day, PDO::PARAM_INT);
			}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$pid                 = $row["PROFILEID"];
				$arr[$pid]['EMAIL']  = $row['EMAIL'];
				$arr[$pid]['NAME']   = $row['NAME'];
				$arr[$pid]['PHONE']  = $row['PHONE'];
			}
			return $arr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

        public function getCountOfMails($tableName,$sent)
        {
                if(!$tableName)
                        throw new jsException("", "No Table exists with this name");
                try
                {
			if($sent=='')
                        	$sql = "SELECT COUNT(*) AS CNT from $tableName";
			else
	                        $sql = "SELECT COUNT(*) AS CNT from $tableName WHERE SENT=:SENT";	
                        $res = $this->db->prepare($sql);
			if($sent || $sent=='0')
	                        $res->bindValue(":SENT", $sent, PDO::PARAM_INT);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row["CNT"];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	public function getTotalCountTillYesterday($mailerId){
		try
                {
			$date = date('Y-m-d');
			$sql = "SELECT SUM(SENT) AS CNT from mmmjs.MAIL_SENT_NEW WHERE MAILER_ID=:MAILER_ID AND DATE<:DATE";
			$res = $this->db->prepare($sql);
			$res->bindValue(":MAILER_ID", $mailerId, PDO::PARAM_INT);
			$res->bindValue(":DATE", $date, PDO::PARAM_INT);
			$res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row["CNT"];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}


}
?>
