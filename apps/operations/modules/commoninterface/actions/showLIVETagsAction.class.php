<?php

/**
 * showLIVETagsAction actions.
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Manoj
 */
class showLIVETagsAction extends sfActions
{
	/**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function execute($request)
    {
    	$request = sfContext::getInstance()->getRequest();
    	$urlToHit = "http://gitlabweb.infoedge.com/api/v3/projects/Jeevansathi%2FJsMain/repository/tags?";
    	$headerArr = array(
    		'PRIVATE-TOKEN:YY7g4CeG_tf17jZ4THEi',				
			); //token used is of username: vidushi@naukri.com

    	$this->tagArr = array();
    	$june1Date = "2017-06-01 00:00:00";

    	// LOGIC TO FIND TAGS TO BE USED IN DASHBOARD
    	$response = $this->sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");
    	$i=0;
    	foreach ($response as $key => $value) 
    	{    		
    		$updatedAtDate = explode(".",$value->commit->committed_date);
			$updatedDate = str_replace("T"," ", $updatedAtDate[0]);
			if($updatedDate > $june1Date)
			{
				$this->tagArr[$i]["tagName"] = $value->name;
                $tagNameArr = explode("@",$value->name);
                if($tagNameArr[1])
                {
                    $tagDate = explode("_",$tagNameArr[1]);
                    $tagTime = str_replace("-", ":", $tagDate[1]);
                }                
                    
				$this->tagArr[$i]["description"] = $value->release->description;
				$this->tagArr[$i]["dateTime"] = $tagDate[0]." ".$tagTime;
				$timeArr[$i] = $updatedDate;    			
			}
			$i++;    		
    		unset($updatedDate);
            unset($tagNameArr);
            unset($tagDate);
            unset($tagTime);
    	}
    	array_multisort($timeArr, SORT_DESC, $this->tagArr);
    }

    function sendCurlGETRequest($urlToHit,$postParams,$timeout='',$headerArr="",$requestType="")
    {    
    	if(!$timeout)
    		$timeout = 50000;
    	$ch = curl_init($urlToHit);    
    	if($headerArr)
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
    	else
    		curl_setopt($ch, CURLOPT_HEADER, 0);
    	if($postParams)
    		curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	if($postParams)
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);	
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
    	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
    	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
    	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	$output = curl_exec($ch);	
    	return json_decode($output);
    }
}