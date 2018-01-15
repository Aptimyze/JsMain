<?
class FTO_FTO_CONTACT_VIEWED extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	
	public function getContactViewed(Profile $viewerProfile, Profile $viewedProfile)
	{
		 if(!($viewerProfile instanceof Profile) && !($viewedProfile instanceof Profile))
			throw new jsException("","no where conditions passed");
		try{
			$sql = "SELECT COUNT(*) as COUNT FROM FTO.FTO_CONTACT_VIEWED WHERE VIEWER = :VIEWER AND VIEWED = :VIEWED";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$viewerProfile->getPROFILEID(),PDO::PARAM_INT);
			$res->bindValue(":VIEWED",$viewedProfile->getPROFILEID(),PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			if($row["COUNT"] == 0)
				return false;
			else
				return true;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	public function insertContactViewed($viewerProfile,$viewedProfile,$acceptance)
	{
		if(!$viewerProfile && !$viewedProfile && !$acceptance){
			throw new jsException("","no where conditions passed");}
		try{
			$sql = "INSERT IGNORE INTO FTO.FTO_CONTACT_VIEWED VALUES (:VIEWER, :VIEWED, now() , :ACCEPTANCE)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$viewerProfile->getPROFILEID(),PDO::PARAM_INT);
			$res->bindValue(":VIEWED",$viewedProfile->getPROFILEID(),PDO::PARAM_INT);
			$res->bindValue(":ACCEPTANCE",$acceptance,PDO::PARAM_STR);
			$res->execute();
		}
		catch (PDOExeption $e)
		{
			throw new jsExeption($e);
		}
	}
	public function getInboundCount($profile)
	{
		if(!profile)
			throw new jsExeption("","profile object not passed");
		try{
			$sql = "SELECT COUNT(*) as COUNT FROM FTO.FTO_CONTACT_VIEWED WHERE VIEWER = :VIEWER AND ACCEPTANCE = 'I'";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$profile->getPROFILEID(),PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row['COUNT'];
		}
		catch (PDOExeption $s)
		{
			throw new jsExeption($e);
		}
	}
	public function getOutboundCount($profile)
	{
		if(!profile)
			throw new jsExeption("","profile object not passed");
		try{
			$sql = "SELECT COUNT(*) as COUNT FROM FTO.FTO_CONTACT_VIEWED WHERE VIEWER = :VIEWER AND ACCEPTANCE = 'O'";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$profile->getPROFILEID(),PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row['COUNT'];
		}
		catch (PDOExeption $s)
		{
			throw new jsExeption($e);
		}
	}
	public function getTotalCount($profile)
	{
		if(!profile)
			throw new jsExeption("","profile object not passed");
		try{
			$sql = "SELECT COUNT(*) as COUNT,ACCEPTANCE FROM FTO.FTO_CONTACT_VIEWED WHERE VIEWER = :VIEWER GROUP BY ACCEPTANCE";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$profile->getPROFILEID(),PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($row['ACCEPTANCE'] == 'I')
				{
					$count['INBOUND'] = $row["COUNT"];
					$totalCount = $totalCount+$row["COUNT"];
				}
				if($row['ACCEPTANCE'] == 'O')
				{
					$count['OUTBOUND'] = $row["COUNT"];
					$totalCount = $totalCount+$row["COUNT"];
				}
			}
			$count['TOTAL'] = $totalCount;
			return $count;		
		}
		catch (PDOExeption $s)
		{
			throw new jsExeption($e);
		}
	}
}
			
