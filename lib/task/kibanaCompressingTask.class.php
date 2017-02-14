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
    $this->elkServer = 'http://172.10.18.66';
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
      $dirPath = '/data/applogs/CompressData';
                if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }

        $fieldsToQuery = array("channelName",'Domain','typeOfError','beat.name','moduleName','logType','apiVersion','REQUEST_URI','actionName');

      $hoursNow = $arguments['hours'];

      for($j=0 ; $j <9 ; $j++){
      for($i=0 ; $i <= $hoursNow ; $i++)
      { 
        $greaterThan = $i+1;
        $lessThan = $i;
        $urlToHit = $this->elkServer.':'.$this->elkPort.'/'.$this->indexName.'/'.$this->query;
        $time = time('Y-m-d H:i:s',strtotime('-'.$lessThan.' hours'));
      
        $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$greaterThan}h",
                        "lte" => "now-{$lessThan}h"                    ]
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
        if(is_array($arrResponse) && array_key_exists('aggregations',$arrResponse )){
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {  

            $fieldName = $module['key'];
            for($k = 0 ; $k < $module['doc_count'] ; $k++)
            { 
                $arrResult['time'] = $time;
                $arrResult[$fieldsToQuery[$j]] = $fieldName;
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
}
