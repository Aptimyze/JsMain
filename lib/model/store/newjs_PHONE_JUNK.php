<?php
class PHONE_JUNK extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function checkJunk($number=''){
                try
                {
                        $res=null;
                        if($number)
                        {
				$str=":num, :num0, :num91, :num91A";
        			$sql="SELECT count(*) AS COUNT FROM newjs.PHONE_JUNK WHERE PHONE_NUM IN (".$str.")";
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":num",$number,PDO::PARAM_STR);
                                $prep->bindValue(":num0",'0'.$number,PDO::PARAM_STR);
                                $prep->bindValue(":num91",'91'.$number,PDO::PARAM_STR);
                                $prep->bindValue(":num91A",'+91'.$number,PDO::PARAM_STR);
                                $prep->execute();
                                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        if($result["COUNT"]>0)
						$return='Y';
					else
						$return="N";
                                }

                        }
                        return $return;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	public function getJunkNumbers()
	{
		$sql = "SELECT * FROM newjs.PHONE_JUNK";
		$prep=$this->db->prepare($sql);
		$prep->execute();
		while($result = $prep->fetch(PDO::FETCH_ASSOC))
		{
			$return[]=$result['PHONE_NUM'];
		}
		return $return;
	}
}
?>
