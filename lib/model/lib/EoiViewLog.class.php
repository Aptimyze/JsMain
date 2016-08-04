<?php

class EoiViewLog{
	
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	public function getEoiViewed($viewer,$viewed)
	{
		$dbName = JsDbSharding::getShardNo($viewer);
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
        
        public function setEoiViewedForAReceiver($receiver,$filtered=0)
        {
            
            $receiverShard=JsDbSharding::getShardNo($receiver);
            $eoiSenderArray=(new newjs_CONTACTS($receiverShard))->getContactedProfiles($receiver, 'RECEIVER', array('I'),'', $filtered);
            $tempShard =  $receiverShard;
            foreach ($eoiSenderArray['I'] as $key => $value) 
            {
                $tempArray['R'] = $receiver;                
                $tempArray['S'] = $value;
                $shardArray[$tempShard][]=$tempArray;
                unset($tempArray);
                
            }
            
            foreach ($eoiSenderArray['I'] as $key => $value) 
            {
                $tempShard =  JsDbSharding::getShardNo($value);
                if($tempShard == $receiverShard) continue;
                $tempArray['R'] = $receiver;                
                $tempArray['S'] = $value;
                $shardArray[$tempShard][]=$tempArray;
                unset($tempArray);
                
            }
            for($i=0;$i<3;$i++)
            {
            $tempShard =  JsDbSharding::getShardNo($i);    
            $eoiViewLogStore = new NEWJS_EOI_VIEWED_LOG($tempShard);
            $eoiViewLogStore->insertMultiple($shardArray[$tempShard]);
            unset($eoiViewLogStore);
            }
        }
	
}	
