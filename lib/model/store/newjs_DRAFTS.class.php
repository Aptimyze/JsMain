<?php
class NEWJS_DRAFTS extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function getDrafts($pid,$decline)
	{
		try
		{
			$res=null;
			if($pid)
			{
				$sql="SELECT MESSAGE,DRAFTID,DRAFTNAME FROM newjs.DRAFTS WHERE PROFILEID=:PROFILEID and DECLINE_MES=:DECLINE";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
				$prep->bindValue(":DECLINE",$decline,PDO::PARAM_STR);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]=$result;
				}
				
			}
			else
				throw new jsException("No pid passed in drafts table");
				
			return $res;
			
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
public function getAllDrafts ($profileid)
    {
        try {
            $sql = "select MESSAGE,DRAFTID,DRAFTNAME,DECLINE_MES from newjs.DRAFTS where PROFILEID=:PROFILEID ORDER BY CREATE_TIME DESC";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $drafts[] = $result;
            }
            return $drafts;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
	public function insertDrafts($profileid,$draftname,$message,$decline)
    {
    	try 
    	{
    		$sql = "insert ignore into DRAFTS (DRAFTNAME,MESSAGE,PROFILEID,CREATE_TIME,DECLINE_MES) VALUES (:DRAFTNAME,:MESSAGE,:PROFILEID,now(),:DECLINE_MES)";
    		$prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":DRAFTNAME",$draftname,PDO::PARAM_STR);
            $prep->bindValue(":MESSAGE",$message,PDO::PARAM_STR);
            $prep->bindValue("DECLINE_MES",$decline,PDO::PARAM_STR);
            $prep->execute();
    	}
    	catch (Exception $e) {
            throw new jsException($e);
        }    	
    }
    public function updateDrafts($id,$draftname,$message)
    {
    	try 
    	{
    		$sql = "update DRAFTS set DRAFTNAME=:DRAFTNAME,MESSAGE=:MESSAGE,CREATE_TIME=now() where DRAFTID=:DRAFTID";
    		$prep = $this->db->prepare($sql);
            
            $prep->bindValue(":DRAFTNAME",$draftname,PDO::PARAM_STR);
            $prep->bindValue(":MESSAGE",$message,PDO::PARAM_STR);
            $prep->bindValue("DRAFTID",$id,PDO::PARAM_INT);
            $prep->execute();
    	}
    	catch (Exception $e) {
            throw new jsException($e);
        }    	
    }	
}
?>
