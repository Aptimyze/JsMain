<?php
class billing_ORDERS_DEVICE extends TABLE{


	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertOrderDetails($id,$orderid,$source,$profileid,$couponCodeVal)
	{
		try
		{
			if($couponCodeVal != ''){
				$sql = "INSERT INTO billing.ORDERS_DEVICE(PROFILEID,ID,ORDERID,SOURCE,COUPON_CODE) VALUES (:PROFILEID,:ID,:ORDERID,:SOURCE,:COUPON_CODE)" ;
			} else {
				$sql = "INSERT INTO billing.ORDERS_DEVICE(PROFILEID,ID,ORDERID,SOURCE) VALUES (:PROFILEID,:ID,:ORDERID,:SOURCE)" ;
			}
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":ID", $id, PDO::PARAM_INT);
			$res->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
			$res->bindValue(":SOURCE", $source, PDO::PARAM_STR);
			if($couponCodeVal != ''){
				$res->bindValue(":COUPON_CODE", $couponCodeVal, PDO::PARAM_STR);
			}
			$res->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	public function getOrderDevice($id,$orderid)
	{
		try
		{
			$sql="SELECT SOURCE FROM billing.ORDERS_DEVICE WHERE ID=:ID AND ORDERID=:ORDERID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ID", $id, PDO::PARAM_INT);
			$prep->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$device= $result['SOURCE'];
			}
			return $device;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getOrderDeviceFromBillid($billid)
	{
		try
		{
			$sql="SELECT SOURCE FROM billing.ORDERS_DEVICE WHERE BILLID=:BILLID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
			$prep->execute();
			if ($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$device = $result['SOURCE'];
			}
			return $device;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function checkAppliedCoupon($id,$orderid)
	{
		try
		{
			$sql="SELECT COUPON_CODE FROM billing.ORDERS_DEVICE WHERE ID=:ID AND ORDERID=:ORDERID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ID", $id, PDO::PARAM_INT);
			$prep->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$device= $result['COUPON_CODE'];
			}
			return $device;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function updateBillingDetails($id,$orderid,$billid)
	{
		try
		{
			$sql="UPDATE billing.ORDERS_DEVICE SET BILLID=:BILLID, ENTRY_DT=NOW() WHERE ID=:ID AND ORDERID=:ORDERID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ID", $id, PDO::PARAM_INT);
			$prep->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
			$prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
			$prep->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getGatewayAndChannelWiseData($start_dt, $end_dt, $cur_type, $table, $condition)
	{
		try
		{
			if($cur_type=='RS' || $cur_type=='DOL') 
			{
				$sql="SELECT SUM(pd.AMOUNT*(1-TAX_RATE/100)) as AMOUNT, ord.GATEWAY, odd.SOURCE from billing.$table as pd, billing.PURCHASES as pur, billing.ORDERS as ord, billing.ORDERS_DEVICE as odd where pd.ENTRY_DT>=:START_DATE and pd.ENTRY_DT<=:END_DATE and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.ID=odd.ID and ord.STATUS!='R' and pd.TYPE=:CUR_TYPE GROUP BY ord.GATEWAY, odd.SOURCE";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":CUR_TYPE", $cur_type, PDO::PARAM_STR);
			}
			else
			{
				$sql="SELECT SUM(if(pd.TYPE='DOL',pd.AMOUNT*DOL_CONV_RATE*(1-TAX_RATE/100),pd.AMOUNT*(1-TAX_RATE/100))) as AMOUNT, ord.GATEWAY, odd.SOURCE from billing.$table as pd, billing.PURCHASES as pur, billing.ORDERS as ord, billing.ORDERS_DEVICE as odd where pd.ENTRY_DT>=:START_DATE and pd.ENTRY_DT<=:END_DATE and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.ID=odd.ID and ord.STATUS!='R' GROUP BY ord.GATEWAY, odd.SOURCE";				
				$prep=$this->db->prepare($sql);
			}

			$prep->bindValue(":START_DATE", $start_dt, PDO::PARAM_STR);
			$prep->bindValue(":END_DATE", $end_dt, PDO::PARAM_STR);
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				if($row['GATEWAY'] && $row['SOURCE'])
					$res[$row['GATEWAY']][$row['SOURCE']] = round($row['AMOUNT']);
			}
			return $res;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getGatewayWiseData($start_dt, $end_dt, $cur_type,$table,$condition)
	{
		try
		{
			if($cur_type=='RS' || $cur_type=='DOL') 
			{
				$sql="SELECT SUM(pd.AMOUNT*(1-TAX_RATE/100)) as AMOUNT, ord.GATEWAY from billing.$table as pd, billing.PURCHASES as pur, billing.ORDERS as ord where pd.ENTRY_DT>=:START_DATE and pd.ENTRY_DT<=:END_DATE and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.STATUS!='R' and pd.TYPE=:CUR_TYPE GROUP BY ord.GATEWAY";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":CUR_TYPE", $cur_type, PDO::PARAM_STR);
			}
			else
			{
				$sql="SELECT SUM(if(pd.TYPE='DOL',pd.AMOUNT*DOL_CONV_RATE*(1-TAX_RATE/100),pd.AMOUNT*(1-TAX_RATE/100))) as AMOUNT, ord.GATEWAY from billing.$table as pd, billing.PURCHASES as pur, billing.ORDERS as ord where pd.ENTRY_DT>=:START_DATE and pd.ENTRY_DT<=:END_DATE and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.STATUS!='R' GROUP BY ord.GATEWAY";				
				$prep=$this->db->prepare($sql);
			}

			$prep->bindValue(":START_DATE", $start_dt, PDO::PARAM_STR);
			$prep->bindValue(":END_DATE", $end_dt, PDO::PARAM_STR);
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				if($row['GATEWAY'])
					$res[$row['GATEWAY']]['desktop'] = round($row['AMOUNT']);
			}
			return $res;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getPaymentSourceFromBillid($billid)
	{
		try
		{
			$sql="SELECT SOURCE FROM billing.ORDERS_DEVICE WHERE BILLID=:BILLID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
			$prep->execute();
			if($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				return $row['SOURCE'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getPaymentSourceFromBillidStr($billidStr)
	{
		try
		{
			$sql="SELECT ID,ORDERID,BILLID,SOURCE FROM billing.ORDERS_DEVICE WHERE BILLID IN ($billidStr)";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{	
				$result[$row['BILLID']] = $row;
			}
			return $result;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getApplePayOrdersForOrderIds($idArr)
	{
		try
		{
			if(empty($idArr)){
				return NULL;
			} else {
				$idStr = implode(",",$idArr);
				$sql="SELECT * FROM billing.ORDERS_DEVICE WHERE ID IN ($idStr)";
				$prep=$this->db->prepare($sql);
				$prep->execute();
				while($row = $prep->fetch(PDO::FETCH_ASSOC))
				{	
					$result[$row['BILLID']] = $row;
				}
				return $result;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

}
?>
