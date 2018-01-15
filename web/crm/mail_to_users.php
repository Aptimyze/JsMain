<?php

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include(JsConstants::$docRoot."/classes/authentication.class.php");

if(authenticated($cid))
{
	$operator_name=getname($cid);
	if($send)
	{
		//if($username)
		if($profileid)
		{
			//$sql="SELECT EMAIL FROM newjs.JPROFILE WHERE USERNAME='".addslashes(stripslashes($username))."'";
			$sql="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$success=1;
				$to=$row['EMAIL'];
			}
			else
			{
				$success=0;
			}

			$sql="SELECT EMAIL,USERNAME FROM jsadmin.PSWRDS WHERE USERNAME='".addslashes($operator_name)."'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$success=1;
				$from=$row['EMAIL'];
				$from_name=$row['USERNAME'];
			}
			else
			{
				$success=0;
			}
		}
		else
		{
                        $success=0;
		}

		if(!trim($to))
			$success=0;

		if($success)
                {
			$checksum = md5($profileid)."i".$profileid;
			$protect=new protect;
			$echecksum = $protect->js_encrypt($checksum);
			$message = str_replace("<CHECKSUM>",$checksum,$message);
			$message = str_replace("<ECHECKSUM>",$echecksum,$message);
                        $message=nl2br(stripslashes($message));
                        send_email($to,$message,$subject,$from,'','','','','','','1','',$from_name);
                        $msg="Mail sent succesfully<br>";
                }
                else
                {
                        $msg="Mail could not be sent. Try again<br>";
                }

                $msg .="Kindly close this window </a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("crm_msg.tpl");
	}
		
	//if form has been submitted make validation checks
	else if($CMDSend)
	{
		$message="";	
		$message="Dear ". $username.",\n\n";
		$error=0;
		//Check if subject is empty
		if(trim($heademail)=='')
		{
			$smarty->assign("NOSUBJECT","Y");
			$error=1;
		}

		//$bodyemail is the value returned from template for body
		if(!is_array($bodyemail))
		{
			$smarty->assign("NOBODY","Y");
			$error=1;
		}
		else
		{
		//$bodyemail returned more than 3 values
		if(count($bodyemail)>3)
		{
			$smarty->assign("MORETHAN3BODY","Y");
                        $error=1;
		}
		//Festive Offer
		if(in_array('15',$bodyemail))
		{
			//Festive offer end date is mandatory
			if(!$FO_till)
			{
				$smarty->assign("NOFOFFERTILL","Y");
                                $error=1;
			}
		}
		//Other Offer
		if(in_array('16',$bodyemail))
                {
			//Other offer upto% is mandatory
                        if(!$Upto)
                        {
                                $smarty->assign("NOOOFFERUPTO","Y");
                                $error=1;
                        }
                        elseif(!is_numeric($Upto))
                        {
                                $smarty->assign("NOOOFFERUPTO","Y");
                                $error=1;
                        }
                        elseif($Upto>100)
                        {
                                $smarty->assign("NOOOFFERUPTO","Y");
                                $error=1;
                        }
			//Other offer end date is mandatory
                        if(!$OO_till)
                        {
                                $smarty->assign("NOOOFFERTILL","Y");
                                $error=1;
                        }
                }
		//Jeevansathi Branch Details
		if(in_array('19',$bodyemail))
                {
                        //Branch is mandatory
                        if(!$SUBLOCATION)
                        {
                                $smarty->assign("NOSUBLOCATION","Y");
                                $error=1;
                        }
                }
		}
		//If there is any error rethrow the form
		if($error)
		{
			$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='".addslashes($operator_name)."'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$from=$row['EMAIL'];
			}
			$sql="SELECT * FROM incentive.EMAIL_DATA WHERE DISPLAY='Y' ORDER BY ID ASC";
	                $res=mysql_query_decide($sql)or die("$sql".mysql_error_js());
			$h=0;   //variable for header	
                	$b=0;  //variable for body
                	$f=0;  //variable for footer
			
			while($row=mysql_fetch_array($res))
			{
				if($row['FLAG']=='H')
				{
					$header[$h]['ID']=$row['ID'];
					$header[$h]['TITLE']=$row['TITLE'];
					$header[$h]['MESSAGE']=$row['MESSAGE'];
					$header[$h]['FLAG']=$row['FLAG'];
					$h++;
				}
				if($row['FLAG']=='B')
				{
					$body[$b]['ID']=$row['ID'];
					$body[$b]['TITLE']=$row['TITLE'];
					$body[$b]['MESSAGE']=$row['MESSAGE'];
					$body[$b]['FLAG']=$row['FLAG'];
					$b++;
				}
				if($row['FLAG']=='F')
				{
					$footer[$f]['ID']=$row['ID'];
					$footer[$f]['TITLE']=$row['TITLE'];
					$footer[$f]['MESSAGE']=$row['MESSAGE'];
					$footer[$f]['FLAG']=$row['FLAG'];
					$f++;
				}
			}

			//$select array contains "Y" for those body option that were selected			
			for($i=0;$i<count($body);$i++)
			{
				if(is_array($bodyemail) && in_array($body[$i]['ID'],$bodyemail))
					$select[$i]="Y";
			}
			
			$smarty->assign("heademail",$heademail);	
			$smarty->assign("select",$select);
			$smarty->assign("subject",$subject);
			$smarty->assign("header",$header);
			$smarty->assign("body",$body);
	                $smarty->assign("footer",$footer);
			$smarty->assign("from_email",$from);
			$smarty->assign("name",$operator_name);
			$smarty->assign("cid",$cid);
			$smarty->assign("profileid",$profileid);
			$smarty->assign("username",stripslashes($username));
			$smarty->assign("error",$error);
			$smarty->assign("preview",$CMDSend); 
			$sublocation = list_of_branches();
	                $smarty->assign("sublocation",$sublocation);
			$days_arr = days_dropdown();
	                $smarty->assign("days_arr",$days_arr);
			$smarty->display("mail_to_users.htm");
		}
		else
		{
			$str=$heademail;
			$str.= ",".implode(',',$bodyemail);
                        $query="SELECT * FROM incentive.EMAIL_DATA WHERE ID IN". "(" . $str.") AND DISPLAY='Y' ORDER BY FLAG DESC,ID ASC";
                        $res=mysql_query_decide($query)or die(mysql_error_js());
                        while($row=mysql_fetch_array($res))
                       	{
				if($row['FLAG']=='H')
                                        $subject = $row['TITLE'];
				$message .= $row['MESSAGE'];
				if($row['ID']=='12' || $row['ID']=='13')
				{
					$sql_se="SELECT EXPIRY_DT,SERVICEID FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid' AND SERVEFOR LIKE '%F%' AND ACTIVE='Y' AND ACTIVATED_ON!='0000-00-00' ORDER BY EXPIRY_DT DESC LIMIT 1";
                                        $res_se=mysql_query_decide($sql_se) or die("$sql_se".mysql_error_js());
                                        if($row_se=mysql_fetch_array($res_se))
					{
						$message = str_replace("<Date of Expiry>",date("d-M-Y",strtotime($row_se['EXPIRY_DT'])),$message);
						$message = str_replace("<Subscription expiry/discount expiry>",date("d-M-Y",strtotime($row_se['EXPIRY_DT'])+10*86400),$message);
					}
				}
				elseif($row['ID']=='15')
                                	$message .= date("d-M-Y",strtotime($FO_till));
				elseif($row['ID']=='16')
				{
                                	$message = str_replace("<Max % Value>",$Upto."%",$message);
					$message .= date("d-M-Y",strtotime($OO_till));
				}
                                elseif($row['ID']=='19')
                                {
                                        $sql_sl="SELECT ADDRESS FROM newjs.CONTACT_US WHERE VALUE='$SUBLOCATION'";
                                        $res_sl=mysql_query_decide($sql_sl) or die("$sql_sl".mysql_error_js());
                                        if($row_sl=mysql_fetch_array($res_sl))
                                        {
                                                $message .= $row_sl['ADDRESS'].".";
                                        }
                                }
				$message .= "\n\n";
                        }

                	//Adding footer to the message
	                $sql="SELECT * FROM incentive.EMAIL_DATA WHERE FLAG='F' AND DISPLAY='Y'";
                        $res=mysql_query_decide($sql)or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$message .= $row['MESSAGE']."\n\n";
			}

			$sql="SELECT EMAIL,PHONE, SIGNATURE FROM jsadmin.PSWRDS WHERE USERNAME='".addslashes($operator_name)."'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$from=$row['EMAIL'];

			$message .=$row['SIGNATURE'] . "\n\nPhone No.: ".$row['PHONE'];
       				                 
			$smarty->assign("name",$operator_name);
			$smarty->assign("from",$from);
			$smarty->assign("subject",stripslashes($subject));
			$smarty->assign("message",stripslashes($message));
			$smarty->assign("error",$error);
			$smarty->assign("preview",$CMDSend);
			$smarty->assign("cid",$cid);
			$smarty->assign("profileid",$profileid);
                        $smarty->assign("username",stripslashes($username));
			$sublocation = list_of_branches();
	                $smarty->assign("sublocation",$sublocation);
			$days_arr = days_dropdown();
	                $smarty->assign("days_arr",$days_arr);
			$smarty->display("mail_to_users.htm");
		}
	}
	else
	{
		$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='".addslashes($operator_name)."'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$from=$row['EMAIL'];
		}
		$sql="SELECT * FROM incentive.EMAIL_DATA WHERE DISPLAY='Y' ORDER BY ID ASC";
		$res=mysql_query_decide($sql)or die("$sql".mysql_error_js());
		$h=0;  //variable for header
		$b=0;  //variable for body
		$f=0;  //variable for footer
		while($row=mysql_fetch_array($res))
		{
			if($row['FLAG']=='H')
			{
				$header[$h]['ID']=$row['ID'];
				$header[$h]['TITLE']=$row['TITLE'];
				$header[$h]['MESSAGE']=$row['MESSAGE'];
				$header[$h]['FLAG']=$row['FLAG'];
				$h++;
			}
			if($row['FLAG']=='B')
			{
				$body[$b]['ID']=$row['ID'];
				$body[$b]['TITLE']=$row['TITLE'];
				$body[$b]['MESSAGE']=$row['MESSAGE'];
				$body[$b]['FLAG']=$row['FLAG'];
				$b++;
			}
			if($row['FLAG']=='F')
			{
				$footer[$f]['ID']=$row['ID'];
				$footer[$f]['TITLE']=$row['TITLE'];
				$footer[$f]['MESSAGE']=$row['MESSAGE'];
				$footer[$f]['FLAG']=$row['FLAG'];
				$f++;
			}
		}

		$smarty->assign("from_email",$from);
		$smarty->assign("username",stripslashes($username));
		$smarty->assign("profileid",$profileid);
		$smarty->assign("name",$operator_name);
		$smarty->assign("cid",$cid);
		$smarty->assign("header",$header);
		$smarty->assign("body",$body);
		$smarty->assign("footer",$footer);	
		$sublocation = list_of_branches();
		$smarty->assign("sublocation",$sublocation);
		$days_arr = days_dropdown();
		$smarty->assign("days_arr",$days_arr);
		$smarty->display("mail_to_users.htm");
	}
}
else
{
	//In case session has been timed out login page is diplayed
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("crm_msg.tpl");
}

function list_of_branches()
{
	$sql = "SELECT SQL_CACHE VALUE, NAME FROM newjs.CONTACT_US WHERE 1 ORDER BY NAME";
        $res = mysql_query_decide($sql);
        $ret = "";
	while($myrow = mysql_fetch_array($res))
	{
		$ret .= "<option value=\"$myrow[VALUE]\">$myrow[NAME]</option>\n";
	}
	return $ret;
}

function days_dropdown()
{
	$today = date("d-M-Y");
        $ret = "";
	$ret .= "<option value=\"$today\">$today</option>\n";
	for($i=1;$i<15;$i++)
        {
		$next_15_days = date("d-M-Y",strtotime($today)+86400*$i);
        	$ret .= "<option value=\"$next_15_days\">$next_15_days</option>\n";
        }
        return $ret;
}

?>
