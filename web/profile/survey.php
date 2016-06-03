<?php
/*****************************************************************************************************
Filename    : survey.php
Description : Display survey form to user and store survey results. [2326]
Created On  : 5 October 2007
Created By  : Sadaf Alam
******************************************************************************************************/

include("connect.inc");

$db=connect_db();

if($submit)
{
	$ques1=addslashes(stripslashes(trim($ques1)));
	$ques2=addslashes(stripslashes(trim($ques2)));
	$ques3=addslashes(stripslashes(trim($ques3)));
	$ques4=addslashes(stripslashes(trim($ques4)));
	$empty=0;
	if($ques1=='')
	$empty++;
	elseif(strlen($ques1)>1000)
	{
		$error=1;
		$smarty->assign("1long","1");
	}
	if($ques2=='')
	$empty++;
	elseif(strlen($ques2)>1000)
	{
		$error=1;
        	$smarty->assign("2long","1");
	}
	if($ques3=='')
	$empty++;
        elseif(strlen($ques3)>1000)
	{
		$error=1;
        	$smarty->assign("3long","1");
	}
	if($ques4=='')
	$empty++;
	elseif(strlen($ques4)>1000)
	{
        	$error=1;
		$smarty->assign("4long","1");
	}
	if($empty>1)
	{
		$error=1;
		$smarty->assign("empty","1");
	}	
	if(!$error)
	{
		$sql="UPDATE MIS.SURVEY SET QUES1='$ques1',QUES2='$ques2',QUES3='$ques3',QUES4='$ques4' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$smarty->assign("layer","1");
	}

	if($ques1)
	$smarty->assign("ques1",$ques1);
	if($ques2)
	$smarty->assign("ques2",$ques2);
	if($ques3)
	$smarty->assign("ques3",$ques3);
	if($ques4)
	$smarty->assign("ques4",$ques4);
	$smarty->assign("PROFILEID",$profileid);
	$smarty->display("survey.htm");
	
}
else
{
	$smarty->assign("PROFILEID",$profileid);
	$smarty->display("survey.htm");
}
?>
