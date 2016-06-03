<?
/**************************************************************************************************************************
FILE 		: onhold.php
DESCRIPTION	: This script is used to put the profile on hold for new/edit screening module.
CREATED BY 	: Sriram Viswanathan
DATE		: May 29th 2007. 
**************************************************************************************************************************/
include("connect.inc");
if(1)//authenticated($cid))
{
	if($submit)
	{
		if($type=="O")
			$table_name = "newjs.OBSCENE_WORDS";
		else
			$table_name = "jsadmin.MISUSED_WORDS";

		$word = strtolower(addslashes(stripslashes($word)));

		$sql = "SELECT COUNT(*) AS COUNT FROM $table_name WHERE WORD = '$word'";
		$res = mysql_query_decide($sql) or die($sql.mysql_error);
		$row = mysql_fetch_array($res);
		if($row['COUNT'] > 0)
			$smarty->assign("ALREADY_EXISTS",1);
		else
		{
			$sql = "INSERT INTO $table_name(WORD) VALUES('$word')";
			mysql_query_decide($sql) or die($sql.mysql_error_js());
		}

		$smarty->assign("cid",$cid);
		$smarty->assign("SUBMIT",1);
		$smarty->display("insert_obscene_words.htm");
	}
	elseif($show_data)
	{
		if($type=="O")
			$table_name = "newjs.OBSCENE_WORDS";
		else
			$table_name = "jsadmin.MISUSED_WORDS";

		$sql = "SELECT * FROM $table_name";
		$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			$word_arr[$i]["ID"] = $i + 1;
			$word_arr[$i]["WORD"] = $row['WORD'];
			$i++;
		}
		$smarty->assign("SHOW_DATA",1);
		$smarty->assign("word_arr",$word_arr);
		$smarty->display("insert_obscene_words.htm");
	}
	else
	{
		$smarty->assign("cid",$cid);
		$smarty->display("insert_obscene_words.htm");
	}
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
