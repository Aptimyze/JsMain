<?php
class JsAuthentication
{
	/***
	**** @class: JsAuthentication
	**** @version: 1.0;
	**** @author: Kumar Anand
	**** @date: 30th June 2011
	**** @license: Jeevansathi.com;
	****
	**** This class has the encrypt and decrypt functions for profileid
	****/
	
	private $errorMsg="";
	private $_KEY = "Radhe Shaam";
	private $_SUBKEY = "muhaafiz Khudi ke";

        /**
        * @author - lavesh
        * Create a encyrpted checksum based on user profileid and email to auto login purpose.
        * Dnt use js_encrypt function
        * @param pid profileid of user
        * @param email emailid of user.
        * @return encyrpted checksum
        */
        public static function jsEncrypt($pid,$email)
        {
                $checksum = md5($pid)."i".$pid;
                include_once(JsConstants::$docRoot."/classes/authentication.class.php");
                $protect=new protect;
                return $protect->js_encrypt($checksum,$email);
        }

	
	/*
	**** @function: js_encrypt
	**** input is plain text that is to be encrypted and output is cipher text
	*/
	public function js_encrypt($plainText)
	{
		//return rawurlencode(md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" . $plainText);
		return md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" . $plainText;
	}



	/*
	**** @function: js_decrypt
	**** input is cipher text that is to be decrypted and output is plain text or false on error
	*/
	public function js_decrypt($cipherText)
	{
		//$arrTmp = explode("|i|", rawurldecode($cipherText));
		$arrTmp = explode("|i|", $cipherText);
		$arrTmp[1]=stripslashes($arrTmp[1]);
		//this change was done for earlier usernmames which have special characters in them so as to remove backslas (/) that is added to them.
		if (md5($this->_KEY . md5($arrTmp[1]) . $this->_SUBKEY) == $arrTmp[0])
			return $arrTmp[1];
		else
			return false;
	}

	/** 
	 * This function is used to get the profileid from profilechecksum
	**/
	static public function jsDecryptProfilechecksum($profilechecksum)
	{
		$chkprofilechecksum=explode("i",$profilechecksum);
		if($chkprofilechecksum[0]==md5($chkprofilechecksum[1]))
			return $profileid=$chkprofilechecksum[1];
		else
			return NULL;
	}

	/** 
	 * This function is used to get the profilechecksum from profileid
	**/
	static public function jsEncryptProfilechecksum($profileid)
	{
		return md5($profileid) . "i" . $profileid;
	}
}
