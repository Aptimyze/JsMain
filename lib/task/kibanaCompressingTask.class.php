<?php

class kibanaCompressingTask extends sfBaseTask
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

    $this->namespace        = 'indexCompressor';
    $this->name             = 'kibanaCompressing';
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


      for($i=0 ; $i <=24 ; $i++)
      {
        $currdate = date('Y.m.d');
        $elkServer = '10.10.18.66';
        $elkPort = '9200';
        $indexName = 'filebeat-'.$currdate;
        $query = '_search';
        $urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
        $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'channelName',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
                    ]
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }



         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }


         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
                    ]
                ]
            ],
            'aggs' => [
                'modules' => [
                    'terms' => [
                        'field' => 'hostname',
                        'size' => 1000
                    ]
                ]
            ]
        ];
        $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
        $arrResponse = json_decode($response, true);
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }
         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }
         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }

         $params = [
            "query"=> [
                "range" => [
                    "@timestamp" => [
                        "lt" => "now-48-".$i,
                        "gt" => "now-48-".$i+1
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
        $arrModules = array();
        foreach($arrResponse['aggregations']['modules']['buckets'] as $module)
        {
            $arrModules[$module['key']] = $module['doc_count'];
        }

      }
    }
  }

