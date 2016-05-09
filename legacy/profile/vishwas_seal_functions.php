<?php
function send_verify_email($profileid,$returnsuccess='')
{
	global $SITE_URL;
        $sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row=mysql_fetch_assoc($res);
        $email=$row['EMAIL'];
        $uname=$row['USERNAME'];

        $msg="Dear $uname,<br><br> 
	This is a verification email sent to you by Jeevansathi.com. To get the Vishwas seal on your matrimony profile on Jeevansathi.com, verify your email id by clicking on the following link:<br><br>";
        $msg.="<a href='$SITE_URL/profile/mainmenu.php?verifyEmail=1&CALL_ME=myjs_vishwas_seal.php%3FMYMOBILE%3D%26PROFILEID%3D$profileid%26width%3D700%26ajax_error%3D1%26random%3D1235036554401'>'$SITE_URL/profile/mainmenu.php?verifyEmail=1&CALL_ME=myjs_vishwas_seal.php%3FMYMOBILE%3D%26PROFILEID%3D$profileid%26width%3D700%26ajax_error%3D1%26random%3D1235036554401'</a>";

        $msg.="<br><br>Wishing you the best in your matrimony search.<br><br>The Jeevansathi Team";

        send_email($email,$msg,"Verify your email","info@jeevansathi.com");
        if($email && $returnsuccess)
                return $email;

}

function verify_vishwas_seal($field,$profileid)
{
        $$field=1;
        $date=date('Y-m-d');
        if($field=='email')
        {
                $sql="REPLACE INTO newjs.VERIFY_EMAIL (PROFILEID,ENTRY_DT,STATUS) VALUES ($profileid,'$date','Y')";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        }
        elseif($field!='email')
        {
                $sql_email="SELECT STATUS FROM newjs.VERIFY_EMAIL WHERE PROFILEID=$profileid";
                $res_email=mysql_query_decide($sql_email) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_email,"ShowErrTemplate");
                $row_email=mysql_fetch_assoc($res_email);
                if($row_email['STATUS']=='Y')
                        $email=1;
        }
        if($field!='address' && $email)
        {
                $sql_add="SELECT SCREENED FROM jsadmin.ADDRESS_VERIFICATION WHERE PROFILEID=$profileid";
                $res_add=mysql_query_decide($sql_add) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row_add=mysql_fetch_assoc($res_add);
                if($row_add['SCREENED']=='Y')
                        $address=1;
        }
        if($field!='mobile' && $email && $address)
        {
		/* Code to be removed
                $sql_jprof="SELECT PHONE_MOB FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
                $res_jprof=mysql_query_decide($sql_jprof) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row_jprof=mysql_fetch_assoc($res_jprof);
                if($row_jprof['PHONE_MOB'])
                {
                        $mobile=$row_jprof['PHONE_MOB'];
                        $sql_mob="SELECT STATUS FROM newjs.MOBILE_VERIFICATION_SMS WHERE MOBILE='$mobile'";
                        $res_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $row_mob=mysql_fetch_assoc($res_mob);
                        if($row_mob['STATUS']=='Y')
                                $address=1;
                }
		// ivrCheck for phone Verification
		if(!$address)
		{
                        include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
                        $phoneCheck = getPhoneValidity($profileid);
			if($phoneCheck)
				 $address=1;
		}
		// end ivrCheck
		*/

		include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
		$chk_phoneStatus =getPhoneStatus('',$profileid);
		if($chk_phoneStatus =='Y')
			$mobile=1;
        }
        if($email && $address && $mobile)
        {
                $sql_vs="REPLACE INTO newjs.VISHWAS_SEAL (PROFILEID,ENTRY_DT,STATUS) VALUES ($profileid,'$date','Y')";
                $res_vs=mysql_query_decide($sql_vs) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_vs,"ShowErrTemplate");

                $sql_sj="INSERT IGNORE INTO newjs.SWAP_JPROFILE (PROFILEID) VALUES ($profileid)";
                $res_sj=mysql_query_decide($sql_sj) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sj,"ShowErrTemplate");

		$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
	        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	        $row=mysql_fetch_assoc($res);
	        $email=$row['EMAIL'];
		$msg="Dear member,<br>";
		$msg.="We are glad to inform you that the 'Vishwas Seal' will now be visible on your profile to other members of Jeevansathi.com. This gives your profile a seal of trust and will improve the credibility and response of your matrimony profile.<br><br>";
		$msg.="Thank you and wishing you the best in your matrimony search.<br>";
		$msg."The Jeevansathi team";

		send_email($email,$msg,"Your Jeevansathi profile has the seal of trust now","info@jeevansathi.com");

        }
}

function update_vishwas_seal($fields,$profileid,$val='')
{
        $date=date('Y-m-d');
        $field_arr=explode(",",$fields);
        foreach($field_arr as $k=>$field)
        {
                if($field=='email')
                {
                        $sql="REPLACE INTO newjs.VERIFY_EMAIL (PROFILEID,ENTRY_DT,STATUS) VALUES ($profileid,'$date','N')";
                	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                }
                elseif($field=='address')
                {
                        $sql="UPDATE jsadmin.ADDRESS_VERIFICATION SET SCREENED='$val', DATE='$date' WHERE PROFILEID='$profileid'";
                	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                }
		/*
                elseif($field=='mobile')
                {
			$mobile=$val;
                        //$sql="UPDATE newjs.MOBILE_VERIFICATION_SMS SET STATUS='N' AND ENTRY_DT=NOW() WHERE MOBILE='$mobile'";
			$sql_sel="SELECT COUNT(*) AS CNT FROM newjs.MOBILE_VERIFICATION_SMS WHERE MOBILE='$mobile'";
                	$res=mysql_query_decide($sql_sel) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sel,"ShowErrTemplate");
			$row=mysql_fetch_assoc($res);
			$verify_mob=$row['CNT'];
                }
		elseif($field=='landline')
		{
			// ivrCheck for phone Verification
			include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
			$phoneCheck = getPhoneValidity($profileid);
			// end ivrCheck
		}
		*/	
		elseif($field =='mobile' || $field =='landline' || $field=="alternate")
		{
	                include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
	                $chk_phoneStatus =getPhoneStatus('',$profileid);
	                if($chk_phoneStatus =='Y')
	                        $verify_mob=1;
		}
        }
	if(!$verify_mob)
	{
		$sql_vs="UPDATE newjs.VISHWAS_SEAL SET ENTRY_DT='$date',STATUS='N' WHERE PROFILEID='$profileid'";
		$res_vs=mysql_query_decide($sql_vs) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                $sql_sj="INSERT IGNORE INTO newjs.SWAP_JPROFILE (PROFILEID) VALUES ($profileid)";
                $res_sj=mysql_query_decide($sql_sj) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sj,"ShowErrTemplate");
	}
}
?>
