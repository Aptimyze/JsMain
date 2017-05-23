<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("connect.inc");
include_once("track_matchalert.php");
$db=connect_db();

/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/************************************************End of Portion of Code*****************************************/

$data=authenticated();
if($_GET['clicksource']=='matchalert1')
{
	if($_GET['echecksum'])
	{
		$show_chatbar=1;
		if($_COOKIE['chatbar']=='yes')
			$show_chatbar=0;

	}
	if(!$data['PROFILEID'])
	{
		$epid=$protect_obj->js_decrypt($_GET['echecksum']);
		if($_GET['checksum']==$epid)
		{
			$epid_arr=explode("i",$epid);
			$profileid=$epid_arr[1];
			if($profileid)
			{
				$smarty->assign("for_about_us","1");
				$sql="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE PROFILEID=$profileid and  activatedKey=1";
				$res=mysql_query_decide($sql) or die($sql.mysql_error());
				$row=mysql_fetch_assoc($res);
				$_POST['username']=$row['USERNAME'];
				$_POST['password']=$row['PASSWORD'];
				$username=$row['USERNAME'];
				$password=$row['PASSWORD'];
				$data =login($username,$password);
			}
		}
	}
	else
	{
		$profileid=$data['PROFILEID'];
		$username=$data['USERNAME'];
	}
	if($show_chatbar==1)
	{
		$request_uri=$_SERVER['REQUEST_URI'];

		$pos = strpos($request_uri,"login.php");
		$pos1= strpos($request_uri,"intermediate.php");
		$pos2=strpos($request_uri,"login_redirect.php");
		if($pos == false && $pos1 == false && $pos2== false){
			header("Location:".$SITE_URL."/profile/intermediate.php?parentUrl=".$request_uri);
			exit;
		}
	}
}
$data=authenticated();


if($_POST["submit"] || $_POST["submit_x"]) 
{
	$count=1;

	if($_POST['photo'])
		$photo=1;
	$receiver=$_POST['receiver'];
	$user=$_POST['user'];
	
	$dpp.=$_POST['height'];
	$dpp.=$_POST['age'];
	$dpp.=$_POST['mstatus'];
	$dpp.=$_POST['city'];
	$dpp.=$_POST['country'];
	$dpp.=$_POST['religion'];
	$dpp.=$_POST['caste'];
	$dpp.=$_POST['mtongue'];
        $dpp.=$_POST['manglik'];
        $dpp.=$_POST['diet'];
        $dpp.=$_POST['smoke'];
        $dpp.=$_POST['drink'];
	$dpp.=$_POST['complexion'];
        $dpp.=$_POST['btype'];
        $dpp.=$_POST['gothra'];
        $dpp.=$_POST['edu'];
        $dpp.=$_POST['occ'];
        $dpp.=$_POST['income'];

	$reason=$_POST['textarea'];
	$reason=addslashes(stripslashes($reason));
	MatchDislikeReason($photo,$dpp,$reason,$receiver,$user);
}
else
{
	if($_GET['echecksum'])
	{
		$show_chatbar=1;
		if($_COOKIE['chatbar']=='yes')
			$show_chatbar=0;

	}
	if(!$data['PROFILEID'])
	{
		$epid=$protect_obj->js_decrypt($_GET['echecksum']);
		if($_GET['checksum']==$epid)
		{
			$epid_arr=explode("i",$epid);
			$profileid=$epid_arr[1];
			if($profileid)
			{
				$smarty->assign("for_about_us","1");
				$sql="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE PROFILEID=$profileid and  activatedKey=1";
				$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row=mysql_fetch_assoc($res);
				$_POST['username']=$row['USERNAME'];
				$_POST['password']=$row['PASSWORD'];
				$username=$row['USERNAME'];
				$password=$row['PASSWORD'];
				$data =login($username,$password);
		       }
		}
	}
	$receiver=$data["PROFILEID"];
	list($temp,$user)=explode("i",$profilechecksum);

	$receiver=$receiver;
	$match=$user;
	if($MatchAlertlike)
		MatchLikedOrNor($MatchAlertlike,$receiver,$match);

	$count=0;
	$smarty->assign("receiver",$receiver);
	$smarty->assign("user",$user);
	
}
$smarty->assign("count",$count);
$smarty->assign("alert_manager",1);
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("head_tab",'my jeevansathi');
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("PROFILECHECKSUM",$profilechecksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->display("matchalertDislike.htm");
?>
