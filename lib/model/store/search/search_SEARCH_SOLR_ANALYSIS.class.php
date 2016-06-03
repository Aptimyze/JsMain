<?php
/**
* @author Akash Kumar
* @created 2015-03-27
*/
class search_SEARCH_SOLR_ANALYSIS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /*
	* log records
	*/
	public function ins($profileId,$sType,$noOfResult,$execution,$URL_PATH)
	{
		try
		{
			$sql = "INSERT INTO search.SEARCH_SOLR_ANALYSIS (PROFILEID,SEARCH_TYPE,LIMIT_SEARCHED,TIME_EXECUTION,URL_FROM) VALUES (:PID,:ST,:RESULT,:EXE,:URL)";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PID", $profileId, PDO::PARAM_INT);
			$res->bindParam(":ST",$sType, PDO::PARAM_STR);
        	        $res->bindParam(":RESULT", $noOfResult, PDO::PARAM_INT);
			$res->bindParam(":EXE",$execution, PDO::PARAM_INT);
                        $res->bindParam(":URL",$URL_PATH, PDO::PARAM_STR);
                        $res->execute();	
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
