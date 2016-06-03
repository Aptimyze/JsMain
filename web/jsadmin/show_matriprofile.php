<?php
/***************************************************************************************************************************
FILE NAME		: show_matriprofile.php
DESCRIPTION		: This file shows the list of unalloted profiles which have matri-profile
			: as their main / addon service.
MODIFICATION DATE	: July 11th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("time.php");
include("matri_functions.inc");
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPA',$priv))
        {
		//when allot button is clicked.
		if($submit)
		{
			//if there exists a profile to allot.
			if($allot)
			{
				if($executive=='')
				{
					$smarty->assign("b",1);
					$smarty->assign("emsg",'Please choose the executive');
				}
				else
				{
					$n=0;
					//finding details all profiles who avail Matri profile and their matri-profile has not been completed.
					//$sql = "SELECT a.PROFILEID, a.USERNAME,  a.ENTRY_DT FROM billing.PURCHASES AS a LEFT JOIN billing.MATRI_COMPLETED AS b  ON a.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' AND (SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M' ) ORDER BY ENTRY_DT ASC";
					$sql = "SELECT c.PROFILEID, a.USERNAME,  c.ENTRY_DT FROM billing.MATRI_PURCHASES AS c join billing.PURCHASES AS a on a.BILLID=c.BILLID LEFT JOIN billing.MATRI_COMPLETED AS b ON c.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' ";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$k=0;
					while($row=mysql_fetch_array($res))
					{
						$profileid=$row['PROFILEID'];
						if($profileid==$allot[$n])
						{
							$entry_dt=$row['ENTRY_DT'];
							$sql1="SELECT COUNT(PROFILEID) cnt,MAX(ENTRY_DT) mdt, STATUS FROM billing.MATRI_PROFILE WHERE PROFILEID='$profileid' group by STATUS";
							$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
							$row1=mysql_fetch_array($res1);
							$cnt=$row1['cnt'];
							$stat=$row1['STATUS'];
							$m_entry_dt=$row1['mdt'];
							//if no entry exists in MATRI_PROFILE.
							if($cnt==0)
							{
								$username=$row['USERNAME'];
								//$scheduled_time=date("Y-m-d G:i:s", JSstrToTime($entry_dt)+7*24*60*60);
								$sql_ins="INSERT INTO billing.MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,ENTRY_DT,STATUS) VALUES($allot[$n],'$username','$executive',now(),'$entry_dt','N')";
								$res_ins=mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());
								$allot_msg[$k]=$allot[$n];

								$sql_msg="SELECT DISTINCT USERNAME FROM billing.PURCHASES WHERE PROFILEID='$allot_msg[$k]'";
								$res_msg=mysql_query_decide($sql_msg) or die("$sql_msg".mysql_error_js());
								while($row_msg=mysql_fetch_array($res_msg))
									$allot_name[$k]=$row_msg['USERNAME'];

								$smarty->assign("msg",1);
								$allotted_name .= " ".$allot_name[$k].", ";
								$n++;$k++;
							}
							else
							{
								//if entry is found in MATRI_PROFILE but it is older one.
								if($entry_dt>$m_entry_dt)
								{
									$username=$row['USERNAME'];
									if($stat=='H')
										$stat='N';

									$sql_ins="UPDATE billing.MATRI_PROFILE SET ENTRY_DT='$entry_dt',ALLOT_TIME=now(),STATUS='$stat' WHERE PROFILEID='$profileid'";
									$res_ins=mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());
									$allot_msg[$k]=$allot[$n];
									$sql_msg="SELECT DISTINCT USERNAME FROM billing.PURCHASES WHERE PROFILEID='$allot_msg[$k]'";
									$res_msg=mysql_query_decide($sql_msg) or die("$sql_msg".mysql_error_js());
									while($row_msg=mysql_fetch_array($res_msg))
										$allot_name[$k]=$row_msg['USERNAME'];

									$smarty->assign("msg",1);
									$allotted_name .= " ".$allot_name[$k].", ";
									$n++;$k++;
								}
							}
							$smarty->assign("allot_msg",$allot_msg[$k]);
							$smarty->assign("MSG","$allotted_name successfully allotted to $executive");
						}
					}
				}
			}
			else
			{
				$smarty->assign("MSG","No Profile(s) selected to allot.");
			}
		}
		//if put on hold button is clicked
		elseif($put_hold)
		{
			if($hold)
			{
				for($i=0;$i<count($profileid);$i++)
				{
					$pid = $profileid[$i];
					if($hold[$pid]=="H")
					{
						//insert into MATRI_ONHOLD
						$sql_ins = "REPLACE INTO billing.MATRI_ONHOLD(PROFILEID,USERNAME,ENTRY_DT,HOLD_TIME,HOLD_REASON,STATUS,ENTRYBY) VALUES('$pid','$username[$pid]','$entry_dt[$pid]',now(),'".addslashes($hold_reason[$pid])."','H','$user')";
						mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
						$username_str .= $username[$pid].", ";
					}
				}
				$smarty->assign("MSG","$username_str successfully put on hold");
			}
			else
				$smarty->assign("MSG","No Profile(s) selected put on hold");
		}
		//if unhold button is clicked
		elseif($unhold_profile)
		{
			if($unhold)
			{
				for($i=0;$i<count($profileid);$i++)
                                {
					$pid = $profileid[$i];
					if($unhold[$pid]=="U")
					{
						//update MATRI_ONHOLD
						$sql_upd = "UPDATE billing.MATRI_ONHOLD SET STATUS='N', UNHOLD_REASON='".addslashes(stripslashes($unhold_reason[$pid]))."', UNHOLD_BY='$user', UNHOLD_TIME=now() WHERE PROFILEID='$pid'";
						mysql_query_decide($sql_upd) or die($sql_upd.mysql_error_js());

						//update MATRI_PROFILE (if profile is allotted to an executiv)
						$sql_upd = "UPDATE billing.MATRI_PROFILE SET STATUS='N' WHERE PROFILEID='$pid'";
						mysql_query_decide($sql_upd) or die($sql_upd.mysql_error_js());

						$username_str .= $username[$pid].", ";
					}
				}
				$smarty->assign("MSG","$username_str successfully unholded.");
			}
			else
				$smarty->assign("MSG","No profile(s) selected to unhold.");
		}
		else
		{
			//finding details of allotted/unallotted profiles on hold.
			$profileid_arr = array();
			$sql = "SELECT PROFILEID,USERNAME, ENTRY_DT,ALLOTTED_TO, ALLOTTED_TIME, HOLD_REASON FROM billing.MATRI_ONHOLD WHERE STATUS='H'";
			$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$i=0;
			while($row = mysql_fetch_array($res))
			{
				$profileid_arr[] = $row['PROFILEID'];
				$unallotted_onhold[$i]['SNO']=$i+1;
				$unallotted_onhold[$i]['PROFILEID']=$row['PROFILEID'];
				$unallotted_onhold[$i]['USERNAME']=$row['USERNAME'];
				$unallotted_onhold[$i]['ENTRY_DT']=$row['ENTRY_DT'];
				$unallotted_onhold[$i]['ALLOTTED_TO']=$row['ALLOTTED_TO'];
				$unallotted_onhold[$i]['ALLOTTED_TIME']=$row['ALLOTTED_TIME'];
				$unallotted_onhold[$i]['HOLD_REASON']=$row['HOLD_REASON'];
				$i++;
				$smarty->assign("ONHOLD_EXISTS",1);
			}
		  
			$ts = time();
			$end_date = date("Y-m-d",$ts)." 23:59:59";
			$ts -= 7*24*60*60;
			$start_date = date("Y-m-d",$ts)." 00:00:00";
			$start_date ="2009-04-01 00:00:00";

			$sql="SELECT COUNT(DISTINCT(c.PROFILEID)) FROM billing.MATRI_PURCHASES AS c  join billing.PURCHASES AS a on a.BILLID=c.BILLID LEFT JOIN billing.MATRI_COMPLETED AS b ON c.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE'" ;
			$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
			$row = mysql_fetch_row($res);
			$unallotted_count = $row[0];

			//finding details of all profiles who avail Matri profile and their matri-profile has not been completed.
			$sql = "SELECT c.PROFILEID, a.USERNAME,  c.ENTRY_DT FROM billing.MATRI_PURCHASES AS c join billing.PURCHASES AS a on a.BILLID=c.BILLID LEFT JOIN billing.MATRI_COMPLETED AS b ON c.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' ";
			if(!$show_all)
			{
				$sql .= " AND a.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
				$smarty->assign("SHOW_ALL",1);
			}

			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			
			$k=0;

			while($row=@mysql_fetch_array($res))
			{
				$profileid=$row['PROFILEID'];
				$entry_dt=$row['ENTRY_DT'];

				$sql1="SELECT COUNT(PROFILEID) cnt,MAX(ENTRY_DT) mdt FROM billing.MATRI_PROFILE WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
				$row1=mysql_fetch_array($res1);
				$cnt=$row1['cnt'];
				$m_entry_dt=$row1['mdt'];
				//if no entry is found in MATRI_PROFILE or the entry in MATRI_PROFILE is an old entry, then it is unalloted.
				if(($cnt==0 || ($entry_dt > $m_entry_dt)) && !in_array($profileid,$profileid_arr))
				{
					$smarty->assign("flag",1);
					$unallotted[$k]['SNO']=$k+1;
					$unallotted[$k]['PROFILEID']=$row['PROFILEID'];
					$unallotted[$k]['USERNAME']=$row['USERNAME'];
					$unallotted[$k]['ENTRY_DT']=$row['ENTRY_DT'];
					$unallotted[$k]['SCHEDULED_TIME']=newtime($row['ENTRY_DT'],4,0,0);
					$k++;
				}
			}

			//finding distinct users who have matri-profile privilage.
			$sql2="SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE REGEXP 'MPU'";
			$res2=mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
			$i=0;
			while($row2=mysql_fetch_array($res2))
			{
				$allotted_to[$i]['SNO']=$i+1;
				$allotted_to[$i]['NAME']=$row2['USERNAME'];

				//finding count of profiles on progress for each user.
				$allotted_to[$i]['CNT_ONPROGRESS'] = get_matri_count("billing","MATRI_PROFILE","N",$row2['USERNAME']);

				//finding count of profiles on hold for each user.
				$allotted_to[$i]['CNT_ONHOLD'] = get_matri_count("billing","MATRI_PROFILE","H",$row2['USERNAME']);

				//finding count of profiles for follow up for each user.
				$allotted_to[$i]['CNT_FOLLOWUP'] = get_matri_count("billing","MATRI_PROFILE","F",$row2['USERNAME']);

				//finding count of profiles completed by a particular user
				$allotted_to[$i]['CNT_COMPLETED'] = get_matri_count("billing","MATRI_PROFILE","Y",$row2['USERNAME']);
				$i++;
			}

			//finding total count of unallotted profiles.
			$cnt_onprogress = get_matri_count("billing","MATRI_PROFILE","N");
			$smarty->assign("onprogress",$cnt_onprogress);

			//finding total count of profiles on hold.
			$onhold = get_matri_count("billing","MATRI_ONHOLD","H");
			$smarty->assign("onhold",$onhold);

			//finding total count of completed profiles.
			$Completed = get_matri_count("billing","MATRI_COMPLETED");
			$smarty->assign("Completed",$Completed);

			//finding total count of profiles for follow-up.
			$followup = get_matri_count("billing","MATRI_PROFILE","F");
			$smarty->assign("followup",$followup);

			$smarty->assign("unallotted",$unallotted);
			$smarty->assign("unallotted_onhold",$unallotted_onhold);
			$smarty->assign("allotted_to",$allotted_to);
			$smarty->assign("cnt_unallotted",$unallotted_count);
			$smarty->assign("cnt_executive",$i);
			$smarty->assign("executive",$executive);
			$smarty->assign("allotted_name",$allotted_name);
			$smarty->assign("NOT_SUBMITTED",1);
		}
		$smarty->assign("checksum",$checksum);
		$smarty->assign("scriptname","show_matriprofile.php");
		$smarty->assign("SEARCH_BAND",$smarty->fetch("search_matri_profile.htm"));
		$smarty->assign("MATRI_MESSAGE",$smarty->fetch("matri_message.htm"));
		$smarty->display("show_matriprofile.htm");			
        }
        else
        {
                echo "You don't have permission to view this mis";
                die();
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>

