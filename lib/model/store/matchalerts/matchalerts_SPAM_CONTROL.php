<?php
/**************************************************************************
 * matchalerts_TOP_VIEW_COUNT CLASS.
 *
 * @package    Jeevansathi
 * @subpackage matchalerts
 * @author     akash kumar
 **************************************************************************/
class matchalerts_SPAM_CONTROL extends TABLE
{
   	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
	}

    	/**
	* Function to update data in SPAM_CONTROL table for open email tracking
	*  @param Array of Checksumarray[0], logic used,sent date,frequency
	*/
	public function updateSpamControl($openEmail,$dated)
	{
		if(!$openEmail)
			throw new jsException("","No email detail data is given in matchalerts_SPAM_CONTROL function updateSpamControl");
		/**
		* QUERY to update data in SPAM_CONTROL table on every email opened based on Email Domain(gmail,yahoo,hotmail etc)
		*/
		$email=$openEmail['email'];
		
		try 
		{
			$sql="UPDATE matchalerts.SPAM_CONTROL SET ".$email."_OPEN=".$email."_OPEN+1 WHERE DATE=:DAY";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":DAY",$dated,PDO::PARAM_STR);
			$prep->execute();
			$row_affected=$prep->rowCount();
			
			if($row_affected==0)
			{
				return 0;
			}
				return 1;    // return 1 for success
		 	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
		
	}
		
	/**
	* Function to insert data in SPAM_CONTROL table for open email tracking
	*  @param Array of Checksumarray[0], logic used,sent date,frequency
	*/
	public function insertSpamControl($openEmail,$dated)  //emaildate is No. of days from 01 jan 2006
	{
		if(!$openEmail)
			throw new jsException("","No email detail data is given in matchalerts_SPAM_CONTROL function insertSpamControl");
		
		try 
		{
			$email=$openEmail['email'];
			$sql="INSERT IGNORE INTO matchalerts.SPAM_CONTROL (".$email."_OPEN,DATE)  VALUES (:FIRST,:DAY)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":FIRST","1",PDO::PARAM_INT);
			$prep->bindValue(":DAY",$dated,PDO::PARAM_STR);
			$prep->execute();
			return 1; 	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
			
	}
	/* This function is used to INSERT the SPAM_CONTROL table entry for a specific date
	* @return 1
	*/
	public function insertSentEmail($subMem)
	{
		try 
		{
		/**
		* QUERY to INSERT data in SPAM_CONTROL table
		*/
				
				$sql="INSERT INTO `matchalerts`.`SPAM_CONTROL` (GMAIL,YAHOO,HOTMAIL,REDIFF,OTHERS,DATE)  VALUES (:GMAIL,:YAHOO,:HOTMAIL,:REDIFF,:OTHERS,:DAY)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":DAY",$subMem["day"],PDO::PARAM_STR);
				$prep->bindValue(":GMAIL",$subMem["g"],PDO::PARAM_INT);
				$prep->bindValue(":YAHOO",$subMem["y"],PDO::PARAM_INT);
				$prep->bindValue(":HOTMAIL",$subMem["h"],PDO::PARAM_INT);
				$prep->bindValue(":REDIFF",$subMem["r"],PDO::PARAM_INT);
				$prep->bindValue(":OTHERS",$subMem["other"],PDO::PARAM_INT);
				$prep->execute();
				return 1;
					 
			 	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
	/* This function is used to update the SPAM_CONTROL table entry for a specific date
	* @return 1 for success otherwise 0
	*/
	public function updateSentEmail($subMem)
        {
		try 
		{
	/**
	 * QUERY to update data in SPAM_CONTROL table
	 */
				
				$sql="UPDATE `matchalerts`.`SPAM_CONTROL` SET `GMAIL`=:GMAIL,`YAHOO`=:YAHOO,`HOTMAIL`=:HOTMAIL,`REDIFF`=:REDIFF,`OTHERS`=:OTHERS,`COUNTER`=COUNTER+1 WHERE `DATE`=:DAY";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":DAY",$subMem["day"],PDO::PARAM_STR);
				$prep->bindValue(":GMAIL",$subMem["g"],PDO::PARAM_INT);
				$prep->bindValue(":YAHOO",$subMem["y"],PDO::PARAM_INT);
				$prep->bindValue(":HOTMAIL",$subMem["h"],PDO::PARAM_INT);
				$prep->bindValue(":REDIFF",$subMem["r"],PDO::PARAM_INT);
				$prep->bindValue(":OTHERS",$subMem["other"],PDO::PARAM_INT);
				$prep->execute();
				$row_affected=$prep->rowCount();
				
				if($row_affected==0){
				return 0;
			   }
			   return 1;
				
					 
			 	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	
		
			
	}
		
	/* This function is used to get various email domains detail of sent emails
	* @return array of Entries of SPAM_CONTROL table
	*/
	public function analyseEmailOpenrate()
	{
		try 
		{
		/**
		* QUERY to get sent email domain count
		*/
		$sql="SELECT * FROM `matchalerts`.`SPAM_CONTROL` WHERE DATE=:DAY";
		$prepr=$this->db->prepare($sql);
		$prepr->bindValue(":DAY",date('Y-m-d',strtotime("-1 days")),PDO::PARAM_STR);
	        $prepr->execute();
		$result = $prepr->fetch(PDO::FETCH_ASSOC);				
		return $result;			 
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

}	
?>
