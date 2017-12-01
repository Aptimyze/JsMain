<?php
/**
 * API For Cache  
 * 
 */
 
/**
 * Class ApiProfileCacheV1
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @date       29th August 2016 
 */
class ApiProfileCacheV1Action extends sfAction
{
  public function execute($request)
  {
    $respObj=ApiResponseHandler::getInstance();
    
    //if env is producation then allow only post method
    if(JsConstants::$whichMachine == 'prod' && false === $this->request->isMethod('POST')){
      $respObj->setHttpArray(ResponseHandlerConfig::$POST_PARAM_INVALID);
      $respObj->generateResponse();
      die;
    }
    
    $iProfileId = $request->getParameter("profileid");
    $fields = strtoupper($request->getParameter("fields"));
    
    if($request->hasParameter("cmd")) {
      $cmd = strtolower(trim($request->getParameter("cmd")));
      $validRequest = $this->isAuthenticate($cmd);
      if($validRequest) {
        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $arr = $this->processCmd($cmd);
        
        if(isset($arr['error'])){
          $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        $respObj->setResponseBody($arr);
        
      } else {
        $respObj->setHttpArray(ResponseHandlerConfig::$POST_PARAM_INVALID);
      }
    }
    else{     
      $arr = ProfileCacheLib::getInstance()->checkProfileData($iProfileId, $fields);
      if(JsConstants::$whichMachine == 'prod' && LoggedInProfile::getInstance()->getPROFILEID() != $iProfileId){
        unset($arr['EMAIL']);
        unset($arr['PHONE_MOB']);
        unset($arr['PHONE_RES']);
      }
      $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
      $respObj->setResponseBody($arr);
    }
       
    
    $respObj->generateResponse();
    die;
  }
  
  private function isAuthenticate($command)
  {
    switch(strtolower(trim($command)))
    {
      case "flushall":
        $valid = (JsConstants::$whichMachine != "prod") ? true : false;
      break;
      case "flush":
        $valid = ($this->request->hasParameter("profileid")) ? true : false;
      break;
      default :
        $valid = false;
      break;  
    }
    return $valid;
  }
  
  private function processCmd($command)
  {
    switch(strtolower(trim($command)))
    {
      case "flushall":
        try{
          if(JsMemcache::getInstance()->client instanceof Predis\Client) {
            $res = JsMemcache::getInstance()->client->flushall();
            $arr = array('msg'=>$res->__toString());
          }
        } catch(Exception $ex) {
          $arr = array('error'=>$ex->getMessage());
        }
      break;
      case "flush":
        $iProfileID = $this->request->getParameter('profileid');
        $res = ProfileCacheLib::getInstance()->removeCache($iProfileID);
	$this->deleteCALKeys($iProfileID);
        if($res)
          $arr = array('msg'=>"Success");
        else
          $arr = array('error'=>"Issue while remove from cache");
      break;
    }
    
    if(isset($arr))
      return $arr;
  }
  private function deleteCALKeys($profileId){
    $redis = JsMemcache::getInstance();
    $redis->delete($profileId.'_NOCAL_DAY_FLAG');
    $redis->delete($profileId.'_CAL_DAY_FLAG');
    $redis->delete($profileId.'_NO_LI_CAL');
  return true;
}

}
?>
