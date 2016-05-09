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
	
}	
