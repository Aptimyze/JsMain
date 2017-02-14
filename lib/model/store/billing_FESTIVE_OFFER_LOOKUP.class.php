<?php

class billing_FESTIVE_OFFER_LOOKUP extends TABLE {

	public function __construct($dbname = "") {
		parent::__construct($dbname);
	}

	public function retrieveCurrentLookupTable()
	{
		try
		{
			$sql = "SELECT * FROM billing.FESTIVE_OFFER_LOOKUP";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[$row['SERVICEID']] = array('DISCOUNT_DURATION'=>$row['DISCOUNT_DURATION'],
					'OFFERED_SERVICEID'=>$row['OFFERED_SERVICEID'],
					'DISCOUNT_PERCENT'=>$row['DISCOUNT_PERCENT']);
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	public function updatePercentageDiscount($serviceid,$perc){
		try
		{
			$sql = "UPDATE billing.FESTIVE_OFFER_LOOKUP SET DISCOUNT_PERCENT=:DISCOUNT_PERCENT WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DISCOUNT_PERCENT", $perc, PDO::PARAM_INT);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function updateDurationDiscount($serviceid,$months,$updatedSid){
		try
		{
			$sql = "UPDATE billing.FESTIVE_OFFER_LOOKUP SET DISCOUNT_DURATION=:DISCOUNT_DURATION, OFFERED_SERVICEID=:OFFERED_SERVICEID WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DISCOUNT_DURATION", $months, PDO::PARAM_INT);
			$res->bindValue(":OFFERED_SERVICEID", $updatedSid, PDO::PARAM_STR);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function fetchServiceId($offered_serviceid)
	{
		try
		{
			$sql = "SELECT SERVICEID FROM billing.FESTIVE_OFFER_LOOKUP WHERE OFFERED_SERVICEID=:OFFERED_SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":OFFERED_SERVICEID", $offered_serviceid, PDO::PARAM_STR);			
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $row['SERVICEID'];
	}	

	public function getMaxFestiveDiscountPercentage(){
		try
		{
			$sql = "SELECT MAX(DISCOUNT_PERCENT) as MAX_PERC FROM billing.FESTIVE_OFFER_LOOKUP";
			$res = $this->db->prepare($sql);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output = $row['MAX_PERC'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;		
	}

	public function getPercDiscountOnService($serviceid){
		try
		{
			$sql = "SELECT DISCOUNT_PERCENT FROM billing.FESTIVE_OFFER_LOOKUP WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output = $row['DISCOUNT_PERCENT'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;			
	}

	public function getDurationDiscountOnService($serviceid){
		try
		{
			$sql = "SELECT DISCOUNT_DURATION FROM billing.FESTIVE_OFFER_LOOKUP WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output = $row['DISCOUNT_DURATION'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;			
	}

	public function checkIfFestApplicable($serviceid){
		try
		{
			$sql = "SELECT SERVICEID FROM billing.FESTIVE_OFFER_LOOKUP WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)){
				return true;
			} else {
				return false;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function fetchOfferedServiceId($serviceid)
	{
		try
		{
			$sql = "SELECT OFFERED_SERVICEID FROM billing.FESTIVE_OFFER_LOOKUP WHERE SERVICEID=:SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);			
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $row['OFFERED_SERVICEID'];
	}
	public function fetchReverseOfferedServiceId($serviceid)
	{
		try
		{
			$sql = "SELECT SERVICEID FROM billing.FESTIVE_OFFER_LOOKUP WHERE OFFERED_SERVICEID=:OFFERED_SERVICEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":OFFERED_SERVICEID", $serviceid, PDO::PARAM_STR);			
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $row['SERVICEID'];
	}		
	public function getFestiveDiscountPercentage($serviceStr){
		try
		{
			$sql = "SELECT SERVICEID, DISCOUNT_PERCENT FROM billing.FESTIVE_OFFER_LOOKUP WHERE SERVICEID IN (".$serviceStr.")";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[$row['SERVICEID']] = $row['DISCOUNT_PERCENT'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;		
	}

}

