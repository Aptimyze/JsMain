<?php
/*********************************************************************************************
* FILE NAME             : useredit_1min_top.php
* DESCRIPTION           : script for showing the already filled fields to the backend team
* CREATION DATE         : 13 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//include("time.php");
include("connect.inc");
//include(JsConstants::$docRoot."/commonFiles/flag.php");
//include("com_func_1min.php");
//include_once("../profile/arrays.php");

if(authenticated($cid))
{
		$sql="SELECT USERNAME,GENDER,AGE,EMAIL,DTOFBIRTH,AGE,PHONE_RES,YOURINFO from newjs.JPROFILE_AFFILIATE where ID=$pid";
		//$sql="SELECT * from newjs.JPROFILE_AFFILIATE where ID=$pid";
                //$sql="SELECT GENDER,AGE,COUNTRY_RES,CITY_RES,MSTATUS,MANGLIK,MTONGUE,RELIGION,CASTE,SUBCASTE,COUNTRY_BIRTH,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT from newjs.JPROFILE where PROFILEID=$pid";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                $myrow=mysql_fetch_array($result);
                //echo $myrow['YOURINFO'];
                $smarty->assign("Email",$myrow['EMAIL']);
                $smarty->assign("username",$myrow["USERNAME"]);
                $smarty->assign("gender",$myrow["GENDER"]);
                $smarty->assign("age",$myrow["AGE"]);
                $smarty->assign("DOB",$myrow["DTOFBIRTH"]);
                $smarty->assign("phone",$myrow['PHONE_RES']);
                $smarty->assign("Information_old",$myrow['YOURINFO']);
                $smarty->assign("pid",$pid);
                $smarty->assign("cid",$cid);
                $smarty->assign("user",$user);
                $smarty->display("user_edit_1min_top.htm");
}

?>
