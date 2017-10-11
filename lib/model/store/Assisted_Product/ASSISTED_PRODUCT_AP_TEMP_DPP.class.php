<?php
/*
This class is used to send queries to AP_TEMP_DPP table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_TEMP_DPP extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the full data corresponding to a profileid and creator name
	@param 1) profileId 2) creator name
	@return an array having all the data from the table
	*/
	public function getData($profileid,$name)
	{
		if(!$profileid || !$name)
                        throw new jsException("","PROFILEID OR NAME IS BLANK IN getData() OF ASSISTED_PRODUCT_AP_TEMP_DPP.class.php");

		try
		{
			$sql = "SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID=:PROFILEID AND CREATED_BY=:NAME";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":NAME", $name, PDO::PARAM_STR);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}
	
	/*This function updates the full data corresponding to a profileid and some condition
	@param 1) profileId 2) update string 3) where condition string
	@return 
	*/
	public function updateDPP($profileid,$updStr,$whereStr="")
	{
		if($profileid && $updStr)
		{
			try
			{
				$sql = "UPDATE Assisted_Product.AP_TEMP_DPP SET ". $updStr ." WHERE PROFILEID=:profileid ".$whereStr;
				
				$res = $this->db->prepare($sql);
				$res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
				$res->execute();
				if($res->rowCount() == 0){
          return false;
        }
        return true;
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		else
		{
			throw new jsException("","PROFILEID OR update string  IS BLANK IN AP_TEMP_DPP store class function update dPP ");
		}
	}

	/*
        This function replaces/inserts data in AP_TEMP_DPP table
        @param - parameters array where index is the column name and value is the column value
        */
	public function replaceData($parameters)
	{
		if(!$parameters || !is_array($parameters))
			throw new jsException("","PARAMETERS ARRAY IS BLANK IN replaceData() OF ASSISTED_PRODUCT_AP_TEMP_DPP.class.php");

		try
		{
			$columns = "";
			$vals = "";
			foreach($parameters as $k=>$v)
			{
				$columns = $columns.$k.",";
				$vals = $vals.":".$k.",";
			}
			$columns = rtrim($columns,",");
			$vals = rtrim($vals,",");

			$sql = "REPLACE INTO Assisted_Product.AP_TEMP_DPP(".$columns.") VALUES (".$vals.")";
			$res = $this->db->prepare($sql);

			foreach($parameters as $k=>$v)
                        {
				$res->bindValue(":".$k, $v, PDO::PARAM_STR);
                        }
			$res->execute();
		}
		catch(PDOException $e)
                {
      $http_msg=print_r($_SERVER,true);
      $params = print_r($parameters,true);
                            mail("lavesh.rawat@gmail.com","AP_TEMP_DPP Replace Data","$http_msg , $params");
                        throw new jsException($e);
                }
	}
}
?>

