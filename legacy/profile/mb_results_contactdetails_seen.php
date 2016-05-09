<?
$fromprofilepage=1;
include('../marriage_bureau/connectmb.inc');
$db=connect_dbmb();
$mbdata=authenticatedmb($mbchecksum);
mysql_select_db_js('newjs');
include_once('connect.inc');
if($checksum)
	$data=authenticated($checksum);
else
	$data=login_every_user($pid);
mysql_select_db_js('marriage_bureau');
$mb_username_profile=$data["USERNAME"];
$marriage_bureau_profile=1;
if($mbdata)
{
	$mbchecksum=$mbdata["CHECKSUM"];
	$checksum=$data["CHECKSUM"];
	$pid=$data["PROFILEID"];
	include('search.inc');
	$db=connect_db();
	$PAGELEN=12;
	$flag_mb=0;
	mysql_select_db_js('newjs');

	//for new contacts

	//Sharding On Contacts done by Lavesh Rawat
        $contactResult=getResultSet("SENDER","","",$pid,"","'A");
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$array_profiles[]=$contactResult[$key]["SENDER"];
		}
	}
	//Sharding On Contacts done by Lavesh Rawat
	

	//Added By lavesh
	if($trick_bybureau==1)
	{
		$sql="SELECT VIEWED_PROFILE FROM marriage_bureau.VIEWED WHERE  AGAINST_PROFILE='$pid'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			if(is_array($array_profiles))
			{
				if(!in_array($row['VIEWED_PROFILE'],$array_profiles))
				{
					$profiles_declined_after_accaptance[$i]=$row['VIEWED_PROFILE'];
					$i++;
				}
			}
			else
			{
				$profiles_declined_after_accaptance[$i]=$row['VIEWED_PROFILE'];
				$i++;
			}
		}
		$array_profiles=$profiles_declined_after_accaptance;
	}
	//Ends Here.

	$profilestoshow="'".implode("','",$array_profiles)."'";
	$sql="SELECT PROFILEID FROM newjs.JPROFILE where  activatedKey=1 and PROFILEID IN ($profilestoshow)";
	$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
	if(mysql_num_rows($result) > 0)
               $flag_mb=1;
	if(!$j )
                $j = 0;
        $curcount=$j;
	if ($j)
                $cPage = ($j/$PAGELEN) + 1;
        else
                $cPage = 1;
	if($flag_mb==1)
		$RESULT_ARRAY=displayresults($result,$curcount,$scriptname,$totalrec,"","","","","","","");
	$mb_username_profile=newjsusername($pid);
	$smarty->assign('whatsthat',$whatsthat);
	$smarty->assign('mbchecksum',$mbchecksum);
	$smarty->assign('checksum',$checksum);
	$smarty->assign('source',$mbdata["SOURCE"]);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("mb_username_profile",$mb_username_profile);
	$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
	$smarty->assign("againstprofileid",$pid);
	$smarty->display('mb1.htm');
}
else
{
	timeoutmb();
}
?>
