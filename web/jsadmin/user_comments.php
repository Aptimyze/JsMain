<?php
include("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	if($comments)
	{
		$date=date("Y-m-d H:i:s");
		$comments=trim($comments);
		$comments=addslashes(stripslashes($comments));	
		$sql="INSERT INTO jsadmin.OFFLINE_COMMENTS VALUES('$pid','$date','$op','$comments')";
		mysql_query_decide($sql) or die(mysql_error_js());
	}
	$sql_disp="SELECT DATE,OPERATOR,COMMENTS FROM jsadmin.OFFLINE_COMMENTS WHERE PROFILEID='$pid' ORDER BY DATE DESC";
	$res_disp=mysql_query_decide($sql_disp) or die(mysql_error_js());
	while($row= mysql_fetch_array($res_disp))
	{
		$val=addslashes(stripslashes($row['COMMENTS']));
                $val = str_replace("\r\n"," ",trim(htmlentities($val)));
		$data[$i]["DATE"]=$row["DATE"];
		$data[$i]["OP"]=$row["OPERATOR"];
		$data[$i]["COMMENT"]=$val;
		$i++;
	}
	if(is_array($data))
		$smarty->assign("show_table","1");
	$smarty->assign("data",$data);
	$smarty->assign("cid",$cid);
	$smarty->assign("op",$op);
	$smarty->assign("pid",$pid);
	$smarty->display("user_comments.htm");
}
else
{
	echo $msg="Your session has been timed out<br><br>";
}
?>
