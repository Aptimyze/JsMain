<?php
class ResetPasswordAuthentication
{
    
/*this variable is added to add a differet level expiry for all the links
In case if we think that our links security has been compromised in some way and the hacker know some of the existing links,
we can reset this varibable and all the links will stop working and user will have to generate it again to reset the password*/
    public static $fixedMixer="atbowribfmseohjk";
    public static function generateLinkParams($profileid)
    {
	do
	{
		$resetLinkParams = array();
		do
		{
			$id=ResetPasswordAuthentication::createRandomId();
		} while (ResetPasswordAuthentication::idAlreadyExist($id));
		$resetLinkParams['ID']=$id;
		$resetLinkParams['HASH_ID']=ResetPasswordAuthentication::createRandomId();
		$resetLinkParams['PROFILEID']=$profileid;
		$resetDataObj = new newjs_SERIES;
		$resetDataSet = $resetDataObj->insert($resetLinkParams);
	} while (!$resetDataSet);
	return $resetLinkParams;
    }


    public static function generateResetLoginStr($resetLinkParams)
    {
	$hash = PasswordHashFunctions::encrypt($resetLinkParams['HASH_ID'],$resetLinkParams['ID'],ResetPasswordAuthentication::$fixedMixer);
	return $str="d=".$resetLinkParams['ID']."&h=".$hash;
    } 
    public static function getResetLoginStr($profileid)
    {
	$resetDataObj = new newjs_SERIES;
	$data = $resetDataObj->getArray(array('PROFILEID'=>$profileid),'','','*','','TIME DESC',1);
	$valid = ResetPasswordAuthentication::checkExpireCriteria($data);
	if(!$valid)
		$getData = ResetPasswordAuthentication::generateLinkParams($profileid);
	else
		$getData = $data[0];
	return ResetPasswordAuthentication::generateResetLoginStr($getData);
    }
    public static function validateResetLoginParams($d,$h)
    {
	if($d==''||$h=='')
		return false;
	$resetDataObj = new newjs_SERIES;
	$getData = $resetDataObj->getArray(array('ID'=>$d),'','','*','','TIME DESC',1);
	if(ResetPasswordAuthentication::checkExpireCriteria($getData))//compare date 3 days for expiry
	{
		$hash = PasswordHashFunctions::encrypt($getData[0]['HASH_ID'],$getData[0]['ID'],ResetPasswordAuthentication::$fixedMixer);
		$valid =  PasswordHashFunctions::slowEquals($hash,$h);
		if($valid)
			return $getData[0];
	}
	return false;
    }
    public static function checkExpireCriteria($getData)
    {
	$compareDate	= date("Y-m-d H:i:s");
	$diff = abs(JsCommon::dateDiff($getData[0]['TIME'],$compareDate));
        if(is_array($getData) && $getData[0]['USED']=="N" && $diff<1)
		return true;
	return false;
    }
    private static function createRandomId()
    {
	$uid = str_replace("=","_",str_replace( "+", ".", base64_encode( mcrypt_create_iv(24, MCRYPT_DEV_URANDOM ))));
	return $uid;
    } 
    public static function idAlreadyExist($id)
    {
	$resetDataObj = new newjs_SERIES;
	$getData = $resetDataObj->getArray(array('ID'=>$id));
	if(is_array($getData))
		return true;
	else
		return false;
    }
    public static function disableProfileidLinks($profileid)
    {
	$resetDataObj = new newjs_SERIES;
	return $resetDataObj->disableProfileidLinks($profileid);
    }

    public static function markIdUsed($id)
    {
	$resetDataObj = new newjs_SERIES;
	return $resetDataObj->updateUsed($id,'Y');
    }
}
?>
