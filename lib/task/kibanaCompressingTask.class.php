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
      $dirPath = '/home/ayush/Desktop/logsForCompress';

      $hoursNow = $arguments[hours];
      $hoursNow = 240;
      for($i=3 ; $i <= $arguments[hours] ; $i++)
      {
        $hoursNow = $i;
        $date = new DateTime(date("Y-m-d", strtotime('-'.$hoursNow.' hours')));
        $date = $date->format('Y.m.d');
        $elkServer = '10.10.18.66';
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
        $arrChannels = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrChannels[$module['key']] = $module['doc_count'];
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
            $arrDomain[$module['key']] = $module['doc_count'];
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
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $TypeOfError[$module['key']] = $module['doc_count'];
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
            $arrHostname[$module['key']] = $module['doc_count'];
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
            $arrmoduleName[$module['key']] = $module['doc_count'];
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
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrLogType[$module['key']] = $module['doc_count'];
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
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrApiVersion[$module['key']] = $module['doc_count'];
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
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrActionName[$module['key']] = $module['doc_count'];
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
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrrequestURI[$module['key']] = $module['doc_count'];
        }
                

        $finalArrayToWrite['arrChannels'] = json_encode($arrChannels);
        $finalArrayToWrite['arrDomain'] = json_encode($arrDomain);
        $finalArrayToWrite['TypeOfError'] = json_encode($TypeOfError);
        $finalArrayToWrite['arrHostname'] = json_encode($arrHostname);
        $finalArrayToWrite['arrmoduleName'] = json_encode($arrmoduleName);
        $finalArrayToWrite['arrApiVersion'] = json_encode($arrApiVersion);
        $finalArrayToWrite['arrActionName'] = json_encode($arrActionName);
        $finalArrayToWrite['arrrequestURI'] = json_encode($arrrequestURI);
        $finalArrayToWrite['arrLogType'] = json_encode($arrLogType); 

        $arrToWrite['DESCRIPTION'] = "This is data from now-{$hoursNow}h  to now-{$ltHour}h \n ";
        $arrToWrite['DATA'] = $finalArrayToWrite;
              
        if (false === is_dir($dirPath)) {
            mkdir($dirPath,0777,true);
        }
        
        $filePath = $dirPath."/kibanaCompressing-".$date;
        $fileResource = fopen($filePath,"a");
        fwrite($fileResource,print_r($arrToWrite,true));
        //fwrite($fileResource,json_encode($finalArrayToWrite));
        fwrite($fileResource, "\n");
        fclose($fileResource);
      }
    }
}