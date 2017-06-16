<?php
class PICTURE_PIC_URL_RESPONSE_CHECK extends TABLE{
	

	public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

    public function insertData($pictureId,$urlType,$url,$httpStatusCode){
    	try 
		{	 
			$sql="INSERT INTO PICTURE.PIC_URL_RESPONSE_CHECK(PICTUREID,URLTYPE,COMPLETEURL,HTTPCODE) VALUES(:PICTUREID,:URLTYPE,:COMPLETEURL,:HTTPCODE)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PICTUREID", $pictureId, PDO::PARAM_INT);
			$prep->bindValue(":URLTYPE", $urlType, PDO::PARAM_STR);
			$prep->bindValue(":COMPLETEURL", $url, PDO::PARAM_STR);
			$prep->bindValue(":HTTPCODE", $httpStatusCode, PDO::PARAM_INT);
            $prep->execute();           			
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
    }
}


