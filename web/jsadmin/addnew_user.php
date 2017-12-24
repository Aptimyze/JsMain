<?php

include ("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$empty=1;

if(authenticated($cid))
{
    $name=getname($cid);
    if($submit)
	{
            	$jsadminPswrdsObj = new jsadmin_PSWRDS();
		$special_priv = array('ExcFld', 'SupFld', 'MgrFld');
		$special_action = 0;
		
                if(!$PRIVILAGE && (!$SAME_AS || $SAME_AS == $HEAD))
                {
                        $empty=0;
                        $smarty->assign('check_priv','1');
                }
                else
                {
                        if($SAME_AS)
                        {
                                $sql_priv = "SELECT PRIVILAGE FROM jsadmin.PSWRDS WHERE EMP_ID='$SAME_AS'" ;
                                $res_priv = mysql_query_decide($sql_priv) or die("Not Able to Fetch SAME_AS ".mysql_error_js());
                                $row_priv = mysql_fetch_array($res_priv);
                                $PRIVILAGE = $row_priv['PRIVILAGE'];
                        }
                        for($i=0; $i<count($special_priv); $i++)
                        {
                                if(in_array($special_priv[$i], $PRIVILAGE))
				{
                                        $special_action = 1;
					break;
				}
                        }
                }

		if (trim($USERNAME)=="")
		{	
			$empty=0;
       			$smarty->assign('check_name',1);
		}
		if (trim($PASSWORD)=='' || strlen(trim($PASSWORD))<5 || ($USERNAME==$PASSWORD))
                {
                        $empty=0;
                        $smarty->assign('check_passwd','1');
                }

		if (trim($FIRSTNAME)=='' || strlen(trim($FIRSTNAME))>13 || !preg_match('/^[A-Za-z ]+$/i',trim($FIRSTNAME)))
                {
                        $empty=0;
                        if(trim($FIRSTNAME)=='' && $special_action==1)
                            $smarty->assign('check_firstname_blank','1');
            		else if(trim($FIRSTNAME)!='' && strlen(trim($FIRSTNAME))>13)
                            $smarty->assign('check_firstname_length','1');
                        else if(trim($FIRSTNAME)!='' && !preg_match('/^[A-Za-z ]+$/i',trim($FIRSTNAME)))
                            $smarty->assign('check_firstname_alpha','1');
			else $empty=1;
                }

		if (trim($LASTNAME)!='' && !preg_match('/^[A-Za-z ]+$/i',trim($LASTNAME)))
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
	
		if (trim($EMAIL)=="" || checkemail($EMAIL))
                {
                        $empty=0;
                        $smarty->assign('check_email',1);
                }
		if (!$CENTER)
                {
			$empty=0;
                        $smarty->assign('check_center','1');
                }
		if (trim($PHONE)=="" && $special_action==1)
		{	
			$empty=0;
       			$smarty->assign('check_phone',1);
		}
		else if(trim($PHONE)!="" && $special_action==1)
		{
			if(strlen($PHONE)!=10 || !is_numeric($PHONE))
			{
				$empty = 0;
				$smarty->assign('check_phone_value',1);
			}
			else if(!in_array($PHONE[0], array('6','7','8','9')))
			{
				$empty = 0; 
				$smarty->assign('check_phone_initiate',1);
			}
		}

		if(trim($EMP_ID)=="")
		{
			$empty=0;
			$smarty->assign('check_emp_id_empty','1');
		}
		else
		{
			if(is_numeric($EMP_ID))
			{
				$sql1="SELECT COUNT(*) as cnt FROM jsadmin.PSWRDS WHERE EMP_ID='$EMP_ID'";
				$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
	                	$row1=mysql_fetch_array($res1);
        	        	if($row1['cnt']>0 && $EMP_ID!='9999')
                		{
                        		$empty=0;
                        		$smarty->assign("emp_exists","1");
                		}
			}
			else{
				$empty=0;
				$smarty->assign('check_empid_numeric','1');
			}
			
		}

		$sql="SELECT COUNT(*) as cnt FROM jsadmin.PSWRDS WHERE USERNAME='$USERNAME'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		if($row['cnt']>0)
		{
			$empty=0;
			$smarty->assign("user_exists","1");
		}

		if ($empty==0)
		{
			$smarty->assign('USERNAME',$USERNAME);
	       		$smarty->assign('PASSWORD',$PASSWORD);
       			$smarty->assign('FIRSTNAME',$FIRSTNAME);
       			$smarty->assign('LASTNAME',$LASTNAME);
		        $smarty->assign('EMAIL',$EMAIL);

                        $options=create_dd($PRIVILAGE,"privilege");
			//$center=create_dd($CENTER,"branch");

			// New sublocation changes
			if($CENTER=='AA|X')
                        	$center_val = explode("|X",$CENTER);
                        else
                                $center_val = explode("|X|",$CENTER);
			$center =pop_location_with_sublocation($center_val[0]);
			$sublocation =create_dd($SUBLOCATION,"sub_location","","",$center_val[0]);	
			
			//create drop down for head field
                        $sql1="SELECT USERNAME,EMP_ID FROM jsadmin.PSWRDS";
                        $result1 = mysql_query_decide($sql1) or die(mysql_error_js());
                        $head="";
                        while($myrow = mysql_fetch_array($result1))
                        {
                                if($HEAD == $myrow['EMP_ID'])
                                {
                                        $head .= "<option value=\"$myrow[EMP_ID]\" selected>$myrow[USERNAME]</option>\n";
                                }
                                else
                                {
                                        $head .= "<option value=\"$myrow[EMP_ID]\">$myrow[USERNAME]</option>\n";
                                }
                        }
//                      drop down created

                        $smarty->assign('options',$options);
			$smarty->assign('center',$center);
			$smarty->assign('sublocation',$sublocation);
			$smarty->assign('SIGN',stripslashes($SIGN));
			$smarty->assign('PHONE',$PHONE);
         	  	$smarty->assign('ACTIVE',$ACTIVE);
			$smarty->assign('EMP_ID',$EMP_ID);
			$smarty->assign('head',$head);
	                $smarty->assign('cid',$cid);

			if($_FILES['UPLOAD_PHOTO']['name']!='' || $PHOTO_UPLOADED==1)
			{
				$url = PictureFunctions::getCloudOrApplicationCompleteUrl($PHOTO_URL);
				if($url != "http://ser7.jeevansathi.com" && $url != "https://ser7.jeevansathi.com")
		                	$smarty->assign('PHOTO_URL',$url);
			}

                        $smarty->display('addnew_user.htm');
		}
                else
		{     
			if (!$ACTIVE)
                	{
                        	$ACTIVE='N';
                	}

			if($SAME_AS)
                        {
                                $sql_priv = "SELECT PRIVILAGE FROM jsadmin.PSWRDS WHERE EMP_ID='$SAME_AS'" ;
                                $res_priv = mysql_query_decide($sql_priv) or die("Not Able to Fetch SAME_AS ".mysql_error_js());
                                $row_priv = mysql_fetch_array($res_priv);
                                $PRIVSTR = $row_priv['PRIVILAGE'];
                        }
                        elseif(is_array($PRIVILAGE))
			{
				$PRIVSTR = implode("+",$PRIVILAGE);
			}
		
			//New sublocation change
                       if($CENTER=='AA|X')
                                $center_val = explode("|X",$CENTER);
                        else
                                $center_val = explode("|X|",$CENTER);
			$CENTER			=getLabel_value("$center_val[0]",'VALUE','NAME','LOCATION','incentive');
			$SUBLOCATION	 	=getLabel_value("$SUBLOCATION",'VALUE','LABEL','SUB_LOCATION','incentive');		
			
			//$sign=addslashes(stripslashes($SIGN));
			if (!trim($EMP_ID))
				$EMP_ID = '9999';
			$PASSWORD = md5($PASSWORD);

               
	    if($PHOTO_URL == "http://ser7.jeevansathi.com" || $PHOTO_URL == "https://ser7.jeevansathi.com")
		$PHOTO_URL = '';					

            $imageServerLogObj = new ImageServerLog;

            $jsadminPswrdsObj->startTransaction();
	    
	    $jsadminPswrdsObj->insertProfile($USERNAME,$PASSWORD,$EMAIL,$PRIVSTR,$CENTER,$ACTIVE,$name,$SIGN,$PHONE,$EMP_ID,$HEAD,$SUBLOCATION,$FIRSTNAME,$LASTNAME,$PHOTO_URL);
        
	    $RESID = $jsadminPswrdsObj->getId($USERNAME);
	 
	    if( ($_FILES['UPLOAD_PHOTO']['name']!='' || $PHOTO_UPLOADED==1 ) && ($PHOTO_URL != '') )
		    $imageServerLogObj->insertBulk("FIELD_SALES",$RESID,"PHOTO_URL","N");
         
	    $jsadminPswrdsObj->commitTransaction();

            $msg= " Record Inserted<br>  ";
            $msg .="<a href=\"showuser.php?cid=$cid\">";
            $msg .="Continue </a>";
            $smarty->assign("MSG",$msg);
            $smarty->display("jsadmin_msg.tpl");
	  }
	}
	else
        {
		$sql1="SELECT USERNAME,EMP_ID FROM jsadmin.PSWRDS";
		$result1 = mysql_query_decide($sql1) or die(mysql_error_js());
		$head="";
		while($myrow = mysql_fetch_array($result1))
		{
		$head .= "<option value=\"$myrow[EMP_ID]\">$myrow[USERNAME]</option>\n";
		}
		$smarty->assign("head",$head);

		$sublocation =create_dd("","sub_location","","","");
		$priv_val=create_dd("","privilege");
		//$center=create_dd("","branch");
		$center=pop_location_with_sublocation();
		
		$smarty->assign('options',$priv_val);
		$smarty->assign('center',$center);
		$smarty->assign("sublocation",$sublocation);
		$smarty->assign('cid',$cid);
		$smarty->display("addnew_user.htm");				  
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
