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
}
