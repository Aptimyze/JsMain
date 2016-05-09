<?php
$url="http://xmppdev.jeevansathi.com/jirasvn/svnsubmit.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_POST, 1);
foreach($_POST as $key=>$val)
{
	$postfields[]="$key=".urlencode($val);
}
curl_setopt($ch, CURLOPT_POSTFIELDS,implode("&",$postfields));
$result = curl_exec($ch);
curl_close($ch);
echo $result;
?>
