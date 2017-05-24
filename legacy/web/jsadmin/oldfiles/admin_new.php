<?php

include("time.php");                                                                                                 
include("connect.inc");
mail("kunal.test02@gmail.com","jsadmin/oldfile/admin_new.php in USE",print_r($_SERVER,true));
global $screen_time;
if(authenticated($cid))
{

	if($Submit)
	{
		$pid="";
		foreach( $_POST as $key => $value )
		{
			if( substr($key, 0, 2) == "cb" )
			{
				$pid .="'".ltrim($key, "cb")."',";
			}
		}
		$pid=substr($pid,0,strlen($pid)-1);
		
		$sql="UPDATE newjs.JPROFILE SET ACTIVATED='U' where PROFILEID in ($pid)";
		$result=mysql_query_decide($sql,$db);
		$count=0;
		foreach( $_POST as $key => $value )
		{
			if( substr($key, 0, 2) == "cb" ) 
			{
				$count=$count+1;
				$pid = ltrim($key, "cb");
				$proid[] = $pid;
			}
		}	
	
		for($i=0;$i<count($proid);$i++)
		{	
			$sql="SELECT USERNAME, MOD_DT, SUBSCRIPTION, SCREENING from newjs.JPROFILE where PROFILEID='$proid[$i]'";	
			$result1=mysql_query_decide($sql);
			$myrow1=mysql_fetch_array($result1);
			$receivetime=$myrow1['MOD_DT'];  echo "\n".$screen_time."\n";
			$submittime=newtime($receivetime,0,$screen_time,0);
			$username=$myrow1['USERNAME'];
			$subscribe=$myrow1['SUBSCRIPTION'];
			$screening_val=$myrow1['SCREENING'];
	
			$sql="INSERT into MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$proid[$i]','".addslashes($username)."','$receivetime','$submittime','".date("Y-m-d H:i")."', '$allotto','O', '$subscribe', '$screening_val')"; 
			$result=mysql_query_decide($sql,$db); 
		}
	        $tdate=date("Y-m-d");
        	$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));

		$sql="SELECT PROFILEID, USERNAME, ENTRY_DT,MOD_DT ,SUBSCRIPTION from newjs.JPROFILE where ACTIVATED='N' and MOD_DT > '$lastweek_date'"; 
		$result=mysql_query_decide($sql);
	
		$i=1;
		while($myrow=mysql_fetch_array($result))
		{
//			$receivetime=$myrow['ENTRY_DT'];
			$receivetime_est=$myrow['MOD_DT'];
			$submittime_est=newtime($receivetime_est,0,$screen_time,0);
			if($myrow["SUBSCRIPTION"]=="")
				$color="fieldsnew";
			else
				$color="fieldsnewgreen";
			$receivetime=getIST($receivetime_est);
			$submittime=getIST($submittime_est);
			$values[] = array("sno"=>$i,
					  "profileid"=>$myrow["PROFILEID"],
                		          "username"=>$myrow["USERNAME"],
		                          "profile_type"=>$myrow["PROFILE_TYPE"],
                		          "receive_time"=>$receivetime,
		                          "submit_time"=>$submittime,
					  "bandcolor"=>$color,	
        				 );
			$i++;
		}
		$smarty->assign("ROW",$values);
	
		$sql="SELECT SQL_CACHE USERNAME,PRIVILAGE from jsadmin.PSWRDS where PRIVILAGE like '%NU%'";
		$result=mysql_query_decide($sql) ;
		while($myrow=mysql_fetch_array($result))
		{
//			$privilage=getprivilage($myrow['PRIVILAGE']);
//                        $priv=explode("+",$privilage);
//                        if(count($priv)>2)
  //                      {}
    //                    else
      //                  {	
				$user[]=$myrow['USERNAME'];
	//		}
		}
		$smarty->assign("user",$user);
		$smarty->assign("allot",$allotto);
		
		if($count==0)
			$msg = "Please check the records to assign<br><br>";
		else	
			$msg = "You have successfully assigned $count records to $allotto<br><br>";
                                                                                                 
                $msg .= "<a href=\"admin_new.php?name=$name&cid=$cid\">";
                
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
                $smarty->assign("user",$name);
		$smarty->assign("pid",$pid);
		$smarty->assign("c",$c);
                $smarty->assign("cid",$cid);
                $smarty->assign("FROM","AN");
                $smarty->display("delete_page.tpl");
	}
	else
	{
	        $tdate=date("Y-m-d");
	        $lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days ")); 
		$sql="SELECT PROFILEID, USERNAME, ENTRY_DT,MOD_DT, SUBSCRIPTION from newjs.JPROFILE where MOD_DT > '$lastweek_date' and ACTIVATED='N' AND INCOMPLETE<>'Y'"; 
		$result=mysql_query_decide($sql); 
	
		$i=1;
		while($myrow=mysql_fetch_array($result))
		{
//			$receivetime=$myrow['ENTRY_DT'];
			$receivetime_est=$myrow['MOD_DT'];
			$submittime_est=newtime($receivetime_est,0,$screen_time,0);
			//Get the remaining time left for screening the profile 
			$status_color = get_status_color($submittime_est,$time_diff);
			$receivetime=getIST($receivetime_est);
			$submittime=getIST($submittime_est);
			if($myrow["SUBSCRIPTION"]=="")
				$color="fieldsnew";
			else
				$color="fieldsnewgreen";
			$values[] = array("sno"=>$i,
					  "profileid"=>$myrow["PROFILEID"],
		                          "username"=>$myrow["USERNAME"],
                        		  "profile_type"=>$myrow["PROFILE_TYPE"],
		                          "receive_time"=>$receivetime,
                		          "submit_time"=>$submittime,
					  "remaining_time" => $time_diff,
					  "status_color" => $status_color,
					  "bandcolor"=>$color,
        				 );
			$i++;
		}
		$smarty->assign("ROW",$values);

		$sql="SELECT SQL_CACHE USERNAME,PRIVILAGE from jsadmin.PSWRDS where PRIVILAGE like '%NU%'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());

		while($myrow=mysql_fetch_array($result))
		{
//			$privilage=$myrow['PRIVILAGE'];
  //                      $priv=explode("+",$privilage);
//                        if(count($priv)>2)
  //                      {}
    //                    else
      //                  {	
				$user[]=$myrow['USERNAME'];
	//		}
		}
		$smarty->assign("user",$user);
		$smarty->assign("allot",$allotto);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("admin_new.tpl");
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
