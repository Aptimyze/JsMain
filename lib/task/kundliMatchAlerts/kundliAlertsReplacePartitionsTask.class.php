<?php

class kundliAlertsReplacePartitionsTask extends sfBaseTask
{
  protected function configure()
  {   
    // // add your own options here
	$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));
    $this->namespace        = 'kundliMatchAlerts';
    $this->name             = 'kundliAlertsReplacePartitions';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [kundliAlertsReplacePartitions|INFO] task does things.
Call it with:

  [php symfony kundliMatchAlerts:kundliAlertsReplacePartitions|INFO]
EOF;
  }

  //this function gets last used partition name and 
  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
        $date = date("Y-m-d");        
        $gap= ceil($this->getNoOfDays());
        
        //kundli_alert.LOG table contains logs for data in kundli_alert
        $kundliAlertsObj = new kundli_alert_LOG();

        //LAST_ACTIVE_LOG1 table to which stores the last active partition number
        $lastActiveLogObj = new kundli_alert_LAST_ACTIVE_LOG1();

        //fetch latest partition number
        $lastPartitionName = intval($lastActiveLogObj->getLastActivePartition());
        //get Last Partition Range
        $lastPartitionRange = intval($kundliAlertsObj->getLatestPartitionRange('p'.$lastPartitionName));
        if($gap >= ($lastPartitionRange-1)){
            //drop and create partition
            $kundliAlertsObj->replacePartitions('p'.($lastPartitionName-3),'p'.($lastPartitionName+1),$lastPartitionRange+30);
            //update new partition number
            $lastActiveLogObj->updateLastActivePartition($lastPartitionName+1, $date);
        }
  }
  public function getNoOfDays()
    {
            $today=mktime(0,0,0,date("m"),date("d"),date("Y"));
            $zero=mktime(0,0,0,01,01,2005);
            $gap=($today-$zero)/(24*60*60);
            return $gap;
    }
}
