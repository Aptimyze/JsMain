<?php

include ("connect.inc");
$empty=1;
$db_dnc =connect_dnc();

if(authenticated($cid))
{
	$name=getname($cid);
	$smarty->assign('cid',$cid);
        if($submit && $mobile)
	{
	        if($mobile)
	        {
	        	$mobile =checkPhoneNumber($mobile);
	                if(!$mobile || (strlen($mobile)!=10)){
	                	$empty=0;
	                        $smarty->assign('check_mobile','1');
	                }       
	        }       
		if($empty==0)
		{
			$smarty->assign('mobile',$mobile);
                        $smarty->display('dncNumberCheck.htm');
		}
                else
		{
			$sql ="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE='$mobile'";
			$result =mysql_query_decide($sql,$db_dnc) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($result);
			$phone 	=$row['PHONE'];
			if($phone)
				$isDNC =true;
			else
				$isDNC =false;

			$smarty->assign("mobile",$mobile);
			$smarty->assign('dncNumberExist',"$isDNC");
			$smarty->assign("resultSet",'1');
			$smarty->display("dncNumberCheck.htm");
		}
	}
	else
		$smarty->display("dncNumberCheck.htm");

}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
