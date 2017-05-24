<?php
        include("connect.inc");
	include("uploadphoto_inc.php");
	include_once("vishwas_seal_functions.php");
	$_SERVER['ajax_error']=1;

	$sb=connect_db();
	$data=authenticated($checksum);
	$smarty->assign("checksum",$checksum);
	$mypid=$data["PROFILEID"];
        if(!$data)
        {
                $smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
                $smarty->display("login_layer.htm");
                die;
        }

	if($filename)
	{
		$filename='filename';
		$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
		$default_extension = ".jpg";
		global $max_filesize;
		global $file,$errors;
		$max_filesize=1048576*5;
		//$max_filesize=104857600;
		$updateTable=1;

		if(upload($filename,$acceptable_file_types,$default_extension)==0)
		{
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$photo_content = addslashes($fcontent);

		}
		else
		{
			foreach($errors as $k=>$v)
			{
				$smarty->assign("ERROR",$v);
			}
			$updateTable=0;
		}

		if($updateTable)
		{
	                $sql="REPLACE into jsadmin.ADDRESS_VERIFICATION(PROFILEID,DOCUMENT,SCREENED,DATE) values('$mypid','$photo_content','X',now())";
			//echo "<script>alert('$updateTable')</script>";
        	        mysql_query_decide($sql) or die(mysql_error_js()) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			//echo "<script>alert('$updateTable')</script>";
			if(mysql_affected_rows_js()==0)
				$smarty->assign("ERROR","Your submitted entry is Under Screening.<br>Please Try Later.");
		}
		$smarty->assign("MSG",1);
		$smarty->display("myjs_vishwas_seal.htm");
		die;
	}
	else
	{
		//if(!$PROFILEID)
			$PROFILEID=$mypid;
		$smarty->assign("profileid",$PROFILEID);
		$sql1="SELECT PROFILEID,EMAIL,PHONE_MOB,PHONE_RES,STD,ISD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$PROFILEID'";
		$res1=mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row1=mysql_fetch_array($res1);
		$username=$row1["USERNAME"];
		$password=$row1["PASSWORD"];
		$email=$row1["EMAIL"];

		/* IVR Check for phone Verification */
                $MYRES=$row1["PHONE_RES"];
                $STD=$row1["STD"];
                $ISD=$row1["ISD"];
                $landline =$ISD." ".$STD." ".$MYRES;
		$smarty->assign("PHONE_RES",$landline);
		/*  end IVR Check  */

		$MYMOBILE=$row1["PHONE_MOB"];		
		$smarty->assign("MYMOBILE",$MYMOBILE);
		$smarty->assign("email",$email);

		/* IVR Check for phone Verification */ 
		$chk_phoneStatus_mob =getPhoneStatus($row1,'','M');
		$chk_phoneStatus_land =getPhoneStatus($row1,'','L');
		if($chk_phoneStatus_mob=='Y')
			$smarty->assign("MOB_VER",'Y');	
		if($chk_phoneStatus_land =='Y')		
			$smarty->assign("RES_VER",'Y');		
		/*  end IVR Check  */
                $sqlAlt ="SELECT ALT_MOBILE, ALT_MOBILE_ISD,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$PROFILEID."'";
                $resAlt =mysql_query_decide($sqlAlt);
                $rowAlt =mysql_fetch_array($resAlt);
		$alt_mobile=$rowAlt['ALT_MOBILE'];
		$alt_mob_status=$rowAlt['ALT_MOB_STATUS'];
		$smarty->assign("ALT_MOBILE",$alt_mobile);
		$smarty->assign("ALT_VER",$alt_mob_status);
		$sql_vis="SELECT count(*) as cnt FROM  newjs.VERIFY_EMAIL where PROFILEID='$PROFILEID' AND STATUS='Y'";
		$result_vis=mysql_query_decide($sql_vis) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
		$row_vis=mysql_fetch_array($result_vis);
		if($row_vis["cnt"]>0)
			$smarty->assign("emailverified",1);

		$sql_vis="SELECT SCREENED FROM jsadmin.ADDRESS_VERIFICATION where PROFILEID='$PROFILEID'";
		$result_vis=mysql_query_decide($sql_vis) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
		$row_vis=mysql_fetch_array($result_vis);
		if($row_vis["SCREENED"]=='Y')
			$smarty->assign("postalverified",1);
		elseif($row_vis["SCREENED"]=='X')
                        $smarty->assign("postalunderScreening",1);
		$smarty->display("myjs_vishwas_seal.htm");
	}
?>
