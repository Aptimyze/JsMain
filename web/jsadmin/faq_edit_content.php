<?php
include("connect.inc");

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$error=0;
		if($val=="add")
		{
			if($ques=='')
			{
				$error++;
				$smarty->assign("QUES_ERR","Y");
			}
			if($answer=='')
			{
				$error++;
				$smarty->assign("ANS_ERR","Y");
			}

			if($error)
			{
				$i=0;
				$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE IS_QUESTION='N'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$idarr[$i]["id"]=$row['ID'];
					$idarr[$i]["name"]=$row['QUESTION'];
					$i++;
				}

				$smarty->assign("idarr",$idarr);
				$smarty->assign("ques",$ques);
				$smarty->assign("answer",$answer);
				$smarty->assign("is_q",$is_q);
				$smarty->assign("publish",$publish);
				$smarty->assign("parent",$parent);
				$smarty->assign("val",$val);
				$smarty->display("faq_edit_content.htm");
			}
			else
			{
				if(!$publish)
					$publish='N';
				if(!$is_q)
					$is_q='N';

				$sql="INSERT INTO feedback.QADATA(PARENT,QUESTION,ANSWER,IS_QUESTION,PUBLISH) VALUES('$parent','".addslashes(stripslashes($ques))."','".addslashes(stripslashes($answer))."','$is_q','$publish')";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$msg="Record Inserted. <br>";
				$msg .="<a href=\"faq_edit_main.php?cid=$cid\">";
				$msg .="Continue>> </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("faq_continue.htm");
			}
		}
		elseif($val=="edit")
		{
			if($ques=='')
                        {
                                $error++;
                                $smarty->assign("QUES_ERR","Y");
                        }
                        if($answer=='')
                        {
                                $error++;
                                $smarty->assign("ANS_ERR","Y");
                        }

                        if($error)
                        {
				$i=0;
				$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE IS_QUESTION='N'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$idarr[$i]["id"]=$row['ID'];
					$idarr[$i]["name"]=$row['QUESTION'];
					$i++;
				}

				$smarty->assign("idarr",$idarr);
				$smarty->assign("id",$id);
                                $smarty->assign("ques",$ques);
                                $smarty->assign("answer",$answer);
                                $smarty->assign("is_q",$is_q);
                                $smarty->assign("publish",$publish);
                                $smarty->assign("parent",$parent);
                                $smarty->assign("val",$val);
                                $smarty->display("faq_edit_content.htm");
                        }
                        else
                        {
                                if(!$publish)
                                        $publish='N';
                                if(!$is_q)
                                        $is_q='N';

				$sql="UPDATE feedback.QADATA SET PARENT='$parent',QUESTION='".addslashes(stripslashes($ques))."',ANSWER='".addslashes(stripslashes($answer))."',IS_QUESTION='$is_q',PUBLISH='$publish' WHERE ID='$id'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$msg="Record Updated. <br>";
				$msg .="<a href=\"faq_edit_main.php?cid=$cid\">";
				$msg .="Continue>> </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("faq_continue.htm");
			}
		}
	}
	else
	{
		if($val=="add")
		{
			$i=0;
                        $sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE IS_QUESTION='N'";
                        $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
                                $idarr[$i]["id"]=$row['ID'];
				$idarr[$i]["name"]=$row['QUESTION'];
                                $i++;
                        }

                        $smarty->assign("idarr",$idarr);
			$smarty->assign("val",$val);
			$smarty->display("faq_edit_content.htm");
		}
		elseif($val=="edit")
		{
			$sql="SELECT * FROM feedback.QADATA WHERE ID='$id'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$smarty->assign("parent",$row['PARENT']);
				$smarty->assign("ques",$row['QUESTION']);
				$smarty->assign("answer",$row['ANSWER']);
				$smarty->assign("is_q",$row['IS_QUESTION']);
				$smarty->assign("publish",$row['PUBLISH']);
			}

			$i=0;
			$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE IS_QUESTION='N'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$idarr[$i]["id"]=$row['ID'];
				$idarr[$i]["name"]=$row['QUESTION'];
				$i++;
			}

			$smarty->assign("idarr",$idarr);
			$smarty->assign("id",$id);
			$smarty->assign("val",$val);
			$smarty->display("faq_edit_content.htm");
		}
		elseif($val=="delete")
		{
			$sql="UPDATE feedback.QADATA SET PUBLISH='N' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			echo "<html>";
			echo "<head>";
			echo "<script>";
			echo "function js(nid)";
			echo "{
				var varname = 'dl_'+nid;
				var x=parent.main.document.getElementById('testTable').rows;
				var div_ref = parent.main.document.getElementById(varname);
			        div_ref.innerHTML = document.form_empty.ANSWER.value;
//	alert(div_ref.innerHTML);
			}";
			echo "</script>";
			echo "</head>";
			echo "<body onload=js(".$nid.");>";
			echo "<form name=form_empty>";
			echo "<input type=hidden name=ANSWER value=\"N\"";
			echo "<input type=hidden name=id value=\"$jsid\"";
			echo "</form>";
			echo "</body>";
			echo "</html>";
		}
	}
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
