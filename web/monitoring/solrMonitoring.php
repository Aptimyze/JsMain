<?php
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");

$today    = date("Y-m-d");
$yesterday = date("Y-m-d",strtotime("-3 day",strtotime($today)));
$daily = "&fq=ENTRY_DT:[".$yesterday."T00:00:00Z%20".$today."T00:00:00Z]";

$urlTocheckArray = array(
    0=>array("append"=>'/select?q=*:*',"counter"=>670000),
    1=>array("append"=>'/select?q=*:*&fq=GENDER:(F)',"counter"=>200000),
    2=>array("append"=>'/select?q=*:*&fq=GENDER:(M)',"counter"=>470000),
    3=>array("append"=>'/select?q=*:*'.$daily,"counter"=>500),
    4=>array("append"=>'/select?q=*:*&fq=GENDER:(F)'.$daily,"counter"=>150),
    5=>array("append"=>'/select?q=*:*&fq=GENDER:(M)'.$daily,"counter"=>160),
);
$downServers = array();
foreach(JsConstants::$solrServerUrls as $key=>$solrUrl){
        $index = array_search($solrUrl, JsConstants::$solrServerUrls);
        if($index == $key && $solrUrl == JsConstants::$solrServerUrls[$index]){
                foreach($urlTocheckArray as $checkData){
                        $url = $solrUrl.$checkData["append"]."&wt=phps";
                        $res = CommonUtility::sendCurlPostRequest($url,'nouse=1',"100");
                        $res = unserialize($res);
                        $totalResults = $res['response']['numFound'];
                        if($totalResults < $checkData["counter"])
                        {               
                                if($res == ""){
                                        $downServers[] = "down server ".$key;
                                        $msg="$key server is down";
                                }else{
                                        $fromServer[]="from server ".$key;
                                        $msg="TotalResults:$totalResults , Expected:More than ".$checkData["counter"];
                                }
                        }
                }
        }
}
$downServers = array_unique($downServers);
$fromServer = array_unique($fromServer);

if(!empty($fromServer) || !empty($downServers))
{
	include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
        if(!empty($fromServer)){
                $mobile         = "9818424749";
                $date = date("Y-m-d h");
                $str=implode(",", $fromServer);
                $message        = "Mysql Error Count have reached solr $date within 5 minutes $str";
                $from           = "JSSRVR";
                $profileid      = "144111";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

                $mobile         = "9873639543";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

                $mobile         = "9711304800";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

                $mobile         = "9650350387";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                
                $mobile         = "9711818214";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                
                $mobile         = "8376883735";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                
                $mobile         = "9810300513";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                
                $mobile         = "9953457479";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        }
        if(!empty($downServers)){
                $mobile         = "9818424749";
                $date = date("Y-m-d h");
                $str=implode(",", $downServers);
                $message        = "Mysql Error Count have reached solr $date within 5 minutes $str";
                $from           = "JSSRVR";
                $profileid      = "144111";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

                $mobile         = "9650350387";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        }
}
