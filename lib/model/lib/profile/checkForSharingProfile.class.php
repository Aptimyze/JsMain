<?php
class checkForSharingProfile{
	CONST mailLimit=20;
	CONST hoursLimitInSec=86400;
	public function getsendMailCriteria($profileid,$shareProfileStoreObj){
		//$shareProfileStoreObj = new PROFILE_SHARE_PROFILE;
		$data=$shareProfileStoreObj->selectData($profileid);
		if($data["COUNT"]<self::mailLimit || $data==NULL || strtotime(date("Y-m-d H:i:s"))-strtotime($data["TIME"])>self::hoursLimitInSec){
			$data["RESPONSE"]="YES";
			return ($data);
		}
		else{
			$data["RESPONSE"]="NO";
			return ($data);
		}
		
	}

	public function updateAfterEmailSend($profileid,$dateTime="",$count="",$response){
		$shareProfileStoreObj = new PROFILE_SHARE_PROFILE;
		if($count==""){
			$count=1;
			$dateTime=date("Y-m-d H:i:s"); 
			$shareProfileStoreObj->insertData($profileid,$dateTime,$count);
		}
		elseif($count<self::mailLimit && strtotime(date("Y-m-d H:i:s"))-strtotime($dateTime)<self::hoursLimitInSec)
		{
			$count=$count+1;
			$shareProfileStoreObj->updateData($profileid,"",$count);
		}
		elseif(strtotime(date("Y-m-d H:i:s"))-strtotime($dateTime)>self::hoursLimitInSec)
		{
			$count=1;
			$dateTime=date("Y-m-d H:i:s");
			$shareProfileStoreObj->updateData($profileid,$dateTime,$count);
		}

	}
}