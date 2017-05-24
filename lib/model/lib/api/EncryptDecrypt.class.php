<?php

/**
 * Encrypt_Decrypts.
 * Class to encrypt and decrypt the data.
 * @package    jeevansathi
 * @subpackage Api
 * @author     Nitesh Sethi
 */

class Encrypt_Decrypt {

    private $iv;
    private $key;
    
    /**
     * Set the Initialization vector and private key for encryption and decryption
     * @param void
     * @return void
     */
    
    function __construct() {
        $this->iv =  JsConstants::$initializationVector;
        $this->key =  JsConstants::$privateKey;
    }

	/**
     * Encrypt the data using mcrypt
     * @param string
     * @return encrupted hex string
     */
    function encrypt($str) {

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $this->iv);

        mcrypt_generic_init($td, $this->key, $this->iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }
	
	
	/**
     * decrypt the data using mcrypt
     * @param encrypted string
     * @return string
     */
    function decrypt($code) {

        $code = $this->hex2bin($code);

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $this->iv);

        mcrypt_generic_init($td, $this->key, $this->iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return utf8_encode(trim($decrypted));
    }
	
	/**
     * convert data from hex to binary
     * @param Hex string
     * @return string
     */
    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

    /*encrypt data to md5*/
    public static function encryptIDUsingMD5($data)
    {
        $id = md5($data)."i".$data;
        return $id;
    }

}

?>
