<?php
/**
* This class will handle function related to test email-ids
*/
class mmmjs_TEST_MAILERS extends TABLE
{
        public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* insert permanent test mailers info in the table .....
	* @param $testMailer - associative array with key (column name) & value
	* @throws - PDO Exception 
	*/
	public function insert($testMailer)
	{
		try
           	{	
			$sql = "INSERT IGNORE INTO mmmjs.TEST_MAILERS (EMAIL, SITE) VALUES ";
			$COUNT = count($testMailer['emailIds']); 
			for($count = 1; $count <= $COUNT; $count++)
			{
				$sql.=" (:email".$count.", ".":site".$count."),";
			} 	
			$sql = substr($sql, 0, -1);
			$res=$this->db->prepare($sql);
			for($count = 1; $count <= $COUNT; $count++)
			{
		        	$res->bindValue(":email".$count, $testMailer['emailIds'][$count-1], PDO::PARAM_STR);			
		        	$res->bindValue(":site".$count, $testMailer['site'], PDO::PARAM_STR);
			}
		        $res->execute();
			return True;
	  	 }
  	         catch(PDOException $e)
  	         {
			throw new jsException($e);
 	         }
	}

	/**
	* This function will retreive test mailer based on website .....
	* @param site 
	* @return $arr list of test email
	*/
        public function retrieveBySite($site)
        {  
        	try
           	{	
			$sql = "select ID, EMAIL from mmmjs.TEST_MAILERS where SITE = :site";
		        $res=$this->db->prepare($sql);
	        	$res->bindValue(":site", $site, PDO::PARAM_STR);
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
		if(!is_array($mail_ids))
			throw new jsException("","mail_ids array empty in delete() of mmmjs_TEST_MAILERS.class.php");
        	try
           	{
			$sql = "DELETE from mmmjs.TEST_MAILERS where ";
			$COUNT = count($mail_ids);

			for($count = 1; $count <= $COUNT; $count++)
				$wherArr[] = ":mail_id".$count;
			$sql.=" ID IN (".implode(",",$wherArr).")";

		        $res=$this->db->prepare($sql);
			for($count = 1; $count <= $COUNT; $count++)
		        	$res->bindValue(":mail_id".$count, $mail_ids[$count-1], PDO::PARAM_INT);
		        $res->execute();
	  	 }
  	         catch(PDOException $e)
  	         {
			throw new jsException($e);
 	         }
        }
}
?>
