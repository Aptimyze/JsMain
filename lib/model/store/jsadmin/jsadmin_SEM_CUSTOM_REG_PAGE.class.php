<?php
/**
 * This class is used to interact with jsadmin.SEM_CUSTOM_REG_PAGE Table
 * @author Kunal Verma
 * @created 2014-02-06
 */
class jsadmin_SEM_CUSTOM_REG_PAGE extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	public function fetchAllRecords()
	{
		try
		{
			$sql = "SELECT * from jsadmin.SEM_CUSTOM_REG_PAGE ORDER BY TIME DESC";
			$res = $this->db->prepare($sql);
			$res->execute();
			$result=$res->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
		
	public function fetchRecord($iPageId)
	{
		try
		{
			$sql = "SELECT CONTENT from jsadmin.SEM_CUSTOM_REG_PAGE WHERE PAGE_ID=:PID";
			
			$res = $this->db->prepare($sql);
			$res->bindValue(":PID", $iPageId, PDO::PARAM_INT);				  	
			$res->execute();
			$result=$res->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	
	public function insertRecord($szContent,$szTitle="")
	{
		if($szContent == "" || $szContent == null)
			throw new jsException("","Content is null passed for insert new record in SEM_CUSTOM_REG_PAGE");
			
		try
		{
			$now = date("Y-m-d H:i:s");
			$sql = "INSERT INTO jsadmin.SEM_CUSTOM_REG_PAGE(TITLE,CONTENT, TIME) VALUES (:title,:content,:time)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":title", $szTitle, PDO::PARAM_STR);				  	
			$res->bindValue(":content", $szContent, PDO::PARAM_STR);				  	
			$res->bindValue(":time", $now, PDO::PARAM_STR);				  	
			$res->execute();
			$page_id = $this->db->lastInsertId("PAGE_ID");
			return $page_id;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function updateRecord($iPage_ID,$szContent,$szTitle="")
	{
		if($szContent == "" || $szContent == null)
			throw new jsException("","Content is null passed for updating record in SEM_CUSTOM_REG_PAGE");
		
		if($iPage_ID == "" || $iPage_ID == null)
			throw new jsException("","Page is null passed for updating record in SEM_CUSTOM_REG_PAGE");
			
		try
		{
			$now = date("Y-m-d H:i:s");
			$sql = "UPDATE jsadmin.SEM_CUSTOM_REG_PAGE SET CONTENT=:content,TIME=:now,TITLE=:title WHERE PAGE_ID=:id";
			
			$res = $this->db->prepare($sql);
			$res->bindValue(":title", $szTitle, PDO::PARAM_STR);				  	
			$res->bindValue(":content", $szContent, PDO::PARAM_STR);				  	
			$res->bindValue(":now", $now, PDO::PARAM_STR);				  	
			$res->bindValue(":id", $iPage_ID, PDO::PARAM_STR);				  	
			$res->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function deleteRecord($iPage_ID)
	{
		if($iPage_ID == "" || $iPage_ID == null)
			throw new jsException("","Page is null passed for deleting record in SEM_CUSTOM_REG_PAGE");
			
		try
		{
			$now = date("Y-m-d H:i:s");
			$sql = "DELETE FROM jsadmin.SEM_CUSTOM_REG_PAGE WHERE PAGE_ID=:id";
			$res = $this->db->prepare($sql);
			$res->bindValue(":id", $iPage_ID, PDO::PARAM_INT);				  	
			$res->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
}
?>
