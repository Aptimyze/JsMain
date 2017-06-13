<?php

include("connect.inc");
include("../crm/display_result.inc");

$PAGELEN = 30 ;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
$sno=$j+1;


if(authenticated($cid))
{
	$sql = "Select Count(*)  from CHECK_MAILID";
	$result = mysql_query_decide($sql,$db);
	$myrow = mysql_fetch_row($result);
	$TOTALREC = $myrow[0];

        $sql = "Select * from CHECK_MAILID LIMIT $j,$PAGELEN";
	$result = mysql_query_decide($sql,$db);


	while($myrow = mysql_fetch_array($result))
		{
			$values[]=array("sno"=>$sno,
					"PROFILEID"=>$myrow["PROFILEID"],
					"USERNAME"=>$myrow["USERNAME"]	);
			$sno++;
		}

	if( $j )
		$cPage = ($j/$PAGELEN) + 1;
	else
		$cPage = 1;
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"mailid_view.php");

	$smarty->assign("ROWS",$values);
        $smarty->assign("COUNT",$TOTALREC);
	$smarty->assign("CURRENTPAGE",$cPage);
	$no_of_pages=ceil($TOTALREC/$PAGELEN);
	$smarty->assign("NO_OF_PAGES",$no_of_pages);

	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("mailid_view.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}


?>

