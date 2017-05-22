<?php
class NEWJS_MESSAGE_LOG_ARCHIVE extends TABLE{
       

       
        public function __construct($dbname="")
        {
			if(strpos($dbname,'master')!==false && JsConstants::$communicationRep)
				$dbname=$dbname."Rep";
			parent::__construct($dbname);
        }
        
        public function insertMessageLogHousekeeping($arrProfileId)
		{
			try 
			{
					if(!is_array($arrProfileId))
					{
						throw new jsException(""," profileID array is not specified in function insertMessageLogHousekeeping of NEWJS_DELETED_MESSAGE_LOG_ARCHIVE.class.php");
					}
					else
					{
						$idStr=implode(",",$arrProfileId);
						$sql="INSERT INTO newjs.MESSAGE_LOG_ARCHIVE SELECT * FROM newjs.DELETED_MESSAGE_LOG WHERE ID IN (".$idStr.")";
						$prep=$this->db->prepare($sql);
						$prep->execute();
						return true;
					}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		//Three function for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}

		
}
