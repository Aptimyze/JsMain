<?php
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
$solr1 = JsConstants::$solrServerProxyUrl;
$url = $solr1.'/select';
$postParamsMale = 'q=*:*&wt=phps&rows=0&fq=GENDER:(M)';
$postParamsFemale = 'q=*:*&wt=phps&rows=0&fq=GENDER:(F)';

$res = sendCurlPostRequestJJ($url,$postParamsMale);   
$res=unserialize($res);
$countM = $res["response"]["numFound"];

$res = sendCurlPostRequestJJ($url,$postParamsFemale);   
$res=unserialize($res);
$countF = $res["response"]["numFound"];

$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/SolrCountNow.txt";
file_put_contents($fileName, date("Y_m_d_H", strtotime("now")).':: M-'.$countM.'  :: F-'.$countF."\n", FILE_APPEND);

$alertSMS = 0;
$ipMismatch = array();
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
                                $serverIp = explode(":",$solrUrl);
                                $serverIp = explode(".",$serverIp[1]);
                                
                                if(is_int(end($serverIp)))
                                        $ipMismatch[] = trim(end($serverIp),"/");
                                else
                                        $ipMismatch[] = trim($serverIp[0],"/");
                                
                                
                                $alertSMS = 1;
                        }
                }
}
if($alertSMS == 1){
	sendJJSMS(implode(" ",$ipMismatch));
}
function sendJJSMS($flag =""){
        $FROM_ID = "JSSRVR";
        $PROFILE_ID = "144111";
        $SMS_TO = array('9773889617','9773889652',"9818424749","9873639543");
        $smsMessage = "Mysql Error Count have reached Threshold on solr count mismatch $flag within 5 minutes";
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
                        CommonUtility::logTechAlertSms($smsMessage, $mobPhone);
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
	curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
	$header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
	curl_setopt($ch, CURLOPT_HEADER, $header);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    return $output;

}
?>
