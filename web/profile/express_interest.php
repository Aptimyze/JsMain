<?php

	$paid=0;
	if(strstr($data['SUBSCRIPTION'],"F")  || strstr($data['SUBSCRIPTION'],"D"))
		$paid=1;
	//$paid=0;
	$pid=$data['PROFILEID'];
	$username=$data['USERNAME'];
	if($pid)
	{
		$type="";
		$who="SENDER";
		$other_paid="";
		global $income_of_login_user;	
		
		//Fetching relation
		$sql="select RELATION,INCOME,PHONE_MOB,PHONE_RES,STD,INCOMPLETE,EMAIL,ISD from newjs.JPROFILE where  activatedKey=1 and PROFILEID=$pid";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$income_of_login_user=$row['INCOME'];
		$relation=$row[0];

		$phone_mob=$row["PHONE_MOB"];
		$phone_res=$row["PHONE_RES"];
		$phone_std=$row["STD"];	
		$data['INCOMPLETE']=$row['INCOMPLETE'];
		$myPhone=$phone_mob."#".$phone_std."-".$phone_res;
		$myEmail=$row['EMAIL'];
		$phone_isd=$row['ISD'];

		set_address_details($phone_mob,$phone_res,$phone_std,$phone_isd,$myEmail);

		set_drafts($paid,$type,$who,$other_paid,$relation);
		
		 $CONTACT_MESSAGE1="Expressing interest in this profile will send the following message. You will be notified by Email when member accepts or declines your interest";
		 $CONTACT_MESSAGE2="Expressing interest in these profiles will send the following message. You will be notified by Email when members accept or decline your interest";
		if($paid)
		{
			if($MODE=='M')
			{
				$CONTACT_HEADLINE="Express Interest";
			}
			else
			{
				$CONTACT_HEADLINE="Express Interest";
			}
		
		}
		else
		{
			if($MODE=='M')
			{
				$CONTACT_HEADLINE="Express interest in  selected profiles Free";
			}
			else
			{
				$CONTACT_HEADLINE="Express interest in this profile Free";
			}
		
		}
		$smarty->assign("MY_PHONE",$myPhone);
		$smarty->assign("CONTACT_HEADLINE",$CONTACT_HEADLINE);
		$smarty->assign("CONTACT_MESSAGE1",$CONTACT_MESSAGE1);
		$smarty->assign("CONTACT_MESSAGE2",$CONTACT_MESSAGE2);
		$smarty->assign("PAID",$paid);
		//Say which type of contact it is
		$smarty->assign("MODE",$MODE);
		$smarty->assign("EXPRESS_LAYER",$smarty->fetch("express_interest_layer.htm"));
	}
	else
        {
                $smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']."&nikhil=1");
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
                $smarty->display("login_layer.htm");
        }

