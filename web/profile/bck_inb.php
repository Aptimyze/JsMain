<?
function create_temp_table()
{
	connect_db();
	$create_temp="CREATE   TEMPORARY TABLE `OFFLINE_NUDGES` (
                                                 `ID` int(11) NOT NULL,
                                                 `SENDER` mediumint(8) unsigned NOT NULL default '0',
                                                 `RECEIVER` mediumint(8) unsigned NOT NULL default '0',
                                                 `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
                                                 `RECEIVER_STATUS` char(1) NOT NULL default 'U',
                                                 `SENDER_STATUS` char(1) NOT NULL default 'U',
                                                `TYPE` char(10) NOT NULL default 'R', 
						`NUDGE`char(1) NOT NULL default 'N'
                                                )";
		
	mysql_query_decide($create_temp) or die($create_temp.mysql_error_js());

}
function insert_into_temp_table($data,$profileid,$sent='')
{
	$str="";

	for($i=0;$i<count($data);$i++)
	{
		if($sent=='')
		{
			$sender=$data[$i]['SENDER'];
			$receiver=$profileid;
			$status='RECEIVER_STATUS';
			$status_value=$data[$i]["RECEIVER_STATUS"];
		}
		else
		{
			$sender=$profileid;
			$receiver=$data[$i]['RECEIVER'];
			$status='SENDER_STATUS';
			$status_value=$data[$i]["SENDER_STATUS"];
		}

		$j=$i+1;
		if($j==count($data))
			$str.="('".$data[$i]["ID"]."','".$sender."','$receiver','".$data[$i]["DATE"]."','".$status_value."','".$data[$i]["TYPE"]."','".$data[$i]["NUDGE"]."') ";
		else
		 	$str.="('".$data[$i]["ID"]."','".$sender."','$receiver','".$data[$i]["DATE"]."','".$status_value."','".$data[$i]["TYPE"]."','".$data[$i]["NUDGE"]."'), ";

	}
	if($str!="")
	{
		$sql="insert into `OFFLINE_NUDGES`(`ID`,`SENDER`,`RECEIVER`,`DATE`,`$status`,`TYPE`,`NUDGE`) values $str";
		mysql_query_decide($sql) or die($sql.mysql_error_js());
		return true;
	}
	return false;
}
function set_msg_log_by_temp_table($profileid,$sent='')
{
	if($sent=='')
		$type='RECEIVER';
	else
		$type='SENDER';

	$sql="SELECT SENDER,RECEIVER,SENDER_STATUS,RECEIVER_STATUS,TYPE,`DATE`,ID,NUDGE FROM OFFLINE_NUDGES where $type='$profileid' order by `DATE` DESC";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	return $result;

}
function NUDGE_STATUS($PROFILEID,$N_SENDER)
{
	if($N_SENDER)
	{
		$sql="select PROFILEID,MATCH_ID,STATUS from jsadmin.OFFLINE_MATCHES where MATCH_ID='$PROFILEID' and PROFILEID IN($N_SENDER)";
		$res=mysql_query_decide($sql);
		while($row=mysql_fetch_array($res))
		{	
			$NSTATUS[$row['PROFILEID']]=$row['STATUS'];
		}
		return $NSTATUS;
	 }
	
}
function get_nudge_mes($PROFILEID,$folderid,$sent_items='')
{
	global $sortorder;
	global $N_SENDER;
	if($sent_items!='')
	{
		if($sortorder=='U')
                        $str=" and SENDER_STATUS='U' ";
                if($sortorder=='A')
                        $str=" and TYPE IN ('ACC') ";
                if($sortorder=='D')
                        $str=" and TYPE IN('REJ','NREJ') ";

	 	$sql="select RECEIVER,SENDER_STATUS,TYPE,`DATE`,ID from jsadmin.OFFLINE_NUDGE_LOG where SENDER='$PROFILEID'  and SENDER_STATUS!='D' $str order by `DATE` DESC";
	}
	else
	{
		if($sortorder=='U')
			$str=" and RECEIVER_STATUS='U' ";
		if($sortorder=='A')
			$str=" and TYPE='ACC' ";
		if($sortorder=='D')
			$str=" and TYPE='REJ' ";

 	$sql="select SENDER,RECEIVER_STATUS,TYPE,`DATE`,ID from jsadmin.OFFLINE_NUDGE_LOG where RECEIVER='$PROFILEID' and FOLDERID='$folderid' and RECEIVER_STATUS!='D' $str order by `DATE` DESC";
	}
	$res=mysql_query_decide($sql) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	while($row=mysql_fetch_array($res))
	{
		$NUDGE[]=$row;
		$N_SENDER.="'".$row[0]."'";
	}

	return $NUDGE;
}
function delete_mes($send_delete,$folderid,$del_arr,$nudge_arr)
{
	global $data,$mysql,$myDb;
	//if not sent item
		if(!$send_delete)
		{       
			if($folderid==1000)
				//deleting from TRASH
			{
				$sql_delete="UPDATE MESSAGE_LOG SET RECEIVER_STATUS='D',SENDER_STATUS='R'  WHERE ID IN ('$del_arr')";
				$sql_delete1="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET RECEIVER_STATUS='D',SENDER_STATUS='R'  WHERE ID IN ('$nudge_arr')";
			}
			else
				//moving mesg to trash
			{
				//Increment the counter by id's selected in NEW_MES field
				if(0==$folderid)
				{
					$num_dec=0;
					
					if($nudge_arr)
					{
						$sql_get_status="select RECEIVER_STATUS from jsadmin.OFFLINE_NUDGE_LOG where ID IN('$nudge_arr') and RECEIVER_STATUS='U' and FOLDERID=0";
				                $res_get_status=mysql_query_decide($sql_get_status)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
				                if(mysql_num_rows($res_get_status))
                				{	
				                        $num_dec=mysql_num_rows($res_get_status);
                				}
					}

					if($del_arr)
					{
						$sql_get_status="select RECEIVER_STATUS from newjs.MESSAGE_LOG where ID IN('$del_arr') and RECEIVER_STATUS='U' and FOLDERID=0";
						$res_get_status=$mysql->ExecuteQuery($sql_get_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
						if(mysql_num_rows($res_get_status))
						{
							$num_dec+=intval(mysql_num_rows($res_get_status));
						}
					}
					if($num_dec>=0)
					{
						$sSQL="Update newjs.CONTACTS_STATUS set NEW_MES=NEW_MES-$num_dec where PROFILEID='".$data['PROFILEID']."'";
						mysql_query_decide($sSQL) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sSQL,"ShowErrTemplate");
					}
				}
			
				$sql_delete="UPDATE MESSAGE_LOG SET FOLDERID=1000,SENDER_STATUS='R' WHERE ID IN ('$del_arr')";
				$sql_delete1="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET FOLDERID=1000,SENDER_STATUS='R' WHERE ID IN ('$nudge_arr')";
			}
		if($del_arr)
		 	$mysql->ExecuteQuery($sql_delete,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_delete,"ShowErrTemplate");
		if($nudge_arr)
			mysql_query_decide($sql_delete1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_delete1,"ShowErrTemplate");

		}
		else
		{
			if($del_arr)
			{
				$sql_delete="UPDATE MESSAGE_LOG SET SENDER_STATUS='D' WHERE ID IN ('$del_arr')";
				$mysql->ExecuteQuery($sql_delete,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_delete,"ShowErrTemplate");
			}
			if($nudge_arr)
			{
				$sql_delete="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='D' WHERE ID IN ('$nudge_arr')";
	                        mysql_query_decide($sql_delete) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_delete,"ShowErrTemplate");
			}
			$send='y';
			
		}
		

}
function mark_mes_unread($folderid,$del_arr,$nudge_arr)
{
	global $data,$mysql,$myDb;
	
	//Increment the counter by id's selected in NEW_MES field
	if(0==$folderid)
	{
		$num_inc=0;

		if($nudge_arr)
		{
			$sql_get_status="select RECEIVER_STATUS from jsadmin.OFFLINE_NUDGE_LOG where ID IN('$nudge_arr') and RECEIVER_STATUS!='U' and FOLDERID=0";
			$res_get_status=mysql_query_decide($sql_get_status)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
        	        if(mysql_num_rows($res_get_status))
			{
				$num_inc=mysql_num_rows($res_get_status);
			}
		}
		if($del_arr)
		{
			$sql_get_status="select RECEIVER_STATUS from newjs.MESSAGE_LOG where ID IN('$del_arr') and RECEIVER_STATUS!='U' and FOLDERID=0";
			$res_get_status=$mysql->ExecuteQuery($sql_get_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
			if(mysql_num_rows($res_get_status))
			{
				$num_inc+=mysql_num_rows($res_get_status);
			}
		}
		if($num_inc>0)
		{
		  	$sSQL="Update newjs.CONTACTS_STATUS set NEW_MES=NEW_MES+$num_inc where PROFILEID='".$data['PROFILEID']."'";
			mysql_query_decide($sSQL) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sSQL,"ShowErrTemplate");
		}
		
	}
	if($del_arr)
	{
		$sql="update MESSAGE_LOG SET RECEIVER_STATUS='U' WHERE ID IN ('$del_arr')";
		$mysql->ExecuteQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	
	if($nudge_arr)
	{
		$sql="update jsadmin.OFFLINE_NUDGE_LOG SET RECEIVER_STATUS='U' WHERE ID IN ('$nudge_arr')";
		mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

}
function move_mes($folderid,$msg_move,$del_arr,$nudge_arr)
{
	//Increment the counter by id's selected in NEW_MES field
	global $data,$mysql,$myDb;

	if(0==$folderid || $msg_move==0)
	{
		$num_inc=0;
	 	if($nudge_arr)
		{
			$sql_get_status="select RECEIVER_STATUS from jsadmin.OFFLINE_NUDGE_LOG where ID IN('$nudge_arr') and RECEIVER_STATUS='U' and FOLDERID='$folderid'";
	                $res_get_status=mysql_query_decide($sql_get_status)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
			if(mysql_num_rows($res_get_status))
			{
				$num_inc=mysql_num_rows($res_get_status);
			}
		}
		

		if($del_arr)
		{
			$sql_get_status="select RECEIVER_STATUS from newjs.MESSAGE_LOG where ID IN('$del_arr') and RECEIVER_STATUS='U' and FOLDERID='$folderid'";
			$res_get_status=$mysql->ExecuteQuery($sql_get_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_get_status,"ShowErrTemplate");
			if(mysql_num_rows($res_get_status))
			{
				$num_inc+=mysql_num_rows($res_get_status);
			}
		}
		if($num_inc>0)
		{
			if($msg_move==0)
				$sSQL="Update newjs.CONTACTS_STATUS set NEW_MES=NEW_MES+$num_inc where PROFILEID='".$data['PROFILEID']."'";
			else
				$sSQL="Update newjs.CONTACTS_STATUS set NEW_MES=NEW_MES-$num_inc where PROFILEID='".$data['PROFILEID']."'";

			mysql_query_decide($sSQL) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sSQL,"ShowErrTemplate");
		}
	}
	//moving mesg to the selected folder    
	if($nudge_arr)
	{
		$sql="update jsadmin.OFFLINE_NUDGE_LOG SET FOLDERID='$msg_move' WHERE ID IN ('$nudge_arr')";
	        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	if($del_arr)
	{
		$sql="update MESSAGE_LOG SET FOLDERID='$msg_move' WHERE ID IN ('$del_arr')";
		$mysql->ExecuteQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	}
}	

