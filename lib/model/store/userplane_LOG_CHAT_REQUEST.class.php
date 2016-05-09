<?php
class userplane_LOG_CHAT_REQUEST extends TABLE{
        
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

	public function getIfChatRequestSent($senders, $receiver, $giveResultsInKey='0')
	{
		try
		{
			$sql = "SELECT SEN FROM userplane.LOG_CHAT_REQUEST WHERE REC IN ($receiver) AND SEN IN ($senders)";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($giveResultsInKey=='1')
					$resultArr[$row['SEN']] = 1;
				else
					$resultArr[] = $row['SEN'];
			}
			return $resultArr;
		}
		catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        //throw new jsException($e);
                        jsException::nonCriticalError("lib/model/store/userplane_LOG_CHAT_REQUEST.class.php(1)-->.$sql".$e);
                        return 0;
                }
		
	}
		
		
}
?>
