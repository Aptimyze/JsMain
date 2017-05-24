<?php

/* This class is meant for for formatting crm agent notifications. */
class formatCRMNotification
{
 
/**
* 
* Function for mapping input data to notification 
* @access public
* @param $params
**/
  public function mapCRMAgentNotification($params)
  {
        if(is_array($params) && $params)
        {
          $message = formatCRMNotification::mapCRMAgentMessage($params['ACTION'],$params['PROFILE'],$params['PROFILE']);
          $onClickLink = formatCRMNotification::mapCRMNotificationClickLink($params['PROFILE'],$params['PROFILEID']);
          
          $result = array('process' =>'AGENT_NOTIFICATIONS','data'=>array('type' => 'update','body'=>array("MESSAGE"=>$message,"AGENT"=>$params['AGENT'],"PROFILE"=>$params['PROFILE'],"ONCLICKLINK"=>$onClickLink)), 'redeliveryCount'=>0 );
          return $result;
        } 
        else
          return null;  
  }

  /**
* 
* Function for mapping data to buffer instant notification 
* @access public
* @param $params
* @return $result
**/
  public static function mapBufferInstantNotification($params)
  {
        if(is_array($params) && $params)
        {      
          $result = array('process' =>'BUFFER_INSTANT_NOTIFICATIONS','data'=>array('type' => 'update','body'=>array("notificationKey"=>$params["notificationKey"],"selfUserId"=>$params["selfUserId"],"otherUserId"=>$params["otherUserId"])), 'redeliveryCount'=>0);
          if($params["message"])
            $result['data']['body']["message"] = $params["message"];
          return $result;
        } 
        else
          return null;  
  }

  /**
* 
* Function for mapping input data to notification message 
* @access public
* @param $action,$profile)
**/
  public static function mapCRMAgentMessage($action,$profile)
  {
    if($action=='ONLINE')
      $message = $profile." is now online.";
    else if($action=='FP')
      $message = $profile." has made a failed payment try.";
    else
      $message = "";
    return $message;     
  }

  /**
* 
* Function for mapping input data to notification onclick link url
* @access public
* @param $params
**/
  public static function mapCRMNotificationClickLink($username,$profileid)
  {
     $onClickLink = "/operations.php/crmAllocation/agentAllocation?username=".$username."&profileid=".$profileid."&subMethod=HANDLED&orders=";
     return $onClickLink; 
  }
}
?>
