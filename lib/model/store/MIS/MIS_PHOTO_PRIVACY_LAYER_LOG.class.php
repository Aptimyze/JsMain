<?php
//This class is used to execute queries on MIS.PHOTO_PRIVACY_LAYER_LOG table
class MIS_PHOTO_PRIVACY_LAYER_LOG extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	/**
		This function is used to insert entries into MIS.PHOTO_PRIVACY_LAYER_LOG table
		It takes the profileid and the page from which this function is called as parameters.
	**/
	public function insertRecord($profileid,$pageName)
	{
		if(!$profileid || !$pageName)
                        throw new jsException("","PROFILEID OR PAGENAME IS BLANK IN insertRecord() OF MIS_PHOTO_PRIVACY_LAYER_LOG.class.php");
		
		try
		{
			$sql = "INSERT INTO MIS.PHOTO_PRIVACY_LAYER_LOG(PROFILEID, FROM_PAGE) VALUES (:PROFILEID, :FROM_PAGE)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->bindValue(":FROM_PAGE", $pageName, PDO::PARAM_STR);
			$res->execute();
			return $this->db->lastInsertId();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/**
		This function is used to update an entry in MIS.PHOTO_PRIVACY_LAYER_LOG table.
		It takes the profileid which is logged in, the auto-increment 'id' for which entry is to be updated, and the selected option as parameters.
	**/
	public function updateRecord($id,$option,$profileid)
	{
		if(!$id || !$option || !$profileid)
			throw new jsException("","ID or OPTION or PROFILEID is blank in updateRecord() of MIS_PHOTO_PRIVACY_LAYER_LOG.class.php");

		try
		{
			$sql = "UPDATE MIS.PHOTO_PRIVACY_LAYER_LOG SET OPTION_SELECTED=:OPTION WHERE ID=:ID AND PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":ID", $id, PDO::PARAM_INT);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->bindValue(":OPTION", $option, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
