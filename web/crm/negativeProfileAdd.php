<?php
include ("connect.inc");
include ("negativeListCommon.php");
include ("negativeListFlagArray.php");
if($_SERVER['DOCUMENT_ROOT'])
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
else
	include_once("../classes/NEGATIVE_TREATMENT_LIST.class.php");
$db =connect_db();
$empty=1;

if(authenticated($cid))
{
	$name=getname($cid);
	$dataSetArr =array();

	// Form submitted for the records insertion or records updateion
        if($submit)
	{
		if($jsUsername)
		{
			$sql ="select PROFILEID from newjs.JPROFILE where USERNAME='$jsUsername'";
			$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			$row =mysql_fetch_array($res);
			if($row['PROFILEID'])
				$validUsername =true;
			else
				$validUsername =false;
		}
		if(trim($selectTypeDropdown)=='')
		{
			$empty=0;
			$smarty->assign('check_type',1);
			$smarty->assign('empty_err_msg',1);
		}
		if(trim($mobile)=='' && trim($landline)=='' && trim($email)=='' && trim($website)=='' && !$validUsername)
		{
			$empty=0;
			$smarty->assign('empty_err_msg',1);	
		}
		if($landline && trim($std)=='')
		{
			$empty=0;
			$smarty->assign('check_std','1');
		}	
		if(trim($email))
		{
		 	if(checkemail($email)){
               	        	$empty=0;
               	        	$smarty->assign('check_email',1);
			}     
           	}
                if($isd)
                {
			$isd =checkPhoneNumber($isd);       
                        if(!$isd){
                                $empty=0;
                                $smarty->assign('check_isd','1');
                        }       
                }       
		if($mobile)
		{
			$mobile =checkPhoneNumber($mobile);
			if(!$mobile || strlen($mobile)!=10){
				$empty=0;
               	        	$smarty->assign('check_mobile','1');
			}
               	}
		if($landline)
		{
			if($std)
				$landline_with_std =$std.$landline;
			else
				$landline_with_std =$landline;	
			$landline_with_std =checkPhoneNumber($landline_with_std);
		 	if(!$landline_with_std){
                        	$empty=0;
               	        	$smarty->assign('check_landline','1');
               		}
		}
		if($std){
			$std =checkPhoneNumber($std);
			if(!$std){
                       		$empty=0;
                       	        $smarty->assign('check_std','1');
			}		
		}

		// Condition to display of any error occurs for the output fields
		if($empty==0)
		{
			$typeDropdown =create_dd("$selectTypeDropdown","competition_type");
			$smarty->assign("typeDropdown",$typeDropdown);
			$smarty->assign('jsProfileid',$jsProfileid);	
			$smarty->assign('jsUsername',$jsUsername);
			$smarty->assign('companyName',$companyName);
			$smarty->assign('domainName',$domainName);	
			$smarty->assign('personName',$personName);
			$smarty->assign('mobile',$mobile);
        		$smarty->assign('landline',$landline);
			$smarty->assign("std",$std);
			$smarty->assign("isd",$isd);
                        $smarty->assign('website',$website);
			$smarty->assign('email',$email);
			$smarty->assign('cityLocation',$cityLocation);
			$smarty->assign('address',$address);
			$smarty->assign('messengerId',$messengerId);
			$smarty->assign('ipAddress',$ipAddress);
                        $smarty->assign('comments',$comments);
			$smarty->assign("emailMatch",$emailMatch);
			$smarty->assign("mobileMatch",$mobileMatch);
			$smarty->assign("landlineMatch",$landlineMatch);
			//$smarty->assign("emailFormat",$emailFormat);
			
			$smarty->assign('cid',$cid);		
                        $smarty->display('negativeProfileAdd.htm');
		}
		// condition if all input fields are correct and satisfy the validation
                else
		{
                        if($emailMatch)
                                $matchedByArr[] ="EMAIL";
                        if($mobileMatch)
                                $matchedByArr[] ="MOBILE";
                        if($landlineMatch)
                                $matchedByArr[]="LANDLINE";
			$matchedBy =@implode(",",$matchedByArr);

			$dataSetArr['TYPE'] =$selectTypeDropdown;
			$dataSetArr['COMPANY'] =$companyName;
			$dataSetArr['WEBSITE'] =$website;
			$dataSetArr['NAME'] =$personName;
			$dataSetArr['EMAIL'] =$email;
			$dataSetArr['MOBILE'] =$mobile;
			$dataSetArr['ISD'] =$isd;
			$dataSetArr['STD_CODE'] =$std;
			$dataSetArr['LANDLINE'] =$landline;
			//$dataSetArr['ACTIVATED'] ='';
			$dataSetArr['MATCHED_BY'] =$matchedBy;
			$dataSetArr['MESSENGER_ID'] =$messengerId;
			$dataSetArr['IP_ADDRESS'] =$ipAddress;
			$dataSetArr['LOCATION'] =$cityLocation;
			$dataSetArr['USERNAME'] =$jsUsername;
			$dataSetArr['DOMAIN'] =$domainName;
			$dataSetArr['ADDRESS'] =$address;
			$dataSetArr['COMMENTS'] =$comments;
			$dataSetArr['ENTRY_BY'] =$name;
			$dataSetArr['ENTRY_DT'] =date("Y-m-d H:i:s");	

			// Records updation
			if($ID)
			{	
				$new_dataSetArr =$dataSetArr;
				unset($new_dataSetArr['ENTRY_DT']);
				$whereStr =" ID= ".$ID;
				UpdateRecords($new_dataSetArr,$whereStr,$db);
                                $msg= " Record Updated Successfully<br>";
                                $msg .="<a href=\"negativeProfileSearch.php?cid=$cid\">";
                                $msg .="Continue </a>";
			}
			// Records insertion
			else
			{
				insertRecords($dataSetArr,$db);	
				// Addded phonenumber in CRM Abusive Phone (* even if record does not exist in JPROFILE for the phone numbers)
				if($mobile || ($landline && $std))
				{
					if($mobile)
						$phoneNumArr[] =$mobile;
					if($landline)
						$phoneNumArr[] =$std.$landline;
					$phoneStr ="('".@implode("'),('",$phoneNumArr)."')";
					$sql ="INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES".$phoneStr;
					mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());

				}
				$msg= " Record Inserted Successfully<br>";
				$msg .="<a href=\"negativeProfileAdd.php?cid=$cid\">";
				$msg .="Continue </a>";
			}
			
                        // JPROFILE condition to update records Starts  
			if(!$jsProfileid)
			{
				if($validUsername)
					updateNegativeProfiles('USERNAME','USERNAME',$jsUsername,'',$db,$dataSetArr);
                        	if($email)
					updateNegativeProfiles('EMAIL','EMAIL',$email,'',$db,$dataSetArr);
				if($mobile)
					updateNegativeProfiles('PHONE_MOB','MOBILE',$mobile,'',$db,$dataSetArr);
				if($landline && $std)
					updateNegativeProfiles('PHONE_WITH_STD','LANDLINE',$landline,$std,$db,$dataSetArr);
			}

                        // JPROFILE condition ends

			$smarty->assign('cid',$cid);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	// condition for the normal execution of the complete page prior to submission	
	else
        {
		// condition if the page is normally executed for the update condition
		if($updateSet)
		{
			$sql ="select * from incentive.NEGATIVE_PROFILE_LIST WHERE ID='$ID'";
			$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$ID			=$row['ID'];
                                $type    	     	=$row['TYPE'];
				$jsProfileid		=$row['PROFILEID'];
                                $jsUsername           	=$row['USERNAME'];
                                $companyName       	=$row['COMPANY'];
                                $website     		=$row['WEBSITE'];
                                $domainName     	=$row['DOMAIN'];
                                $personName        	=$row['NAME'];
                                $email         		=$row['EMAIL'];
                                $mobile     		=$row['MOBILE'];
                                $isd          		=$row['ISD'];
                                $std    		=$row['STD_CODE'];
                                $landline   		=$row['LANDLINE'];
                                $cityLocation   	=$row['LOCATION'];
                                $address     		=$row['ADDRESS'];
                                $activated    		=$row['ACTIVATED'];
                                $matchedBy  		=$row['MATCHED_BY'];
                                $messengerId 		=$row['MESSENGER_ID'];
                                $ipAddress    		=$row['IP_ADDRESS'];
                                $comments    		=$row['COMMENTS'];
			}

			$smarty->assign("ID",$ID);
			$smarty->assign("jsProfileid",$jsProfileid);
                        $typeDropdown =create_dd("$type","competition_type");
                        $smarty->assign("typeDropdown",$typeDropdown);
			if($matchedBy){
				for($i=0; $i<count($matchedByArr);$i++)
				{
					$matchVal =STRTOUPPER($matchedByArr[$i]);
					if($matchVal=='EMAIL')
						$smarty->assign("emailMatch",'1');
					if($matchVal=='MOBILE')
						$smarty->assign("mobileMatch",'1');
					if($matchVal=='LANDLINE')
						$smarty->assign("landlineMatch",'1');
				}
			}
			$smarty->assign("jsUsername",$jsUsername);
			$smarty->assign("companyName",$companyName);
			$smarty->assign("website",$website);
                        $smarty->assign("domainName",$domainName);
                        $smarty->assign("personName",$personName);
                        $smarty->assign("email",$email);
                        $smarty->assign("mobile",$mobile);
                        $smarty->assign("isd",$isd);
                        $smarty->assign("std",$std);
                        $smarty->assign("landline",$landline);
                        $smarty->assign("cityLocation",$cityLocation);
                        $smarty->assign("address",$address);
                        $smarty->assign("activated",$activated);
                        $smarty->assign("messengerId",$messengerId);
                        $smarty->assign("ipAddress",$ipAddress);
                        $smarty->assign("comments",$comments);

		}
		else{
                        $typeDropdown =create_dd('',"competition_type");
                        $smarty->assign("typeDropdown",$typeDropdown);
		}
		$smarty->assign("updateSet",$updateSet);
		$smarty->assign('cid',$cid);
		$smarty->display("negativeProfileAdd.htm");
	}  
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
