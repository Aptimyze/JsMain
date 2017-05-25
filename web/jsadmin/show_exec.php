<?php
/***************************************************************************************************************************
FILE NAME		: show_matri_exec.php
DESCRIPTION		: This file shows the list of alloted/followup/onhold/to verify profiles.
MODIFICATION DATE	: July 11th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("matri_functions.inc");
include("time.php");
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPA',$priv))
        {
		$x=0;
		$dispname='';
		//if verify button is clicked.
		if($verify)
		{
			if($PASS)
			{
				$sql="SELECT PROFILEID FROM billing.MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='Y'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$profileid=$row['PROFILEID'];
					if($PASS[$profileid]=="Y")
					{
						$verified_username_str .= $username[$profileid].", ";
						$sql_upd = "UPDATE billing.MATRI_PROFILE SET VERIFIED_BY='$user',COMPLETION_TIME=now(),STATUS='NY' WHERE PROFILEID='$profileid'";
						mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());
					}
					elseif($PASS[$profileid]=="N")
					{
						$discarded_username_str .= $username[$profileid].", ";
						$sql_upd = "UPDATE billing.MATRI_PROFILE SET STATUS='N', RATING='$rating[$profileid]', RATED_BY='$user' WHERE PROFILEID='$profileid'";
						mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());
					}
				}
				if($verified_username_str)
					$MSG .= "Profile(s) of ".rtrim($verified_username_str,",")." has been verified successfully.";
				if($discarded_username_str)
					$MSG .= "\nProfile(s) of ".rtrim($discarded_username_str,",")." has been re-assigned for progress.";

				$smarty->assign("MSG",nl2br($MSG));

				if(!$verified_username_str && !$discarded_username_str)
					$smarty->assign("MSG","No profile selected to mark as verified.");
			}
			$smarty->assign("VERIFY",1);
		}
		else
		{
			//finding count of profiles on progress.
			$cnt_onprogress = get_matri_count("billing","MATRI_PROFILE","N",$allotted_to);

			//finding count of profiles on hold.
			$cnt_onhold = get_matri_count("billing","MATRI_ONHOLD","H",$allotted_to); 

			//finding count of profiles completed.
			$cnt_completed = get_matri_count("billing","MATRI_COMPLETED","",$allotted_to);

			//finding count of profiles on followup.
			$cnt_followup = get_matri_count("billing","MATRI_PROFILE","F",$allotted_to);

			//finding details of allotted profiles.
			$sql3="SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,STATUS,RATING,RATED_BY FROM billing.MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' and STATUS='N' ORDER BY ENTRY_DT";
			$res3=mysql_query_decide($sql3) or die("$sql3".mysql_error_js());
			$i=0;
			while($row3=mysql_fetch_array($res3))
			{
				$smarty->assign("allot",1);
				$allotted[$i]['SNo']=$i+1;
				$allotted[$i]['PROFILEID']=$row3['PROFILEID'];
				$allotted[$i]['USERNAME']=$row3['USERNAME'];
				$allotted[$i]['ENTRY_DT']=$row3['ENTRY_DT'];
				$allotted[$i]['ALLOT_TIME']=$row3['ALLOT_TIME'];
				$scheduled_time = newtime($row3['ENTRY_DT'],4,0,0);
				$allotted[$i]['SCHEDULED_TIME']=$scheduled_time;
				$allotted[$i]['STATUS']=$row3['STATUS'];
				$allotted[$i]['RATING'] = $RATING[$row3['RATING']];
				if($row_progress['RATED_BY']=="")
					$allotted[$i]['RATING'] = "Not rated yet";
				$i++;
			}

			//finding details of profiles on hold
			$sql = "SELECT mo.PROFILEID, mo.USERNAME, mp.ALLOT_TIME, mo.HOLD_TIME, mo.HOLD_REASON, mp.ENTRY_DT FROM billing.MATRI_ONHOLD mo, billing.MATRI_PROFILE mp WHERE mo.PROFILEID=mp.PROFILEID AND mo.STATUS='H' AND mp.ALLOTTED_TO='$allotted_to' ORDER BY ENTRY_DT";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$m=0;
			while($row=mysql_fetch_array($res))
			{
				$smarty->assign("b",1);
				$onhold[$m]['PROFILEID']=$row['PROFILEID'];
				$onhold[$m]['USERNAME']=$row['USERNAME'];
				$onhold[$m]['ENTRY_DT']=$row['ENTRY_DT'];
				$onhold[$m]['ALLOTTED_TIME']=$row['ALLOT_TIME'];
				$onhold[$m]['HOLD_TIME']=$row['HOLD_TIME'];
				$onhold[$m]['REASON']=$row['HOLD_REASON'];
				$onhold[$m]['SNO']=$m+1;
				$m++;
			}
			$smarty->assign("cnt_onprogress",$cnt_onprogress);
			$smarty->assign("cnt_onhold",$cnt_onhold);
			$smarty->assign("cnt_completed",$cnt_completed);
			$smarty->assign("cnt_followup",$cnt_followup);
			$smarty->assign("allotted",$allotted);
			$smarty->assign("onhold",$onhold);

			//finding details of completed profiles.
			$sql = "SELECT * FROM billing.MATRI_PROFILE WHERE STATUS='Y' and ALLOTTED_TO='$allotted_to' ORDER BY ENTRY_DT";
			$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
			$m=0;
			while($row=mysql_fetch_array($res))
			{
				$completed[$m]['PROFILEID']=$row['PROFILEID'];
				$completed[$m]['USERNAME']=$row['USERNAME'];
				$completed[$m]['ENTRY_DT']=$row['ENTRY_DT'];
				$completed[$m]['COMPLETION_TIME']=$row['COMPLETION_TIME'];
				$completed[$m]['SNO']=$m+1;
				$sql9="SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$res9=mysql_query_decide($sql9) or die("$sql9".mysql_error_js());
				$row9=mysql_fetch_array($res9);
				$completed[$m]['EMAIL']=$row9['EMAIL'];		
				$completed[$m]['PHONE_MOB']=$row9['PHONE_MOB'];		
				$completed[$m]['PHONE_RES']=$row9['PHONE_RES'];		
				$smarty->assign("a",1);
				$m++;
			}

			//finding details of profiles on followup.
			$sql="SELECT * FROM billing.MATRI_PROFILE WHERE STATUS='F' and ALLOTTED_TO='$allotted_to' ORDER BY ENTRY_DT";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$p=0;
			while($row=mysql_fetch_array($res))
			{
				$smarty->assign("follow",1);
				$followup[$p]['PROFILEID']=$row['PROFILEID'];
				$followup[$p]['USERNAME']=$row['USERNAME'];
				$followup[$p]['ENTRY_DT']=$row['ENTRY_DT'];
				$followup[$p]['ALLOT_TIME']=$row['ALLOT_TIME'];
				$followup[$p]['COMPLETION_TIME']=$row['COMPLETION_TIME'];
				$followup[$p]['SNO']=$p+1;
				$sql9="SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$res9=mysql_query_decide($sql9) or die("$sql9".mysql_error_js());
				$row9=mysql_fetch_array($res9);
				$followup[$p]['EMAIL']=$row9['EMAIL'];
				$followup[$p]['PHONE_MOB']=$row9['PHONE_MOB'];
				$followup[$p]['PHONE_RES']=$row9['PHONE_RES'];
				$p++;
			}

			$smarty->assign("allotted_to",$allotted_to);
			$smarty->assign("completed",$completed);
			$smarty->assign("followup",$followup);
		}
		$smarty->assign("extra_params","allotted_to=$allotted_to");
		$smarty->assign("checksum",$checksum);
		$smarty->assign("scriptname","show_exec.php");
		$smarty->assign("SEARCH_BAND",$smarty->fetch("search_matri_profile.htm"));
		$smarty->assign("MATRI_MESSAGE",$smarty->fetch("matri_message.htm"));
		$smarty->display("show_exec.htm");
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
