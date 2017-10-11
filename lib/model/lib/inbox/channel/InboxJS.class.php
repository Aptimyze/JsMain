<?php
/**
 * @brief This class implements InboxChannelInterface class and is base class for all other channels
 */
class InboxJS implements InboxChannelInterface
{
	
	//Constructor 
	function __construct($params="")
	{
			
		
	}
	
    /* This function will return the channel specific variables
    *@param params : need to be set 
    */
    public function setVariables($params)
    {
            
    }

    /* This function will set post params for api request
    *@param :request  
    */
    public function setPostParamsForApiRequest($request)
    {
        if($request)
            {
                //$request = $params["request"];
                $request->setParameter("ContactCenterDesktop",1);
            if(!$request->getParameter('infoTypeId'))
            {
                $lastSearchInfoId = InboxConfig::$cctabArr[InboxConfig::$defaultVerticalTabID]["defaultHtabInfoID"];
                $request->setParameter('infoTypeId',$lastSearchInfoId);
            }
                if(!$request->getParameter('pageNo'))
                    $request->setParameter('pageNo',1);
        }
        else
            throw new JsException("", "request required in InboxJSPC.class.php to set PostParams");
    }
}
?>