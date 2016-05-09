<?php
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

if(authenticated($cid))	
{
	if($operator)	
	{
		if($assign_num)
		{
			if($assign_num<=$count_to_be_screened)
			{
/*				$sql="SELECT PROFILEID from newjs.NOTHUMBNAIL WHERE DONE='N'";
				$result= mysql_query_decide($sql) or die(mysql_error_js());
				$assign_count=0;		
				//Make entry in the main admin table				
				while($myrow=mysql_fetch_array($result))
				{	
					if($assign_count<$assign_num)
					{
						$profileid=$myrow["PROFILEID"];		
						$sql1="select jsadmin.MAIN_ADMIN.SUBMITED_TIME from jsadmin.MAIN_ADMIN where jsadmin.MAIN_ADMIN.PROFILEID='$profileid'";
						$result1=mysql_query_decide($sql1) or die(mysql_error_js());
						$myrow1=mysql_fetch_array($result1);
						if(mysql_num_rows($result1)<=0 || $myrow1["SUBMITED_TIME"]!="0000-00-00 00:00:00" )
						{
							$sql2="REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,ALLOT_TIME,ALLOTED_TO) VALUES('$profileid',NOW(),'$operator') ";							
							$result2= mysql_query_decide($sql2) or die(mysql_error_js());
							$assign_count=$assign_count+1;
						}
					}	
				}				
*/
				echo "came here";
				$sql = "REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,USERNAME,ALLOT_TIME,ALLOTED_TO) SELECT PROFILEID, CODE, NOW(), '$operator' from newjs.NOTHUMBNAIL WHERE DONE='N' limit 0, $assign_num";
				$result= mysql_query_decide($sql) or die(mysql_error_js());
				
				$sql1 = "UPDATE newjs.NOTHUMBNAIL set DONE = 'U' where DONE = 'N' limit $assign_num";
				mysql_query_decide($sql1) or die(mysql_error_js());

				$msg="You have successfully assigned ".$assign_num." records to ".$operator;	
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
	$msg="Your session has been timed out";
	$msg .="<a href=\"index.htm?\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");		
}	

function show_result_message($msg,$new,$adminname,$cid,$smarty)	
{
	$msg .= "<a href=\"show_thumbnails_to_assign.php?name=$adminname&cid=$cid\">";
	$msg .= "Assign again &gt;&gt;</a>";
	$smarty->assign("name",$adminname);
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");	
}	
?>
