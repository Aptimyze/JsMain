<?php
/**
*       Filename        :       assignprofiles_new.php
*       Included        :       time.php
*       Description     :       assigns photo profiles to photo operator for screening
*       Created by      :       Anmol
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
include ("time.php");

global $screen_time;

if(authenticated($cid))	
{
	if($operator)	
	{
		if($assign_num)
		{
			if($assign_num<=$count_to_be_screened)
			{
				if($new)
				{
					$flag="U";
				}
				else 
				{
					//$flag="'E','Y'";
					$flag="Y";
				}		
				//$sql="SELECT USERNAME,PROFILEID,SUBSCRIPTION,PHOTODATE,PHOTOSCREEN from newjs.JPROFILE WHERE PHOTOSCREEN NOT IN ('7','15','23','31') and HAVEPHOTO='$flag'";
				$sql="SELECT USERNAME,PROFILEID,SUBSCRIPTION,PHOTODATE,PHOTOSCREEN from newjs.JPROFILE WHERE PHOTOSCREEN=0 and HAVEPHOTO='$flag'";

				$result= mysql_query_decide($sql) or die(mysql_error_js());

				//echo "<br>Total Rows Selected : ".mysql_num_rows($result)."<br>";
				$assign_count=0;		
				//Make entry in the main admin table				
				while($myrow=mysql_fetch_array($result))
				{	
					if($assign_count<$assign_num)
					{
						$profileid=$myrow["PROFILEID"];		
						$screening_val=$myrow['PHOTOSCREEN'];
						$sql1="select jsadmin.MAIN_ADMIN.SUBMITED_TIME from jsadmin.MAIN_ADMIN where jsadmin.MAIN_ADMIN.PROFILEID='$profileid' and  SCREENING_TYPE = 'P'";
						$result1=mysql_query_decide($sql1) or die(mysql_error_js());
						$myrow1=mysql_fetch_array($result1);
						if(mysql_num_rows($result1)<=0 || $myrow1["SUBMITED_TIME"]!="0000-00-00 00:00:00" )
						//if(mysql_num_rows($result1)<=0 || $myrow1["SUBMITED_TIME"]!="0000-00-00 00:00:00" )
						{
							$RECV_DT=$myrow["PHOTODATE"];
							$SUBMIT_DT=newtime($RECV_DT,0,$screen_time,0);
							$sql2="REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,ALLOTED_TO,SUBSCRIPTION_TYPE, SCREENING_VAL) VALUES('$profileid','".addslashes($myrow['USERNAME'])."','P','$RECV_DT','$SUBMIT_DT',NOW(),'$operator','$myrow[SUBSCRIPTION]', '$screening_val') ";
							$result2= mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
							//echo "Username : $myrow[USERNAME] Affected Rows : ".mysql_affected_rows_js();
							//make an entry in the log
							//$sql2="INSERT INTO jsadmin.LOG(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,ALLOTED_TO,SUBSCRIPTION_TYPE) VALUES('$profileid','$myrow[USERNAME]','P','$RECV_DT','$SUBMIT_DT',NOW(),'$operator','$myrow[SUBSCRIPTION]') ";							
							//$result2= mysql_query_decide($sql2) or die(mysql_error_js());
							
							$assign_count=$assign_count+1;
							//echo "assign_count : $assign_count<br>";
						}
					}	
				}				
				$msg="You have successfully assigned ".$assign_count." records to ".$operator;	
				show_result_message($msg,$new,$adminname,$cid,$smarty);
			}
			else
			{
				$msg="You are trying to assign more profiles than there are to be assigned ";
				show_result_message($msg,$new,$adminname,$cid,$smarty);
			}		
		}
		else
		{
			$msg="You have not entered the no. to be assigned ";
			show_result_message($msg,$new,$adminname,$cid,$smarty);
		}		
	}	
	else 
	{
		$msg="You have not selected any operator ";
		show_result_message($msg,$new,$adminname,$cid,$smarty);
	}	
}
else//user timed out
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm?\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");		
}	

function show_result_message($msg,$new,$adminname,$cid,$smarty)	
{
	if($new)
	{			
		$msg .= "<a href=\"showprofilestoassign_new.php?name=$adminname&user=n&cid=$cid\">";
	}	
	else
	{
		$msg .= "<a href=\"showprofilestoassign_new.php?name=$adminname&user=o&cid=$cid\">";
	}	
	$msg .= "Assign again &gt;&gt;</a>";
	$smarty->assign("name",$adminname);
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");	
}	
?>
