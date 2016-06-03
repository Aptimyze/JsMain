<?php
class MIS_WHY_FILTER extends TABLE{
       

        

        public function __construct($dbName="")
        {
			parent::__construct($dbname);
        }
	public function insertEntry($sender,$receiver,$type,$data,$spam='N')
	{
		try
		{
			$res=null;
			if($sender && $receiver && $type && $data)
			{
				$sql="insert ignore into MIS.WHY_FILTER(SENDER,RECEIVER,TYPE,DATA,SPAM,`DATE`) values(:sender,:receiver,:type,:data,:spam,now())";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":sender",$sender,PDO::PARAM_INT);
				$prep->bindValue(":receiver",$receiver,PDO::PARAM_INT);
				$prep->bindValue(":type",$type,PDO::PARAM_STR);
				$prep->bindValue(":data",$data,PDO::PARAM_STR);
				$prep->bindValue(":spam",$spam,PDO::PARAM_STR);
				$prep->execute();
			}
			else
				throw new jsException("error in why filter $sender,$receiver,$type,$data");
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
?>
