<?php
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
$solr1 = JsConstants::$solrServerUrl;
$solr1 = JsConstants::$solrServerUrl;
$solr2 = JsConstants::$solrServerUrl1;
$solr3 = JsConstants::$solrServerUrl3;
$today    = date("Y-m-d");
$yesterday = date("Y-m-d",strtotime("-3 day",strtotime($today)));
$daily = "&fq=ENTRY_DT:[".$yesterday."T00:00:00Z%20".$today."T00:00:00Z]";

$url[670000] = $solr1.'/select?q=*:*';
$url[200000] = $solr1.'/select?q=*:*&fq=GENDER:(F)';
$url[470000] = $solr1.'/select?q=*:*&fq=GENDER:(M)';
$url[500] = $solr1.'/select?q=*:*'.$daily;
$url[150] = $solr1.'/select?q=*:*&fq=GENDER:(F)'.$daily;
$url[160] = $solr1.'/select?q=*:*&fq=GENDER:(M)'.$daily;

$url[670001] = $solr2.'/select?q=*:*';
$url[200001] = $solr2.'/select?q=*:*&fq=GENDER:(F)';
$url[470001] = $solr2.'/select?q=*:*&fq=GENDER:(M)';
$url[501] = $solr2.'/select?q=*:*'.$daily;
$url[151] = $solr2.'/select?q=*:*&fq=GENDER:(F)'.$daily;
$url[161] = $solr2.'/select?q=*:*&fq=GENDER:(M)'.$daily;

$url[670002] = $solr3.'/select?q=*:*';
$url[200002] = $solr3.'/select?q=*:*&fq=GENDER:(F)';
$url[470002] = $solr3.'/select?q=*:*&fq=GENDER:(M)';
$url[502] = $solr3.'/select?q=*:*'.$daily;
$url[152] = $solr3.'/select?q=*:*&fq=GENDER:(F)'.$daily;
$url[162] = $solr3.'/select?q=*:*&fq=GENDER:(M)'.$daily;
foreach($url as $k=>$v)
{
        $url1=$v."&wt=phps";
        $res = CommonUtility::sendCurlPostRequest($url1,'nouse=1',"100");
        $res = unserialize($res);
        $totalResults = $res['response']['numFound'];
        if($totalResults < $k)
        {
			if($k%3==0)
				$fromServer[0]="from server 1";
			elseif($k%3==1)
				$fromServer[1]="from server 2";
                        else
                                $fromServer[1]="from server 4";
                        
            $msg="TotalResults:$totalResults , Expected:More than $k        ";
        }
} 

$solrDpp = "http://10.10.18.66:8983/solr/techproducts";
$urlDpp[670001] = $solrDpp.'/select?q=*:*';
$urlDpp[200001] = $solrDpp.'/select?q=*:*&fq=GENDER:(F)';
$urlDpp[470001] = $solrDpp.'/select?q=*:*&fq=GENDER:(M)';
$dppError = 0;
$msgDpp = '';
foreach($urlDpp as $k=>$v)
{
        $url1=$v."&wt=phps";
        $res = CommonUtility::sendCurlPostRequest($url1,'nouse=1',"100");
        $res = unserialize($res);
        $totalResults = $res['response']['numFound'];
        if($totalResults < $k)
        {
		$fromServer[2]="from server 3";
                $dppError++;
                $msgDpp="TotalResults:$totalResults , Expected:More than $k        ";
        }
}  
if($msg || $msgDpp)
{
	include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
        if($msg){
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
        if($msgDpp && $dppError >=2){
                $mobile         = "9818424749";
                $date = date("Y-m-d h");
                $str=implode(",", $fromServer);
                $message        = "Mysql Error Count have reached solr $date within 5 minutes $str";
                $from           = "JSSRVR";
                $profileid      = "144111";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

                $mobile         = "9650350387";
                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        }
}
