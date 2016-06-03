<?php
class PHOTO_FACEDETECTION_STATS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function trackPhotoFaceDetection($increaseProcessed,$increaseFaceDetected)
	{
		try
                {
			$date = date("Y-m-d");
			$sql="UPDATE MIS.PHOTO_FACEDETECTION_STATS SET ";
                	if($increaseProcessed)
                		$sql.="PROCESSED_IMAGE_COUNT =PROCESSED_IMAGE_COUNT +1";
			if($increaseFaceDetected)
                        	$sql.=",FACEDETECTED_IMAGE_COUNT =FACEDETECTED_IMAGE_COUNT +1 ";
			$sql.=" WHERE DATE = CURDATE()";
			$res=$this->db->prepare($sql);
			$res->execute();
			if($res->rowCount()==0)
                	{
				$sql2 = "INSERT INTO MIS.PHOTO_FACEDETECTION_STATS(DATE,PROCESSED_IMAGE_COUNT,FACEDETECTED_IMAGE_COUNT) VALUES(CURDATE(),:PINCREASE,:DINCREASE)";
                        	$res2=$this->db->prepare($sql2);
				$pIncrease = $increaseProcessed?1:0;
                        	$dIncrease = $increaseFaceDetected?1:0;
				$res2->bindParam(":PINCREASE", $pIncrease, PDO::PARAM_INT);
				$res2->bindParam(":DINCREASE", $dIncrease, PDO::PARAM_INT);
				$res2->execute();
			}
		}
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }


        }
	
	public function getPhotoFaceDetectionStat($date)
	{
		try
                {
			$sql="SELECT PROCESSED_IMAGE_COUNT,FACEDETECTED_IMAGE_COUNT FROM MIS.PHOTO_FACEDETECTION_STATS WHERE DATE = :DATE";
                	$res=$this->db->prepare($sql);
			$res->bindParam(":DATE", $date, PDO::PARAM_STR);
                	$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result["PROCESSED_IMAGE_COUNT"]= $row["PROCESSED_IMAGE_COUNT"];
				$result["FACEDETECTED_IMAGE_COUNT"] = $row["FACEDETECTED_IMAGE_COUNT"];
			}
			return $result;
		}
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}

}
?>
