<?php

/*
This Cron is used to get the error trends from data which is stored in the indices of Elastic Search. 
*/

class cronErrorTrendsKibana extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'Kibana';
    $this->name             = 'cronErrorTrendsKibana';
    //This is the path to ELK server
    $this->elkServer = 'http://10.10.18.66';
    //This is the port number where Elastic Search is running
    $this->elkPort = '9200';
    //This is the index name which will be searched for getting the records.
    $this->fetchIndexName = 'filebeat-*';
    //This is the type of query which will be used to query elastic search.
    $this->query = '_search';
    //this is used as a pushed index
    $this->pushIndexName = 'errortrends';

    // in days
    $this->interval = 1;
    //start date compression
    $this->startDaysBefore = 1;
    //end date compression
    $this->startDaysEnd = 1;


    $this->briefDescription = 'This cron is used to get error trends from indices of the elastic search.';
    $this->detailedDescription = <<<EOF
The [cronErrorTrendsKibana|INFO] task does things.
Call it with:

  [php symfony Kibana:cronErrorTrendsKibana]
EOF;
  }
    // add your code here
    protected function execute($arguments = array(), $options = array())
    {
      try 
      {
        for ($i=$this->startDaysEnd; $i <= $this->startDaysBefore ; $i++) 
        { 
          $urlToHit = $this->elkServer.':'.$this->elkPort.'/'.$this->fetchIndexName.'/'.$this->query;
          $date = date('Y-m-d',strtotime('-'.$i.' day'));

            $params = [
              "query"=> [
                  "match" => ["logType"=>"Error"]
              ],
              "aggs"=> [
              "filtered"=> [
                "filter"=> [
                  "bool"=> [
                    "must"=> [
                      [
                        "range"=> [
                          "@timestamp"=> [
                            "gte"=> "now-".$i."d/d",
                            "lt"=> "now-".($i-1)."d/d",
                          ]
                        ]
                      ]
                    ]
                  ]
                ],
                "aggs"=> [
                  "Parent"=>
                  [
                      "terms"=>
                      [ "field" => "Parent"],

                      "aggs"=> [
                        "channelName"=>
                        [
                            "terms"=>
                            [ "field" => "channelName"],
                        ]
                      ]
                  ]
                ]
              ]
              ]
            ];

            unset($arrResponse);
            unset($response);
            $response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
            $arrResponse = json_decode($response, true);

            if($response)
            {
              $arrResponse = json_decode($response, true);
              $arrOutput = array();
              // Get module count for each module
              foreach($arrResponse['aggregations']['filtered']['Parent']['buckets'] as $parent)
              {
                $parentName = $parent['key'];
                foreach ($parent["channelName"]["buckets"] as $key => $channelArray) {
                  $arrOutput[$parentName][$channelArray["key"]] = $channelArray["doc_count"];
                }
              }
              $dataOuput = array(
                'date' => $date,
                'o' => $arrOutput
                );
              $dataOuput = json_encode($dataOuput);

              exec("curl -XPOST '$this->elkServer:$this->elkPort/$this->pushIndexName/json' -d '$dataOuput'");
            }
            else
            {
              die;
            }      
        }    
      } 
      catch (Exception $e) 
      {
        throw new jsException($e);   
      }
    }       
}
