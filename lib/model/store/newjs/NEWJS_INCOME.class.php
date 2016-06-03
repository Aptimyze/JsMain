<?php
//This class is used to execute queries on newjs.INCOME table

class NEWJS_INCOME extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	/**
        This function fetches the MAPPED_MIN_VAL corresponding MIN_VALUE or MAPPED_MAX_VAL corresponding to MAX_VALUE from INCOME table.
        * @param  income value and type = 1 for MIN or type = 2 for MAX
        * @return MAPPED_MIN_VAL or MAPPED_MAX_VAL
        **/
	public function getMappedValue($value,$type)
	{
		if((!$value && $value!=0) || !$type)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN getMappedValue() of NEWJS_INCOME.class.php");

		if($type == 1)
			$label = "MIN";
		elseif($type == 2)
			$label = "MAX";

		try
		{
			$sql = "SELECT MAPPED_".$label."_VAL FROM newjs.INCOME WHERE ".$label."_VALUE = :VALUE AND VISIBLE = 'Y' LIMIT 1";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":VALUE", $value, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row["MAPPED_".$label."_VAL"];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
        This function fetches the SORTBY value corresponding to MIN_VALUE/MAX_VALUE and RUPEE/DOLLAR from INCOME table.
        * @param  income value and type = 1 for MIN or type = 2 for MAX and currency = 1 for rupee or currency =2 for dollar
        * @return SORTBY value
        **/
	public function getSortbyValue($value,$type,$currency)
	{
		if((!$value && $value!=0) || !$type)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN getSortbyValue() of NEWJS_INCOME.class.php");

		if($type == 1)
			$label = "MIN";
		elseif($type == 2)
			$label = "MAX";

		if($currency == 1)
			$cLabel = "RUPEES";
		elseif($currency == 2)
			$cLabel = "DOLLARS";
		
		try
		{
			$sql = "SELECT SORTBY FROM newjs.INCOME WHERE ".$label."_VALUE = :VALUE AND VISIBLE = 'Y' AND TYPE = :TYPE AND SORTBY!=0";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":VALUE", $value, PDO::PARAM_INT);
                        $res->bindValue(":TYPE", $cLabel, PDO::PARAM_STR);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row["SORTBY"];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
        This function fetches the income values corresponding to sortby values from INCOME table.
        * @param  sortby values in comma separated format ex 1,2,3
        * @return array of income values
        **/
	public function getIncomeValues($param)
	{
		if(!$param)
			throw new jsException("","PARAM IS BLANK IN getIncomeValues() of NEWJS_INCOME.class.php");

		try
		{
			$param = str_replace("'","",$param);
                        $param = str_replace("\"","",$param);
                        $param_arr = explode(",",$param);
                        foreach($param_arr as $k=>$v)
                        {
                                $paramArr[] = ":PARAM".$k;
                        }
			$sql = "SELECT VALUE FROM newjs.INCOME WHERE VISIBLE = 'Y' AND SORTBY IN (".implode(",",$paramArr).") ORDER BY SORTBY";
			$res=$this->db->prepare($sql);
			foreach($param_arr as $k=>$v)
                        {
                                $res->bindValue($paramArr[$k],$v, PDO::PARAM_INT);
                        }
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row["VALUE"];
			}
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	public function getMinValue($value)
	{
		try 
		{
			$sql="SELECT MIN_VALUE FROM newjs.INCOME WHERE VALUE=:VALUE";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VALUE",$value,PDO::PARAM_INT);
			$res->execute();
			if($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				return $result['MIN_VALUE'];		
			}
			return NULL;
		}
	        catch (PDOException $e)
		{
			throw new jsException($e);
		}		
	}
	public function getMinMaxValue($minValBeg,$minValEnd,$type)
	{
		try 
		{
			$sql = "SELECT MIN_VALUE,MAX_VALUE FROM newjs.INCOME WHERE MIN_VALUE BETWEEN $minValBeg AND $minValEnd AND TYPE=:TYPE AND VALUE!=7";
			$res = $this->db->prepare($sql);
			$res->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[]=$row;		
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/*
        This function fetches ID,LABEL,VALUE,TYPE from newjs.INCOME table
        @return - result set array
        */
        public function getFullTable()
        {
                try
                {
                        $sql = "SELECT SQL_CACHE ID,LABEL,VALUE,TYPE,MIN_LABEL,MIN_VALUE,MAX_LABEL,MAX_VALUE FROM newjs.INCOME WHERE VISIBLE = :VISIBLE ORDER BY TYPE,SORTBY";
                        $res = $this->db->prepare($sql);
			$res->bindValue(":VISIBLE","Y",PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row;
                        }
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
        }
}
?>
