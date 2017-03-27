<?php
/*
This class is used to send query to EDUCATION_LEVEL_NEW table in newjs database
*/
class NEWJS_EDUCATION_LEVEL_NEW extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the distinct education levels corresponding to a set of educations
	@param comma separated education values
	@return an array of education levels
	*/
	public function getEduLevels($edu_str)
	{
		if(!$edu_str)
                        throw new jsException("","PARAM IS BLANK IN getEduLevels() OF NEWJS_EDUCATION_LEVEL_NEW.class.php");

		try
		{
			$edu_str = str_replace("'","",$edu_str);
			$edu_str = str_replace("\"","",$edu_str);
			$edu_str_arr = explode(",",$edu_str);
			foreach($edu_str_arr as $k=>$v)
			{
				$paramArr[] = ":PARAM".$k;
			}

			$sql = "SELECT DISTINCT(OLD_VALUE) FROM newjs.EDUCATION_LEVEL_NEW WHERE VALUE IN (".implode(",",$paramArr).")";
			$res = $this->db->prepare($sql);
			foreach($edu_str_arr as $k=>$v)
			{
				$res->bindValue($paramArr[$k],$v, PDO::PARAM_INT);
			}
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row["OLD_VALUE"];
                        }
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}

	/*
        This function fetches ID,LABEL,VALUE,GROUP NAME and EDU_TYPE from newjs.EDUCATION_LEVEL_NEW table and newjs.EDUCATION_GROUPING table
        @return - result set array
        */
	public function getFullTable()
	{
		try
                {
                        $sql = "SELECT SQL_CACHE E.ID AS ID, E.LABEL AS LABEL, E.VALUE AS VALUE, EG.LABEL AS GROUP_NAME,IF(E.OLD_VALUE=4,'UG',IF(E.OLD_VALUE=5 OR E.OLD_VALUE=6,'PG',IF(E.OLD_VALUE = 0,'ALL',''))) AS EDU_TYPE FROM newjs.EDUCATION_LEVEL_NEW E, newjs.EDUCATION_GROUPING EG WHERE E.GROUPING = EG.VALUE ORDER BY EG.SORTBY,E.SORTBY";
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
}
?>
