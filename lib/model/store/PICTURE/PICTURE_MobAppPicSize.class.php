<?php
class PICTURE_MobAppPicSize extends TABLE 
{
	
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    public function updateSize($size)
    {
		if(!$size)
		{
			throw new jsException('','Size array not defined in PICTURE_MobAppPicSize');
		}
		
		try{
			foreach($size AS $pictureId=>$currentSize){
				$valuesToInsertArr[] = "('".$pictureId."','".$currentSize[0]."','".$currentSize[1]."')";
			}
			$valuesToInsert = implode(",",$valuesToInsertArr);
			$sql = "REPLACE INTO PICTURE.MobAppPicSize (PICTUREID,WIDTH,HEIGHT) VALUES ".$valuesToInsert;
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->execute();
		}catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in updateSize method of PICTURE_MobAppPicSize");
		}
	    
    }
    public function getPictureSize($pictureId)
    {
		if($pictureId)
		{
			try{    
				$loop=0;
				foreach($pictureId AS $profileId => $picId){
					if($picId){
						$pictureIdStr[":PICTUREID".$loop]=$picId;
						$loop++;
					}
						
				}
                                if($loop==0)
                                        return;
				$pictureIdBind = implode(",",array_flip($pictureIdStr));
	                        $profileArr = array_flip($pictureId);
			
				$sql = "SELECT * FROM PICTURE.MobAppPicSize WHERE PICTUREID IN (".$pictureIdBind.")";
				$res = $this->db->prepare($sql);
				foreach($pictureIdStr AS $key => $val){
					$res->bindValue($key, $val,PDO::PARAM_INT);
				}
				$res->execute();
	                        while($result=$res->fetch(PDO::FETCH_ASSOC))
                	        {
                        	        $picture[$profileArr[$result["PICTUREID"]]]["WIDTH"]=$result["WIDTH"];
        	                        $picture[$profileArr[$result["PICTUREID"]]]["HEIGHT"]=$result["HEIGHT"];
	                        }
                        	return $picture;
			}catch(Exception $e)
			{
				throw new jsException($e,"Something went wrong in updateSize method of PICTURE_MobAppPicSize");
			}
		}
	    
    }
      public function updateImageSize($pictureId,$size)
    {
		if(!$size)
		{
			throw new jsException('','Size array not defined in PICTURE_MobAppPicSize');
		}
		
		try{ 
			$sql = "REPLACE INTO PICTURE.MobAppPicSize (PICTUREID,WIDTH,HEIGHT) VALUES ('".$pictureId."','".$size[0]."','".$size[1]."')";
                        $pdoStatement = $this->db->prepare($sql);
			$pdoStatement->execute();
		}catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in updateImageSize method of PICTURE_MobAppPicSize");
		}
	    
    }
}
