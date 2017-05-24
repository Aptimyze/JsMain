<?php
include("connect.inc");
$data           =authenticated($cid);
$db             =connect_db();
$user           =trim(getname($cid));

if($data)
{
	if($Go)
	{
		if($phrase=='U')
		{
			$sql="SELECT PROFILEID,USERNAME from newjs.JPROFILE where ";
                        if(is_numeric($username))
                                $sql.= "PROFILEID='$username'";
                        else
                                $sql.= "USERNAME='$username'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(!mysql_num_rows($result)){
				$sql="SELECT PROFILEID FROM newjs.CUSTOMISED_USERNAME WHERE OLD_USERNAME='$username'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if($row=mysql_fetch_array($res))
                                {
                                        $sql="SELECT PROFILEID, USERNAME from newjs.JPROFILE where PROFILEID='$row[PROFILEID]'";
                                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                }
			}
		}
		else
		{
			$sql="SELECT PROFILEID,USERNAME from newjs.JPROFILE where EMAIL='$username'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(!mysql_num_rows($result)){
				$sql_email ="SELECT PROFILEID FROM newjs.OLDEMAIL where OLD_EMAIL='$username'";
                                $result_email=mysql_query_decide($sql_email) or die("$sql_email".mysql_error_js());
                                if($myrow_email=mysql_fetch_array($result_email))
				{
                                        $sql ="SELECT PROFILEID, USERNAME from newjs.JPROFILE where PROFILEID='$myrow_email[PROFILEID]'";
                                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                }
			}
		}

		if(mysql_num_rows($result)==0){
			$result	='N';	
		}
		else{
			$myrow=mysql_fetch_array($result);
			$profileid	=$myrow['PROFILEID'];
			$username 	=$myrow['USERNAME'];

			// Fetch the sugarcrm LTF data set 
			$sql_ltf ="select * from MIS.LTF WHERE PROFILEID='$profileid'";
			$result_ltf =mysql_query_decide($sql_ltf) or die("$sql_ltf".mysql_error_js());
			while($row_ltf=mysql_fetch_array($result_ltf))
			{
				$type 				=$row_ltf['TYPE'];
				$exec		 		=$row_ltf['EXECUTIVE'];
				$date				=getIST($row_ltf['DATE']);
				$dataSet["$type"]               =$date;
						

			}
			// Ends the sugarcrm LTF data set

			$smarty->assign("dataSet",$dataSet);
			$smarty->assign("username",$username);
			$smarty->assign("exec",$exec);
		}
		$smarty->assign("SEARCH","YES");
		$smarty->assign("RESULT",$result);
	}
	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	$smarty->display("sugarcrm_LTF_search.htm");
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
