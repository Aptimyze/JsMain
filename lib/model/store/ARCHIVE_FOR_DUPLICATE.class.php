<?php
class ARCHIVE_FOR_DUPLICATE extends TABLE
{

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

	/* Get the contacts archive info 
	*/
	public function getContactsArchive($pid,$paramArr="")
	{
		try
		{
			$sql="select SQL_CACHE CHANGEID,FIELD from newjs.CONTACT_ARCHIVE where PROFILEID=:PROFILEID";
			if(is_array($paramArr)){
				$paramStr ="'".implode("','",$paramArr)."'";
				$sql .=" AND FIELD IN ($paramStr)";
			}
			unset($res);
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[$result[FIELD]][]=$result[CHANGEID];
			}

			if(is_array($res))
			{
				unset($contactArchive);
				foreach($res as $key=>$val)
				{
					$changeids =implode(",",$val);
					$sql1="select SQL_CACHE distinct OLD_VAL from newjs.CONTACT_ARCHIVE_INFO where CHANGEID IN ($changeids)";
					$prep1=$this->db->prepare($sql1);
					$prep1->execute();
					while($result1 = $prep1->fetch(PDO::FETCH_ASSOC))
					{
						$old_val[]=$result1[OLD_VAL];
					}
					$old_val =array_unique($old_val);
					$contactArchive[$key]=@implode(", ",$old_val);
					unset($old_val);
				}
			}
			if(is_array($contactArchive))
				return $contactArchive;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	/* Get the alternate number from CRM follow-up
	*/
	public function getAlternatePhone($pid)
	{
		$alternateNumArr =array();
		try{
			$sql="select SQL_CACHE distinct ALTERNATE_NUMBER from incentive.PROFILE_ALTERNATE_NUMBER where PROFILEID=:PROFILEID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$alternateNumArr[]=$result['ALTERNATE_NUMBER'];
			}
			if(is_array($alternateNumArr))
				$alternateNum =@implode(", ",$alternateNumArr);
			return $alternateNum;
		}
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}



        public function getPaymentIP($pid)
        {
                try{
			$paymentIPArr =array();
                        $sql="select SQL_CACHE distinct IPADD from billing.PAYMENT_DETAIL where PROFILEID=:PROFILEID AND STATUS='DONE' ORDER BY RECEIPTID DESC";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                        $prep->execute();
                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $paymentIPArr[]=$result['IPADD'];
                        }
			if(is_array($paymentIPArr))	
				$paymentIPStr =@implode(", ",$paymentIPArr);
                        return $paymentIPStr;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}	

        public function getNameOfUser($pid)
        {
                try{
                        $sql="select SQL_CACHE NAME from incentive.NAME_OF_USER where PROFILEID=:PROFILEID";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                        $prep->execute();
                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $nameOfUser =$result['NAME'];
                        }
                        return $nameOfUser;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

}
?>
