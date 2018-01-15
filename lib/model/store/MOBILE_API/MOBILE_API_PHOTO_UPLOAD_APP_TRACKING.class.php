<?php


class MOBILE_API_PHOTO_UPLOAD_APP_TRACKING extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
       	} 
     
    	public function insert($profileId)
	{
		try 
		{
      			$sql = "INSERT INTO MOBILE_API.PHOTO_UPLOAD_APP_TRACKING (PROFILEID,DATE) VALUES(:PROFILEID,NOW())";
      			$prep = $this->db->prepare($sql);
      			$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			return $prep->execute();				
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

}
?>
