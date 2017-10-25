<?php
/* This class provided functions for new_matches_emails.MAILER table
 * @author : Reshu Rajput
 * @created : Jun 19, 2014
*/
  
class new_matches_emails_MAILER  extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
	}
	
	/* This function is used to get all the profile which need to recieve new matches or already received on basis of a flag.
	* @param totalScript : number of script which can be executed
	* @param script : current script number
	* @param limit : limit if required
	* @param whereSentInY : flag used to find details for already sent mail for tracking on current date
	* @return result : details of mailer to be sent 
	*/
	public function getMailerProfiles($fields="",$totalScript="1",$script="0",$limit="",$whereSentInY='',$dailyCron=0)
	{
		try 
		{
			$defaultFields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,LOGIC_USED,LINK_REQUIRED,RELAX_CRITERIA";

			$selectfields = $fields?$fields:$defaultFields;
                        if($dailyCron ==1){
                                $sql = "SELECT $selectfields FROM new_matches_emails.MAILER_DAILY where ";
                        }else{
        			$sql = "SELECT $selectfields FROM new_matches_emails.MAILER where ";
                        }
			if(!$whereSentInY)
			{
				$sql .=" COALESCE(SENT, '') = ''";
			
			}
			else
				$sql .=" SENT = 'Y' AND DATE = :DATE";
			$sql .=" AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
			if($limit)
				$sql.= " limit 0,:LIMIT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
			$prep->bindValue(":SCRIPT",$script,PDO::PARAM_INT);
			 if($whereSentInY)
				 $prep->bindValue(":DATE",date('Y-m-d'),PDO::PARAM_STR);

			if($limit)
				  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
			$prep->execute();
			
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				if(!$fields)
				{
					$fieldsArray = explode(",",$defaultFields);
					foreach($fieldsArray as $k=>$v)
						$result[$row["SNO"]][$v]=$row[$v];
					unset($result[$row["SNO"]]["SNO"]);
				}
				else
					$result = $row;
			}
			return $result;			
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	 /* This funxtion is used update the sent flag(Y for sent and F for fail) for each mail receiver
        *@param sno : serial number of mail
        *@param flag : sent status of the mail
        */
	public function updateSentForUsers($sno,$flag,$dailyCron=0)
	{
		try
                {
			if(!$sno || !$flag)
				throw new jsException("no sno /flag passed in updateSentForUsers function in new_matches_emails_MAILER.class.php");
			
                        if($dailyCron ==1){
                                $sql="UPDATE new_matches_emails.MAILER_DAILY SET SENT=:FLAG,DATE=:DATE WHERE SNO=:SNO";
                        }else{
                                $sql="UPDATE new_matches_emails.MAILER SET SENT=:FLAG,DATE=:DATE WHERE SNO=:SNO";
                        }
			$res = $this->db->prepare($sql);
                        $res->bindValue(":SNO", $sno, PDO::PARAM_INT);
			$res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
			$res->bindValue(":DATE",date('Y-m-d'),PDO::PARAM_STR);
			$res->execute();
		}
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	
	public function getMailCountForRange()
        {
                try{
                        $sql = "SELECT count(1) as cnt,SENT FROM new_matches_emails.MAILER GROUP BY SENT";
                        $res=$this->db->prepare($sql);
                        $res->execute();
                        $total = 0;
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                if($row['SENT']=='Y')
                                        $output['SENT'] = $row['cnt'];
                                if($row['SENT']=='B')
                                        $output['BOUNCED'] = $row['cnt'];
                                if($row['SENT']=='I')
                                        $output['INCOMPLETE'] = $row['cnt'];
                                if($row['SENT']=='U')
                                        $output['UNSUBSCRIBE'] = $row['cnt'];
                                $total = $total+$row['cnt'];
                        }
                        $output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;
        }
        
        public function truncateTable($dailyCron)
        {
                try{
                        if($dailyCron == 1){
                                $sql = "TRUNCATE TABLE new_matches_emails.MAILER_DAILY";
                        }else{
                                $sql = "TRUNCATE TABLE new_matches_emails.MAILER";
                        }
                        $res=$this->db->prepare($sql);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                    SendMail::send_email("lavesh.rawat@gmail.com","FMA truncate Failed","truncate MAILER failed","lavesh.rawat@gmail.com");
                   throw new jsException($e);
                }
        }
        
        public function insertLogRecords($receiverId, $profileIds, $LogicLevel,$linkRequired,$relaxCriteria,$dailyCron=0){
                if($dailyCron == 1){
                        $sql="INSERT IGNORE INTO new_matches_emails.MAILER_DAILY (RECEIVER";
                }else{
                        $sql="INSERT IGNORE INTO new_matches_emails.MAILER (RECEIVER";
                }
                $n=count($profileIds);
                $userValues = '';
                for($i=1;$i<=$n;$i++)
                {
                  $sql.=",USER$i";
                  $userValues .= ",:USER_ID".$i;
                }
                $sql.=",LOGIC_USED,LINK_REQUIRED,RELAX_CRITERIA) VALUES (:RECEIVER_ID".$userValues.",:LOGIC_USED,:LINK_REQUIRED,:RELAX_CRITERIA)";
                        
                $res = $this->db->prepare($sql);
                $res->bindValue(":RECEIVER_ID", $receiverId, PDO::PARAM_INT);
                $res->bindValue(":LINK_REQUIRED", $linkRequired, PDO::PARAM_STR);
                $res->bindValue(":LOGIC_USED",$LogicLevel,PDO::PARAM_STR);
                $res->bindValue(":RELAX_CRITERIA",$relaxCriteria,PDO::PARAM_STR);

                $userCounter = 1;
                foreach($profileIds as $userId){
                  $res->bindValue(":USER_ID".$userCounter,$userId,PDO::PARAM_INT);
                  $userCounter++;
                }
                $res->execute();
        }
}

?>
