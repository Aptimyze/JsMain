<?php
/*
This function is used to process queries on MIS.FACEDETECTION_SIZE table
*/
class MIS_FACEDETECTION_SIZE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/*
	This function inserts given pictureid and its size in the MIS.FACEDETECTION_SIZE table
	@param pid - pictureid
	@param face_w- width
	@height face_h- height
	*/
	public function saveImageSize($pid,$face_w,$face_h)
	{
		if(!$pid)
			throw new jsException("","PICTUREID IS BLANK IN insert() of test_FACEDETECTION_SIZE.class.php");

		try
		{
			$sql = "REPLACE INTO MIS.FACEDETECTION_SIZE (PICTUREID,WIDTH,HEIGHT) VALUES (:PICTUREID,:WIDTH,:HEIGHT)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PICTUREID",$pid, PDO::PARAM_INT);
			$res->bindValue(":WIDTH",$face_w, PDO::PARAM_INT);
			$res->bindValue(":HEIGHT",$face_h, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
