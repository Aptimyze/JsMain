<?php
/****************************************************************************************************************************
*	FILENAME	   : showpromotiondetails.php
*	INCLUDED           : connect.inc
*       DESCRIPTION        : Displays the details of the promotions via  Newspaper or Affiliates.It also provides 
			     information about the users who have been converted into members or Jeevansathi registered user.
*       CREATED BY         : shobha
****************************************************************************************************************************/

	include("connect.inc");

	$curyymm = date("Y-m");
	dbsql2_connect();

	if (authenticated($cid))
	{
		$username=getname($cid);

		$newscount	= 0;
		$smscount	= 0;
		$regcount	= 0;
		$paidcount	= 0;
		$i		= 0;

		$sql = "SELECT MODE , COUNT(*) AS CNT  FROM jsadmin.AFFILIATE_MAIN WHERE ENTRYBY='$username' AND ENTRYTIME >= '$curyymm-01' AND ENTRYTIME <='$curyymm-31' GROUP BY MODE ";

		$res = mysql_query($sql) or die("$sql".mysql_error());
		while($row = mysql_fetch_array($res))
		{

			if ($row["MODE"] == 'N')
                                $newscount = $row["CNT"];
                        else
                                $smscount  = $row["CNT"];				
		}

		/*$sql = "SELECT COUNT(*) AS CNT ,newjs.JPROFILE.SUBSCRIPTION  FROM jsadmin.MAILER_TEST LEFT JOIN newjs.JPROFILE ON jsadmin.MAILER_TEST.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.MAILER_TEST.ENTRYTIME BETWEEN '$curryymm-01'AND '$curryymm-31' AND  jsadmin.MAILER_TEST.ENTRYBY='$username' GROUP BY newjs.JPROFILE.SUBSCRIPTION" ; 
		
		$res = mysql_query($sql) or die("$sql".mysql_error());
		while($row = mysql_fetch_array($res))
		{
			$regcount+=$row['CNT'];

			if ($row["SUBSCRIPTION"]!='')
				$paidcount+=$row['CNT'];
			
		}*/

		$smarty->assign("newscount",$newscount);
		$smarty->assign("smscount",$smscount);
		$smarty->assign("regcount",$regcount);
		$smarty->assign("paidcount",$paidcount);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->assign("username",$username);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));

		$smarty->display("showpromotiondetails.htm");
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



