<?php

function getchecksum($toid,$totype,$amount,$description , $redirecturl,$key)
{
	$str = "$toid|$totype|$amount|$description|$redirecturl|$key";
	$generatedChecksum = md5($str);
	return $generatedChecksum;
}

function verifychecksum($description,$amount,$status,$checksum,$key)
{
	$str = "$description|$amount|$status|$key";
    $generatedCheckSum = md5($str);

    if($generatedCheckSum == $checksum)
		return "true" ;
    else
		return "false" ;
}

?>
