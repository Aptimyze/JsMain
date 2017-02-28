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
		parent::__construct($dbname);
	}
	
	/**
	 * Truncate Mailer Visitors Data before populating
	 */
	public function truncateMailerVisitorsData()
	{
		try
		{
			$sql="TRUNCATE TABLE visitoralert.MAILER_VISITORS";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	

	/* This function is used to get all the profile which need to recieve matchalert ie having SENT<>Y  and atleat one profile in user.
	* 
	* @param script : current script number
	* @param limit : limit if required
	* @return result : details of mailer to be sent 
	*/
	public function getMailerProfiles($totalScript="1",$script="0",$limit="",$sent='N')
	{
		try 
		{
			
			$sql = "SELECT * FROM visitoralert.MAILER_VISITORS where SENT=:SENT AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
			if($limit)
				$sql.= " limit 0,:LIMIT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
			$prep->bindValue(":SENT",$sent,PDO::PARAM_STR);
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

    /**
     * Inserts data into MAILER_VISITORS
     * @param  array $receiverData an array consisting profiles viewed.
     */
    public function insertReceiverData($receiverData)
    {

    	try
    	{
    
    		if(is_array($receiverData))
    		{
    			$sql="INSERT IGNORE INTO visitoralert.MAILER_VISITORS (PROFILEID,SENT) VALUES ";

    			foreach($receiverData as $key=>$value)
    			{
    				$sql .="(:PROFILEID".$key.",'U'),";
    			}
    			$sql = rtrim($sql,",");
    			$res = $this->db->prepare($sql);
    			foreach($receiverData as $key => $value)
    			{
    				$res->bindValue(":PROFILEID".$key, $value["PROFILEID"], PDO::PARAM_INT);
    			}
    			$res->execute();
    		}

    	}
    	catch(PDOException $e)
    	{
                        //throw new jsException($e);
    		jsException::nonCriticalError("VisitorAlert_MAILER_VISITORS.class.php".$e);
    		return '';
    	}
    }


    public function updateReceiverData($receiverData,$countOfProfiles)
    {
    	try
        {
        	if ( is_array($receiverData) )
        	{
        		foreach ($receiverData as $key => $value) {
	            $sql = "";
	            $sql .= "UPDATE visitoralert.MAILER_VISITORS SET ";
	         
	            $where = " WHERE PROFILEID = :PROFILEID";
	            $setCondition = "";
	            for ($i=1; $i <= sizeof($value); $i++) { 
	            	$setCondition .= "VISITOR".$i."= :VISITOR".$i.",";
	            }
                    $setCondition = rtrim($setCondition,",");
                    $setCondition.=',TOTAL=:COUNT';
                    $setCondition.=",SENT='N'";
	            $sql .= $setCondition;
	            $sql .= $where;

	            $pdoStatement = $this->db->prepare($sql);
	            $pdoStatement->bindValue(":PROFILEID",$key,PDO::PARAM_INT);
                    $pdoStatement->bindValue(":COUNT",$countOfProfiles,PDO::PARAM_INT);
	            
	            for ($i=1; $i <= sizeof($value); $i++) { 
	            	$pdoStatement->bindValue(":VISITOR".$i,$value[$i - 1],PDO::PARAM_INT);	
	            }

	            $pdoStatement->execute();
        	}
        	}
        }

        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    	
    }

    public function countTotalSent()
    {
    	try 
    	{
    		$sql="SELECT sum(TOTAL),count(PROFILEID) FROM visitoralert.MAILER_VISITORS where SENT='Y'";

	    	$pdoStatement = $this->db->prepare($sql);
	    	$pdoStatement->execute();

	    	while($row = $pdoStatement->fetch(PDO::FETCH_ASSOC))
			{
				$result["TOTAL"] = $row["sum(TOTAL)"];
				$result["COUNT"] = $row["count(PROFILEID)"];
			}
			return $result;
    	} 
    	catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
	public function updateReceiverDataSetX($profileID)
    {
    	try
        {
	            $sql = "";
	            $sql .= "UPDATE visitoralert.MAILER_VISITORS SET ";
                    $sql .= 'TOTAL=:COUNT';
                    $sql .= ",SENT='X'";
	            $sql .= " WHERE PROFILEID = :PROFILEID";

	            $pdoStatement = $this->db->prepare($sql);
	            $pdoStatement->bindValue(":PROFILEID",$profileID,PDO::PARAM_INT);
                    $pdoStatement->bindValue(":COUNT",0,PDO::PARAM_INT);
	            

	            $pdoStatement->execute();
        }

        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    	
    }
}

?>
