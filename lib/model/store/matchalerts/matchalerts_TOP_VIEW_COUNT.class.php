<?php
/**************************************************************************
 * matchalerts_TOP_VIEW_COUNT CLASS.
 *
 * @purpose    Updates the TOP_VIEW_COUNT table entry for a specific date 
 * @package    Jeevansathi
 * @subpackage matchalerts
 * @author     Akash kumar
 **************************************************************************/
class matchalerts_TOP_VIEW_COUNT extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
        }
        /**
	* Function to update data in TOP_VIEW_COUNT table for open email tracking
	* 
	*  @param Array of Checksumarray[0], logic used,sent date,frequency
	*/
	public function updateOpenEmail($openEmail)
        {
			if(!$openEmail)
				throw new jsException("","No email detail data is given in matchalerts_TOP_VIEW_COUNT function updateOpenEmail");
				
			try 
			{
				/**
				* QUERY to update data in TOP_VIEW_COUNT table for open email track
				*/
				$sql="INSERT INTO matchalerts.TOP_VIEW_COUNT (PROFILEID,DATE,LOGIC,SENT_DATE,FREQUENCY) VALUES(:PROFILEID,:DATE,:LOGIC,:SENT_DATE,:FREQUENCY)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$openEmail['csa'],PDO::PARAM_INT);
				$prep->bindValue(":DATE",$openEmail['date'],PDO::PARAM_INT);
				$prep->bindValue(":LOGIC",$openEmail['logic'],PDO::PARAM_STR);
				$prep->bindValue(":SENT_DATE",$openEmail['sent'],PDO::PARAM_INT);
				$prep->bindValue(":FREQUENCY",$openEmail['freq'],PDO::PARAM_INT);
				$prep->execute();
				return 1;    // return 1 for success
			 	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
			
		}
	
}	
?>
