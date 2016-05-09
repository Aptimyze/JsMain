<?php
include("connect.inc");
if(authenticated($cid))
{	
	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	if($Go || $Update)
	{
		//If users contacts alloted limit updated.
		if($Update)
		{
			if($inc_allot>0)
			{
				$sql="update jsadmin.CONTACTS_ALLOTED set ALLOTED=ALLOTED+$inc_allot where PROFILEID='$profileid'";
				mysql_query_decide($sql) or die(mysql_error_js());
				if(mysql_affected_rows_js()<=0)
				{
					
					$sql="insert ignore into jsadmin.CONTACTS_ALLOTED set PROFILEID=$profileid,ALLOTED=$inc_allot, VIEWED=0,LAST_VIEWED=now(),CREATED=now()";
					mysql_query_decide($sql) or die(mysql_error_js());
				}
				$op_user = getuser($cid);
				$sql="insert into jsadmin.CONTACTS_ALLOTED_HISTORY set CID='$op_user',DATE=now(),PROFILEID=$profileid,HOW_MUCH=$inc_allot";
				mysql_query_decide($sql) or die(mysql_error_js());
			}
			$sql="select PROFILEID,ALLOTED,VIEWED from jsadmin.CONTACTS_ALLOTED where PROFILEID='$profileid'";
                        $res=mysql_query_decide($sql) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                $alloted=$row['ALLOTED'];
                                $viewed=$row['VIEWED'];
                                $found=1;
                        }
			$smarty->assign("FOUND",$found);
			$smarty->assign("NOT_FOUND",$no_user);
			if($free_user==1)
				$smarty->assign("FREE_USER",1);
			$smarty->assign("alloted",$alloted);
			$smarty->assign("viewed",$viewed);
			$smarty->assign("username",$username);
			$smarty->assign("profileid",$profileid);
			$smarty->display("allot_contact.htm");
			die;
			
		}
		//If username is searched for particular record in contacts alloted table.
		$username=get_real_username($username);
		$sql="select PROFILEID,SUBSCRIPTION from newjs.JPROFILE where USERNAME='$username'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$profileid=$row[0];
			$subscription=$row[1];
			
			if(strstr(strtoupper($subscription),"F"))
			{
				$sql="select PROFILEID,ALLOTED,VIEWED from jsadmin.CONTACTS_ALLOTED where PROFILEID=$profileid";
				$res=mysql_query_decide($sql) or die(mysql_error_js());
				if($row=mysql_fetch_array($res) )
				{
				
					$alloted=$row['ALLOTED'];
					$viewed=$row['VIEWED'];
					$found=1;
				}
				else
				{
					$alloted=0;
					$viewed=0;
                                        $found=1;
				}
			}
			else
			{
				$free_user=1;
			}
			
		}
		else
		{
			$no_user=1;
		}
		$smarty->assign("FOUND",$found);
		$smarty->assign("NOT_FOUND",$no_user);
		if($free_user==1)
			$smarty->assign("FREE_USER",1);
		$smarty->assign("alloted",$alloted);
		$smarty->assign("viewed",$viewed);
		$smarty->assign("username",$username);
		$smarty->assign("profileid",$profileid);
		$smarty->display("allot_contact.htm");
		die;	
	}
	$smarty->display("allot_contact.htm");	

}                                                                                                 
function get_real_username($USERNAME)
{
        $sql="select USERNAME from newjs.NAMES where USERNAME='" .mysql_real_escape_string($USERNAME). "'";
        $result=mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($result)==1)
        {
                $myrow=mysql_fetch_array($result);
                $USERNAME=$myrow['USERNAME'];
        }
        return $USERNAME;

}
?>
