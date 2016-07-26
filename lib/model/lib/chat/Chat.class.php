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
}
