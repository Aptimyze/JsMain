<?php
class NEWJS_VIDEO_LINK extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function inserts the entry for the passed profileid that have been shown the help screens.
        * @return int id
        **/
	public function insertOneTimeEntry($profileId)
	{
		if(!$profileId) return;
		try
		{
			$sql = "INSERT IGNORE INTO newjs.VIDEO_LINK(PROFILEID) VALUES(:PROFILEID)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_VIDEO_LINK.class.php(3)-->.$sql".$e);
                        return '';
			//throw new jsException($e);
		}
	}

	/**
        This function tells us whether the user with the profileid passed has been shown the help screen on new jspc myjs  
        * @return 1 for Yes ... 0 for No
        **/
	public function doesExist($profileId)
	{
		if(!$profileId) return;
		try
			{ 
					$sql="SELECT PROFILEID FROM newjs.VIDEO_LINK WHERE PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return 1;
					}
					return 0;
			}	
		catch(PDOException $e)
			{
				jsException::nonCriticalError("lib/model/store/newjs/NEWJS_VIDEO_LINK.class.php(3)-->.$sql".$e);
                        return '';
			//throw new jsException($e);
			}
				
}


}
?>
