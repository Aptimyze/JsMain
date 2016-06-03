<?php
class TEMP_SUB_BUCKET extends TABLE
{
	public function __construct($dbname = "") {
  parent::__construct($dbname);
}

	public function insertProfiles($profile)
	{
                try
                {
                        $sql="INSERT INTO incentive.TEMP_SUB_BUCKET (ID,PROFILEID) VALUES (:ID,:PROFILEID)";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":ID",$profile['ID'],PDO::PARAM_STR);
                        $prep->bindValue(":PROFILEID",$profile['PROFILEID'],PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
	public function fetchProfiles()
	{
	}

}	
?>
