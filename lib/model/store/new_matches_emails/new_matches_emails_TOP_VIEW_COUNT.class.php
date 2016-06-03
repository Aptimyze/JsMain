<?php
/* This class provided functions for new_matches_emails.TOP_VIEW_COUNT table
 * @author : Reshu Rajput
 * @created : Jun 19, 2014
*/

class new_matches_emails_TOP_VIEW_COUNT  extends TABLE
{
        public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
        }

	/*This function is used to insert entries in the table
	*@param openEmail : array of values , csa,date,logic and sent date
	*/
        public function update($openEmail)
        {
		if(!$openEmail)
			throw new jsException("","No email detail data is given in update_open_email() in new_matches_emails_TOP_VIEW_COUNT");
				
		try 
		{
			$sql="INSERT INTO new_matches_emails.TOP_VIEW_COUNT (PROFILEID,DATE,LOGIC,SENT_DATE) VALUES(:PROFILEID,:DATE,:LOGIC,:SENT_DATE)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$openEmail['csa'],PDO::PARAM_INT);
			$prep->bindValue(":DATE",$openEmail['date'],PDO::PARAM_INT);
			$prep->bindValue(":LOGIC",$openEmail['logic'],PDO::PARAM_STR);
			$prep->bindValue(":SENT_DATE",$openEmail['sent'],PDO::PARAM_INT);
			$prep->execute();
			
		}
		catch(PDOException $e)
		{
				throw new jsException($e);
		}
			
	}
	
}	
?>
