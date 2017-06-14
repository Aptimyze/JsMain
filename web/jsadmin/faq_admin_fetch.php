<?php
include("connect.inc");
include ("../crm/display_result.inc");
//$db=connect_db();

$PAGELEN=50;
$LINKNO=5;
$START=1;
if (!$j )
        $j = 0;

$sno=$j+1;

if(authenticated($cid))
{
	for($i=0;$i<31;$i++)
	{
		$ddarr[$i]=$i+1;
	}
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);

	$i=0;
	$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE PARENT=0";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$catarr[$i]["id"]=$row['ID'];
		$catarr[$i]["name"]=$row['QUESTION'];
		$i++;
	}
	$smarty->assign("catarr",$catarr);

	$sql="SELECT COUNT(*) FROM feedback.TICKETS WHERE STATUS='OPEN' ";
	if($category)
	{
		$sql.=" AND CATEGORY LIKE '%.$category%'";
	}
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row = mysql_fetch_row($res);
	$TOTALREC = $row[0];

	$i=0;

	$sql="SELECT ID,CATEGORY,ABUSE,FIRST_ENTRY_DT,COUNTER FROM feedback.TICKETS WHERE STATUS='OPEN' ";
	if($category)
	{
		$sql.=" AND CATEGORY LIKE '%.$category%'";
	}
	$sql.=" LIMIT $j,$PAGELEN";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$idarr[$i]=$row['ID'];
		$arr[$i]['SNO']=$sno;
		$arr[$i]['ID']=$row['ID'];
		$arr[$i]['CATEGORY']=get_category($row['CATEGORY']);
		$arr[$i]["ABUSE"]=$row['ABUSE'];
		$entry_dt=substr($row['FIRST_ENTRY_DT'],0,10);
		list($yy,$mm,$dd)=explode("-",$entry_dt);
		$arr[$i]["ENTRY_DT"]=my_format_date($dd,$mm,$yy);
		$arr[$i]["COUNTER"]=$row['COUNTER'];
		$i++;
		$sno++;
	}

	if($idarr)
	{
		$idstr=implode(",",$idarr);
		$sql="SELECT TICKETID,QUERY,ENTRY_DT FROM feedback.TICKET_MESSAGES WHERE TICKETID IN ($idstr) GROUP BY TICKETID ORDER BY ENTRY_DT DESC ";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$id=array_search($row['TICKETID'],$idarr);
			$arr[$id]['QUERY']=nl2br($row['QUERY']);
		}
	}

	if($error)
		$smarty->assign("ER_IDARR","Y");

	if( $j )
                $cPage = ($j/$PAGELEN) + 1;
        else
                $cPage = 1;

        pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"faq_admin_fetch.php",'',$flag);
        $smarty->assign("COUNT",$TOTALREC);
        $smarty->assign("CURRENTPAGE",$cPage);
        $no_of_pages=ceil($TOTALREC/$PAGELEN);
        $smarty->assign("NO_OF_PAGES",$no_of_pages);

	$smarty->assign("arr",$arr);
	$smarty->assign("CURID",$category);
	$smarty->assign("cid",$cid);
	$smarty->display("faq_admin_fetch.htm");
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
