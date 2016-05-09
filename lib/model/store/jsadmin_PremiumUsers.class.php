<?php
class jsadmin_PremiumUsers extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function AddUser($pid,$did,$time)
	{
		try
		{
			$sql="insert ignore into jsadmin.PremiumUsers(PID,DID,DATE) values(:PID,:DID,:TIME)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$pid,PDO::PARAM_INT);
			$prep->bindValue(":DID",$did,PDO::PARAM_INT);
			$prep->bindValue(":TIME",$time,PDO::PARAM_INT);
			$prep->execute();
			return $prep->rowCount();

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function RemoveUser($did)
	{
		try
                {
			$sql="delete from jsadmin.PremiumUsers where DID=:DID";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":DID",$did,PDO::PARAM_INT);
			$prep->execute();
			return $prep->rowCount();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
	public function isDummy($did)
	{
		try
                {
			$sql="select DID from jsadmin.PremiumUsers where DID=:DID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DID",$did,PDO::PARAM_INT);
			$prep->execute();
			 if($result = $prep->fetch(PDO::FETCH_ASSOC))
				return $result;
		}
		catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
	

}
?>
