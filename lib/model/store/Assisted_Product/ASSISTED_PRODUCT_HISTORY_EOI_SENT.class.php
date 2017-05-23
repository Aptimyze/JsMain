<?php
/*
This class is used to send queries to HISTORY_EOI_SENT table in Assisted_Product database
*/
class ASSISTED_PRODUCT_HISTORY_EOI_SENT extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function insert the total contacts made in a day  
	*/
	public function Insert($totalContactsMade)
	{
		try{
			$szTimeStamp = date("Y-m-d");
			$sql="REPLACE INTO Assisted_Product.HISTORY_EOI_SENT (DATE,CONTACTS_SENT) VALUES(:datetime,:contactsMade)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":contactsMade", $totalContactsMade, PDO::PARAM_INT);
			$prep->bindValue(":datetime", $szTimeStamp, PDO::PARAM_STR);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
	/*
	This function select the total contacts made in a day  
	@param 
	@return an total no. of contacts of profileid 
	*/
	public function Select()
	{
		try{
			$szTimeStamp = date("Y-m-d");
			$sql="SELECT CONTACTS_SENT FROM Assisted_Product.HISTORY_EOI_SENT WHERE DATE = :date";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":date",$szTimeStamp,PDO::PARAM_STR);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row['CONTACTS_SENT'];
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
}
