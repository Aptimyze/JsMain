<?
/**
* Decrypt data from a CryptoJS json encoding string
*
* @param mixed $passphrase
* @param mixed $jsonString
* @return mixed
*/
class EncryptPassword{
static function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    $salt = hex2bin($jsondata["s"]);
    $ct = base64_decode($jsondata["ct"]);
    $iv  = hex2bin($jsondata["iv"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}

/**
* Encrypt value to a cryptojs compatiable json encoding string
*
* @param mixed $passphrase
* @param mixed $value
* @return string
*/
/*static function cryptoJsAesEncrypt($passphrase, $value){
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}*/
function cryptoJsAesEncrypt($passphrase, $value){
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}


static function generatePassword($string) {
/*    $ans = array();
    $string = str_split($string);
    #go through every character, changing it to its ASCII value
    for ($i = 0; $i < count($string); $i++) {

        #ord turns a character into its ASCII values
        $ascii = (string) ord($string[$i]);

        #make sure it's 3 characters long
        if (strlen($ascii) < 3)
            $ascii = '0'.$ascii;
        $ans[] = $ascii;
    }

    #turn it into a string
    return implode('', $ans);*/
    return md5($string);
}

/*static function decode($string) {
    $ans = '';
    $string = str_split($string);
    $chars = array();

    #construct the characters by going over the three numbers
    for ($i = 0; $i < count($string); $i+=3)
        $chars[] = $string[$i] . $string[$i+1] . $string[$i+2];

    #chr turns a single integer into its ASCII value
    for ($i = 0; $i < count($chars); $i++)
        $ans .= chr($chars[$i]);

    return $ans;
}*/


}
?>
