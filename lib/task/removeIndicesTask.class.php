<?php

/*
This Cron is used to delete the indices which are created by Elastic Search and are no longer in use.
@author :  Ayush Sethi
@dated  :  9 Oct 2016
*/


class removeIndicesTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'IndexRotation';
    $this->name             = 'removeIndices';
    $this->briefDescription = 'This cron is used to remove the indices which are created by kibana ';
    $this->detailedDescription = <<<EOF
The [removeIndices|INFO] task does things.
Call it with:

  [php symfony removeIndices|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
    //mention the number of hours before the current time whose data is not needed anymore. Based on the number of hours enetered, this will delete the index associated with the date at that time. SO USE THIS CAREFULLY !
    
    $hoursToGoBack = 24;
    $date = new DateTime(date("Y-m-d", strtotime('-'.$hoursToGoBack.' hours')));
    $date = $date->format('Y.m.d');
    $path = '/home/ayush/Desktop/logsForCompress/';
    $indexName = 'kibanaCompressing-'.$date;
    $commandToRun = 'rm '.$path.$indexName;
    exec($commandToRun);
  }
}
