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
        $nameObj = new incentive_NAME_OF_USER();
        return $nameData = $nameObj->getArray(array("PROFILEID"=>$profileid),'','','*');
    }
    public function insertName($profileid,$name,$display)
    {
	$name_pdo = new incentive_NAME_OF_USER();
	$name_pdo->insertNameInfo($profileid,$name,$display);    
    }
    public function filterName($name)
    {
        $name = preg_replace('/[^A-Za-z\ ]/', '', $name);
        $name = preg_replace('/\s\s+/', ' ',$name);
	$name = ucwords($name);
	return $name;
    }
    public function isNameAutoScreened($name,$gender)
    {
	$nameArr = explode(" ",$name);
	if(count($nameArr)>3)
		return false;
	$validNameListObj = new newjs_ValidNameList();
	$nameCountInScreenedList = $validNameListObj->haveName($nameArr);
	if($nameCountInScreenedList!=0 && (count($nameArr)==$nameCountInScreenedList))
		return true;
	return false;
    }
    public function showNameToProfiles($selfProfileid,$otherProfilesArr,$selfProfileSubscription='')
    {
	$profileStr = "'".implode("','",$otherProfilesArr)."'";
	$profileStr.=",'".$selfProfileid."'";
	$nameData = $this->getNameData($profileStr);
	if(is_array($nameData))
	{
		foreach($nameData as $k=>$v)
                {
                        if($v['PROFILEID']==$selfProfileid)
                                $nameDataSelf =$v;
                        elseif(in_array($v['PROFILEID'],$otherProfilesArr))
                                $nameDataOther[$v['PROFILEID']] = $v;
		}
		foreach($otherProfilesArr as $k=>$v)
		{
			if(is_array($nameDataSelf) && is_array($nameDataOther[$v])  && $nameDataOther[$v]['DISPLAY']=="Y" && $nameDataSelf['DISPLAY']=="Y")
			{
				$name = $this->getNameStr($nameDataOther[$v]['NAME'],$selfProfileSubscription);
				$returnArr[$v]=array("SHOW"=>true,"NAME"=>$name);
			}
			else
				$returnArr[$v]=array("SHOW"=>false);
		}
	}
	return $returnArr;
    }
    public function getNameStr($othername,$selfsubscription)
    {
	if($selfsubscription!='')
	{
		$name = $othername;
	}
	else
	{
		$nameArr = explode(" ",$othername);
		$name = $nameArr[0];
	}
	return $name;
    }
}
