<?php
class NEWJS_JP_NTIMES extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="newjs_masterRep")
        {
			parent::__construct($dbname);
        }
        public function getProfileViews($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT NTIMES FROM newjs.JP_NTIMES WHERE PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
          $this->logFunctionCalling(__FUNCTION__);
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result["NTIMES"];
					}
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
                
        public function updateProfileViews($pid,$count)
        {
			try 
			{
				if($pid)
				{ 
					$sql="UPDATE newjs.JP_NTIMES SET NTIMES = NTIMES+:COUNT WHERE PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                                        $prep->bindValue(":COUNT",$count,PDO::PARAM_INT);
					$prep->execute();
					if($prep->rowCount() <= 0)
					{   
                                            $sql2="INSERT IGNORE INTO newjs.JP_NTIMES(PROFILEID,NTIMES) VALUES(:PROFILEID,:COUNT)";
                                            $prep2=$this->db->prepare($sql2);
                                            $prep2->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                                            $prep2->bindValue(":COUNT",$count,PDO::PARAM_INT);
                                            $prep2->execute();
					}
          $this->logFunctionCalling(__FUNCTION__);
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

	/**
	 * insertRecord
	 * @param $iProfileId
	 * @param $iCount
	 * @return bool
	 */
	public function insertRecord($iProfileId,$iCount)
	{
		try{
			$sql = "INSERT IGNORE INTO newjs.JP_NTIMES(PROFILEID,NTIMES) VALUES(:PID,:CNT)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PID",$iProfileId,PDO::PARAM_INT);
			$prep->bindValue(":CNT",$iCount,PDO::PARAM_INT);
			$prep->execute();
      $this->logFunctionCalling(__FUNCTION__);
			return true;
		} catch(PDOException $ex) {
			throw new jsException($ex);
		}
	}
  
  private function logFunctionCalling($funName)
    {return;
      $key = __CLASS__.'_'.date('Y-m-d');
      JsMemcache::getInstance()->hIncrBy($key, $funName);
      
      JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
    }
}
?>
