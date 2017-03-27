<?php
class NEWJS_BOOKMARKS extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function isBookmarked($bookmarker,$bookmarkee)
        {
			try 
			{
				if($bookmarker!="" && $bookmarkee!="")
				{ 
					$sql="select BKNOTE from BOOKMARKS where BOOKMARKER=:BOOKMARKER and BOOKMARKEE=:BOOKMARKEE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":BOOKMARKER", $bookmarker, PDO::PARAM_INT);
					$prep->bindValue(":BOOKMARKEE", $bookmarkee, PDO::PARAM_INT);
					$prep->execute();
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
						return true;
					return false;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

	public function getProfilesBookmarks($bookmarker, $bookmarkee, $keyVal='')
	{
		try 
		{
			foreach($bookmarkee as $key=>$b)
			{
				if($key == 0)
					$str = ":BOOKMARKEE".$key;
				else
					$str .= ",:BOOKMARKEE".$key;
			}
			$sql = "SELECT BKNOTE,BOOKMARKEE,BKDATE FROM newjs.BOOKMARKS WHERE BOOKMARKER=:BOOKMARKER AND BOOKMARKEE IN ($str)";
			$res=$this->db->prepare($sql);
			foreach($bookmarkee as $key=>$bm)
			{
				$res->bindValue(":BOOKMARKEE$key", $bm, PDO::PARAM_INT);
			}
			$res->bindValue(":BOOKMARKER", $bookmarker, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($keyVal == 1)
					$bookmarkees[$row['BOOKMARKEE']]=1;
				else
					$bookmarkees[] = $row['BOOKMARKEE'];
			}
			return $bookmarkees;
		}
		catch(PDOException $e)
		{
			jsException::nonCriticalError("lib/model/store/newjs_BOOKMARKS.class.php(3)-->.$sql".$e);
                        return '';
			//throw new jsException($e);
		}
	}
		
	public function addBookmark($bookmarker, $bookmarkee, $note='')
	{
		if($note == '')
			$sql = "REPLACE INTO newjs.BOOKMARKS(BOOKMARKER,BOOKMARKEE,BKDATE) VALUES (:BOOKMARKER, :BOOKMARKEE, CURRENT_TIMESTAMP()) ";
		else
			$sql = "REPLACE INTO newjs.BOOKMARKS(BOOKMARKER,BOOKMARKEE,BKDATE,BKNOTE) VALUES (:BOOKMARKER, :BOOKMARKEE, CURRENT_TIMESTAMP(), :NOTE) ";
		$res=$this->db->prepare($sql);
		$res->bindValue(":BOOKMARKER", $bookmarker, PDO::PARAM_INT);
		$res->bindValue(":BOOKMARKEE", $bookmarkee, PDO::PARAM_INT);
		if($note != '')
			$res->bindValue(":NOTE", $note, PDO::PARAM_STR);
		$res->execute();
	}
	public function getBookmarkCount($bookmarker,$skipProfile=null)
	{
		try{
			if(!$bookmarker){
				throw new jsException("","No profileid  is specified in funcion getBookmarkCount OF newjs_BOOKMARKS.class.php");
			}
			$str = "";
			if(is_array($skipProfile))
			{ 
				$count = 1;
				$str = "BOOKMARKEE NOT IN (";
				foreach($skipProfile as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count]["VALUE"] = $value;
					$bindArr["VALUE".$count]["TYPE"] = "INT";
					$count++;
				}
				$str = substr($str, 0, -1);
				$skipProfile = $str.")";
					$skipProfile = " AND ".$skipProfile;
			}
			$sql = "SELECT COUNT(BOOKMARKEE) AS COUNT FROM newjs.BOOKMARKS WHERE BOOKMARKER = :PROFILEID".$skipProfile;
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$bookmarker,PDO::PARAM_INT);
			if(is_array($bindArr))
			foreach($bindArr as $k=>$v)
			{
				$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
			}
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$output = $row["COUNT"];
		}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
			
	}


	public function getBookmarkedAllForAPeriod($noOfDays, $remainderArray)
	{
		try{
			if(!$noOfDays){
				$noOfDays=30;
			}
			$str = "";

			$sql = "SELECT BOOKMARKER, BOOKMARKEE FROM  newjs.BOOKMARKS WHERE BOOKMARKER % :DIVISOR=:REMAINDER  AND DATE(BKDATE) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY `BKDATE` DESC";
			$res=$this->db->prepare($sql);
			$res->bindValue(":DIVISOR",$remainderArray['divisor'],PDO::PARAM_INT);
			$res->bindValue(":REMAINDER",$remainderArray['remainder'],PDO::PARAM_INT);
			$res->execute();
			while($row=$res->fetch(PDO::FETCH_ASSOC)){

				$result[]=$row;
			}

			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
			return $result;
			
	}
	
	
	public function getBookmarkedProfile($profileid,$conditionArray, $skipArray)
	{
		try{
			if(!$profileid){
				throw new jsException("","No profileid  is specified in funcion getBookmarkCount OF newjs_BOOKMARKS.class.php");
			}
			$string = array('SEEN','BKNOTE','BKDATE');
			$count = 0;
			foreach($conditionArray as $key=>$value)
			{
				if($key=="WHERE")
				{
					foreach($value as $key1=>$value1)
					{
						if($key1 == "NOT_IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$str = $str = $keyName." NOT IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$keyValues =  implode(",",$keyValue);
								$arr[] = $str;
							}
						}
						if($key1 == "IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								if($keyName =="BOOKMARKEE")
									$select = "BOOKMARKER";
								elseif($keyName == "BOOKMARKER")
									$select = "BOOKMARKEE";
								$str = $str = $keyName." IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$arr[] = $str;
							}
						}
						if($key1 == "GREATER_THAN_EQUAL")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName."<= :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								$count++;
							}
						}
					}
					$where = "WHERE ".implode(" AND ",$arr);
				}
				
				if($key == "LIMIT")
				{
					$limit = " LIMIT ";
					$limit = $limit.$value;
				}
				if($key == "ORDER" && !empty($value))
				{
					$order = "ORDER BY $value DESC";
				} 
					
			}
			if(is_array($skipArray))
				{ 
					if($select == "BOOKMARKER")
					$str = "BOOKMARKER NOT IN (";
					else
					$str = "BOOKMARKEE NOT IN (";
					foreach($skipArray as $key=>$value)
					{
						$str = $str.":VALUE".$count.",";
						$bindArr["VALUE".$count]["VALUE"] = $value;
						$bindArr["VALUE".$count]["TYPE"] = "INT";
						$count++;
					}
					$str = substr($str, 0, -1);
					$skipProfile = $str.")";
					if(!isset($where))
						$skipProfile = "WHERE ".$skipProfile;
					else
						$skipProfile = "AND ".$skipProfile;
				}
			$sql = "SELECT ".$select." as PROFILEID,BKDATE as TIME,SEEN FROM newjs.BOOKMARKS ".$where." ".$skipProfile.$order.$limit;
		$res=$this->db->prepare($sql);
		if(is_array($bindArr))
			foreach($bindArr as $k=>$v)
			{
				if($v["TYPE"] =="STRING")
				{
					$res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
				}
				else
				{
					$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
				}
			}
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
		}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
        return $output;
	}
	public function getBookmarkDetails($bookmarker,$bookmarkee)
	{
		foreach($bookmarkee as $key=>$b)
		{
			if($key == 0)
				$str = ":BOOKMARKEE".$key;
			else
				$str .= ",:BOOKMARKEE".$key;
		}
		$sql = "SELECT BKNOTE,BOOKMARKEE,BKDATE FROM newjs.BOOKMARKS WHERE BOOKMARKER=:BOOKMARKER AND BOOKMARKEE IN ($str)";
		$res=$this->db->prepare($sql);
		foreach($bookmarkee as $key=>$bm)
		{
			$res->bindValue(":BOOKMARKEE$key", $bm, PDO::PARAM_INT);
		}
		$res->bindValue(":BOOKMARKER", $bookmarker, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($keyVal == 1)
				$bookmarkees[$row['BOOKMARKEE']]=1;
			else
				$bookmarkees[] = $row;
		}
		return $bookmarkees;
	}
	public function removeBookmark($bookmarker, $bookmarkee)
	{
		$sql = "DELETE FROM newjs.BOOKMARKS WHERE BOOKMARKER = :BOOKMARKER AND BOOKMARKEE = :BOOKMARKEE ";
		$res=$this->db->prepare($sql);
		$res->bindValue(":BOOKMARKER", $bookmarker, PDO::PARAM_INT);
		$res->bindValue(":BOOKMARKEE", $bookmarkee, PDO::PARAM_INT);
		$res->execute();
	}



}
?>
