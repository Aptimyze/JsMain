<?php
class newjs_SOLR_SEARCH_CACHE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function get($searchId)
	{
		try
		{
			$sql = "SELECT URL FROM newjs.SOLR_SEARCH_CACHE WHERE SEARCHID=:SEARCHID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SEARCHID", $searchId, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$arr["URL"] = $row["URL"];
			return $arr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}
	public function add($searchId,$url)
	{
		try
		{
			$sql="REPLACE INTO newjs.SOLR_SEARCH_CACHE (SEARCHID,URL) VALUES (:SEARCHID,:URL)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SEARCHID", $searchId, PDO::PARAM_INT);
			$res->bindValue(":URL", $url, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>	
