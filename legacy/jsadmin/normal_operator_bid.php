<?php

include("connect.inc");
include("time.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
global $screen_time;
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='normal_operator_bid is being hit. We can wrap this to JProfileUpdateLib';
               $subject="normal_operator_bid";
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
			if($val=="new")
			{
				$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED='N' AND INCOMPLETE = 'N' ORDER BY MOD_DT ASC LIMIT 0,$num";
			}
			elseif($val=="edit")
			{
				$sql="SELECT newjs.JPROFILE.PROFILEID as PROFILEID FROM newjs.JPROFILE USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON newjs.JPROFILE.PROFILEID = jsadmin.MAIN_ADMIN.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ACTIVATED = 'Y' AND SCREENING < '4094303'  ORDER BY MOD_DT ASC LIMIT 0,$num";
			}
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$profileid = array();
			if($row=mysql_fetch_array($result))
			{
				$i=0;
				do
				{
					$profileid[$i]=$row['PROFILEID'];
					$i++;
				}while($row=mysql_fetch_array($result));
			}
			$actual_num=$i;
			$pid="'".implode("','",$profileid)."'";

			for($i=0;$i<count($profileid);$i++)
			{
        	                $sql="SELECT USERNAME, ENTRY_DT,MOD_DT, SUBSCRIPTION, SCREENING from newjs.JPROFILE where PROFILEID='$profileid[$i]'"; 
	                        $result1=mysql_query_decide($sql) or die(mysql_error_js());
        	                $myrow1=mysql_fetch_array($result1);
                	        $receivetime=$myrow1['MOD_DT'];
                        	$submittime=newtime($receivetime,0,$screen_time,0);
	                        $username=$myrow1['USERNAME'];
        	                $subscribe=$myrow1['SUBSCRIPTION'];
				$screening_val=$myrow1['SCREENING'];

                	        $sql_i="REPLACE jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$profileid[$i]','".addslashes($username)."','$receivetime','$submittime','".date("Y-m-d H:i")."', '$operator_name','O', '$subscribe','$screening_val')"; 
                        	$result=mysql_query_decide($sql_i) or die("$sql_i".mysql_error_js());
	                }

			$sql_u="UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE ACTIVATED='N' AND PROFILEID in ($pid)";
			$res_u=mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());

			$msg=" $operator_name, you have been successfully assigned $actual_num $val records";
		}

		$msg .= "<a href=\"userview.php?name=$operator_name&cid=$cid\">";
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
