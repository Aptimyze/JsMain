<?php
/**
* This class is used to access/updated picture related details like image size , height ,width .....
*/
class NEWJS_PICTURE_DETAILS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /**
	* This function will capture the picture details.
	* imageDetails array image details in array format
	* profileid int unique id
        **/
        public function ins($picId,$profileid,$imageDetails,$screenedOrUnscreened='U')
        {return 1;
                try
                {
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN ins() of NEWJS_PICTURE_DETAILS.class.php");	
			if(!$picId)
				throw new jsException("","PICTUREID IS BLANK IN ins() of NEWJS_PICTURE_DETAILS.class.php");	
			$arr["PROFILEID"] = $profileid;
			$entry_dt = date("Y-m-d");
			$arr["ENTRY_DT"] = $entry_dt;
			if($screenedOrUnscreened=='U')
				$arr["UNSCREENED_PICTUREID"] = $picId;

			$size = $imageDetails['FileSize'];
			if($size)
				$arr["SIZE"] = ":SIZE";

                        if(!$size)
                                return;

			$height = $imageDetails['COMPUTED']['Height'];
			if($height)
				$arr["HEIGHT"] = $height;
			
			$width = $imageDetails['COMPUTED']['Width'];
			if($width)
				$arr["WIDTH"] = $width;

			$length = $imageDetails['FocalLength'];
			if($length)
				$arr["FOCAL_LENGTH"] = $length;
		
			$time = $imageDetails['DateTime'];
			if($time)	
				$arr["CAMERA_DATETIME"] = $time;

			$make = $imageDetails['Make'];
			if($make)
				$arr["MAKE"] = $make;

			$model = $imageDetails['Model'];
			if($model)
				$arr["MODEL"] = $model;

			$col='';$val='';
			foreach($arr as $k=>$v)
			{
				$col = $col."".$k.",";
				$k= ":".$k;
				$val = $val.$k.",";
			}
			$col = rtrim($col,",");
			$val = rtrim($val,",");

                        $sql = "INSERT IGNORE INTO newjs.PICTURE_DETAILS($col) VALUES ($val)";
                        $res = $this->db->prepare($sql);
                        $res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			if($size)
	                        $res->bindParam(":SIZE", $size, PDO::PARAM_INT);
			if($height)
	                        $res->bindParam(":HEIGHT", $height, PDO::PARAM_INT);	
			if($width)
	                        $res->bindParam(":WIDTH", $width, PDO::PARAM_INT);
			if($length)
	                        $res->bindParam(":FOCAL_LENGTH", $length, PDO::PARAM_INT);
			if($time)
                        	$res->bindParam(":CAMERA_DATETIME", $time, PDO::PARAM_INT);
			if($make)
	                        $res->bindParam(":MAKE", $make, PDO::PARAM_STR);
			if($model)
	                        $res->bindParam(":MODEL", $model, PDO::PARAM_STR);
			$res->bindValue(":ENTRY_DT", $entry_dt, PDO::PARAM_STR);
			if($picId)
			{
				if($arr["UNSCREENED_PICTUREID"])
		                        $res->bindParam(":UNSCREENED_PICTUREID", $picId, PDO::PARAM_INT);
				else
		                        $res->bindParam(":SCREENED_PICTUREID", $picId, PDO::PARAM_INT);
			}
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/**
	* update screened picture id corresponding to unscreened picture id.
	* @param oldPicId int
	* @param newMappedPicId int
	* @param profileid int
	* @access public
	*/
        public function upd($oldPicId,$newMappedPicId,$profileid)
        {return 1;
                try
                {
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN ins() of NEWJS_PICTURE_DETAILS.class.php");	
			if(!$oldPicId)
				throw new jsException("","oldPicId IS BLANK IN ins() of NEWJS_PICTURE_DETAILS.class.php");	
			if(!$newMappedPicId)
				throw new jsException("","newMappedPicId IS BLANK IN ins() of NEWJS_PICTURE_DETAILS.class.php");	
                        $sql = "UPDATE newjs.PICTURE_DETAILS SET SCREENED_PICTUREID=:SCREENED_PICTUREID WHERE PROFILEID=:PROFILEID AND UNSCREENED_PICTUREID=:UNSCREENED_PICTUREID";
                        $res = $this->db->prepare($sql);
                        $res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindParam(":SCREENED_PICTUREID", $newMappedPicId, PDO::PARAM_INT);
			$res->bindParam(":UNSCREENED_PICTUREID", $oldPicId, PDO::PARAM_INT);	
                        $res->execute();
			if($res->rowCount()==0)
			{
				$sql = "UPDATE newjs.PICTURE_DETAILS SET SCREENED_PICTUREID=:SCREENED_PICTUREID WHERE PROFILEID=:PROFILEID AND SCREENED_PICTUREID=:UNSCREENED_PICTUREID";
				$res = $this->db->prepare($sql);
				$res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
				$res->bindParam(":SCREENED_PICTUREID", $newMappedPicId, PDO::PARAM_INT);
				$res->bindParam(":UNSCREENED_PICTUREID", $oldPicId, PDO::PARAM_INT);	
				$res->execute();
			}
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/**
	*/
        public function get($whereCriteriaArr,$excludeArr='')
	{
		$fields = "*";

		if(!$whereCriteriaArr)
			throw new jsException("","whereCriteriaArr, compulsory paramaters missing in get() of NEWJS_PICTURE_DETAILS.class.php");	

		$sql = "SELECT $fields FROM newjs.PICTURE_DETAILS WHERE ";
	
		if(is_array($whereCriteriaArr))	
		foreach($whereCriteriaArr as $k=>$v)
		{
			$whererArr[] = $k."=:".$k;
			${$k} = $v;
		}	

		if(is_array($excludeArr))	
		foreach($excludeArr as $k=>$v)
		{
			$whererArr[] = $k." NOT IN (:".$k.")";
			${$k} = $v;
		}	

		$sql.=implode(" AND ",$whererArr);
		$res = $this->db->prepare($sql);

		if($PROFILEID)
			$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
		if($SCREENED_PICTUREID)
			$res->bindValue(":SCREENED_PICTUREID", $SCREENED_PICTUREID, PDO::PARAM_INT);
		if($SIZE)
			$res->bindValue(":SIZE", $SIZE, PDO::PARAM_INT);
		if($WIDTH)
			$res->bindValue(":WIDTH", $WIDTH, PDO::PARAM_INT);
		if($HEIGHT)
			$res->bindValue(":HEIGHT", $HEIGHT, PDO::PARAM_INT);
		if(isset($FOCAL_LENGTH))
			$res->bindValue(":FOCAL_LENGTH", $FOCAL_LENGTH, PDO::PARAM_INT);
		if($CAMERA_DATETIME)
			$res->bindValue(":CAMERA_DATETIME", $CAMERA_DATETIME, PDO::PARAM_STR);
		/*
		if(isset($MAKE))
			$res->bindValue(":MAKE", $MAKE, PDO::PARAM_STR);
		*/
		if(isset($MODEL))
			$res->bindValue(":MODEL", $MODEL, PDO::PARAM_STR);
		/*
		if($)
			$res->bindValue(":", $, PDO::PARAM_INT);
		*/

		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$detailArr[] = $row;
		}
		return $detailArr;
	}
}
?>
