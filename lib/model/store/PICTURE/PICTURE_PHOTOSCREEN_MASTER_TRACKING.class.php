<?php
class PICTURE_PHOTOSCREEN_MASTER_TRACKING extends TABLE 
{
	
	/**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    private $m_szNameClass = __CLASS__; 
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    public function insertRecord($arrRecordData)
    {
		if(!$arrRecordData["PROFILEID"])
		{
			throw new jsException('','Null Profile-id in insertRecord of $this->m_szNameClass');
		}
		if(!is_array($arrRecordData))
		{
			throw new jsException("","Array is not passed in insertRecord OF $this->m_szNameClass");
		}
		
		try
		{
			$szINs = implode(',',array_fill(0,count($arrRecordData),'?'));
			$arrFields = array();
			//If ACCEPT_REJECT_Q_COMPLETION_TIME is not specified or specified as ''
			// then assign NULL value explicitly
			if(!$arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME']
			|| !strlen($arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME']))
			{
				$arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME'] = NULL;	
			}
			
			//If PROCESS_Q_COMPLETION_TIME is not specified or specified as ''
			// then assign NULL value explicitly
			if(!$arrRecordData['PROCESS_Q_COMPLETION_TIME'] 
			|| !strlen($arrRecordData['PROCESS_Q_COMPLETION_TIME']))
			{
				$arrRecordData['PROCESS_Q_COMPLETION_TIME'] = NULL;
			}
			
			foreach($arrRecordData as $key=>$val)
			{
				$arrFields[] = strtoupper($key);
			}
			$szFields = implode(",",$arrFields);
			
			$sql = "INSERT INTO PICTURE.PHOTOSCREEN_MASTER_TRACKING ($szFields) VALUES ($szINs)";
			$pdoStatement = $this->db->prepare($sql);
			//Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
				$pdoStatement->bindValue(($count), $value);
			}
			$pdoStatement->execute();
			return $pdoStatement->rowCount();
		}
		catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in insertRecord method of $this->m_szNameClass");
		}
	}
	
	//Update Only PictureCompletionTime And PhotoUpload
	public function updateRecord($arrRecordData)
	{
		if(!$arrRecordData["PROFILEID"] || !$arrRecordData['PROFILE_TYPE'])
		{
			throw new jsException('','Null Profile-id or null profile type passed in updateRecord of $this->m_szNameClass');
		}
		if(!is_array($arrRecordData))
		{
			throw new jsException("","Array is not passed in updateRecord OF $this->m_szNameClass");
		}

		if(!$arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME']
		|| !strlen($arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME']))
		{
			$arrRecordData['ACCEPT_REJECT_Q_COMPLETION_TIME'] = NULL;	
		};
		
		if(!$arrRecordData['PROCESS_Q_COMPLETION_TIME'] 
		|| !strlen($arrRecordData['PROCESS_Q_COMPLETION_TIME']))
		{
			$arrRecordData['PROCESS_Q_COMPLETION_TIME'] = NULL;
		}
		
		try
		{
			$cProfileType = $arrRecordData['PROFILE_TYPE'];
			unset($arrRecordData['PROFILE_TYPE']);
			$arrFields = array();			
			foreach($arrRecordData as $key=>$val)
			{
				$columnName = strtoupper($key);
				$arrFields[] = "$columnName = ?";
			}
			$szFields = implode(",",$arrFields);

			$sql = "UPDATE PICTURE.PHOTOSCREEN_MASTER_TRACKING SET $szFields WHERE PROFILEID = ? AND PROFILE_TYPE= ? AND  (ACCEPT_REJECT_Q_COMPLETION_TIME = '0000-00-00 00:00:00' OR ACCEPT_REJECT_Q_COMPLETION_TIME IS NULL)  AND (PROCESS_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR PROCESS_Q_COMPLETION_TIME IS NULL) ";
			$pdoStatement = $this->db->prepare($sql);
			//Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
				$pdoStatement->bindValue(($count), $value);
			}
			$pdoStatement->bindValue((++$count),$arrRecordData['PROFILEID'],PDO::PARAM_INT);
			$pdoStatement->bindValue((++$count),$cProfileType,PDO::PARAM_STR);
			$pdoStatement->execute();
			return $pdoStatement->rowCount();
		}
		catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in insertRecord method of $this->m_szNameClass");
		}
	}
		
	public function updateAcceptRejectQueueCompletionTime($iProfileId,$cProfileType,$enumQueueName)
	{
		if(!$iProfileId || !strlen($cProfileType))
		{
			throw new jsException('','Null Profile-id or null profile type is passed in updateAcceptRejectQueueCompletionTime of $this->m_szNameClass');
		}
		
		try{
			$now = date("Y-m-d H:i:s");
			$sql = "UPDATE PICTURE.PHOTOSCREEN_MASTER_TRACKING SET ACCEPT_REJECT_Q_COMPLETION_TIME=:NOW,  SCREENING_COMPLETION_QUEUE_NAME=:QUEUENAME WHERE PROFILEID=:PID AND PROFILE_TYPE=:PTYPE AND (ACCEPT_REJECT_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR ACCEPT_REJECT_Q_COMPLETION_TIME IS NULL) AND (PROCESS_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR PROCESS_Q_COMPLETION_TIME IS NULL)	AND COALESCE(SCREENING_COMPLETION_QUEUE_NAME,'') = ''";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileId,PDO::PARAM_INT);
			$pdoStatement->bindValue(":NOW", $now,PDO::PARAM_STR);
			$pdoStatement->bindValue(":QUEUENAME",$enumQueueName,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PTYPE",$cProfileType,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return $pdoStatement->rowCount();
		}catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in updateRecords method of $this->m_szNameClass");
		}
	}
	
	public function updateProcessQueueCompletionTime($iProfileId,$cProfileType,$enumQueueName)
	{
		if(!$iProfileId || !strlen($cProfileType))
		{
			throw new jsException('','Null Profile-id or null profile type is passed in updateProcessQueueCompletionTime of $this->m_szNameClass');
		}
		
		try{			
			$now = date("Y-m-d H:i:s");
			$sql = "UPDATE PICTURE.PHOTOSCREEN_MASTER_TRACKING SET PROCESS_Q_COMPLETION_TIME=:NOW ,SCREENING_COMPLETION_QUEUE_NAME=:QUEUENAME WHERE PROFILEID=:PID AND PROFILE_TYPE=:PTYPE AND ACCEPT_REJECT_Q_COMPLETION_TIME IS NOT NULL AND (PROCESS_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR PROCESS_Q_COMPLETION_TIME IS NULL)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileId,PDO::PARAM_INT);
			$pdoStatement->bindValue(":NOW", $now,PDO::PARAM_STR);
			$pdoStatement->bindValue(":QUEUENAME",$enumQueueName,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PTYPE",$cProfileType,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return $pdoStatement->rowCount();
		}catch(Exception $e)
		{
			throw new jsException($e,"Something went wrong in updateRecords method of $this->m_szNameClass");
		}
	}
	
	public function isRecordExist($iProfileID,$cProfileType,&$arrRefData="",$bConsiderAcceptRejectTime=true)
	{
		if(!$iProfileID || !strlen($cProfileType))
		{
			throw new jsException('','Null Profile-id or null profile type is passed in isRecordExist of $this->m_szNameClass');
		}
		try{
			
			$sql = "SELECT * FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILEID=:PID  AND PROFILE_TYPE=:PTYPE AND COALESCE(SCREENING_COMPLETION_QUEUE_NAME,'' ) = '' AND (PROCESS_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR PROCESS_Q_COMPLETION_TIME IS NULL)";
			if($bConsiderAcceptRejectTime)
			{
				$sql.=" AND (ACCEPT_REJECT_Q_COMPLETION_TIME='0000-00-00 00:00:00' OR ACCEPT_REJECT_Q_COMPLETION_TIME IS NULL) ";
			}
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":PTYPE",$cProfileType,PDO::PARAM_STR);
			$pdoStatement->execute();
			$bStatus = $pdoStatement->rowCount();
			if($bStatus && is_array($arrRefData))
			{
				$data = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
				$arrRefData = $data[0];
			}
			else
			{
				$arrRefData = null;
			}
			return $bStatus;
		}catch(Exception $e)
		{	
			throw new jsException($e,"Something went wrong in isRecordExist method of $this->m_szNameClass");	
		}
	}
	
	public function getAvgtimeQueue1New($mon,$year)
	{	
		    try
			    {	
					$sql = "SELECT DAY (PREPROCESS_COMPLETION_TIME) AS DAYs,HOUR (PREPROCESS_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' AND MONTH(PREPROCESS_COMPLETION_TIME)=:MON  AND YEAR(PREPROCESS_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
					$prep = $this->db->prepare($sql);
					$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
					{
						$fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];	
					}
				}
			catch(Exception $e)
				{
					throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
				}
			return $fin1;
	}
	public function getAvgtimeQueue1Edit($mon,$year)
	{
				try
				{
					 $sql = "SELECT DAY (PREPROCESS_COMPLETION_TIME) AS DAYs,HOUR (PREPROCESS_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' AND MONTH(PREPROCESS_COMPLETION_TIME)=:MON AND YEAR(PREPROCESS_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
					 $prep = $this->db->prepare($sql);
					 $prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					 $prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					 $prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
					{
						$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
					}
				}
			   
			catch(Exception $e)
				{
					throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
				}
			return $fin2;
	}	
	
	public function getAvgtimeQueue2New($mon,$year)
		{ 
			try
				{

					$sql = "SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,ACCEPT_REJECT_Q_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' AND MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=:MON AND YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
					$prep = $this->db->prepare($sql);
					$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
				$prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
					{
						 $fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];
					}
					
				}
			catch(Exception $e)
				{
					throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
				}
			return $fin1;
		}

	public function getAvgtimeQueue2Edit($mon,$year)
		{
			try
				{
					$sql = "SELECT DAY (ACCEPT_REJECT_Q_COMPLETION_TIME) AS DAYs,HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,ACCEPT_REJECT_Q_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' AND MONTH(ACCEPT_REJECT_Q_COMPLETION_TIME)=:MON AND YEAR(ACCEPT_REJECT_Q_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (ACCEPT_REJECT_Q_COMPLETION_TIME), HOUR (ACCEPT_REJECT_Q_COMPLETION_TIME) ";
					$prep = $this->db->prepare($sql);
					$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
					{
						$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
					}
			  }
			catch(Exception $e)
				{
					throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
				}
			return $fin2;
		}
			
	 public function getAvgtimeQueue3New($mon,$year)
		{
			try
				{
					$sql="SELECT DAY (PREPROCESS_COMPLETION_TIME) AS DAYs,HOUR (PREPROCESS_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' AND MONTH(PREPROCESS_COMPLETION_TIME)=:MON AND YEAR(PREPROCESS_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
					$prep = $this->db->prepare($sql);
					$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
						{
							$fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];	
						}
					$sql = "SELECT DAY (PROCESS_Q_COMPLETION_TIME) AS DAYs,HOUR (PROCESS_Q_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='N' AND MONTH(PROCESS_Q_COMPLETION_TIME)=:MON AND YEAR(PROCESS_Q_COMPLETION_TIME)=:YEAR AND PROCESS_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
					$prep = $this->db->prepare($sql);
					$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
					$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
					$prep->execute();
					while($result=$prep->fetch(PDO::FETCH_ASSOC))
						{
							 $fin1[$result['HOURs']][$result['DAYs']]=$result["avg"];
						}
				}
				catch(Exception $e)
					{
						throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
					}
				
			return $fin1;
		}
		
		public function getAvgtimeQueue3Edit($mon,$year)
			{
				try
					{
						$sql="SELECT DAY (PREPROCESS_COMPLETION_TIME) AS DAYs,HOUR (PREPROCESS_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(ACCEPT_REJECT_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' AND MONTH(PREPROCESS_COMPLETION_TIME)=:MON AND YEAR(PREPROCESS_COMPLETION_TIME)=:YEAR AND ACCEPT_REJECT_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
						$prep = $this->db->prepare($sql);
						$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
						$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
						$prep->execute();
						while($result=$prep->fetch(PDO::FETCH_ASSOC))
							{
								$fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];	
							}
						$sql="SELECT DAY (PREPROCESS_COMPLETION_TIME) AS DAYs,HOUR (PREPROCESS_COMPLETION_TIME) AS HOURs,SUM(time_to_sec(timediff(PROCESS_Q_COMPLETION_TIME,PREPROCESS_COMPLETION_TIME) ) /60 )/COUNT(*) AS avg FROM PICTURE.PHOTOSCREEN_MASTER_TRACKING WHERE PROFILE_TYPE='E' AND MONTH(PREPROCESS_COMPLETION_TIME)=:MON AND YEAR(PREPROCESS_COMPLETION_TIME)=:YEAR AND PROCESS_Q_COMPLETION_TIME<>'NULL' GROUP BY DAY (PREPROCESS_COMPLETION_TIME), HOUR (PREPROCESS_COMPLETION_TIME) ";
						$prep = $this->db->prepare($sql);
						$prep->bindValue(":MON",$mon,PDO::PARAM_INT);
						$prep->bindValue(":YEAR",$year,PDO::PARAM_INT);
						$prep->execute();
						while($result=$prep->fetch(PDO::FETCH_ASSOC))
							{
								 $fin2[$result['HOURs']][$result['DAYs']]=$result["avg"];
							}
					}
				catch(Exception $e)
					{
						throw new jsException($e,"Something went wrong in PICTURE_PHOTOSCREEN_MASTER_TRACKING");
					}
					
				return $fin2;
			
		}
}    
