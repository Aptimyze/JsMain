<?
include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$start_dt=$year."-".$month."-".$day." 00:00:00";
		$end_dt=$year."-".$month."-".$day." 23:59:59";

		$date=$year."-".$month."-".$day;
		$smarty->assign("DATE",$date);

		$i=0;

		$sql="SELECT TICKETID,QUERY,REPLY,REPLYBY,STATUS,REPLY_DT FROM feedback.TICKET_MESSAGES WHERE ENTRY_DT BETWEEN '$start_dt' AND '$end_dt'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$ticketid=$row['TICKETID'];
			$sql="SELECT USERNAME,EMAIL FROM feedback.TICKETS WHERE ID='$ticketid'";
			$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			$row1=mysql_fetch_array($res1);
			$arr[$i]['USERNAME']=$row1['USERNAME'];
			$arr[$i]['EMAIL']=$row1['EMAIL'];
			$arr[$i]['QUERY']=$row['QUERY'];
			$arr[$i]['REPLY']=$row['REPLY'];
			$arr[$i]['STATUS']=$row['STATUS'];
			$arr[$i]['REPLYBY']=$row['REPLYBY'];
			$arr[$i]['REPLY_DT']=substr($row['REPLY_DT'],0,10);

			$i++;
		}
		$smarty->assign("arr",$arr);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag","1");
		$smarty->display("faqmis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;

		$smarty->assign("cid",$cid);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);

		$smarty->display("faqmis.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
