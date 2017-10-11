<?php

	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	$t = new lime_test(100, new lime_output_color());

        $viewedId = '2351780';
        $viewerId = '224';

        $profileObj[0] = Profile::getInstance('newjs_master',$viewedId);
        $profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,AGE");
	$pictureDisplayLogic = FieldMap::getFieldLabel('photo_display_logic', '', 1);
//	$filterObj = new ProfileFilter();
	$db = JsDbSharding::getShardNo('2');
	$jpartner = new newjs_JPARTNER($db);
	$contactsObj = new newjs_CONTACTS($db);
	foreach($pictureDisplayLogic as $key=>$result)
	{
echo "\n\n******************************************\n\n";
echo $key."---";
		$arr = str_split($key);
//print_r($arr);
		$havephoto = $arr[0];
if($havephoto == 'N')
continue;
		$photoDisplay = $arr[1];
		$privacy = $arr[2];
		$loggedIn = $arr[3];
//if($loggedIn == 'Y')
//continue;
		$filtersPassed = $arr[4];
		$contactStatus = $arr[5].$arr[6];

		unset($updateArr);
		$updateArr['HAVEPHOTO']="$havephoto";
		$updateArr['PHOTO_DISPLAY']="$photoDisplay";
		$updateArr['PRIVACY']="$privacy";

		$jprofileObj = new JPROFILE();
		$jprofileObj->edit($updateArr,$viewedId,'PROFILEID');

		if($loggedIn == 'Y')
		{
			$db = JsDbSharding::getShardNo('2');
			$sql = "DELETE FROM CONTACTS WHERE SENDER IN ($viewerId,$viewedId) OR RECEIVER IN ($viewerId,$viewedId)";
			$contactsObj->runQuery($sql);

			$profileObj1=LoggedInProfile::getInstance('newjs_master',$viewerId);
			$profileObj1->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
		}
else
unset($data);

		if($privacy == 'F')
		{
			if($profileObj1)
			{
				$age = $profileObj1->getAGE();
				$sql = '';
				if($filtersPassed == 'Y')
					$sql = "UPDATE newjs.JPARTNER SET LAGE=".($age-1)." , HAGE=".($age+1)." WHERE PROFILEID=$viewedId";
				elseif($filtersPassed == 'N')
					$sql = "UPDATE newjs.JPARTNER SET LAGE=".($age-5)." , HAGE=".($age-1)." WHERE PROFILEID=$viewedId";
				if($sql != '')
				{
					$jpartner->runQuery($sql);
				}
			}
		}

		if($photoDisplay == 'C' || $privacy == 'F')
		{
			if($contactStatus != 'DM')
			{
				if($arr[5] == 'R')
				{
					$sender = $viewerId;
					$receiver = $viewedId;
					$type = $arr[6];
				}
				else
				{
					$sender = $viewedId;
					$receiver = $viewerId;
					$type = $arr[5];
				}
				$sql = "INSERT INTO newjs.CONTACTS (SENDER,RECEIVER,TYPE) VALUES ($sender,$receiver,'$type')";
				$contactsObj->runQuery($sql);
			}
		}
		$profileObj[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER,AGE");
		$obj = new PictureArray();
		$t->is($obj->getProfilePhoto($profileObj,'','','','','',''), $result , "final result $result");
//		$t->is($obj->getProfilePhoto($profileObj,'','','','','',$loggedIn,''), $result , "final result $result");
		unset($profileObj1);
//print_r($profileObj);
//if(++$prinka>19)
//die;
	}
/*
//        $profileObj1=Profile::getInstance('newjs_master','3187885');
$profileObj1=LoggedInProfile::getInstance('newjs_master','3187885');
        $profileObj1->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
        $obj = new PictureArray();
//        $r = $obj->getProfilePhoto($profileObj,'','','','','','Y','');
//        print_r($r);
$t->is($obj->getProfilePhoto($profileObj,'','','','','','Y',''), 'filteredPhoto', 'final result');
*/
?>	
