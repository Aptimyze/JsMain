<?php
//This class is used to execute queries on newjs.CITY_NEW table

class newjs_CITY_NEW extends TABLE {
  
  public function __construct($dbname = "") {
    
    parent::__construct($dbname);
  }

	public function getCityLabel($value)
	{
		try
		{
			$sql = "select SQL_CACHE LABEL from newjs.CITY_NEW WHERE VALUE=:VALUE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":VALUE",$value,PDO::PARAM_STR);
                        $prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;	
	}	 

	/*
        This function fetches ID,LABEL,VALUE,STATE from newjs.CITY_NEW table for given country
	*@param countries : Array of countries 
        @return - result set array
        */
        public function getCities($countries)
        {
                try
                {
			for($i=0;$i<sizeof($countries) ;$i++)
                	{
                        	$cIds[]=":COUNTRY$i";
                	}

                        $sql = "SELECT SQL_CACHE ID,LABEL,VALUE,SUBSTRING(VALUE,1,2) AS STATE,COUNTRY_VALUE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE IN (".implode(",",$cIds).") AND TYPE IN ('CITY','') ORDER BY COUNTRY_VALUE, SORTBY";
                        $res = $this->db->prepare($sql);
			for($i=0;$i<sizeof($countries) ;$i++)
	                {
                        	$res->bindValue(":COUNTRY$i", $countries[$i], PDO::PARAM_INT);
                	}

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
    public function getAllCityLabel()
    {
        try
        {
            $sql = "select VALUE, LABEL from newjs.CITY_NEW";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $res = array();
            while($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                if($row['VALUE'])
                    $res[$row['VALUE']] = $row['LABEL'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res; 
    }    
}
