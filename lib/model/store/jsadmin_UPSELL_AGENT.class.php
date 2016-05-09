<?php
class UPSELL_AGENT extends TABLE
{
	public function __construct($dbname="incentive_master")
	{
        	parent::__construct($dbname);
	}

	public function fetchAgentsForUpsell()
	{
		try
		{
			$sql="SELECT USER FROM jsadmin.UPSELL_AGENT";
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
