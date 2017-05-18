<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiGetDataForMonitoringMetricsAction extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
    
        private $timeChange = 9.5*60*60;
	public function execute($request)
	{
            $startDate = strtotime($request->getParameter('startDate'))-$this->timeChange;
            $endDate = strtotime($request->getParameter('endDate'))-$this->timeChange;
            $greaterThan = date('Y-m-d',$startDate)."T".date('H:i:s',$startDate);
            $lessThan = date('Y-m-d',$endDate)."T".date('H:i:s',$endDate);

            $timeDiff = $endDate -  $startDate;
            
            $startDate2 = strtotime($request->getParameter('startDate2'));
            if($startDate2){
                $endDate2 = $startDate2 + $timeDiff;

                $greaterThan2 = date('Y-m-d',$startDate2)."T".date('H:i:s',$startDate2);
                $lessThan2 = date('Y-m-d',$endDate2)."T".date('H:i:s',$endDate2);
            }
            
            
            $type = $request->getParameter("type");
            $this->interval = intval($timeDiff/8);
            $channelQuery = "";
            $channel =$request->getParameter('channel');
            if($channel!="all")
                    $channelQuery ='{ "match" : {"channel" : "'.$channel.'"}},';
                   
                    $this->mustQuery = '"must":  ['.  $channelQuery.   
                                                            '{ "match" : {"logType" : "'.$type.'"}}'.
                                                 '],';
                   // die($this->mustQuery);
            $fieldToQuery = $request->getParameter('type');
            $elkServer = JsConstants::$kibana['ELK_SERVER'] ;
            $keyToFetch = "coolmatric-*";
            $key2 = "coolmatric";
            $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
            $query = '_search';
            $unId = time() + LoggedInProfile::getInstance()->getPROFILEID();
            $this->urlToHit = $elkServer.':'.$elkPort."/".$keyToFetch."/".$key2."/".$query;
            $totalResponse= array();
            $totalResponse[] = $this->getResponse($greaterThan, $lessThan);
            if($startDate2){
            $totalResponse[] = $this->getResponse($greaterThan2, $lessThan2);
            }
            
            echo json_encode($totalResponse);die;//die('pa');
            return sfView::NONE;
    }
    
    public function getResponse($gt,$lt){
       $params =  '{
                        "size": "0",
                        "query": 
                        {
                                "bool": 
                                {'. $this->mustQuery.
                                        '"filter": 
                                            {
                                                    "range": 
                                                    {
                                                            "@timestamp": 
                                                            {
                                                                    "gte": "'.$gt.'",
                                                                    "lte": "'.$lt.'"
                                                            }
                                                    }
                                            }
                                }
                        },
                        "aggs": 
                        {
                                "articles_over_time": 
                                {
                                        "date_histogram": 
                                        {
                                                "field": "@timestamp",
                                                "interval": "'.$this->interval.'s",
                                                "extended_bounds" : {"min" : "'.$gt.'","max" : "'.$lt.'"},
                                                "min_doc_count" : 0    
                                        }
                                }
                        }
                }';
        
       // print_r($params);print_r($this->urlToHit);
            $response = CommonUtility::sendCurlPostRequest($this->urlToHit,$params);
            $phpResponse = json_decode($response,true);
            foreach ($phpResponse['aggregations']['articles_over_time']['buckets'] as $key => $value) {
                $returnedDate = substr(str_replace('T',' ', $value['key_as_string']),0,19);
                $newResponse['timestamp'][] = date('Y-m-d H:i:s',strtotime($returnedDate)+$this->timeChange);
                $newResponse['count'][] = $value['doc_count'];
            }
            $newResponse['totalCount'] = $phpResponse['hits']['total'];
            return $newResponse;
        
        
    }
        
}
