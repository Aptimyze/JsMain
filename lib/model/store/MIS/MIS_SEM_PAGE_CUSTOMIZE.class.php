<?php

class MIS_SEM_PAGE_CUSTOMIZE extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function sourceSEM()
	{
		$sql="SELECT SQL_CACHE SOURCE FROM MIS.SEM_PAGE_CUSTOMIZE WHERE ACTIVE='Y'";
		$res = $this->db->prepare($sql);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		$arrayValues[]=$row['SOURCE'];;
		return $arrayValues;
	}
}
 
?>