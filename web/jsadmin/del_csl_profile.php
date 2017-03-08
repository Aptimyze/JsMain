<?php
/******************************************************************************************************************
file        : del_csl_profile.php
Description : script to delete comma-separated list of profiles
Created By  : Neha Verma
Created On  : 13 May 2009
******************************************************************************************************************/

include("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	$smarty->assign("user",$name);
	$smarty->assign("cid",$cid);
	$smarty->assign("criteria",$criteria);
	if($search)
	{
		if($list)
		{
			
			$list_arr=explode(",",$list);
			$count=count($list_arr);
			if($criteria=='USERNAME')
			{
				for($i=0;$i<$count;$i++)
				{
					$new_arr[]="'".trim($list_arr[$i])."'";
					$smarty->assign("err",$err);
				}
				$new_list=implode(",",$new_arr);
			}
			else
			{
				for($i=0;$i<$count;$i++)
				{
					if(!is_numeric(trim($list_arr[$i])))
					{
						$err="Please enter proper value of profileids";
						$smarty->assign("err",$err);
						$smarty->display("del_csl_profiles.htm");
						die;
					}
				}
				$new_list=$list;
			}

			$sql="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND $criteria IN ($new_list) ";
			$res=mysql_query_decide($sql) or die($sql.mysql_error()); 
			$i=0;
			while($row=mysql_fetch_array($res))
			{
				$pid=$row['PROFILEID'];
				$uname=$row['USERNAME'];
				$res_arr[$i]['uname']=$uname;
				$res_arr[$i]['pid']=$pid;
				$res_arr[$i]['checksum']=md5($pid)."i".$pid;
				$i++;
			}
			if($i==0)
			{
				$err="User(s) not found";
	                        $smarty->assign("err",$err);
			}
			$smarty->assign('res_arr',$res_arr);
		}
		else
		{
			$err="Please enter users to delete";
			$smarty->assign("err",$err);
		}

	}
	elseif($delete)
	{
		$count=count($users_arr);
		if($count)
		{
			$jprofileObj =JProfileUpdateLib::getInstance();
			while($count)
			{
				$count--;
				$details=explode(":",$users_arr[$count]);
				$profile=$details[0];
				$username=$details[1];

				$profileDeleteObj = new PROFILE_DELETE_LOGS();
				$startTime = date('Y-m-d H:i:s');
		      	$arrDeleteLogs = array(
		          'PROFILEID' => $profile,
		          'DELETE_REASON' => $reason,
		          'SPECIFY_REASON' => $comments,
		          'USERNAME'  => $username,
		          'CHANNEL' => 'back(del_csl)',
		          'START_TIME' => $startTime,
		          'INTERFACE' => 'B',
		      );

		      $profileDeleteObj->insertRecord($arrDeleteLogs);
		      
						/*$sql2="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID=$profile";
				mysql_query_decide($sql2) or die(logError($sql2,$db));*/
	                        $extraStr ="PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0";
        	                $jprofileObj->updateJProfileForBilling('',$profile,'PROFILEID',$extraStr);

				$tm = date("Y-M-d");
				$sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($profile,'$username','$reason','$comments','$name','$tm')";
				mysql_query_decide($sql) or die(logError($sql,$db));
				if($ins_str)
					$ins_str.=",(".$profile.")";
				else
					$ins_str="(".$profile.")";
			}
			$sql_ins="REPLACE INTO jsadmin.DEL_STATUS (PROFILEID) VALUES $ins_str";
			mysql_query_decide($sql_ins) or die(logError($sql_ins,$db));
			$path = $_SERVER['DOCUMENT_ROOT']."/jsadmin/del_profilelist_bg.php > /dev/null &";
                        $cmd = JsConstants::$php5path." -q ".$path;
                        //$cmd = "php -q ".$path;
                        passthru($cmd);
			$err="Selected profile(s) have been deleted";

			$arrDeleteLogs = array(
        'END_TIME' => date('Y-m-d H:i:s'),
        'COMPLETE_STATUS' => 'Y',
    );
    $profileDeleteObj->updateRecord($profile, $startTime, $arrDeleteLogs);
			
		}
		else
			$err="Please select user(s) to delete";
		$smarty->assign("err",$err);
	}
		
	$smarty->display("del_csl_profiles.htm");
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
