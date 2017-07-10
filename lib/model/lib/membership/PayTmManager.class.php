<?php

class PayTmManager
{
    
    public static function encrypt_e($input, $ky) {
        $key = $ky;
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $input = self::pkcs5_pad_e($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $iv = "@@@@&&&&####$$$$";
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }
    
    public static function decrypt_e($crypt, $ky) {
        
        $crypt = base64_decode($crypt);
        $key = $ky;
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $iv = "@@@@&&&&####$$$$";
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $crypt);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $decrypted_data = self::pkcs5_unpad_e($decrypted_data);
        $decrypted_data = rtrim($decrypted_data);
        return $decrypted_data;
    }
    
    public static function pkcs5_pad_e($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    public static function pkcs5_unpad_e($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        return substr($text, 0, -1 * $pad);
    }
    
    public static function generateSalt_e($length) {
        $random = "";
        srand((double)microtime() * 1000000);
        
        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data.= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data.= "0FGH45OP89";
        
        for ($i = 0; $i < $length; $i++) {
            $random.= substr($data, (rand() % (strlen($data))), 1);
        }
        
        return $random;
    }
    
    public static function checkString_e($value) {
        $myvalue = ltrim($value);
        $myvalue = rtrim($myvalue);
        if ($myvalue == 'null') $myvalue = '';
        return $myvalue;
    }
    
    public static function getChecksumFromArray($arrayList, $key, $sort = 1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = self::getArray2Str($arrayList);
        $salt = self::generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = self::encrypt_e($hashString, $key);
        return $checksum;
    }
    
    public static function verifychecksum_e($arrayList, $key, $checksumvalue) {
        $arrayList = self::removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = self::getArray2Str($arrayList);
        $paytm_hash = self::decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        
        $finalString = $str . "|" . $salt;
        
        $website_hash = hash("sha256", $finalString);
        $website_hash.= $salt;
        
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            return TRUE;
        } 
        else {
            return FALSE;
        }
    }
    
    public static function getArray2Str($arrayList) {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr.= self::checkString_e($value);
                $flag = 0;
            } 
            else {
                $paramStr.= "|" . self::checkString_e($value);
            }
        }
        return $paramStr;
    }
    
    public static function redirect2PG($paramList, $key) {
        $hashString = self::getchecksumFromArray($paramList);
        $checksum = self::encrypt_e($hashString, $key);
    }
    
    public static function removeCheckSumParam($arrayList) {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }
    
    public static function getTxnStatus($requestParamList) {
        return self::callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
    }
    
    public static function initiateTxnRefund($requestParamList) {
        $CHECKSUM = self::getChecksumFromArray($requestParamList, PAYTM_MERCHANT_KEY, 0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return callAPI(PAYTM_REFUND_URL, $requestParamList);
    }
    
    public static function callAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($postData)));

        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT,"JsInternal");    

        $jsonResponse = curl_exec($ch);

        // remove header from curl Response 
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $jsonResponse = substr($jsonResponse, $header_size);

        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }
} 

?> 
