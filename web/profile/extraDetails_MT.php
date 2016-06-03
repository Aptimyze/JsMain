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
	curl_setopt($ch, CURLOPT_HEADER, 0);
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
