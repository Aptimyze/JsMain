<?php
/**
*       Filename        :       add_offline_horoscope.php
*       Included        :       connect.inc
*       Description     :       logs in the operator to add offline horoscope for a user
*       Created by      :       Gaurav Arora
**/
/**
*       Included        :       connect.inc
**/
include ("connect.inc");
//include(JsConstants::$docRoot."/commonFiles/flag.php");
include ("time.php");

if(authenticated($cid))
{	
	if($login)
	{
		$operator_name=getname($cid);

		$sql="select PROFILEID,DTOFBIRTH from newjs.JPROFILE where USERNAME='$username'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$myrow=mysql_fetch_array($result);
			$profileid=$myrow['PROFILEID'];
			//$RECV_DT=date("Y-m-d");
			//$SUBMIT_DT=newtime($RECV_DT,0,12,0);
			//$subs=$myrow['SUBSCRIPTION'];
			$dtofbirth=$myrow['DTOFBIRTH'];

			$sql_horoscope_screen="select HOROSCOPE_SCREENING,TYPE from newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
			$result_horoscope_screen=mysql_query_decide($sql_horoscope_screen) or die(mysql_error_js());
			$myrow_horoscope_screen=mysql_fetch_array($result_horoscope_screen);
			if(mysql_num_rows($result_horoscope_screen)>0)
			{
				if($myrow_horoscope_screen['TYPE']=='U' && $myrow_horoscope_screen['HOROSCOPE_SCREENING']==0)
				{
					$message="This user's horoscope is already under screening.<br>";
					$message.="<a href=\"add_offline_horoscope.php?cid=$cid&username=$operator_name\">Add more offline horoscopes</a>";
					$smarty->assign("MSG",$message);
					$smarty->display("jsadmin_msg.tpl");
				}
				else
				{
					$sql_update="update newjs.ASTRO_DETAILS set TYPE='U',HOROSCOPE_SCREENING='0' where PROFILEID='$profileid';";
					mysql_query_decide($sql_update) or die(mysql_error_js());
					//$sql_assign="REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,ALLOTED_TO,SUBSCRIPTION_TYPE) VALUES('$profileid','$username','H','$RECV_DT','$SUBMIT_DT',NOW(),'$operator_name','$subs')";
					$sql_assign="REPLACE INTO newjs.HOROSCOPE_FOR_SCREEN (PROFILEID) VALUES('$profileid')";
					mysql_query_decide($sql_assign) or die(mysql_error_js());

					$message="This user's profile has been marked for horoscope screening successfully.<br>";
					$message.="<a href=\"add_offline_horoscope.php?cid=$cid&username=$operator_name\">Add more offline horoscopes</a>";
					$smarty->assign("MSG",$message);
					$smarty->display("jsadmin_msg.tpl");
				
				}
			}
			else
			{
				$sql_insert="REPLACE INTO newjs.ASTRO_DETAILS (PROFILEID,TYPE,HOROSCOPE_SCREENING,DTOFBIRTH) values ('$profileid','U','0','$dtofbirth')";
				mysql_query_decide($sql_insert) or die(mysql_error_js());
				//$sql_assign="REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,ALLOTED_TO,SUBSCRIPTION_TYPE) VALUES('$profileid','$username','H','$RECV_DT','$SUBMIT_DT',NOW(),'$operator_name','$subs')";
				$sql_assign="REPLACE INTO newjs.HOROSCOPE_FOR_SCREEN (PROFILEID) VALUES('$profileid')";
				mysql_query_decide($sql_assign) or die(mysql_error_js());

				$message="This user's profile has been marked for horoscope screening successfully.<br>";
				$message.="<a href=\"add_offline_horoscope.php?cid=$cid&username=$operator_name\">Add more offline horoscopes</a>";
				$smarty->assign("MSG",$message);
				$smarty->display("jsadmin_msg.tpl");

			}

		}
		else 
		{
			$smarty->assign("OPERATOR_NAME",$operator_name);
			$smarty->assign("RELOGIN",1);
			$smarty->assign("CID",$cid);
			$smarty->display("add_offline_horoscope.htm");
		}		
	}
	else 
	{		
		$smarty->assign("OPERATOR_NAME",$username);
		$smarty->assign("CID",$cid);
		$smarty->display("add_offline_horoscope.htm");
	}
}
else //user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}	

?>
