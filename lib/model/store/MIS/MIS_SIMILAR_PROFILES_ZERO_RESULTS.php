<?php
/* This class provided functions for similar profile tracker functionalaity
 * @author : Akash Kumar
 * @created : July 14, 2014
*/
  
class MIS_SIMILAR_PROFILES_ZERO_RESULTS extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	public function trackZeroResultsForSimilar($viewer,$viewed)
	{ 	try 
		{
			$sql = "INSERT IGNORE INTO MIS.SIMILAR_PROFILES_ZERO_RESULTS VALUES('LoggedIn',:VIEWER,:VIEWED,CURDATE())";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":VIEWER",$viewed,PDO::PARAM_INT);
			$prep->bindValue(":VIEWED",$viewer,PDO::PARAM_INT);
			$prep->execute();			
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

}

?>
