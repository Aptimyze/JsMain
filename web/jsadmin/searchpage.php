<?php
include("connect.inc");
if(authenticated($cid))
{
	$minlimit=0;
	$maxlimit=50;
	if($Go)
	{
		$i=0;
                $prev=getprivilage($cid);
		$priv=explode("+",$prev);
                if(in_array('MA',$priv) || in_array('MC',$priv))
                        $smarty->assign("SHOW","Y");
                //if(in_array('A',$priv))
                //        $smarty->assign("ADMIN","Y");

		//mysql_close($db);
		//$db=connect_slave();
		if($phrase=='U')
		{
			$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED ,SOURCE, VERIFY_EMAIL from newjs.JPROFILE where ";

                        if(is_numeric($username))
                                $sql.= "PROFILEID='$username'";
                        else
                                $sql.= "USERNAME='$username'";

			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(!mysql_num_rows($result))
			{
				$sql="SELECT PROFILEID FROM newjs.CUSTOMISED_USERNAME WHERE OLD_USERNAME='$username'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED,SOURCE, VERIFY_EMAIL from newjs.JPROFILE where PROFILEID='$row[PROFILEID]'";
					$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
			}
		}
		else
		{
			$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED ,SOURCE, VERIFY_EMAIL from newjs.JPROFILE where EMAIL='$username'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			// New code added for old email check
			if(mysql_num_rows($result)==0)
			{
				$sql_email ="SELECT PROFILEID FROM newjs.OLDEMAIL where OLD_EMAIL='$username'";
				$result_email=mysql_query_decide($sql_email) or die("$sql_email".mysql_error_js());
				$myrow_email=mysql_fetch_array($result_email);
				$pid =$myrow_email['PROFILEID'];
				if($pid){
					$emailOld =$username;
					$sql ="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, SUBSCRIPTION, INCOMPLETE, ACTIVATED ,SOURCE, VERIFY_EMAIL from newjs.JPROFILE where PROFILEID='$pid'";
					$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}	
			}
			// Ends
		}

			$smarty->assign("SEARCH","YES");
			$count_incomplete=0;

			$i=1;
			if(mysql_num_rows($result)==0)
			{
				$PAGEREF="zero";
				$smarty->assign("PAGEREF",$PAGEREF);
			}
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['INCOMPLETE']=='Y')
					$count_incomplete++;
				if($myrow['SOURCE']=='OFL_PROF' || $myrow['SOURCE']=='ofl_prof')
					$source='O';
				if($myrow['SOURCE']=='101')
					$source='101';
				
				$Username=ereg_replace(" ","&nbsp;",$myrow['USERNAME']);
				if($phrase=='U' && $username!=$Username  && !is_numeric($username))
					$Old_Username=$username;
				$Email=$myrow['EMAIL'];
				$Verify_Email=$myrow['VERIFY_EMAIL'];
				$str =JSstrToTime($myrow["MOD_DT"]);//+19800;
                        	$Mod_dt = strftime("%d %b' %y %H:%M:%S", $str);//strftime("%d %b %Y %H:%M:%S %Z", $str);
				$Profileid=$myrow['PROFILEID'];
				$profilechecksum=md5($Profileid) . "i" . $Profileid;

				if($myrow["ACTIVATED"]!='D')
				{
					$sql_marked= "SELECT PROFILEID FROM jsadmin.MARK_DELETE WHERE PROFILEID='$Profileid' AND STATUS='M'";
					$res_marked= mysql_query_decide($sql_marked) or die("$sql_marked".$mysql_error_js());
					if(mysql_num_rows($res_marked)>0)
						$marked_del=1;
					else
						$marked_del=0;
				}
				if($myrow["SUBSCRIPTION"]=="")
					$color="fieldsnew";
				else
					$color="fieldsnewgreen";
                            $sql_del="select USER,RETRIEVED_BY,TIME from jsadmin.DELETED_PROFILES where PROFILEID=$Profileid order by ID DESC LIMIT 1";
                                $result_del=mysql_query_decide($sql_del) or die("$sql_del".mysql_error_js());
                                if(mysql_num_rows($result_del)!=0)
                                {
    		                         $myrow_del=mysql_fetch_array($result_del);
					 $del_scr="y";
					//if($myrow_del['USER']!='')
	                                //{
        	                                $stat_user="Deleted by ".$myrow_del['USER'];
                	                //}
                        	        if($myrow_del["RETRIEVED_BY"]!='')
                                	{
                                        	$stat_user="Retrieved by ".$myrow_del['RETRIEVED_BY'];
                                		if($myrow['ACTIVATED']=='D')
						{
							$del_scr="N1";
						}
					}
		
				}
				else
				{
					$del_scr="N";	
				}
                $sql_negative = "select PROFILEID from incentive.NEGATIVE_LIST where PROFILEID = $Profileid";
                $result_negaive=mysql_query_decide($sql_negative) or die("$sql_negative".mysql_error_js());
                if(mysql_num_rows($result_negaive)!=0){
                    $negativeListcheck = 1;
                }
				$sqljs="select DEACTIVE_DATE,PROFILEID from newjs.JSARCHIVED where PROFILEID='$myrow[PROFILEID]' and STATUS ='Y'";
				$resjs=mysql_query_decide($sqljs) or die("$sql_del".mysql_error_js());
				if($jsarch=mysql_fetch_array($resjs))
				{
					$smarty->assign("JSARCH_DATE",$jsarch['DEACTIVE_DATE']);
				}
				
	
			$values[] = array(	  "Profileid"=>$Profileid,
						  "Profilechecksum"=>$profilechecksum,		
						  "Username"=>$Username,
						  "Old_Username"=>$Old_Username,
						  "Email" => $Email,
						  "Mod_dt" => $Mod_dt,
						  "bandcolor"=>$color,
						  "incomplete"=>$myrow['INCOMPLETE'],	
						  "activated"=>$myrow['ACTIVATED'],
						  "del_ret_by"=>$stat_user,
						  "del_scr"=>$del_scr,
						  "timeofdel"=>$myrow_del['TIME'],
						  "Verify_Email"=>$Verify_Email,
						  "source"=>$source,
						  "marked_del"=>$marked_del,
                          "negativeListcheck"=>$negativeListcheck
						);
			$i++;
			
		
			}
			$smarty->assign("ROW",$values); 
			/**********
			To show the link for user details only if user has SA privilage 
			**********/
			if(in_array('SA',$priv))
			{
				$smarty->assign("USERDETAILSLINK","1");
			}
		
		$smarty->assign("checkpaid",$checkpaid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("CHECKDELETED",$checkdeleted);
		$smarty->assign("YEAR1",$year1);	
		$smarty->assign("MONTH1",$month1);	
		$smarty->assign("DAY1",$day1);	
		$smarty->assign("YEAR2",$year2);	
		$smarty->assign("MONTH2",$month2);	
		$smarty->assign("DAY2",$day2);	
		$smarty->assign("USER_NAME",$username);
		$smarty->assign("E_MAIL",$email);
		$smarty->assign("TOTAL",$total);
		$smarty->assign("COUNT_INCOMPLETE",$count_incomplete);
		$smarty->assign("NUM_PAGE",$num_page);
		$smarty->assign("PAGE",$PAGE);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		//added by sriram.
		$smarty->assign("profileid",$Profileid);
		
		//$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&PAGE=$PAGE&grp_no=$grp_no";
		//$smarty->assign("link_msg",$link_msg);
		if($source=='O')
		{
			$sql_select="(SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNLOG WHERE PROFILEID='$Profileid' ORDER BY ASSIGN_DATE DESC LIMIT 1) UNION (SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNLOG WHERE PROFILEID='$Profileid' ORDER BY ASSIGN_DATE ASC LIMIT 1)";
			$res_select=mysql_query_decide($sql_select) or die("$sql_select".mysql_error_js());
			if(mysql_num_rows($res_select)==1)
			{
				$operator_assto=mysql_fetch_assoc($res_select);
				$operator_regby=$operator_assto;
			}
			else
			{
				$operator_assto=mysql_fetch_assoc($res_select);
	                        $operator_regby=mysql_fetch_assoc($res_select);
			}
			$smarty->assign("operator_assto",$operator_assto["OPERATOR"]);
			$smarty->assign("operator_regby",$operator_regby["OPERATOR"]);
			unset($res_select);
			unset($operator_assto);
			unset($operator_regby);
		}
		elseif($source=='101')
		{
			$sql_select="(SELECT OPERATOR FROM jsadmin.ASSIGNLOG_101 WHERE PROFILEID='$Profileid' ORDER BY DATE DESC LIMIT 1) UNION (SELECT OPERATOR FROM jsadmin.ASSIGNLOG_101 WHERE PROFILEID='$Profileid' ORDER BY DATE ASC LIMIT 1)";
			$res_select=mysql_query_decide($sql_select) or die("$sql_select".mysql_error_js());
			if(mysql_num_rows($res_select)==1)
                        {
                                $operator_assto=mysql_fetch_assoc($res_select);
                                $operator_regby=$operator_assto;
                        }
                        else
                        {
                                $operator_assto=mysql_fetch_assoc($res_select);
                                $operator_regby=mysql_fetch_assoc($res_select);
                        }
			$smarty->assign("operator_assto",$operator_assto["OPERATOR"]);
			$smarty->assign("operator_regby",$operator_regby["OPERATOR"]);
			unset($res_select);
			unset($operator_assto);
			unset($operator_regby);
		}
		$smarty->display("search_page.tpl");
		//echo $date1."kush".$date2."kush".$username."kush".$email;
	}
	elseif($Delete)
	{
                $c=0;
                foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "cb" )
                        {
                                $c=$c+1;
                                $proid[]=ltrim($key, "cb");
                        }
                }
		if(count($proid)>0)
			$pid=implode($proid,",");
			
		$smarty->assign("profiles",$pid);
		$smarty->assign("c",$c);
		$smarty->assign("submit","Y");
		$smarty->assign("count",1);
		$smarty->assign("cid",$cid);
	        $smarty->assign("name",$user);
		$smarty->assign("Handicapped","All");

		$smarty->display("del_profile_bulk.htm");
	}
	else
	{
		$year=date('Y');
		$month=date('m');
		$day=date('d');
//		$smarty->assign("YEAR1",$year);	
//		$smarty->assign("YEAR2",$year);	
//		$smarty->assign("MONTH1",$month);	
//		$smarty->assign("MONTH2",$month);	
//		$smarty->assign("DAY1",$day);	
//		$smarty->assign("DAY2",$day);	
//		$smarty->assign("YEAR1",$year);	
		$smarty->assign("PAGE",$PAGE);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&PAGE=$PAGE&grp_no=$grp_no";
		$smarty->assign("link_msg",$link_msg);
		$smarty->display("search_page.tpl");
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
