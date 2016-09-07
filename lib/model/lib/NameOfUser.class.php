<?php
/**
 * Name of user
 *
 */
class NameOfUser
{
    // name to be stored in cache for 4 hours i.e. 4 * 3600
    private $cacheLifeTime = 14400; // 4 hours
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
        
        if(!is_array($profileid)){
                $profileid = array($profileid);
        }
        $pData = $this->getNameFromCache($profileid);
        $noCache = array();
        if(!empty($pData)){
                foreach($profileid as $pid){
                      if(array_key_exists($pid, $pData)) {
                                $finalData[$pid]=$pData[$pid];
                      }else{
                                $noCache[]      = $pid;
                      }
                }
        }else{
               $noCache =  $profileid;
        }
        //print_r($noCache);die;
        if(!empty($noCache)){
                $nameObj = new incentive_NAME_OF_USER();
                $profileid = "'".implode("','",$noCache)."'";
                $nameData = $nameObj->getArray(array("PROFILEID"=>$profileid),'','','*');
                foreach($nameData as $k=>$v)
                {
                        $this->setNameInCache($v['PROFILEID'],$v);
                        $finalData[$v['PROFILEID']]=$v;
                }
        }
       //print_r($finalData);die;
	return $finalData;
    }
    public function insertName($profileid,$name,$display)
    {
	if($profileid && ($name ||$display))
	{
		$name_pdo = new incentive_NAME_OF_USER();
		$name_pdo->insertNameInfo($profileid,$name,$display); 
		$pData = $this->getNameFromCache($profileid);
		if($name == ''){
			$name = $pData[$profileid]["NAME"];
		}
		if($display == ''){
			$display = $pData[$profileid]["DISPLAY"];
		}
		$this->setNameInCache($profileid,array("NAME"=>$name,"DISPLAY"=>$display,"PROFILEID"=>$profileid));
	}
    }
    public function updateName($profileid,$arr)
    {
	if($profileid && is_array($arr))
	{
		$name_pdo = new incentive_NAME_OF_USER();
		$name_pdo->updateNameInfo($profileid,$arr);
		$this->removeNameFromCache($profileid);
	}
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
        if(empty($otherProfileObjArr)){
                return array();
        }
	foreach($otherProfileObjArr as $k=>$v)
	{
		$profileArr[]=$v->getPROFILEID();
	}
	$profileArr[]=$selfProfileObj->getPROFILEID();
	$nameData = $this->getNameData($profileArr);
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
                        $namePartWithOutSpecialChar= preg_replace('/[.]/', '', $v);
                        if(strlen($namePartWithOutSpecialChar)>2)
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
    public function setNameInCache($profileId,$nameData){
            $memObject=JsMemcache::getInstance();
            $memObject->set('NAME_OF_USER_'.$profileId,serialize($nameData),$this->cacheLifeTime);
    }
    public function removeNameFromCache($profileId){
            $memObject=JsMemcache::getInstance();
            $memObject->remove('NAME_OF_USER_'.$profileId);
    }
    public function getNameFromCache($profileIds){
        $profileCache = array();
        $memObject=JsMemcache::getInstance();
        if(!is_array($profileIds)){
                $profileIds = array($profileIds);
        }
        foreach($profileIds as $pid){
                $nameData=$memObject->get('NAME_OF_USER_'.$pid);
                if(!empty($nameData)){
                        $profileCache[$pid] = unserialize($nameData);     
                }
        }
        return $profileCache;
    }
}
