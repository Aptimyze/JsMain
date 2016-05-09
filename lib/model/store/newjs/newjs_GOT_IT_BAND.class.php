<?php
class newjs_GOT_IT_BAND extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->tableName = "newjs.GOT_IT_BAND";
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->PAGES_DONE_BIND_TYPE = "INT";
        }

	public function insert($page,$profileid)
	{
		if(!$page||!$profileid)
			return;
		try
		{
			$sqlInsert = "INSERT IGNORE INTO ".$this->tableName." (PROFILEID, PAGES_DONE) VALUES (:PROFILEID,:PAGES_DONE)";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":PAGES_DONE",$page,constant('PDO::PARAM_'.$this->{'PAGES_DONE_BIND_TYPE'}));
			$resInsert->execute();
			return $resInsert->rowCount();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function update($page,$profileid)
	{
		if(!$page||!$profileid)
			return;
		try
		{
			$sql = "UPDATE ".$this->tableName." SET PAGES_DONE=PAGES_DONE*:PAGE WHERE PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PAGE",$page,constant('PDO::PARAM_'.$this->{'PAGES_DONE_BIND_TYPE'}));
			$res->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="", $lessThanEqualArrayWithoutQuote="", $like="",$nolike="",$groupBy="",$having="")
	{
		try
		{
			$arrays = array('valueArray'	=> "IN",
					'excludeArray'	=> "NOT IN",
					'greaterThanArray' => ">",
					'lessThanArray' => "<",
					'greaterThanEqualArrayWithoutQuote' => ">=",
					'lessThanEqualArrayWithoutQuote' => "<=",
					'like' => "LIKE",
					'nolike' => "NOT LIKE");
			$sqlSelectDetail = "SELECT $fields FROM ".$this->tableName." WHERE ";
			if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray && !$lessThanEqualArrayWithoutQuote)
				$sqlSelectDetail.="1";
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$sqlSelectDetail.= $this->getBindString($value,$param,$count,$relation);
						$count++;
					}
				}
			}
			if($groupBy)
			{
				$sqlSelectDetail.=" group by $groupBy ";
				if($having)
				{
					$sqlSelectDetail.=" having ".$having;
				}
			}
			if($orderby)
				$sqlSelectDetail.=" order by $orderby ";
			if($limit)
				$sqlSelectDetail.=" limit $limit ";
			$resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$paramBindValue = $this->{$param."_BIND_TYPE"};
						$values = explode(",",$value);
						foreach($values as $k1=>$val)
							$resSelectDetail->bindValue(":".$param."_".$count."_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
						$count++;
					}
				}
			}
			$resSelectDetail->execute();
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
				$detailArr[] = $rowSelectDetail;
			}
			return $detailArr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}

	public function getBindString($value,$param,$count,$relation)
	{
		if($count>1)
			$return = " AND ";
		
		$array = explode(",",$value);
		foreach($array as $k1=>$val)
			$bindArr[] = ":".$param."_".$count."_".$k1;
		$str=implode(",",$bindArr);
		if(strstr($relation,"LIKE"))
			$return.=" ".$param." ".$relation." '%$str%' ";
		else
			$return.=" ".$param." ".$relation." (".$str.") ";
		return $return;
	}
}
?>
