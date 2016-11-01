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

  [php symfony CALDataHousekeeping|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here

  $CALHousekeepingObject = new MIS_CA_LAYER_TRACK;
  //Provide Layer IDs in the arrays mentioned below 
  $unlimitedCALArray = array(3,5,7,6,8,9,10);
  $limitedCALArray = array(1,2,4,11,12);
  //The duration for removing older data has been provided in months.
  $durationForUnlimitedCAL = 3;
  $durationForLimitedCAL = 6;

  $CALHousekeepingObject->removeLimitedCAL($limitedCALArray,$durationForLimitedCAL); 
  $CALHousekeepingObject->removeUnlimitedCAL($unlimitedCALArray,$durationForUnlimitedCAL);

  }
}
