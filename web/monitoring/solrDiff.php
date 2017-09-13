<?php
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");

$today    = date("Y-m-d");
$urlTocheckArray = array(
    0=>array("append"=>'/select?q=*:*&fq=GENDER:(M)&fl=id&rows=2',"counter"=>600000)
    );
$downServers = array();
$sendSmsCounter = array();
$consArray = array(
        1 => 'http://10.10.18.70:8080/solr/collection1/',
        2 => 'http://10.10.18.66:8988/solr/collection1/',
);
foreach($consArray as $key=>$solrUrl){
                $index = array_search($solrUrl, $consArray);
                if($index == $key && $solrUrl == $consArray[$index]){
                        foreach($urlTocheckArray as $checkData){
                                $url = $solrUrl.$checkData["append"]."&wt=phps";
                                $res = CommonUtility::sendCurlPostRequest($url,'nouse=1',"100");
                                $res = unserialize($res);
                                $r1[] = profileIDSS($res["response"]["docs"]);
                        }
                }
}
$dif = array_diff($r1[0], $r1[1]);
print_r($dif);die;
function profileIDSS($docs){
        $profiles = array();
        foreach($docs as $d){
              $profiles[]   = $d['id'];
        }
        return $profiles;
}
