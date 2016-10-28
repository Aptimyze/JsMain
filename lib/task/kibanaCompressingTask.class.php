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
        
        //This is done to convert the time into epoch time system such that it can be fed into Kibana for proper pushing and fetching.
     /*   $time = date("Y/m/d H:i:s"); 
        $out = new DateTime($time);
        print_r($out,1);
        $time = $out->date;
        print_r($time,1); 
        $time = strtotime($time);
    */
      $dirPath = '/home/ayush/Desktop/logsForCompress';
                if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }

     // $hoursNow = $arguments[hours];
      $hoursNow = 48;
      for($i=0 ; $i <= $hoursNow ; $i++)
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

        $time = date("Y/m/d H:i:s",strtotime('-'.$hoursNow.' hours')); 
        $out = new DateTime($time);
        print_r($out,1);
        $time = $out->date;
        print_r($time,1); 
        $time = strtotime($time);
        
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
        $arrChannels = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {  
            //$arrChannels[$module['key']] = $module['doc_count'];
            $channelName = $module['key'];
              // $out = $module['doc_count']; 
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrChannels['time'] = $time;
                $arrChannels['channelName'] = $channelName;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrChannels)."\n");
                fclose($fileResource);
            }
        }
     
        
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
   //      $arrDomain['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
          //  $arrDomain[$module['key']] = $module['doc_count'];
          //  $arrDomainName = $module['key'];
             $domainName = $module['key'];
              // $out = $module['doc_count']; 
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrDomain['time'] = $time;
                $arrDomain['Domain'] = $domainName;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrDomain)."\n");
                fclose($fileResource);
            }
        }
        /*
        if(sizeof($arrDomain) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrDomain)."\n");
        fclose($fileResource);
           }
        */
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
                        'field' => 'typeOfError',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $TypeOfError = array();
        //$TypeOfError['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            //$TypeOfError[$module['key']] = $module['doc_count'];
             $errorType = $module['key'];
              // $out = $module['doc_count']; 
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $TypeOfError['time'] = $time;
                $TypeOfError['typeOfError'] = $errorType;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($TypeOfError)."\n");
                fclose($fileResource);
            }
        }
 /*
        if(sizeof($TypeOfError) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($TypeOfError)."\n");
        fclose($fileResource);
           }
*/
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
   //     $arrHostname['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
          //  $arrHostname[$module['key']] = $module['doc_count'];
            $hostname = $module['key'];
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrHostname['time'] = $time;
                $arrHostname['beat.name'] = $hostname;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrHostname)."\n");
                fclose($fileResource);
            }


        }

        /*
        if(sizeof($arrHostname) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrHostname)."\n");
        fclose($fileResource);
        }
        */

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
      //  $arrmoduleName['time'] = $time;

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
         //   $arrmoduleName[$module['key']] = $module['doc_count'];
             $modulename = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrmoduleName['time'] = $time;
                $arrmoduleName['moduleName'] = $channelName;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrmoduleName)."\n");
                fclose($fileResource);
            }
        }
        /*
        if(sizeof($arrmoduleName) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrmoduleName)."\n");
        fclose($fileResource);
        }
        */

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
                        'field' => 'logType',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrLogType = array();
   //     $arrLogType['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
          //  $arrLogType[$module['key']] = $module['doc_count'];
            $logType = $module['key'];

             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrLogType['time'] = $time;
                $arrLogType['logType'] = $logType;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrLogType)."\n");
                fclose($fileResource);
            }
        }
        /*
        if(sizeof($arrLogType) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrLogType)."\n");
        fclose($fileResource);
             }
        */
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
                        'field' => 'apiVersion',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrApiVersion = array();
    //    $arrApiVersion['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
      //      $arrApiVersion[$module['key']] = $module['doc_count'];
             $apiVersion = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrApiVersion['time'] = $time;
                $arrApiVersion['apiVersion'] = $apiVersion;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrApiVersion)."\n");
                fclose($fileResource);
            }
        }
        /*
        if(sizeof($arrApiVersion) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrApiVersion)."\n");
        fclose($fileResource);
        }
        */

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
                        'field' => 'actionName',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrActionName = array();
  //      $arrActionName['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
    //        $arrActionName[$module['key']] = $module['doc_count'];
            $actionName = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrActionName['time'] = $time;
                $arrActionName['actionName'] = $actionName;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrActionName)."\n");
                fclose($fileResource);
            }

        }
        /*
        if(sizeof($arrActionName) > 1){
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,json_encode($arrActionName)."\n");
        fclose($fileResource);
        }
        */
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
                        'field' => 'REQUEST_URI',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrrequestURI = array();
 //       $arrrequestURIs['time'] = $time;
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
   //         $arrrequestURI[$module['key']] = $module['doc_count'];
              $requestURI = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrrequestURI['time'] = $time;
                $arrrequestURI['REQUEST_URI'] = $requestURI;
                $filePath = $dirPath."/kibanaCompressing-".$date;
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrrequestURI)."\n");
                fclose($fileResource);
            }
        }
        /*
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