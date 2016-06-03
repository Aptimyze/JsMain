<?php
class PHOTO_URL extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	/**
	* This function inserts urls of all the photos being displayed on the page into the table newjs.PHOTO_URL
	* and generates a unique id for each url.
	**/
	public function insertURL($allUrls, $profileid, $start, $end, $importSite)
	{
		$picNo=$start;
		foreach($allUrls as $key=>$value)
		{
			$sqlArr[]="(:PROFILEID,:v$key,:PIC_NO$key,:IMPORTSITE)";
		}
		$sqlStr = implode(",",$sqlArr);
                $sql="INSERT IGNORE INTO newjs.PHOTO_URL(PROFILEID,LARGE_URL,PHOTO_NO,IMPORTSITE) VALUES $sqlStr";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":IMPORTSITE", $importSite, PDO::PARAM_STR);
		foreach($allUrls as $key=>$value)
                {
                        $res->bindValue(":PIC_NO$key", $picNo++, PDO::PARAM_INT);
                	$res->bindValue(":v$key", $value, PDO::PARAM_STR);
                }
		$res->execute();
		$sql1="SELECT ID FROM newjs.PHOTO_URL WHERE PROFILEID= :PROFILEID AND PHOTO_NO BETWEEN :START AND :END AND IMPORTSITE=:IMPORTSITE ORDER BY ID ASC";
		$res1=$this->db->prepare($sql1);
		$res1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res1->bindValue(":START", $start, PDO::PARAM_INT);
		$res1->bindValue(":END", $end, PDO::PARAM_INT);
		$res1->bindValue(":IMPORTSITE", $importSite, PDO::PARAM_STR);
		$res1->execute();
		
		while($row = $res1->fetch(PDO::FETCH_ASSOC))
			$idList[] = $row['ID'];
		return $idList;
        }

	/**
	* This function maps inputted ids with the ids in newjs.PHOTO_URL ans returns the corresponding URLs.
	**/
	public function mapURL($profileid, $idList)
	{
		$idStr= str_replace("\'","",$idList);
		$idArr= explode(",",$idStr);
		foreach($idArr as $k=>$v)
			$idSqlArr[]=":v$k";
		$idSql="(".(implode(",",$idSqlArr)).")";
		$sql="SELECT LARGE_URL FROM newjs.PHOTO_URL WHERE PROFILEID = :PROFILEID AND ID IN $idSql";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		foreach($idArr as $k=>$v)
			$res->bindValue(":v$k", $v, PDO::PARAM_INT);
		$res->execute();
	
		while($row = $res->fetch(PDO::FETCH_ASSOC))
			$urlList[] = $row['LARGE_URL'];

		return $urlList;
	}

	/**
	* This function empties all entries of a profileid from the table newjs.PHOTO_URL.
	**/
	public function emptyTable($profileid,$importSite)
	{
		$sql="DELETE FROM newjs.PHOTO_URL WHERE PROFILEID = :PROFILEID AND IMPORTSITE=:IMPORTSITE";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":IMPORTSITE", $importSite, PDO::PARAM_STR);
		$res->execute();
	}

	/**
	* This function deletes the entry corresponding to the id that is passed.
	**/
	function deleteId($id)
	{
		$sql="DELETE FROM newjs.PHOTO_URL WHERE ID =:ID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":ID", $id, PDO::PARAM_INT);
		$res->execute();
	
	}
}
?>
