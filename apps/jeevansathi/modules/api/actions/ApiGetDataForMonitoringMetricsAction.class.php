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
    
        private $timeChange = 34200;//9.5*60*60;
        private $oneDay = 86399;
	public function execute($request)
	{
            $firstDateDay = explode('-',explode(' ',$request->getParameter('startDate'))[0])[2];
            $endDateDay = explode('-',explode(' ',$request->getParameter('endDate'))[0])[2];
            if($firstDateDay == $endDateDay){
                $this->interval = "hour";
            }
            else{
                $this->interval = "day";
            }
            $startDate = strtotime($request->getParameter('startDate'))-$this->timeChange;
            $endDate = strtotime($request->getParameter('endDate'))-$this->timeChange;
            //if($this->interval == "hour"){
                $greaterThan = date('Y-m-d',$startDate)."T".date('H:i:s',$startDate);
                $lessThan = date('Y-m-d',$endDate)."T".date('H:i:s',$endDate);
            //}
            //else{
                //$greaterThan = date('Y-m-d',$startDate)."T".date('H:i:s',  strtotime('00:00:00')+$this->timeChange);
                //$lessThan = date('Y-m-d',$endDate+$this->oneDay)."T".date('H:i:s',strtotime('00:00:00')+$this->oneDay+$this->timeChange);
            //}
            $timeDiff = $endDate -  $startDate;
            
            $startDate2 = strtotime($request->getParameter('startDate2'))-$this->timeChange;
            if($startDate2){
                $endDate2 = $startDate2 + $timeDiff;
                //if($this->interval == "hour"){
                    $greaterThan2 = date('Y-m-d',$startDate2)."T".date('H:i:s',$startDate2);
                    $lessThan2 = date('Y-m-d',$endDate2)."T".date('H:i:s',$endDate2);
                //}
                //else{
                    //$greaterThan2 = date('Y-m-d',$startDate2)."T".date('H:i:s',strtotime('00:00:00')+$this->timeChange);
                    //$lessThan2 = date('Y-m-d',$endDate2+$this->oneDay)."T".date('H:i:s',strtotime('00:00:00')+$this->oneDay+$this->timeChange);
                //}
            }
            
            
            $type = $request->getParameter("type");
            //$this->interval = intval($timeDiff/8);
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
            $totalResponse[] = $this->getResponse($greaterThan, $lessThan,$request->getParameter('startDate'),$request->getParameter('endDate'));
            if($startDate2){
            $totalResponse[] = $this->getResponse($greaterThan2, $lessThan2,$request->getParameter('startDate2'),date('Y-m-d H:i:s',$endDate2));
            }
            $totalResponse["dayOrHour"] = $this->interval;
            echo json_encode($totalResponse);die;//die('pa');
            return sfView::NONE;
    }
    
    public function getResponse($gt,$lt,$oGt,$oLt){
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
                                                "min_doc_count": 0,
                                                "field": "@timestamp",
                                                "interval": "'.$this->interval.'"
                                        }
                                }
                        }
                }';
        
       // print_r($params);print_r($this->urlToHit);
       //echo $params;die;
      // print_r($params);die;
            $response = CommonUtility::sendCurlPostRequest($this->urlToHit,$params);
            $phpResponse = json_decode($response,true);
            foreach ($phpResponse['aggregations']['articles_over_time']['buckets'] as $key => $value) {
                $returnedDate = substr(str_replace('T',' ', $value['key_as_string']),0,19);
                if($this->interval == "day"){
                    if(date('d',strtotime($returnedDate)) < date('d',strtotime($gt)) || date('d',strtotime($returnedDate)) > date('d',strtotime($lt)))
                        continue;
                    $newResponse['timestamp'][] = date('Y-m-d H:i:s',strtotime($returnedDate)+$this->timeChange);
                }
                else
                    $newResponse['timestamp'][] = date('Y-m-d H:i:s',strtotime($returnedDate)+$this->timeChange+1800);
                $newResponse['count'][] = $value['doc_count'];
            }
            if($this->interval == "day"){
                $ll = date('d',strtotime($oGt));
                $hl = date('d',strtotime($oLt));
            }
            else{
                $ll = date('H',strtotime($gt));
                $hl = date('H',strtotime($lt));
            }
            /*for($i=$ll;$i<=$hl;$i++){
                $found=0;
                foreach ($newResponse['timestamp'] as $key => $value) {
                    $returnedDate = substr(str_replace('T',' ', $value),0,19);
                    if(($this->interval == "day" && date('d',strtotime($returnedDate)) == $i) || ($this->interval == "hour" && date('H',strtotime($returnedDate)) == $i)){
                        $found = 1;
                    }
                }
                if(!$found){
                    $numString = (string)$i;
                    if(strlen($numString)==1)
                         $numString = "0".$numString;
                    if($this->interval == "day"){
                        //print_r(substr_replace($returnedDate,$numString,8,2));die;
                        $newResponse['timestamp'][] = substr_replace($returnedDate,$numString,8,2);
                    }
                    else{
                        $newResponse['timestamp'][] = substr_replace($returnedDate,$numString,11,2);
                    }

                    $newResponse['count'][] = 0;
                }
            }*/
            $newResponse['totalCount'] = $phpResponse['hits']['total'];
            return $newResponse;
        
        
    }
        
}
