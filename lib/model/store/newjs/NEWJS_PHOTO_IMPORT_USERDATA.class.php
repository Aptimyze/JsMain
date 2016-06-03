<?php
/**
* This class is used to access/updated picture related details like image size , height ,width .....
*/
class NEWJS_PHOTO_IMPORT_USERDATA extends TABLE
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
        public function ins($imageDetails,$profileid)
        {
                try
                {
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN ins() of NEWJS_PHOTO_IMPORT_USERDATA.class.php");	
			$source = $imageDetails["SOURCE"];
			$uniqueId = $imageDetails["UNIQUE_ID"];
			$dt = date("Y-m-d");

                        $sql = "INSERT IGNORE INTO newjs.PHOTO_IMPORT_USERDATA(PROFILEID,SOURCE,UNIQUE_ID,ENTRY_DT) VALUES (:PROFILEID,:SOURCE,:UNIQUE_ID,'$dt')";
                        $res = $this->db->prepare($sql);

                        $res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
	                $res->bindParam(":SOURCE", $source, PDO::PARAM_STR);
	                $res->bindParam(":UNIQUE_ID", $uniqueId, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/**
	* getter function
	*/
	public function get($profileid="",$source='',$greaterDt='',$uniqueId='',$skipPid='')
	{
		try
		{
			$sql = "SELECT * FROM newjs.PHOTO_IMPORT_USERDATA WHERE 1";
			if($profileid)
				$sql.=" AND PROFILEID=:PROFILEID";
			if($skipPid)
				$sql.=" AND PROFILEID!=:SPROFILEID";
			if($source)
				$sql.=" AND SOURCE=:SOURCE";
			if($greaterDt)
				$sql.=" AND ENTRY_DT>=:ENTRY_DT";
			if($uniqueId)
				$sql.=" AND UNIQUE_ID=:UNIQUE_ID";
                        $res = $this->db->prepare($sql);

			if($profileid)
				$res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			if($uniqueId)
		                $res->bindParam(":UNIQUE_ID", $uniqueId, PDO::PARAM_STR);
			if($skipPid)
				$res->bindParam(":SPROFILEID", $skipPid, PDO::PARAM_INT);
			if($source)
				$res->bindParam(":SOURCE", $source, PDO::PARAM_STR);
			if($greaterDt)
				$res->bindParam(":ENTRY_DT", $greaterDt, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
}
?>
			
