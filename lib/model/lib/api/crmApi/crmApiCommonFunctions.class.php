<?php
//This is a common library for crm API module where common/general functions can be defined

class crmApiCommonFunctions
{
	private $_KEY;
	private $_SUBKEY;
	private $encryptSeparator;

	public function __construct($key="",$subkey="",$encryptop="")
	{
		$this->_KEY = $key;
		$this->_SUBKEY = $subkey;
		$this->encryptSeparator = $encryptop;
	}


	/*
	**** @function: ecrypt
	* input is plain text that is to be encrypted and output is cipher text using base64_encode
	*/
	public function ecrypt($str)
	{
		$key = $this->_KEY;
		for($i=0; $i<strlen($str); $i++) {
			 $char = substr($str, $i, 1);
			 $keychar = substr($key, ($i % strlen($key))-1, 1);
			 $char = chr(ord($char)+ord($keychar));
			 $result.=$char;
		}
		return urlencode(base64_encode($result));
	}

	/*
	**** @function: js_encrypt
	**** input is plain text that is to be encrypted and output is cipher text
	*/
	public function js_encrypt($plainText,$email="")
	{
		//Embedding mail id
		$cur_time=time();
		if($email)
		{
				$email=$this->ecrypt($email);
		}
		$extra_params=$this->encryptSeparator.$cur_time.$this->encryptSeparator.$email;

		return md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" .$plainText.$extra_params;
	}

	/*
	**** @function: js_decrypt
	**** input is cipher text that is to be decrypted and output is plain text or false on error using md5
	*/
	public function js_decrypt($cipherText,$fromAutoLogin = "N")
	{
		//$arrTmp = explode("|i|", rawurldecode($cipherText));
		$arr=explode($this->encryptSeparator,$cipherText);
		$arrTmp = explode("|i|", $arr[0]);
		$arrTmp[1]=stripslashes($arrTmp[1]);
		//this change was done for earlier usernmames which have special characters in them so as to remove backslas (/) that is added to them.
		if (md5($this->_KEY . md5($arrTmp[1]) . $this->_SUBKEY) == $arrTmp[0])
		{
			
				return true;
		}
		else
			return false;
	}

	/*
	**** @function: explode_assoc
	*/
	public function explode_assoc($glue1, $glue2, $array)
	{
		$array2=explode($glue2, $array);
		foreach($array2 as  $val)
		{
			$pos=strpos($val,$glue1);
			$key=substr($val,0,$pos);
			$array3[$key] =substr($val,$pos+1,strlen($val));
		}
		return $array3;
	}

	/*
	**** @function: encryptAppendTime
	**** input is cipher text and output is 
	*/
	public function encryptAppendTime($checksum)
	{
		//Encrypting Checksum
		$encryptObj= new Encrypt_Decrypt();
		$encryptAuthChecksum=$encryptObj->encrypt($checksum);
		return $encryptAuthChecksum;
	}

	/*public function mapPrivilegeLinks($privilegeArray) ---privilege based not being used in FSO
	{
		$result = array();
		foreach(commonInterfaceDisplay::$privilegeMapping as $text=>$privilegeList)
		{
			if(array_intersect($privilegeList,$privilegeArray))
				$result[] = $text;
		}
		$result= array("Upload profile verification documents","Sync profile verification documents","Edit address of profile");
		return $result;
	}*/
}
?>