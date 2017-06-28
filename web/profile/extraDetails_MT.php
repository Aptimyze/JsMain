<?php

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/sphinx_search_function.php");

$checksumArr =explode("i",$checksum);
$profileid =$checksumArr[1];

if($profileid)
{
	$url = $SITE_URL."/search/partnermatches";
	$postParams = "profileChecksum=".$checksum."&callingSource=myjs";

	$ch = curl_init($url);
	$header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
	curl_setopt($ch, CURLOPT_HEADER, $header);
	curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
	if($postParams)
		curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if($postParams)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	$ExactMatchArrCnt = curl_exec($ch);
	echo $ExactMatchArrCnt;
}
else
	echo "error";
die;

?>
