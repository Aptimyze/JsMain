<?php
/**
* This class handles pictureid which are screened and photo-duplication need to be checked.
*/
class duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	* This function is used to record screened pictureid.
	*/
        public function ins($profileid,$picId)
        {
		try
		{
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN ins() of duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION.class.php");	
			if(!$picId)
				throw new jsException("","PICTUREID IS BLANK IN ins() of duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION.class.php");	
			$sql = "INSERT IGNORE INTO duplicates.SCREENED_PICTUREIDS_FOR_DUPLICATION(PROFILEID,PICTUREID) VALUES (:PROFILEID,:PICTUREID)";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PICTUREID", $picId, PDO::PARAM_INT);
			$res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
	* This function is used select paramters correspondin to a profileid;
	*/
        public function get($pid,$fields="*")
        {
                if(!$pid)
                        throw new jsException("","pid missing in get() of duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION.class.php");

                $sql = "SELECT $fields FROM duplicates.SCREENED_PICTUREIDS_FOR_DUPLICATION WHERE PROFILEID IN (:PROFILEID)";
                $res = $this->db->prepare($sql);

                if($pid)
                        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
		$res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                        $detailArr[] = $row;
                }
                return $detailArr;
	}

	/**
	* This function is used to delete 
	*/
        public function del($picStr)
        {
                if(!$picStr)
                        throw new jsException("","pic id(s) missing in del() of duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION.class.php");
                $sql = "DELETE FROM duplicates.SCREENED_PICTUREIDS_FOR_DUPLICATION WHERE PICTUREID IN (:PICTUREID)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PICTUREID", $picStr, PDO::PARAM_INT);
		$res->execute();
	}
}
?>
