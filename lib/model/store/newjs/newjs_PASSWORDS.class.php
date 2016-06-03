<?php
class newjs_PASSWORDS extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->PASSWORD_BIND_TYPE = "STR";
			$this->tableName="newjs.PASSWORDS";
        }
	public function update($profileid,$password)
	{
		if(!$profileid || !$password)
			return;
		$sql = "REPLACE INTO ".$this->tableName." (`PROFILEID`,PASSWORD) VALUES (:PROFILEID, :PASSWORD)";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$res->bindValue(":PASSWORD",$password,constant('PDO::PARAM_'.$this->{'PASSWORD_BIND_TYPE'}));
		$res->execute();
	}
	public function insert($data)
	{
		if(!is_array($data))
		{
			throw new jsException("profileid or password not being passed");
		}
		$count = count($data);
		$sqlInsert = "INSERT IGNORE INTO  ".$this->tableName." (`PROFILEID`,PASSWORD) VALUES ";
		foreach($data as $k=>$v)
			$sqlInsertArr[]="(:PROFILEID".$k.",:PASSWORD".$k.")";
		$str = implode(",",$sqlInsertArr);
		$sqlInsert.=$str;
		$resInsert = $this->db->prepare($sqlInsert);
		foreach($data as $k=>$v)
		{
			$resInsert->bindValue(":PROFILEID".$k,$v['PROFILEID'],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":PASSWORD".$k,$v['PASSWORD'],constant('PDO::PARAM_'.$this->{'PASSWORD_BIND_TYPE'}));
		}
		$resInsert->execute();
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
			if($fields!='returnOnlySql')
			{
                	        if(!stristr($fields,"*"))
                        	{
					if($fields)
					{
						foreach($defaultFieldsRequired as $k=>$fieldName)
						{
							if(!stristr($fields,$fieldName))
								$fields.=",".$fieldName;
						}
					}
					else
					{
						$fields = implode (", ",$defaultFieldsRequired);
					}
	                        }
			}
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
			if($fields=='returnOnlySql')
				return $sqlSelectDetail;
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
    public function getAllPasswords($l1,$l2)
    {
        try{

                    $sql = "SELECT PROFILEID, PASSWORD FROM ".$this->tableName." WHERE PROFILEID BETWEEN :L1 AND :L2";
                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":L1",$l1,PDO::PARAM_INT);
                    $prep->bindValue(":L2",$l2,PDO::PARAM_INT);
                    $prep->execute();
                    while($res=$prep->fetch(PDO::FETCH_ASSOC))
                    $data[] = $res;
        }

        catch(Exception $e){
                throw new jsException($e);
        }
        return $data;
    }

}
?>
