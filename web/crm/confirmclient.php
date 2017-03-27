<?php

include("connect.inc");
include("../billing/comfunc_sums.php");
include ("display_result.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
$sno=1;
$db = connect_db();
$db_slave = connect_rep();
if(authenticated($cid))
{
	$name= getname($cid);
	$centre_label=get_centre($cid);
	$privilage=explode('+',getprivilage($cid));
	$serviceObj = new Services;
	
	// walkin or admin
	if(in_array('IUI',$privilage) || in_array('IA',$privilage))
	{
		$user_type='admin';
	}
	// outbound
	elseif(in_array('IUO',$privilage))
	{
		$user_type='outbound';
	}
	// rajeev
	else
	{
		$user_type='rajeev';
	}

	if($centre_label!="HO")
	{	
		$sql="SELECT VALUE from incentive.BRANCH_CITY where UPPER(LABEL) ='".strtoupper($centre_label)."'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql,$db_slave));
		$centre=$myrow['VALUE'];
	}
	else
		$centre="HO";

	if($Confirm && $FLAG==1)
	{
		$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,REF_ID,PREFIX_NAME,LANDMARK) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,'$id',PREFIX_NAME,LANDMARK FROM incentive.PAYMENT_COLLECT where ID='$id'";
                mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());

		$sql="UPDATE incentive.PAYMENT_COLLECT set CONFIRM='Y', ENTRYBY='$name',ENTRY_DT=now(),COMMENTS = '$new_comment',COURIER_TYPE='$courier_type', ACC_REJ_MAIL_BY='$name' where ID='$id'";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		

		echo "<br>";
		echo "<font color=\"blue\">$user</font> has been confirmed for payment";
		$flag=0;
	}
	else
	{
		$i=1;
		if($name == 'jayaprabha')
                {
                        $sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE IN_REGION='S'";
                        $result=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                                $ar_branch[]=$myrow['VALUE'];

                         if(count($ar_branch)>=1)
                                $ar=implode("','",$ar_branch);

                        $sql="(SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.BRANCH_CITY,incentive.PAYMENT_COLLECT LEFT JOIN incentive.MAIN_ADMIN ON incentive.PAYMENT_COLLECT.PROFILEID = incentive.MAIN_ADMIN.PROFILEID WHERE incentive.MAIN_ADMIN.PROFILEID IS NULL AND CONFIRM = '' AND PAYMENT_COLLECT.CITY IN ('$ar') AND DISPLAY <>'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ) ";
                        $sql.="UNION (SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.BRANCH_CITY,incentive.PAYMENT_COLLECT LEFT JOIN incentive.MAIN_ADMIN ON incentive.PAYMENT_COLLECT.PROFILEID = incentive.MAIN_ADMIN.PROFILEID WHERE incentive.MAIN_ADMIN.ALLOTED_TO='$name' AND CONFIRM = '' AND PAYMENT_COLLECT.CITY IN ('$ar') AND DISPLAY <>'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE )";
                }
		elseif($user_type=='outbound')
		{
			$sql = "SELECT AR_BRANCH FROM incentive.ARAMEX_BRANCHES WHERE 1";
			$res = mysql_query_decide($sql,$db_slave) or die($sql.mysql_error_js());
			while($row = mysql_fetch_array($res))
				$arm_city_arr[] = $row["AR_BRANCH"];

			if(is_array($arm_city_arr))
				$arm_city_str = implode("','",$arm_city_arr);

			unset($arm_city_arr);

			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = '$name'";
			$res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
			while($row = mysql_fetch_array($res))
				$profileid_arr[] = $row['PROFILEID'];

			$profileid_count = count($profileid_arr);
			$outer_loop = ($profileid_count/100) + 1;
			$inner_loop = 0;
			for($i=0;$i<$outer_loop;$i++)
			{
				for($k=$inner_loop;$k<$inner_loop+100;$k++)
				{
					if($profileid_arr[$k])
						$temp_profileid_arr[] = $profileid_arr[$k];
				}
				if(is_array($temp_profileid_arr))
				{
					$profileid_str = @implode(",",$temp_profileid_arr);

					if($profileid_str)
					{
						$sql_jp = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN($profileid_str) AND CITY_RES IN('$arm_city_str')";
						$res_jp = mysql_query_decide($sql_jp,$db_slave) or die($sql_jp.mysql_error_js());
						while($row_jp = mysql_fetch_array($res_jp))
							$final_profileid_arr[] = $row_jp['PROFILEID'];
					}
				}
				$inner_loop+=100;
				unset($profileid_str);
				unset($temp_profileid_arr);
			}

			if(is_array($final_profileid_arr))
				$profileid_str = implode(",",$final_profileid_arr);

			unset($final_profileid_arr);

			if($profileid_str =="")
				$do_not_run_query=1;
			$sql="SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,PAYMENT_COLLECT.COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PAYMENT_COLLECT.PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.PAYMENT_COLLECT,incentive.MAIN_ADMIN,incentive.BRANCH_CITY where CONFIRM='' and DISPLAY <> 'N' and incentive.PAYMENT_COLLECT.PROFILEID=incentive.MAIN_ADMIN.PROFILEID and incentive.MAIN_ADMIN.ALLOTED_TO='$name' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE AND incentive.PAYMENT_COLLECT.PROFILEID IN ($profileid_str)";

			unset($profileid_str);
		}

		elseif($user_type=='admin')
		{
			if($name=='sharmil')
				$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE like('GU%') or VALUE like('MH%') or VALUE like('MP%')";
			else
				$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$centre'";
                        $result=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $ar_branch[]=$myrow['VALUE'];
                        }

			 if(count($ar_branch)>=1)
                                $ar=implode("','",$ar_branch);

	/**********Code added by Aman to show the records alloted to those who have left*****************/
		
			$sql="SELECT UPPER(LABEL) FROM incentive.BRANCH_CITY WHERE VALUE IN('$ar')";
			$result=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $branch_label[]=$myrow['LABEL'];
                        }
			if(count($branch_label)>=1)
                                $br_lbl=implode("','",$branch_label);

			$sql_sel="SELECT USERNAME FROM jsadmin.PSWRDS WHERE UPPER(CENTER) IN ('$br_lbl') AND PRIVILAGE LIKE  '%IUO%' AND ACTIVE='N'";
			$res_sel=mysql_query_decide($sql_sel,$db_slave) or die(mysql_error_js());
                        while($row=mysql_fetch_array($res_sel))
                        {
                                $usernamearr[]=$row['USERNAME'];
                        }
			$count_diff=0;
 			if(count($usernamearr)>0)
			{
				$user_str=implode("','",$usernamearr);
				$sql_cnt_dif="SELECT COUNT(*) from incentive.PAYMENT_COLLECT,incentive.MAIN_ADMIN,incentive.BRANCH_CITY where CONFIRM='' and PAYMENT_COLLECT.CITY IN ('$ar') and DISPLAY <> 'N' and incentive.PAYMENT_COLLECT.PROFILEID=incentive.MAIN_ADMIN.PROFILEID and incentive.MAIN_ADMIN.ALLOTED_TO IN('$user_str') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
                        	$result_diff=mysql_query_decide($sql_cnt_dif,$db_slave) or die(mysql_error_js());
                        	$myrow_diff = mysql_fetch_row($result_diff);
                        	$count_diff = $myrow_diff[0];
			}

			if(in_array('IUO',$privilage))
			{
				$sql="SELECT COUNT(*) from incentive.PAYMENT_COLLECT,incentive.MAIN_ADMIN,incentive.BRANCH_CITY where CONFIRM='' and PAYMENT_COLLECT.CITY IN ('$ar') and DISPLAY <> 'N' and incentive.PAYMENT_COLLECT.PROFILEID=incentive.MAIN_ADMIN.PROFILEID and incentive.MAIN_ADMIN.ALLOTED_TO='$name' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
				$result=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
				$myrow = mysql_fetch_row($result);
				$count_out = $myrow[0];
			}
             /*************************end of code portion***********************************************************/
			
                        $sql="(SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.BRANCH_CITY,incentive.PAYMENT_COLLECT LEFT JOIN incentive.MAIN_ADMIN ON incentive.PAYMENT_COLLECT.PROFILEID = incentive.MAIN_ADMIN.PROFILEID WHERE incentive.MAIN_ADMIN.PROFILEID IS NULL AND CONFIRM = '' AND PAYMENT_COLLECT.CITY IN ('$ar') AND DISPLAY <>'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ) ";
			//Vibhor
			$sql.="UNION (SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.BRANCH_CITY,incentive.PAYMENT_COLLECT LEFT JOIN incentive.MAIN_ADMIN ON incentive.PAYMENT_COLLECT.PROFILEID = incentive.MAIN_ADMIN.PROFILEID WHERE incentive.MAIN_ADMIN.ALLOTED_TO='$name' AND CONFIRM = '' AND PAYMENT_COLLECT.CITY IN ('$ar') AND DISPLAY <>'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE )";
			//end
		/**********code for alloted to those who have left*******************/
			if($count_diff>0)
			{
				$sql.=" UNION (SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,PAYMENT_COLLECT.COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PAYMENT_COLLECT.PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.PAYMENT_COLLECT,incentive.MAIN_ADMIN,incentive.BRANCH_CITY where CONFIRM='' and PAYMENT_COLLECT.CITY IN ('$ar') and DISPLAY <> 'N' and incentive.PAYMENT_COLLECT.PROFILEID=incentive.MAIN_ADMIN.PROFILEID and incentive.MAIN_ADMIN.ALLOTED_TO IN ('$user_str') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE)";
			}
			if(in_array('IUO',$privilage))
			{
				$sql.="UNION (SELECT PAYMENT_COLLECT.ID,PAYMENT_COLLECT.PROFILEID,PAYMENT_COLLECT.USERNAME,PAYMENT_COLLECT.NAME,PAYMENT_COLLECT.EMAIL,PAYMENT_COLLECT.PHONE_RES,PAYMENT_COLLECT.PHONE_MOB,PAYMENT_COLLECT.COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PAYMENT_COLLECT.PREF_TIME,PAYMENT_COLLECT.COURIER_TYPE, SERVICE,PAYMENT_COLLECT.ADDRESS,BRANCH_CITY.LABEL as CITY,PAYMENT_COLLECT.PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.PAYMENT_COLLECT,incentive.MAIN_ADMIN,incentive.BRANCH_CITY where CONFIRM='' and PAYMENT_COLLECT.CITY IN ('$ar') and DISPLAY <> 'N' and incentive.PAYMENT_COLLECT.PROFILEID=incentive.MAIN_ADMIN.PROFILEID and incentive.MAIN_ADMIN.ALLOTED_TO IN ('$name') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE) ";	
			}
		/**************** end of code portion********************************/
                }

		elseif($user_type=='rajeev')
		{
			$sql="SELECT PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,COMMENTS,PAYMENT_COLLECT.ENTRY_DT,PREF_TIME,COURIER_TYPE, SERVICE,ADDRESS,BRANCH_CITY.LABEL as CITY,PIN,PAYMENT_COLLECT.ADDON_SERVICEID,PAYMENT_COLLECT.PREFIX_NAME,PAYMENT_COLLECT.LANDMARK from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='' and  DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
		}	


//		$sql="SELECT PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICES.NAME as SERVICE,ADDRESS,BRANCH_CITY.LABEL as CITY,PIN from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='' and AR_GIVEN='' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";	

		if(!$do_not_run_query)
			$result=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
		if($myrow=@mysql_fetch_array($result))
		{
			do
			{
				//Removing onoffreg profiles
				$pid=$myrow["PROFILEID"];
				/* removed online/offline check
				$sql_jp = "SELECT CRM_TEAM FROM newjs.JPROFILE WHERE PROFILEID=$pid";
				$res_jp = mysql_query_decide($sql_jp) or die($sql_jp.mysql_error_js());
				if($row_jp = mysql_fetch_array($res_jp))
					$source = $row_jp['CRM_TEAM'];
				if($source=='online')
				{
				*/
				//end
					$address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
					$entry_date_time_arr = explode(" ",$myrow["ENTRY_DT"]);
					$entry_date_arr = explode("-",$entry_date_time_arr[0]);
					$entry_date_val = date("M-d-Y",mktime(0,0,0,$entry_date_arr[1],$entry_date_arr[2],$entry_date_arr[0]))." ".$entry_date_time_arr[1];

					if ($pref_time == "0000-00-00 00:00:00" )
						$date_val = "Not specified";
					else
					{	
						$date_time_arr = explode(" ",$myrow["PREF_TIME"]);
						$date_arr = explode("-",$date_time_arr[0]);
						$date_val = date("M-d-Y",mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]))." ".$date_time_arr[1];
					}
					$addon='';
					if(strstr($myrow["ADDON_SERVICEID"],'B'))
						$addon.=' Bold-listing ';
					if(strstr($myrow["ADDON_SERVICEID"],'H'))        
						$addon.=' Horroscope ';
					if(strstr($myrow["ADDON_SERVICEID"],'K'))
						$addon.=' Kundli ';
					if(strstr($myrow["ADDON_SERVICEID"],'M'))
						$addon.=' Matri-Profile ';
				
					$services=$serviceObj->getServiceName($myrow["SERVICE"]);
					$service_names='';
					foreach($services as $k=>$v)
					{
						foreach($v as $k1=>$v1)
						{
							if($service_names=='')
								$service_names.=$v1;
							else
								$service_names.=",".$v1;
						}
					}
					$values[] = array("sno"=>$sno,
							  "id"=>$myrow["ID"],
							  "profileid"=>$myrow["PROFILEID"],
							  "username"=>$myrow["USERNAME"],
							  "name"=>$myrow["NAME"],
							  "email"=>$myrow["EMAIL"],
							  "phone_res"=>$myrow["PHONE_RES"],
							  "phone_mob"=>$myrow["PHONE_MOB"],
							  "service"=>$service_names,
							  "addon"=>$addon,	
							  "address"=>$address,
							  "comments"=>$myrow["COMMENTS"],
							  "courier"=>$myrow["COURIER_TYPE"],
							  "entry_dt_val"=>$entry_date_val,	
							  "pref_time"=>$date_val,
							  "prefix_name"=>$myrow['PREFIX_NAME'],
							  "pincode"=>$myrow['PIN'],
							  "landmark"=>$myrow['LANDMARK']			
							 );
					$sno++;
				//}
			}while($myrow=mysql_fetch_array($result));
		}
	
		$smarty->assign("ROW",$values);

		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("confirmclient.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

?>
