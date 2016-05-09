<?php
/*************************js_encryption_functions.php**********************
	Created By			: Puneet Makkar
	Created on			: 18-May-2006
	Description			: This file defines encryption/decryption functions for jeevansathi.
******************************************************************************/

global $_KEY, $_SUBKEY;
$_KEY = "Radhe Shaam";
$_SUBKEY = "muhaafiz Khudi ke";

/*********************************************************************************************
	Function			: js_encrypt
	Input				: $plainText - plain text that is to be encrypted
	Output				: cipher text
	Description			: Encrypts the plain text.
**********************************************************************************************/
function js_encrypt($plainText)
{
	global $_KEY, $_SUBKEY;
	
	//return rawurlencode(md5($_KEY . md5($plainText) . $_SUBKEY) . "|i|" . $plainText);
	return md5($_KEY . md5($plainText) . $_SUBKEY) . "|i|" . $plainText;
}

/*******************************************************************************************
	Function			: js_decrypt
	Input				: $cipherText - cipher text that is to be decrypted
	Output				: plain text or false on error
	Description			: Decrypts the cipher text, and also validates the cipher text, because if cipher text is incorrect, then false is returned.
*********************************************************************************************/
function js_decrypt($cipherText)
{
	global $_KEY, $_SUBKEY;
	
	//$arrTmp = explode("|i|", rawurldecode($cipherText));
	$arrTmp = explode("|i|", $cipherText);
	$arrTmp[1]=stripslashes($arrTmp[1]);//this change was done for earlier usernmames which have special characters in them so as to remove backslash(/) that is added to them.
	if (md5($_KEY . md5($arrTmp[1]) . $_SUBKEY) == $arrTmp[0])
		return $arrTmp[1];
	else
		return false;
}

?>
