<?php
class incentive_NEW_REGISTRATION_SALES_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function insertProfile($profileid)
        {
                try
                {
                        $sql = "INSERT IGNORE INTO incentive.NEW_REGISTRATION_SALES_LOG (PROFILEID) VALUES(:PROFILEID)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
