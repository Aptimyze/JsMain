<?php

/*
This Cron is used to compress the data which is stored in the indices of Elastic Search. 
@author :  Ayush Sethi
@dated  :  9 Oct 2016

 * <code>
 * To execute : $ php symfony Kibana:indicesCompressor 24
 * The parameter passed in above query is the time in hours. 
 * example  $ php symfony Kibana:indicesCompressor 
 * By default the time (in hours) whose data is to be fetched 
 * </code>
*/

class kibanaCompressingTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'Kibana';
    $this->name             = 'indicesCompressor';
    //This is the path to ELK server
    $this->elkServer = 'localhost';
    //This is the port number where Elastic Search is running
    $this->elkPort = '9200';
    //This is the index name which will be searched for getting the records.
    $this->indexName = 'filebeat-*';
    //This is the type of query which will be used to query elastic search.
    $this->query = '_search'; 

    $this->addArguments(array(new sfCommandArgument('hours',sfCommandArgument::OPTIONAL,'this parameter is added to include the number of hours for which the cron has to be made',$hours = "24")));   
    $this->briefDescription = 'This cron is used to compress data which is extracted from indices of the elastic search.';
    $this->detailedDescription = <<<EOF
The [kibanaCompressing|INFO] task does things.
Call it with:

  [php symfony Kibana:indicesCompressor]
EOF;
  }
    // add your code here
    protected function execute($arguments = array(), $options = array())
    {   
      //Path of Folder which will store all Data Files.
      $dirPath = '/home/ayush/Desktop/logsForCompress';
                if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }

        $fieldsToQuery = array("channelName",'Domain','typeOfError','beat.name','moduleName','logType','apiVersion','REQUEST_URI','actionName');

      $hoursNow = $arguments['hours'];
      for($j=0 ; $j <9 ; $j++){
      for($i=0 ; $i <= $hoursNow ; $i++)
      {
        $hoursNow = $i; 
        $urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
        $ltHour = $hoursNow + 1;

        $time = time('Y-m-d H:i:s',strtotime('-'.$hoursNow.' hours'));         
      
        $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    ]
                ]
            ],
            "aggs" => [
                "modules" => [
                    "terms" => [
                        "field" =>$fieldsToQuery[$j] ,
                        "size" => 1000
                    ]
                ]
            ]
        ];

        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrResult = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {  

            $fieldName = $module['key'];
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrResult['time'] = $time;
                $arrResult['fieldValue'] = $fieldName;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrResult)."\n");
                fclose($fileResource);
            }
        }
     }
    }

}
}