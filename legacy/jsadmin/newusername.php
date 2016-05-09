<?php

include("connect.inc");
include("../crm/display_result.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("../profile/screening_functions.php");

$PAGELEN = 30 ;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
$sno=$j+1;

if(authenticated($cid))
{
	if($Submit)
	{
		$nameofperson=getname($cid);
		if($IdLista!="")
		{
			$sql="UPDATE newjs.CUSTOMISED_USERNAME SET SCREENED='A',SCREENEDBY='$nameofperson',SCREENED_TIME=NOW() WHERE SCREENED='N' AND PROFILEID";
			$acceptarray=explode("|oo|",$IdLista);
			$accept1="'".implode("','",$acceptarray)."'";
			$sql.=" IN ($accept1)";
			$row=mysql_query_decide($sql,$db) or die();//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$acceptname=explode("|oo|",$IdLista_name);
			$ek=sizeof($acceptarray);
			for($i=0;$i<$ek;$i++)
			{
				$sql="SELECT PASSWORD,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$acceptarray[$i]'";
				$res=mysql_query_decide($sql,$db) or die();//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
				if($row=mysql_fetch_array($res))
				{
					$emailid=$row['EMAIL'];
					$password=$row['PASSWORD'];
				}
				$smarty->assign('newusername',$acceptname[$i]);
				$smarty->assign('emailid',$emailid);
				$smarty->assign('password',$password);
				$THINGSTODO=thingstodobox($acceptarray[$i]);
                                $smarty->assign('THINGSTODO',$THINGSTODO);
				$msg=$smarty->fetch("mailer_ID_accepted.htm");
				send_email($emailid,$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
				makes_username_changes($acceptarray[$i],$acceptname[$i]);
			}
		}
	
		if($IdListd!="")
		{
			$sql="UPDATE newjs.CUSTOMISED_USERNAME SET SCREENED='D',SCREENEDBY='$nameofperson',SCREENED_TIME=NOW() WHERE SCREENED='N' AND PROFILEID ";
			$declinearray=explode("|oo|",$IdListd);
			$decline1="'".implode("','",$declinearray)."'";
			$sql.=" IN ($decline1)";
			$row=mysql_query_decide($sql,$db) or die();//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$ek=sizeof($declinearray);
			for($i=0;$i<$ek;$i++)
			{
				$THINGSTODO=thingstodobox($declinearray[$i]);
				$smarty->assign('THINGSTODO',$THINGSTODO);
                        	$msg=$smarty->fetch("mailer_ID_declined.htm");
				$sql="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$declinearray[$i]'";
				$res=mysql_query_decide($sql,$db) or die(mysql_error_js($sql));
				if($row=mysql_fetch_array($res))
					$emailid=$row['EMAIL'];
				send_email($emailid,$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
			}
		}
	}
	$sql = "Select Count(*) from newjs.CUSTOMISED_USERNAME WHERE SCREENED='N'";
	$result = mysql_query_decide($sql,$db);
	$myrow = mysql_fetch_row($result);
	$TOTALREC = $myrow[0];
	$r=0;
	if($TOTALREC>0)
	{
		$sql = "Select OLD_USERNAME,PROFILEID,NEW_USERNAME from newjs.CUSTOMISED_USERNAME WHERE SCREENED='N' LIMIT $j,$PAGELEN";
		$result = mysql_query_decide($sql,$db) or die();//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow = mysql_fetch_array($result))
		{
			$values[]=array("sno"=>$sno,
					"OLD_USERNAME"=>$myrow["OLD_USERNAME"],
					"PROFILEID"=>$myrow["PROFILEID"],
					"USERNAME"=>$myrow["NEW_USERNAME"]	);
			$sno++;
			$r=1;
		}
	}
	if( $j )
		$cPage = ($j/$PAGELEN) + 1;
	else
		$cPage = 1;
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"newusername.php");
	$smarty->assign('arethere',$r);
	$smarty->assign("ROWS",$values);
	$smarty->assign("COUNT",$TOTALREC);
	$smarty->assign("CURRENTPAGE",$cPage);
	$no_of_pages=ceil($TOTALREC/$PAGELEN);
	$smarty->assign("NO_OF_PAGES",$no_of_pages);
	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("newusername.htm");
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
