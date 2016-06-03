<?php
include("connect.inc");
include("../profile/thumb_identification_array.inc");

$db=connect_db();

if(authenticated($cid))
{
	if($searchProfile)
	{
		$userName1=trim($userName1);
		$userName2=trim($userName2);
		$smarty->assign("userName1",$userName1);
		$smarty->assign("userName2",$userName2);
		if($userName1 && $userName2)
		{
			$sql="SELECT PROFILEID,GENDER,USERNAME,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME FROM newjs.JPROFILE WHERE USERNAME IN('$userName1','$userName2')";
			$res=mysql_query_decide($sql) or die("Error while fetchin user info    ".mysql_error_js());
			if(mysql_num_rows($res)<2)
				$smarty->assign("message","One or both usernames is invalid");
			else
			{
				while($row=mysql_fetch_assoc($res))
				{
					if($row["USERNAME"]==$userName1)
					{
						$profileid1=$row["PROFILEID"];
						$trend1[]=calculate_user_trend($row);
						$gender1=$row["GENDER"];
					}
					if($row["USERNAME"]==$userName2)
					{
						$profileid2=$row["PROFILEID"];
						$trend2[]=calculate_user_trend($row);
						$gender2=$row["GENDER"];
					}
				}
				$data["PROFILEID"]=$profileid1;
				$data["GENDER"]=$gender1;
				$forwardScore=getting_reverse_trend($trend2,0);	
				if(!$forwardScore)
					$forwardScore="No Score available";
				$data["PROFILEID"]=$profileid2;
				$data["GENDER"]=$gender2;
				$reverseScore=getting_reverse_trend($trend1,0);
				if(!$reverseScore)
					$reverseScore="No Score available";
				$message="Score of $userName2 against $userName1's trends : $forwardScore";
				$message.="<br>";
				$message.="Score of $userName1 against $userName2's trends : $reverseScore";
				$smarty->assign("message",$message);
			}
			
		}
		else
			$smarty->assign("message","Please enter two usernames!");
	}
	$smarty->assign("cid",$cid);
	$smarty->display("check_profiles_score.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
