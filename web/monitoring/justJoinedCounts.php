<?php
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
$solr1 = JsConstants::$solrServerProxyUrl;
$SAFE_TIME = 6;
$DAY_GAP = 6;
$endDate = str_replace(" ","T",date("Y-m-d H:i:s", strtotime("now") - $SAFE_TIME * 3600))."Z";
$startDate = str_replace(" ","T",date("Y-m-d 00:00:00", strtotime($endDate) - $DAY_GAP*24*3600))."Z"; 
$daily = "&fq=VERIFY_ACTIVATED_DT:[".$startDate." ".$endDate."]";
$url = $solr1.'/select';
$postParamsMale = 'q=*:*&wt=phps&rows=0&fq=GENDER:(M)'.$daily;
$postParamsFemale = 'q=*:*&wt=phps&rows=0&fq=GENDER:(F)'.$daily;
$jj = $argv[1];
$res = sendCurlPostRequestJJ($url,$postParamsMale);   
$res=unserialize($res);
$countM = $res["response"]["numFound"];

$res = sendCurlPostRequestJJ($url,$postParamsFemale);   
$res=unserialize($res);
$countF = $res["response"]["numFound"];

$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
file_put_contents($fileName, date("Y_m_d_H", strtotime("now")).':: M-'.$countM.'  :: F-'.$countF."\n", FILE_APPEND);

$alertSMS = 0;
foreach(JsConstants::$solrServerUrls as $key=>$solrUrl){
                $index = array_search($solrUrl, JsConstants::$solrServerUrls);
                if($index == $key && $solrUrl == JsConstants::$solrServerUrls[$index]){
                        $urlToHit = $solrUrl.'/select';
                        $res = sendCurlPostRequestJJ($url,$postParamsMale);   
                        $res=unserialize($res);
                        $Mcnt = $res["response"]["numFound"];

                        $res = sendCurlPostRequestJJ($url,$postParamsFemale);   
                        $res=unserialize($res);
                        $Fcnt = $res["response"]["numFound"];
                        
                        if($Mcnt != $countM || $Fcnt != $countF){
                                $alertSMS = 1;
                        }
                }
}
if($jj == 1 && $alertSMS == 1){
	sendJJSMS(1);
}elseif($alertSMS == 1){
	sendJJSMS();
	$php5 = JsConstants::$php5path;
        $cronDocRoot = JsConstants::$cronDocRoot;
        sleep(2);
        passthru("$php5 $cronDocRoot/web/monitoring/justJoinedCounts.php 1");
}
function sendJJSMS($flag =""){
        $FROM_ID = "JSSRVR";
        $PROFILE_ID = "144111";
        $SMS_TO = array('9650350387','9873639543');
        $smsMessage = "Mysql Error Count have reached Threshold on Just joined count $flag within 5 minutes";
        foreach ($SMS_TO as $mobPhone) {
                $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
                $xml_content = "%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22" . urlencode($smsMessage) . "%22%20PROPERTY=%220%22%20ID=%22" . $PROFILE_ID . "%22%3E%3CADDRESS%20FROM=%22" . $FROM_ID . "%22%20TO=%22" . $mobPhone . "e%22%20SEQ=%22" . $PROFILE_ID . "%22%20TAG=%22%22/%3E%3C/SMS%3E";
                $xml_end = "%3C/MESSAGE%3E";
                $xml_code = $xml_head . $xml_content . $xml_end;
                $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
                if ($fd) {
                        $response = '';
                        while (!feof($fd)) {
                                $response.= fread($fd, 4096);
                        }
                        fclose($fd);
                }
        }
}
function sendCurlPostRequestJJ($urlToHit,$postParams,$timeout='',$headerArr="")
{
    $timeout = 50000;
    $ch = curl_init($urlToHit);
	
	if($postParams)
		curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if($postParams)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    return $output;

}
?>
