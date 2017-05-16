<?php
class InboxUtility
{	
	public static function cachedInboxApi($type,$request="",$pid="",$response="")
        {
                $caching = $request->getParameter("caching");
                if($caching || $type=="del")
                {      
			if(!$pid)
			{
				$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');   
	                        $pid = $loggedInProfileObj->getPROFILEID();
			}
			if(!$pid)
				return 0;
            if(in_array($request->getParameter("infoTypeId"),array(7,8,2,24,5,23)))
            {      
            	if($request->getParameter("androidMyjsNew"))
    				$memcacheid =  "MMA".$request->getParameter("infoTypeId"); 
    			else        		
					$memcacheid =  "MM".$request->getParameter("infoTypeId"); 
                    if($type=='set')
                    {    
						foreach($response["profiles"] as $k=>$v)
						unset($response["profiles"][$k]["profileObject"]);
                        JsMemcache::getInstance()->set("cached$memcacheid$pid",serialize($response));
					}
                    elseif($type=='get')
                    {       
                            $response = JsMemcache::getInstance()->get("cached$memcacheid$pid");
							if($response)
                                return unserialize($response);
                    }
            }
			if($type=='del')
			{
				JsMemcache::getInstance()->set("cachedMM7$pid","");
				JsMemcache::getInstance()->set("cachedMM8$pid","");
				JsMemcache::getInstance()->set("cachedMM2$pid","");
				JsMemcache::getInstance()->set("cachedMM24$pid","");
				JsMemcache::getInstance()->set("cachedMM5$pid","");
				JsMemcache::getInstance()->set("cachedMM23$pid","");
				JsMemcache::getInstance()->set("cachedMMA7$pid","");
				JsMemcache::getInstance()->set("cachedMMA8$pid","");
				JsMemcache::getInstance()->set("cachedMMA2$pid","");
				JsMemcache::getInstance()->set("cachedMMA24$pid","");
				JsMemcache::getInstance()->set("cachedMMA5$pid","");
				JsMemcache::getInstance()->set("cachedMMA23$pid","");
			}	
                }
                return 0;
        }
}
?>
