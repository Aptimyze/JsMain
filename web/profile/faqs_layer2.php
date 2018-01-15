<?php
	
        //to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

        include("connect.inc");

        $lang=$_COOKIE["JS_LANG"];

        $db=connect_db();
        $data=authenticated($checksum);
        login_relogin_auth($data);//added for contact details on leftpanel.
	if($data)
	{
		$USERNAME_FIELD=$data['USERNAME'];
		                if($email=="")
                {
                        $sql_email="select EMAIL,COUNTRY_RES,CITY_RES from JPROFILE where  activatedKey=1 and PROFILEID='".$data['PROFILEID']."'";
                        $res_email=mysql_query_decide($sql_email);
                        $row_email=mysql_fetch_row($res_email);
                        $email=$row_email[0];
			$country=$row_email[1];
			$city=$row_email[2];
                }
                if($name=="")
                {
                        $sql_name="select NAME from incentive.NAME_OF_USER where PROFILEID='".$data['PROFILEID']."'";
                        $res_name=mysql_query_decide($sql_name);
                        $row_name=mysql_fetch_row($res_name);
                        $name=$row_name[0];
                        $smarty->assign("name",$name);
                }
	}
		$smarty->assign("USERNAME_FIELD",$USERNAME_FIELD);
        if($dada==1)
        {
                $sql_email="select EMAIL from JPROFILE where  activatedKey=1 and USERNAME='$USERNAME_FIELD'";
                $res_email=mysql_query_decide($sql_email);
                $row_email=mysql_fetch_row($res_email);
                $email=$row_email[0];
        }
		$ret = "";
		$dont_made_other_selected=0;
		$sql_ci = "select VALUE, LABEL from CITY_NEW WHERE COUNTRY_VALUE='$country' AND TYPE!='STATE' order by SORTBY";
		$res_ci = mysql_query_optimizer($sql_ci) or logError("error",$sql_ci);
		$ret .= "<select style=\"width:185px;\" name=\"City_Res\" id=\"City_arr\" onchange=\"show_code();\">";
		while($myrow_ci = mysql_fetch_array($res_ci))
		{
			if($myrow_ci["VALUE"]==$city)
			{
				$ret .= "<option value=\"$myrow_ci[VALUE]\" selected>$myrow_ci[LABEL]</option>\n";
				$dont_made_other_selected=1;
			}
			else
				$ret .= "<option value=\"$myrow_ci[VALUE]\">$myrow_ci[LABEL]</option>\n";
		}
		if(!$dont_made_other_selected && $city!="")
			$ret .= "<option value=\"0\" selected>Others</option>\n";
		else
			$ret .= "<option value=\"0\">Others</option>\n";
		$ret .= "</select>";
		$myprofilechecksum = md5($data['PROFILEID'])."i".($data['PROFILEID']);
		$smarty->assign("pid",$data['PROFILEID']);
		$smarty->assign("myprofilechecksum",$myprofilechecksum);
                $smarty->assign("CITY_ARR",$ret);
		$smarty->assign("COUNTRY_RES",create_dd($country,"Country_Residence"));
		$smarty->assign("email",$email);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("FEEDBACK_ID",$FEEDBACK_ID);
		$smarty->assign("abuse",$abuse);
		$smarty->assign("allcategory",$allcategory);
		$smarty->assign("question",$question);
		$smarty->assign("tracepath",$tracepath);
		$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
		$smarty->assign("questiontext",$questiontext);
	//	$smarty->assign("
		//Added by lavesh
		$category="Edit country living in";
		$category_value="edit";
		$smarty->assign("category",$category);
		$smarty->assign("category_value",$category_value);
		//Ends Here.
		$com_category=array("0"=>"Retrieve username/password","15"=>"Profile Deletion","19"=>"Contact initiation","18"=>"Login to jeevansathi.com","60"=>"Search for perfect match","92"=>"Edit Basic information");
		$com_faq=array("0"=>"retrieve","15"=>"delete","19"=>"initiate","18"=>"login","60"=>"search","92"=>"edit");
		$smarty->assign("select_category",$allcategory);
		$smarty->display("profile_edit_basicinfo_spec2.htm");

    
?>
