<?
	ini_set('max_execution_time',0);

	include('connect.inc');
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='bhavanakadwal@gmail.com';
               $msg1='delete other is being hit. We can wrap this to JProfileUpdateLib';
               $subject="delete other";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);

	$db=connect_slave();
	
	
	$sql="select PROFILEID from newjs.JPROFILE where MSTATUS='O' and ACTIVATED!='D' and SUBSCRIPTION='' " ;
	
	$res_profile=mysql_query_decide($sql);
	
	//mysql_close();
	
	$db=connect_db();
	
	mysql_select_db_js("newjs");
	$sql="CREATE TABLE `BACKUP_JPROFILE_MS` (`PROFILEID` MEDIUMINT( 11 ) NOT NULL DEFAULT '0')";
	mysql_query_decide($sql);
	while($row_profile=mysql_fetch_row($res_profile))
	{
				
				$profileid=$row_profile[0];
				$sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', MOD_DT=now() where PROFILEID='$profileid'";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

					// delete the contacts of this person
					//added by sriram to prevent the query on CONTACTS table being run several times on page reload.
					
					//finding the contactid(s) where sender is the profile being deleted.
					$sql="select CONTACTID, TYPE, RECEIVER from newjs.CONTACTS where SENDER='$profileid'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());
					while($myrow=mysql_fetch_array($result))
					{
						$contactid=$myrow["CONTACTID"];
						//inserting into DELETED_PROFILE_CONTACTS.
						$sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
						$res=mysql_query_decide($sql) or die(mysql_error_js());;
						if($res)
						{
							//deleting the records from CONTACTS table.
							$sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
							mysql_query_decide($sql) or die(mysql_error_js());;
					
							//updating the counts in leftpanel.
							/*if($myrow['TYPE']!='C')
							{
								if($myrow['TYPE']=='I')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$myrow[RECEIVER]'";
								elseif($myrow['TYPE']=='A')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
								elseif($myrow['TYPE']=='D')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
								mysql_query_decide($sql_upd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
							}*/
						}
					}
					mysql_free_result($result);
					
					//finding the contactid(s) where receiver is the profile being deleted.
					$sql="select CONTACTID, TYPE, SENDER from newjs.CONTACTS where RECEIVER='$profileid'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());;
					while($myrow=mysql_fetch_array($result))
					{
						$contactid=$myrow["CONTACTID"];
						//inserting into DELETED_PROFILE_CONTACTS.
						$sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
						$res=mysql_query_decide($sql) or die(mysql_error_js());
						if($res)
						{
							//deleting the records from CONTACTS table.
							$sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
							mysql_query_decide($sql) or die(mysql_error_js());;
					
							//updating the counts in leftpanel.
							/*if($myrow['TYPE']!='C')
							{
								if($myrow['TYPE']=='I')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$myrow[SENDER]'";
								elseif($myrow['TYPE']=='A')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
								elseif($myrow['TYPE']=='D')
									$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
								mysql_query_decide($sql_upd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
							}*/
						}
					}
					mysql_free_result($result);
					
					//finding the id(s) from MESSAGE_LOG where sender is the profile being deleted.
					$sql="select ID,RECEIVER,RECEIVER_STATUS from newjs.MESSAGE_LOG where SENDER='$profileid'";
					$result=mysql_query_decide($sql,"",1) or die(mysql_error_js());;
					while($myrow=mysql_fetch_array($result))
					{
						$contactid=$myrow["ID"];
						//inserting into DELETED_MESSAGE_LOG.
						$sql="insert ignore into newjs.DELETED_MESSAGE_LOG	 select * from newjs.MESSAGE_LOG where ID='$contactid'";
						$sql="insert ignore into newjs.DELETED_MESSAGE_LOG	 select * from newjs.MESSAGE_LOG where ID='$contactid'";
						$res=mysql_query_decide($sql,"",1) or die(mysql_error_js());;
						if($res)
						{
							//deleting from MESSAGE_LOG table.
							$sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
							mysql_query_decide($sql,"",1) or die(mysql_error_js());;
						}
						
						
					}
					mysql_free_result($result);
					
					//finding the id(s) from MESSAGE_LOG where receiver is the profile being deleted.
					$sql="select ID from newjs.MESSAGE_LOG where RECEIVER='$profileid'";
					$result=mysql_query_decide($sql,"",1) or die(mysql_error_js());;
					while($myrow=mysql_fetch_array($result))
					{
						$contactid=$myrow["ID"];
						//inserting into DELETED_MESSAGE_LOG.
						$sql="insert ignore into newjs.DELETED_MESSAGE_LOG select * from newjs.MESSAGE_LOG where ID='$contactid'";
						$res=mysql_query_decide($sql,"",1) or die(mysql_error_js());;
						if($res)
						{
							//deleting from MESSAGE_LOG table.
							$sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
							mysql_query_decide($sql,"",1) or die(mysql_error_js());;
						}
					}
					mysql_free_result($result);
					
					//deleting the record from CONTACTS_STATUS table.
					$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$profileid'";
//					mysql_query_decide($sql_del) or die(mysql_error_js());
					
/*					//mysql_close();
					$db=connect_211();
					
					//Deleting records from VIEW_LOG_TRIGGER table.
					$SUFFIX=getsuffix($profileid);
					$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER_$SUFFIX WHERE VIEWED='$profileid'";
					mysql_query_decide($sql_delete) or die(mysql_error_js());
					for($SUFFIX=1;$SUFFIX<11;$SUFFIX++)
					{
						$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER_$SUFFIX  WHERE VIEWER='$profileid'";
						mysql_query_decide($sql_delete) or die(mysql_error_js());
					}
					
					//mysql_close();
					$db=connect_db();
*/					
					$sql="insert into newjs.BACKUP_JPROFILE_MS(PROFILEID)values('$profileid')";
					mysql_query_decide($sql) or die(mysql_error_js());

					$sql="truncate table CONTACTS_STATUS";
//					mysql_query_decide($sql) or die(mysql_error_js());
	}
				
	
