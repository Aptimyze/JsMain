<?php

/*
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
Copyright (c) CCAvenue . 2003 - 2005 -- All Rights Reserved
PROJECT					:	   CCAvenue World
MODULE					:	   CC-World Transaction Page
FILE							:	   libFunctions.php3
DATE CREATED		:      September 25, 2003, 6:55:25 PM
DESCRIPTION			:	   It is a function file which is used to generate or verify checksum.
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
*/
?>


<?php
function getchecksum($MerchantId, $OrderId, $Amount, $WorkingKey, $currencyType, $redirectURL) {
    $str = "$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$redirectURL";
    $adler = 1;
    $adler = adler32($adler, $str);
    return $adler;
}

function verifyCheckSumAll($MerchantId, $OrderId, $Amount, $WorkingKey, $currencyType, $Auth_Status, $checksum) {
    $str = "$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$Auth_Status";
    $adler = 1;
    $adler = adler32($adler, $str);
    if ($adler == $checksum) return "true";
    else return "false";
}

function adler32($adler, $str) {
    $BASE = 65521;
    
    $s1 = $adler & 0xffff;
    $s2 = ($adler >> 16) & 0xffff;
    for ($i = 0; $i < strlen($str); $i++) {
        $s1 = ($s1 + Ord($str[$i])) % $BASE;
        $s2 = ($s2 + $s1) % $BASE;
        
        //echo "s1 : $s1 <BR> s2 : $s2 <BR>";
        
    }
    return leftshift($s2, 16) + $s1;
}

function leftshift($str, $num) {
    $str = DecBin($str);
    for ($i = 0; $i < (64 - strlen($str)); $i++) $str = "0" . $str;
    
    for ($i = 0; $i < $num; $i++) {
        $str = $str . "0";
        $str = substr($str, 1);
        
        //echo "str : $str <BR>";
        
    }
    return cdec($str);
}

function cdec($num) {
    for ($n = 0; $n < strlen($num); $n++) {
        $temp = $num[$n];
        $dec = $dec + $temp * pow(2, strlen($num) - $n - 1);
    }
    return $dec;
}
?>