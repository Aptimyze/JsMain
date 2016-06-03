<?php
class PHOTO_DELETE_TRACKING extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function trackPhotoDelete($pictureid,$profileid,$source,$picType)
	{
		$date = date("Y-m-d");
		$sql="INSERT INTO MIS.PHOTO_DELETE_TRACKING (PICTUREID,PROFILEID,DATE,SOURCE,PICTURE_TYPE) VALUES (:PICTUREID, :PROFILEID,:DATE, :SOURCE, :PICTYPE)";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":PICTUREID", $pictureid, PDO::PARAM_INT);
		$res->bindValue(":SOURCE", $source, PDO::PARAM_STR);
		$res->bindValue(":PICTYPE", $picType, PDO::PARAM_STR);
		$res->bindValue(":DATE", $date, PDO::PARAM_STR);

		$res->execute();
        }

	public function trackPhotoDeleteBulk($pictureid,$profileid,$source,$picType)
	{
		if(is_array($pictureid) && $profileid && $source && is_array($picType))
		{
			$date = date("Y-m-d");
			$sql="INSERT INTO MIS.PHOTO_DELETE_TRACKING (PICTUREID,PROFILEID,DATE,SOURCE,PICTURE_TYPE) VALUES ";

			for($i=0;$i<sizeof($pictureid) ;$i++)
			{
				$sql.=" ( :PICTUREID$i, :PROFILEID,:DATE, :SOURCE, :PICTYPE$i ) ";
				if($i != (sizeof($pictureid)-1))
					$sql.=',';
			}
                	$res=$this->db->prepare($sql);
	
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":SOURCE", $source, PDO::PARAM_STR);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
	
			for($i=0;$i<sizeof($pictureid) ;$i++)
			{
				$res->bindValue(":PICTUREID$i", $pictureid[$i], PDO::PARAM_INT);
				$res->bindValue(":PICTYPE$i", $picType[$i], PDO::PARAM_STR);
			}
	
			$res->execute();
		}
        }
}
?>
