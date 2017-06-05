<?php
/***************************************************************************************************************************
FILE NAME		: matri_add_comment.php
DESCRIPTION		: This file is used to add/view comments for a particular profile.
DATE			: July 11th 2007.
CREATED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
if(authenticated($checksum))
{
	//if submit button is clicked.
	if($submit)
	{
		if(trim($comment) == "")
			$smarty->assign("MSG","Comment field should not be blank.");
		else
		{
			$sql_ins = "INSERT INTO billing.MATRI_COMMENTS(PROFILEID,COMMENT,ENTRYBY,ENTRY_DT) VALUES('$profileid','".addslashes(stripslashes($comment))."','".getuser($checksum)."',now())";
			mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());

			$smarty->assign("MSG","Comment successfully added.");
		}
		$smarty->assign("COMMENTED",1);
	}
	else
	{
		//fetching the comments.
		$sql = "SELECT * FROM billing.MATRI_COMMENTS WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
		$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			$details[$i]["SNO"] = $i+1;
			$details[$i]["COMMENT"] = $row["COMMENT"];
			$details[$i]["COMMENT_BY"] = $row["ENTRYBY"];
			$details[$i]["ENTRY_DT"] = $row["ENTRY_DT"];
			$i++;
		}
		$smarty->assign("details",$details);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("username",$username);
	}
	$smarty->assign("checksum",$checksum);
	$smarty->assign("extra_params","profileid=$profileid&username=$username");
	$smarty->assign("scriptname","matri_add_comment.php");
	$smarty->assign("MATRI_MESSAGE",$smarty->fetch("matri_message.htm"));
        $smarty->display("matri_add_comment.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
