<?php
class incentive_GHARPAY_CSV_DATA extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($prefixName, $name, $contactNumber, $address, $landmark, $email, $pincode, $city, $username, $amount, $prefTime, $serviceNameStr, $quantity, $entryBy, $csvDate)
	{
		try
		{
			$sql= "INSERT IGNORE INTO incentive.GHARPAY_CSV_DATA(`PREFIX`,`FIRST_NAME`,`LAST_NAME`,`CONTACT_NUMBER`,`ADDRESS`,`LANDMARK`,`EMAIL`,`PINCODE`,`CITY`,`ORDER_ID`,`ORDER_AMOUNT`,`DELIVERY_DT`,`PRODUCT_DESC`,`QUANTITY`,`UNIT_PRICE`,`COMMENTS`,`CSV_ENTRY_DATE`) VALUES(:PREFIX,:FIRST_NAME,:LAST_NAME,:CONTACT_NUMBER,:ADDRESS,:LANDMARK,:EMAIL,:PINCODE,:CITY,:ORDER_ID,:ORDER_AMOUNT,:DELIVERY_DT,:PRODUCT_DESC,:QUANTITY,:UNIT_PRICE,:COMMENTS,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PREFIX",$prefixName,PDO::PARAM_STR);
			$prep->bindValue(":FIRST_NAME",$name,PDO::PARAM_STR);
			$prep->bindValue(":LAST_NAME",$name,PDO::PARAM_STR);
			$prep->bindValue(":CONTACT_NUMBER",$contactNumber,PDO::PARAM_STR);
			$prep->bindValue(":ADDRESS",$address,PDO::PARAM_STR);
			$prep->bindValue(":LANDMARK",$landmark,PDO::PARAM_STR);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->bindValue(":PINCODE",$pincode,PDO::PARAM_STR);
			$prep->bindValue(":CITY",$city,PDO::PARAM_STR);
			$prep->bindValue(":ORDER_ID",$username,PDO::PARAM_STR);
			$prep->bindValue(":ORDER_AMOUNT",$amount,PDO::PARAM_STR);
			$prep->bindValue(":DELIVERY_DT",$prefTime,PDO::PARAM_STR);
			$prep->bindValue(":PRODUCT_DESC",$serviceNameStr,PDO::PARAM_STR);
			$prep->bindValue(":QUANTITY",$quantity,PDO::PARAM_STR);
			$prep->bindValue(":UNIT_PRICE",$amount,PDO::PARAM_STR);
			$prep->bindValue(":COMMENTS",$entryBy,PDO::PARAM_STR);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
        public function getData($date)
        {
                try
                {
                        $sql="SELECT * FROM incentive.GHARPAY_CSV_DATA WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$date,PDO::PARAM_STR);
                        $prep->execute();
                        $i=0;
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $data[$i]=$res;
                                $i++;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
        }
}
?>
