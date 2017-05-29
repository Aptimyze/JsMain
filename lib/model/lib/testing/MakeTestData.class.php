<?php
class MakeTestData
{ 
	public static function createGetProfilePicTestData($row,$loggedIn,$other)
	{
                $sqlJprofileArr = MakeTestData::getUpdateJprofileQuery($row,$other,$loggedIn);
                $sqlArr = MakeTestData::getHavePhotoQueries($row['HAVEPHOTO'],$other);

                $sqlArr=array_merge($sqlArr,$sqlJprofileArr);
                MakeTestData::executeQueries($sqlArr);

                $filtersSql = MakeTestData::getFilterQuery($row['FILTERS_PASSED'],$other,$loggedIn);
                $contactQueries = MakeTestData::getContactQueries($row['CONTACT_STATUS'],$loggedIn,$other);

                if(is_array($contactQueries))
                {
                        $shardQueries = $contactQueries;
                        if(is_array($filtersSql))
                                $shardQueries = array_merge($shardQueries,$filtersSql);
                }
                elseif(is_array($filtersSql))
                {
                        $shardQueries = $filtersSql;
                }

                if(is_array($shardQueries))
                        MakeTestData::executeQueries($shardQueries,$shard = 1);

	}
	public static function firstTimeQueries($profiles)
	{
		$sqlArr[] = "UPDATE JPROFILE SET HAVEPHOTO='N', ACTIVATED='Y',RELIGION='1' WHERE PROFILEID IN ('".$profiles[0]."','".$profiles[1]."')";
		return $sqlArr;
	}
	public static function getFilterQuery($filterPassed,$other,$loggedIn)
	{
		$gender = substr(PictureArrayTest::$gender[$other],0,1);
		$sqlArr[] = "DELETE FROM newjs.JPARTNER WHERE PROFILEID='".$other."' OR PROFILEID='".$loggedIn."'";
		$sqlArr[] = "INSERT INTO newjs.JPARTNER (PROFILEID,GENDER) VALUES ('".$other."','".$gender."')";
		if($filterPassed=="Y")
			$religion="1";
		else
			$religion="2";
		$sqlArr[] = "UPDATE newjs.JPARTNER SET PARTNER_RELIGION='".$religion."',DATE=now() WHERE PROFILEID='".$other."'";
		return $sqlArr;
	}
	public static function getHavePhotoQueries($havePhoto,$profileid)
	{
		$sqlArr = array();
                $sqlArr[] = "DELETE from PICTURE_NEW where PROFILEID= '".$profileid."' OR PICTUREID='24162303'";
                $sqlArr[] = "DELETE from PICTURE_FOR_SCREEN_NEW where PROFILEID= '".$profileid."' OR PICTUREID='10919125'";
		$time = date("Y-m-d H:i:s");
		switch($havePhoto)
		{
			case "U":
				$sqlArr[] = "REPLACE INTO newjs.PICTURE_FOR_SCREEN_NEW (MainPicUrl,OriginalPicUrl,ProfilePic120Url,ProfilePic235Url,ProfilePicUrl,ProfilePic450Url,Thumbail96Url,TITLE,KEYWORD,PICTUREID,ORDERING,PROFILEID,PICFORMAT) VALUES ('JS/upic1.jpeg','','','','','','JS/upic2.jpeg','','','10919125','','".$profileid."','jpeg')";
				break;
			case "Y":
				$sqlArr[] = "INSERT INTO  `PICTURE_NEW` (  `PICTUREID` ,  `PROFILEID` ,  `ORDERING` ,  `TITLE` ,  `KEYWORD` ,  `UPDATED_TIMESTAMP` ,  `MainPicUrl` ,  `ProfilePicUrl` ,  `ThumbailUrl` ,  `Thumbail96Url` ,  `PICFORMAT` ,  `SearchPicUrl` , `MobileAppPicUrl` ,  `ProfilePic120Url` ,  `ProfilePic235Url` ,  `ProfilePic450Url` ,  `OriginalPicUrl` ,  `UNSCREENED_TITLE` ) VALUES ('24162303',  '".$profileid."',  '0',  '".$time."', NULL , NOW( ) ,  'JS/spic1.jpg', 'JS/spic2.jpg', 'JS/spic3.jpg', 'JS/spic4.jpg', NULL , 'JS/spic5.jpg', 'JS/spic6.jpg', 'JS/spic7.jpg', 'JS/spic8.jpg', 'JS/spic9.jpg', 'JS/spic10.jpg',  '');";
				break;
			case "":
			case "N":
			default:
				break;
		}
		return $sqlArr;
	}
	public static function getUpdateJprofileQuery($row,$other,$loggedIn)
	{
		$changeArr=array();
		$changeArr[] = " PHOTO_DISPLAY = '".$row['PHOTO_DISPLAY']."' ";
		$changeArr[] = " PRIVACY = '".$row['PRIVACY']."' ";
		$changeArr[] = " HAVEPHOTO = '".$row['HAVEPHOTO']."' ";
		$changeArr[] = " ACTIVATED = 'Y' ";
		if($row['FILTERS_PASSED']=="N")
			$changeArr[] = " RELIGION = '2' ";
		$changeStr = implode(",", $changeArr);
		$sql[] = "UPDATE newjs.JPROFILE SET ".$changeStr." WHERE PROFILEID='".$other."'";	
		$sql[] = "UPDATE newjs.JPROFILE SET RELIGION='1' WHERE PROFILEID='".$loggedIn."'";
		return $sql;
	}
	public static function executeQueries($sqlArr,$shard)
	{
		if($shard)
		{
			$shard--;
			 $dbName = JsDbSharding::getShardNo($shard,'');
		}
                $storeObj=  new StoreTable($dbName);
                $conn = $storeObj->getDBObject();
$sqlArr[]="SELECT 1";
		foreach($sqlArr as $k=>$query)
		{
			$sql = $conn->prepare($query);
			$sql->execute();
		}
		return;
	}
	public static function getContactQueries($contactStatus,$loggedIn,$other)
	{
		if($contactStatus=="DM" || $contactStatus=="")
			return;
		$sqlArr[] = "DELETE FROM newjs.CONTACTS WHERE CONTACTID='395009841' OR SENDER IN ('".$loggedIn."','".$other."')";
		$array1 = array('I','A','D','C','RE');
		$array2 = array("RI","RA","RD","RC","E");
		if(in_array($contactStatus,$array1))
		{
			$receiver = $loggedIn;
			$sender = $other;
		}
		elseif(in_array($contactStatus,$array2))
		{
				$receiver = $other;
				$sender = $loggedIn;
		}
		if(in_array($contactStatus,array("RI","RC","RA","RD","RE")))
			$contactStatus = substr($contactStatus,-1);
		$time = date("Y-m-d H:i:s");
		$sqlArr[] = "INSERT IGNORE INTO newjs.CONTACTS VALUES ('395009841','".$sender."','".$receiver."','".$contactStatus."','".$time."',1,NULL,'N',NULL,'')";
		return $sqlArr;
	}
	public static function getPHOTO_DISPLAY_LOGIC_testData($loggedIn,$other)
	{
                $storeObj=  new StoreTable();
                $conn = $storeObj->getDBObject();
                $query = "SELECT ID,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,LOGIN_STATUS,FILTERS_PASSED,CONTACT_STATUS,IS_PHOTO_SHOWN FROM PICTURE_DISPLAY_LOGIC";
                $sql = $conn->prepare($query);
                $sql->execute();
                $data=$sql->fetchAll();
		$count = count($data);
                foreach($data as $k=>$v)
                {
                        $result[$k][$k]['ID']=$v['ID'];
                        $result[$k][$k]['HAVEPHOTO']=$v['HAVEPHOTO'];
                        $result[$k][$k]['PHOTO_DISPLAY']=$v['PHOTO_DISPLAY'];
                        $result[$k][$k]['PRIVACY']=$v['PRIVACY'];
                        $result[$k][$k]['LOGIN_STATUS']=$v['LOGIN_STATUS'];
                        $result[$k][$k]['FILTERS_PASSED']=$v['FILTERS_PASSED'];
                        $result[$k][$k]['CONTACT_STATUS']=$v['CONTACT_STATUS'];
                        $result[$k][$k]['IS_PHOTO_SHOWN']=$v['IS_PHOTO_SHOWN'];
			$result[$k][$k]['loggedIn']=$loggedIn;
			$result[$k][$k]['other']=$other;
			$result[$count+$k][$count+$k]=$result[$k][$k];
			$result[$count+$k][$count+$k][ID]=$count+$k;
			$result[$count+$k][$count+$k]['loggedIn']=$other;
			$result[$count+$k][$count+$k]['other']=$loggedIn;
                }
		return $result;
	}
	public static function getFunctionPreVariablesValues($row)
	{
		$result['skipProfilePrivacy']=($row[FILTERS_PASSED]=="D")?"Y":"";

                if($row[CONTACT_STATUS]=="DM")
                        $result['contactsBetweenViewedAndViewer']='';
                else
                {
                        $result['contactsBetweenViewedAndViewer']=substr($row[CONTACT_STATUS],0,-1);//remove R from status and get data according to it
                }
                if($row[LOGIN_STATUS]=="Y")
                {
                        $result['viewerObj'] = new LoggedInProfile('',$row[loggedIn]);
                        $result['viewerObj']->getDetail($row['loggedIn'],'PROFILEID');
                }
                else
                        $result['viewerObj'] = '';

                $result['viewedObjArray'][0]= new Profile('',$row['other']);
                $result['viewedObjArray'][0]->getDetail($row['other'],"PROFILEID");;

		return $result;
	}
}
