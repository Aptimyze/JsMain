<?php
include('connect.inc');
include_once("contact.inc");
$db=connect_db();
$data=authenticated($checksum);
$checksum=$data["CHECKSUM"];
			$frst_mes=1;
	if($data){
			
			//echo "1111111";
			$sql="select GENDER,USERNAME from JPROFILE where activatedKey=1 and  PROFILEID = '$sendersid'";
			$resgender=mysql_query_decide($sql);
			$genderrow=mysql_fetch_array($resgender);
			//echo "1111111111";
			//echo "222222";
			//mysql_free_result($resgender);
			$sql="select userID from userplane.users where userID='$sendersid'";
			$res=mysql_query_decide($sql);
			if(!($row=mysql_fetch_array($res)))
			{
				$sql="select USER as profileID from bot_jeevansathi.user_online where USER='$sendersid'";
				$res=mysql_query_decide($sql);
				if(!($row=mysql_fetch_array($res)))
				{
					echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"The user $genderrow[USERNAME] is now offline and cannot receive your chat request, please try later\"}";
					die;
				}
			}
			$sql="select count(*) from userplane.blocked where userID='$sendersid' and destinationUserID='$data[PROFILEID]'";
		        $res=mysql_query_decide($sql);
		        $row=mysql_fetch_row($res);
			if($row[0])
			{
				echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"The user $genderrow[USERNAME] blocked you from sending further request\"}";
				die;
			}
			
			$sql="select if(ACTION='D',if(DATEDIFF(now(),`DATE`)>15,1,0),1) as to_do from userplane.LOG_CHAT_REQUEST where SEN=$data[PROFILEID] and REC='$sendersid' and ACTION NOT IN ('I', 'T') order by ID DESC limit 1";
			$res=mysql_query_decide($sql);
			if($row=mysql_fetch_array($res))
			{
				$frst_mes=$row['to_do'];
					
			} 
			if($genderrow["GENDER"]==$data["GENDER"])
			{
				//echo "333333";
				//die;
				echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"You can chat with people of the opposite gender only\"}";
				exit;
			}else if(check_privacy_filtered1($receiversid,$sendersid) && !in_array(get_contact_status($sendersid,$receiversid),array('I','A','RA')))	{
				//echo "Chat cannot be initiated because you do not meet $genderrow[USERNAME]'s filters";
				echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"Chat cannot be initiated because you do not meet user's filters\"}";
				exit;
			}else{
                        		include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
		                        $NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST();
                		        $spamParamaters['CHAT_INITIATION']=1;
		                        if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($receiversid,$spamParamaters))
					{
						echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"Chat cannot be initiated because you do not meet user's filters\"}";
						exit;
					}
					//Sharding On Contacts done by Lavesh Rawat
					$contactResult=getResultSet("count(*) as CNT","$receiversid","",$sendersid,"","'D'");
					$rowdecline[0]=$contactResult[0]['CNT'];
					if($rowdecline[0] > 0)
					{
						echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"This user has declined your contact and hence you cannot chat with him/her\"}";
						exit;
					}
				
			}
			$newone=0;
			$sql_olduser="select count(*) from bot_jeevansathi.invite_send where PROFILEID='$sendersid'";
			$resold=mysql_query_decide($sql_olduser);
			$rowold=mysql_fetch_row($resold);
			if($rowold[0]>0)
				$newone=1;

			echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"Yes, you can chat\",'NewBeta':$newone}";
			exit;
	
	}
	else
	{
		
		echo "var ajaxResponse={'FRST_MES':$frst_mes,'MES':\"user not logged in.\"}";
		exit;
	}




?>
