<?php
class INVALID_SCREENING_ENTRIES_TRACKING extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function trackInvalidScreeningEntries($profileid)
	{
		$sql="INSERT IGNORE INTO MIS.INVALID_SCREENING_ENTRIES_TRACKING (PROFILEID, DATE) VALUES(:PROFILEID,CURDATE())";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
        }

}
?>
