<?php

/**
 * This task arhives queries in MIS.SEARCHQUERY TABLE for all data except 
 * the past 30 days starting from the date this cron is scheduled.
 * This was done to reduce the the processing time for crons which rely on data coming
 * from this table.
 */

class searchQueryArchivingTask extends sfBaseTask
{
	protected function configure()
	{

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
			));

		$this->namespace        = 'archiving';
		$this->name             = 'searchQueryArchiving';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
		The [archiving|INFO] task does things.
		Call it with:
		[php symfony archiving:searchQueryArchiving|INFO]
EOF;

	}

	protected function execute($arguments = array(), $options = array())
	{	
		// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		// Set date past which all data is to be archived
		$startDt = date('Y-m-d 00:00:00', strtotime('today - 15 days'));
		include_once(JsConstants::$docRoot."/profile/connect_db.php");

		//  Slave connection
		$misSearchquerySlaveObj = new MIS_SEARCHQUERY('newjs_slave');
		//  Master connection
                $misSearchqueryObj = new MIS_SEARCHQUERY('newjs_masterDDL');

		$startingID = $misSearchquerySlaveObj->getMinID();
		$lastID = $misSearchquerySlaveObj->getIdForCorrespondingDateTime($startDt);
		
		//  Transfer records 
		if(!empty($startingID) && !empty($lastID)) {
			$misSearchqueryObj->transferRecordsToTempArchivingTable($startingID, $lastID);
			// Remove Transferred Records
			$misSearchqueryObj->removeArchivedRecords($startingID, $lastID);
		}

		// Finally check if temp table needs archiving
		$date = $misSearchquerySlaveObj->getFirstInsertedRecordInTempTableDate();
		if(strtotime($date) < strtotime(date('Y-m-d 00:00:00', strtotime('today - 90 days')))) {
			$newName = "SEARCHQUERY_".date('d_M_Y');
			$misSearchqueryObj->renameTempTableForArchiving($newName);
			$misSearchqueryObj->createTempArchivingTable();
		}	
		unset($misSearchqueryObj);
		unset($misSearchquerySlaveObj);
	}
}
