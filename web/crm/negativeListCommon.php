<?php
include_once(dirname(__FILE__)."/../classes/NEGATIVE_TREATMENT_LIST.class.php");
function updateNegativeProfiles($fName1='',$fName2='',$fValue1='',$fValue2='',$db='',$dataSetArr='')
{
	global $negativeListFlagArray;
	$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db);
	$setUpdate =false;
	$storeProfileArr=array();
	$profileArr	=array();

        if($fName1=='' && $fValue1=='')
                return;
	if($fName1=='USERNAME'){
		$queryStr1 ="$fName1 IN('$fValue1')";
		$queryStr2 ="$fName2 IN('$fValue1')";
	}
        else if($fName1=='PHONE_MOB' || $fName1=='PHONE_WITH_STD'){
		if($fValue2)
			$phoneNumber =$fValue2.$fValue1;
		else
			$phoneNumber =$fValue1;
                $queryStr1 ="PHONE_MOB IN('$phoneNumber','0$phoneNumber') OR PHONE_WITH_STD IN('$phoneNumber','0$phoneNumber')";
                $queryStr2 ="(MOBILE IN('$phoneNumber','0$phoneNumber') OR (LANDLINE IN('$fValue1','0$fValue1')";
		if($fValue2)
			$queryStr2 .=" AND STD_CODE IN('$fValue2','0$fValue2')";
		$queryStr2 .="))";

	        $sql_alt ="select distinct PROFILEID from newjs.JPROFILE_CONTACT where ALT_MOBILE IN('$phoneNumber','0$phoneNumber')";
        	$res_alt =mysql_query_decide($sql_alt,$db) or die("$sql_alt".mysql_error_js());
        	while($row_alt=mysql_fetch_array($res_alt))
        	        $profileArr[]  =$row_alt['PROFILEID'];

        	$sql_crm ="select distinct PROFILEID from incentive.PROFILE_ALTERNATE_NUMBER where ALTERNATE_NUMBER IN('$phoneNumber','0$phoneNumber')";
        	$res_crm =mysql_query_decide($sql_crm,$db) or die("$sql_crm".mysql_error_js());
        	while($row_crm=mysql_fetch_array($res_crm))
        		$profileArr[] =$row_crm['PROFILEID'];

		$profileArr =array_unique($profileArr);
		$profileStr ='';
		if(count($profileArr)>0){		
			$profileStr =@implode(",",$profileArr);
			$queryStr1 .=" OR PROFILEID IN($profileStr)";
		}
        }
        elseif($fName1=='EMAIL'){
                $queryStr1 ="$fName1 IN('$fValue1')";
                $queryStr2 ="$fName2 IN('$fValue1')";
        }

	if($queryStr1){
        $sql ="select PROFILEID,USERNAME,ACTIVATED,ISD,STD,PHONE_MOB,PHONE_RES,PHONE_WITH_STD,EMAIL,MESSENGER_ID,IPADD,CONTACT from newjs.JPROFILE WHERE ".$queryStr1;
        $res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
        {
		$ignoreInsert		=false;
		$phoneNumArr	 	=array();
                $selectTypeDropdown     =$dataSetArr['TYPE'];
                $name                   =$dataSetArr['ENTRY_BY'];
		$addParameters		=array();

		// Checking the profileid existence in array to skip for duplicate profileid	
		$profileidJs    =$row['PROFILEID'];
		if(in_array($profileidJs,$storeProfileArr))
			continue;	
		$storeProfileArr[]	=$row['PROFILEID'];
		// Ends

                $usernameJs     =$row['USERNAME'];
		$activatedJs    =$row['ACTIVATED'];
  		$isdJs          =$row['ISD'];
                $stdJs          =$row['STD'];
                $mobileJs       =$row['PHONE_MOB'];
                $landlineJs     =$row['PHONE_RES'];
		$phone_stdJs	=$row['PHONE_WITH_STD'];
		$emailJs        =$row['EMAIL'];
                $messengeridJs  =$row['MESSENGER_ID'];
		$ipAddressJs	=$row['IPADD'];
		$contactJs	=$row['CONTACT'];

		// JPROFILE data set 
		$dataSetArr['PROFILEID']	=$profileidJs;
		$dataSetArr['USERNAME']		=$usernameJs;
		$dataSetArr['ACTIVATED']	=$activatedJs;	
		$dataSetArr['EMAIL']		=$emailJs;
		$dataSetArr['MESSENGER_ID']	=$messengeridJs;	
		$dataSetArr['MOBILE']		=$mobileJs;
		$dataSetArr['ISD']		=$isdJs;
		$dataSetArr['STD_CODE']		=$stdJs;
		$dataSetArr['LANDLINE']		=$landlineJs;
		$dataSetArr['IP_ADDRESS']	=$ipAddressJs;
		$dataSetArr['ADDRESS']		=$contactJs;
		
		$sql_alt ="select ALT_MOBILE from newjs.JPROFILE_CONTACT where PROFILEID='$profileidJs'";
		$res_alt =mysql_query_decide($sql_alt,$db) or die("$sql_alt".mysql_error_js());
		if($row_alt=mysql_fetch_array($res_alt))
			$phoneNumArr[]	=$row_alt['ALT_MOBILE'];
		
		$sql_crm ="select distinct ALTERNATE_NUMBER from incentive.PROFILE_ALTERNATE_NUMBER where PROFILEID='$profileidJs'";
		$res_crm =mysql_query_decide($sql_crm,$db) or die("$sql_crm".mysql_error_js());
		while($row_crm=mysql_fetch_array($res_crm)){
			$phoneNumArr[] =$row_crm['ALTERNATE_NUMBER'];
		}
		$phoneNumArr =array_unique($phoneNumArr);
				
		// NEGATIVE TREATMENT LIST Added
		include_once(dirname(__FILE__)."/negativeListFlagArray.php");
		$f_viewable	=$negativeListFlagArray["$selectTypeDropdown"]['FLAG_VIEWABLE'];
		$f_inbox_eoi	=$negativeListFlagArray["$selectTypeDropdown"]['FLAG_INBOX_EOI'];
		$f_contact	=$negativeListFlagArray["$selectTypeDropdown"]['FLAG_CONTACT_DETAIL'];
		$f_outbound	=$negativeListFlagArray["$selectTypeDropdown"]['FLAG_OUTBOUND_CALL'];
		$f_inbound	=$negativeListFlagArray["$selectTypeDropdown"]['FLAG_INBOUND_CALL'];
		$f_chat_init    =$negativeListFlagArray["$selectTypeDropdown"]['CHAT_INITIATION'];

		$sqlTreatmentList ="select PROFILEID from incentive.NEGATIVE_TREATMENT_LIST where PROFILEID='$profileidJs'";
		$resTreatmentList =mysql_query_decide($sqlTreatmentList,$db) or die("$sqlTreatmentList".mysql_error_js());
		$rowTreatmentList =mysql_fetch_array($resTreatmentList);
		$pidTreatmentList =$rowTreatmentList['PROFILEID'];

		if($pidTreatmentList){
			if($f_viewable=='N')
				$addParameters['FLAG_VIEWABLE']=$f_viewable;
			if($f_inbox_eoi=='N')
				$addParameters['FLAG_INBOX_EOI']=$f_inbox_eoi;
			if($f_contact=='N')
				$addParameters['FLAG_CONTACT_DETAIL']=$f_contact;	
			if($f_outbound=='N')
				$addParameters['FLAG_OUTBOUND_CALL']=$f_outbound;
			if($f_inbound=='N')
				$addParameters['FLAG_INBOUND_CALL']=$f_inbound;
			if($f_chat_init=='N')
				$addParameters['CHAT_INITIATION']=$f_chat_init;
			$addParameters['TYPE']="$selectTypeDropdown";
			$NEGATIVE_TREATMENT_LIST->UpdateRecords($addParameters,$profileidJs);	
		}
		else{			
                        $addParameters["TYPE"]			=$selectTypeDropdown;
                        $addParameters["ENTRY_BY"]		=$name;
			$addParameters["FLAG_VIEWABLE"]		=$f_viewable;
			$addParameters["FLAG_INBOX_EOI"]	=$f_inbox_eoi;
			$addParameters["FLAG_CONTACT_DETAIL"]	=$f_contact;
			$addParameters["FLAG_OUTBOUND_CALL"]	=$f_outbound;
			$addParameters["FLAG_INBOUND_CALL"]	=$f_inbound;
			$addParameters["CHAT_INITIATION"]	=$f_chat_init;
                        $NEGATIVE_TREATMENT_LIST->addRecords($addParameters,$profileidJs);
		}
		//NEGATIVE TREATMENT LIST Ends
		
		// Update the record entered by Executive
		 
                if(!$setUpdate){
			$whereStr =" $queryStr2 "." AND PROFILEID='0'";
			UpdateRecords($dataSetArr,$whereStr,$db);
                        $setUpdate =true;
			$ignoreInsert =true;
			if(mysql_affected_rows()==0)
				insertRecords($dataSetArr,$db);
                }

		// Ignore the first record here , it gets update only
		if(!$ignoreInsert)
			insertRecords($dataSetArr,$db);

		// Insert other alternate phone numbers
		foreach($phoneNumArr as $key=>$val)
		{
			if($mobileJs!=$val && $phone_stdJs!=$val){	
				$dataSetArr['MOBILE'] =$val;
				insertRecords($dataSetArr,$db);
			}
		}
		if($mobileJs)
			$phoneNumArr[] =$mobileJs;
		if($phone_stdJs)
			$phoneNumArr[] =$phone_stdJs;
		$phoneStr ="('".@implode("'),('",$phoneNumArr)."')"; 
		$sql ="INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES".$phoneStr;
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

        }} // loop of each profileid ends
}

// function to add records in table incentive.NEGATIVE_PROFILE_LIST
function insertRecords($insertParamters,$db)
{
	if(is_array($insertParamters))
	{
		foreach($insertParamters as $k=>$v)
		{
			$kArray[]=$k;
			$vArray[]="'".$v."'";
		}
		if(!$insertParamters["ENTRY_DT"])
		{
			$kArray[]='ENTRY_DT';
			$vArray[]="now()";
		}
		$kStr=implode(",",$kArray);
		$vStr=implode(",",$vArray);
		$sql="INSERT INTO incentive.NEGATIVE_PROFILE_LIST($kStr) VALUES($vStr)";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        }
}

// function to update records in table incentive.NEGATIVE_PROFILE_LIST
function UpdateRecords($updateParamters,$whereStr,$db)
{
	if($whereStr && is_array($updateParamters))
	{
		foreach($updateParamters as $k=>$v)
		{
			$val="'".$v."'";
			$strArray[]=$k."=".$val;
		}
		$kStr=implode(",",$strArray);
		$sql="UPDATE incentive.NEGATIVE_PROFILE_LIST SET $kStr where $whereStr";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	}
}
?>
