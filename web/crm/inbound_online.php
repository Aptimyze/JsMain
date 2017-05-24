<?php
include_once("connect.inc");
if (authenticated($cid))
{
        $privilage = explode("+",getprivilage($cid));
	if(!in_array("P",$privilage)){
        	$msg="Request Timed Out";
        	$smarty->assign("MSG",$msg);
        	$smarty->display("jsadmin_msg.tpl");
		die();
	}

        $now=time();
        $now+=60*60;
        $today=date("Y-m-d",$now)." 23:59:59";
        $name= getname($cid);
	$sql ="SELECT CENTER FROM jsadmin.PSWRDS where USERNAME='$name'";
	$resulty=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$row=mysql_fetch_array($resulty);
	
	$sql ="SELECT C.PROFILEID as ID,C.ANALYTIC_SCORE AS score FROM userplane.users A LEFT JOIN incentive.MAIN_ADMIN_POOL C ON A.userID = C.PROFILEID LEFT JOIN incentive.MAIN_ADMIN B ON A.userID = B.PROFILEID WHERE B.PROFILEID IS NULL ";
	$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$i=0;
	while($myrow = mysql_fetch_array($result))
	{
		unset($result1);
		$sql_v="SELECT COUNT(*) AS CNT FROM jsadmin.UNALLOTED_FREE_ONLINE_VIEWED WHERE VIEWED='$myrow[ID]'";
		$res_v=mysql_query_decide($sql_v,$db) or die(mysql_error($db));
		$row_v=mysql_fetch_array($res_v);
		if($row_v["CNT"]>0)
			$old=1;
		else
			$old=0;
		// removed online/offline check
		$sql2="SELECT ALTERNATE_NUMBER FROM incentive.PROFILE_ALTERNATE_NUMBER WHERE PROFILEID='$myrow[ID]'";
                        $result2=mysql_query_decide($sql2) or die(mysql_error_js());
                        $myrow2= mysql_fetch_array($result2);
                        if($myrow2['ALTERNATE_NUMBER'])
                                $alternatenumber=$myrow2['ALTERNATE_NUMBER'];
                        else
                                $alternatenumber="-";		

		$SQL="SELECT CITY_RES AS city,USERNAME AS user,SUBSCRIPTION AS SUB,PROFILEID,RELATION,FAMILY_INCOME,MTONGUE,INCOME,ENTRY_DT,PHONE_RES,PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$myrow[ID]'";
		$result1=mysql_query_decide($SQL,$db) or die(mysql_error($db));
		$RES=mysql_fetch_array($result1);
	
		if($RES['SUB'] == '' && mysql_num_rows($result1)>0)
                {
			if(strstr($RES['city'],'TN')||strstr($RES['city'],'KA')||strstr($RES['city'],'AP')||strstr($RES['city'],'KE'))
			{
				if($name=='jayaprabha')
				{  
					setdata($RES,$alternatenumber,$i);
					$i++;
					continue;				
				}
				else
				{
					continue;
				}

			}
			if(($row['CENTER']=='PUNE')&&($RES['city']!='MH08'))
			{
				continue;
			}
			else if(($row['CENTER']=='MUMBAI')&&(!strstr($RES['city'],'MH')||$RES['city']=='MH08'))
			{			
				continue;
			}
			else if(($row['CENTER']=='NOIDA')&&(strstr($RES['city'],'MH'))&&($name=='jayaprabha'))
			{
				continue;
			}
			else if(($row['CENTER']=='NOIDA')&&($name=='jayaprabha'))
			{
				continue;
			}	
			setdata($RES,$alternatenumber,$i);
			$i++;
		}
	}
        $smarty->assign("cid",$cid);
        $smarty->assign("score",$arr);
        $smarty->assign("name",$name);
        $smarty->display("inbound_online.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}


	function setdata($RES,$alternatenumber,$i)
		{
			global $arr;
 			$arr[$i]['seq']=$i+1;
                        $arr[$i]['score']=$myrow['score'];
                        $value = $RES['city'];
                        $sqlcn = "select LABEL from newjs.CITY_NEW WHERE VALUE='$value'";
                        $rescn = mysql_query_decide($sqlcn,$db) or die(mysql_error($db));
                        $myrowcn= mysql_fetch_array($rescn);
                        $arr[$i]['city']=$myrowcn['LABEL'];
                        include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
                        $value1 = $RES['RELATION'];
                        $arr[$i]['relation']=$RELATIONSHIP[$value1];
                        $arr[$i]['user']=$RES['user'];
                        $arr[$i]['pid']=$RES['PROFILEID'];
                        $arr[$i]['alternate_no']=$alternatenumber;
                        $arr[$i]['status']=$old;
			$income=label_select('INCOME',$RES['INCOME']);
			$arr[$i]['income']=$income['LABEL'];       
                        $fam_income=label_select('INCOME',$RES['FAMILY_INCOME']);
                        $arr[$i]['family_income']=$fam_income['LABEL'];
                        $moth_tong=label_select('MTONGUE',$RES['MTONGUE']);
                        $arr[$i]['mother_tongue']=$moth_tong['LABEL'];
                        $entry_date=JSstrToTime($RES['ENTRY_DT']);
                        $arr[$i]['entry_date']=date("d-m-Y",$entry_date);
                        if($RES['PHONE_RES'])
                        	$ph_res=$RES['PHONE_RES'];
                        else
                        	$ph_res="-";
                        if($RES['PHONE_MOB'])
                        	$ph_mob=$RES['PHONE_MOB'];
                        else
                        	$ph_mob="-";
                        $arr[$i]["res_no"]=$ph_res;
                        $arr[$i]["mob_no"]=$ph_mob;
		}
?>

