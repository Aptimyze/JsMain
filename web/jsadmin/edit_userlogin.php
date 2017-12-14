<?php

/*************************************************************************************************************************
*    FILENAME        : edit_userlogin.php 
*    DESCRIPTION     : Edits the user information 
**************************************************************************************************************************/  
include ("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$empty=1;

if (authenticated($cid))
{
	$name=getname($cid);
	if ($submit)
	{
                $jsadminPswrdsObj = new jsadmin_PSWRDS();
                $special_priv = array('ExcFld', 'SupFld', 'MgrFld');
                $special_action = 0;

                if(!$MOD_PRIV)
                {
                        $empty=0;
                        $smarty->assign('check_priv','1');
                }
                else
                {
                        for($i=0; $i<count($special_priv); $i++)
                        {
                                if(in_array($special_priv[$i], $MOD_PRIV))
                                {
                                        $special_action = 1;
                                        break;
                                }
                        }
                }
	
                if (trim($MOD_FIRSTNAME)=='' || strlen(trim($MOD_FIRSTNAME))>13 || !preg_match('/^[A-Za-z ]+$/i',trim($MOD_FIRSTNAME)))
                {
                        $empty=0;
                        if(trim($MOD_FIRSTNAME)=='' && $special_action==1)
                            $smarty->assign('check_firstname_blank','1');
                        else if(trim($MOD_FIRSTNAME)!='' && strlen(trim($MOD_FIRSTNAME))>13)
                            $smarty->assign('check_firstname_length','1');
                        else if(trim($MOD_FIRSTNAME)!='' && !preg_match('/^[A-Za-z ]+$/i',trim($MOD_FIRSTNAME)))
                            $smarty->assign('check_firstname_alpha','1');
			else $empty=1;
                }

                if (trim($MOD_LASTNAME)!='' && !preg_match('/^[A-Za-z ]+$/i',trim($MOD_LASTNAME)))
                {
                        $empty=0;
                        $smarty->assign('check_lastname_alpha','1');
                }

	        if($_FILES['UPLOAD_PHOTO']['name']!='')
        	{
	            $empty=0;
        	    $size = getimagesize($_FILES['UPLOAD_PHOTO']['tmp_name']);

	            if($_FILES['UPLOAD_PHOTO']['size']>10000000)
        	        $smarty->assign('check_upload_photo_size','1');

	            else if($_FILES['UPLOAD_PHOTO']['type']!="image/jpeg")
	                $smarty->assign('check_upload_photo_type','1');

        	    else if($size[0]!=172 || $size[1]!=156)
	                $smarty->assign('check_upload_photo_dimensions','1');

        	    else
		    {
	                $empty=1;
			$date = new DateTime();
			$timeStamp = $date->getTimestamp();
        	        $destination = sfConfig::get("sf_upload_dir")."/FieldSales/ExecPic/".$USERNAME."_".$timeStamp.".jpg";
                	move_uploaded_file($_FILES['UPLOAD_PHOTO']['tmp_name'], $destination);  // copying photo to application server

	                $PHOTO_URL = IMAGE_SERVER_ENUM::$appPicUrl."/uploads/FieldSales/ExecPic/$USERNAME"."_"."$timeStamp.jpg";

	                $smarty->assign('check_upload_photo_success','1');
		    }
        	}
	        
		else if($special_action==1 && $PHOTO_UPLOADED==0)
        	{
	        	$empty=0;
        	   	$smarty->assign('check_upload_photo_success','0');
        	}
	        
		else if($PHOTO_UPLOADED==1)
		{
	                $PHOTO_URL = $jsadminPswrdsObj->getPhotoUrl($USERNAME);
        	        $smarty->assign('check_upload_photo_success','1');
		}

		if (trim($MOD_EMAIL)=="" || checkemail($MOD_EMAIL))
		{
			$empty=0;
			$smarty->assign('check_email',1);
		}
                if (trim($MOD_PHONE)=="" && $special_action==1)
                {
                        $empty=0;
                        $smarty->assign('check_phone',1);
                }
                else if(trim($MOD_PHONE)!="" && $special_action==1)
                {
                        if(strlen($MOD_PHONE)!=10 || !is_numeric($MOD_PHONE))
                        {
                                $empty = 0;
                                $smarty->assign('check_phone_value',1);
                        }
                        else if(!in_array($MOD_PHONE[0], array('6','7','8','9')))
                        {
                                $empty = 0;
                                $smarty->assign('check_phone_initiate',1);
                        }
                }
                
		if (!$MOD_CENTER)
                {
                        $empty=0;
                        $smarty->assign('check_center',1);
                }                                                                                                                            
		if (trim($MOD_EMP_ID)=="")
		{
			$empty=0;
			$smarty->assign('check_emp_id_empty',1);
		}
		else
                {
			if(is_numeric($MOD_EMP_ID))
			{
                        	$sql1="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID='$MOD_EMP_ID'";
                        	$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
                        	$row1=mysql_fetch_array($res1);
				if($row1)
				{
                        		if($row1['USERNAME']!=$USERNAME && $MOD_EMP_ID!='9999')
                        		{	
                        	       		$empty=0;
                        	        	$smarty->assign("emp_exists","1");
                        		}
				}
			}
                        else{
                                $empty=0;
                                $smarty->assign('check_empid_numeric','1');
                        }
                }

		if ($empty==0)
		{
			$options=create_dd($MOD_PRIV,"privilege");
			$center=create_dd(strtoupper($MOD_CENTER),"branch");

			// New sublocation changes
                        if($MOD_CENTER=='AA|X')
                                $center_val = explode("|X",$MOD_CENTER);
                        else
                                $center_val = explode("|X|",$MOD_CENTER);
                        $center =pop_location_with_sublocation($center_val[0]);
                        $sublocation =create_dd($MOD_SUBLOCATION,"sub_location","","",$center_val[0]);

//			create drop down for head field
                        $sql1="SELECT USERNAME,EMP_ID FROM jsadmin.PSWRDS";
                        $result1 = mysql_query_decide($sql1) or die(mysql_error_js());
                        $head="";
                        while($myrow = mysql_fetch_array($result1))
                        {
                                if($MOD_HEAD == $myrow['EMP_ID'])
                                {
                                        $head .= "<option value=\"$myrow[EMP_ID]\" selected>$myrow[USERNAME]</option>\n";
                                }
                                else
                                {
                                        $head .= "<option value=\"$myrow[EMP_ID]\">$myrow[USERNAME]</option>\n";
                                }
                        }
//			drop down created

			$smarty->assign('USERNAME',$USERNAME);
			$smarty->assign('FIRSTNAME',$MOD_FIRSTNAME);
			$smarty->assign('LASTNAME',$MOD_LASTNAME);
			$smarty->assign('EMAIL',$MOD_EMAIL);
			$smarty->assign('ACTIVE',$MOD_ACTIVE);
                        $smarty->assign("options",$options);
			$smarty->assign("center",$center);
			$smarty->assign("sublocation",$sublocation);					
			$smarty->assign('CENTER',$MOD_CENTER);		
			$smarty->assign('SIGN',$MOD_SIGN);		
			$smarty->assign('PHONE',$MOD_PHONE);		
			$smarty->assign('RESID',$RESID);
			$smarty->assign('cid',$cid);
			$smarty->assign('EMP_ID',$MOD_EMP_ID);
			$smarty->assign("head",$head);

                        if($_FILES['UPLOAD_PHOTO']['name']!='' || $PHOTO_UPLOADED==1)
                        {
                                $url = PictureFunctions::getCloudOrApplicationCompleteUrl($PHOTO_URL);
                                if($url != "http://ser7.jeevansathi.com")
                                        $smarty->assign('PHOTO_URL',$url);
                        }

			$smarty->display('edit_userlogin.htm');
		}
		else
		{
			if(is_array($MOD_PRIV))
			{
				$privstr=implode("+",$MOD_PRIV);
			}
			if(!$MOD_ACTIVE)
				$MOD_ACTIVE='N';
                        //New sublocation change
                       if($MOD_CENTER=='AA|X')
                                $center_val = explode("|X",$MOD_CENTER);
                        else
                                $center_val = explode("|X|",$MOD_CENTER);
		        $MOD_CENTER		=getLabel_value("$center_val[0]",'VALUE','NAME','LOCATION','incentive');
                        $MOD_SUBLOCATION 	=getLabel_value("$MOD_SUBLOCATION",'VALUE','LABEL','SUB_LOCATION','incentive');

			$sql= "UPDATE jsadmin.PSWRDS SET ";
			if($MOD_PASSWD)
			{
				$MOD_PASSWD = md5($MOD_PASSWD);
				$sql.="PASSWORD ='$MOD_PASSWD', ";
			}
			if (trim($MOD_EMP_ID) == '')
				$MOD_EMP_ID = '9999';

	                $imageServerLogObj = new ImageServerLog();

		        if($PHOTO_URL == "http://ser7.jeevansathi.com")
                		$PHOTO_URL = '';

            		$jsadminPswrdsObj->startTransaction();

		        $RESID = $jsadminPswrdsObj->getId($USERNAME);

			$sql.="FIRST_NAME = '$MOD_FIRSTNAME', LAST_NAME = '$MOD_LASTNAME', EMAIL ='$MOD_EMAIL',PRIVILAGE ='$privstr', CENTER ='$MOD_CENTER', ACTIVE='$MOD_ACTIVE', SIGNATURE='".addslashes(stripslashes($MOD_SIGN))."', PHONE='$MOD_PHONE', MOD_DT=NOW(), ENTRYBY='$name', EMP_ID='$MOD_EMP_ID', HEAD_ID='$MOD_HEAD',SUB_CENTER='$MOD_SUBLOCATION'";
			
			if ($PHOTO_URL)
				$sql.=", PHOTO_URL='$PHOTO_URL' WHERE RESID='$RESID'" ;
			else
				$sql.=" WHERE RESID='$RESID'";
				$sql_log = "SELECT * FROM jsadmin.PSWRDS WHERE RESID='$RESID'";
				$existing_data = mysql_fetch_assoc(mysql_query($sql_log));
				$values = "NULL,'".implode("','", array_values($existing_data))."'";
				$sql_log = "INSERT INTO jsadmin.PSWRDS_LOG VALUES ($values)";
				mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

		        if($_FILES['UPLOAD_PHOTO']['name']!='' || $PHOTO_UPLOADED==1)
				$imageServerLogObj->insertBulk("FIELD_SALES",$RESID,"PHOTO_URL","N");
           		 
			$jsadminPswrdsObj->commitTransaction();
										       
			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showuser.php?cid=$cid\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");

		}

	}
	else
	{
		if($act)
		{
			$sql="update jsadmin.PSWRDS set ACTIVE='$act' , MOD_DT=NOW() , ENTRYBY='$name'";
			if($act=="Y")
				$sql.=", LAST_LOGIN_DT=NOW() ";
			$sql.=" where RESID='$RESID'";
			$sql_log = "SELECT * FROM jsadmin.PSWRDS WHERE RESID='$RESID'";
			$existing_data = mysql_fetch_assoc(mysql_query($sql_log));
			$values = "NULL,'".implode("','", array_values($existing_data))."'";
			$sql_log = "INSERT INTO jsadmin.PSWRDS_LOG VALUES ($values)";
			mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());
			mysql_query_decide($sql) or die(mysql_error_js());

			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showuser.php?cid=$cid\">";
			$msg .="Continue </a>";                                
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$smarty->assign("PHOTO_URL",'');
			$sql = "SELECT USERNAME,FIRST_NAME,LAST_NAME,EMAIL,CENTER,SUB_CENTER,PRIVILAGE,ACTIVE,SIGNATURE,PHONE,EMP_ID,HEAD_ID,PHOTO_URL FROM jsadmin.PSWRDS WHERE RESID='$RESID'" ;
			$result = mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($result);
			$privilage=$row['PRIVILAGE'];
			$priv=explode("+",$privilage);		
			$options=create_dd($priv,"privilege");
			$branch=strtoupper($row["CENTER"]);
			$sublocation =strtoupper($row["SUB_CENTER"]);
			//$center=create_dd($branch,"branch");

			//New sublocation changes
			$branch_val =getLabel_value($branch,'NAME','VALUE','LOCATION','incentive');
			$sublocation_val =getLabel_value($sublocation,'LABEL','VALUE','SUB_LOCATION','incentive');
			$center =pop_location_with_sublocation($branch_val);
			$sublocation =create_dd($sublocation_val,"sub_location","","",$branch_val);	

			$smarty->assign('USERNAME',$row["USERNAME"]);
			$smarty->assign('FIRSTNAME',$row["FIRST_NAME"]);
			$smarty->assign('LASTNAME',$row["LAST_NAME"]);
			$smarty->assign('EMAIL',$row["EMAIL"]);
			$smarty->assign('ACTIVE',$row["ACTIVE"]);
			$smarty->assign('SIGN',$row["SIGNATURE"]);
			$smarty->assign('PHONE',$row["PHONE"]);
			$smarty->assign('EMP_ID',$row["EMP_ID"]);

			
			if($row["PHOTO_URL"])
			{
				$url = PictureFunctions::getCloudOrApplicationCompleteUrl($row["PHOTO_URL"]);
				if($url != "http://ser7.jeevansathi.com")
					$smarty->assign("PHOTO_URL",$url);
				$smarty->assign('check_upload_photo_success','1');
			}
			
			$sql1="SELECT USERNAME,EMP_ID FROM jsadmin.PSWRDS";
			$result1 = mysql_query_decide($sql1) or die(mysql_error_js());
			$head="";
			while($myrow = mysql_fetch_array($result1))
			{
				if($row['HEAD_ID'] == $myrow['EMP_ID'])
				{
					$head .= "<option value=\"$myrow[EMP_ID]\" selected>$myrow[USERNAME]</option>\n";
				}
				else
				{
					$head .= "<option value=\"$myrow[EMP_ID]\">$myrow[USERNAME]</option>\n";
				}
			}
			$smarty->assign("head",$head);
			
			$smarty->assign("options",$options); 
			$smarty->assign("center",$center);
			$smarty->assign("sublocation",$sublocation);	
			$smarty->assign("cid",$cid);	
			$smarty->assign("RESID",$RESID);
		
			$smarty->display('edit_userlogin.htm');
		}   
	}
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
