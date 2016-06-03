<?php

class PICTURE_ErrorForPictureUpload extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function Insert($PROFILEID,$type,$msg)
	{
		try
		{
			$szTimeStamp = date("Y-m-d");
			$sql="INSERT INTO PICTURE.ErrorForPictureUpload(PROFILEID,ENTRY_DT,error_type,error_msg) VALUES(:ID,:date,:errortype,:errormsg)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$PROFILEID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":date",$szTimeStamp,PDO::PARAM_STR);
			$pdoStatement->bindValue(":errortype",$type ,PDO::PARAM_STR);
			$pdoStatement->bindValue(":errormsg",$msg,PDO::PARAM_STR);
			$pdoStatement->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
}
?>
