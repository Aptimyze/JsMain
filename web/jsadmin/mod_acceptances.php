<?php
include("connect.inc");
if(authenticated($cid))
{

	if($submit2)
	{
		$enter_acc=1;
		$smarty->assign("enter_acc",$enter_acc);
		
		$name= getuser($cid);
		if (trim($oc_new_acc) == "")
		{
		    $isempty_acc = 1;
		    $smarty->assign("cid",$cid);
		    $smarty->assign("isempty_acc",$isempty_acc);
		    $smarty->display('mod_acceptances.tpl');
		}
		elseif(!is_numeric($oc_new_acc))
		{
			$msg= "Please enter numeric value of new acceptances!!";
			$smarty->assign("msg",$msg);
			$smarty->assign("oc_acc",$oc_acc);
			$smarty->assign("oc_uname",$oc_uname);
			$smarty->assign("cid",$cid);
			$smarty->display('mod_acceptances.tpl');	
		}
		else
		{
			$ts=time();
			$today=date("Y-m-d",$ts);
			$profile_sql= "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME= '$oc_uname'";
			$profile_res= mysql_query_decide($profile_sql) or die(mysql_error_js());
			while($profile_row= mysql_fetch_array($profile_res))
			{
				$profile= $profile_row['PROFILEID'];
			}
			$oc_new=$oc_acc+$oc_new_acc;
			$updt_acc_sql= "UPDATE jsadmin.OFFLINE_BILLING SET ACC_ALLOWED= '$oc_new' WHERE PROFILEID= '$profile'";
			$updt_acc_res= mysql_query_decide($updt_acc_sql) or die(mysql_error_js());
			if($updt_acc_res)
			{
				$log_sql= "INSERT INTO jsadmin.OFFLINE_ACCEPTANCE_LOG(OPERATOR,PROFILEID,ONUMBER,NNUMBER,MOD_DATE) VALUES('$name','$profile','$oc_acc','$oc_new_acc','$today')";
				$log_res= mysql_query_decide($log_sql) or die(mysql_error_js());
			
			}
			else
			{
				$msg.="Unsuccessful updation<br>";
				$msg.="<a href=\"mainpage.php?cid=$cid&name=$name\">Continue</a>";
				$smarty->assign("name",$name);
				$smarty->assign("cid",$cid);
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
				
			}
			
			$msg.="Allowed acceptances for the offlline customer has been successfully updated.<br>";
			$msg.="<a href=\"mainpage.php?cid=$cid&name=$name\">Continue</a>";
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	 }
	 else
	 {
		if (trim($oc_uname) == "")
		{
			$isempty_oc_uname = 1;
			$smarty->assign("isempty_oc_uname",$isempty_oc_uname);
			$smarty->assign("cid",$cid);
			$smarty->display('mod_acceptances.tpl');
		}
		else
		{
			$flag= false;
			$smarty->assign(cid,'$cid');
			$oc_sql= "SELECT USERNAME FROM newjs.JPROFILE WHERE USERNAME= '$oc_uname'";
			$oc_res= mysql_query_decide($oc_sql) or die(mysql_error_js());
			$oc_num= mysql_num_rows($oc_res);

			if($oc_num > 0)
			{
				   $oc_status_sql= "SELECT SOURCE, ACTIVATED, PROFILEID FROM newjs.JPROFILE WHERE USERNAME= '$oc_uname'";
				   $oc_status_res= mysql_query_decide($oc_status_sql) or die(mysql_error_js());
				   while($oc_status_row= mysql_fetch_array($oc_status_res))
				   {
					$oc_status= $oc_status_row['SOURCE'];
					$oc_active= $oc_status_row['ACTIVATED'];
					$oc_profile= $oc_status_row['PROFILEID'];
				   }

				   if($oc_status== 'ofl_prof' || $oc_status== 'OFL_PROF')
				   {
					if($oc_active== 'Y')
					{
						$oc_acc_sql= "SELECT ACC_ALLOWED, ACC_MADE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$oc_profile'";
						$oc_acc_res= mysql_query_decide($oc_acc_sql) or die(mysql_error_js());
						if(mysql_num_rows($oc_acc_res)>0)
						{
							while($oc_acc_row= mysql_fetch_array($oc_acc_res))
							{
								$oc_acc= $oc_acc_row['ACC_ALLOWED'];
								$oc_acc_made= $oc_acc_row['ACC_MADE'];
							}
							$enter_acc=1;
							$smarty->assign("enter_acc",$enter_acc);
							$smarty->assign("oc_acc",$oc_acc);
							$smarty->assign("oc_acc_made",$oc_acc_made);
							$smarty->assign("oc_uname",$oc_uname);
							$smarty->assign("cid",$cid);
							$smarty->display('mod_acceptances.tpl');
						}
						else
						{
							$msg= "The Offline Customer is inactive!!!";
							$flag= true;
						}
					}
					else
					{
						$msg= "The Offline Customer is inactive!!!";
						$flag= true;
					}
				   }
				   else
				   {
					   $msg= "No Offline Customer with this username exists!!!";
					   $flag= true;
				   }
			 }
			 else
			{
				$msg="Invalid Username!!!";
				$flag= true;
			}
			if($flag)
			{
				$smarty->assign("cid",$cid);
				$smarty->assign("msg",$msg);
				$smarty->display('mod_acceptances.tpl');
			 }
		 }
	}
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
