<?php
class CCAvenueDolManager
{
    public static function getChecksum($MerchantId, $OrderId, $Amount, $WorkingKey, $currencyType, $redirectURL) {
        $str = "$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$redirectURL";
        $adler = 1;
        $adler = self::adler32($adler, $str);
        return $adler;
    }
    
    public static function verifyCheckSumAll($MerchantId, $OrderId, $Amount, $WorkingKey, $currencyType, $Auth_Status, $checksum) {
        $str = "$MerchantId|$OrderId|$Amount|$WorkingKey|$currencyType|$Auth_Status";
        $adler = 1;
        $adler = self::adler32($adler, $str);
        if ($adler == $checksum) return "true";
        else return "false";
    }
    
    public static function adler32($adler, $str) {
        $BASE = 65521;
        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for ($i = 0; $i < strlen($str); $i++) {
            $s1 = ($s1 + Ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
        }
        return self::leftshift($s2, 16) + $s1;
    }
    
    public static function leftshift($str, $num) {
        $str = DecBin($str);
        for ($i = 0; $i < (64 - strlen($str)); $i++) $str = "0" . $str;
        for ($i = 0; $i < $num; $i++) {
            $str = $str . "0";
            $str = substr($str, 1);
        }
        return self::cdec($str);
    }
    
    public static function cdec($num) {
        for ($n = 0; $n < strlen($num); $n++) {
            $temp = $num[$n];
            $dec = $dec + $temp * pow(2, strlen($num) - $n - 1);
        }
        return $dec;
    }
}
?> 