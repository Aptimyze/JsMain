<?php
/*****************************************************************************************************************************		FILENAME      : useredit.php
*	   MODIIFICATION : Changes made to mark a profile 'INCOMPLETE' in case profile is new and YOURINFO field on edit
*			   page is empty or rendered empty after screening.
*			   Modified lines : 23 , 27 , 30 - 31 ,73 - 77 respectively. 
*	   DONE ON       : 19th May 2005 BY Shobha.
*
****************************************************************************************************************************/

include("time.php");
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/arrays.php");
mail("kunal.test02@gmail.com","jsadmin/oldfile/useredit.php in USE",print_r($_SERVER,true));
if(authenticated($cid))
{

	if($Submit)
	{
                $email_ev   = 1;
		$act	    = 1;

		//$sql="SELECT USERNAME from newjs.JPROFILE where PROFILEID='$pid'";
		$sql="SELECT USERNAME , ACTIVATED from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result);
		$username	= $myrow['USERNAME'];
		$activated	= $myrow['ACTIVATED'];
		
		if ($activated == 'U')
			$act = 0;
	
		if ($name!="")
		{
			$NAME=explode(",",$name); 
			for($i=0;$i<count($NAME);$i++)
			{
				if($NAME[$i]=="PHONE_RES")
					$screen=setFlag("PHONERES",$screen);
				elseif($NAME[$i]=="PHONE_MOB")
					$screen=setFlag("PHONEMOB",$screen);
				elseif($NAME[$i]=="CITY_BIRTH")
					$screen=setFlag("CITYBIRTH",$screen);
				elseif($NAME[$i]=="MESSENGER_ID")
					$screen=setFlag("MESSENGER",$screen);
				else
					$screen=setFlag("$NAME[$i]",$screen);
			}
	
			for($i=0;$i<count($NAME);$i++)
			{
				$str .= $NAME[$i]." = '".$_POST[$NAME[$i]]."' ,";
                                if($NAME[$i]=="YOURNAME" && $_POST[$NAME[$i]]=="" )
                                {
                                        //$email_ev=0;
					$bl_msg="<b>Please Note : </b>We have removed the content that you had put in \
						Other Information about yourself<br>.Please add related/valid/clear \
						information in this field. Better description will get you better results.\
						<br><br>Please" ;

					$bl_msg.="<a href = \"http://www.jeevansathi.com/profile/editprofile.php?checksum=&mail=Y\"> click here </a>";
					$bl_msg.=" to edit your profile <br>";
					//$bl_msg.=" <br><br>regards.<br><br> Jeevansathi.com Team ";                    
                                }
			}

			$str = rtrim($str,","); 
			//$sql = "UPDATE newjs.JPROFILE set $str, SCREENING='$screen', ACTIVATED='Y' where PROFILEID='$pid'";
			$sql = " UPDATE newjs.JPROFILE set $str, SCREENING='$screen',";

                        if (0)//!$act && !$email_ev) //condition removed on 23May05 By Alok
                                $sql.= "ACTIVATED = 'N' AND INCOMPLETE ='Y' ";
                        else
                                $sql.= "ACTIVATED = 'Y' ";

                        $sql.= " where PROFILEID = '$pid' ";
			mysql_query_decide($sql);
			
			$sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'APPROVED', SUBSCRIPTION_TYPE, SCREENING_VAL from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";  
			mysql_query_decide($sql);

			$sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			mysql_query_decide($sql);

			$msg = "User $username is successfully screened<br><br>";
               	}
		else
			$msg="User $username is already screened<br><br>";                                                                                  
                $msg .= "<a href=\"userview.php?user=$user&cid=$cid\">";

                $msg .= "Continue &gt;&gt;</a>";

		$sql="SELECT EMAIL from newjs.JPROFILE where PROFILEID in ($pid)";
                $r1=mysql_query_decide($sql);
                $r2=mysql_fetch_array($r1);
                $to=$r2['EMAIL'];
//		echo $to;

		$mail_msg = "Dear $username <br><br> We thank you for your interest in Jeevansathi.com\
                              <br><br>This is to notify you that your profile submitted with us has been screened through. \
				and will now be fully visible to every user who wishes to see your profile.<br>";
		if(!$email_ev)
			$mail_msg .= $bl_msg;

		$mail_msg .= "<br>Hope you have a lifetime experience with us.<br><br>With regards,<br>Jeevansathi.com Team";

		send_email($to,$mail_msg);

                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
	}
	elseif($Submit1)
	{
/*		
		$sql="SELECT ACTIVATED from newjs.JPROFILE where PROFILEID = '$pid'";
                $result=mysql_query_decide($sql);
                $myrow=mysql_fetch_array($result);
                $activated=$myrow['ACTIVATED'];
                $sql="UPDATE newjs.JPROFILE set PREACTIVATED='$activated', ACTIVATED='D', ACTIVATE_ON=now() where PROFILEID='$pid'";
                mysql_query_decide($sql);
*/
                $smarty->assign("user",$user);
                $smarty->assign("pid",$pid);
                $smarty->assign("cid",$cid);
		$smarty->assign("c","1");
                $smarty->assign("FROM","U");
		$smarty->display("delete_page.tpl");
	}
	else
	{
		$sql="SELECT USERNAME, SCREENING,GENDER,AGE,COUNTRY_RES,CITY_RES,MSTATUS,MANGLIK,MTONGUE,SUBCASTE,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL from newjs.JPROFILE where PROFILEID=$pid";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);

		$smarty->assign("USERNAME",$myrow["USERNAME"]);
		$screen=$myrow['SCREENING'];

		$smarty->assign("SHOW_AGE",$myrow["AGE"]);
		$smarty->assign("SHOW_GENDER",$myrow["GENDER"]);
		$smarty->assign("SHOW_COUNTRY",label_select("COUNTRY",$myrow["COUNTRY_RES"]));

		if($myrow["COUNTRY_RES"]=='51')
			$smarty->assign("SHOW_CITYRES",label_select("CITY_INDIA",$myrow["CITY_RES"]));
		elseif($myrow["COUNTRY_RES"]=='128')
			$smarty->assign("SHOW_CITYRES",label_select("CITY_USA",$myrow["CITY_RES"]));
		else
			$smarty->assign("SHOW_CITYRES","");

//		$manglik=$MANGLIK[$myrow["MANGLIK"]];
//		$smarty->assign("SHOW_MANGLIK",$manglik);
		//$mstatus=$MSTATUS[$myrow["MSTATUS"]];
		//$smarty->assign("SHOW_MSTATUS",$mstatus[0]);
		$smarty->assign("SHOW_MSTATUS",$MSTATUS["$myrow[MSTATUS]"]);
		$smarty->assign("SHOW_MTONGUE",label_select("MTONGUE",$myrow["MTONGUE"]));

		$subcaste_set=isFlagSet("SUBCASTE",$screen);
		$citybirth_set=isFlagSet("CITYBIRTH",$screen);
		$gothra_set=isFlagSet("GOTHRA",$screen);
		$nakshatra_set=isFlagSet("NAKSHATRA",$screen);
		$messenger_set=isFlagSet("MESSENGER",$screen);
		$yourinfo_set=isFlagSet("YOURINFO",$screen);
		$familyinfo_set=isFlagSet("FAMILYINFO",$screen);
		$spouse_set=isFlagSet("SPOUSE",$screen);
		$contact_set=isFlagSet("CONTACT",$screen);
		$education_set=isFlagSet("EDUCATION",$screen);
		$phoneres_set=isFlagSet("PHONERES",$screen);
		$phonemob_set=isFlagSet("PHONEMOB",$screen);
		$email_set=isFlagSet("EMAIL",$screen);
//		$originstate_set=isFlagSet("MTONGUE",$screen);
//		$caste_set=isFlagSet("CASTE",$screen);
//		$religion_set=isFlagSet("RELIGION",$screen);
		
		
/*		if(!$originstate_set)
		{
			$item[]="ORIGINSTATE";
			$smarty->assign("SHOWORIGINSTATE","Y");
		}
		if(!$caste_set)
		{
			$item[]="CASTE";
			$smarty->assign("SHOWCASTE","Y");
		}
		if(!$subcaste_set)
		{
			$item[]="SUBCASTE";
			$smarty->assign("SHOWCASTE","Y");
		}
*/
		if(!$subcaste_set)
		{
			$item[]="SUBCASTE";
			$smarty->assign("SHOWSUBCASTE","Y");
			$smarty->assign("SUBCASTEvalue",$myrow['SUBCASTE']);
		}
		if(!$citybirth_set)
		{
			$item[]="CITY_BIRTH";
			$smarty->assign("SHOWCITY","Y");
			$smarty->assign("CITY_BIRTHvalue",$myrow['CITY_BIRTH']);
		}
		if(!$gothra_set)
		{
			$item[]="GOTHRA";
			$smarty->assign("SHOWGOTHRA","Y");
			$smarty->assign("GOTHRAvalue",$myrow['GOTHRA']);
		}
		if(!$nakshatra_set)
		{
			$item[]="NAKSHATRA";
			$smarty->assign("SHOWNAKSHATRA","Y");
			$smarty->assign("NAKSHATRAvalue",$myrow['NAKSHATRA']);
		}
		if(!$messenger_set)
		{
			$item[]="MESSENGER_ID";
			$smarty->assign("SHOWMESSENGER","Y");
			$smarty->assign("MESSENGER_IDvalue",$myrow['MESSENGER_ID']);
		}
		if(!$yourinfo_set)
		{
			$item[]="YOURINFO";
			$smarty->assign("SHOWYOURINFO","Y");
			$smarty->assign("YOURINFOvalue",$myrow['YOURINFO']);
		}
		if(!$familyinfo_set)
		{
			$item[]="FAMILYINFO";
			$smarty->assign("SHOWFAMILYINFO","Y");
			$smarty->assign("FAMILYINFOvalue",$myrow['FAMILYINFO']);
		}
		if(!$spouse_set)
		{
			$item[]="SPOUSE";
			$smarty->assign("SHOWSPOUSE","Y");
			$smarty->assign("SPOUSEvalue",$myrow['SPOUSE']);
		}
		if(!$contact_set)
		{
			$item[]="CONTACT";
			$smarty->assign("SHOWCONTACT","Y");
			$smarty->assign("CONTACTvalue",$myrow['CONTACT']);
		}
		if(!$education_set)
		{
			$item[]="EDUCATION";
			$smarty->assign("SHOWEDUCATION","Y");
			$smarty->assign("EDUCATIONvalue",$myrow['EDUCATION']);
		}
		if(!$phoneres_set)
		{
			$item[]="PHONE_RES";
			$smarty->assign("SHOWPHONERES","Y");
			$smarty->assign("PHONE_RESvalue",$myrow['PHONE_RES']);
		}
		if(!$phonemob_set)
		{
			$item[]="PHONE_MOB";
			$smarty->assign("SHOWPHONEMOB","Y");
			$smarty->assign("PHONE_MOBvalue",$myrow['PHONE_MOB']);
		}
		if(!$email_set)
		{
			$item[]="EMAIL";
			$smarty->assign("SHOWEMAIL","Y");
			$smarty->assign("EMAILvalue",$myrow['EMAIL']);
		}
		if(count($item)>0)
		{
			$itemstring=implode(",",$item);
			/*$sql="SELECT $itemstring from newjs.JPROFILE where PROFILEID=$pid"; 
			$result=mysql_query_decide($sql);
			$myrow=mysql_fetch_array($result);*/
		
			/*for($i=0;$i<count($item);$i++)
			{
				$itemvalue=$myrow[$i];
				$itemname=$item[$i]."value";
				$smarty->assign("$itemname",$itemvalue);
			}
			$itemstring=implode(",",$item);*/
			$smarty->assign("names",$itemstring);
		}
		$smarty->assign("pid",$pid);
		$smarty->assign("screen",$screen);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->display("user_edit.tpl");
	}
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
