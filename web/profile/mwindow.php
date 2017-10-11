<?
include('connect.inc');
$db=connect_db();
$data=authenticated($checksum);

//if($alreadyloggedin==2)
if($data)
{
        //$profileid=$_COOKIE['ISEARCH'];
	$profileid=$data["PROFILEID"];
        $alreadyloggedin=1;
}
else if($profileid)
{
	$alreadyloggedin=1;
}
$sql="SELECT USERNAME,GENDER,SUBSCRIPTION
FROM newjs.JPROFILE
WHERE PROFILEID='$profileid'";
$res=mysql_query_decide($sql,$db) or die();
$row=mysql_fetch_array($res);
$username=$row['USERNAME'];
$subscriptionArray=explode(",",$row['SUBSCRIPTION']);
$k=count($subscriptionArray);
$valuable=0;
for($i=0;$i<$k;$i++)
{
	switch($subscriptionArray[$i])
	{
		case 'F' :
			//if($valuable>0)$userstatus.=" and ";
			//$userstatus.="an Erishta member";
			$valuable++;
			break;
		case 'D' :
			//if($valuable>0)$userstatus.=" and ";
			//$userstatus.="an EClassified member";
			$valuable++;
			break;
		default :
			break;
	}
}
$gender=$row['GENDER'];
//$uniqueid=uniqid("abc");
$uniqueid=2.6;

$smarty->assign("uniqueid",$uniqueid);

$smarty->assign("gender",$gender);
$smarty->assign("profileid",$profileid);
$smarty->assign("username",$username);
$smarty->assign("valuable",$valuable);
$smarty->assign("alreadyloggedin",$alreadyloggedin);
$smarty->display("mwindow.html");
?>
