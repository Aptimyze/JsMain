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
    public function getAllCityLabel($oneTimeFlag="")
    {
        try
        {
            $sql = "select VALUE, LABEL from newjs.CITY_NEW";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $res = array();
            while($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                if($oneTimeFlag)
                {
                    $res[$row['VALUE']] = $row['LABEL'];
                }
                else
                {
                    if($row['VALUE'])
                    {
                        $res[$row['VALUE']] = $row['LABEL'];
                    }
                }
                
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res; 
    }

    //This is a one time use function and should be removed after usage
    public function addCitiesIntoTable($mergedArr)
    {
        try
        {
            $sql = "INSERT INTO newjs.city_test(LABEL,VALUE,SORTBY) VALUES";
            $i=1;
            foreach($mergedArr as $key=>$values)
            {
                $paramArr[] = "(:LABEL".$i.",:VALUE".$i.",".$i.")";
                $i++;               
            }
            $sql = $sql.implode(",",$paramArr);            
            $res = $this->db->prepare($sql);
            $i=1;
            foreach($mergedArr as $key=>$value)
            {
                $res->bindValue(":LABEL".$i,$value, PDO::PARAM_STR);
                $res->bindValue(":VALUE".$i,$key, PDO::PARAM_STR);
                $i++;
            }
            $res->execute();            
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    //This is a one time use function and should be removed after usage
    public function updateNewCityData($newCityKeysArr)
    {
        try
        {        
            $sql = "UPDATE newjs.city_test SET TYPE=:TYPE,DD_TOP='',DD_TOP_SORTBY='',STD_CODE=:STD_CODE,COUNTRY_VALUE=:COUNTRY_VAL WHERE VALUE IN (";
            $i=0;
            foreach($newCityKeysArr as $key=>$value)
            {
                $cityKey[] = ":VALUE".$i;
                $i++;
            }
            $sql = $sql.implode(",",$cityKey).")";            
            $res = $this->db->prepare($sql);

            $i=0;
            foreach($newCityKeysArr as $key=>$value)
            {
                $res->bindValue(":VALUE".$i,$value, PDO::PARAM_STR);
                $i++;
            }                    
            $res->bindValue(":TYPE","CITY", PDO::PARAM_STR);
            $res->bindValue(":STD_CODE","0", PDO::PARAM_INT);
            $res->bindValue(":COUNTRY_VAL","51", PDO::PARAM_INT);            
            $res->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    //This is a one time use function and should be removed after usage
    public function updateOldCityValuesIntoNewTable()
    {
        try
        {
            $sql = "UPDATE  newjs.city_test as T1 INNER JOIN newjs.CITY_NEW as T2 ON (T1.VALUE = T2.VALUE) SET T1.TYPE = T2.TYPE, T1.DD_TOP = T2.DD_TOP, T1.DD_TOP_SORTBY = T2.DD_TOP_SORTBY, T1.STD_CODE = T2.STD_CODE, T1.COUNTRY_VALUE = T2.COUNTRY_VALUE ";
            $res = $this->db->prepare($sql);
            $res->execute();            
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    //This is a one time use function and should be removed after usage
    public function RenameTable()
    {
        try 
        {
            $sql = "RENAME TABLE newjs.CITY_NEW to newjs.CITY_NEW_BACKUP,newjs.city_test to newjs.CITY_NEW";
            $res = $this->db->prepare($sql);
            $res->execute();
        }    
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    //This is a one time use function and should be removed after usage
    public function updateSpellings($value,$label)
    {
        try
        {
            $sql = "UPDATE newjs.CITY_NEW SET LABEL=:LABEL WHERE VALUE=:VALUE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":LABEL",$label, PDO::PARAM_STR);
            $res->bindValue(":VALUE",$value, PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }    
}
