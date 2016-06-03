<?php
/* This class provided functions for similar profile tracker functionalaity
 * @author : Akash Kumar
 * @created : July 14, 2014
*/
  
class MIS_TRACK_SIMILAR_PROFILES_ALGO extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	public function trackSimilarProfilesAlgoUpdate($algo)
	{ 

//JSM-881---temp stop
return 1;
	
		try
		{ 		
			$sql = "UPDATE MIS.TRACK_SIMILAR_PROFILES_ALGO SET COUNT=COUNT+1 WHERE SIMILAR_PROFILE_ALGO=:ALGO AND DATE=CURDATE()";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ALGO",$algo,PDO::PARAM_STR);
			$prep->execute();
			$row_affected=$prep->rowCount();
			if($row_affected==0)
				return 0;
			return 1;    // return 1 for success
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/* 
	*This function is used update the sent flag(Y for sent and F for fail and I for invalid users for receiver) for each mail receiver
        *@param sno : serial number of mail
        *@param flag : sent status of the mail
        */
	public function trackSimilarProfilesAlgoInsert($algo)
	{ 	
//JSM-881---temp stop
return 1;
		try 
		{
			$sql = "INSERT IGNORE INTO MIS.TRACK_SIMILAR_PROFILES_ALGO VALUES(:ALGO,1,CURDATE())";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ALGO",$algo,PDO::PARAM_STR);
			$prep->execute();
			
			return 1;			
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
}

?>
