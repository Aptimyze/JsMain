<?php

/*****
	Modified by shiv on Jun 17 , 2005 .
	MOD_DT condition removed from queries as index is made on SCREENING field in JPROFILE
*****/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("time.php");
global $screen_time;                                    
if(authenticated($cid))
{
	if($Submit)
	{
		$count=0;
		foreach( $_POST as $key => $value )
		{
			if( substr($key, 0, 2) == "cb" ) 
			{
				$count++;
				$pid = ltrim($key, "cb");
				$proid[] = $pid;
			}
		
		}	
	
		for($i=0;$i<count($proid);$i++)
		{	
        	        $sql="SELECT USERNAME, MOD_DT, SUBSCRIPTION, SCREENING from newjs.JPROFILE where PROFILEID='$proid[$i]'";
                	$result1=mysql_query_decide($sql) or die(mysql_error_js());
	                $myrow1=mysql_fetch_array($result1);
        	        $receivetime=$myrow1['MOD_DT'];
                	$submittime=newtime($receivetime,0,$screen_time,0);
			$username=$myrow1['USERNAME'];                                                                   
			$subscribe=$myrow1['SUBSCRIPTION'];
			$screening_val=$myrow1['SCREENING'];

			$sql= "REPLACE jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values ('$proid[$i]','".addslashes($username)."','$receivetime','$submittime','".date("Y-m-d H:i")."', '$allotto','O','$subscribe', '$screening_val')";
	                $result=mysql_query_decide($sql) or die(mysql_error_js()); 
		
		}
		$tdate=date("Y-m-d");
		$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));

		$sum=setAllFlags();
//		$sql="SELECT PROFILEID, USERNAME, MOD_DT, SUBSCRIPTION from newjs.JPROFILE where MOD_DT > '$lastweek_date' and ACTIVATED='Y' and SCREENING != $sum";
		//$sql="SELECT PROFILEID, USERNAME, MOD_DT, SUBSCRIPTION from newjs.JPROFILE where ACTIVATED='Y' and SCREENING < '$sum' AND MOD_DT>='$lastweek_date'";
		$sql="SELECT PROFILEID, USERNAME, MOD_DT, SUBSCRIPTION from newjs.JPROFILE USE INDEX(SCREENING) where ACTIVATED='Y' and SCREENING < '$sum'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
	
		$i=0;
		while($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];
			$username=$myrow['USERNAME'];
			$receivetime_est=$myrow['MOD_DT'];
			$submittime_est=newtime($receivetime_est,0,$screen_time,0);
	                if($myrow["SUBSCRIPTION"]=="")
        	        	$color="fieldsnew";
                	else
                        	$color="fieldsnewgreen";
			$receivetime=getIST($receivetime_est);
                        $submittime=getIST($submittime_est);

			$sql= "SELECT ALLOTED_TO from jsadmin.MAIN_ADMIN where PROFILEID= $profileid";
			$result1=mysql_query_decide($sql);
			$myrow1=mysql_fetch_array($result1);		
				
			$values[] = array("sno"=>$i,
					  "profileid"=>$profileid,
                        		  "username"=>$username,
		                          "receive_time"=>$receivetime,
                		          "submit_time"=>$submittime,
					  "bandcolor"=>$color,
					  "ALLOT"=>$myrow1['ALLOTED_TO'],
		        		 );
			//print_r($values);
			$i++;
		}
		$smarty->assign("ROW",$values);

		$sql="SELECT SQL_CACHE USERNAME,PRIVILAGE from jsadmin.PSWRDS where PRIVILAGE like '%NU%' AND ACTIVE='Y'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
//			$privilage=$myrow['PRIVILAGE'];
//			$priv=explode("+",$privilage);
//			if(count($priv)>2)
//			{}
//			else
//			{
				$user[]=$myrow['USERNAME'];
//			}
		}
		$smarty->assign("user",$user);
		$smarty->assign("allot",$allotto);
		if($count==0)
                        $msg = "Please check the records to assign<br><br>";
                else
                        $msg = "You have successfully assigned $count records to $allotto<br><br>";
                                                                                                 
                $msg .= "<a href=\"admin_edit.php?name=$name&cid=$cid\">";
                                                                                                 
                $msg .= "Continue &gt;&gt;</a>";
                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
	
	}
	elseif($Submit1)
	{
	        $pid="";
                $c=0;
                foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "cb" )
                        {
                                $c=$c+1;
                                $pid .="'".ltrim($key, "cb")."',";
                        }
                }
                $pid=substr($pid,0,strlen($pid)-1);
/*                if($c>0)
                {


                        $sql="SELECT ACTIVATED, PROFILEID from newjs.JPROFILE where PROFILEID in
($pid)";
                        $result=mysql_query_decide($sql);
                        while($myrow=mysql_fetch_array($result))
                        {
                                $activated[]=$myrow['ACTIVATED'];
                                $proid[]=$myrow['PROFILEID'];
                        }
                        for($i=0;$i<count($activated);$i++)
                        {
                                $sql="UPDATE newjs.JPROFILE set PREACTIVATED='$activated[$i]', ACTIVATED='D', ACTIVATE_ON='".date("Y-m-d H:i")."' where PROFILEID='$proid[$i]'";
                                mysql_query_decide($sql);
                        }

                }
*/                                                                                 
                                                                                                 
		$smarty->assign("pid",$pid);
                $smarty->assign("c",$c);
                $smarty->assign("user",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("FROM","AE");
                $smarty->display("delete_page.tpl");
                                                                                 
	}
	else
	{
	        $tdate=date("Y-m-d");
        	$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));

		$sum=setAllFlags();
		//$sql="SELECT  newjs.JPROFILE.PROFILEID, newjs.JPROFILE.USERNAME, MOD_DT, SUBSCRIPTION FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON newjs.JPROFILE.PROFILEID = jsadmin.MAIN_ADMIN.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (ACTIVATED = 'Y' AND SCREENING < '$sum' ) AND MOD_DT>='$lastweek_date' ORDER BY MOD_DT"; 
		$sql="SELECT  newjs.JPROFILE.PROFILEID, newjs.JPROFILE.USERNAME, MOD_DT, SUBSCRIPTION FROM newjs.JPROFILE USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON newjs.JPROFILE.PROFILEID = jsadmin.MAIN_ADMIN.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (ACTIVATED = 'Y' AND SCREENING < '$sum' ) ORDER BY MOD_DT"; 
		$result=mysql_query_decide($sql) or die(mysql_error_js());
	
		$i=1;
		while($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];
			$username=$myrow['USERNAME'];
			$receivetime_est=$myrow['MOD_DT'];
			$submittime_est=newtime($receivetime_est,0,$screen_time,0);
			$status_color = get_status_color($submittime_est,$time_diff);
                        $receivetime=getIST($receivetime_est);
                        $submittime=getIST($submittime_est);

			//Get the remaining time left for screening the profile 
                	if($myrow["SUBSCRIPTION"]=="")
                		$color="fieldsnew";
	                else
        	                $color="fieldsnewgreen";

			$sql= "SELECT ALLOTED_TO from jsadmin.MAIN_ADMIN where PROFILEID= $profileid";
			$result1=mysql_query_decide($sql) or die(mysql_error_js());
			$myrow1=mysql_fetch_array($result1);		
			$values[] = array("sno"=>$i,
					  "profileid"=>$profileid,
		                          "username"=>$username,
                		          "receive_time"=>$receivetime,
		                          "submit_time"=>$submittime,
					  "remaining_time" => $time_diff,
					  "status_color" => $status_color,
					  "bandcolor"=>$color,
					  "ALLOT"=>$myrow1['ALLOTED_TO'],
        				 );
			$i++;
		}
		$smarty->assign("ROW",$values);

		$sql="SELECT SQL_CACHE USERNAME,PRIVILAGE from jsadmin.PSWRDS where PRIVILAGE like '%NU%' AND ACTIVE='Y'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
//			$privilage=$myrow['PRIVILAGE'];
//			$priv=explode("+",$privilage);
//			if(count($priv)>2)
//			{}
//			else
//			{
				$user[]=$myrow['USERNAME'];
//			}
		}
		$smarty->assign("user",$user);
		$smarty->assign("allot",$allotto);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("admin_edit.tpl");
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
