<?php
/**
* This class will handle things related to temporary email id for a mailer.
*/
class mmmjs_TEST_MAILERS_TEMP extends TABLE
{
	public function __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}

	/**
	* convert string to quote separated string
	* @param $x - string
	* @return quote separated string	
	* @throws - PDO Exception 
	*/
	private function convertValueToQuotseparated($x)
	{	
		if($x && !strstr($x,"'"))
		{
			$y = explode(",",$x);
			$z = implode("','",$y);
			return $z;
		}
		return $x;
	}

	/**
	* insert temporary test mailers based on the specific mailer id .....
	* @param $testMailersArr - associative array with key (column name) & value
	* @return unique id of inserted mailer.
	*/
	public function insert($testMailersArr)
	{
		try
		{	
			$sql = "INSERT IGNORE INTO mmmjs.TEST_MAILERS_TEMP (EMAIL, MAILER_ID) VALUES";
			$COUNT = count($testMailersArr['emailIds']); 
			for($count = 1; $count <= $COUNT; $count++)
			{
				$sql.=" (:email".$count.", ".":mailer_id".$count."),";
			}

			$sql = substr($sql, 0, -1);
			$res=$this->db->prepare($sql);
			for($count = 1; $count <= $COUNT; $count++)
			{
				$res->bindValue(":email".$count, $testMailersArr['emailIds'][$count-1], PDO::PARAM_STR);			
				$res->bindValue(":mailer_id".$count, $testMailersArr['mailer_id'], PDO::PARAM_STR);
			}
			$res->execute();
			return $this->db->lastInsertId();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
	* This function will retreive test mailer based on mailer id
	* @param $mailer_id
	* @return $arr list of test email
	*/
	public function retrieveByMailer_id($mailer_id)
	{  
		try
		{	
			$sql = "select ID, EMAIL from mmmjs.TEST_MAILERS_TEMP where MAILER_ID = :mailer_id";
			$res=$this->db->prepare($sql);
			$res->bindValue(":mailer_id", $mailer_id, PDO::PARAM_STR);
			$res->execute();
			$arr = array();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$row['ID']]=$row['EMAIL'];
			}
			return $arr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
	* Delete test mailer email ids .....
	* @param mail_ids array containing list of email ids
	*/
	public function delete($mail_ids)
	{  
		try
		{	
			$sql = "DELETE from mmmjs.TEST_MAILERS_TEMP where ";
			$COUNT = count($mail_ids);

			for($count = 1; $count <= $COUNT; $count++)
			{	
				if($count == 1)
				{
					$sql.=" ID = :mail_id".$count." ";
				}
				else
				{
					$sql.=" OR ID = :mail_id".$count." ";
				}
			}

			$res=$this->db->prepare($sql);
			for($count = 1; $count <= $COUNT; $count++)
			{
				$res->bindValue(":mail_id".$count, $mail_ids[$count-1], PDO::PARAM_INT);
			}
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}	
}
?>
