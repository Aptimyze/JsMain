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
    	$response = CommonFunction::sendCurlGETRequest($urlToHit,'',"",$headerArr,"GET");
    	$i=0;
    	foreach ($response as $key => $value) 
    	{    		
    		$updatedAtDate = explode(".",$value->commit->committed_date);
			$updatedDate = str_replace("T"," ", $updatedAtDate[0]);
			if($updatedDate > $june1Date)
			{
				$this->tagArr[$i]["tagName"] = $value->name;
                if(strpos($value->name,"@")!==false)
                {
                    $tagNameArr = explode("@",$value->name);    
                    if($tagNameArr[1])
                    {
                        if(strpos($tagNameArr[1],"_")!==false)
                        {
                            $tagDate = explode("_",$tagNameArr[1]);
                            $tagTime = str_replace("-", ":", $tagDate[1]);
                        }
                        else
                        {
                            $tagDate = strtotime($tagNameArr[1]);                  
                            $tagTime = date("Y-m-d H:i:s",$tagDate);
                        }
                    }
                }
                elseif(strpos($value->name,"#")!==false)
                {
                    $tagNameArr = explode("#",$value->name);
                    $tagDate = strtotime($tagNameArr[1]);                    
                    $tagTime = date("Y-m-d H:i:s",$tagDate);                    
                }
                    
				$this->tagArr[$i]["description"] = explode(",",$value->release->description);
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
}