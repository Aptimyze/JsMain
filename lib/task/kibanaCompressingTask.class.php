<?php

/*
This Cron is used to compress the data which is stored in the indices of Elastic Search.
@author :  Ayush Sethi
@dated  :  9 Oct 2016
*/

class kibanaCompressingTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'indexCompressor';
    $this->name             = 'kibanaCompressing';
    $this->addArguments(array(new sfCommandArgument('hours',sfCommandArgument::OPTIONAL,'this parameter is added to include the number of hours for which the cron has to be made',$hours = "72")));   
    $this->briefDescription = 'This cron is used to compress data which is extracted from indices of the elastic search.';
    $this->detailedDescription = <<<EOF
The [kibanaCompressing|INFO] task does things.
Call it with:

  [php symfony kibanaCompressing|INFO]
EOF;
  }
    // add your code here
    protected function execute($arguments = array(), $options = array())
    {   
      //Path of Folder which will store all Data Files.
        $time = date("Y/m/d H:i:s"); 
        //This is done to convert the time into epoch time system such that it can be fed into Kibana for proper pushing and fetching.
        $out = new DateTime($time);
        print_r($out,1);
        $time = $out->date;
        print_r($time,1); 
        $time = strtotime($time);

      $dirPath = '/home/ayush/Desktop/logsForCompress';
                if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }

      $hoursNow = $arguments[hours];
      $hoursNow = 48;
      for($i=0 ; $i <= $arguments[hours] ; $i++)
      {
        $hoursNow = $i;
        $date = new DateTime(date("Y-m-d", strtotime('-'.$hoursNow.' hours')));
        $date = $date->format('Y.m.d');
        $elkServer = 'localhost';
        $elkPort = '9200';
        $indexName = 'filebeat-*';
        $query = '_search';
        $urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
        $ltHour = $hoursNow + 1;
        
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
                        "field" => "channelName",
                        "size" => 1000
                    ]
                ]
            ]
        ];

        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        print_r($arrResponse); die("on");
        $arrChannels = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {   die("in");
            //$arrChannels[$module['key']] = $module['doc_count'];
            $channelName = $arrChannels[$module['key']];
               $out = $module['doc_count']; 
               die($out);
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { die("in here");
                $arrChannels['time'] = $time;
                $arrChannels['channelName'] = $channelName;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrChannels)."\n");
                fclose($fileResource);
            }
        }

        die("job done");

              
        
         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                                        ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'Domain',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrDomain = array();
         $arrDomain['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrDomain[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrDomain) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrDomain)."\n");
        fclose($fileResource);
           }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                       "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'TypeOfError',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $TypeOfError = array();
        $TypeOfError['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $TypeOfError[$module['key']] = $module['doc_count'];
        }
 
        if(sizeof($TypeOfError) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($TypeOfError)."\n");
        fclose($fileResource);
           }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                  "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'beat.name',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrHostname = array();
        $arrHostname['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrHostname[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrHostname) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrHostname)."\n");
        fclose($fileResource);
        }
         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'moduleName',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrmoduleName = array();
        $arrmoduleName['time'] = $time;

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrmoduleName[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrmoduleName) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrmoduleName)."\n");
        fclose($fileResource);
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'logtype',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrLogType = array();
        $arrLogType['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrLogType[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrLogType) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrLogType)."\n");
        fclose($fileResource);
             }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'ApiVersion',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrApiVersion = array();
        $arrApiVersion['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrApiVersion[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrApiVersion) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrApiVersion)."\n");
        fclose($fileResource);
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'Action Name',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrActionName = array();
        $arrActionName['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrActionName[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrActionName) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrActionName)."\n");
        fclose($fileResource);
        }
         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "gte" => "now-{$ltHour}h",
                        "lte" => "now-{$hoursNow}h"                    
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'Request Uri',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrrequestURI = array();
        $arrrequestURIs['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrrequestURI[$module['key']] = $module['doc_count'];
        }
        if(sizeof($arrrequestURI) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrrequestURI)."\n");
        fclose($fileResource);
        }
   /*             
        $finalArrayToWrite['arr'] = ($arrChannels);
        $finalArrayToWrite[] = ($arrDomain);
        $finalArrayToWrite[] = ($TypeOfError);
        $finalArrayToWrite[] = ($arrHostname);
        $finalArrayToWrite[] = ($arrmoduleName);
        $finalArrayToWrite[] = ($arrApiVersion);
        $finalArrayToWrite[] = ($arrActionName);
        $finalArrayToWrite[] = ($arrrequestURI);
        $finalArrayToWrite[] = ($arrLogType); 

              
        if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }


        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($finalArrayToWrite)."\n");
        fclose($fileResource);

        */
      }
    }
}