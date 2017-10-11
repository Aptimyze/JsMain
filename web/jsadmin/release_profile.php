<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        $name = getname($cid);
	$alloted =true;	
        $privilage = explode("+",getprivilage($cid));
        if(in_array("SLHD",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage))
                $releaseAll =true;
        else
                $releaseAll =false;

        if($Submit)
        {
		$sql_main="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME ='$release_user'";
                $res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
                if($row_main=mysql_fetch_array($res_main))
                        $pid=$row_main['PROFILEID'];

		if(!$releaseAll){
			$sqlAlloted ="select PROFILEID from incentive.MAIN_ADMIN where PROFILEID='$pid' and ALLOTED_TO='$name'";
			$resAlloted=mysql_query_decide($sqlAlloted) or die("$sqlAlloted".mysql_error_js());
			if(mysql_num_rows($resAlloted))
				$alloted =true;
			else
				$alloted =false;
			
		}

		$del_ma=0;
		$id=0;
		if($pid && $alloted)
		{
			$sql="DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID=$pid";
			$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(mysql_affected_rows())
				$del_ma++;
			if($del_ma)
			{
				$sql="SELECT ID,ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID=$pid ORDER BY ID DESC LIMIT 1";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$id=$row['ID'];
					$alloted_to=$row['ALLOTED_TO'];
				}
				if($id)         
		                {        
                		        $sql="DELETE FROM incentive.CRM_DAILY_ALLOT WHERE ID=$id";
		                        $res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		                }
				$sql1="SELECT ID,ALLOTED_TO FROM incentive.MANUAL_ALLOT WHERE PROFILEID=$pid ORDER BY ID DESC LIMIT 1";
                                $res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
                                if($row1=mysql_fetch_array($res1))
				{
                                        $id1=$row1['ID'];
					$alloted_to1=$row1['ALLOTED_TO'];
				}
                                if($id1 && $alloted_to==$alloted_to1)
                                {
                                        $sql11="DELETE FROM incentive.MANUAL_ALLOT WHERE ID=$id1";
                                        $res11=mysql_query_decide($sql11) or die("$sql11".mysql_error_js());
                                }
				$sql2="SELECT ID,ALLOTED_TO FROM incentive.INBOUND_ALLOT WHERE PROFILEID=$pid ORDER BY ID DESC LIMIT 1";
                                $res2=mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
                                if($row2=mysql_fetch_array($res2))
				{
                                        $id2=$row2['ID'];
					$alloted_to2=$row2['ALLOTED_TO'];
				}
                                if($id2 && $alloted_to==$alloted_to2)
                                {
                                        $sql22="DELETE FROM incentive.INBOUND_ALLOT WHERE ID=$id2";
                                        $res22=mysql_query_decide($sql22) or die("$sql22".mysql_error_js());
                                }

			}
		}
		if($del_ma)
			$msg ="Profile released.<br><a href=\"$SITE_URL/jsadmin/release_profile.php?name=$user&cid=$cid\">Next</a>";
		else
			$msg ="This profile could not be released. Please email your supervisor to get this profile released. <br><a href=\"$SITE_URL/jsadmin/release_profile.php?name=$user&cid=$cid\">Back</a>";
			//$msg ="Either you have entered invalid username or the profile is already released.<br><a href=\"$SITE_URL/jsadmin/release_profile.php?name=$user&cid=$cid\">Back</a>"
;
		$smarty->assign("msg","$msg");
	}		
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        unset($Submit);
        $smarty->display("release_profile.htm");
}
?>
