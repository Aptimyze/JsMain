<?php
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");


if(authenticated($cid))
{
	$name = getname($cid);
	$errorSet =false;
	$phoneType ='';

	if($verify_phone_alt!='Y' && $verify_phone_res!='Y' && $verify_phone_mob!='Y')
		$errorSet =true;

	if($verify && !$errorSet)
	{
		$message ='OPS';

                if("Y"==$verify_phone_res){
                        $phoneType ='L';
                        phoneUpdateProcess($profileid,$phone_res,$phoneType,'Y',$message,$name);
                }
                if("Y"==$verify_phone_mob){
                        $phoneType ='M';
                        phoneUpdateProcess($profileid,$phone_mob,$phoneType,'Y',$message,$name);
                }
		if("Y"==$verify_phone_alt){
			$phoneType ='A';
			phoneUpdateProcess($profileid,$phone_alt,$phoneType,'Y',$message,$name);
		}
		if($phoneType!=''){
			logPhoneVerification($profileid,$name);
			$smarty->assign("VERIFIED",1);
		}
	}
	else
	{
		$chk_phoneStatus =getPhoneStatus('',$profileid);
		if($chk_phoneStatus =='Y')
			$smarty->assign("ALREADY_VERIFIED",1);
		else{
                	$sql_jp = "SELECT PHONE_WITH_STD,PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                	$res_jp = mysql_query_decide($sql_jp) or die($sql_jp.mysql_error_js());
                	$row_jp = mysql_fetch_array($res_jp);

                	$phone_res = $row_jp["PHONE_WITH_STD"];
                	$phone_mob = $row_jp["PHONE_MOB"];

                	$sql_alt = "SELECT ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
                	$res_alt = mysql_query_decide($sql_alt) or die($sql_alt.mysql_error_js());
                	$row_alt = mysql_fetch_array($res_alt);
                	$phone_alt = $row_alt["ALT_MOBILE"];

			$smarty->assign("phone_alt",$phone_alt);
			$smarty->assign("phone_res",$phone_res);
			$smarty->assign("phone_mob",$phone_mob);
		}
	}
	$smarty->assign("profileid",$profileid);
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->assign("errorSet",$errorSet);
	$smarty->display("phone_number_validation.htm");
}
else
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

function logPhoneVerification($profileid,$name)
{
	$sql_ins = "INSERT INTO incentive.PHONE_DAILY_VERIFICATION(PROFILEID,VERIFIED_BY,VERIFIED_DT) VALUES('$profileid','$name',now())";
	mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());
}

?>
