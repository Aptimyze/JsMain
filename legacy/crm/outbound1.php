<?php
/**
*       Filename        :       outbound1.php.php
*       Created by      :       Abhinav
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
include ("display_result.inc");
include ($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include ($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
$mysqlObj = new Mysql;
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
	$date_after_30days = date('Y-m-d',time()+30*86400);
	$orderby="CONTACTS_ACC";

	if($flag=='O')
	{
		$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS = 'F' AND ORDERS='N' ";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		$sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.ALTERNATE_NO,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND ORDERS='N' AND STATUS = 'F' ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else 
				$ph_res="-";
			if($myrow['ALTERNATE_NO'])
			{
				$ph_res.=",".$myrow['ALTERNATE_NO'];
			}
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else 
				$ph_mob="-";
			$temp_email=explode("@",$myrow["EMAIL"]);
			$email=$temp_email[0]."@xxx.com";
			
			$ordersusersarr[] = array("SNO"=> $sno,
						"NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
						"USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
						"EMAIL" => $email,
						"PROFILEID" => $myrow['PROFILEID'],
						"RES_NO" => $ph_res,	
						"MOB_NO" => $ph_mob,
						"CITY_INDIA" => $city['LABEL'],
						"AGE" => $myrow['AGE'],
						"GENDER" => $myrow['GENDER'],
						"ENTRY_DT" => $myrow['ENTRY_DT'],
						"TIMES_TRIED" => $myrow['TIMES_TRIED'],
						"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
			$sno++;
		}
		$smarty->assign("ordersusersarr",$ordersusersarr);
	}
	elseif($flag=='OF')
	{
		$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name'  AND STATUS='F' AND ORDERS='Y' AND ";
		if($getold || ($yy1 && $mm1 && $dd1 && $yy2 && $mm2 && $dd2))
		{
			if($getold)
			{
				list($st_dt,$end_dt)=explode("--",$getold);
				list($yy1,$mm1,$dd1)=explode("-",$st_dt);
				list($yy2,$mm2,$dd2)=explode("-",$end_dt);
				$st_dt.=" 00:00:00";
				$end_dt.=" 23:59:59";
			}
			else
			{
				$st_dt=$yy1."-".$mm1."-".$dd1." 00:00:00";
				$end_dt=$yy2."-".$mm2."-".$dd2." 23:59:59";
				$getold=$yy1."-".$mm1."-".$dd1."--".$yy2."-".$mm2."-".$dd2;
			}
			$sql.=" FOLLOWUP_TIME BETWEEN '$st_dt' AND '$end_dt'";
		}
		else
			$sql.=" FOLLOWUP_TIME<='$today'";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		$sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.ALTERNATE_NO,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name' AND ORDERS='Y' AND STATUS='F' AND ";
		if($getold || ($yy1 && $mm1 && $dd1 && $yy2 && $mm2 && $dd2))
		{
			if($getold)
			{
				list($st_dt,$end_dt)=explode("--",$getold);
				list($yy1,$mm1,$dd1)=explode("-",$st_dt);
				list($yy2,$mm2,$dd2)=explode("-",$end_dt);
				$st_dt.=" 00:00:00";
				$end_dt.=" 23:59:59";
			}
			else
			{
				$st_dt=$yy1."-".$mm1."-".$dd1." 00:00:00";
				$end_dt=$yy2."-".$mm2."-".$dd2." 23:59:59";
				$getold=$yy1."-".$mm1."-".$dd1."--".$yy2."-".$mm2."-".$dd2;
			}
			$sql.=" FOLLOWUP_TIME BETWEEN '$st_dt' AND '$end_dt'";
		}
		else
			$sql.=" FOLLOWUP_TIME<='$today'";
		$sql.=" ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else 
				$ph_res="-";
			if($myrow['ALTERNATE_NO'])
			{
				$ph_res.=",".$myrow['ALTERNATE_NO'];
			}
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else 
				$ph_mob="-";
			$temp_email=explode("@",$myrow["EMAIL"]);
			$email=$temp_email[0]."@xxx.com";
			
			
			$sql1 =" SELECT FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID=$myrow[PROFILEID]  AND ORDERS='Y'";
			$result1= mysql_query_decide($sql1) or die(mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
			$follow=strftime("%Y-%m-%d",JSstrToTime($myrow1['FOLLOWUP_TIME']));

			$followordersusersarr[] = array("SNO"=> $sno,
					"NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
					"USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
					"EMAIL" => $email,
					"PROFILEID" => $myrow['PROFILEID'],
					"RES_NO" => $ph_res,	
					"MOB_NO" => $ph_mob,
					"CITY_INDIA" => $city['LABEL'],
					"AGE" => $myrow['AGE'],
					"GENDER" => $myrow['GENDER'],
					"ENTRY_DT" => $myrow['ENTRY_DT'],
					"TIMES_TRIED" => $myrow['TIMES_TRIED'],
					"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
			$sno++;
		}
		$smarty->assign("followordersusersarr",$followordersusersarr);
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2004;
		}

		$smarty->assign("dd1",$dd1);
		$smarty->assign("mm1",$mm1);
		$smarty->assign("yy1",$yy1);
		$smarty->assign("dd2",$dd2);
		$smarty->assign("mm2",$mm2);
		$smarty->assign("yy2",$yy2);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	elseif($flag=='NF')
	{
		$sql ="SELECT PROFILEID , CONTACTS_ACC FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name'  AND STATUS='F' AND ORDERS='' AND ";
		if($getold || ($yy1 && $mm1 && $dd1 && $yy2 && $mm2 && $dd2))
		{
			if($getold)
			{
				list($st_dt,$end_dt)=explode("--",$getold);
				list($yy1,$mm1,$dd1)=explode("-",$st_dt);
				list($yy2,$mm2,$dd2)=explode("-",$end_dt);
				$st_dt.=" 00:00:00";
				$end_dt.=" 23:59:59";
			}
			else
			{
				$st_dt=$yy1."-".$mm1."-".$dd1." 00:00:00";
				$end_dt=$yy2."-".$mm2."-".$dd2." 23:59:59";
				$getold=$yy1."-".$mm1."-".$dd1."--".$yy2."-".$mm2."-".$dd2;
			}
			$sql.=" FOLLOWUP_TIME BETWEEN '$st_dt' AND '$end_dt'";
		}
		else
			$sql.=" FOLLOWUP_TIME<='$today'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($result))
		{
			$pid = $row['PROFILEID'];
			$contactarr[$pid] = $row['CONTACTS_ACC'];
			$arr[]=$row['PROFILEID'];
		}

		unset($pid);

		$profile_cnt=count($arr);
		$num=$profile_cnt/100 + 1;
		$j2=0;
		for($i=0;$i<$num;$i++)
		{
			for($k=$j2;$k<$j2+100;$k++)
			{
				if($arr[$k])
					$temp_arr[]=$arr[$k];
			}
			if($temp_arr)
			{
				$str=implode(",",$temp_arr);
				$sql="SELECT PROFILEID, AGE, SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID IN ($str) AND ACTIVATED IN ('Y','H')";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$pid = $row['PROFILEID'];
					$sub_arr=explode(",",$row['SUBSCRIPTION']);
					if(in_array("F",$sub_arr))
					{
						$sql_paid = "SELECT MAX( ENTRY_DT )  AS PAYMENT_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID = '$pid'";
						$res_paid =  mysql_query_decide($sql_paid) or die("$sql_paid".mysql_error_js());
						$row_paid =  mysql_fetch_array($res_paid);

						list($paymentdt,$temp) = explode(" ",$row_paid['PAYMENT_DT']);
						list($yy,$mm,$dd)=explode("-",$paymentdt);

                				$paymentdt_ts = mktime(0,0,0,$mm,$dd,$yy);

						$today_dt= date("Y-m-d");
                				list($yy,$mm,$dd)=explode("-",$today_dt);
                				$limit_ts = mktime(0,0,0,$mm,$dd,$yy);
                                                                                                                            
                				$days_diff = intval(($limit_ts - $paymentdt_ts)/(24*60*60));

					}
					{
						if ($contactarr[$pid] < 100 && $contactarr[$pid] >= 10)
							$contact = "0".$contactarr[$pid];
						elseif ($contactarr[$pid] < 10 && $contactarr[$pid]>0)
							$contact = "00".$contactarr[$pid];
						elseif ($contactarr[$pid]==0)
							$contact = "000";
							
						if ($defaultsort)
						{
							$last_login_arr[] = $contact."i".$row['PROFILEID'];
						}
						else
						{
							$cpid=$row['PROFILEID'];
                                                        $score_pid_arr[]=$cpid;
                                                        $score_pid_arr1[$cpid]=$contact;
						}
					}
				}
				if(count($score_pid_arr)>0)
				{
				$score_pid_str = implode(",",$score_pid_arr);
                                $sql_score = "SELECT SCORE,PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($score_pid_str)";
                                $res_score = mysql_query_decide($sql_score) or die("$sql_score".mysql_error_js());
                                while($row_score=mysql_fetch_array($res_score))
                                {
                                        $score_pid = $row_score['SCORE'];
                                        $cpid1 = $row_score['PROFILEID'];
                                        if(!$score_pid)
                                                $score_pid  = 0;
                                        if ($score_pid < 100)
                                                $score_pid = "0".$score_pid;
                                        $last_login_arr[]=$score_pid."i".$score_pid_arr1[$cpid1]."i".$cpid1;
                                }
				$last_login_arr_main=$last_login_arr;
                                unset($last_login_arr);
				}
			}
			$j2+=100;
			$str='';
			unset($temp_arr);
		}
		unset($arr);
		if(count($score_pid_arr)>0)
			$last_login_arr=$last_login_arr_main;
		$TOTALREC=count($last_login_arr);
		unset($contactarr);
		if(is_array($last_login_arr))
		{
			rsort($last_login_arr); 
			$j2=$j;
			for($i=$j2;$i<$j2+25;$i++)
			{
				if ($defaultsort)
				{
					list($contacts,$profileid)=explode("i",$last_login_arr[$i]);
				}
				else
				{
					list($score,$contacts,$profileid)=explode("i",$last_login_arr[$i]);
				}

				if($profileid)
					$final_profilearr[]=$profileid;
			}
		}
		unset($last_login_arr);
		$i = 0;
		if(is_array($final_profilearr))
		{
			$str=implode(",",$final_profilearr);
			$sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$temp_email=explode("@",$myrow["EMAIL"]);
				$email=$temp_email[0]."@xxx.com";
														    
				$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");

				$i = array_search($myrow["PROFILEID"],$final_profilearr);

				$followusersarr[$i]["SNO"]=$i + $sno;
				$followusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
				$followusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
				$followusersarr[$i]["EMAIL"]=$email;
				$followusersarr[$i]["PROFILEID"]=$myrow['PROFILEID'];
				$followusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
				$followusersarr[$i]["AGE"]=$myrow['AGE'];
				$followusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
				$followusersarr[$i]["GENDER"]=$myrow['GENDER'];
				$followusersarr[$i]["ENTRY_DT"]=$myrow['ENTRY_DT'];
				$followusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];
			}
			unset($i);
	
			/* SCORE commented
			$sql = "SELECT PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($myrow=mysql_fetch_array($result))
                        {
				if (!$myrow['SCORE'])
					$score = 0;
				else
					$score = $myrow['SCORE'];
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
				$followusersarr[$i]["SCORE"] = $score;
			}
			*/

			$sql =" SELECT PROFILEID, FOLLOWUP_TIME , ALTERNATE_NO,RES_NO,MOB_NO FROM incentive.MAIN_ADMIN WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['RES_NO'])
					$ph_res=$myrow['RES_NO'];
				else
					$ph_res="-";
				if($myrow['ALTERNATE_NO'])
				{
					$ph_res.=",".$myrow['ALTERNATE_NO'];
				}
				if($myrow['MOB_NO'])
					$ph_mob=$myrow['MOB_NO'];
				else
					$ph_mob="-";
														    
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
				$followusersarr[$i]["RES_NO"]=$ph_res;
				$followusersarr[$i]["MOB_NO"]=$ph_mob;
			}
			
			$discountArr =getDiscount($str);
			for($d=0; $d<count($final_profilearr); $d++)	
			{
				$profileid 	=$final_profilearr[$d];
				$dataArr 	=$discountArr[$profileid];
				$discount	=$dataArr['DISCOUNT'];		
				$date		=$dataArr['EDATE'];
				$dateArr	=explode("-",$date);
				$eDate		=$dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
				if($discount)
					$followusersarr[$d]['DISCOUNT'] =$discount."% valid till ".$eDate; 	
			}


		}
		unset($temp_profilearr);
		$smarty->assign("followusersarr",$followusersarr);

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2004;
		}

		$smarty->assign("dd1",$dd1);
		$smarty->assign("mm1",$mm1);
		$smarty->assign("yy1",$yy1);
		$smarty->assign("dd2",$dd2);
		$smarty->assign("mm2",$mm2);
		$smarty->assign("yy2",$yy2);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
	}
	elseif($flag=='N')
	{
		$sql ="SELECT PROFILE_TYPE , PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO='$name' AND HANDLED='N' AND STATUS='N' ORDER BY PROFILE_TYPE ASC ";
		$result= mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($result))
		{
			$pid = $row['PROFILEID'];
			$sql="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
			$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row1=mysql_fetch_array($res1);
			if($row1['SUBSCRIPTION']=='')
			{
				if ($row['PROFILE_TYPE'] == 'O')
					$profile_type = "A";
				elseif ($row['PROFILE_TYPE'] == 'N')
					$profile_type = "B";

				$profile_typearr[$pid] = $profile_type;
				$arr[]=$row['PROFILEID'];
			}
			else
			{
				mysql_query_decide("UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED='Y' WHERE PROFILEID='$pid'") or die(mysql_error_js());
			}
		}

		$profile_cnt=count($arr);
		$num=$profile_cnt/100 + 1;
		$j2=0;
		$score_pid = 0;
		for($i=0;$i<$num;$i++)
		{
			for($k=$j2;$k<$j2+100;$k++)
			{
				if($arr[$k])
					$temp_arr[]=$arr[$k];
			}
			if($temp_arr)
			{
				$str=implode(",",$temp_arr);
				$sql = "SELECT PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($str)";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$pid = $row['PROFILEID'];
					$score_pid = $row['SCORE'];
					$scorearr[$pid] = $row['SCORE'];
					
					if (!$score_pid)
						$score_pid =0;
					if ($score_pid < 100)
						$score_pid = "0".$score_pid;
					if ($score_pid < 1000)
						$score_pid = "00".$score_pid;
					$score_pid_arr[]=$profile_typearr[$pid]."i".$score_pid."i".$row['PROFILEID'];
				}
			}
			$j2+=100;
			$str='';
			unset($temp_arr);
		}
		unset($arr);
		$TOTALREC=count($score_pid_arr);
		if(is_array($score_pid_arr))
		{
			rsort($score_pid_arr);

			$j2=$j;
			for($i=$j2;$i<$j2+25;$i++)
			{
				list($ptype,$score,$profileid)=explode("i",$score_pid_arr[$i]);
				if($profileid)
					$final_profilearr[]=$profileid;
			}
		}
		unset($score_pid_arr);

		if(is_array($final_profilearr))
		{
			$i = 0;
			$str=implode(",",$final_profilearr);                                                                         
			for ($i=0;$i < count($final_profilearr);$i++)
			{                                         
				$pid = $final_profilearr[$i];
				$temp_arr[] = $pid;
				$newusersarr[$i]["SNO"]= $sno;
				//$newusersarr[$i]["SCORE"] = $scorearr[$pid];
				$newusersarr[$i]["PROFILEID"]=$pid;
				$sno++;
			}
			$str1=implode(",",$temp_arr);
			
			$sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT , PHONE_MOB , PHONE_RES FROM newjs.JPROFILE WHERE  PROFILEID IN ($str1)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$i=array_search($myrow["PROFILEID"],$temp_arr);
				$temp_email=explode("@",$myrow["EMAIL"]);
				$email=$temp_email[0]."@xxx.com";
				$pid = $myrow['PROFILEID'];
														    
				$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");                                                                                
				$newusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
				$newusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
				$newusersarr[$i]["EMAIL"]=$email;
				$newusersarr[$i]["PROFILEID"]=$pid;
				$newusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
				$newusersarr[$i]["AGE"]=$myrow['AGE'];
				$newusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
				$newusersarr[$i]["GENDER"]=$myrow['GENDER'];
				$newusersarr[$i]["ENTRY_DT"]=$myrow['ENTRY_DT'];
				$newusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];
				$newusersarr[$i]['PROFILE_TYPE']=$profile_typearr[$pid];


				if($myrow['PHONE_RES'])
					$ph_res=$myrow['PHONE_RES'];
				else
					$ph_res="-";
				if($myrow['PHONE_MOB'])
					$ph_mob=$myrow['PHONE_MOB'];
				else
					$ph_mob="-";
				$newusersarr[$i]["RES_NO"]=$ph_res;
				$newusersarr[$i]["MOB_NO"]=$ph_mob;
			}

                        $discountArr =getDiscount($str);
                        for($d=0; $d<count($final_profilearr); $d++)
                        {
                                $profileid      =$final_profilearr[$d];
                                $dataArr        =$discountArr[$profileid];
                                $discount       =$dataArr['DISCOUNT'];
                                $date           =$dataArr['EDATE'];
                                $dateArr        =explode("-",$date);
                                $eDate          =$dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
                                if($discount)
                                        $newusersarr[$d]['DISCOUNT'] =$discount."% valid till ".$eDate;
                        }


		}
		unset($temp_arr);
		$smarty->assign("newusersarr",$newusersarr);
	}
		/*******This section of code modified by Kush *****************/
/**********It selects the entries in the table which r expiring in next 30 days and shows them on	on display on the basis of user and branch ************************************/	
	elseif($flag=='S')
	{
		$sql ="SELECT PROFILEID FROM incentive.SUBSCRIPTION_EXPIRY_PROFILES WHERE ALLOTED_TO='$name' AND HANDLED = 'N'";
	        $result= mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($result))
                {
			$pid_sub =$row['PROFILEID'];
			$sqlC =" SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='F' AND PROFILEID='$pid_sub' AND FOLLOWUP_TIME<='$today'";
			$resultC= mysql_query_decide($sqlC) or die(mysql_error_js());
			$rowC=mysql_fetch_array($resultC);
			if(!$rowC['PROFILEID'])
				$pidarr[]=$row['PROFILEID'];                         
		}

		$arr_cnt = count($pidarr);
		$num = $arr_cnt/100 + 1;
                $j2=0;
                for($i=0;$i<$num;$i++)
		{
			for($k=$j2;$k<$j2+100;$k++)
                        {
                                if($pidarr[$k])
                                        $temp_arr[]=$pidarr[$k];
                        }
			
			if($temp_arr)
			{
				for ($x = 0;$x < count($temp_arr);$x++)
				{
					$profileid = $temp_arr[$x];//echo "<br>";
					$sql1= "SELECT ID,PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID ='$profileid' AND SERVEFOR LIKE '%F%' ORDER BY ID DESC LIMIT 1";
					$result1= mysql_query_decide($sql1) or die(mysql_error_js());
					while($myrow1=mysql_fetch_array($result1))
					{
						$sub_expiry_arr[]=$myrow1['EXPIRY_DT']."i".$myrow1['PROFILEID'];
					}
				}	
			}
			$j2+=100;
                        $str='';
                        unset($temp_arr);
		}
		unset($pidarr);
		$TOTALREC=count($sub_expiry_arr);
                if(is_array($sub_expiry_arr))
		{
			sort($sub_expiry_arr);
                        $j2=$j;
                        for($i=$j2;$i<$j2+25;$i++)
                        {
				list($date,$profileid)=explode("i",$sub_expiry_arr[$i]);
                                if($profileid)
				{
                                        $final_profilearr[]=$profileid;
					$expiry_dt[$profileid]=$date;
				}
                        }
		}

		unset($sub_expiry_arr);
		$i = 0;
		if (is_array($final_profilearr))
		{
			$str=implode("','",$final_profilearr);
			$sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT , PHONE_MOB , PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID IN ('$str')";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
				$pid = $myrow['PROFILEID'];
				$temp_email=explode("@",$myrow["EMAIL"]);
				$email=$temp_email[0]."@xxx.com";
														    
				$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");                                                                                                                             
				$newusersarr[$i]["SNO"]= $i + $sno;
				$newusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
				$newusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
				$newusersarr[$i]["EMAIL"]=$email;
				$newusersarr[$i]["PROFILEID"]=$myrow['PROFILEID'];
				$newusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
				$newusersarr[$i]["AGE"]=$myrow['AGE'];
				$newusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
				$newusersarr[$i]["GENDER"]=$myrow['GENDER'];
				$newusersarr[$i]["ENTRY_DT"]=$myrow['ENTRY_DT'];
				$newusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];
				$newusersarr[$i]["EXPIRY_DT"]= $expiry_dt[$pid];
                                                                                                                            
				if($myrow['PHONE_RES'])
					$ph_res=$myrow['PHONE_RES'];
				else
					$ph_res="-";                                         
				if($myrow['PHONE_MOB'])
					$ph_mob=$myrow['PHONE_MOB'];
				else
					$ph_mob="-";
				$newusersarr[$i]["RES_NO"]=$ph_res;
                                $newusersarr[$i]["MOB_NO"]=$ph_mob;
			}
		}
		unset($final_profilearr);
		$smarty->assign('subs_expiry',"1");
		$smarty->assign("newusersarr",$newusersarr);
	}
	 /*******This section of code added by Vibhor *****************/
/**********It selects the entries in the table expiring in next 10 days and are unalloted ************************************/
        elseif($flag=='R')
        {
		$sql =" SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='R'";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
                while($row=mysql_fetch_array($result))
                {
                        $pidarr[]=$row['PROFILEID'];
                }

                $arr_cnt = count($pidarr);
                $num = $arr_cnt/100 + 1;
                $j2=0;
                for($i=0;$i<$num;$i++)
                {
                        for($k=$j2;$k<$j2+100;$k++)
                        {
                                if($pidarr[$k])
                                        $temp_arr[]=$pidarr[$k];
                        }

                        if($temp_arr)
                        {
                                for ($x = 0;$x < count($temp_arr);$x++)
                                {
                                        $profileid = $temp_arr[$x];//echo "<br>";
                                        $sql1= "SELECT ID,PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID ='$profileid' AND SERVEFOR LIKE '%F%' ORDER BY ID DESC LIMIT 1";
                                        $result1= mysql_query_decide($sql1) or die(mysql_error_js());
                                        while($myrow1=mysql_fetch_array($result1))
                                        {
                                                $sub_expiry_arr[]=$myrow1['EXPIRY_DT']."i".$myrow1['PROFILEID'];
					}
                                }
                        }
                        $j2+=100;
                        $str='';
                        unset($temp_arr);
                }
                unset($pidarr);
                $TOTALREC=count($sub_expiry_arr);
                if(is_array($sub_expiry_arr))
                {
                        sort($sub_expiry_arr);
                        $j2=$j;
                        for($i=$j2;$i<$j2+25;$i++)
                        {
                                list($date,$profileid)=explode("i",$sub_expiry_arr[$i]);
                                if($profileid)
                                {
                                        $final_profilearr[]=$profileid;
                                        $expiry_dt[$profileid]=$date;
                                }
                        }
                }

                unset($sub_expiry_arr);
                $i = 0;
                if (is_array($final_profilearr))
                {
                        $str=implode("','",$final_profilearr);
                        $sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT , PHONE_MOB , PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID IN ('$str')";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
                                $pid = $myrow['PROFILEID'];
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";

                                $city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");                            
                                $renusersarr[$i]["SNO"]= $i + $sno;
                                $renusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
                                $renusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
                                $renusersarr[$i]["EMAIL"]=$email;
                                $renusersarr[$i]["PROFILEID"]=$myrow['PROFILEID'];
                                $renusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
                                $renusersarr[$i]["AGE"]=$myrow['AGE'];
                                $renusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
                                $renusersarr[$i]["GENDER"]=$myrow['GENDER'];
                                $renusersarr[$i]["ENTRY_DT"]=$myrow['ENTRY_DT'];
                                $renusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];
                                $renusersarr[$i]["EXPIRY_DT"]= $expiry_dt[$pid];

                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $renusersarr[$i]["RES_NO"]=$ph_res;
                                $renusersarr[$i]["MOB_NO"]=$ph_mob;
                        }
                }
		unset($final_profilearr);
                //$smarty->assign('subs_expiry',"1");
                $smarty->assign("renusersarr",$renusersarr);
        }
	 /*******This section of code changes done by  Manoj *****************/
/**********It selects the entries in the table which are paid for less than six months subscription and are unalloted ************************************/
        elseif($flag=='U')
        {
		$sql =" SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='U'";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
                while($row=mysql_fetch_array($result))
                {
                        $pidarr[]=$row['PROFILEID'];
                }

                $arr_cnt = count($pidarr);
                $num = $arr_cnt/100 + 1;
                $j2=0;
                for($i=0;$i<$num;$i++)
                {
                        for($k=$j2;$k<$j2+100;$k++)
                        {
                                if($pidarr[$k])
                                        $temp_arr[]=$pidarr[$k];
                        }

                        if($temp_arr)
                        {
                                for ($x = 0;$x < count($temp_arr);$x++)
                                {
                                        $profileid = $temp_arr[$x];//echo "<br>";
                                        $sql1= "SELECT ID,PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID ='$profileid' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' ORDER BY ID DESC LIMIT 1";
                                        $result1= mysql_query_decide($sql1) or die(mysql_error_js());
                                        while($myrow1=mysql_fetch_array($result1))
                                        {
                                                $sub_expiry_arr[]=$myrow1['EXPIRY_DT']."i".$myrow1['PROFILEID'];
					}
                                }
                        }
                        $j2+=100;
                        $str='';
                        unset($temp_arr);
                }
                unset($pidarr);
                $TOTALREC=count($sub_expiry_arr);
                if(is_array($sub_expiry_arr))
                {
                        sort($sub_expiry_arr);
                        $j2=$j;
                        for($i=$j2;$i<$j2+25;$i++)
                        {
                                list($date,$profileid)=explode("i",$sub_expiry_arr[$i]);
                                if($profileid)
                                {
                                        $final_profilearr[]=$profileid;
                                        $expiry_dt[$profileid]=$date;
                                }
                        }
                }

                unset($sub_expiry_arr);
                $i = 0;
                if (is_array($final_profilearr))
                {
                        $str=implode("','",$final_profilearr);
                        $sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT , PHONE_MOB , PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID IN ('$str')";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
                                $pid = $myrow['PROFILEID'];
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";

                                $city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");                            
                                $paidusersarr[$i]["SNO"]= $i + $sno;
                                $paidusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
                                $paidusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
                                $paidusersarr[$i]["EMAIL"]=$email;
                                $paidusersarr[$i]["PROFILEID"]=$myrow['PROFILEID'];
                                $paidusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
                                $paidusersarr[$i]["AGE"]=$myrow['AGE'];
                                $paidusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
                                $paidusersarr[$i]["GENDER"]=$myrow['GENDER'];
                                $paidusersarr[$i]["ENTRY_DT"]=$myrow['ENTRY_DT'];
                                $paidusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];
                                $paidusersarr[$i]["EXPIRY_DT"]= $expiry_dt[$pid];

                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $paidusersarr[$i]["RES_NO"]=$ph_res;
                                $paidusersarr[$i]["MOB_NO"]=$ph_mob;
                        }
                }
		unset($final_profilearr);
                //$smarty->assign('subs_expiry',"1");
                $smarty->assign("paidusersarr",$paidusersarr);
        }


	/*******This section of code done by  Shubham *****************/
	/**********It show the entries of  Paid Profiles Not Due for Renewal Yet in a table************/


	elseif($flag == 'PA') 
 	{ 	$today_start=date("Y-m-d",$now)." 00:00:00";	
		$sql ="SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS!='F' UNION SELECT PROFILEID from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS='F' AND FOLLOWUP_TIME>'$today'";
                $result= mysql_query_decide($sql) or die(mysql_error_js());
                while($row=mysql_fetch_array($result))
		{	//if($row['FOLLOWUP_TIME'] < $today_start || $row['FOLLOWUP_TIME'] > $today)
				$pidarr[]=$row['PROFILEID'];
               	}
		$pidarr =array_unique($pidarr);
		$arr_cnt = count($pidarr);
                $num = $arr_cnt/100 + 1;
                $j2=0;
                for($i=0;$i<$num;$i++)
                {
                	for($k=$j2;$k<$j2+100;$k++)
                        {
                                if($pidarr[$k])
                                        $temp_arr[]=$pidarr[$k];
                        }

                        if($temp_arr)
                        {
				$str=implode("','",$temp_arr);
                        	$sql1= "SELECT DISTINCT PROFILEID, EXPIRY_DT ,ACTIVATED_ON ,SERVICEID ,ADDON_ID  FROM billing.SERVICE_STATUS WHERE PROFILEID IN ('$str') AND EXPIRY_DT >='$date_after_30days' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%'";
                        	$result1= mysql_query_decide($sql1) or die(mysql_error_js());
                         	while($myrow1=mysql_fetch_array($result1))
				{
					if($myrow1['ADDON_ID'])
  						$info_arr[]=$myrow1['EXPIRY_DT']."i".$myrow1['PROFILEID']."i".$myrow1['ACTIVATED_ON']."i".$myrow1['SERVICEID'].",".$myrow1['ADDON_ID'];
                                       	else
						 $info_arr[]=$myrow1['EXPIRY_DT']."i".$myrow1['PROFILEID']."i".$myrow1['ACTIVATED_ON']."i".$myrow1['SERVICEID'];
				}
                                
 			}
                        $j2+=100;
                        $str='';
                        unset($temp_arr);
                }
                unset($pidarr);
                $TOTALREC=count($info_arr);
                if(is_array($info_arr))
                {
                        sort($info_arr);
                        $j2=$j;
                        for($i=$j2;$i<$j2+25;$i++)
                        {
                                list($date,$profileid,$activedate,$service)=explode("i",$info_arr[$i]);
                                if($profileid)
                                {
                                        $final_profilearr[]=$profileid;
                                        $expiry_dt[$profileid]=$date;
					$activated_dt[$profileid]=$activedate;
					$services[$profileid]=$service;
                                }
                        }
                }

                unset($info_arr);
                $i = 0;
                if (is_array($final_profilearr)) 
		{
		
			/////////////finding TOTALEOI , SERVICVES and VIEWED CONTACTES of users
			$db2=connect_db();
			for($i=0;$i<count($final_profilearr);$i++)
			{
				$pid=$final_profilearr[$i];
			if($pid)
			{
				$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
                        	$dbs=$mysqlObj->connect("$myDbName");
			}

				$sql="SELECT COUNT(*) AS TOTALEOI  FROM newjs.CONTACTS WHERE SENDER='$pid' AND TIME >='$activated_dt[$pid]' AND TYPE='I'";
				$result= mysql_query_decide($sql,$dbs) or die(mysql_error_js());
				if($myrow=mysql_fetch_array($result))
					$count[$pid]=$myrow['TOTALEOI'];
				$str2=$services[$pid];
				$sql2="SELECT NAME FROM billing.SERVICES WHERE SERVICEID IN('$str2')";
				$result2= mysql_query_decide($sql2,$db2) or die(mysql_error_js());
				while($myrow2=mysql_fetch_array($result2))
					$paid_service[$pid]=" ".$myrow2['NAME'];
				$sql3="SELECT VIEWED FROM jsadmin.CONTACTS_ALLOTED WHERE PROFILEID='$pid'";
				$result3=mysql_query_decide($sql3,$db2) or die(mysql_error_js());
			 	if($myrow3=mysql_fetch_array($result3))
					$contact_view[$pid]=$myrow3['VIEWED'];
			 	else 
					$contact_view[$pid]=0;
			}
			
                        $str=implode("','",$final_profilearr);
                        $sql =" SELECT PROFILEID,HAVEPHOTO,USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN ('$str')";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $i=array_search($myrow["PROFILEID"],$final_profilearr);
                                $pid = $myrow['PROFILEID'];
                                $renusersarr[$i]["SNO"]= $i + $sno;
				$renusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
                                $renusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
                                $renusersarr[$i]["PROFILEID"]=$myrow['PROFILEID']; 
                                $renusersarr[$i]["EXPIRY_DT"]= $expiry_dt[$pid];
				$renusersarr[$i]["PURCHASE_DT"]= $activated_dt[$pid];
				$renusersarr[$i]["TOTAL_EOI"]= $count[$pid];
                                $renusersarr[$i]["SERVICE_PURCHASE"]= $paid_service[$pid];
				$renusersarr[$i]["CONTACT_VIEWED"]= $contact_view[$pid];
				if($myrow['HAVEPHOTO']=='Y')
                                       $renusersarr[$i]["HAVEPHOTO"]="YES";
                                else
                                  	$renusersarr[$i]["HAVEPHOTO"]="NO";
                        }
                }
                unset($final_profilearr);
		unset($expiry_dt);
		unset($activated_dt);
  		unset($services);
                $smarty->assign("renusersarr",$renusersarr);
  	}

                  

	elseif ($flag == 'C') // condition added by Shobha on 09.12.2005 to include list of members to be claimed
	{
		$sql ="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND ORDERS='' AND (STATUS='C' OR (STATUS='F' AND FOLLOWUP_TIME>'$today') OR STATUS='P')";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($result))
		{
			$arr[]=$row['PROFILEID'];
		}
		$profile_cnt=count($arr);
		$num=$profile_cnt/100 + 1;
		$j2 = 0;

		for($i=0;$i<$num;$i++)
		{
			for($k=$j2;$k<$j2+100;$k++)
			{
				if($arr[$k])
					$temp_arr[]=$arr[$k];
			}
			if($temp_arr)
			{
				$str=implode(",",$temp_arr);
				$sql="SELECT PROFILEID,LAST_LOGIN_DT, SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID IN ($str)";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$pid = $row['PROFILEID'];
					if($row['SUBSCRIPTION']=="")
					{
						if ($defaultsort)
						{
							$last_login_arr[]=$row['LAST_LOGIN_DT']."i".$row['PROFILEID'];
						}
						else
						{
							$spid=$row['PROFILEID'];
							$score_pid_arr[]=$spid;
							$score_pid_arr1[$spid]=$row['LAST_LOGIN_DT'];
						}
					}
				}
				if(count($score_pid_arr)>0)
				{
				$score_pid_str = implode(",",$score_pid_arr);
				$sql_score = "SELECT SCORE,PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($score_pid_str)";
				$res_score = mysql_query_decide($sql_score) or die("$sql_score".mysql_error_js());
				while($row_score=mysql_fetch_array($res_score))
                                {
					$score_pid = $row_score['SCORE'];
					$spid1 = $row_score['PROFILEID'];
					if(!$score_pid)
						$score_pid  = 0;
					if ($score_pid < 100)
						$score_pid = "0".$score_pid;
					$last_login_arr[]=$score_pid."i".$score_pid_arr1[$spid1]."i".$spid1;
				}
				$last_login_arr_main=$last_login_arr;
                                unset($last_login_arr);
				}
			}
			$j2+=100;
			$str='';
			unset($temp_arr);
		}
		unset($arr);
		if(count($score_pid_arr)>0)
			$last_login_arr=$last_login_arr_main;
		$TOTALREC=count($last_login_arr);
		if(is_array($last_login_arr))
		{
			rsort($last_login_arr);
			$j2=$j;
			for($i=$j2;$i<$j2+25;$i++)
			{
				if ($defaultsort)
                                {
					list($date,$profileid)=explode("i",$last_login_arr[$i]);
                                }
                                else
                                {
					list($score,$date,$profileid)=explode("i",$last_login_arr[$i]);
                                }
				if($profileid)
                                        $final_profilearr[]=$profileid;
			}
		}
		unset($last_login_arr);
		if(is_array($final_profilearr))
		{
			$str=implode(",",$final_profilearr);

			$sql =" SELECT EMAIL,PROFILEID,ACTIVATED,AGE,GENDER,USERNAME,CITY_RES,ENTRY_DT,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$temp_email=explode("@",$myrow["EMAIL"]);
				$email=$temp_email[0]."@xxx.com";

				$city=label_select("CITY_NEW",$myrow['CITY_RES'],"newjs");

                                $i = array_search($myrow["PROFILEID"],$final_profilearr);

				$claimusersarr[$i]["SNO"]=$i + $sno;
				$claimusersarr[$i]["NAME"] = addslashes(stripslashes(get_name_of_user($myrow['PROFILEID'])));
				$claimusersarr[$i]["USERNAME"]=addslashes(stripslashes($myrow['USERNAME']));
				$claimusersarr[$i]["EMAIL"]=$email;
				$claimusersarr[$i]["PROFILEID"]=$myrow['PROFILEID'];
				$claimusersarr[$i]["CITY_INDIA"]=$city['LABEL'];
				$claimusersarr[$i]["ACTIVATED"]=$myrow['ACTIVATED'];
				$claimusersarr[$i]["LAST_LOGIN_DT"]=$myrow['LAST_LOGIN_DT'];

			}
			$i = 0;
			/* SCORE commented	
			$sql = "SELECT PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				if (!$myrow['SCORE'])
					$score = 0;
				else
				$score = $myrow['SCORE'];
				$i=array_search($myrow["PROFILEID"],$final_profilearr);
				$claimusersarr[$i]["SCORE"] = $score;
			}
			*/
			$sql =" SELECT PROFILEID,ALTERNATE_NO,RES_NO,MOB_NO,ALLOT_TIME,CONVINCE_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID IN ($str)";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['RES_NO'])
					$ph_res=$myrow['RES_NO'];
				else
					$ph_res="-";
				if($myrow['ALTERNATE_NO'])
				{
					$ph_res.=",".$myrow['ALTERNATE_NO'];
				}
				if($myrow['MOB_NO'])
					$ph_mob=$myrow['MOB_NO'];
				else
					$ph_mob="-";
				$allot_time=$myrow['ALLOT_TIME'];
				$last_handled_time=$myrow['CONVINCE_TIME'];
				$i = array_search($myrow["PROFILEID"],$final_profilearr);
				$claimusersarr[$i]["RES_NO"]=$ph_res;
				$claimusersarr[$i]["MOB_NO"]=$ph_mob;
				$claimusersarr[$i]["ALLOT_DT"]=$allot_time;
				$claimusersarr[$i]["HANDLED_DT"]=$last_handled_time;
			}

                        $discountArr =getDiscount($str);
                        for($d=0; $d<count($final_profilearr); $d++)
                        {
                                $profileid      =$final_profilearr[$d];
                                $dataArr        =$discountArr[$profileid];
                                $discount       =$dataArr['DISCOUNT'];
                                $date           =$dataArr['EDATE'];
                                $dateArr        =explode("-",$date);
                                $eDate          =$dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
                                if($discount)
                                        $claimusersarr[$d]['DISCOUNT'] =$discount."% valid till ".$eDate;
                        }
			$smarty->assign("claimusersarr",$claimusersarr);
		}
		unset($temp_profilearr);
	}
	// changes end here

        if( $j )
                $cPage = ($j/$PAGELEN) + 1;
        else
                $cPage = 1;

	$smarty->assign("defaultsort",$defaultsort);
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"outbound1.php",'',$flag,$getold,"",$defaultsort);
        $smarty->assign("COUNT",$TOTALREC);
        $smarty->assign("CURRENTPAGE",$cPage);
        $no_of_pages=ceil($TOTALREC/$PAGELEN);
        $smarty->assign("NO_OF_PAGES",$no_of_pages);

	$smarty->assign("flag",$flag);
	$smarty->assign("sort_order",$sort_order);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("outbound1.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
