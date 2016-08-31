<?php
/**
 * Name of user
 *
 */
class NameOfUser
{
    /**
     */
     
    /**
     * Private construct so nobody else can instance it
     *
     */
    public function __construct()
    {
    }
    
    public function getNameData($profileid)
    {
        if($profileid=='')
                return;
        $nameObj = new incentive_NAME_OF_USER();
        $nameData = $nameObj->getArray(array("PROFILEID"=>$profileid),'','','*');
	foreach($nameData as $k=>$v)
	{
		$finalData[$v['PROFILEID']]=$v;
	}
	return $finalData;
    }
    public function insertName($profileid,$name,$display)
    {
	$name_pdo = new incentive_NAME_OF_USER();
	$name_pdo->insertNameInfo($profileid,$name,$display);    
    }
    public function filterName($name)
    {
	return $name;
    }
    public function isNameAutoScreened($name,$gender)
    {
        $name = preg_replace('/[^A-Za-z\ ]/', '', $name);
        $name = preg_replace('/\s\s+/', ' ',$name);
	$name = strtolower($name);
	$nameArr = explode(" ",$name);
	if(count($nameArr)>3)
		return false;
	$validNameListObj = new newjs_ValidNameList($dbName="",$gender);
	$nameCountInScreenedList = $validNameListObj->haveName($nameArr);
	if($nameCountInScreenedList!=0 && (count($nameArr)==$nameCountInScreenedList))
		return true;
	return false;
    }
    public function showNameToProfiles($selfProfileObj,$otherProfileObjArr)
    {
	foreach($otherProfileObjArr as $k=>$v)
	{
		$profileArr[]=$v->getPROFILEID();
	}
	$profileArr[]=$selfProfileObj->getPROFILEID();
	$profileStr = "'".implode("','",$profileArr)."'";
	$nameData = $this->getNameData($profileStr);
	if(is_array($nameData))
	{
		$selfProfileid = $selfProfileObj->getPROFILEID();
		foreach($otherProfileObjArr as $k=>$v)
		{
				$otherProfileid = $v->getPROFILEID();
				$isSelfScreened = Flag::isFlagSet($FLAGID="name",$value=$selfProfileObj->getSCREENING());
				$isOtherScreened = Flag::isFlagSet($FLAGID="name",$value=$v->getSCREENING());
/*
print_r($nameData);
echo "self".$selfProfileid."\n";
echo "other".$otherProfileid."\n";
echo "self screened";
var_dump($isSelfScreened);
echo "\n";
echo "other screened";
var_dump($isOtherScreened);
echo "\n";
echo "subscription".$selfProfileObj->getSUBSCRIPTION();
die;
*/

				if(!is_array($nameData[$otherProfileid]) || $nameData[$otherProfileid]['DISPLAY']!="Y" || $nameData[$otherProfileid]['NAME']=='')
					$returnArr[$otherProfileid]=array("SHOW"=>false,"REASON"=>$v->getUSERNAME()." has not mentioned a name or has decided to not show name to other members");
				elseif(!$isOtherScreened)
					$returnArr[$otherProfileid]=array("SHOW"=>false,"REASON"=>$v->getUSERNAME()."'s name is under screening");
				elseif($nameData[$selfProfileid]['DISPLAY']!="Y"||$nameData[$selfProfileid]['NAME']=="")
					$returnArr[$otherProfileid]=array("SHOW"=>false,"REASON"=>"Please add your name and change its privacy to 'Show to all members' to see the name of ".$v->getUSERNAME());
				elseif(!$isSelfScreened)
					$returnArr[$otherProfileid]=array("SHOW"=>false,"REASON"=>"Your name is under screening, you will be able to see other members' names as soon as it gets screened");
				
				else
				{
					$name = $this->getNameStr($nameData[$otherProfileid]['NAME'],$selfProfileObj->getSUBSCRIPTION());
					$returnArr[$otherProfileid]=array("SHOW"=>true,"NAME"=>$name);
				}
		}
	}
/*
print_r($returnArr);die;
*/
	return $returnArr;
    }
    public function getNameStr($othername,$selfsubscription)
    {
        $othername = strtolower($othername);
	if($selfsubscription!='')
	{
		$finalName = ucwords($othername);
	}
	else
	{
		$nameArr = explode(" ",$othername);
		foreach($nameArr as $k=>$v)
		{
			if(strlen($v)>2)
			{
				$finalName = ucfirst($v);
				break;
			}
		}
		if($finalName=='')
		{
			$finalName = ucfirst($v);
		}
	}
	return $finalName;
    }
}
