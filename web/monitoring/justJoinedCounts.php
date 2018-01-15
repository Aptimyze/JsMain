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

$res = sendCurlPostRequestJJ($url,$postParamsMale);   
$res=unserialize($res);
$countM = $res["response"]["numFound"];

$res = sendCurlPostRequestJJ($url,$postParamsFemale);   
$res=unserialize($res);
$countF = $res["response"]["numFound"];

$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
file_put_contents($fileName, date("Y_m_d_H", strtotime("now")).':: M-'.$countM.'  :: F-'.$countF."\n", FILE_APPEND);

function sendCurlPostRequestJJ($urlToHit,$postParams,$timeout='',$headerArr="")
{
    if(!$timeout)
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
