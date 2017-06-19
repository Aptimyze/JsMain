<?php
//to zip the file before sending it
include_once("config.php");
if($checksum)
{
	$profileChecksum = $checksum;
}
else
{
	if($profileid)
		$profileChecksum = md5($profileid)."i".$profileid;
	else
	{
		//mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com","No Profileid","No profileid in Partner_Profile_Match.php");
		//die;
	}
}

$url = $SITE_URL."/search/partnermatches?profileChecksum=".$profileChecksum;
header("Location: ".$url);
?>
