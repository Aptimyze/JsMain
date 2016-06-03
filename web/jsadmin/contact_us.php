<?php

/************************************************************************************************************************
*    FILENAME           : contact_us.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : Edit table BRANCHES/CONTACT_US 
*    CREATED BY         : lavesh rawat
*    CHANGED ON         : 1st March 2007
*    MODIFIED NEXT	: By Neha Verma on 14feb 2008 as new fields are added in contact_us table  
***********************************************************************************************************************/


include("connect.inc");

if(authenticated($cid))
{
	$name=getname($cid);
	$smarty->assign("cid",$cid);
	if($submit=="MODIFY")
	{	$smarty->assign("sel_table",$sel_table);
		if(trim($branch)=='')
		{
			$is_error++;
			$smarty->assign("b_err",1);
		}
		else
		{
			$branch=ltrim(stripslashes($branch),"'");
			$branch_arr=explode("#",$branch);
			$branch=$branch_arr[0];
		}
		if($sel_table=='CONTACT_US')
			$sql="SELECT TYPE FROM newjs.CONTACT_US WHERE VALUE='$branch'";
		else
			$sql="SELECT ID FROM newjs.$sel_table WHERE VALUE='$branch'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$myrow=mysql_fetch_array($result);

                if($sel_table=='CONTACT_US')
		{
			$type=$myrow['TYPE'];
			if(trim($MOBILE==''))
			{
				$is_error++;
				$smarty->assign("mob_err",1);
			}
			if(trim($STATE==''))
			{
				$is_error++;
				$smarty->assign("stat_err",1);
			}
		}
		else
                {
                        if($myrow['ID']==9 || $myrow['ID']==18 || $myrow['ID']==8 || $myrow['ID']==5 || $myrow['ID']==2 || $myrow['ID']==1 || $myrow['ID']==6)
                                $type='S';
                }

		if($type)
		{
			if(trim($CONTACT_PERSON)=="")
			{
				$is_error++;
				$smarty->assign("c_p_err",1);
			}
		}
		//changed by shiv on 13th oct 2007. address check removed.
		if(0)//trim($ADDRESS)=='')
		{
			$is_error++;
			$smarty->assign("add_err",1);
		}
        if($sel_table!='CONTACT_US')
        {
            if(trim($PHONE==''))
    		{
    			$is_error++;
    			$smarty->assign("pho_err",1);
    		}
        }
		
		if($is_error)
		{
			$smarty->assign("CONTACT_PERSON",stripslashes($CONTACT_PERSON));
			$smarty->assign("ADDRESS",stripslashes($ADDRESS));
			$smarty->assign("PHONE",stripslashes($PHONE));
			$smarty->assign("MOBILE",stripslashes($MOBILE));
			$smarty->assign("STATE",stripslashes($STATE));
			$smarty->assign("branch",$branch);
			$smarty->assign("sel_table",$sel_table);
			display_info($sel_table);
		}
		else
		{
			if($sel_table== "CONTACT_US")
			{
				$changes=record_changes_contact($sel_table,$branch,$CONTACT_PERSON,$ADDRESS,$STATE,$MOBILE);
				if($changes)
				{
					$CONTACT_PERSON=trim($CONTACT_PERSON);
					$ADDRESS=trim($ADDRESS);
					$STATE=trim($STATE);
					$MOBILE=trim($MOBILE);
					$sql="UPDATE newjs.$sel_table set CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',STATE='$STATE',MOBILE='$MOBILE' WHERE VALUE='$branch'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());	
					$msg= " Record Updated<br>  ";
				}
				else
					$msg= " No Changes Made<br>  ";
			}			
			else
			{
				$changes=record_changes($sel_table,$branch,$CONTACT_PERSON,$ADDRESS,$PHONE);	
				if($changes)
				{
					//$subj='Table '.$sel_table.' changed by '.$name;
					//mail('lavesh.rawat@jeevansathi.com',$subj,$changes);
					$CONTACT_PERSON=trim($CONTACT_PERSON);
					$ADDRESS=trim($ADDRESS);
					$PHONE=trim($PHONE);
					$sql="UPDATE newjs.$sel_table set 	CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE' WHERE VALUE='$branch'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());	
	
				/*$sql = "INSERT INTO jsadmin.CONTACT_US_BRANCHES_CHANGE(ID,CHANGED_TABLE,USER,DATE,CHANGE_DETAILS,COMPANY) VALUES ('','$sel_table','$name',NOW(),'".addslashes(stripslashes($changes))."','JS')";
			                mysql_query_decide($sql) or die(mysql_error_js());*/

					$msg= " Record Updated<br>  ";
				}
				else
				$msg= " No Changes Made<br>  ";
			}
                        $msg .="<a href=\"contact_us.php?cid=$cid\">";
                        $msg .="Continue </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	elseif($Go)
	{
		$smarty->assign("sel_table",$sel_table);
		display_info($sel_table);
	}
	else
	{
		$smarty->assign("menu_page",1);
		$smarty->display("contact_us.htm");	
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}

function record_changes_contact($sel_table,$branch,$CONTACT_PERSON,$ADDRESS,$STATE,$MOBILE)
{
	$sql="SELECT CONTACT_PERSON,ADDRESS,MOBILE,STATE FROM newjs.$sel_table where VALUE='$branch'";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	$myrow=mysql_fetch_array($result);

	$old_add=addslashes(stripslashes($myrow['ADDRESS']));
	$old_contact=addslashes(stripslashes($myrow['CONTACT_PERSON']));
	$old_state=addslashes(stripslashes($myrow['STATE']));
	$old_mobile=addslashes(stripslashes($myrow['MOBILE']));

	$ADDRESS=addslashes(stripslashes($ADDRESS));
	$CONTACT_PERSON=addslashes(stripslashes($CONTACT_PERSON));
	$MOBILE=addslashes(stripslashes($MOBILE));
	$STATE=addslashes(stripslashes($STATE));
	
	if( (trim($ADDRESS)!=trim($old_add)) || (trim($CONTACT_PERSON)!=trim($old_contact)) || (trim($MOBILE)!=trim($old_mobile)) || (trim($STATE)!=trim($old_state)))
		return(1);
	else
		return(0);
		
}

function record_changes($sel_table,$branch,$CONTACT_PERSON,$ADDRESS,$PHONE)
{
	$sql="SELECT CONTACT_PERSON,ADDRESS,PHONE FROM newjs.$sel_table where VALUE='$branch'";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	$myrow=mysql_fetch_array($result);

	$old_add=addslashes(stripslashes($myrow['ADDRESS']));
	$old_contact=addslashes(stripslashes($myrow['CONTACT_PERSON']));
	$old_phone=addslashes(stripslashes($myrow['PHONE']));

	$ADDRESS=addslashes(stripslashes($ADDRESS));
	$PHONE=addslashes(stripslashes($PHONE));
	$CONTACT_PERSON=addslashes(stripslashes($CONTACT_PERSON));
	

	/*if(trim($ADDRESS)!=trim($old_add))
		$comments="<br><b>"." ADDRESS :"."</b><br>"." Changed  From "."<b>".stripslashes($old_add)."</b><br>"." To "."<b>".stripslashes($ADDRESS)."</b><br>";
	if(trim($CONTACT_PERSON)!=trim($old_contact))
		$comments.="<br><b>"." CONTACT_PERSON :"."</b><br>"." Changed  From "."<b>".stripslashes($old_contact)."</b><br>"." To "."<b>".stripslashes($CONTACT_PERSON)."</b><br>";
	if(trim($PHONE)!=trim($old_phone))
		$comments.="<br><b>"." PHONE :"."</b><br>"." Changed  From "."<b>".stripslashes($old_phone)."</b><br>"." To "."<b>".stripslashes($PHONE)."</b><br>";*/
	
	if( (trim($ADDRESS)!=trim($old_add)) || (trim($CONTACT_PERSON)!=trim($old_contact)) || (trim($PHONE)!=trim($old_phone)) )
		return(1);
	else
		return(0);
		
}


function display_info($sel_table)
{
	global $smarty;
	$sql="SELECT * FROM newjs.$sel_table order by NAME ASC";
	$result=mysql_query_decide($sql) or die(mysql_error_js()); //logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$cnt=0;
	while($myrow=mysql_fetch_array($result))
	{
		$string = strtolower($myrow['NAME']);
		$string = substr_replace($string, strtoupper(substr($string, 0, 1)), 0, 1);
		$cnt++;
		$values.=$myrow['VALUE']."#";
		$address=stripslashes($myrow['ADDRESS']);
		$value=$myrow['VALUE'];
		$name=$string;
		$contact_person=stripslashes($myrow['CONTACT_PERSON']);
		$phone=stripslashes($myrow['PHONE']);
		$state=stripslashes($myrow['STATE']);
		$mobile=stripslashes($myrow['MOBILE']);
		
																	     
		while($contact_person!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$contact_person)))
			$contact_person=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$contact_person));
		while($value!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$value)))
			$value=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$value));
		while($name!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$name)))
			$name=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$name));
		while($address!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$address)))
			$address=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$address));
		while($phone!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$phone)))
			$phone=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$phone));
		while($state!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$state)))
			$state=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$state));
		while($mobile!=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$mobile)))
			$mobile=ereg_replace("\r\n|\n\r|\n|\r"," ",str_replace("\"","'",$mobile));

		if($sel_table= "CONTACT_US")
		{
			$branch[]=array("VALUE" => $value,
				"NAME" => $name,
				"CONTACT_PERSON" => $contact_person,
				"ADDRESS" => $address,
				"MOBILE" =>$mobile,
				"STATE"=>$state
				);
		}
		else
		{								     
			$branch[]=array("VALUE" => $value,
				"NAME" => $name,
				"CONTACT_PERSON" => $contact_person,
				"ADDRESS" => $address,
				"PHONE" => $phone
				);
		}
	}
	$smarty->assign("BRANCHES",$branch);
	$smarty->assign("VALUES",$values);
	$smarty->assign("COUNT",$cnt);
	$smarty->display("contact_us.htm");
}

?>
