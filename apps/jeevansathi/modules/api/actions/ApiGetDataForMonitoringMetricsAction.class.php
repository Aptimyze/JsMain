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
	public function execute($request)
	{
            $greaterThan = date('Y-m-d',strtotime($request->getParameter('startDate')))."T".date('H:i:s',strtotime($request->getParameter('startDate')));
            $lessThan = date('Y-m-d',strtotime($request->getParameter('endDate')))."T".date('H:i:s',strtotime($request->getParameter('endDate')));
            $timeDiff = strtotime($lessThan) -  strtotime($greaterThan);
            $type = $request->getParameter("type");
            $interval = intval($timeDiff/8);
            $channelQuery = "";
            $channel = $request->getParameter('channel');
            if($channel!="all")
            {
                 $channelQuery = '"must": 
                                            {
                                                    "match": 
                                                    {
                                                            "channel": "'.$channel.'"
                                                    }
                                           },';
            }
            $fieldToQuery = $request->getParameter('type');
            $elkServer = '10.10.18.66' ;
            $keyToFetch = "coolmatric-*";
            $key2 = "coolmatric";
            $elkPort = '9200';
            $query = '_search';
            $unId = time() + LoggedInProfile::getInstance()->getPROFILEID();
            $urlToHit = $elkServer.':'.$elkPort."/".$keyToFetch."/".$key2."/".$query;
            $params = '{
                        "size": "0",
                        "query": 
                        {
                                "bool": 
                                {'. $channelQuery.
                                        '"filter": 
                                            {
                                                    "range": 
                                                    {
                                                            "@timestamp": 
                                                            {
                                                                    "gte": "'.$greaterThan.'",
                                                                    "lte": "'.$lessThan.'"
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
                                                "interval": "'.$interval.'s",
                                                "extended_bounds" : {"min" : "'.$greaterThan.'","max" : "'.$lessThan.'"},
                                                "min_doc_count" : 0    
                                        }
                                }
                        }
                }';
            
            $response = CommonUtility::sendCurlPostRequest($urlToHit,$params);
            $phpResponse = json_decode($response,true);
            foreach ($phpResponse['aggregations']['articles_over_time']['buckets'] as $key => $value) {
                $newResponse['timestamp'][] = $value['key_as_string'];
                $newResponse['count'][] = $value['doc_count'];
            }
            $newResponse['totalCount'] = $phpResponse['hits']['total'];
            echo json_encode($newResponse);die;//die('pa');
            return sfView::NONE;
    }
        
}
