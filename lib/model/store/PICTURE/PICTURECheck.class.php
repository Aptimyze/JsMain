<?php

class PICTURECheck extends TABLE
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
			$sql="INSERT IGNORE INTO PICTURE.PictureUploadCheck(PROFILEID,ENTRY_DT,ALBUM_PAGE) VALUES(:ID,:date,1)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$PROFILEID,PDO::PARAM_INT);
			//$pdoStatement->bindValue(":errortype",$type ,PDO::PARAM_STR);
			//$pdoStatement->bindValue(":errormsg",$msg,PDO::PARAM_INT);
			$pdoStatement->bindValue(":date",$szTimeStamp,PDO::PARAM_STR);
			$pdoStatement->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function Update($PROFILEID,$type,$msg)
	{
		try
		{
			$sql= "UPDATE PICTURE.PictureUploadCheck  SET ".$msg."=".$msg."+1 WHERE PROFILEID =:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$PROFILEID,PDO::PARAM_STR);
			$pdoStatement->execute();
			$count=$pdoStatement->rowCount();
			if($count>0)
				return true;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}
}
?>
