<?php
class billing_DISCOUNT_OFFER extends TABLE
{
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getDiscountOffer($subMem)
        {
		try 
		{
			if($subMem)
			{ 
				$sql="SELECT DISCOUNT FROM billing.DISCOUNT_OFFER WHERE SERVICEID = :SERVICEID ";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":SERVICEID",$subMem,PDO::PARAM_INT);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res= $result["DISCOUNT"];
				}
				return $res;
			}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function getDiscountUpto()
        {
                try
                {
                        $sql="SELECT DISCOUNT FROM billing.DISCOUNT_OFFER ORDER BY DISCOUNT DESC LIMIT 1";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
                              $discount=$result['DISCOUNT'];
			      return $discount;
                        }
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }

        }

	public function checkDiscountOffer()
	{
		try
		{
			$sql="SELECT COUNT(*) AS CNT FROM billing.DISCOUNT_OFFER_REVAMP WHERE END_DT>=NOW()";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				if($res["CNT"]>0)
					return 1;
				else
					return 0;
			}
		}
		catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }

	}

	public function truncateTable(){
		try{
			$sql = "TRUNCATE TABLE billing.DISCOUNT_OFFER";
			$prep=$this->db->prepare($sql);
			$prep->execute();
		} catch (Exception $e){
			throw new jsException($e);
		}
	}

	public function removeDiscountValue($serviceId){
		try{
			$sql ="DELETE FROM billing.DISCOUNT_OFFER WHERE SERVICEID=:SERVICEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SERVICEID",$serviceId,PDO::PARAM_STR);
			$prep->execute();
		} catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function updateDiscountValue($serviceId, $discountPerc){
		try{
			$sql ="UPDATE billing.DISCOUNT_OFFER SET DISCOUNT=:DISCOUNT WHERE SERVICEID=:SERVICEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DISCOUNT",$discountPerc,PDO::PARAM_INT);
			$prep->bindValue(":SERVICEID",$serviceId,PDO::PARAM_STR);
			$prep->execute();
		} catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function insertDiscountValue($serviceId, $discountPerc){
		try{
			$sql ="INSERT INTO billing.DISCOUNT_OFFER (SERVICEID,DISCOUNT) VALUES(:SERVICEID,:DISCOUNT)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DISCOUNT",$discountPerc,PDO::PARAM_INT);
			$prep->bindValue(":SERVICEID",$serviceId,PDO::PARAM_STR);
			$prep->execute();
		} catch(Exception $e){
			throw new jsException($e);
		}
	}

    public function checkFlatDiscount()
    {
        try
        {
            $sql="SELECT count(distinct DISCOUNT) AS CNT FROM billing.DISCOUNT_OFFER";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                  $disCount =$result['CNT'];
            }
			if($disCount==1)
				return true;
			return false;			
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }

    }

	public function getDiscountOfferForServiceArr($serviceArr){
		if(empty($serviceArr) || !is_array($serviceArr)){
			throw new jsException("Empty array list passed in getDiscountOfferForServiceArr");	
		}
		$serviceStr = "'".implode("','", $serviceArr)."'";
		try{
			$sql = "SELECT SQL_CACHE SERVICEID, DISCOUNT FROM billing.DISCOUNT_OFFER WHERE SERVICEID IN ($serviceStr)";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$outputArr[$result['SERVICEID']]= $result['DISCOUNT'];
			}
			return $outputArr;
		} catch (Exception $e){
			throw new jsException($e);
		}
	}
}	
?>
