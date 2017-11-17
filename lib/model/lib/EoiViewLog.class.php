<?php

class EoiViewLog{
	
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	public function getEoiViewed($viewer,$viewed)
	{
		$dbName = JsDbSharding::getShardNo($viewed);
		$eoiViewedLogObj = new NEWJS_EOI_VIEWED_LOG($dbName);
		$ifViewed = $eoiViewedLogObj->getEoiViewed($viewer,$viewed);
		return $ifViewed;
	}
	
	
	public function getMutipleEoiViewed($viewer,$viewed)
	{
		$dbName = JsDbSharding::getShardNo($viewed);
		$eoiViewedLogObj = new NEWJS_EOI_VIEWED_LOG($dbName);
		$viewedData = $eoiViewedLogObj->getMutipleEoiViewed($viewer,$viewed);
		return $viewedData;
	}
        
        public function setEoiViewedForAReceiver($receiver,$filtered='',$time)
        {
            
            $receiverShard=JsDbSharding::getShardNo($receiver);
			
			$condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
            $condition["WHERE"]["IN"]["RECEIVER"] = $receiver;
            $condition["WHERE"]["NOT_IN"]["SEEN"] = 'Y';
            if($filtered=="Y")
				$condition["WHERE"]["IN"]["FILTERED"] = array('J', 'Y');
			elseif($filtered=="N")
				$condition["WHERE"]["NOT_IN"]["FILTERED"] = array('J', 'Y');
            $eoiSenderArray=(new ContactsRecords())->getContactedProfileArray($receiver,$condition);
            
            foreach ($eoiSenderArray as $key => $value) 
            {
                $tempShard =  JsDbSharding::getShardNo($key);
                $tempArray['R'] = $receiver;                
                $tempArray['S'] = $key;
                $shardArray[$tempShard][]=$tempArray;
                unset($tempArray);
                
            }
            for($i=0;$i<3;$i++)
            {
            
            $tempShard =  JsDbSharding::getShardNo($i);    
            if(!count($shardArray[$tempShard])) continue;
            $eoiViewLogStore = new NEWJS_EOI_VIEWED_LOG($tempShard);
            $eoiViewLogStore->insertMultiple($shardArray[$tempShard],$time);
            unset($eoiViewLogStore);
            }
        }
	
}	
