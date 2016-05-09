<?php 
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(9, new lime_output_color());

//$uid="52988c7106422";
$authKey="2fdb5a916872020afbc92a86b9726a28";
$authKey=decrypt($authKey);
$strRevAuthKey=strrev($authKey);
echo $revAuthKeyEncrypted=encrypt($strRevAuthKey);die;

$revAuthKey=decrypt($revAuthKeyEncrypted);
echo $authKeyGenerated=strrev($revAuthKey);die;


 $t->ok($validity != $catched, $message);
 

 function encrypt($str) {
		$iv =  'fedcba9876543210';
        $key = '0123456789abcdef';
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    function decrypt($code) {
		$iv =  'fedcba9876543210';
        $key = '0123456789abcdef';
        $code = hex2bin($code);

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

     function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }
 
 
?>
