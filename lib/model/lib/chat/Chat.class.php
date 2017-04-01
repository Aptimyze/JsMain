<?php


/**
 * This library contains the function to be used for CHAT.
 *
 */
class Chat {
    
    public function convertXml($xml){
        
        $result = array();
        foreach($xml as $key => $val){
            $xmlContent = str_replace(array("\n","\r","\t"), '', $val);
            $xmlContent = trim(str_replace('"', "'", $xmlContent));
            $simpleXML = simplexml_load_string($xmlContent);
            $result[$key] = $simpleXML;
        }
        return $result;
    }
    
    public function addNewProfile($profileid){
        try{
            $producerObj = new Producer();
            if ($producerObj->getRabbitMQServerConnected()) {
                $chatData = array('process' => 'USERCREATION', 'data' => ($profileid), 'redeliveryCount' => 0);
                $producerObj->sendMessage($chatData);
            }
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function storeLoggedInUserContacts($profileid,$type){
        if($profileid){
            $memcacheObj = new ProfileMemcacheService($profileid);
            $paramsArr['PROFILEID'] = $profileid;
            $paramsArr['ACC_BY_ME'] = $memcacheObj->get('ACC_BY_ME');
            $paramsArr['ACC_ME'] = $memcacheObj->get('ACC_ME');
            $paramsArr['AWAITING_RESPONSE'] = $memcacheObj->get('AWAITING_RESPONSE');
            $paramsArr['BOOKMARK'] = $memcacheObj->get('BOOKMARK');
            $paramsArr['TYPE'] = $type;
            $chatContactsLogObj = new newjs_CHAT_CONTACTS_LOG();
            $chatContactsLogObj->insert($paramsArr);
        }
    }

    public function storeChatTimeoutProfiles($profileid,$cookie,$uagent){
        if($profileid){
            $paramsArr['PROFILEID'] = $profileid;
            $paramsArr['COOKIE'] = $cookie;
            $paramsArr['UAGENT'] = $uagent;
            $chatContactsLogObj = new newjs_CHAT_TIMEOUT_LOG();
            $chatContactsLogObj->insert($paramsArr);
        }
    }

}
