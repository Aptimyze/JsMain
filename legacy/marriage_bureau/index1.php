<?php
/************************************************************************************************************************
*   FILENAME      :  index.php
*   DESCRIPTION   :  To end the session of the user.
*   CREATED BY    :  Lavesh
***********************************************************************************************************************/
include_once("connectmb.inc");
include_once('../profile/display_result.inc');
$db=connect_dbmb();
if($afterreg=="1")
	$data=loginmb($username,$password);
else if($justloggedin!=1)
	$data=authenticatedmb($mbchecksum);
if($data)
{
	$PAGELEN=15;
	$LINKNO=10;
	if(!$j )
		$j = 0;
	$sno=$j+1;
	$checksum=$data["CHECKSUM"];
	$username=$data["USERNAME"];
	$profileid=$data["PROFILEID"];
	$sql="SELECT MONEY FROM marriage_bureau.BUREAU_PROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_array($res))
		$money=$row['MONEY'];
	$profileid=$data["PROFILEID"];
	$source=generate_source4MB($data["PROFILEID"]);
	mysql_select_db_js('newjs');
	$sql="SELECT count(*) as cnt FROM newjs.JPROFILE WHERE SOURCE='$source' AND  ACTIVATED!='D'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_array($result))
		$TOTALREC=$row['cnt'];
	if ($j)
        	$cPage = ($j/$PAGELEN) + 1;
	else
		$cPage = 1;
	//pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"index1.php",'',$param);
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$checksum,"index1.php",'',$param);
	$no_of_pages=ceil($TOTALREC/$PAGELEN);
	shownewjs_details($source,$profileid,$j,$PAGELEN);
	$smarty->assign('money',$money);
	$smarty->assign('source',$source);
	$smarty->assign('username',$username);
	$smarty->assign('money',$money);
	$smarty->assign('profilesposted',$TOTALREC);
	$smarty->assign('cid',$checksum);
	$smarty->assign('mbchecksum',$checksum);
	assign_template_pathprofile();
	$HEAD=$smarty->fetch('top_band.htm');
	assign_template_pathmb();
	$smarty->assign('HEAD',$HEAD);
	
	//Added By lavesh to account info of profiles which are now deleted but may earlier have seen contact details.
	if($TOTALREC < ($cPage*$PAGELEN))
        {
		$sql="SELECT PROFILEID FROM newjs.JPROFILE where SOURCE='$source' AND  ACTIVATED='D'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$pid=$row['PROFILEID'];
			$sql1="SELECT count(*) as cnt FROM marriage_bureau.VIEWED WHERE AGAINST_PROFILE='$pid'";
			$res1=mysql_query_decide($sql1) or die(mysql_error_js());
			$row1=mysql_fetch_array($res1);

			if($row1['cnt']>0)
			{
				$delete_contact+=$row1['cnt'];
			}
		}
		if($delete_contact)
		{
			$smarty->assign("delete_contact",$delete_contact);
		}
	}
        //Ends Here

	$smarty->display('mainmenu.htm');
}
else
{
	timeoutmb();
}
?>
