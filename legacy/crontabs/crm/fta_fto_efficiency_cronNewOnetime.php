<?php
$flag_using_php5=1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db=connect_db();
$mysql=new Mysql;

	//************************************    Condition after submit state  ***************************************
                $uname_arr      =array();
		$todayDate	=date("Y-m-d");
		$last15Days     =date("Y-m-d H:i:s",JSstrToTime("$todayDate -15 days"));
		$currentTime	=date("Y-m-d H:i:s");

		/* FTO States
                FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO = "2"; 
                FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO = "3"; 
                FTO_ELIGIBLE_NO_PHONE_NO_PHOTO = "1"; 
                FTO_ACTIVE_LEAST_THRESHOLD = "4"; 
                FTO_ACTIVE_BELOW_LOW_THRESHOLD = "5"; 
                FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD = "6"; 
                FTO_ACTIVE_ABOVE_HIGH_THRESHOLD = "7"; 
                FTO_EXPIRED_BEFORE_ACTIVATED = "9"; 
                FTO_EXPIRED_AFTER_ACTIVATED = "8"; 
                FTO_EXPIRED_INBOUND_ACCEPT_LIMIT = "10"; 
                FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT = "11"; 
                FTO_EXPIRED_TOTAL_ACCEPT_LIMIT = "12"; 
		*/   
		$ftoPhoneUnverArr       =array('1','3');			// phone unverified states	
		$ftoEligArray		=array('1','2','3');			// fto eligible states
		$ftoOfferArray		=array('4','5','6','7','10','11','12'); // fto offer states
		$ftoActivationArray 	=array('5','6','7','10','11','12');	// fto activation states
		$ftoActivationStr	=implode(",",$ftoActivationArray);
		$ftoOfferStr		=implode(",",$ftoOfferArray);
			
		// Get the currently active executives from the PSWRDS table
                $sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%FTAFTO%' AND LAST_LOGIN_DT>='$last15Days'";
                $res_unames = mysql_query_decide($sql_unames,$db) or die($sql_unames.mysql_error_js());
                while($row_unames = mysql_fetch_array($res_unames))
                	$uname_arr[] = $row_unames['USERNAME'];
		$uname_arr =array_unique($uname_arr);
		$uname_str ="'".@implode("','",$uname_arr)."'";


		// Get the last handled date for process ID,
                $crmHandledId           ='3';           // id for the last handled crm allocation               
                $deAllocationId         ='4';           // id for the last handled deallocation date
                $ftoPhotoHandledId      ='5';           // id for the last handled photo date
                $ftoPhoneHandledId      ='6';           // id for the last phone verified date
                $ftoStateHandledId      ='7';           // fto state changed handled id
                $crmTrackHandledId      ='8';           // id for the last handled crm allocation
		

		// New allotment of the profiles
		$previousIdArr =getLastHandledDate($crmHandledId,$db);
		$previousId =$previousIdArr['HANDLED_ID'];	
		$sql1 ="select ID,PROFILEID,ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT WHERE ID>'$previousId' AND ALLOTED_TO IN($uname_str) ORDER BY ID";
		$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
		while($row1=mysql_fetch_array($res1)){

			$crmId			=$row1['ID'];
			$profileid 		=$row1['PROFILEID'];
			$allotTime		=$row1['ALLOT_TIME'];
			$deAllocationDt 	=$row1['DE_ALLOCATION_DT']." 23:59:59";
			$allotedTo		=$row1['ALLOTED_TO'];

			addRecord($profileid,$allotTime,$deAllocationDt,$allotedTo,$db);

                        $sqlIns ="insert ignore into MIS.FTO_ACTIVITY_INFO(`PROFILEID`) VALUES('$profileid')";
                        mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js());

			updateLastHandledDate( $crmHandledId,$crmId,'HANDLED_ID',$db);				
		}
		unset($deAllocationDt);

                $previousIdArr2 =getLastHandledDate($crmTrackHandledId,$db);
                $previousId2 =$previousIdArr2['HANDLED_ID'];
                $sql2 ="select ID,PROFILEID,ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT_TRACK WHERE ID>'$previousId2' AND ALLOTED_TO IN($uname_str) ORDER BY ID";
                $res2 =mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
                while($row2=mysql_fetch_array($res2)){
                        $crmId2                  =$row2['ID'];
                        $profileid2              =$row2['PROFILEID'];
                        $allotTime2              =$row2['ALLOT_TIME'];
                        $deAllocationDt2         =$row2['DE_ALLOCATION_DT']." 23:59:59";
                        $allotedTo2              =$row2['ALLOTED_TO'];
                        addRecord($profileid2,$allotTime2,$deAllocationDt2,$allotedTo2,$db);
                        $sqlIns ="insert ignore into MIS.FTO_ACTIVITY_INFO(`PROFILEID`) VALUES('$profileid2')";
                        mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js());
                        updateLastHandledDate($crmTrackHandledId,$crmId2,'HANDLED_ID',$db);
                }
                unset($deAllocationDt);


		// Step 2	
		// Update the actual de-allocation date
		$lastDeAllocatedDateArr =getLastHandledDate($deAllocationId,$db);  
		$lastDeAllocatedDate =$lastDeAllocatedDateArr['DATE'];
		$sqlDel ="select PROFILEID,DEALLOCATION_DT,PROCESS_NAME from incentive.DEALLOCATION_TRACK WHERE DEALLOCATION_DT>'$lastDeAllocatedDate'";
		$resDel =mysql_query_decide($sqlDel,$db) or die("$sqlDel".mysql_error_js());
		while($rowDel=mysql_fetch_array($resDel)){

			$deAllotedPid 	=$rowDel['PROFILEID'];
			$deAllocationDt	=$rowDel['DEALLOCATION_DT'];
			$processName	=$rowDel['PROCESS_NAME'];

			$sql3 ="update MIS.FTO_EXEC_EFFICIENCY_MIS SET DEALLOCATION_DT='$deAllocationDt' WHERE PROFILEID='$deAllotedPid' AND ALLOT_TIME<'$deAllocationDt' AND DEALLOCATION_DT>'$deAllocationDt' ORDER BY ID DESC LIMIT 1";
			mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
			updateLastHandledDate($deAllocationId,$deAllocationDt,'',$db);
		}

		// Step 3
		// Update the FTO_EXEC_EFFICIENCY_MIS records.
		$sql ="select PROFILEID FROM MIS.FTO_ACTIVITY_INFO";
		$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res)){

                                $profileid              =$row['PROFILEID'];
                                $phoneVerifyDt          =$row['PHONE_VERIFY_DT'];
                                $photoDt                =$row['PHOTO_DT'];

                                // Find FTO State before Allocation
                                $sqlFto ="select STATE_ID,ENTRY_DATE,COMMENT from FTO.FTO_STATE_LOG WHERE PROFILEID='$profileid'";
                                $resFto =mysql_query_decide($sqlFto,$db) or die("$sqlFto".mysql_error_js());
                                while($rowFto=mysql_fetch_array($resFto)){
                                        $ftoStateId  =$rowFto['STATE_ID'];
                                        $ftoEntryDt  =$rowFto['ENTRY_DATE'];
                                        $comment     =$rowFto['COMMENT'];

                                        if(!in_array($ftoStateId,$ftoStateIdArr)){
                                                $ftoStateIdArr[] =$ftoStateId;
                                                $ftoStateAfterAllocDtArr[$ftoStateId] =$ftoEntryDt;
                                        }
                                        if($comment=='PHOTO' && $ftoStateId!=1 && $ftoStateId!=2)
                                                $photoUploadDtDurAlloc =$ftoEntryDt;
                                }

                                if(isset($photoUploadDtDurAlloc)){
                                        $sql_photo ="select PROFILEID from newjs.PHOTO_FIRST WHERE ENTRY_DT>='$photoUploadDtDurAlloc' AND PROFILEID='$profileid' limit 1";
                                        $res_photo =mysql_query_decide($sql_photo,$db) or die("$sql_photo".mysql_error_js());
                                        if($row_photo=mysql_fetch_array($res_photo))
                                                $sqlUpdateParamArr["PHOTO_DT"]=$photoUploadDtDurAlloc;
                                }

                                // Find FTO Offer
                                foreach($ftoOfferArray as $key1=>$val1){
                                	if(in_array($val1,$ftoStateIdArr)){
                                        	$ftoStateAfterAllocDt =$ftoStateAfterAllocDtArr[$val1];
                                        	$sqlUpdateParamArr["FTO_OFFER_DT"]=$ftoStateAfterAllocDt;
                                        	break;
                                        }
                                }

                                // Set FTO Phone
                                $sqlPhone ="select ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                                $resPhone =mysql_query_decide($sqlPhone,$db) or die("$sqlPhone".mysql_error_js());
                                if($rowPhone=mysql_fetch_array($resPhone)){
                                        $phoneVerifiedDt      =$rowPhone['ENTRY_DT'];
                                        $sqlUpdateParamArr["PHONE_VERIFY_DT"]=$phoneVerifiedDt;
                                }

				// Set FTO EOI
				$myDbName=getProfileDatabaseConnectionName($profileid);
				$myDb=$mysql->connect("$myDbName");
				$sqlEoi ="select DATE from newjs.MESSAGE_LOG WHERE SENDER='$profileid' AND TYPE='I' ORDER BY DATE ASC LIMIT 1";
				$resEoi =mysql_query_decide($sqlEoi,$myDb) or die("$sqlEoi".mysql_error_js());
				if($rowEoi=mysql_fetch_array($resEoi)){
					$eoiSendDate =$rowEoi['DATE'];
					$sqlUpdateParamArr["FIRST_EOI_DT"]=$eoiSendDate;
				}

				if(count($sqlUpdateParamArr)>0)
					updateRecord('',$profileid,$sqlUpdateParamArr,$db,'FTO_ACTIVITY_INFO');
				unset($sqlUpdateParamArr);
				unset($photoUploadDtDurAlloc);
				unset($ftoStateIdArr);
				unset($ftoStateAfterAllocDtArr);
								
		}/* Whileloop ends */

		// Step 4
                // Update the FTO_EXEC_EFFICIENCY_MIS records.
                $sqlEx ="select ID,PROFILEID,ALLOT_TIME,DEALLOCATION_DT,EXECUTIVE FROM MIS.FTO_EXEC_EFFICIENCY_MIS";
                $resEx =mysql_query_decide($sqlEx,$db) or die("$sqlEx".mysql_error_js());
                while($row=mysql_fetch_array($resEx)){

                                $idFto                  =$row['ID'];
                                $profileid              =$row['PROFILEID'];
                                $allotTime              =$row['ALLOT_TIME'];
                                $deAllocationDt         =$row['DEALLOCATION_DT'];
                                $allotTimeStamp         =JSstrToTime($allotTime);
				$deAllocationTimeStamp  =JSstrToTime($deAllocationDt);

                                // Find FTO State before Allocation
                                $sqlFto ="select STATE_ID,ENTRY_DATE from FTO.FTO_STATE_LOG WHERE PROFILEID='$profileid' AND ENTRY_DATE<='$deAllocationDt'";
                                $resFto =mysql_query_decide($sqlFto,$db) or die("$sqlFto".mysql_error_js());
                                while($rowFto=mysql_fetch_array($resFto)){
                                        $ftoStateId       =$rowFto['STATE_ID'];
                                        $ftoStateDate     =$rowFto['ENTRY_DATE'];

                                        if(JSstrToTime($ftoStateDate)>$allotTimeStamp){
                                                if(!in_array("$ftoStateId",$ftoStateAfterAllocArr)){
                                                        $ftoStateAfterAllocArr[] =$ftoStateId;
                                                        $ftoStateAfterAllocDtArr[$ftoStateId] =$ftoStateDate;
                                                }
                                        }
                                        else{
                                                $ftoStateBefAlloc       =$ftoStateId;
                                                $ftoStateDateBefAlloc   =$ftoStateDate;
                                        }
                                }

				// First EOI
                                $sql1 ="select FIRST_EOI_DT,PHOTO_DT from MIS.FTO_ACTIVITY_INFO where PROFILEID='$profileid'";
                                $res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
                                $row1=mysql_fetch_array($res1);
                                $firstEoiDt 	=$row1['FIRST_EOI_DT'];
				$photoUploadDt	=$row1['PHOTO_DT'];

                                if((JSstrToTime($firstEoiDt)>$allotTimeStamp) && (JSstrToTime($firstEoiDt)<$deAllocationTimeStamp))
                                        $sqlUpdateParamArr["FIRST_EOI_DT"]=$firstEoiDt;
				if((JSstrToTime($photoUploadDt)>$allotTimeStamp) && (JSstrToTime($photoUploadDt)<$deAllocationTimeStamp))
					$sqlUpdateParamArr["PHOTO_DT"]=$photoUploadDt;


                                // Set Fto Eligible
                                $setFtoElig =false;
                                if(in_array("$ftoStateBefAlloc",$ftoEligArray)){
                                        $setFtoElig =true;
                                        $sqlUpdateParamArr["FTO_ELIGIBILITY_DT"]=$ftoStateDateBefAlloc;
                                }
                                // Find FTO Offer                       
                                if($setFtoElig){
                                        foreach($ftoOfferArray as $key=>$val){
                                                if(in_array("$val",$ftoStateAfterAllocArr)){
                                                        $ftoStateAfterAllocDt =$ftoStateAfterAllocDtArr[$val];
                                                        $sqlUpdateParamArr["FTO_OFFER_DT"]=$ftoStateAfterAllocDt;
                                                        break;
                                                }
                                        }
                                }
                                // Set FTO Activation 
                                foreach($ftoActivationArray as $key1=>$val1){
                                        if(in_array($val1,$ftoStateAfterAllocArr)){
                                                $ftoStateAfterAllocDt =$ftoStateAfterAllocDtArr[$val1];
                                                $sqlUpdateParamArr["FTO_ACTIVATION_DT"]=$ftoStateAfterAllocDt;
                                                break;
                                        }
                                }
                                // Set FTO Phone
                                if(in_array("$ftoStateBefAlloc",$ftoPhoneUnverArr)){
                                        $sqlPhone ="select ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID='$profileid' AND ENTRY_DT>='$allotTime' AND ENTRY_DT<='$deAllocationDt' ORDER BY ID ASC LIMIT 1";
                                        $resPhone =mysql_query_decide($sqlPhone,$db) or die("$sqlPhone".mysql_error_js());
                                        if($rowPhone=mysql_fetch_array($resPhone)){
                                                $phoneVerifyDt      =$rowPhone['ENTRY_DT'];
                                                $sqlUpdateParamArr["PHONE_VERIFY_DT"]=$phoneVerifyDt;
                                        }
                                }

                                if(count($sqlUpdateParamArr)>0)
                                        updateRecord($idFto,'',$sqlUpdateParamArr,$db,'FTO_EXEC_EFFICIENCY_MIS');
                                unset($ftoStateAfterAllocArr);
				unset($ftoStateAfterAllocDtArr);
                                unset($sqlUpdateParamArr);
				unset($setFtoElig);

                }/* Whileloop ends */


									

		// Add/Update record in the table
		function addRecord($profileid,$allotTime,$deAllocationDt,$allotedTo,$db='')
		{
			$sql ="insert ignore into MIS.FTO_EXEC_EFFICIENCY_MIS(`PROFILEID`,`ALLOT_TIME`,`DEALLOCATION_DT`,`EXECUTIVE`) VALUES('$profileid','$allotTime','$deAllocationDt','$allotedTo')";
			mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());	
		}

                function updateRecord($idFto='',$profileid='',$sqlUpdateParamArr,$db='',$tableName)
                {
			foreach($sqlUpdateParamArr as $key=>$val){
                                $value="'".$val."'";
                                $strArray[]=$key."=".$value;
			}
			$kStr=implode(",",$strArray);
                        $sql ="update MIS.".$tableName." SET $kStr where ";
			if($idFto)
				$sql.="ID=$idFto";
			elseif($profileid)
				$sql.="PROFILEID=$profileid";	
                        mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                }
		function getLastHandledDate($sourceId,$db='')
		{
			$detailsArr =array();
			$sqlGet ="select SOURCE_ID,HANDLED_ID,DATE FROM incentive.LAST_HANDLED_DATE WHERE SOURCE_ID='$sourceId'";
			$resGet =mysql_query_decide($sqlGet,$db) or die($sqlGet.mysql_error_js());
			if($rowGet =mysql_fetch_array($resGet)){
				$detailsArr['HANDLED_ID']    	=$rowGet['HANDLED_ID'];
				$detailsArr['DATE']		=$rowGet['DATE'];
			}
			return $detailsArr;	
		}
                function updateLastHandledDate($sourceId,$paramvalue,$param='',$db='')
                {
                        if(!$param)
                                $param ='DATE';
                        $sqlGet ="update incentive.LAST_HANDLED_DATE SET `$param`='$paramvalue' WHERE SOURCE_ID='$sourceId'";
                        //mysql_query_decide($sqlGet,$db) or die($sqlGet.mysql_error_js());
                }
?>
