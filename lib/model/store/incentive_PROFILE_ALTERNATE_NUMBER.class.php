<?php
class PROFILE_ALTERNATE_NUMBER extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function checkPhone($numberArray='',$isd=''){
                try
                {
                        $res=null;
                        $str='';
                        if($numberArray)
                        {
                                foreach($numberArray as $k=>$num)
                                {
                                        if($k!=0)
                                                $str.=", ";
                                        $str.=":mob".$k.", :mob0".$k.", :mobIsd".$k.", :mobIsdA".$k.", :mobIsd0".$k;
                                }
                        }
                        if($str)
                        {
                                $sql="SELECT PROFILEID,ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE ALTERNATE_NUMBER IN (".$str.")";
                                $prep=$this->db->prepare($sql);
                                if($numberArray)
                                {
                                        foreach($numberArray as $k=>$num)
                                        {

                                                $prep->bindValue(":mob".$k,$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob0".$k,'0'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd".$k,$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsdA".$k,'+'.$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd0".$k,'0'.$isd.$num,PDO::PARAM_STR);
                                        }
                                }
                                $prep->execute();
				$i=0;
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $res[$i]['PROFILEID']=$result['PROFILEID'];
                                        $res[$i]['NUMBER']=$result['ALTERNATE_NUMBER'];
                                        $res[$i]['TYPE']="FOLLOWUP";
					$i++;
                                }

                        }
                        else
                                throw new jsException("No phone number as Input paramter");

                        return $res;

                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	public function getAlternateNumber($profileid)
	{
		try
                {
                        $sql = "SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID = :PROFILEID ORDER BY ID DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $AL_number=$result['ALTERNATE_NUMBER'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $AL_number;

	}
        public function addPhoneNumber($profileid,$alternateNo,$entryBy)
	{
        	try
        	{
                	$sql = "insert ignore into incentive.PROFILE_ALTERNATE_NUMBER (PROFILEID,ALTERNATE_NUMBER,ENTRYBY,ENTRY_DT) VALUES (:PROFILEID,:ALTERNATE_NUMBER,:ENTRYBY,now())";
                	$prep = $this->db->prepare($sql);
            		$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            		$prep->bindValue(":ALTERNATE_NUMBER",$alternateNo,PDO::PARAM_STR);
            		$prep->bindValue(":ENTRYBY",$entryBy,PDO::PARAM_STR);
            		$prep->execute();
        	}
        	catch (Exception $e) {
            		throw new jsException($e);
        	}
	}
}
?>
