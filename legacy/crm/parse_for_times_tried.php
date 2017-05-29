<?php

//include("../connect.inc");
include("connect.inc");
$db=connect_db();

if (authenticated($cid))
{
	if ($submit)
	{
		$filename =JsConstants::$docRoot."/crm/csv_files/bulk_csv_crm_data_".$file."_n.txt";

		//$filename="/usr/local/apache/sites/jeevansathi.com/htdocs/crm/csv_files/".$file;
		$fp = fopen($filename,"r");
		if(!$fp)
		{
			die("no file pointer");
		}

		$whole_file=fread($fp,filesize($filename));

		$rows_arr=explode("\n",$whole_file);

		$rows_cnt=count($rows_arr);

		for($i=1;$i<$rows_cnt-1;$i++)
		{
			$cols_arr=explode(",",$rows_arr[$i]);

			$pid=$cols_arr[0];
			$pid=str_replace("\"","",$pid);
			$profile_arr[]=$pid;
			unset($cols_arr);
		}

		unset($rows_arr);

		$profile_cnt=count($profile_arr);

		for($i=0;$i<$profile_cnt;$i++)
		{
			$sql="SELECT COUNT(*) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profile_arr[$i]'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$cnt=$row['cnt'];

			if($cnt==0)
			{
				$final_profile_arr[]=$profile_arr[$i];
			}
		}
		unset($profile_arr);

		if($final_profile_arr)
		{
			$profile_str=implode("','",$final_profile_arr);
			$pid_str=implode(",",$final_profile_arr);

			$sql="UPDATE incentive.MAIN_ADMIN_POOL SET TIMES_TRIED=TIMES_TRIED+1 WHERE PROFILEID IN ('$profile_str')";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			//echo mysql_affected_rows_js();

			$sql = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL  = 'N' WHERE TIMES_TRIED >= 3 AND PROFILEID IN ('$profile_str')";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$sql = "INSERT INTO incentive.PROFILES_NOT_FOLLOWED VALUES('','$pid_str','$name',NOW())";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$msg="List of ProfileIds submitted<br>";
                	$msg .="<a href=\"mainpage.php?cid=$cid\">";
                	$msg .="Go To MainPage </a>";
                	$smarty->assign("MSG",$msg);
                	$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$smarty->assign("name",$name);
        	$smarty->assign("cid",$cid);
		$smarty->display("parse_for_times_tried.htm");
	}
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
