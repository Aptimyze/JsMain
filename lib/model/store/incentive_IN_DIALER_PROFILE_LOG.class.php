<?php
class incentive_IN_DIALER_PROFILE_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function insertProfile($profileid,$username='',$eligible,$filterName='',$filterValue='')
        {
                try
                {
			if(!$filterName)
				$filterName='';
			if(!$filterValue)
				$filterValue='';
			if(!$username)
				$username='';
                        $sql = "INSERT INTO incentive.IN_DIALER_PROFILE_LOG (PROFILEID, USERNAME, ELIGIBLE, FILTER_NAME, FILTER_VALUE, ENTRY_DT) VALUES(:PROFILEID, :USERNAME, :ELIGIBLE, :FILTER_NAME, :FILTER_VALUE, CURDATE())";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
                        $prep->bindValue(":ELIGIBLE",$eligible,PDO::PARAM_STR);
                        $prep->bindValue(":FILTER_NAME",$filterName,PDO::PARAM_STR);
                        $prep->bindValue(":FILTER_VALUE",$filterValue,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
