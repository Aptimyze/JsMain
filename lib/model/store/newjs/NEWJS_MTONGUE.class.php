<?php
class NEWJS_MTONGUE extends TABLE
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
	public function getDATA()
	{
		try
		{
			$sql = "SELECT SQL_CACHE VALUE, LABEL, SMALL_LABEL, REGION FROM MTONGUE WHERE REGION <>5 ORDER BY REGION DESC,SORTBY_NEW";
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
        This function fetches ID,LABEL,VALUE,REGION from newjs.MTONGUE table
        @return - result set array
        */
        public function getFullTableForRegistration()
        {
                try
                {
                        $sql = "SELECT SQL_CACHE ID,SMALL_LABEL,VALUE,REGION FROM newjs.MTONGUE WHERE REG_DISPLAY!='N' AND REGION!=5 ORDER BY REGION DESC,SORTBY_NEW";
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
        /**
        This function fetches the MAPPED_MIN_VAL corresponding MIN_VALUE or MAPPED_MAX_VAL corresponding to MAX_VALUE from INCOME table.
        * @param  income value and type = 1 for MIN or type = 2 for MAX
        * @return MAPPED_MIN_VAL or MAPPED_MAX_VAL
        **/
	public function getMtongue($val)
	{
		try
		{
			$sql = "SELECT SQL_CACHE VALUE, LABEL, SMALL_LABEL, REGION FROM MTONGUE WHERE REGION <>5 AND VALUE=:VALUE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":VALUE", $val, PDO::PARAM_INT);
            $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[]=$row;
			}
			return $result[0];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
