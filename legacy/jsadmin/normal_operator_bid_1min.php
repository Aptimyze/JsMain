<?php
/*********************************************************************************************
* FILE NAME             : normal_operator_id_1min.php
* DESCRIPTION           : script for allocating profiles of 1 min registration to a particular user
* CREATION DATE         : 5 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
include("time.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
global $screen_time;
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='normal_operator_bid_1min is being hit. We can wrap this to JProfileUpdateLib';
               $subject="normal_operator_bid_1min";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
if(authenticated($cid))
{
	if($CMDBid)
	{
		$operator_name=getname($cid);
		$tdate=date("Y-m-d");
		$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));
		$sum=SetAllFlags();

		if(trim($num)=="" || !is_numeric($num))
		{
			$msg="Please check the records to assign";
		}
		else
		{
			$pid="";
			$sql="SELECT ID FROM newjs.JPROFILE_AFFILIATE WHERE BACKEND='Y' AND MOVED='N' ORDER BY MOD_DT ASC LIMIT 0,$num";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$profileid = array();
			if($row=mysql_fetch_array($result))
			{
				$i=0;
				do
				{
					$profileid[$i]=$row['ID'];
					$i++;
				}while($row=mysql_fetch_array($result));
			}
			$actual_num=$i;
			$pid="'".implode("','",$profileid)."'";
			$sql_u="UPDATE newjs.JPROFILE_AFFILIATE SET BACKEND='B' WHERE ID in ($pid)";
			$res_u=mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());
			for($i=0;$i<count($profileid);$i++)
                        {
                                $sql="SELECT ENTRY_DT,MOD_DT from newjs.JPROFILE_AFFILIATE where ID='$profileid[$i]'";
                                $result1=mysql_query_decide($sql) or die(mysql_error_js());
                                $myrow1=mysql_fetch_array($result1);
                                $receivetime=$myrow1['MOD_DT'];
                                $submittime=newtime($receivetime,0,$screen_time,0);
                                                                                                 
                                $sql_i="REPLACE jsadmin.MAIN_ADMIN_1MIN (PROFILEID, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO) values('$profileid[$i]','$receivetime','$submittime','".date("Y-m-d H:i")."', '$users')";
                                $result=mysql_query_decide($sql_i) or die("$sql_i".mysql_error_js());
                        }

			$msg=" $operator_name, you have been successfully assigned $actual_num $val records";
		}

		$msg .= "<a href=\"userview_1min.php?name=$operator_name&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";

		$smarty->assign("MSG",$msg);
		
		$smarty->display("jsadmin_msg.tpl");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
