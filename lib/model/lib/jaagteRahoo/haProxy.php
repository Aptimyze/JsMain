<?php
$urlHit ="lb.ieil.net/js/";
$username = "readjs";
$password = "password@234";
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $urlHit );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

$DOM = new DOMDocument;
$DOM->loadHTML($output);

$finder = new DomXPath($DOM);
$classname="active0";
$nodes = $finder->query("//*[contains(@class, '$classname')]");
foreach($nodes as $k=>$node)
{
	if($y= $node->childNodes->item(0)->nodeValue)
		$servers[]=$y;
}
print_r($servers);
