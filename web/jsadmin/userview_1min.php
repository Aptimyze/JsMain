<?php
/*********************************************************************************************
* FILE NAME             : userview_1min.php
* DESCRIPTION           : script for showing the list of 1 min registration profiles alloted to a particular user 
* CREATION DATE         : 5 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                         
include("time.php");                                                                                                  
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
if(authenticated($cid))
{
        $name = getname($cid);
        $tdate=date("Y-m-d");
        $lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));
        $sql="SELECT PROFILEID, ALLOT_TIME, SUBMIT_TIME FROM jsadmin.MAIN_ADMIN_1MIN WHERE ALLOTED_TO = '$name' ORDER BY ALLOT_TIME DESC";
        $result=mysql_query_decide($sql) or die(mysql_error_js());
        $i=1;
        if(mysql_num_rows($result)>0)
        {
                while($myrow=mysql_fetch_array($result))
                {
			$profileid=$myrow['PROFILEID'];
                        $receivetime_est=$myrow['ALLOT_TIME'];
                        $receivetime=getIST($receivetime_est);
                        $submittime_est=$myrow['SUBMIT_TIME'];
                        $submittime=getIST($submittime_est);
			//echo $time_diff;
			$color="fieldsnewgreen";
			$status_color = get_status_color($submittime_est,$time_diff);
                        $values[] = array("sno"=>$i,
                                          "profileid"=>$profileid,
                                          "receive_time"=>$receivetime,
                                          "submit_time"=>$submittime,
                                          "remaining_time" => $time_diff,
                         		"status_color" => $status_color,
					  "bandcolor"=>$color,
                                 	);
                $i++;
                }
                $smarty->assign("ROW",$values);
        }
	/*$smarty->assign("for","U");
                                                                                                 
        $sum=setAllFlags();
        $sql="SELECT jsadmin.MAIN_ADMIN_1MIN.PROFILEID, ALLOT_TIME, SUBMIT_TIME FROM jsadmin.MAIN_ADMIN_1MIN, newjs.JPROFILE_AFFILIATE WHERE jsadmin.MAIN_ADMIN_1MIN.PROFILEID = newjs.JPROFILE.PROFILEID AND ALLOTED_TO = '$name' AND SUBMITED_TIME =0 AND SCREENING<131071";
        $result1=mysql_query_decide($sql);
        $i=1;
        if(mysql_num_rows($result1)>0)
        {
                while($myrow1=mysql_fetch_array($result1))
                {
$profileid=$myrow1['PROFILEID'];
                        $username=$myrow1['USERNAME'];
                        $receivetime_est=$myrow1['ALLOT_TIME'];
                        $receivetime=getIST($receivetime_est);
                        $submittime_est=$myrow1['SUBMIT_TIME'];
                        $status_color = get_status_color($submittime_est,$time_diff);
                        $submittime=getIST($submittime_est);
                                                                                                 
                        if($myrow1["SUBSCRIPTION_TYPE"]=="")
                                $color="fieldsnew";
                        else
                                $color="fieldsnewgreen";
                        $values1[] = array("sno"=>$i,
                                          "profileid"=>$profileid,
                                          "username"=>$username,
                                          "receive_time"=>$receivetime,
                                          "submit_time"=>$submittime,
                                  "remaining_time" => $time_diff,
                                  "status_color" => $status_color,
                                  "bandcolor"=>$color,
                                 );
                $i++;
                }
                $smarty->assign("ROW1",$values1);
        }
        $smarty->assign("for","Y");*/
	$smarty->assign("cid",$cid);
        $smarty->assign("user",$name);
        $smarty->display("user_view_1min.htm");
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
                                                                                                 
?>

