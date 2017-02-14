<?php
class billing_COMPONENTS extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function getDurationForServiceArrWithoutJoin($serviceid)
    {
        if(empty($serviceid)){
            throw new jsException("Empty serviceid passed in getDurationForServiceArrWithoutJoin in billing_COMPONENTS.class.php");    
        }
        try
        {
        	if(is_array($serviceid)){
        		$serviceIdArr = "'".implode("','",$serviceid)."'";
        		$sql="SELECT SQL_CACHE COMPID,DURATION FROM billing.COMPONENTS WHERE COMPID IN ($serviceIdArr)";
        	} else {
        		$sql="SELECT SQL_CACHE DURATION FROM billing.COMPONENTS WHERE COMPID = :SERVICEID";
        	}
            $prep=$this->db->prepare($sql);
            if(!is_array($serviceid)){
            	$prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            }
            $prep->execute();
            if(!is_array($serviceid)){
	            if($result = $prep->fetch(PDO::FETCH_ASSOC))
	            {
	                $output = $result['DURATION'];
	            }
        	} else {
        		while($result = $prep->fetch(PDO::FETCH_ASSOC))
	            {
	                $output[$result['COMPID']] = $result['DURATION'];
	            }
        	}
            return $output;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getDurationForServiceArrWithJoin($serviceid)
    {
        if(empty($serviceid)){
            throw new jsException("Empty serviceid passed in getDurationForServiceArrWithJoin in billing_COMPONENTS.class.php");   
        }
        try
        {
        	if(is_array($serviceid)){
        		$serviceIdArr = "'".implode("','",$serviceid)."'";
        		$sql="SELECT SQL_CACHE MAX(c.DURATION) as DURATION, s.SERVICEID AS SERVICEID FROM billing.SERVICES s, billing.COMPONENTS c, billing.PACK_COMPONENTS pc WHERE s.PACKID = pc.PACKID AND pc.COMPID=c.COMPID AND s.SERVICEID IN ($serviceIdArr) GROUP BY s.SERVICEID";
        	} else {
            	$sql="SELECT SQL_CACHE MAX(c.DURATION) as DURATION, s.SERVICEID AS SERVICEID FROM billing.SERVICES s, billing.COMPONENTS c, billing.PACK_COMPONENTS pc WHERE s.PACKID = pc.PACKID AND pc.COMPID=c.COMPID AND s.SERVICEID = :SERVICEID GROUP BY s.SERVICEID";
        	}
            $prep=$this->db->prepare($sql);
            if(!is_array($serviceid)) {
            	$prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            }
            $prep->execute();
            if(!is_array($serviceid)){
	            if($result = $prep->fetch(PDO::FETCH_ASSOC))
	            {
	                $output = $result['DURATION'];
	            }
        	} else {
        		while($result = $prep->fetch(PDO::FETCH_ASSOC))
	            {
	                $output[$result['SERVICEID']] = $result['DURATION'];
	            }
        	}
            return $output;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getRights($serviceid)
    {
        if(empty($serviceid)){
            throw new jsException("Empty serviceid passed in getRights in billing_COMPONENTS.class.php");   
        }
        try
        {
            $sql="SELECT SQL_CACHE c.RIGHTS AS RIGHTS FROM billing.SERVICES a, billing.COMPONENTS c WHERE a.PACKAGE = 'N' AND a.ADDON = 'Y' AND a.COMPID = c.COMPID AND a.SERVICEID = :SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $rights = $result['RIGHTS'];
            }
            return $rights;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getPackRights($serviceid)
    {
        if(empty($serviceid)){
            throw new jsException("Empty serviceid passed in getPackRights in billing_COMPONENTS.class.php");   
        }
        try
        {
            $sql="SELECT SQL_CACHE c.RIGHTS AS RIGHTS FROM billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c WHERE a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = :SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $rights[] = $result['RIGHTS'];
            }
            $rights = implode(',',$rights);
            return $rights;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getPackServices($serviceid){
        if(empty($serviceid)){
            throw new jsException("Empty serviceid passed in getPackServices in billing_COMPONENTS.class.php"); 
        }
        try{
            $sql = "SELECT SQL_CACHE b.COMPID as COMPID from billing.SERVICES a, billing.PACK_COMPONENTS b WHERE a.PACKID = b.PACKID AND a.SERVICEID = :SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $comp_ar[] = $result['COMPID'];
            }
            return $comp_ar;
        } 
        catch(Exception $e)
        {
            throw new jsException($e);
            
        }
    }

    public function getServiceDurationData(){
        try{
            $sql = "SELECT * FROM billing.COMPONENTS ORDER BY DURATION";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output[] = $result;
            }
            return $output;
        }
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getServiceCountData(){
        try{
            $sql = "SELECT ACC_COUNT,RIGHTS FROM billing.COMPONENTS WHERE RIGHTS='I' ORDER BY ACC_COUNT";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output[] = $result;
            }
            return $output;
        }
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getServiceType($serviceid){
        if(empty($serviceid)){
            throw new jsException("Serviceid Blank passed in getServiceType in billing_COMPONENTS.class.php");
        }
        try{
            $sql = "SELECT TYPE FROM billing.COMPONENTS WHERE COMPID=:SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output = $result['TYPE'];
            }
            return $output;
        }
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getAccCount($serviceid){
        if(empty($serviceid)){
            throw new jsException("Serviceid Blank passed in getAccCount in billing_COMPONENTS.class.php");
        }
        try{
            $sql = "SELECT ACC_COUNT FROM billing.COMPONENTS WHERE COMPID=:SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output = $result['ACC_COUNT'];
            }
            return $output;
        }
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getDurationRightsForServiceDetails($serviceid, $package) {
        try {
        	if($package == "Y"){
	            $sql = "Select c.DURATION,c.RIGHTS from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = :SERVICEID";
	            $resSelectDetail = $this->db->prepare($sql);
	            $resSelectDetail->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
	            $resSelectDetail->execute();
	            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
	            	$output[] = $rowSelectDetail;
	            }
	        } else {
	        	$sql = "Select c.DURATION,c.RIGHTS from billing.SERVICES a, billing.COMPONENTS c where c.COMPID = a.COMPID AND a.SERVICEID = :SERVICEID";
	            $resSelectDetail = $this->db->prepare($sql);
	            $resSelectDetail->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
	            $resSelectDetail->execute();
	            if ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
	            	$output = $rowSelectDetail;
	            }
	        }
            return $output;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
}
?>
