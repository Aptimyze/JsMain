<?php
include("connect.inc");
include("display_common.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");

$db 		=connect_slave();
$db_master 	=connect_db();
$moduleLiveDate ='2012-11-28 00:00:00';
//$PAGELEN	=10;
//$totalCount 	=0;
$checkRegCnt	=array();

if(authenticated($cid))
{
	$name = getname($cid);
	if($name == 'nisha.k')
		$rem = 0;
        elseif($name == 'pankaj.dubey')
                $rem = 1;
        elseif($name == 'sagar.m')
                $rem = 2;
        elseif($name == 'deepika.s')
                $rem = 3;
        elseif($name == 'manpreet.g')
                $rem = 4;

        $privilage = explode("+",getprivilage($cid));
        if(!in_array("FTAReg",$privilage) && !in_array("TRNG",$privilage) && !in_array("P",$privilage) && !in_array("MG",$privilage)){
                $msg="Request Timed Out";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
                die();
	}

	$sql ="select j.PROFILEID from newjs.JPROFILE j where j.ENTRY_DT>='$moduleLiveDate' AND (SEC_SOURCE!='C' AND SOURCE!='onoffreg')";
	$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($result))
	{
		$pid =$row['PROFILEID'];
		$check_pid =$pid%5;
		if($check_pid==$rem)
		{	
			$sqlc1 ="SELECT PROFILEID from MIS.REG_COUNT WHERE PAGE5='Y' AND PROFILEID='$pid'";
			$resultc1 = mysql_query_decide($sqlc1) or die("$sqlc1".mysql_error_js());
			if(mysql_num_rows($resultc1)){
				$checkRegCnt[$pid] ='Y';
				$totalCount++;
			}
		}
	}

	// Pagination start
/*	if(!$j)
	        $j=1;
	$start=$j-1;
	$start=$start*$PAGELEN;
	$serialNo =$start+1;	
	pagination($j,$totalCount, $PAGELEN,"");
	$curPage="photoFollowup.php?cid=$cid";
	$smarty->assign("CUR_PAGE",$curPage);*/
	// Pagination ends

	$sql ="select PROFILEID, USERNAME, PHONE_MOB, PHONE_WITH_STD, ENTRY_DT, GENDER, RELATION, HAVEPHOTO from newjs.JPROFILE where ENTRY_DT>='$moduleLiveDate' AND (SEC_SOURCE!='C' AND SOURCE!='onoffreg') ORDER BY ENTRY_DT DESC";
	$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	if(mysql_num_rows($result))
	{
		$i=0;
		while($row=mysql_fetch_array($result))
		{
			$profileid =$row['PROFILEID'];
			$check_pid =$profileid%5;
			if($check_pid==$rem)
			{
				if($checkRegCnt[$profileid]=='Y')
				{
	                	       	$sql1 ="select ALT_MOBILE,ALT_MOBILE_ISD from newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
        	        	       	$result1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
        	        	       	$row1=mysql_fetch_array($result1);
        	        	       	$mobile2 =$row1['ALT_MOBILE'];
        	        	       	if($mobile2 && $row1['ALT_MOBILE_ISD'])
        	        	       	        $mobile2 =$row1['ALT_MOBILE_ISD']."-".$mobile2;
					$mobile1 =$row['PHONE_MOB'];
					$landline =$row['PHONE_WITH_STD'];	
					$users[$i]["PHONE_NUMBER"] 	=$mobile1;
					if($mobile2)
						$users[$i]["PHONE_NUMBER"] .=",".$mobile2;
					if($landline)
						$users[$i]["PHONE_NUMBER"] .=",".$landline;

					$relation =$RELATIONSHIP_DROP[$row['RELATION']];
					$users[$i]["RELATION"] 	=$relation;
	
					$photoVal =$row['HAVEPHOTO'];
					$users[$i]["PHOTO_UPLOAD"] ='No';
					$users[$i]["PHOTO_LIVE"] ='No';
					if($photoVal=='U')
						$users[$i]["PHOTO_UPLOAD"] ='Yes';
					elseif($photoVal =='Y')
						$users[$i]["PHOTO_LIVE"] ='Yes';
				
					$users[$i]["GENDER"] 	=$row['GENDER'];
					$users[$i]["ENTRY_DT"]	=getIST($row['ENTRY_DT']);
					$users[$i]["USERNAME"] 	=$row['USERNAME'];
					$users[$i]["PROFILEID"] =$profileid;
					$users[$i]["S_NO"] 	=$serialNo;
					$serialNo++;
					$i++;
				}
			}
		}
		$smarty->assign('lusers',$users);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display('photoFollowup.htm');
	}
	else{
		$smarty->assign("NO_RECORD",'Y');
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display('photoFollowup.htm');
	}
}
else
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");

}
?>
