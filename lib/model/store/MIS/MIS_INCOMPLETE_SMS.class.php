<?php
//This class is used to execute queries on MIS.INCOMPLETE_SMS table
//Created by Nitesh Sethi
class MIS_INCOMPLETE_SMS extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertLanding($pid)
	{
		if(!$pid)
                        throw new jsException("","error in hits ");
		$date=date("Y-m-d H-i-s");
		try
		{
			$sql = "INSERT IGNORE INTO MIS.INCOMPLETE_SMS(PROFILEID,DATE,PAGE_DISPLAY,PAGE_SUBMIT) VALUES(:pid,:date,:pageDisplay,:pageSubmit)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":pid", $pid, PDO::PARAM_INT);
                        $res->bindValue(":date", $date, PDO::PARAM_STR);
                        $res->bindValue(":pageDisplay", 'Y', PDO::PARAM_STR);
                        $res->bindValue(":pageSubmit", 'N', PDO::PARAM_STR);
			$res->execute();
			

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	
	public function insertCompletion($pid)
	{
		if(!$pid)
           throw new jsException("","error in hits ");
		
		try
		{
			$sql = "UPDATE MIS.INCOMPLETE_SMS set PAGE_SUBMIT=:pageSubmit WHERE PROFILEID=:pid";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":pid", $pid, PDO::PARAM_INT);
                        $res->bindValue(":pageSubmit", 'Y', PDO::PARAM_STR);
			$res->execute();

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	
}
?>
