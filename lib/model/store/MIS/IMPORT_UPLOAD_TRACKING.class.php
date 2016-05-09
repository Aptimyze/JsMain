<?php

class IMPORT_UPLOAD_TRACKING extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

        /**
        * This function updates the table MIS.IMPORT_UPLOAD_TRACKING 
        * whenever a user imports/uploads a photo.
        **/
	public function photoSaveEntry($profileid,$photoSource,$noOfPhotos) 
	{
		$date=date("Y-m-d");

		$sql="UPDATE MIS.IMPORT_UPLOAD_TRACKING SET NO_OF_PHOTOS=NO_OF_PHOTOS+:NO_PHOTOS WHERE PROFILEID=:PROFILEID AND PHOTO_SOURCE = :PHOTOSOURCE AND DATE =:DATE";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":PHOTOSOURCE", $photoSource, PDO::PARAM_STR);
		$res->bindValue(":DATE", $date, PDO::PARAM_STR);
		 $res->bindValue(":NO_PHOTOS", $noOfPhotos, PDO::PARAM_INT);
		$res->execute();

		if($res->rowCount()==0)
		{
			$sql1="INSERT INTO MIS.IMPORT_UPLOAD_TRACKING(PROFILEID,PHOTO_SOURCE,NO_OF_PHOTOS,DATE) VALUES (:PROFILEID,:SOURCE,:NO_PHOTOS,:DATE)";
			$res1=$this->db->prepare($sql1);
			$res1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res1->bindValue(":SOURCE", $photoSource, PDO::PARAM_STR);
			$res1->bindValue(":NO_PHOTOS", $noOfPhotos, PDO::PARAM_INT);
			$res1->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res1->execute();
		}
	}
}
 
?>
