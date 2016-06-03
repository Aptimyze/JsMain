<?php
class newjs_HOROSCOPE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function getIfHoroscopePresent($pid)
	{
		$sql = "SELECT COUNT(*) as C FROM newjs.HOROSCOPE WHERE PROFILEID=:pid";
                $res=$this->db->prepare($sql);
		$res->bindValue(":pid", $pid, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row["C"];
	}
}
?>
