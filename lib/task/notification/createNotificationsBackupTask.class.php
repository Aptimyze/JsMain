<?php
/* This class runs a cron to transfer scheduled notifications into BROWSER_SCHEDULED_NOTIFICATION_BACKUP table which have been acknowledged by channel or are pending for acknowledgement since TTL back or last 1 day if no TTL into backup table from BROWSER_NOTIFICATION table*/


class createNotificationsBackupTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'notification';
		$this->name             = 'createNotificationsBackup';
		$this->briefDescription = 'transfer scheduled notifications which have been acknowledged by channel or are pending for acknowledgement since TTL back or last 1 day if no TTL into backup table from BROWSER_NOTIFICATION table';
		$this->detailedDescription = <<<EOF
		The [createNotificationsBackup|INFO] task does things.
		Call it with:
		[php symfony notification:createNotificationsBackup|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
		$backupObj = new MOBILE_API_BROWSER_NOTIFICATION_BACKUP();
		$sourceSlaveObj = new MOBILE_API_BROWSER_NOTIFICATION("newjs_masterRep");
		$sourceMasterObj = new MOBILE_API_BROWSER_NOTIFICATION();

		//count of notifications picked from BROWSER_NOTIFICATION and backed up at a time
		$limit = BrowserNotificationEnums::$backupNotificationsCountLimit; 
		$offset = 0;
		
		$count = $sourceSlaveObj->getAllRowsCount();
		$curDate = date('Y-m-d H:i:s');
		if($count>0)
		{
			for($i=$offset;$i<=$count;$i=$i+$limit)
			{
				$idArr = array();
				//get notifications to be backed up in backup table
				$data = array();
				$data = $sourceSlaveObj->getBackupEligibleNotifications("*",$limit,$i);
				//print_r($data);
				if(is_array($data) && count($data)>0)
					foreach ($data as $key => $value) 
					{
						$backupToBeDone = false;
						if($value['SENT_TO_CHANNEL']=='Y')
							$backupToBeDone = true;
						else
						{
							if(($value['TTL'] && $value['ENTRY_DT']<date("Y-m-d H:i:s",strtotime($curDate)-$value['TTL'])) || (!$value['TTL'] && $value['ENTRY_DT']<date("Y-m-d H:i:s",strtotime("- 1 day"))))
							{
								$backupToBeDone=true;
							}
						}
						if($backupToBeDone==true)
						{
							//echo "........backup...........";
							$idArr[$key] = $value['ID'];
						}
						else
						{ 
							//echo "----no---";
							unset($data[$key]);
						}
					}
				else
				{
					//if no more backup eligible notifications left in source table, then stop
					break;
				}
				
				//delete backed up notifications from source table
				if(count($idArr)>0)
				{
					//print_r($idArr);
					$backupObj->insertBackupNotification($data);
					$sourceMasterObj->deleteNotifications($idArr);
				}

			}
		}
		unset($sourceMasterObj);
		unset($sourceSlaveObj);
		unset($idArr);
		unset($backup);
		unset($data);
	}
}
