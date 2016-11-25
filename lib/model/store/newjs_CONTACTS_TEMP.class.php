<?php

class NEWJS_CONTACTS_TEMP extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }
    
    public function getTemporaryContactsCount($profileid)
    {
        try {
            $today = date("Y-m-d");
            $sql   = "SELECT COUNT(1) cnt, DATE(TIME) date FROM newjs.CONTACTS_TEMP WHERE SENDER = :PROFILEID AND DELIVERED='N' GROUP BY date";
            $prep  = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                if ($row["date"] === $today) {
                    $tempDayContactCount = $row["cnt"];
                }
                $tempOverAllContactCount += $row["cnt"];
            }
            return array(
                $tempOverAllContactCount,
                $tempDayContactCount
            );
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function insert($sender_profileid, $receiver_profileid, $message, $draft_name, $draft_write, $stype)
    {
        try {
            $sql  = "INSERT IGNORE INTO newjs.CONTACTS_TEMP(SENDER, RECEIVER, CUST_MESSAGE, DRAFT_NAME, DRAFT_MESSAGE, STYPE, TIME, DELIVERED) VALUES(:SENDER, :RECEIVER, :CUST_MESSAGE, :DRAFT_NAME, :DRAFT_MESSAGE, :STYPE, now(), 'N')";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SENDER", $sender_profileid, PDO::PARAM_INT);
            $prep->bindValue(":RECEIVER", $receiver_profileid, PDO::PARAM_INT);
            $prep->bindValue(":CUST_MESSAGE", $message, PDO::PARAM_STR);
            $prep->bindValue(":DRAFT_NAME", $draft_name, PDO::PARAM_STR);
            $prep->bindValue(":DRAFT_MESSAGE", $draft_write, PDO::PARAM_STR);
            $prep->bindValue(":STYPE", $stype, PDO::PARAM_STR);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getTempContacts($sender, $receivers)
    {
        $sql = "SELECT RECEIVER FROM newjs.CONTACTS_TEMP WHERE SENDER = $sender AND RECEIVER IN ($receivers) AND DELIVERED='N'";
        $res = $this->db->prepare($sql);
        $res->execute();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
    /**
     * get the details of temp contact for given sender arrary
     * @param sender array 
     * @return array of temp contact details 
     * @access public
     */
    public function getTempAllContacts($sendersArr)
    {
		try {
            $sender = implode(",", $sendersArr);
            $sql    = "SELECT * FROM newjs.CONTACTS_TEMP WHERE SENDER IN (" . $sender . ")  AND DELIVERED='N'";
            $res    = $this->db->prepare($sql);
            $res->execute();
            $i = 0;
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $tempContact[$i]["SENDER"]        = $row["SENDER"];
                $tempContact[$i]["RECEIVER"]      = $row["RECEIVER"];
                $tempContact[$i]["STYPE"]         = $row["STYPE"];
                $tempContact[$i]["CUST_MESSAGE"]  = $row["CUST_MESSAGE"];
                $tempContact[$i]["DRAFT_NAME"]    = $row["DRAFT_NAME"];
                $tempContact[$i]["DRAFT_MESSAGE"] = $row["DRAFT_MESSAGE"];
                $i++;
            }
            return $tempContact;
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    /**
     * set the temp contact as delivered 
     * @param sender , receiver, error if present
     * @return void
     * @access public
     */
    public function setDeliveredInTempContacts($sender, $receiver, $error = "")
    {
        try {
            if ($error) {
                $sql = "UPDATE CONTACTS_TEMP SET DELIVERED='E', DELIVER_TIME=now(), COMMENTS= :ERROR WHERE SENDER  = :SENDER AND RECEIVER=:RECEIVER AND DELIVERED ='N'";
            } else {
                $sql = "UPDATE CONTACTS_TEMP SET DELIVERED='Y', DELIVER_TIME=now() WHERE SENDER = :SENDER AND RECEIVER= :RECEIVER AND DELIVERED='N'";
            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SENDER", $sender, PDO::PARAM_INT);
            $prep->bindValue(":RECEIVER", $receiver, PDO::PARAM_INT);
            if ($error)
                $prep->bindValue(":ERROR", $error, PDO::PARAM_STR);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
        
    }
    
     /**
     * Delete the entry from CONTACTS_TEMP which contacts delivered 30 days before the current time. 
     * @param time before all delivered entries have to delete
     * @return void
     * @access public
     */
    public function deleteOldDeliveredContact($time)
    {
		try{
			$sql = "DELETE FROM CONTACTS_TEMP WHERE DELIVERED != 'N' AND DELIVER_TIME < :TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":TIME",$time,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e) {
			throw new jsException($e);
		}
	}
	public function getTempContact($sender,$receiver)
	{
		try{
			$sql = "SELECT CONTACTID,SENDER,RECEIVER,TIME as `DATE` from newjs.CONTACTS_TEMP where SENDER= :SENDER and RECEIVER= :RECEIVER AND DELIVERED='N' ";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SENDER",$sender,PDO::PARAM_INT);
			$prep->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
			$prep->execute();
			while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
				$result[] = $row;
			}
		}
		catch(PDOException $e) {
			throw new jsException($e);
		}
        return $result;
    }

    //This function is used to find the Profiles that were contacted by a user
    public function getTempContactProfilesForUser($pid,$seperator)
    {
        try
        {
            $sql = "SELECT RECEIVER from newjs.CONTACTS_TEMP where SENDER= :SENDER AND DELIVERED='N'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SENDER",$pid,PDO::PARAM_INT);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                if($seperator == 'spaceSeperator')
                    $result.= $row["RECEIVER"]." ";
                else
                    $result[] = $row["RECEIVER"];
            }
            //print_r($result);die;
            return $result;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }
    
}
