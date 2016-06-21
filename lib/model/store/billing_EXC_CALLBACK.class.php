<?php

class billing_EXC_CALLBACK extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function addRecord($profileid='',$phoneNo='',$email='', $device=NULL, $channel=NULL, $callbackSource=NULL)
    {
        try
        {
            $entryDt =date("Y-m-d H:i:s",time());
            if($profileid){
                $phoneNo='';
                $email='';
            }
            else
                $profileid='';
            
            $sql="INSERT INTO billing.EXC_CALLBACK(PROFILEID,EMAIL,PHONE_NUMBER,ENTRY_DT,DEVICE,CHANNEL,CALLBACK_SOURCE) VALUES(:PROFILEID,:EMAIL,:PHONE_NUMBER,:ENTRY_DT,:DEVICE,:CHANNEL,:CALLBACK_SOURCE)";
            $row = $this->db->prepare($sql);
            $row->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $row->bindValue(":EMAIL",$email, PDO::PARAM_STR);
            $row->bindValue(":PHONE_NUMBER",$phoneNo, PDO::PARAM_INT);
            $row->bindValue(":ENTRY_DT",$entryDt, PDO::PARAM_STR);
            $row->bindValue(":DEVICE",$device, PDO::PARAM_STR);
            $row->bindValue(":CHANNEL",$channel, PDO::PARAM_STR);
            $row->bindValue(":CALLBACK_SOURCE",$callbackSource, PDO::PARAM_STR);
            $row->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function getLatestEntryDate($profileid='',$email='')
    {
        try{
            $sql ="select ENTRY_DT from billing.EXC_CALLBACK where SERVICEID=''";
            if($profileid)
                $sql .=" AND PROFILEID=:PROFILEID";
            else
                $sql .=" AND EMAIL=:EMAIL";
            $sql .=" ORDER BY ENTRY_DT DESC LIMIT 1";   
            $row = $this->db->prepare($sql);
            if($profileid)
                $row->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
            else
                $row->bindValue(":EMAIL",$email, PDO::PARAM_STR);
            $row->execute();
            $result=$row->fetch(PDO::FETCH_ASSOC);
            if($result)
                return $result['ENTRY_DT'];
            return;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getWebmasterLeads($startDt,$endDt)
    {
        try{
            $profileidArr =array();
            $sql ="select distinct PROFILEID from billing.EXC_CALLBACK where ENTRY_DT>=:START_DT AND ENTRY_DT<:END_DT AND SERVICEID NOT LIKE 'X%' ORDER BY ENTRY_DT DESC";
            $row = $this->db->prepare($sql);
            $row->bindValue(":START_DT",$startDt, PDO::PARAM_STR);
            $row->bindValue(":END_DT",$endDt, PDO::PARAM_STR);
            $row->execute();
            while($result=$row->fetch(PDO::FETCH_ASSOC)){
		if($result['PROFILEID']>0)
	                $profileidArr[]['PROFILEID'] =$result['PROFILEID'];
            }
            return $profileidArr;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function insertCallbackWithSelectedService($phoneNo, $email, $jsSelectd, $profileid='', $device=NULL, $channel=NULL, $callbackSource=NULL)
    {
        $date = DATE("Y-m-d H:i:s");
        try{
            $sql ="INSERT INTO billing.EXC_CALLBACK (PHONE_NUMBER,EMAIL,ENTRY_DT,SERVICEID,PROFILEID,DEVICE,CHANNEL,CALLBACK_SOURCE) VALUES (:PHONE_NUMBER,:EMAIL,:ENTRY_DT,:JSSEL,:PROFILEID,:DEVICE,:CHANNEL,:CALLBACK_SOURCE)";
            $row = $this->db->prepare($sql);
            $row->bindValue(":ENTRY_DT",$date, PDO::PARAM_STR);
            $row->bindValue(":EMAIL",$email, PDO::PARAM_STR);
            $row->bindValue(":JSSEL",$jsSelectd, PDO::PARAM_STR);
            $row->bindValue(":PHONE_NUMBER",$phoneNo, PDO::PARAM_STR);
            $row->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $row->bindValue(":DEVICE",$device, PDO::PARAM_STR);
            $row->bindValue(":CHANNEL",$channel, PDO::PARAM_STR);
            $row->bindValue(":CALLBACK_SOURCE",$callbackSource, PDO::PARAM_STR);
            $row->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    
    public function getWebmasterLeadsForExclusive($startDt, $endDt)
    {
        try{
            $profileidArr =array();
            $sql ="select distinct PROFILEID from billing.EXC_CALLBACK where ENTRY_DT>=:START_DT AND ENTRY_DT<:END_DT AND SERVICEID LIKE 'X%' ORDER BY ENTRY_DT DESC";
            $row = $this->db->prepare($sql);
            $row->bindValue(":START_DT",$startDt, PDO::PARAM_STR);
            $row->bindValue(":END_DT",$endDt, PDO::PARAM_STR);
            $row->execute();
            while($result=$row->fetch(PDO::FETCH_ASSOC)){
		if($result['PROFILEID']>0)
	                $profileidArr[]['PROFILEID'] =$result['PROFILEID'];
            }
            return $profileidArr;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
}
