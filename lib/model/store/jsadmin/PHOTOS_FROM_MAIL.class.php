<?php

/**
 * This table is used to store details of each mail that has been sent to photos@jeevansathi.com.
**/
class PHOTOS_FROM_MAIL extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	 * This function is used to get details of a mail corresponding to the ID passed.
	**/
	public function getMailDetails($id)
	{
		$sql = "SELECT SENDER,SUBJECT,MESSAGE,DATE,ID FROM jsadmin.PHOTOS_FROM_MAIL WHERE ID=:ID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":ID", $id, PDO::PARAM_INT);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
$sub = utf8_decode($row["SUBJECT"]);
$sub = trim($sub,"?");
$row["SUBJECT"] = $sub;
			return $row;
		}
		else
			return NULL;
	}

}

?>
