<?php
class FEATURED_PROFILE_MAILER extends TABLE
{
	public function __construct($dbname = "") {
		parent::__construct($dbname);
	}
	public function truncateFeaturedProfileData()
	{
		try
		{
			$sql="TRUNCATE TABLE mailer.FEATURED_PROFILE_MAILER";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch (PDOException $e)
		{
						//add mail/sms
			throw new jsException($e);
		}
	}

	public function insertFeaturedProfileData($profileIdArr)
	{
		try
		{
			if(is_array($profileIdArr))
			{
				$sql = "INSERT IGNORE into mailer.FEATURED_PROFILE_MAILER (PROFILEID) values ";
				foreach($profileIdArr as $key=>$profileId)
				{
					$sqlInsert.= "(:PROFILEID".$key."),";
				}
				$sql .= rtrim($sqlInsert,",");

				$pdoStatement = $this->db->prepare($sql);

				foreach($profileIdArr as $key=>$profileId)
				{
					$pdoStatement->bindValue(":PROFILEID".$key, $profileId, PDO::PARAM_INT);
				}
				$pdoStatement->execute();
			}
		}
		catch (PDOException $e)
		{
						//add mail/sms
			throw new jsException($e);
		}
	}

	public function getMailerProfiles($totalScript="1",$script="0",$limit)
	{
		try 
        {
            $sql = "SELECT PROFILEID FROM mailer.FEATURED_PROFILE_MAILER where SENT IN ('N') AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
            if($limit)
                $sql.= " limit 0,:LIMIT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
            $prep->bindValue(":SCRIPT",$script,PDO::PARAM_INT);
            if($limit)
                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
            $prep->execute();

            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $detailArr[] = $row['PROFILEID'];
            }
            return $detailArr;         
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
	}

	public function update($profileId,$flag)
	{
		try
		{
			$sql = "UPDATE mailer.FEATURED_PROFILE_MAILER SET SENT=:FLAG WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":FLAG",$flag,PDO::PARAM_STR);
			$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
}