<?php
include("connect.inc");
//$db=connect_db();

$i=0;
$sql="SELECT ID,PARENT,QUESTION,IS_QUESTION FROM feedback.QADATA ORDER BY PARENT ASC";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$parent=$row['PARENT'];
	if($parent!=$oldparent)
		$i=0;
	$is_q=$row['IS_QUESTION'];
	if($is_q!='Y')
	{
		if(is_array($topicidarr))
		{
			if(!in_array($row['ID'],$topicidarr))
			{
				$topicarr[]=$row['QUESTION'];
				$topicidarr[]=$row['ID'];
			}
		}
		else
		{
			$topicarr[]=$row['QUESTION'];
			$topicidarr[]=$row['ID'];
		}
	}
	$oldparent=$parent;
	$k=array_search($row['PARENT'],$topicidarr);
	$arr[$k][$i]["id"]=$row['ID'];
	$arr[$k][$i]["catid"]=$row['PARENT'];
	$arr[$k][$i]["ques"]=$row['QUESTION'];
//	$arr[$k][$i]["ans"]=$row['ANSWER'];
	$i++;
}
$smarty->assign("topicarr",$topicarr);
$smarty->assign("ques_arr",$arr);
$smarty->assign("cid",$cid);
$smarty->display("faq_rightpanel.htm");
?>
