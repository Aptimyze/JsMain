<?php
class NEWJS_DELETED_MESSAGE_LOG extends TABLE{
       

       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function insert($pid,$whereStrLabel='SENDER')
        {
			if(!$pid)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN insert() of NEWJS_DELETED_MESSAGE_LOG.class.php");
			try 
			{ 
					$sql="INSERT IGNORE INTO newjs.DELETED_MESSAGE_LOG SELECT * FROM newjs.MESSAGE_LOG WHERE ".$whereStrLabel."=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					$count = $prep->rowCount();
					return $count;
			
			}
			catch(PDOException $e)
			{
				return false;
				throw new jsException($e);
			}
		}
		
		public function selectActiveDeletedData($pid,$listOfActiveProfile,$whereStrLabel1='RECEIVER',$whereStrLabel2='SENDER')
        {
			if(!$pid || !$listOfActiveProfile)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of NEWJS_DELETED_MESSAGE_LOG.class.php");
			try 
			{ 
					$sql="select ID FROM newjs.DELETED_MESSAGE_LOG WHERE (".$whereStrLabel1."=:PROFILEID OR ". $whereStrLabel2."=:PROFILEID) AND (".$whereStrLabel1." IN (".$listOfActiveProfile.") OR ".$whereStrLabel2." IN (".$listOfActiveProfile."))";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row['ID'];
					}
					return $output;
			
			}
			catch(PDOException $e)
			{
				return false;
				throw new jsException($e);
			}
		}
		
		public function deleteMessages($profileid,$listOfActiveProfile,$whereStrLabel1='RECEIVER',$whereStrLabel2='SENDER')
		{
			try 
			{
				if($listOfActiveProfile && $profileid)
				{ 
					
					$sql="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE (".$whereStrLabel1."=:PROFILEID OR ".$whereStrLabel2."=:PROFILEID) AND (".$whereStrLabel1." IN (".$listOfActiveProfile.") OR ".$whereStrLabel2." IN (".$listOfActiveProfile."))";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->execute();
					return true;
				}
				else
				{
					throw new jsException("","profile id  is not specified in function deleteMessages of newjs_DELETED_MESSAGES.class.php");
				}
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
		public function getAllMessageIdLog($profileid,$senderRecevierStr='SENDER')
		{
			try 
			{
				if(!$profileid)
				{
					throw new jsException("","profile id is not specified in function getAllMessageIdLog of newjs_DELETED_MESSAGES.class.php");
				}
				else
				{
					$sql="select ID FROM newjs.DELETED_MESSAGE WHERE ".$senderRecevierStr."=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row['ID'];
					}
				
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function deleteMessageLogById($msgId)
	{
		try 
		{
				if(!$msgId)
				{
					throw new jsException(""," id is not specified in function deleteMessageLogById of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="DELETE FROM newjs.MESSAGE_LOG WHERE ID=:MSG_ID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":MSG_ID",$msgId,PDO::PARAM_INT);
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
	public function getSenderIdMessageLog($profileid,$senderRecevierStr='SENDER')
	{
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profile id is not specified in function getAllMessageIdLog of newjs_DELETED_MESSAGES.class.php");
				}
				else
				{
					if($senderRecevierStr=="SENDER")
						$getStr="RECEIVER";
					elseif($senderRecevierStr=="RECEIVER")
						$getStr="SENDER";
					$sql="select ID,RECEIVER FROM newjs.DELETED_MESSAGE WHERE ".$senderRecevierStr."=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function insertMessageLogHousekeeping($arrProfileId)
	{
		try 
		{
				if(!is_array($arrProfileId))
				{
					throw new jsException(""," profileID array is not specified in function insertMessageLogHousekeeping of newjs_DELETED_MESSAGE_LOG.class.php");
				}
				else
				{
					$idStr=implode(",",$arrProfileId);
					$sql="REPLACE INTO newjs.DELETED_MESSAGE_LOG SELECT * FROM newjs.MESSAGE_LOG WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function deleteMultipleLogForSingleProfile($profileArray)
	{
		try 
			{
				if(is_arary($profileArray))
				{
					throw new jsException("","profile id is not specified in function deleteMultipleLogForSingleProfile of DELETED_MESSAGE_LOG.class.php");
				}
				else
				{
					$idStr=implode(",",$profileArray);
					$sql="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
}
?>
