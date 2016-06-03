<?php
/* This class provided functions for similar profile tracker functionalaity
 * @author : Akash Kumar
 * @created : July 14, 2014
*/
  
class MIS_CONTACTS_ALGO_ZERO_RESULTS extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	public function trackContactsAlgoZeroResultsInsert($viewer,$viewed)
	{ 	try 
		{
			$sql = "INSERT INTO MIS.CONTACTS_ALGO_ZERO_RESULTS VALUES(:VIEWER,:VIEWED,CURDATE())";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
			$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
			$prep->execute();
			
			return 1;    // return 1 for success
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
}

?>
