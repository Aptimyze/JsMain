<?php
class KUNDLI_ALERT_KUNDLI_MATCHES_MAILER extends TABLE
{
	public function __construct($dbname = "") {
		$dbname = "matchalerts_slave";
        parent::__construct($dbname);
	}

	//This function is used to truncate kundli Mailer Data
	public function truncateKundliMailerData()
	{
		try
		{
			$sql="TRUNCATE TABLE kundli_alert.KUNDLI_MATCHES_MAILER";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch (PDOException $e)
		{
						//add mail/sms
			throw new jsException($e);
		}
	}

	//This function inserts into kundli mailer, the data of receivers to KUNDLI_MATCHES_MAILER
	public function insertKundliMailerData($profileIdArr)
	{
		try
		{
			if(is_array($profileIdArr))
			{
				$sql = "INSERT IGNORE into kundli_alert.KUNDLI_MATCHES_MAILER (RECEIVER) values ";
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

	//This function fetches details of receiver specifically the PROFILEID's
        public function fetchReceiverDetails($totalScript="1",$currentScript="0",$limit="")
        {
            try
            {
                $result = NULL;
                $sql = "SELECT RECEIVER FROM kundli_alert.KUNDLI_MATCHES_MAILER WHERE RECEIVER%:TOTAL_SCRIPT=:SCRIPT AND SENT=:STATUS";
                if($limit)
                    $sql.= " limit 0,:LIMIT";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                $prep->bindValue(":STATUS",'N',PDO::PARAM_STR);
                if($limit)
                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $result[] = $row;
                }
            return $result;
            }
            catch (PDOException $e)
            {
                throw new jsException($e);
            }
        }


        public function updateUserMailerData($dataArr)
    {
        try
        {
            $sql .= "UPDATE kundli_alert.KUNDLI_MATCHES_MAILER SET ";
            $count = 1;
            $gunaCount = 1;
            $i =1;
            $j=1;
            $whereCond = " WHERE RECEIVER = :RECEIVERID";
            foreach($dataArr as $receiverId=>$value)
            {
                if($receiverId != "GUNASCORE")
                {
                	foreach($value as $user=>$userId)
                	{
                		if($count<=16 && in_array($user,kundliMatchAlertMailerEnums::$userArray))
                		{
                			$sql .=$user."=:USER".$count." ,";
                		}
                		$count++;

                	}
                	if(array_key_exists("SENT", $value))
                	{
                		$sql .="SENT =:SENT ,";
                	}
                }
                else
                {
                	foreach($value as $gunaUser=>$gunaScore)
                	{
                		if($gunaCount<=16 && in_array($gunaUser,kundliMatchAlertMailerEnums::$gunaUserArray))
                		{
                			$sql .=$gunaUser."=:GUNA_SCORE".$gunaCount." ,";
                		}
                		$gunaCount++;
                	}
                }                                

            }
            $sql = rtrim($sql,",");
            $sql = $sql.$whereCond;
            $pdoStatement = $this->db->prepare($sql);
            foreach($dataArr as $receiverId=>$value)
            {                
                if($receiverId!="GUNASCORE")
                {	
                	if($i=1)
                    	$pdoStatement->bindValue(":RECEIVERID",$receiverId,PDO::PARAM_INT);
                	foreach($value as $user=>$userId)
                	{
                		if($i<=16 && in_array($user,kundliMatchAlertMailerEnums::$userArray))
                		{
                			$pdoStatement->bindValue(":USER".$i,$userId,PDO::PARAM_INT);
                		}
                		$i++;
                	}
                	if(array_key_exists("SENT", $value))
                	{
                		$pdoStatement->bindValue(":SENT",$value["SENT"],PDO::PARAM_STR);
                	}
                }
                else
                {
                	foreach($value as $gunaUser=>$gunaScore)
                	{
                		if($j<=16 && in_array($gunaUser,kundliMatchAlertMailerEnums::$gunaUserArray))
                		{
                			$pdoStatement->bindValue(":GUNA_SCORE".$j,$gunaScore,PDO::PARAM_INT);
                		}
                		$j++;
                	}
                }          
            }

            $pdoStatement->execute();
        }

        catch (PDOException $e)
        {
            throw new jsException($e);
        }
        
    }

    /* This function is used to get all the profile which need to recieve KundliAlertsMail i.e having SENT<>Y 
    * @param fields : fields to get if different from default
    * @param totalScript : number of script which can be executed
    * @param script : current script number
    * @param limit : limit if required
    * @return result : details of mailer to be sent 
    */
    public function getMailerProfiles($fields="",$totalScript="1",$script="0",$limit="")
    {
        try 
        {
            $defaultFields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,USER11,USER12,USER13,USER14,USER15,USER16,GUNA_U1,GUNA_U2,GUNA_U3,GUNA_U4,GUNA_U5,GUNA_U6,GUNA_U7,GUNA_U8,GUNA_U9,GUNA_U10,GUNA_U11,GUNA_U12,GUNA_U13,GUNA_U14,GUNA_U15,GUNA_U16";

            $selectfields = $fields?$fields:$defaultFields;
            $sql = "SELECT $selectfields FROM kundli_alert.KUNDLI_MATCHES_MAILER where SENT IN ('U') AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
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
                if(!$fields)
                {
                    $fieldsArray = explode(",",$defaultFields);
                    foreach($fieldsArray as $k=>$v)
                    {
                        $result[$row["SNO"]][$v]=$row[$v];
                    }
                }
                else
                    $result[] = $row;
                unset($result[$row["SNO"]]["SNO"]);
            }
            return $result;         
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function updateKundliMatchesUsersFlag($sno,$flag,$pid)
    {
        try
        {
            $sql = "UPDATE kundli_alert.KUNDLI_MATCHES_MAILER SET SENT=:FLAG WHERE SNO=:SNO AND RECEIVER =:RECEIVERID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SNO",$sno,PDO::PARAM_INT);
                        $prep->bindValue(":FLAG",$flag,PDO::PARAM_STR);
                        $prep->bindValue(":RECEIVERID",$pid,PDO::PARAM_INT);
                        $prep->execute();
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

}