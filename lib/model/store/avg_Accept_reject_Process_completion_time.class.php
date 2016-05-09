<?php
class avg_Accept_reject_Process_completion_time extends TABLE
{
	function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function getAvgtimeQueue1New($mon,$year)
	{	
		    try{
				
				$sql = "SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' && MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=$mon && YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=$year && ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
				$prep = $this->db->prepare($sql);
				$prep->execute();
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];	
				}
				}
			catch(Exception $e)
				{
				
				}
			return $fin1;
	}
	public function getAvgtimeQueue1Edit($mon,$year)
	{
				try
				{
				 $sql = "SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' && MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=$mon && YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=$year && ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
				 $prep = $this->db->prepare($sql);
				 $prep->execute();
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
				}
	}
			   
			catch(Exception $e)
			{
				
			}
			return $fin2;
	}	
	
	 public function getAvgtimeQueue2New($mon,$year)
		{ 
			try
			{
				$sql = "SELECT DAY (PROCESS_Q_COMPLETION_TIME) AS DAYs,HOUR (PROCESS_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' && MONTH(PROCESS_Q_COMPLETION_TIME)=$mon && YEAR(PROCESS_Q_COMPLETION_TIME)=$year && PREPROCESS_COMPLETION_TIME<>'NULL' GROUP BY DAY (PROCESS_Q_COMPLETION_TIME), HOUR (PROCESS_Q_COMPLETION_TIME) ";
				
				
				$prep = $this->db->prepare($sql);
				 $prep->execute();
				 	
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					 $fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];
				}
				
			}
			catch(Exception $e)
			{
		
			}
			return $fin1;
			
		  }
	 public function getAvgtimeQueue2Edit($mon,$year)
		{
			try{
				$sql = "SELECT DAY (PROCESS_Q_COMPLETION_TIME) AS DAYs,HOUR (PROCESS_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' && MONTH(PROCESS_Q_COMPLETION_TIME)=$mon && YEAR(PROCESS_Q_COMPLETION_TIME)=$year && PREPROCESS_COMPLETION_TIME<>'NULL' GROUP BY DAY (PROCESS_Q_COMPLETION_TIME), HOUR (PROCESS_Q_COMPLETION_TIME) ";
				$prep = $this->db->prepare($sql);
				 $prep->execute();
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
				}
		  }
			catch(Exception $e)
			{
				
			}
			return $fin2;
		}
			
			 public function getAvgtimeQueue3New($mon,$year)
		{
			try{
				$sql="SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' && MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=$mon && YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=$year && ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
				$prep = $this->db->prepare($sql);
				$prep->execute();
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];	
				}
				$sql = "SELECT DAY (PROCESS_Q_COMPLETION_TIME) AS DAYs,HOUR (PROCESS_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' && MONTH(PROCESS_Q_COMPLETION_TIME)=$mon && YEAR(PROCESS_Q_COMPLETION_TIME)=$year && PROCESS_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PROCESS_Q_COMPLETION_TIME), HOUR (PROCESS_Q_COMPLETION_TIME) ";
				
				
				$prep = $this->db->prepare($sql);
				 $prep->execute();
				 	
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					 $fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];
				}
			}
				catch(Exception $e)
				{
				
				}
				
			return $fin1;
		}
		
		 public function getAvgtimeQueue3Edit($mon,$year)
		{
			try{
				$sql="SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' && MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=$mon && YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=$year && ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
				$prep = $this->db->prepare($sql);
				$prep->execute();
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];	
				}
				$sql="SELECT DAY (PROCESS_Q_COMPLETION_TIME) AS DAYs,HOUR (PROCESS_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' && MONTH(PROCESS_Q_COMPLETION_TIME)=$mon && YEAR(PROCESS_Q_COMPLETION_TIME)=$year && PROCESS_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PROCESS_Q_COMPLETION_TIME), HOUR (PROCESS_Q_COMPLETION_TIME) ";
				$prep = $this->db->prepare($sql);
				 $prep->execute();
				 	
				while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					 $fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
				}
			}
				catch(Exception $e)
				{
				
				}
				
			return $fin2;
		
	}
}
	

