<?php 
	/**
	* visitor alert record for tracking total visitor alerts sent.
	*/
	class visitorAlert_RECORD extends TABLE
	{
		
		/* This will connect to matchalert slave by default*/
		public function __construct($dbname="")
		{	
			$dbname=$dbname?$dbname:"shard1_master";
			//$dbname = $dbname?$dbname:"matchalerts_slave_localhost";
			parent::__construct($dbname);
		}

		public function updateVisitorAlertRecord($total,$count)
		{
			try {
				$sql="REPLACE INTO visitoralert.VISITOR_ALERT_RECORD (PROFILE_SENT,ALERT_SENT,SENT_DATE) VALUES (:TOTAL,:COUNT,now())";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":TOTAL",$total,PDO::PARAM_INT);
				$prep->bindValue(":COUNT",$count,PDO::PARAM_INT);
				$prep->execute();
			} catch (PDOException $e) {
				throw new jsException($e);
			}

		}
                
                public function getVisitorAlertRecord($date)
		{
			try {
				$sql="SELECT * FROM visitoralert.VISITOR_ALERT_RECORD WHERE SENT_DATE = :DATE";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":DATE",$total,PDO::PARAM_STR);
				$prep->execute();
                                if($prep->fetch(PDO::FETCH_ASSOC))
                                    return true;
			} catch (PDOException $e) {
				throw new jsException($e);
			}
                        return false;

		}
	}

	?>