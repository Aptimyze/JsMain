<?php
class FACEBOOK_ALBUM_DETAILS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	/**
	* This function inserts no of photos and album ids of all the albums of a logged in user who is importing photos fom facebook
	* These values are inserted in the table newjs.FACEBOOK_ALBUM_DETAILS.
	**/
	public function insertAlbumData($insertStr,$profileid)
	{
		$count=0;
		foreach($insertStr as $key=>$value)
                {
                        $sqlArr[]="(:PROFILEID,:id$count,:c$count)";
			$count++;
                }
                $sqlStr = implode(",",$sqlArr);
                $sql="REPLACE INTO newjs.FACEBOOK_ALBUM_DETAILS(PROFILEID,ALBUM_ID,NO_OF_PHOTOS) VALUES $sqlStr";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$count = 0;
                foreach($insertStr as $key=>$value)
                {
			$res->bindValue(":id$count", $key, PDO::PARAM_STR);
			$res->bindValue(":c$count", $value, PDO::PARAM_INT);
			$count++;
		}
		$res->execute();
        }

	/**
	  * This function returns the no of photos present in a facebook album.
	**/
	public function getAlbumData($profileid,$aid)
	{
		$sql="SELECT NO_OF_PHOTOS FROM newjs.FACEBOOK_ALBUM_DETAILS WHERE PROFILEID = :PROFILEID AND ALBUM_ID=:ALBUM_ID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":ALBUM_ID", $aid, PDO::PARAM_STR);
		$res->execute();
	
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['NO_OF_PHOTOS'];

		return NULL;
	}

	/**
	* This function empties all entries of a profileid from the table newjs.FACEBOOK_ALBUM_DETAILS.
	**/
	public function deleteProfilesEntries($profileid)
	{
		$sql="DELETE FROM newjs.FACEBOOK_ALBUM_DETAILS WHERE PROFILEID = :PROFILEID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
	}
}
?>
