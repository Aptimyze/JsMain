<?php
class incentive_DO_NOT_CALL extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function checkProfileDNC($profileid)
	{
		try
                {
			$sql = "SELECT COUNT(*) AS COUNT FROM incentive.DO_NOT_CALL WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $DNCCount=$result['COUNT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $DNCCount;
	}
        public function getDoNotCallProfiles($profileIdArr)
        {
		try{
                	if(is_array($profileIdArr))
                	{
                	        foreach($profileIdArr as $key=>$pid){
                	                if($key == 0)
                	                        $str = ":PROFILEID".$key;
                	                else
                	                        $str .= ",:PROFILEID".$key;
                        	}
                        	$sql = "SELECT distinct PROFILEID FROM incentive.DO_NOT_CALL WHERE PROFILEID IN ($str) ";
                	        $res=$this->db->prepare($sql);
                        	unset($pid);
                        	foreach($profileIdArr as $key=>$pid)
                        	        $res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
                        	$res->execute();
                        	while($row = $res->fetch(PDO::FETCH_ASSOC))
                        	        $result[] = $row['PROFILEID'];
                        	return $result;
                	}
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

}
?>
