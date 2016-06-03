<?php
class MIS_REG_COUNT extends TABLE{
       

        

        public function __construct($dbName="")
        {
			parent::__construct($dbname);
        }
	public function updateEntryRegPage($page,$status,$profileid)
	{
		try
		{
			$res=null;
			if($profileid && $status && $page)
			{
				$sql="UPDATE MIS.REG_COUNT SET $page=:status WHERE PROFILEID=:profileid";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
				$prep->bindValue(":status",$status,PDO::PARAM_STR);
				$prep->execute();
			}
			else
				throw new jsException("error in mis reg count $profileid $status");
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	
	public function insert($id,$page)
	{
		try
		{
			if($id && $page)
			{
				$sql="INSERT INTO MIS.REG_COUNT(PROFILEID,$page) VALUES (:id,'Y')";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":id",$id,PDO::PARAM_INT);
				$prep->execute();
			}
			else
				throw new jsException("error in mis reg count $profileid $status");
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
?>
