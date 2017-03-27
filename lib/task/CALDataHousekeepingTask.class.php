<?php

class CALDataHousekeepingTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = 'cleanup';
    $this->name             = 'CALDataHousekeeping';
    $this->briefDescription = 'Housekeeping of CAL Data';
    $this->detailedDescription = <<<EOF
The [CALDataHousekeeping|INFO] task is used to clear data for CALs which is no longer in use. For CALs with limited appearances , we delete the data older than 6 months and for CALs with unlimited appearances , we delete the data older than 3 months.Still the duration is kept configurable for future use.

Call it with:

  [php symfony cleanup:CALDataHousekeeping]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here

  $CALHousekeepingObject = new MIS_CA_LAYER_TRACK;
  //Provide Layer IDs in the arrays mentioned below 

 for ($i=1;;$i++)
 {
 $layer = CriticalActionLayerDataDisplay::getDataValue($i,'','');
 if($layer)
 { 
 if($layer['UNLIMITED'] == 'Y')
 $beforeDate = date('Y-m-d',strtotime('-9 months'));
 else
 $beforeDate = date('Y-m-d',strtotime('-9 months'));
 $CALHousekeepingObject->truncateForUserAndLayer('',$i,$beforeDate);
 }
 else break;
 }

  }
}
