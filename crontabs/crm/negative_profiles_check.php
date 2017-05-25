<?php 
/***************************************************************************************************************************
Filename    : negative_profiles_check.php 
Description : Update the profile id in NEGATIVR_PROFILE_LIST.
Created On  : 28 March 2011
****************************************************************************************************************************/
$curFilePath = dirname(__FILE__)."/";  
include_once("/usr/local/scripts/DocRoot.php");

$path=$_SERVER['DOCUMENT_ROOT'];
include_once("$path/crm/connect.inc");
include_once("$path/crm/negativeListFlagArray.php");
include_once("$path/crm/negativeListCommon.php");
include_once("$path/classes/NEGATIVE_TREATMENT_LIST.class.php");

$db = connect_db();
$count =0;
$start_time =date("Y-m-d H:i:s");
$dateCheck  =date("Y-m-d H:i:s",JSstrToTime("$start_time -90 days"));
$dateCheck1  =date("Y-m-d H:i:s",JSstrToTime("$start_time -1 days"));

	// Process Start 1
	$sql = "SELECT * from incentive.NEGATIVE_PROFILE_LIST WHERE ENTRY_DT>'$dateCheck' AND PROFILEID=''";
	$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($myrow=mysql_fetch_array($myres))
	{
		$dataSetArr =array();
		$dataSetArr['TYPE'] 		=$myrow['TYPE'];;
		$dataSetArr['COMPANY'] 		=$myrow['COMPANY'];
		$dataSetArr['WEBSITE'] 		=$myrow['WEBSITE'];
		$dataSetArr['NAME'] 		=$myrow['NAME'];
		$dataSetArr['MATCHED_BY'] 	=$myrow['MATCHED_BY'];
		$dataSetArr['MESSENGER_ID'] 	=$myrow['MESSENGER_ID'];
		$dataSetArr['IP_ADDRESS'] 	=$myrow['IP_ADDRESS'];
		$dataSetArr['LOCATION'] 	=$myrow['LOCATION'];
		$dataSetArr['USERNAME'] 	=$myrow['USERNAME'];
		$dataSetArr['DOMAIN'] 		=$myrow['DOMAIN'];
		$dataSetArr['ADDRESS'] 		=$myrow['ADDRESS'];
		$dataSetArr['COMMENTS'] 	=$myrow['COMMENTS'];
		$dataSetArr['ENTRY_BY'] 	=$myrow['ENTRY_BY'];
		$dataSetArr['ENTRY_DT'] 	=$myrow['ENTRY_DT'];;

		$email 		=$myrow['EMAIL'];
      		$phone_mob 	=$myrow['MOBILE'];
		$phone_res 	=$myrow['LANDLINE'];
        	$phone_mob 	=checkPhoneNumber($phone_mob);
        	$phone_res 	=checkPhoneNumber($phone_res);
        	$std 		=checkPhoneNumber($myrow['STD_CODE']);
		$isd 		=checkPhoneNumber($myrow['ISD']);

		$dataSetArr['ISD']     =$isd;
		$dataSetArr['STD_CODE'] =$std;

		if($email){
                        $email= addslashes(stripslashes($email));
                        $rep_values =array("'", "\"");
                        $email =trim(str_replace($rep_values,'',$email));
			$email_check =checkemail($email);
			if(!$email_check){
				$dataSetArr['EMAIL'] =$email;
				updateNegativeProfiles('EMAIL','EMAIL',$email,'',$db,$dataSetArr);
				$count++;
			}
		}
		if($phone_mob){
			$dataSetArr['MOBILE'] =$phone_mob;	
			updateNegativeProfiles('PHONE_MOB','MOBILE',$phone_mob,'',$db,$dataSetArr);
			$count++;
		}
		
		if($phone_res){
			$dataSetArr['LANDLINE'] =$phone_res;
			updateNegativeProfiles('PHONE_WITH_STD','LANDLINE',$phone_res,$std,$db,$dataSetArr);
			$count++;
		}
	}
	
	// Process Start 2
	$storePidArr =array();
	$sql ="select a.PROFILEID,a.FIELD,i.NEW_VAL from newjs.CONTACT_ARCHIVE a,newjs.CONTACT_ARCHIVE_INFO i where a.CHANGEID=i.CHANGEID AND a.FIELD IN('EMAIL','PHONE_MOB','PHONE_RES','ALT_MOBILE') AND i.DATE>='$dateCheck1' ORDER BY i.ID DESC";
	$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
	while($myrow=mysql_fetch_array($myres))
	{
		$AddRecordFlag	=false;
		$pid =$myrow['PROFILEID'];
		if(in_array($pid,$storePidArr))
			continue;
		$storePidArr[]=$pid;
		$field 		=$myrow['FIELD'];
		$value		=$myrow['NEW_VAL'];	
	
		// 1. If already profileid exist in NEGATIVE_PROFILE_LIST, with old value , insert new record for that profileid with updated field
		$sql2 ="select * from incentive.NEGATIVE_PROFILE_LIST where PROFILEID='$pid' ORDER BY ID ASC";
		$myres2=mysql_query($sql2,$db) or die("$sql2".mysql_error($db));
		while($myrow2 =mysql_fetch_array($myres2)){
		
			$dataSetArr 	=array();	
			$AddRecordFlag 	=true;
			$emailNeg			=$myrow2['EMAIL'];
			$mobileNeg			=$myrow2['MOBILE'];
			$stdNeg				=$myrow2['STD_CODE'];
			$landlineNeg			=$myrow2['LANDLINE'];

                        $dataSetArr['TYPE']             =$myrow2['TYPE'];
                        $dataSetArr['COMPANY']          =$myrow2['COMPANY'];
                        $dataSetArr['WEBSITE']          =$myrow2['WEBSITE'];
                        $dataSetArr['DOMAIN']           =$myrow2['DOMAIN'];
                        $dataSetArr['NAME']             =$myrow2['NAME'];
                        $dataSetArr['LOCATION']         =$myrow2['LOCATION'];
                        $dataSetArr['MATCHED_BY']       =$myrow2['MATCHED_BY'];
                        $dataSetArr['COMMENTS']         =$myrow2['COMMENTS'];
                        $dataSetArr['ENTRY_BY']         =$myrow2['ENTRY_BY'];

                        // Get data from JPROFILE
                        $sqlJ ="select PROFILEID,USERNAME,EMAIL,PHONE_MOB,PHONE_RES,STD,ISD,ACTIVATED,MESSENGER_ID,IPADD,CONTACT,PHONE_WITH_STD from newjs.JPROFILE where PROFILEID='$pid'";
                        $myresJ=mysql_query($sqlJ,$db) or die("$sqlJ".mysql_error($db));
                        $myrowJ=mysql_fetch_array($myresJ);
			
			$emailJs			=$myrowJ['EMAIL'];
			$mobileJs			=$myrowJ['PHONE_MOB'];
			$phone_stdJs			=$myrowJ['PHONE_WITH_STD'];
			
                        $dataSetArr['PROFILEID']        =$myrowJ['PROFILEID'];
                        $dataSetArr['USERNAME']         =$myrowJ['USERNAME'];
                        $dataSetArr['ACTIVATED']        =$myrowJ['ACTIVATED'];
                        $dataSetArr['EMAIL']            =$myrowJ['EMAIL'];
                        $dataSetArr['MESSENGER_ID']     =$myrowJ['MESSENGER_ID'];
                        $dataSetArr['MOBILE']           =$myrowJ['PHONE_MOB'];
                        $dataSetArr['ISD']              =$myrowJ['ISD'];
                        $dataSetArr['STD_CODE']         =$myrowJ['STD'];
                        $dataSetArr['LANDLINE']         =$myrowJ['PHONE_RES'];
                        $dataSetArr['IP_ADDRESS']       =$myrowJ['IPADD'];
                        $dataSetArr['ADDRESS']          =$myrowJ['CONTACT'];
			
			if($field=='EMAIL'){
				if($emailJs ==$emailNeg){
					$AddRecordFlag =false;
					break;
				}	
			}
			elseif($field=='PHONE_MOB'){
				if(($mobileJs==$mobileNeg) || ($mobileJs==$landlineNeg)){
					$AddRecordFlag =false;
					break;
				}
				$phoneStr =$mobileJs;
			}
			elseif($field=='PHONE_RES'){
				$std_landlineNeg =$stdNeg.$landlineNeg;
				if(($phone_stdJs==$std_landlineNeg) || ($phone_stdJs==$mobileNeg)){
					$AddRecordFlag =false;
					break;
				}
				$phoneStr =$std_landlineNeg;
			}
			elseif($field=='ALT_MOBILE'){
				$value 		=str_replace('+','',$value);
				$phoneJsArr     =@explode("-",$value);
				$altMob		=$phoneJsArr[1];
				if(($altMob==$mobileNeg) || ($altMob==$landlineNeg)){
					$AddRecordFlag =false;
					break;
				}
				$dataSetArr['MOBILE'] =$altMob;
				$phoneStr =$altMob;
			}
		}
		if($AddRecordFlag){
                        insertRecords($dataSetArr,$db);
			if($phoneStr){
                        	$sql ="INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES('$phoneStr')";
                        	mysql_query($sql,$db) or die("$sql".mysql_error($db));
			}
                        $count++;
		}

		// 2. If profileid does not exist in NEGATIVE_PROFILE_LIST for the field, find the duplicate profileid for that field and enter a new entry
		if(mysql_num_rows($myres2)==0){

			$dataSetArr 	=array();
			$query1 	='';
			$value 		=str_replace('+','',$value);
	                if($field=='EMAIL'){
				$emailJs	=$value;
        	                $query1 	="EMAIL IN('$value')";
			}	
        	        elseif($field=='PHONE_MOB'){
				$phoneJsArr 	=@explode("-",$value);
				$isdJs		=$phoneJsArr[0];
				$mobileJs	=$phoneJsArr[1];
        	                $query1 	="MOBILE IN('$mobileJs','0$mobileJs') OR LANDLINE IN ('$mobileJs','0$mobileJs')";
			}
	                elseif($field=='PHONE_RES'){
				$phoneJsArr 	=@explode("-",$value);
				$isdJs          =$phoneJsArr[0];
				$stdJs       	=$phoneJsArr[1];
				$landlineJs     =$phoneJsArr[2];
				$phone_stdJs	=$stdJs.$landlineJs;
	                        $query1 ="(STD_CODE IN('$stdJs','0$stdJs') AND LANDLINE IN('$landlineJs','0$landlineJs')) OR MOBILE IN('$phone_stdJs','0$phone_stdJs')";
			}
	                elseif($field=='ALT_MOBILE'){
				$phoneJsArr     =@explode("-",$value);
				$isdJs          =$phoneJsArr[0];
				$mobileJs       =$phoneJsArr[1];
	                        $query1 ="MOBILE IN('$mobileJs','0$mobileJs') OR LANDLINE IN ('$mobileJs','0$mobileJs')";
	                }
			if($query1){
				$sql1 ="select TYPE from incentive.NEGATIVE_PROFILE_LIST where ".$query1." order by ID DESC limit 1";
				$myres1=mysql_query($sql1,$db) or die("$sql1".mysql_error($db));
				$myrow1 =mysql_fetch_array($myres1);
				if($myrow1['TYPE']){
	                	        $dataSetArr['TYPE'] =$myrow1['TYPE'];
					$dataSetArr['ENTRY_BY'] ='system';
				
					if($field=='EMAIL' && $emailJs){
						$dataSetArr['EMAIL'] =$emailJs;
                        	        	updateNegativeProfiles('EMAIL','EMAIL',$emailJs,'',$db,$dataSetArr);
					}
					elseif(($field=='PHONE_MOB' || $field=='ALT_MOBILE') && $mobileJs){
						$dataSetArr['MOBILE'] =$mobileJs;
						updateNegativeProfiles('PHONE_MOB','MOBILE',$mobileJs,'',$db,$dataSetArr);	
					}	
					elseif($field=='PHONE_RES' && $landlineJs){
						$dataSetArr['LANDLINE'] =$landlineJs;
				                $dataSetArr['STD_CODE'] =$stdJs;
						updateNegativeProfiles('PHONE_WITH_STD','LANDLINE',$landlineJs,$stdJs,$db,$dataSetArr);
					}
                        	}
			} // query1 ends
		} // 2 ends
	} // Process 2 ends

$end_time =date("Y-m-d H:i:s");
$str =$start_time."-".$end_time;
$str .="<br> Total profiles list:".$count; 
mail("manoj.rana@naukri.com","Negative Treatment List-Daily Update","$str");

?>
