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
                        $sql_email="select EMAIL from JPROFILE where  activatedKey=1 and PROFILEID='".$data['PROFILEID']."'";
                        $res_email=mysql_query_decide($sql_email);
                        $row_email=mysql_fetch_row($res_email);
                        $email=$row_email[0];
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
		$category=array("Profile Deletion","Contact initiation","Edit Basic information","Login to jeevansathi.com","Retrieve username/password","Search for perfect match","Photo Upload","Membership/Payment Related Queries","Report Abuse","Suggestions","Others");
		$category_value=array("delete","initiate","edit","login","retrieve","search","Photo","Payment","Abuse","Suggestion","Other");
		$smarty->assign("category",$category);
		$smarty->assign("category_value",$category_value);
		//Ends Here.
		$com_category=array("0"=>"Retrieve username/password","15"=>"Profile Deletion","19"=>"Contact initiation","18"=>"Login to jeevansathi.com","60"=>"Search for perfect match","92"=>"Edit Basic information");
		$com_faq=array("0"=>"retrieve","15"=>"delete","19"=>"initiate","18"=>"login","60"=>"search","92"=>"edit");
		$smarty->assign("select_category",$allcategory);
		$smarty->display("profile_edit_basicinfo_spec.htm");

    
?>
