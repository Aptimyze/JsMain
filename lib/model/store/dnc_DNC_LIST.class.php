<?php
class dnc_DNC_LIST extends TABLE
{
        public function __construct($dbname="dnc_con")
        {
                parent::__construct($dbname);
        }
	public function fetchDNCNumberArray($phoneNumArr)
	{
		try
                {
			$count = count($phoneNumArr);
			if($count==0)
				return array();
                        $in_params = trim(str_repeat('?, ', $count), ', ');
			$sql = "SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN({$in_params})";
                        $prep = $this->db->prepare($sql);
                        $prep->execute($phoneNumArr);
			$phoneArray=array();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                               $phoneArray[]=$result['PHONE'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $phoneArray;
	}
        public function DncStatus($phoneArray)
        {
                if(!is_array($phoneArray))
                        return;
                try
                {
                        foreach($phoneArray as $k=>$v)
                        {
                                if($str!='')
                                        $str.=",";
                                $str.= ":NUM".$k;
                        }
                        $sql = "SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN (".$str.")";
                        $prep = $this->db->prepare($sql);
                        foreach($phoneArray as $k=>$v)
                                $prep->bindValue(":NUM".$k,$v,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                               $return[$result['PHONE']]="Y";
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $return;
        }
        public function fetchDncCount()
        {
                try{
                        $sql = "SELECT count(1) as cnt FROM DNC.DNC_LIST";
                        $prep = $this->db->prepare($sql);
			$prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                               $tot=$result['cnt'];
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $tot;
        }
}
?>
