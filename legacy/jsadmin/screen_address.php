<?php
/******************************************************************************************************************
file        : screen_address.php
Description : script to upload, browse and verify address of a user
Created By  : Neha Verma
Created On  : 29 Dec 2008
*******************************************************************************************************************/

include("connect.inc");
include_once("../profile/vishwas_seal_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db=connect_db();
if(authenticated($cid))
{
	if($pid || $username)
	{
		if($submit)
		{
			if($verified!='Y')
				$verified='N';
			$sql="UPDATE jsadmin.ADDRESS_VERIFICATION SET SCREENED='$verified' WHERE PROFILEID=$pid";
			mysql_query_decide($sql) or die($sql.mysql_error_js());
			if($verified!='Y')
			{
				update_vishwas_seal('address',$pid,'N');
				$msg=$username."'s address has been marked as not verified.";
			}
			else
			{
				verify_vishwas_seal('address',$pid);
				$msg=$username."'s address has been marked as Verified.";
			}
			$smarty->assign('msg',$msg);	
		}
		elseif($view)
		{
			$sql="SELECT DOCUMENT FROM jsadmin.ADDRESS_VERIFICATION WHERE PROFILEID=$pid";
			$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
			$row=mysql_fetch_assoc($res);
			header ("Content-Type: image/jpeg");
			echo $row['DOCUMENT'];
			
			die;
		}
		elseif($upload)
		{
			include('../profile/uploadphoto_inc.php');
			global $max_filesize;
			global $file;
			$profileid=$data["PROFILEID"];
			$max_filesize = 1048576; //1MB
			$flag_error = 0;
			$flag_upload = 0;
			$CHECKSUM = $data["CHECKSUM"];
			$acceptable_file_types = "image/gif|image/jpeg|image/jpg";
			$default_extension = ".jpg";
			
			if($which_photo == "mainphoto")
			{
				$filename="mainphotofile";
				//upload main photo
				if(upload($filename, $acceptable_file_types, $default_extension))
				{
					$msg="File not uploaded. Please check that image size is less than 1MB. Also only .gif,.jpeg and .jpg file format are accepted.";
				}
				else
				{
					if($errors[0]=="No file was uploaded")
						$msg=$errors[0];
					else
					{
						$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
						$fcontent = fread($fp,filesize($file["tmp_name"]));
						$fcontent = addslashes($fcontent);
						fclose($fp);
						$sql_ins="INSERT INTO jsadmin.ADDRESS_VERIFICATION (PROFILEID,DOCUMENT,SCREENED) VALUES ('$pid','$fcontent','X') ";
						mysql_query_decide($sql_ins) or die(mysql_error_js());
						$msg='Document uploaded successfully!!!!';
					}
				}
			}
			$smarty->assign('msg',$msg);
		}
		$sql="SELECT PROFILEID,CONTACT,GENDER,EMAIL,DTOFBIRTH,PHONE_RES,PHONE_MOB FROM newjs.JPROFILE WHERE USERNAME='$username'";
		$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			$smarty->assign('username',$username);
			$smarty->assign('name',$row['NAME']);
			$smarty->assign('gen',$row['GENDER']);
			$smarty->assign('email',$row['EMAIL']);
			$smarty->assign('contact',$row['CONTACT']);
			$smarty->assign('birth_dt',$row['DTOFBIRTH']);
			$phone_arr[]=$row['PHONE_RES'];
			$phone_arr[]=$row['PHONE_MOB'];
			$phone=implode(",",$phone_arr);
			$smarty->assign('phone',$phone);
			$pid=$row['PROFILEID'];

			$sql="SELECT SCREENED FROM jsadmin.ADDRESS_VERIFICATION WHERE PROFILEID=$pid";
			$res=mysql_query_decide($sql) or die($sql.mysql_error_js());
			if(mysql_num_rows($res))
				$row=mysql_fetch_assoc($res);
			$smarty->assign('screened',$row['SCREENED']);
			$smarty->assign('pid',$pid);
		}
		else
			$smarty->assign('blank','1');
	
	}
	else
		$smarty->assign('blank','1');

        $smarty->assign("data",$data);
        $smarty->assign("user",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("screen_address.htm");
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
