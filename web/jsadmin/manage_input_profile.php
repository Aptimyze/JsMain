<?php
include_once("connect.inc");
                                                                                                 
/****************************************************************************************************************************
        FILENAME        :       manage_input_profile.php
        CHANGED By      :       Tanu Gupta
	DATE            :       10' Jan, 07.
        DESCRIPTION     :       The file is used to add or edit a template for a particular source.

****************************************************************************************************************************/
                                                                                                 
if(authenticated($cid))
{
	//$error=0; // to check if no template is selected to get upload
	if($temp_submit)
	{
		if($Source=='S')  // if no source is selected
                {
                        $flag_error=1;
                        $smarty->assign("flag_error_source",1);
                }
                if($flag_error==1)  // if some error in Source
                {
			$smarty->assign("page",1);
                        $smarty->assign("name",$user);
			$smarty->assign("page",1);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("flag",$flag);
                        $smarty->assign("source",create_dd($Source,"Source"));
                        $smarty->assign("Source",$Source);
                        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                        $smarty->display("manage_input_profile.htm");
                }
                else                   
                {
			
			$smarty->assign("page",2);
			$sql="SELECT SourceName FROM MIS.SOURCE WHERE SourceID='$Source'";
			$res=mysql_query_decide($sql);
			$row=mysql_fetch_array($res);
			if($Source=='0')
				$smarty->assign("temp_source","Default Source");
			else
				$smarty->assign("temp_source",$row['SourceName']);
                        $smarty->assign("name",$user);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("Source",$Source);
                        $smarty->assign("flag",$flag);
                        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                        $smarty->display("manage_input_profile.htm");
		}			

	}
	elseif($Submit || $Preview)
	{
			if($temp_topband=='')
				die("Top Band Content for the page is blank.");
			if($temp_headline=='')
				die("Headline for the page is empty.");

			if($Preview)
			{
				$smarty->assign("temp_topband",stripslashes($temp_topband));
				$smarty->assign("temp_headline",stripslashes($temp_headline));
				$smarty->display("../jeevansathi/registration_pg1.htm");
				die;
			}

			$sql="insert into BANNER_TEMPLATE(SOURCEID,DATE,STATUS,TOP_BAND,HEADLINE) values('$Source',now(),'I','$temp_topband','$temp_headline')";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$flag_thanks=1;
			$smarty->assign("flag_thanks",$flag_thanks);
		
		$smarty->assign("Source",$Source);
		$smarty->assign("temp_source",$temp_source);
		$smarty->assign("name",$user);
		//$smarty->assign("page",2);
		$smarty->assign("flag",$flag);
		$smarty->assign("cid",$cid);
		$smarty->assign("source",create_dd($Source,"Source"));
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->display("manage_input_profile.htm");
	}
	else
	{
		$smarty->assign("name",$user);
		$smarty->assign("flag",$flag);
                $smarty->assign("cid",$cid);
                $smarty->assign("page",1);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("source",create_dd($Source,"Source"));
		$smarty->assign("Source",$Source);
		$smarty->display("manage_input_profile.htm");
	}

}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
                                                                                                 
?>

