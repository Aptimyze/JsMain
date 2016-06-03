<?php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
//$db=connect_db();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	$admin_name=getname($cid);

	$i=0;
	$error=0;

	if($CMDForward && ($forwardto=='' || checkemail($forwardto)==1))
	{
		$error++;
	}

	if($tempidstr)
	{
		if($idarr)
		{
			$idstr="'".implode("','",$idarr)."'";
		}
		else
		{
			$error++;
			$idarr=explode(",",$tempidstr);
			$idstr="'".implode("','",$idarr)."'";
			$smarty->assign("ER_IDARR","Y");
		}
	}
	else
	{
		if($idarr)
		{
			$idstr="'".implode("','",$idarr)."'";
			$tempidstr=implode(",",$idarr);
		}
		else
		{
			$error++;
			$smarty->assign("ER_IDARR","Y");
		}
	}

	if($error)
	{
		// assign variables to template 

		if($faqfetch)
		{
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/jsadmin/faq_admin_fetch.php?cid=$cid&category=$category&error=1\"></body></html>";
/*			$i=0;

			$sql="SELECT ID,COMMENT,ABUSE,ENTRY_DT,CATEGORY FROM feedback.MAIN_DATA";
			if($category)
			{
				$sql.=" WHERE CATEGORY LIKE '$category.%'";
			}
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$entry_dt=substr($row['ENTRY_DT'],0,10);
				list($yy,$mm,$dd)=explode("-",$entry_dt);
				$arr[$i]["ID"]=$row['ID'];
//				$arr[$i]["NAME"]=$row['NAME'];
//				$arr[$i]["USERNAME"]=$row['USERNAME'];
//				$arr[$i]["EMAIL"]=$row['EMAIL'];
				$arr[$i]["CATEGORY"]=get_category($row['CATEGORY']);
				$arr[$i]["COMMENT"]=$row['COMMENT'];
//				$arr[$i]["ADDRESS"]=$row['ADDRESS'];
				$arr[$i]["ENTRY_DT"]=my_format_date($dd,$mm,$yy);
				$arr[$i]["ABUSE"]=$row['ABUSE'];
//				$arr[$i]["SUBJECT"]=$row['SUBJECT'];
				$i++;
			}
			$smarty->assign("arr",$arr);

			$smarty->display("faq_admin_fetch.htm");
*/
		}
		elseif($faqreply)
		{
			if($CMDForward1)
				$smarty->assign("FORWARD","Y");
			$smarty->assign("tempidstr",$tempidstr);
			if(count($idarr)==1)
                        {
                                $smarty->assign("SINGLE","Y");
                                $id=$idarr[0];
/*                                $sql="SELECT * FROM feedback.MAIN_DATA WHERE ID ='$id'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if($row=mysql_fetch_array($res))
                                {
                                        list($edt,$etime)=explode(" ",$row['ENTRY_DT']);
                                        list($yy,$mm,$dd)=explode("-",$edt);
                                        $smarty->assign("ID",$row['ID']);
                                        $smarty->assign("NAME",$row['NAME']);
                                        $smarty->assign("USERNAME",$row['USERNAME']);
                                        $smarty->assign("EMAIL",$row['EMAIL']);
                                        $smarty->assign("CATEGORY",get_category($row['CATEGORY']));
                                        $smarty->assign("COMMENT",$row['COMMENT']);
                                        $smarty->assign("ADDRESS",$row['ADDRESS']);
                                        $smarty->assign("ENTRY_DT",my_format_date($dd,$mm,$yy));
                                        $smarty->assign("ABUSE",$row['ABUSE']);
                                }
*/
				$sql="SELECT ID,CATEGORY,ABUSE,FIRST_ENTRY_DT,NAME,USERNAME,EMAIL,ADDRESS FROM feedback.TICKETS WHERE STATUS='OPEN' AND ID='$id'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($row=mysql_fetch_array($res))
                                {
                                        $smarty->assign("TICKETID",$row['ID']);
                                        $smarty->assign("CATEGORY",get_category($row['CATEGORY']));
                                        $smarty->assign("ABUSE",$row['ABUSE']);
                                        $entry_dt=substr($row['FIRST_ENTRY_DT'],0,10);
                                        list($yy,$mm,$dd)=explode("-",$entry_dt);
                                        $smarty->assign("FIRST_ENTRY_DT",my_format_date($dd,$mm,$yy));
                                        $smarty->assign("NAME",$row['NAME']);
                                        $smarty->assign("USERNAME",$row['USERNAME']);
                                        $smarty->assign("EMAIL",$row['EMAIL']);
                                        $smarty->assign("ADDRESS",$row['ADDRESS']);
                                }

                                $j=0;
                                unset($arr);
                                $sql="SELECT ID,QUERY,ENTRY_DT,REPLY,REPLY_DT,REPLYBY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$id' ORDER BY ENTRY_DT ASC";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($row=mysql_fetch_array($res))
                                {
                                        list($edt,$etime)=explode(" ",$row['ENTRY_DT']);
                                        list($eyy,$emm,$edd)=explode("-",$edt);
                                        list($rdt,$rtime)=explode(" ",$row['REPLY_DT']);
                                        list($ryy,$rmm,$rdd)=explode("-",$rdt);

                                        $arr[$j]["id"]=$row['ID'];
                                        $arr[$j]["ENTRY_DT"]=my_format_date($edd,$emm,$eyy);
                                        $arr[$j]["REPLY_DT"]=my_format_date($rdd,$rmm,$ryy);
                                        $arr[$j]["QUERY"]=nl2br($row['QUERY']);
                                        $arr[$j]["REPLY"]=$row['REPLY'];
                                        $arr[$j]["REPLYBY"]=$row['REPLYBY'];
                                        $j++;
                                }
				$smarty->assign("ticket_idarr",$arr);
                                $smarty->assign("idarr",$idarr);
                                unset($arr);
                        }
                        else
                        {
                                $category='';
                                $i=0;
				$sql="SELECT ID,NAME,FIRST_ENTRY_DT,USERNAME,EMAIL,CATEGORY,ADDRESS,ABUSE FROM feedback.TICKETS WHERE ID IN ($idstr)";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($row=mysql_fetch_array($res))
				{
                                        list($edt,$etime)=explode(" ",$row['FIRST_ENTRY_DT']);
                                        list($yy,$mm,$dd)=explode("-",$edt);

                                        $arr[$i]["ID"]=$row['ID'];
                                        $arr[$i]["NAME"]=$row['NAME'];
                                        $arr[$i]["USERNAME"]=$row['USERNAME'];
                                        $arr[$i]["EMAIL"]=$row['EMAIL'];
                                        $arr[$i]["CATEGORY"]=get_category($row['CATEGORY']);
//                                        $arr[$i]["COMMENT"]=$row['COMMENT'];
                                        $arr[$i]["ADDRESS"]=$row['ADDRESS'];
                                        $arr[$i]["ENTRY_DT"]=my_format_date($dd,$mm,$yy);
                                        $arr[$i]["ABUSE"]=$row['ABUSE'];
                                        $category.="\n".$arr[$i]["CATEGORY"];
                                        $i++;
                                }
                                $smarty->assign("arr",$arr);
                                $smarty->assign("idarr",$idarr);
                                $smarty->assign("CATEGORY",$category);
                                unset($arr);
                        }
                        // assign variables to template

                        $smarty->display("faq_admin_reply.htm");
		}
		else
		{
			echo "lk";
		}
	}
	else
	{
		if($CMDReply)
		{
			$flag=1;
			$status="R";

			$msg=$reply;
			$msg.="\n\n";
			$from="jeevansathi_feedback@jeevansathi.com";

			$sql="SELECT ID,EMAIL FROM feedback.TICKETS WHERE ID IN ($idstr)";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$subject="Reply to your query no [#$row[ID]] ";
				$sql="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$row[ID]' AND REPLY_DT=0";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row1=mysql_fetch_array($res1))
				{
					$msg.="\n\n".$row1['QUERY'];
				}
				$msg=nl2br($msg);
				$to=$row['EMAIL'];
				send_email($to,$msg,$subject,$from);
			}
		}
		elseif($CMDForward)
		{
			$status="F";

			$from="jeevansathi_feedback@jeevansathi.com";

			$sql="SELECT ID,EMAIL,USERNAME FROM feedback.TICKETS WHERE ID IN ($idstr)";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$msg="Your query has been forwarded to the concerned department";
				$msg.="\n\n";
				$msgforward='';
				$msgforward="\nUsername : $row[USERNAME]\n";
				$msgforward.="Email : $row[EMAIL]";
				$subject="Reply to your query no [#$row[ID]]";
				$to=$row['EMAIL'];
				$sql="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$row[ID]' AND REPLY_DT=0";
                                $res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($row1=mysql_fetch_array($res1))
                                {
                                        $msg.="\n\n".$row1['QUERY'];
					$msgforward.="\n\n".$row1['QUERY'];
                                }
				$msgforward.="\n\n".$reply;
				$msgforward=nl2br($msgforward);
				$msg=nl2br($msg);
				
				send_email($forwardto,$msgforward,$subjectforward,$from);
				send_email($to,$msg,$subject,$from);
			}
			$reply.="\nMessage forwarded to $forwardto";
			$flag=1;
		}
		elseif($CMDDiscard)
		{
			$flag=1;
			$status="D";
			$reply.="\nMessage Discarded";
		}

		if($flag)
		{
			$sql="UPDATE feedback.TICKETS SET STATUS='REPLIED' WHERE ID IN ($idstr) ";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$sql="UPDATE feedback.TICKET_MESSAGES SET REPLY='".addslashes(stripslashes($reply))."',REPLYBY='$admin_name',REPLY_DT=now(),STATUS='$status' WHERE TICKETID IN ($idstr) AND REPLY_DT=0";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$msg="Query Answered<br>";
			$msg .="<a href=\"faq_admin_fetch.php?cid=$cid&category=$category\">";
			$msg .="Continue &gt;&gt; </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("faq_continue.htm");
		}
		else
		{
			if($CMDForward1)
				$smarty->assign("FORWARD","Y");
			$smarty->assign("tempidstr",$tempidstr);

			if(count($idarr)==1)
			{
				$smarty->assign("SINGLE","Y");
				$id=$idarr[0];

				$sql="SELECT ID,CATEGORY,ABUSE,FIRST_ENTRY_DT,NAME,USERNAME,EMAIL,ADDRESS FROM feedback.TICKETS WHERE STATUS='OPEN' AND ID='$id'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$smarty->assign("TICKETID",$row['ID']);
					$smarty->assign("CATEGORY",get_category($row['CATEGORY']));
					$smarty->assign("ABUSE",$row['ABUSE']);
					$entry_dt=substr($row['FIRST_ENTRY_DT'],0,10);
					list($yy,$mm,$dd)=explode("-",$entry_dt);
					$smarty->assign("FIRST_ENTRY_DT",my_format_date($dd,$mm,$yy));
					$smarty->assign("NAME",$row['NAME']);
					$smarty->assign("USERNAME",$row['USERNAME']);
					$smarty->assign("EMAIL",$row['EMAIL']);
					$smarty->assign("ADDRESS",$row['ADDRESS']);
				}

				$j=0;
				unset($arr);
				$sql="SELECT ID,QUERY,ENTRY_DT,REPLY,REPLY_DT,REPLYBY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$id' ORDER BY ENTRY_DT ASC";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					list($edt,$etime)=explode(" ",$row['ENTRY_DT']);
					list($eyy,$emm,$edd)=explode("-",$edt);
					list($rdt,$rtime)=explode(" ",$row['REPLY_DT']);
					list($ryy,$rmm,$rdd)=explode("-",$rdt);

					$arr[$j]["id"]=$row['ID'];
					$arr[$j]["ENTRY_DT"]=my_format_date($edd,$emm,$eyy);
					$arr[$j]["REPLY_DT"]=my_format_date($rdd,$rmm,$ryy);
					$arr[$j]["QUERY"]=nl2br($row['QUERY']);
					$arr[$j]["REPLY"]=$row['REPLY'];
					$arr[$j]["REPLYBY"]=$row['REPLYBY'];
					$j++;
				}
				$smarty->assign("ticket_idarr",$arr);
				$smarty->assign("idarr",$idarr);
				unset($arr);
			}
			else
			{
				$category='';
				$i=0;
				$sql="SELECT ID,NAME,FIRST_ENTRY_DT,USERNAME,EMAIL,CATEGORY,ADDRESS,ABUSE FROM feedback.TICKETS WHERE ID IN ($idstr)";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					list($edt,$etime)=explode(" ",$row['FIRST_ENTRY_DT']);
					list($yy,$mm,$dd)=explode("-",$edt);
					$arr[$i]["ID"]=$row['ID'];
					$arr[$i]["NAME"]=$row['NAME'];
					$arr[$i]["USERNAME"]=$row['USERNAME'];
					$arr[$i]["EMAIL"]=$row['EMAIL'];
					$arr[$i]["CATEGORY"]=get_category($row['CATEGORY']);
//					$arr[$i]["COMMENT"]=$row['COMMENT'];
					$arr[$i]["ADDRESS"]=$row['ADDRESS'];
					$arr[$i]["ENTRY_DT"]=my_format_date($dd,$mm,$yy);
					$arr[$i]["ABUSE"]=$row['ABUSE'];
					$category.="\n".$arr[$i]["CATEGORY"];
					$i++;
				}
				$smarty->assign("arr",$arr);
//				$smarty->assign("idarr",$idarr);
				$smarty->assign("CATEGORY",$category);
				unset($arr);
			}
			// assign variables to template 

			$smarty->display("faq_admin_reply.htm");
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
