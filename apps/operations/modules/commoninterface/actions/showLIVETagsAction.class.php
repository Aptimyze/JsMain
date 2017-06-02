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
    	$monthBackDate = date('Y-m-d H:i:s', strtotime('-1 month'));

    	// LOGIC TO FIND TAGS TO BE USED IN DASHBOARD
    	$response = $this->sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");
    	$i=0;
    	foreach ($response as $key => $value) 
    	{    		
    		$updatedAtDate = explode(".",$value->commit->committed_date);
			$updatedDate = str_replace("T"," ", $updatedAtDate[0]);
			if($updatedDate > $monthBackDate)
			{
				$this->tagArr[$i]["tagName"] = $value->name;
				$this->tagArr[$i]["description"] = $value->release->description;
				$this->tagArr[$i]["dateTime"] = $updatedDate;
				$timeArr[$i] = $updatedDate;    			
			}
			$i++;    		
    		unset($updatedDate);
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