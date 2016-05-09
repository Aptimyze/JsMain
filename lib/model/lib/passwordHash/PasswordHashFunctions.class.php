<?php
/**
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 */
define("ITERATIONS", 1000);
define("SALT_SIZE", 24);
define("HASH_SIZE", 24);
class PasswordHashFunctions
{
	public static $algoUsed="sha256";
	public static $mixer="atbowribfmseohjk";
	public static $ORIGINAL_PASSWORD_MAXLENGTH = 50; 

	public static function createHash($password)
	{
                $salt = str_replace("&","e",str_replace("=","_",str_replace( "+", ".", base64_encode( mcrypt_create_iv(SALT_SIZE, MCRYPT_DEV_URANDOM )))));
		$hashGenerated = PasswordHashFunctions::encrypt($password,$salt);
		return PasswordHashFunctions::mixStrings($hashGenerated,$salt);
	}
	public static function mixStrings($string1,$string2)
	{
		$pointer=((ord(substr($string1,0,1))+ord(substr($string2,-1)))%7)+1;
		for($i=0;$i<(strlen($string1)/$pointer);$i++)
		{
			$strStart = ($i*$pointer);
			for($x=0;$x<$pointer;$x++)
			    $output.=$string1[$x+$strStart];
			for($x=0;$x<$pointer;$x++)
			    $output.=$string2[$x+$strStart];
		}
		return $output;
	}
	public static function unmixString($string)
	{
		$maxLength = strlen($string)/2;
		$pointer=((ord(substr($string,0,1))+ord(substr($string,-1)))%7)+1;
		for($i=0;$i<(strlen($string)/$pointer);)
		{
			$strStart = $i*$pointer;
			for($x=0;$x<$pointer;)
			{
			    if(strlen($output['STRING1'])<$maxLength)
			    {
				    $output['STRING1'].=$string[$x+$strStart];
				    $x++;
			    }
			    else
				break;
			}
			for(;$x<$pointer*2;$x++)
			{
			    if(strlen($output['STRING2'])<$maxLength)
				    $output['STRING2'].=$string[$x+$strStart];
			    else
				break;
			}
			$i+=2;
		}
		return $output;
	}
	public static function validatePassword($password, $passwordHash,$mixer='')
	{
		$params = PasswordHashFunctions::unmixString($passwordHash);
		$encryptedPassword = $params['STRING1'];
		$salt = $params['STRING2'];
		$passwordToValidate = PasswordHashFunctions::encrypt($password,$salt,$mixer);
		return PasswordHashFunctions::slowEquals($encryptedPassword,$passwordToValidate);
	}

	// Compares two strings $a and $b in length-constant time.
	public static function slowEquals($a, $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		return $diff === 0;
	}
	public static function encrypt($password,$salt,$mixer='')
	{
		if($mixer=='')
			$mixer = PasswordHashFunctions::$mixer;
		$password = $password.$mixer;
		return str_replace("&","e",str_replace("=","_",str_replace( "+", ".",base64_encode(PasswordHashFunctions::hashAlgo($password,$salt)))));
	}

	public static function hashAlgo($password, $salt)
	{
		$algorithm = PasswordHashFunctions::$algoUsed;
		$count = ITERATIONS;
		$key_length = HASH_SIZE;
		$hash_length = strlen(hash($algorithm, "", true));
		$block_count = ceil($key_length / $hash_length);
		$output = "";
		for($i = 1; $i <= $block_count; $i++) 
		{
			$last = $salt . pack("N", $i);
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			for ($j = 1; $j < $count; $j++) 
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			$output .= $xorsum;
		}
		return substr($output, 0, $key_length);
	}
}
?>
