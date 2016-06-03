<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
include("display_result.inc");
$db=connect_db();
$data = authenticated($checksum);
$profileid=$data["PROFILEID"];
if($fsubmit)
{
	if($abuse=='Y')
	{
		$sql = "INSERT INTO FEEDBACK (NAME,ADDRESS,EMAIL,COMMENTS,DATE,ABUSE) VALUES ('$NAME','$ADDRESS','$EMAIL','$COMMENTS', '".date("Y-m-d")."','Y')";
	}
	else
	{
		$sql = "INSERT INTO FEEDBACK (NAME,ADDRESS,EMAIL,COMMENTS,DATE) VALUES ('$NAME','$ADDRESS','$EMAIL','$COMMENTS', '".date("Y-m-d")."')";
	}
        $result = mysql_query_decide($sql);
        $smarty->assign("CHECKSUM",$checksum);
        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                                                                                                 
        $smarty->display("confirmation.htm");

}
else
{
echo "ab : ".$abuse;
	if($abuse=='Y')
	{ 
		$smarty->assign("abuse",'Y');
	}
	$smarty->assign("CHECKSUM",$checksum);
       	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                                                                                                
       	$smarty->display("feedback.htm");

}

// flush the buffer
if($zipIt)
	ob_end_flush();
                                                                                                 
?>
