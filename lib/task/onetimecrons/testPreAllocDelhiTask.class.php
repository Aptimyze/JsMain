<?php
class testPreAllocDelhiTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
			));

		$this->namespace        = 'oneTimeCron';
		$this->name             = 'testPreAllocDelhi';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
		The [salesPreAllocation|INFO] task does things.
		Call it with:
		[php symfony oneTimeCron:testPreAllocDelhi|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		error_reporting(0);
		sfContext::createInstance($this->configuration);
		$flag_using_php5=1;
		include_once(JsConstants::$docRoot."/profile/connect_db.php");
		$db = connect_slave81();

		$startDt = date("Y-m-d");
		$lastLogin = date("Y-m-d", strtotime("-15 days", strtotime($startDt)));
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE LAST_LOGIN_DT >= '{$lastLogin}' AND LAST_LOGIN_DT < '{$startDt}' AND CITY_RES='DE00'";
		$res = mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);
		$jprofileObj = new JPROFILE('newjs_slave');
		$historyObj = new incentive_HISTORY('newjs_slave');
		$jprofileAlertsObj = new JprofileAlertsCache('newjs_slave');
		$jprofileContactObj = new ProfileContact('newjs_slave');
		$agentAllocDetailsObj = new AgentAllocationDetails('newjs_slave');
		$countDNC = 0;
		$countDispTwentyFive = 0;
		$countDispCNC = 0;
		$countDispCNCAndTwentyFive = 0;
		while($row=mysql_fetch_array($res)){
			$isDNC=0;
			$profileid = $row['PROFILEID'];

			$fields="LAST_LOGIN_DT,PHONE_WITH_STD,PHONE_RES,PHONE_MOB,ISD,STD,HAVE_JCONTACT,MOB_STATUS,LANDL_STATUS,ACTIVATE_ON";
			$valueArray['ACTIVATED']="'Y'";
			$valueArray['INCOMPLETE']="'N'";
			$valueArray['PROFILEID'] = $profileid;
			$profileData=$jprofileObj->getArray($valueArray,$excludeArray,$greaterArray,$fields,$lessArray);

			$phoneNumStack = array();
			$haveJContact = $profileData[0]['HAVE_JCONTACT'];
			$phone_res = $profileData[0]['PHONE_WITH_STD'];
			if(!$phone_res && $profileData[0]['PHONE_RES']){
				$phone_res = $profileData[0]['STD'].$profileData[0]['PHONE_RES'];
			}
			if($phone_res){
				$phone_res = $agentAllocDetailsObj->phoneNumberCheck($phone_res);
				if($phone_res){
					array_push($phoneNumStack,"$phone_res");
				}
			}
			$phone_mob = $profileData[0]['PHONE_MOB'];
			if($phone_mob){
				$phone_mob = $agentAllocDetailsObj->phoneNumberCheck($phone_mob);
				if($phone_mob){
					array_push($phoneNumStack,"$phone_mob");
				}
			}

			unset($valueArray);
			$valueArray['PROFILEID'] = $profileid;
			$fieldsRequired = "ALT_MOBILE,ALT_MOB_STATUS";
			$contactDetails = $jprofileContactObj->getArray($valueArray,"","",$fieldsRequired);

			$phone_alternate = $contactDetails[0]['ALT_MOBILE'];
			$phone_alternate = $agentAllocDetailsObj->phoneNumberCheck($phone_alternate);
			if($phone_alternate){
				array_push($phoneNumStack,"$phone_alternate");
			}
			$phone_alternate2 = $agentAllocDetailsObj->getOtherPhoneNums($profileid);
			$phone_alternate2 = $agentAllocDetailsObj->phoneNumberCheck($phone_alternate2);
			if($phone_alternate2){
				array_push($phoneNumStack,"$phone_alternate2");
			}
			$DNCArray = $agentAllocDetailsObj->checkDNC($phoneNumStack);
			$isDNC = $DNCArray['STATUS'];

			if($isDNC){
				//echo $profileid."\n";
				$countDNC++;
				//disposition
				$allDispositionCount = $historyObj->getCountOfDisposition($profileid);
				$singleDispostionCount = $historyObj->getCountOfDisposition($profileid,'CNC');
				if($allDispositionCount > 25){
					$countDispTwentyFive++;
				}
				if($singleDispostionCount > 5){
					$countDispCNC++;
				}
				if($allDispositionCount > 25 && $singleDispostionCount > 5){
					$countDispCNCAndTwentyFive++;
				}
			}

		}
		echo "Total Profiles : ".$countDNC."\nProfiles with more than 25 Dispositions : ".$countDispTwentyFive."\nProfiles with more than 5 CNC Dispositions : ".$countDispCNC."\nProfiles with both more than 25 Dispositions and 5 CNC Dispositions : ".$countDispCNCAndTwentyFive."\n";

	}
}

