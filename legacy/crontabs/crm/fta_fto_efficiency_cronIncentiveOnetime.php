<?php
$flag_using_php5 =1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db=connect_db();
$mysql=new Mysql;

		$todayDate	=date("Y-m-d");
		$allotTimeTaken	='2013-06-14 00:00:00';

		$ftoOfferArray		=array('4','5','6','7','10','11','12'); // fto offer states

		$sqlUpd ="update MIS.`FTO_EXEC_EFFICIENCY_MIS` SET FTO_INCENTIVE_DT=FTO_ACTIVATION_DT WHERE ALLOT_TIME<'$allotTimeTaken'";
		mysql_query_decide($sqlUpd,$db) or die("$sqlUpd".mysql_error_js());
			
                // Update the FTO_EXEC_EFFICIENCY_MIS records.
                $sqlEx ="select ID,PROFILEID,ALLOT_TIME,DEALLOCATION_DT,EXECUTIVE FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE ALLOT_TIME>='$allotTimeTaken'";
                $resEx =mysql_query_decide($sqlEx,$db) or die("$sqlEx".mysql_error_js());
                while($row=mysql_fetch_array($resEx)){

                                $idFto                  =$row['ID'];
                                $profileid              =$row['PROFILEID'];
                                $allotTime              =$row['ALLOT_TIME'];
                                $deAllocationDt         =$row['DEALLOCATION_DT'];
                                $allotTimeStamp         =JSstrToTime($allotTime);

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
                                }

                                        foreach($ftoOfferArray as $key=>$val){
                                                if(in_array("$val",$ftoStateAfterAllocArr)){
                                                        $ftoStateAfterAllocDt =$ftoStateAfterAllocDtArr[$val];
							$sql ="update MIS.FTO_EXEC_EFFICIENCY_MIS SET FTO_INCENTIVE_DT='$ftoStateAfterAllocDt' where ID='$idFto'";
							mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                                        break;
                                                }
                                        }

                                unset($ftoStateAfterAllocArr);
				unset($ftoStateAfterAllocDtArr);

                }/* Whileloop ends */

?>
