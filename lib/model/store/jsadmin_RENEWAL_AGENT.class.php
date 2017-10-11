<?php
class RENEWAL_AGENT extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function fetchAgentsForRenewal()
	{
		try
		{
			$sql="SELECT USER FROM jsadmin.RENEWAL_AGENT";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
        			$executives[] = $result['USER'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $executives;

	}
}
?>
