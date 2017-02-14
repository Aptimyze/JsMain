<?php
include ("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$PAGELEN=25;
$LINKNO=10;
$START=1;
if (!$j )
	$j = 0;

$sno=$j+1;

if (authenticated($cid))
{
	$name= getname($cid);
	$now=time();
	$now+=60*60;
	$today=date("Y-m-d",$now)." 23:59:59";

      	$jsCommonObj =new JsCommon();
        $onlineProfiles =$jsCommonObj->getOnlineUsetList();
        /*
	$JsMemcacheObj 	=JsMemcache::getInstance();
	$listName	=CommonConstants::ONLINE_USER_LIST;
	$onlineProfiles =$JsMemcacheObj->zRange($listName, 0, -1);*/

	//$sql= "SELECT C.PROFILEID AS ID,C.SCORE as score FROM userplane.recentusers A, incentive.MAIN_ADMIN B, incentive.MAIN_ADMIN_POOL C WHERE A.userID = B.PROFILEID AND A.userID = C.PROFILEID AND B.ALLOTED_TO='$name'";
	$sql="select PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name'";

	$result=mysql_query_decide($sql,$db) or die(mysql_error($db));
	while($myrow = mysql_fetch_array($result))
	{
		$profileid =$myrow['PROFILEID'];
		if(!in_array($profileid, $onlineProfiles))
			continue;
		$SQL="SELECT USERNAME AS user,SUBSCRIPTION AS SUB FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$result1=mysql_query_decide($SQL,$db) or die(mysql_error($db));
		$RES=mysql_fetch_array($result1);
		if(mysql_num_rows($result1)>0)
		{
			$arr1[]=$myrow['score'];
			$arr2[]=$RES['user'];
			$arr3[]=$RES['SUB'];
			$arr4[]=$profileid;
		}
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("score",$arr1);
	$smarty->assign("user",$arr2);
	$smarty->assign("sub",$arr3);
	$smarty->assign("id",$arr4);
	$smarty->assign("name",$name);
	$smarty->display("online.htm");
}
else //user timed out
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.php\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

//        $TOTALREC = $myrow[0];
?>

