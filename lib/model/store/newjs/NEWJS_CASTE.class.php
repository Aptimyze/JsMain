<?php
//This class is used to execute queries on newjs.CASTE table

class NEWJS_CASTE extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function fetches the PARENT,ISALL,ISGROUP values belonging to various castes.
        * @param  caste values in comma separated format ex - '1','2','3'.
        * @return array[VALUE][PARENT],[VALUE][ISALL],[VALUE][ISGROUP]
        **/
	public function getAllData($caste_values)
	{
		if(!$caste_values)
			throw new jsException("","CASTE_VALUES IS BLANK IN getAllData() of NEWJS_CASTE.class.php");
    
    //Look into AllCasteMap File
    return AllCasteMap::getAllCaste($caste_values);
	}

	/**
        This function fetches all the castes belonging to a parent caste.
        * @param  parent value as integer.
        * @return castes array.
        **/
	public function getCastesOfParent($parent)
	{
		if(!$parent)
			throw new jsException("","PARENT IS BLANK IN getCastesOfParent() of NEWJS_CASTE.class.php");

		try
		{
			$sql = "SELECT SQL_CACHE VALUE FROM newjs.CASTE WHERE PARENT = :PARENT";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PARENT", $parent, PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
				$output[] = $row["VALUE"];
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/**
        This function fetches the caste value of a parent.
        * @param  parent value as integer.
        * @return caste value
        **/
	public function getParent($parent)
	{
		if(!$parent)
			throw new jsException("","PARENT IS BLANK IN getParent() of NEWJS_CASTE.class.php");

		try
		{
			$sql = "SELECT SQL_CACHE VALUE FROM newjs.CASTE WHERE ISALL='Y' AND PARENT= :PARENT";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PARENT", $parent, PDO::PARAM_STR);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$output = $row["VALUE"];
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function getDATA($religion_value)
        {
                try
                {
                        $sql = "SELECT SQL_CACHE VALUE,LABEL from CASTE WHERE PARENT=:relVal AND VALUE NOT IN (242,243,244,245,246) ORDER BY SORTBY";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(':relVal',$religion_value,PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_BOTH))
                        {
                                $result[]=$row;
                        }
                        return $result;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/*
	This function is used to populate the caste data for top search band
	@param - religion value(optional)
	@return - caste array
	*/
	public function getTopSearchBandCasteData($religion='')
	{
		try
                {
                        $sql = "SELECT SQL_CACHE C.PARENT AS PARENT,C.LABEL AS LABEL, C.VALUE AS VALUE, C.ISALL AS ISALL, C.ISGROUP AS ISGROUP FROM CASTE C, CASTE AS B WHERE C.PARENT = B.PARENT AND B.ISALL = 'Y' ";
			if($religion)
				$sql = $sql."AND C.PARENT = :RELIGION AND C.ISALL!='Y'";
			$sql = $sql."ORDER BY B.TOP_SORTBY, C.SORTBY";
                        $res=$this->db->prepare($sql);
			if($religion)
				$res->bindParam(":RELIGION", $religion, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $result[]=$row;
                        }
                        return $result;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*
	This function is used to populate the religion caste dependency data for top search band
	@return - caste religion array
	*/
	public function getTopSearchBandReligionCasteData()
	{
		try
                {
			$sql = "SET SESSION group_concat_max_len = 2000";
			$res=$this->db->prepare($sql);
                        $res->execute();
			
                        $sql = "SELECT SQL_CACHE C.PARENT AS PARENT, GROUP_CONCAT( C.VALUE ORDER BY B.TOP_SORTBY, C.SORTBY SEPARATOR ',' ) AS VALUE FROM CASTE C, CASTE AS B WHERE C.PARENT = B.PARENT AND B.ISALL =  'Y' AND C.ISALL !=  'Y' GROUP BY C.PARENT";
                        $res=$this->db->prepare($sql);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $result[]=$row;
                        }
                        return $result;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/*
	This function fetches ID,LABEL,CASTE,PARENT from newjs.CASTE table
	@return - result set array
	*/
	public function getFullTableForRegistration()
	{
		try
		{
			$sql = "SELECT SQL_CACHE C.ID AS ID,C.PARENT AS PARENT, C.LABEL AS LABEL, C.VALUE AS VALUE FROM CASTE C, CASTE B WHERE C.PARENT = B.PARENT AND B.ISALL =  'Y' AND C.REG_DISPLAY !=  'N' AND C.ISALL!='Y' ORDER BY B.TOP_SORTBY, C.SORTBY";
			$res = $this->db->prepare($sql);
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

	/*This function return parent if all the caste values given belong to same parent else return null
	*@param caste_values : string of caste values
	*@return output : parent of the provided castes
	*/
	 
	public function getParentIfSingle($caste_values)
        {
                if(!$caste_values)
                        throw new jsException("","CASTE_VALUES IS BLANK IN getParentIfSingle() of NEWJS_CASTE.class.php");

                try
                {
                        $caste_values = str_replace("'","",$caste_values);
                        $caste_values = str_replace("\"","",$caste_values);
                        $caste_values_arr = explode(",",$caste_values);
                        foreach($caste_values_arr as $k=>$v)
                        {
                                $paramArr[] = ":PARAM".$k;
                        }
                        $sql = "SELECT DISTINCT(PARENT) FROM newjs.CASTE WHERE VALUE IN (".implode(",",$paramArr).")";
                        $res = $this->db->prepare($sql);
                        foreach($caste_values_arr as $k=>$v)
                        {
                                $res->bindValue($paramArr[$k],$v, PDO::PARAM_STR);
                        }
                        $res->execute();
			$count =0;
	                while($row = $res->fetch(PDO::FETCH_ASSOC))
        	        {
				if($count == 1)
					return NULL;
                	        $output = $row["PARENT"];
				$count++;
                        }
                        return $output;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

}
?>
