<?php

class CONNECT extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function findUser($userno)
	{
		$sql= "select * from jsadmin.CONNECT where ID=:USERNO";
		$res=$this->db->prepare($sql);
		$res->bindValue(":USERNO", $userno, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$count++;
			$arr['TIME']=$row['TIME'];
			$arr['USER']=$row['USER'];
		}
		if($count)
			return $arr;
		else
			return 0;
	}

	public function updateUserTime($userno)
	{
		$tm = time();
		$sql = "update jsadmin.CONNECT set TIME=:TIME where ID=:ID";
		$res=$this->db->prepare($sql);
                $prep->bindValue(":TIME",$tm,PDO::PARAM_STR);
		$prep->bindValue(":ID",$userno,PDO::PARAM_INT);
		$res->execute();
	}
}

?>
