<?php
class NEWJS_MESSAGES_ARCHIVE extends TABLE{
       

       
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
						throw new jsException(""," profileID array is not specified in function insertMessageLogHousekeeping of NEWJS_DELETED_MESSAGES_ARCHIVE.class.php");
					}
					else
					{
						$idStr=implode(",",$arrProfileId);
						$sql="INSERT INTO newjs.MESSAGES_ARCHIVE SELECT * FROM newjs.DELETED_MESSAGES WHERE ID IN (".$idStr.")";
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
		
}
