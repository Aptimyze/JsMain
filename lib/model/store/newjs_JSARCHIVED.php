<?php
class newjs_ARCHIVED extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function getArchived($pid)
	{
		if($pid)
		{
			$sql = "select DEACTIVE_DATE,PROFILEID from newjs.JSARCHIVED where PROFILEID=:pid and STATUS ='Y'";
	                $res=$this->db->prepare($sql);
			$res->bindValue(":pid", $pid, PDO::PARAM_INT);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC))
				return $row;
		}
	}
}
?>
