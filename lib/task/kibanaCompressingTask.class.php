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
    $this->elkServer = '10.10.18.66';
    //This is the port number where Elastic Search is running
    $this->elkPort = '9200';
    //This is the index name which will be searched for getting the records.
    $this->indexName = 'filebeat-*';
    //This is the type of query which will be used to query elastic search.
    $this->query = '_search'; 

    $this->addArguments(array(new sfCommandArgument('hours',sfCommandArgument::OPTIONAL,'this parameter is added to include the number of hours for which the cron has to be made',$hours = "72")));   
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

      $hoursNow = 48;
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

            $channelName = $module['key'];
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrChannels['time'] = $time;
                $arrChannels['channelName'] = $channelName;
                $filePath = $dirPath."/kibanaCompressing-";
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

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

             $domainName = $module['key']; 
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrDomain['time'] = $time;
                $arrDomain['Domain'] = $domainName;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrDomain)."\n");
                fclose($fileResource);
            }
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
                        'field' => 'typeOfError',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $TypeOfError = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

             $errorType = $module['key'];
 
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $TypeOfError['time'] = $time;
                $TypeOfError['typeOfError'] = $errorType;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($TypeOfError)."\n");
                fclose($fileResource);
            }
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

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $hostname = $module['key'];
            for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrHostname['time'] = $time;
                $arrHostname['beat.name'] = $hostname;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrHostname)."\n");
                fclose($fileResource);
            }


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

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

             $modulename = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrmoduleName['time'] = $time;
                $arrmoduleName['moduleName'] = $channelName;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrmoduleName)."\n");
                fclose($fileResource);
            }
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
                        'field' => 'logType',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrLogType = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

            $logType = $module['key'];

             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrLogType['time'] = $time;
                $arrLogType['logType'] = $logType;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrLogType)."\n");
                fclose($fileResource);
            }
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
                        'field' => 'apiVersion',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrApiVersion = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

             $apiVersion = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrApiVersion['time'] = $time;
                $arrApiVersion['apiVersion'] = $apiVersion;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrApiVersion)."\n");
                fclose($fileResource);
            }
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
                        'field' => 'actionName',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrActionName = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

            $actionName = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrActionName['time'] = $time;
                $arrActionName['actionName'] = $actionName;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrActionName)."\n");
                fclose($fileResource);
            }

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
                        'field' => 'REQUEST_URI',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrrequestURI = array();

        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {

              $requestURI = $module['key'];
             for($i = 0 ; $i < $module['doc_count'] ; $i++)
            { 
                $arrrequestURI['time'] = $time;
                $arrrequestURI['REQUEST_URI'] = $requestURI;
                $filePath = $dirPath."/kibanaCompressing-";
                $fileResource = fopen($filePath,"a");
                fwrite($fileResource,json_encode($arrrequestURI)."\n");
                fclose($fileResource);
            }
        }

      }
    }
}