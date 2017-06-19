<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class TrackingMisDataCollectionTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'tracking';
    $this->name             = 'TrackingMisDataCollection';
    $this->briefDescription = 'collection of data from responsetracking table to built data for mis daily basis, so as to avoid direct queries to table for mis';
    $this->detailedDescription = <<<EOF
      The [TrackingMisDataCollection|INFO] task collects data from responsetracking table and put it the summarized daily data in other table for mis , so as to avoid direct queries to table.
      Call it with:

      [php symfony tracking:TrackingMisDataCollection] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	$this->date = date('Y-m-d',mktime(0,0,0,date('m'),date("d")-1,date("Y")));
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$trackingData = $this->getSummarizedContactTrackingData();
	if(is_array($trackingData))
		$this->storeSummarizedContactTrackingData($trackingData);
  }
/*
function to get the summarized data from all shards, query on MIS.RESPONSETRACKING and return the data in proper format
*/
  private function getSummarizedContactTrackingData()
  {
	$shard        = JsDbSharding::getShardList();
	$completeTrackingData = array();
	foreach ($shard as $key => $dbName) 
	{
		$responseTrackingObj = new MIS_RESPONSETRACKING($dbName);
		$trackingData = $responseTrackingObj->getSummarizedContactTrackingData($this->date);
		if($key==0)
			$completeTrackingData = $trackingData;
		else
		{
			if(is_array($trackingData))
			{
				foreach($trackingData as $k1=>$v1)
					foreach($v1 as $k2=>$v2)
						$completeTrackingData[$k1][$k2]+=$v2;
			}
		}
		unset($trackingData);
		unset($responseTrackingObj);
	}
	$i=0;
	foreach($completeTrackingData as $k1=>$v1)
	{
		foreach($v1 as $k2=>$v2)
		{	
			$finalData[$i]['TRACKING_STRING']=$k1;
			$finalData[$i]['CONTACT_TYPE']=$k2;
			$finalData[$i]['COUNT']=$v2;
			$i++;
		}
	}
	return $finalData;
  }
/*
function to store the summarzied data to the mis temp table, mailny insert query in MIS.SUMMARY_RESPONSE_TRACKING
*/
  private function storeSummarizedContactTrackingData($trackingData)
  {
	$contactTrackingObj = new MIS_SUMMARY_RESPONSE_TRACKING;
	//failure check to remove entering duplicate enteries in case of rerun.
	$contactTrackingObj->deleteSummarizedContactTrackingData($this->date);
	$contactTrackingObj->storeSummarizedContactTrackingData($trackingData,$this->date);
  }
}
