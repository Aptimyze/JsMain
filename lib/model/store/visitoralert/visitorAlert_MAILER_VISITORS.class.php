<?php
/* This class provided functions for visitoralert.MAILER_VISITOR table
 * @author : Akash Kumar
 * @created : Jun 11, 2014
*/
  
class visitorAlert_MAILER extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{	$dbname=$dbname?$dbname:"shard1_master";
		//$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
			parent::__construct($dbname);
	}
	
	/* This function is used to get all the profile which need to recieve matchalert ie having SENT<>Y  and atleat one profile in user.
	* @param totalScript : number of script which can be executed
	* @param script : current script number
	* @param limit : limit if required
	* @return result : details of mailer to be sent 
	*/
	public function getMailerProfiles($totalScript="1",$script="0",$limit="")
	{
		try 
		{
			
			$sql = "SELECT * FROM visitoralert.MAILER_VISITORS where SENT='N' AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
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
				$result[$row["SNO"]] = $row;
				unset($result[$row["SNO"]]["SNO"]);
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
	public function updateSentForUsers($sno,$flag)
	{ 
		try
       {
			if(!$sno || !$flag)
				throw new jsException("no sno /flag passed in updateSentForUsers function in matchalerts_MAILER.class.php");
			
			$sql="UPDATE visitoralert.MAILER_VISITORS SET SENT=:FLAG WHERE SNO=:SNO";
			$res = $this->db->prepare($sql);
            $res->bindValue(":SNO", $sno, PDO::PARAM_INT);
			$res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
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
                        $sql = "SELECT count(1) as cnt,SENT FROM visitoralert.MAILER_VISITORS GROUP BY SENT";
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
}

?>
