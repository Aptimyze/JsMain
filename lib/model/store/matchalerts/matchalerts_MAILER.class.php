<?php
/* This class provided functions for matchalerts.MAILER table
 * @author : Reshu Rajput
 * @created : May 19, 2014
*/
  
class matchalerts_MAILER extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
		parent::__construct($dbname);
	}

	/**
        * Empty The table
        */
        public function truncateTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE matchalerts.MAILER";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
                        //add mail/sms
                        throw new jsException($e);
                }
        }

	
	/* This function is used to get all the profile which need to recieve matchalert ie having SENT<>Y  and atleat one profile in user.
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
			$defaultFields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,LOGIC_USED,FREQUENCY";

			$selectfields = $fields?$fields:$defaultFields;
			$sql = "SELECT $selectfields FROM matchalerts.MAILER where COALESCE(SENT, '') = '' AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
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
						$result[$row["SNO"]][$v]=$row[$v];
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

	 /* This function is used update the sent flag(Y for sent and F for fail and I for invalid users for receiver) for each mail receiver
        *@param sno : serial number of mail
        *@param flag : sent status of the mail
        */
	public function updateSentForUsers($sno,$flag)
	{
		try
                {
			if(!$sno || !$flag)
				throw new jsException("no sno /flag passed in updateSentForUsers function in matchalerts_MAILER.class.php");
			
			$sql="UPDATE matchalerts.MAILER SET SENT=:FLAG,DATE=:DATE WHERE SNO=:SNO";
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
	/* This function is used to get the number of email sent from various email domains(Gmail, Yahoo etc).
	* @return array of count for various email domains
	*/
	public function checkSentEmail()
	{
		try 
		{
		/**
		* QUERY to get sent email domain count
		*/

		$sql="SELECT DATE,COUNT(*) AS COUNT,IF(B.EMAIL LIKE '%@gmail.com', 'g', IF(B.EMAIL LIKE '%@yahoo%', 'y', IF(B.EMAIL LIKE '%@rediffmail.com', 'r', IF(B.EMAIL LIKE '%@hotmail.com', 'h', 'other')))) AS MAILS FROM `matchalerts`.`MAILER` A LEFT JOIN `newjs`.`JPROFILE` B ON A.RECEIVER=B.PROFILEID WHERE (A.DATE=:DAY AND A.SENT='Y') GROUP BY MAILS";
		$prepr=$this->db->prepare($sql);
		$prepr->bindValue(":DAY",date('Y-m-d',strtotime("-1 days")),PDO::PARAM_STR);
		$prepr->execute();
			while($result = $prepr->fetch(PDO::FETCH_ASSOC))
			{
				$count[$result["MAILS"]]=$result["COUNT"];
				$count["day"]=$result["DATE"];
			}
		return $count;			 
			
		}
		catch(PDOException $e)
		{
		/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function getMailCountForRange()
    	{
                try{
                        $sql = "SELECT count(1) as cnt,SENT FROM matchalerts.MAILER GROUP BY SENT";
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
        public function insertLogRecords($receiverId, $profileIds, $LogicLevel,$frequency){
          $sql="INSERT INTO matchalerts.MAILER (RECEIVER";
          $n=count($profileIds);
          $userValues = '';
          for($i=1;$i<=$n;$i++)
          {
            $sql.=",USER$i";
            $userValues .= ",:USER_ID".$i;
          }
          $sql.=",LOGIC_USED,FREQUENCY,DATE) VALUES (:RECEIVER_ID".$userValues.",:LOGIC_USED,:FREQUENCY,:DATE)";
          
          $res = $this->db->prepare($sql);
          $res->bindValue(":RECEIVER_ID", $receiverId, PDO::PARAM_INT);
          $res->bindValue(":FREQUENCY", $frequency, PDO::PARAM_INT);
          $res->bindValue(":LOGIC_USED",$LogicLevel,PDO::PARAM_INT);
          $res->bindValue(":DATE",date('Y-m-d'),PDO::PARAM_INT);
          
          $userCounter = 1;
          foreach($profileIds as $userId){
            $res->bindValue(":USER_ID".$userCounter,$userId,PDO::PARAM_INT);
            $userCounter++;
          }
          $res->execute();
        }

	public function countNotSentMails()
        {
                try{
                        $sql = "SELECT count(*) as CNT FROM matchalerts.MAILER WHERE SENT=''";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row['CNT'];
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;
        }

}
?>
