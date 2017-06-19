<?php
class incentive_INBOUND_ALLOT extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	public function insertProfile($paramsArr)
	{
		try
                {
			foreach($paramsArr as $key=>$val)
                                ${$key} = $val;

                        $sql="INSERT INTO incentive.INBOUND_ALLOT(PROFILEID,USERNAME,CALL_SOURCE,QUERY_TYPE,COMMENTS,ALLOTED_TO,ALLOT_TIME) VALUES(:PROFILEID,:USERNAME,:CALL_SOURCE,:QUERY_TYPE,:COMMENTS,:ALLOTED_TO,:ALLOT_TIME)";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
			$prep->bindValue(":USERNAME",$USERNAME,PDO::PARAM_STR);			
			$prep->bindValue(":CALL_SOURCE",$CALL_SOURCE,PDO::PARAM_STR);
			$prep->bindValue(":QUERY_TYPE",$QUERY_TYPE,PDO::PARAM_STR);
			$prep->bindValue(":COMMENTS",$COMMENTS,PDO::PARAM_STR);
			$prep->bindValue(":ALLOTED_TO",$ALLOTED_TO,PDO::PARAM_STR);
			$prep->bindValue(":ALLOT_TIME",$ALLOT_TIME,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}	
?>
