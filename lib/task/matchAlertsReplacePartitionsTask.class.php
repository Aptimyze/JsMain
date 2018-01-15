<?php

class matchAlertsReplacePartitionsTask extends sfBaseTask
{
  protected function configure()
  {   
    // // add your own options here
	$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));
    $this->namespace        = 'cron';
    $this->name             = 'matchAlertsReplacePartitions';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [matchAlertsReplacePartitions|INFO] task does things.
Call it with:

  [php symfony cron:matchAlertsReplacePartitions|INFO]
EOF;
  }

  //this function gets last used partition name and 
  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
        $date = date("Y-m-d");
        $gap= ceil($this->getNoOfDays());
        $matchAlertsObj = new matchalerts_LOG();
        $lastActiveLogObj = new matchalerts_LAST_ACTIVE_LOG();
        //fetch latest partition number
        $lastPartitionName = intval($lastActiveLogObj->getLastActivePartition());
        //get Last Partition Range
        $lastPartitionRange = intval($matchAlertsObj->getLatestPartitionRange('p'.$lastPartitionName));
        if($gap >= ($lastPartitionRange-1)){
            //drop and create partition
            $matchAlertsObj->replacePartitions('p'.($lastPartitionName-5),'p'.($lastPartitionName+1),$lastPartitionRange+6);
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
