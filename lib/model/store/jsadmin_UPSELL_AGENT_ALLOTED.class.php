<?php
class UPSELL_AGENT_ALLOTED extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}

	public function getLastAgentAlloted()
	{
		try
		{
			$sql="SELECT USER FROM jsadmin.UPSELL_AGENT_ALLOTED";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
        			$lastExecutive = $result['USER'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $lastExecutive;

	}
	public function updateLastAllotedAgent($allotTo)
	{
		try
                {
                        $sql="UPDATE jsadmin.UPSELL_AGENT_ALLOTED SET USER=:USER WHERE 1";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":USER",$allotTo,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
