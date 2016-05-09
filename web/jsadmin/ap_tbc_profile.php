<?php

/******************    Include Files  ********************/
ob_start();
$flag_using_php5=1;
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/functions.inc");
include_once("ap_common.php");
include_once("ap_functions.php");

/*************   Include Files Ends  ****************/

if(!authenticated($cid))
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
        exit;
}
        $PERSON_LOGGED_IN=1;
        $VIEWPROFILE_IMAGE_URL="http://ser2.jeevansathi.com/profile";
        global $listMainArray;
        $name = getname($cid);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);

        /* Section to get role of the logged in user
        *  Roles defines as -> SE,QA,DISPATCHER,TELECALLER      
        */
        $role = fetchRole($cid);
        $smarty->assign("ROLE",$role);
        /* Ends */

	if($Go && $username)
	{
                if($phrase=='U')
                {
                        $sql="SELECT `PROFILEID` from newjs.JPROFILE where ";
                        if(is_numeric($username))
                                $sql.= "PROFILEID='$username'";
                        else
                                $sql.= "USERNAME='$username'";
                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        if(!mysql_num_rows($result))
                        {
                                $sql="SELECT `PROFILEID` FROM newjs.CUSTOMISED_USERNAME WHERE OLD_USERNAME='$username'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if($row=mysql_fetch_array($res))
                                {
                                        $sql="SELECT `PROFILEID` from newjs.JPROFILE where PROFILEID='$row[PROFILEID]'";
                                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                }
                        }
                }
                else
                {
                        $sql="SELECT `PROFILEID` from newjs.JPROFILE where EMAIL='$username'";
                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                }
		while($myrow=mysql_fetch_array($result)){
			$profileid =$myrow["PROFILEID"];
		}
		if($profileid)	
			header("Location: $SITE_URL/jsadmin/ap_viewprofile.php?cid=$cid&page=MYPROFILE&list=PULL&callreq_pid=$profileid&qtype=userid");
		else
			$smarty->assign("errMsg","1");	
	
	}
	else if($Go && $username=='')
		$smarty->assign("errMsg","1");	

	$smarty->display("ap_tbc_profile.tpl");
?>
