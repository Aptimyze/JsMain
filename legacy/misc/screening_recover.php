<?php
	include("connect.inc");

	$db = connect_slave();
	$sql = "SELECT PROFILEID FROM test.DOB_PID WHERE 1";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	while($row = mysql_fetch_array($res))
		$pid_arr[] = $row['PROFILEID'];

	$pid_str = implode("','",$pid_arr);
	unset($pid_arr);

	@//mysql_close();
	$db = connect_db();

	$sql = "SELECT a.PROFILEID, a.DTOFBIRTH AS PREV_DOB, b.DTOFBIRTH, a.GENDER AS PREV_GENDER, b.GENDER AS CUR_GENDER, a.MSTATUS AS PREV_MSTATUS, b.MSTATUS AS CUR_MSTATUS, a.PHOTO_DISPLAY AS PREV_PHOTO_DISPLAY, b.PHOTO_DISPLAY AS CUR_PHOTO_DISPLAY, a.ENTRY_TYPE, b.ENTRY_TYPE FROM jsadmin.SCREENING_LOG a, jsadmin.SCREENING_LOG b WHERE a.PROFILEID IN ('$pid_str') AND a.PROFILEID = b.PROFILEID AND a.ID = b.REF_ID AND a.DTOFBIRTH <> b.DTOFBIRTH";

	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		$profileid = $row['PROFILEID'];
		$gender = $row['PREV_GENDER'];
		$dob = $row['PREV_DOB'];
		$age = getAge($dob);
		$mstatus = $row['PREV_MSTATUS'];
		$photo_display = $row['PREV_PHOTO_DISPLAY'];

		$sql_upd = "UPDATE newjs.JPROFILE SET DTOFBIRTH='$dob', AGE='$age'";

		if(($row['PREV_GENDER'] != "" && $row['CUR_GENDER'] != "") && ($row['PREV_GENDER'] != $row['CUR_GENDER']))
		{
			$sql_upd .= " , GENDER='$gender'";
			gender_related_changes($profileid,$row['CUR_GENDER']);
		}
		if($row['PREV_MSTATUS'] != "" && $row['CUR_MSTATUS'] != "" && $row['PREV_MSTATUS'] != $row['CUR_MSTATUS'])
			$sql_upd .= " , MSTATUS='$mstatus'";
		if($row['PREV_PHOTO_DISPLAY'] != "" && $row['CUR_PHOTO_DISPLAY'] != "" && $row['PREV_PHOTO_DISPLAY'] != $row['CUR_PHOTO_DISPLAY'])
			$sql_upd .= " , PHOTO_DISPLAY='$photo_display'";

		$sql_upd .= " WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql_upd) or die("$sql".mysql_error_js());

		$mail_msg = "Dear member,\n\nWe have noticed that your date of birth has been inadvertently changed in our system due to a technical problem.  We are extremely sorry for any inconvenience this might have caused you. We have rectified the error now and have changed your date of birth back to its original value which is $dob as per our records. In case this is incorrect, please let us know by writing to vivek@jeevansathi.com. Once again, we regret the inconvenience and wish you all the best in your partner search.\n\nRegards\nJeevansathi Team";

		$sql_jp = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res_jp = mysql_query_decide($sql_jp) or die("$sql_jp".mysql_query_decide());
		$row_jp = mysql_fetch_array($res_jp);
		$email = $row_jp['EMAIL'];

		//send_email($to,$msg="",$subject="",$from="",$cc="",$bcc="",$attach="",$filetype="",$filename="",$registration="")
		send_email($email,nl2br($mail_msg),"Change of Date of Birth","vivek@jeevansathi.com");

		unset($profileid);
		unset($gender);
		unset($dob);
		unset($age);
		unset($mstatus);
		unset($photo_display);
		unset($email);
	}

	function gender_related_changes($pid, $previous_gender)
	{
		//selecting age and height to fill default values in JPARTNER.
		$sql_jprof = "SELECT AGE,HEIGHT FROM newjs.JPROFILE WHERE PROFILEID = '$pid'";
		$res_jprof = mysql_query_decide($sql_jprof) or die("$sql_jprof".mysql_error_js());
		$row_jprof = mysql_fetch_array($res_jprof);

		$age = $row_jprof['AGE'];
		$height = $row_jprof['HEIGHT'];

		$partner_tbl_arr = array('PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_FBACK','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RES_STATUS','PARTNER_SMOKE');

		delete_record($pid);

		// get partner id of the person to delete the entries from the corresponding PARTNER_ tables
		$partner_id_sql         = "SELECT PARTNERID FROM newjs.JPARTNER WHERE PROFILEID='$pid'";
		$partner_id_res         = mysql_query_decide($partner_id_sql);
		$partner_id_row         = mysql_fetch_array($partner_id_res);
		$partner_id             = $partner_id_row['PARTNERID'];

		// delete the corresponding entries from PARTNER_ tables
		for($i=0;$i<count($partner_tbl_arr);$i++)
		{
			$del_partner_sql = "DELETE FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
			mysql_query_decide($del_partner_sql) or die(mysql_error_js());
		}

		// update SEARCH_MALE / FEMALE tables in sync with the change of gender
		$sql="DELETE FROM  newjs.JPARTNER WHERE PARTNERID='$partner_id'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($previous_gender=='M')
		{
			$sql="DELETE from newjs.SEARCH_MALE where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			//mysql_close();
			$db=connect_737();
			$sql="DELETE from newjs.SEARCH_MALE_FULL1 where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			//mysql_close($db);
			$db=connect_db();

			//if previous_gender was M then current gender must be F.
			$hage = $age+7;
			if($age < 21)
				$lage = 21;
			else
				$lage = $age;
			if($hage > 70)
				$hage = 70;

			$lheight = $height;
			if($height <= 20)
				$hheight = $height + 10;
			else
				$hheight=30;
			if($height > $hheight)
				$hheight = 32;

			$sql_jpartner = "INSERT into newjs.JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE,DPP) values ('$pid','M','$lage','$hage','$lheight','$hheight',now(),'R')";
			mysql_query_decide($sql_jpartner) or die("$sql_jpartner".mysql_error_js());
		}
		if($previous_gender=='F')
		{
			$sql="DELETE from newjs.SEARCH_FEMALE where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			//mysql_close();
			$db=connect_737();
			$sql="DELETE from newjs.SEARCH_FEMALE_FULL1 where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			//mysql_close($db);
			$db=connect_db();

			//if previous_gender was F then current gender must be M.
			if($age < 25)
				$lage = 18;
			else
				$lage = $age - 7;
			$hage=$age;

			$hheight = $height;
			if($height > 10)
				$lheight = $height - 10;
			else
				$lheight = 1;

			$sql_jpartner="INSERT into newjs.JPARTNER(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE,DPP) values ('$pid','F','$lage','$hage','$lheight','$hheight',now(),'R')";
			mysql_query_decide($sql_jpartner) or die("$sql_jpartner".mysql_error_js());
		}
	}

	function delete_record($pid)
	{
		//Deleting contacts from newjs.CONTACTS
		//$sql="SELECT CONTACTID FROM newjs.CONTACTS WHERE SENDER = '$pid'" ;
		//modified by sriram for updation in leftpanel
		$sql="SELECT CONTACTID, TYPE, RECEIVER FROM newjs.CONTACTS WHERE SENDER = '$pid'" ;
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cid=$row['CONTACTID'];
			$sql1="DELETE FROM newjs.CONTACTS WHERE CONTACTID='$cid'";
			mysql_query_decide($sql1) or die(mysql_error_js());
			//added by sriram,lavesh for updation of fields in leftpanel
			if($row['TYPE']!='C')
			{
				if($row['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$row[RECEIVER]'";
				elseif($row['TYPE']=='A')
					 $sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$row[RECEIVER]'";
				elseif($row['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$row[RECEIVER]'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
		}

		//$sql="SELECT CONTACTID FROM newjs.CONTACTS WHERE RECEIVER = '$pid'" ;
		//modified by sriram for updation in leftpanel
		$sql="SELECT CONTACTID, TYPE, RECEIVER FROM newjs.CONTACTS WHERE RECEIVER = '$pid'" ;
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cid=$row['CONTACTID'];
			$sql1="DELETE FROM newjs.CONTACTS WHERE CONTACTID='$cid'";
			mysql_query_decide($sql1) or die(mysql_error_js());
			//added by sriram,lavesh for updation of fields in leftpanel
			if($row['TYPE']!='C')
			{
				if($row['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$row[SENDER]'";
				elseif($row['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$row[SENDER]'";
				elseif($row['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$row[SENDER]'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
		}
		//added by sriram
		$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$pid'";
		mysql_query_decide($sql_del);

		 //Deleting contacts from newjs.DELETED_PROFILE_CONTACTS
		$sql  = "SELECT CONTACTID FROM newjs.DELETED_PROFILE_CONTACTS WHERE SENDER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cid=$row['CONTACTID'];
			$sql1="DELETE FROM newjs.DELETED_PROFILE_CONTACTS WHERE CONTACTID='$cid'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}

		$sql  = "SELECT CONTACTID FROM newjs.DELETED_PROFILE_CONTACTS WHERE RECEIVER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cid=$row['CONTACTID'];
			$sql1="DELETE FROM newjs.DELETED_PROFILE_CONTACTS WHERE CONTACTID='$cid'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}

		//Deleting contacts from newjs.MESSAGE_LOG
		$sql  = "SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$id=$row['ID'];
			$sql1="DELETE FROM newjs.MESSAGE_LOG WHERE ID='$id'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}

		$sql  = "SELECT ID FROM newjs.MESSAGE_LOG WHERE RECEIVER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$id=$row['ID'];
			$sql1="DELETE FROM newjs.MESSAGE_LOG WHERE ID='$id'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}
		
		//Deleting contacts from newjs.DELETED_MESSAGE_LOG
		$sql  = "SELECT ID FROM newjs.DELETED_MESSAGE_LOG WHERE SENDER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$id=$row['ID'];
			$sql1="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID='$id'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}			
		
		$sql  = "SELECT ID FROM newjs.DELETED_MESSAGE_LOG WHERE RECEIVER='$pid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$id=$row['ID'];
			$sql1="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID='$id'";
			mysql_query_decide($sql1) or die(mysql_error_js());
		}
	}
?>
