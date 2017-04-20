<?php
class search_PAID_MEMBERS_MAILER extends TABLE
{
	public function __construct($dbname = "") {
		parent::__construct($dbname);
	}

	/* This function is used to get all the profile which need to recieve paidMembersMail 
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
            $defaultFields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,USER11,USER12,USER13,USER14,USER15,USER16";

            $selectfields = $fields?$fields:$defaultFields;
            $sql = "SELECT $selectfields FROM search.PAID_MEMBERS_MAILER where SENT IN ('N','F') AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
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

    public function updatePaidMembersReceiverFlag($sno,$flag,$profileId)
    {
    	try
		{
			$sql = "UPDATE search.PAID_MEMBERS_MAILER SET SENT=:FLAG WHERE RECEIVER=:PROFILEID AND SNO=:SNO";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":FLAG",$flag,PDO::PARAM_STR);
			$prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$prep->bindValue(":SNO",$sno,PDO::PARAM_INT);
			$prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
    }
}