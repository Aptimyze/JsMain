<?
include('connect.inc');
$db=connect_db();
$sql="SELECT GENDER,SUBSCRIPTION
FROM newjs.JPROFILE
WHERE PROFILEID='$profileid'";
$res=mysql_query_decide($sql,$db) or die();
$row=mysql_fetch_array($res);
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
$smarty->assign("gender",$gender);
$smarty->assign("profileid",$profileid);
$smarty->assign("username",$username);
$smarty->assign("valuable",$valuable);
$smarty->display('messenger1.html');
?>
