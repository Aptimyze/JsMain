<?
include("../connect.inc");
$db=connect_db();

if($Submitted)
{
	$option="";
	$sql="select USERNAME,PROFILEID from newjs.JPROFILE where EMAIL='$email'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	 {
		$username=$row[0];
		$profileid=$row["PROFILEID"];
		if($check1!="")
			$option=$check1;
		else
		{
			if($check2!="")
					$option=$check2;
	 		if($check3!="")
                        	if($option!="")
                                	$option.=", ".$check3;
	                        else
        	                        $option.=$check3;
			if($check4!="")
				if($option!="")
                                        $option.=", ".$check4;
				else
					$option.=$check4;
		}
		$sql="replace into FASHION(EMAILID,PROFILEID,USERNAME,NAME,OPTION_SELECT,ENTRY_DT) values('$email','$profileid','$username','$Name','$option',now())";
		mysql_query_decide($sql) or die(mysql_error_js());
	}
	$smarty->display("fashion/grooming_thankyou.html");

}
else
{
	$smarty->assign("email",$email);
	$smarty->display("fashion/grooming_landing.html");	
}
	
