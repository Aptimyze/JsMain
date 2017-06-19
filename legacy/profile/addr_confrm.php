<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
$db=connect_db();
$data=authenticated();

if(($_GET['echecksum']) && ($addr_mailer==1) && !$data['PROFILEID']){
	$epid=$protect_obj->js_decrypt($_GET['echecksum']);
        if($_GET["checksum"]==$epid)
        {
		$epid_arr=explode("i",$epid);
                $profileid=$epid_arr[1];
                if($profileid){
                	$sql="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
                        $res=mysql_query_decide($sql) or die($sql.mysql_error());
                        $row=mysql_fetch_assoc($res);
                        $_POST['username']=$row['USERNAME'];
                        $_POST['password']=$row['PASSWORD'];
			$smarty->assign('LOGIN',1);
                        $username=$row['USERNAME'];
                        $password=$row['PASSWORD'];
			$smarty->assign('USERNAME',$username);
                        $data =login($username,$password);
			$parenturl=$_SERVER['REQUEST_URI'];
			header("Location:".$SITE_URL."/profile/intermediate.php?parentUrl=".$parenturl);
			exit;
                }
        }
}
if(!$data){
        TimedOut();
        exit;
}

if($data)
{
	$profileid=$data['PROFILEID'];
	$smarty->assign("CHECKSUM",$data["CHECKSUM"]);

	// values set for addrStatus for different action 
	if($addrStatus==1){
		$contentDisplay ="updation";	
	}
	else if($addrStatus==2){
		$contentDisplay ="confirmation";
	}
	else if($addrStatus==3){
		$contentDisplay ="verification";
	}
	else if($addrStatus==4){
		$contentDisplay ="denial";
	}
	addr_tracking($profileid,$addrStatus);
}

$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("contentDisplay",$contentDisplay);
$smarty->display("addr_confrm.htm");

// function to track the emails opened in address mailer
function addr_tracking($profileid,$status)
{
	if($status=='1' || $status=='2')
		$field="CHNG_CONF";
	elseif($status=='3')
		$field="VERIFIED";	
	elseif($status=='4')
		$field="DENIED";	

	$sql ="SELECT COUNT(*) as cnt FROM MIS.ADDR_TRACK WHERE PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or logError("error",$sql);
	$row =mysql_fetch_array($res);
	$cnt =$row['cnt'];
	if($cnt){
		if($field=='VERIFIED')
			$field2='DENIED';
		else
			$field2='VERIFIED';
		$sql ="UPDATE MIS.ADDR_TRACK SET `$field`='Y',`$field2`='' WHERE `PROFILEID`='$profileid'";
		mysql_query_decide($sql);
	}else{
		$sql="INSERT INTO MIS.ADDR_TRACK (`PROFILEID`,`$field`) VALUES ('$profileid','Y')";
		mysql_query_decide($sql);
	}
}

?>

