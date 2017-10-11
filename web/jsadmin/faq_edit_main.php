<?php
include("connect.inc");

if(authenticated($cid))
{
	$i=0;
	$sql="SELECT ID,PARENT,QUESTION,IS_QUESTION,PUBLISH FROM feedback.QADATA";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$arr[$i]["id"]=$row['ID'];
		$arr[$i]["parent"]=$row['PARENT'];
		$arr[$i]["ques"]=$row['QUESTION'];
		$arr[$i]["is_q"]=$row['IS_QUESTION'];
		$arr[$i]["publish"]=$row['PUBLISH'];
		$lab=$i%2;
		if($lab==0)
			$arr[$i]["class"]="mainTableAlt";
		else
			$arr[$i]["class"]="mainTable";
		$i++;
	}

	$smarty->assign("arr",$arr);
	$smarty->assign("cid",$cid);
	$smarty->display("faq_edit_main.htm");
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("faq_continue.htm");
}
?>
