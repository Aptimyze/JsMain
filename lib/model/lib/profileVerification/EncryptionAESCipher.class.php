<?php
/**
 * Description of EncryptionAESCipher
 * Library Class to handle encrytion using AES and cipher method
 *
 * @package     jeevansathi
 * @author      Reshu Rajput
 * @created     30 Nov 2017
 */
class EncryptionAESCipher
{
	const OPENSSL_CIPHER_NAME= 'aes-128-cbc';
    const CIPHER_KEY_LEN =16 ;//128 bits
    
   
	private static function fixKey($key) {
		if (strlen($key) < self::CIPHER_KEY_LEN) {
		//0 pad to len 16
		return str_pad("$key", self::CIPHER_KEY_LEN, "0"); 
		}
		if (strlen($key) > self::CIPHER_KEY_LEN) {
		//truncate to 16 bytes
		return substr($key, 0, self::CIPHER_KEY_LEN); 
		}
		return $key;
	}
	
	private static function getIV() {
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::OPENSSL_CIPHER_NAME));
		return $iv;
	}

	/**
	* Encrypt data using AES Cipher (CBC) with 128 bit key
	* 
	* @param type $key 
	-
	key should be 16 bytes long (128 
	bits)
	* @param type $iv 
	-
	initialization vector
	* @param type $data 
	-
	data to encrypt
	* @return encrypted data in base64 encoding with "iv" attached at end after a colon":"
	*/
	public static function encrypt($key, $data) {
		$vi = self::getIV();
		$key=hash ('sha512' , $key,false );
		$key=substr($key,0,self::CIPHER_KEY_LEN);
		$encodedEncryptedData = base64_encode(openssl_encrypt($data, self::OPENSSL_CIPHER_NAME,self::fixKey($key), OPENSSL_RAW_DATA, $iv));
		$encodedIV = base64_encode($iv);
		$encryptedPayload = $encodedEncryptedData.":".$encodedIV;
		return $encryptedPayload;
	}
	
	
	/**
	* Decrypt data using AES Cipher (CBC) with 128 bit key
	* 
	* @param type $key 
	-
	key should be 16 bytes long (128 bits)
	* @param type $data 
	-
	data to be decrypt
	ed in base64 encoding with iv attached at the end after a 
	colon":"
	* @return decrypted data
	*/
	public static function decrypt($key, $data) {
		$key=hash('sha512',$key,false );
		$key=substr($key,0,self::CIPHER_KEY_LEN);
		$parts = explode(':', $data); //Separate Encrypted data from iv.
		$encrypted = $parts[0];
		$iv = $parts[1];
		$iv = base64_decode($iv);
		$decryptedData = openssl_decrypt(base64_decode($encrypted),self::OPENSSL_CIPHER_NAME,self::fixKey($key), OPENSSL_RAW_DATA, $iv);
		return $decryptedData;
	}
	

}
