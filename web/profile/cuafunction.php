<?php
/************************************************************************************************************************
* 	FILE NAME	:	cua.php
* 	DESCRIPTION 	: 	Get details for a new profile
* 	MODIFY DATE	: 	16 Feb, 2005
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	Ajax Based form 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//see if the newusername exists in the dbase or not
function checknewusername($newusername,$profileid)
{
	global $smarty;
	/*$sql="SELECT NEW_USERNAME FROM newjs.CUSTOMISED_USERNAME WHERE NEW_USERNAME='$newusername'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(!($row=mysql_fetch_array($res)))
                $username_available1=1;
	else
		$username_available1=0;*/
	$username_available1=valid_new_username($newusername);
	$ava1=check_username($newusername);
	$ava2=check_username_jprofile($newusername);
	$ava3=isvalid_username($newusername);
	if($username_available1==1 && $ava1==0 && $ava2==0 && $ava3==0)
		$username_available=1;
	else
	{
		$username_available=0;
		$sql="SELECT CASTE,AGE,COUNTRY_RES,YEAR(DTOFBIRTH) as yy FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
		{
			$caste=$row["CASTE"];
			$age=$row["AGE"];
			$country=$row["COUNTRY_RES"];
			$dtofbirth=$row["yy"];
			$sql="SELECT SMALL_LABEL FROM newjs.CASTE WHERE ID='$caste'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($row=mysql_fetch_array($res))
			{
				$caste=$row["SMALL_LABEL"];
				$caste=str_replace("-","",$caste);
			}
			$sql="SELECT LABEL FROM newjs.COUNTRY WHERE ID='$country'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($row=mysql_fetch_array($res))
			{
				$country=$row["LABEL"];
			}
		}
		$u_p = array($caste,$age,$country,$dtofbirth);
		$zz=sizeof($u_p);
		$i=0;$k=0;
		for($i=0;$i<$zz;$i++)
			{$u_c[$k]=$newusername.$u_p[$i];$k++;}
		for($i=0;$i<$zz;$i++)
			{$u_c[$k]=$newusername."_".$u_p[$i];$k++;}
		for($i=0;$i<$zz;$i++)
			{$u_c[$k]=$u_p[$i]."_".$newusername;$k++;}
		$i=0;$m=0;
		while($i<5 && $m<$k)
		{
			$ava1=check_username($u_c[$m]);
		        $ava2=check_username_jprofile($u_c[$m]);
			$ava3=isvalid_username($u_c[$m]);
			$username_available1=valid_new_username($u_c[$m]);//added by lavesh

			//added by sriram
			$check_username_email = check_username_email($profileid,$u_c[$m]);
			$check_obscene_word = check_obscene_word($u_c[$m]);
			$check_for_continuous_numerics = check_for_continuous_numerics($u_c[$m],"");
			$check_for_intelligent_usage = check_for_intelligent_usage($u_c[$m]);
			//added by sriram

			if($ava1==0 && $ava2==0 && $ava3==0 && $username_available1==1 && !$check_username_email && !$check_obscene_word && !$check_for_continuous_numerics && !$check_for_intelligent_usage)
			{
				$suggestnames[$i]=$u_c[$m];
				$i++;
			}
			$m++;
		}
		$smarty->assign("suggestnames",$suggestnames);
	}
	$smarty->assign("newusername",$newusername);
	//$smarty->assign("username_available",$username_available);
	return $username_available;
}

//Added By lavesh
function valid_new_username($newusername)
{
 	$sql="SELECT COUNT(*) as cnt FROM newjs.CUSTOMISED_USERNAME WHERE NEW_USERNAME='$newusername'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row=mysql_fetch_array($res);

	if($row['cnt']>0)
                $username_available1=0;
        else
                $username_available1=1;

	return($username_available1);
}
//Ends Here.

?>
