<?php
include("../../connect.inc");
include("../functions.php");
require("functions_transecute.php");
connect_db();
/*****************Portion of Code added for display of Banners*******************************/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);
                                                                                                                             
       // $regionstr=8;
       // include("../bmsjs/bms_display.php");
/***********************End of Portion of Code*****************************************/
$ip=FetchClientIP();//Gets ipaddress of user
if(strstr($ip, ","))    
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

$smarty->assign("head_tab","memberships"); //flag for headnew.htm tab
if(!$checksum)
{
        header('Location: /P/mem_comparison.php');
        die();
}
$smarty->assign("ICICI",'N');
if($data = authenticated($checksum))
{
	$profileid = $data["PROFILEID"];
	$smarty->assign("COURIER",$COURIER);
	$smarty->assign("indian",$indian);
	if($checkout)
	{
		if(strstr($paymode,"cheque"))
		{
			//cheque or draft payment
			global $error_msg, $pay_arrayfull, $pay_arrayfull, $announce_to_email, $ip, $DOL_CONV_RATE;

			/*if(strstr($service_main,'P'))
				$ORDERID = sprintf ("J%1.1s%09lX", 'F', time(NULL));
			elseif(strstr($service_main,'D'))
				$ORDERID = sprintf ("J%1.1s%09lX", 'D', time(NULL));
			elseif(strstr($service_main,'C'))
				$ORDERID = sprintf ("J%1.1s%09lX", 'C', time(NULL));*/

			$data = getProfileDetails($profileid);
			$data["AMOUNT"]=$total;

			$service_detail=getServiceDetails($service_main);
			list($servefor,$addon_serviceid)=serve_for($service_main,$service_str);

			$data["SERVICE_MAIN"]=$service_main;
			$data["ADDON_SERVICE"]=$addon_serviceid;

			unset($insert_id);

			$sql1 = "insert into incentive.PAYMENT_COLLECT (PROFILEID, USERNAME, SERVICE, BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,STATUS,COURIER_TYPE,DISPLAY,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PHONE_RES,EMAIL,ADDRESS,REQ_DT) values ('$profileid', '" . addslashes($data[USERNAME]) . "','$service_main','Y','N','',NOW(),'','','','$addon_serviceid','$discount','$data[AMOUNT]','$type','$data[PHONE]','$data[EMAIL]','".addslashes(stripslashes($data[CONTACT]))."',NOW())";

			$res1 = mysql_query_decide($sql1) or die($sql.mysql_error_js());
//logError($error_msg,$sql1,"ShowErrTemplate",$announce_to_email);


			///$sql1 = "insert into billing.ORDERS (PROFILEID, USERNAME, ORDERID, PAYMODE, SERVICEMAIN, CURTYPE,SERVEFOR, AMOUNT, ENTRY_DT, EXPIRY_DT, BILL_ADDRESS, PINCODE, BILL_COUNTRY, BILL_PHONE, BILL_EMAIL, IPADD,ADDON_SERVICEID,DISCOUNT,SET_ACTIVATE,GATEWAY) values ('$profileid', '" . addslashes($data[USERNAME]) . "', '$ORDERID', '$paymode', '$service_main','$curtype','$servefor', '$data[AMOUNT]', NOW(), DATE_ADD('$activate_on',INTERVAL ".$service_detail["DURATION"]." MONTH), '".addslashes(stripslashes($data[CONTACT]))."', '".addslashes(stripslashes($data[PINCODE]))."', '".addslashes(stripslashes($data[COUNTRY]))."', '$data[PHONE]', '$data[EMAIL]','$ip','$addon_serviceid','$discount','$setactivate','$gateway')";

        		$insert_id = mysql_insert_id_js();
        		$data["REQUESTID"] = $insert_id;

			if(!$insert_id)
			{
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->display("pg/ordererror.htm");
				die;
			}
			else
				$ORDER = $data;

			$orderdate = date("Y-m-d",time());
			list($year,$month,$day) = explode("-",$orderdate);
			$orderdate = my_format_date($day,$month,$year);

			if($type=="DOL")
			{
				$paytype = "US $";
				$smarty->assign("PAYTYPE_WORDS","Dollar");
				$smarty->assign("AMOUNT",$ORDER["AMOUNT"] / $DOL_CONV_RATE);
			}
			else
			{
				$paytype = "Rs. ";
				$smarty->assign("PAYTYPE_WORDS","Rupees");
				$smarty->assign("AMOUNT",$ORDER["AMOUNT"]);
			}


			$service_main_details=getServiceDetails($service_main);


			chequepickup($profileid,$ORDER["REQUESTID"]);
			depositcheque($ORDER["REQUESTID"]);
			get_nearest_branches($profileid);

			$smarty->assign("PERIOD",$service_main_details["DURATION"]);
			
			$smarty->assign("ORDERID",$ORDER["REQUESTID"]);
			$smarty->assign("ORDERDATE",$orderdate);
			$smarty->assign("BILL_NAME",$ORDER["USERNAME"]);
			$smarty->assign("BILL_ADD",$ORDER["CONTACT"]);
			$smarty->assign("BILL_COUNTRY",$ORDER["COUNTRY"]);
			$smarty->assign("BILL_PHONE",$ORDER["PHONE"]);
			$smarty->assign("BILL_EMAIL",$ORDER["EMAIL"]);
			$smarty->assign("PAYTYPE",$paytype);
			$smarty->assign("SER_MAIN",$ser_main);

			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

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
				$yyarr[$i]=$i+2005;
			}
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);

			list($cur_year,$cur_month,$cur_day) = explode("-",date('Y-m-d'));
			$smarty->assign("cur_year",$cur_year);
			$smarty->assign("cur_month",ltrim($cur_month,"0"));
			$smarty->assign("cur_day",ltrim($cur_day,"0"));

			/*Added by sriram for not allowing to select today's and tomorrow's date under checque pickup option*/
			$after_two_days = mktime(0,0,0,date('m'),date('d')+2,date('Y'));
			list($after2_year,$after2_month,$after2_date) = explode("-",date('Y-m-d',$after_two_days));
			$smarty->assign("after2_date",$after2_date);
			$smarty->assign("after2_month",$after2_month);
			$smarty->assign("after2_year",$after2_year);
			/*End of - Added by sriram for not allowing to select today's and tomorrow's date under checque pickup option*/
			$smarty->display("pg/orderreceipt_cheque.htm");
		}

	}
	else
	{
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("SER_MAIN",$ser_main);
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

		$smarty->display("pg/ordererror.htm");
		//echo "<b>Strange :</b> How you get this link.<br>If you get this link genuinely, then intimate to me at alok@jeevansathi.com";
	}
}
else
{
	TimedOut();
}
function chequepickup($pid,$REQUESTID)
{
	global $smarty , $dec_ag;
	//$dec_ag = 'Y';
/*	$smarty->assign("DEC_AG",$dec_ag);	
	echo $sql="select CONFIRM,AR_GIVEN,STATUS from incentive.PAYMENT_COLLECT  where PROFILEID='$pid' and BILLING!='Y' and DISPLAY<>'N' order by ID desc";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result)>0 && $dec_ag!='Y')
	{
		echo "1";
		$myrow=mysql_fetch_array($result);
		if($myrow["STATUS"]=='D')
		{
			$msg="Last time, You declined the payment to our representative.To make new request  ";
			$msg.="<a href=\"/P/payment.php?ser_main=C&dec_ag=Y\"> Click here</a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("thanks_sky.htm");
			exit;
		}
		elseif($myrow["CONFIRM"]=='' && $myrow["AR_GIVEN"]=='')
		{
			$msg="Your contact details have been entered and your request is already in a queue";
			$smarty->assign("MSG",$msg);
			$smarty->display("thanks_sky.htm");
			exit;
		}
		elseif($myrow["CONFIRM"]=='Y')
		{
			$msg="Your request has been confirmed and our representative will contact you shortly";
			$smarty->assign("MSG",$msg);
			$smarty->display("thanks_sky.htm");
			exit;
		}
	}
	else
	{*/
		$sql = "SELECT SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($result);
		$service_main = $row['SERVICE'];
		$addon = $row['ADDON_SERVICEID'];
														    
		$username=$data["USERNAME"];
		$main_ser=$service_main;
		$addon_ser=$addon;
		$amt=$row['AMOUNT'];
		$amt_words = convert($amt);//added by sriram to display amount in words
		$amt_words .= " Only";
		$smarty->assign("AMOUNT_WORDS",$amt_words);

		$sql="select EMAIL,PHONE_MOB,CITY_RES,PHONE_RES,CONTACT from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		$email=$myrow["EMAIL"];
		$city_res=$myrow["CITY_RES"];
		
		$main_ser_name=service_name($main_ser);
														    
		if($row["ADDON_SERVICEID"])
		{
			$addon_serviceid = $row["ADDON_SERVICEID"];
			$addon_serviceid_ar = explode(",",$addon_serviceid);
			for($j=0;$j<count($addon_serviceid_ar);$j++)
				$addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
			$addon_serviceid_str = implode(",",$addon_serviceid_ar);
														    
			$sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
			$result_services = mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow_result_services = mysql_fetch_array($result_services))
			{
				$add_on_services[] = "<br>".$myrow_result_services["NAME"];
			}
		}
		

		$sql_near="SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y'";
                $result_near=mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $i=0;
                while($row_near=mysql_fetch_array($result_near))
                {
                        if($row_near["VALUE"]!="GU")
                        {
                                $near_ar[$i]['LABEL']=$row_near["LABEL"];
                                $near_ar[$i]['VALUE']=$row_near["VALUE"];
                                $i++;
                        }
                }

		$smarty->assign("STP",$stp);
		$smarty->assign("REQUESTID",$REQUESTID);
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("EMAIL",$email);
		$smarty->assign("USERNAME",$username);
		$smarty->assign("PHONE_MOB",$myrow["PHONE_MOB"]);
		$smarty->assign("PHONE_RES",$myrow["PHONE_RES"]);
		$smarty->assign("ADDRESS",$myrow["CONTACT"]);
		$smarty->assign("SERVICE",$main_ser);
		$smarty->assign("ADDON_SER",$addon_ser);
		$smarty->assign("MAIN_SER_NAME",$main_ser_name);
		$smarty->assign("ADDON_ARR",$addon_arr);
		$smarty->assign("ADDON_SERVICES",$addon_service_names);
		$smarty->assign("CUR_TYPE",$row['CUR_TYPE']);
		$smarty->assign("AMOUNT",$amt);
		$smarty->assign("NEAR_ARR",$near_ar);
                $smarty->assign("CITY_RES",$city_res);
		//$smarty->display("pay_req.htm");
		unset($add_on_services);
	//}
}
function service_name($id)
{
        $sql="select NAME from billing.SERVICES where SERVICEID='$id'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $myrow=mysql_fetch_array($result);
        $name=$myrow["NAME"];
        return $name;
}
function depositcheque($REQUESTID)
{
	global $smarty;
	$sql = "SELECT PROFILEID , USERNAME , EMAIL , SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($result);
	$main_ser_name=service_name($row['SERVICE']);
	if($row["ADDON_SERVICEID"])
	{
		$addon_serviceid = $row["ADDON_SERVICEID"];
		$addon_serviceid_ar = explode(",",$addon_serviceid);
		for($j=0;$j<count($addon_serviceid_ar);$j++)
			$addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
		$addon_serviceid_str = implode(",",$addon_serviceid_ar);
														    
		$sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
		$result_services = mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow_result_services = mysql_fetch_array($result_services))
		{
			$add_on_services[] = "<br>".$myrow_result_services["NAME"];
		}
		$addon_service_names = implode(",",$add_on_services);
	}

	$smarty->assign("EMAIL",$row['EMAIL']);
	$smarty->assign("USERNAME",$row['USERNAME']);
	$smarty->assign("REQUESTID",$REQUESTID);
	$smarty->assign("MAIN_SER_NAME",$main_ser_name);
	$smarty->assign("ADDON_SERVICES",$addon_service_names);
	$smarty->assign("CURTYPE",$row['CUR_TYPE']);
	$smarty->assign("AMOUNT",$row['AMOUNT']);
	$smarty->assign("PROFILEID",$row['PROFILEID']);
														    
	$sql="SELECT NAME FROM billing.BANK";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$bank[$i]=$row['NAME'];
		$i++;
	}
	$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$dep_branch_arr[$i]=$row['NAME'];
		$i++;
	}
	$dd_arr=explode("-",Date('Y-m-d'));
	$smarty->assign("DEP_DAY",$dd_arr[2]);
	$smarty->assign("DEP_MONTH",$dd_arr[1]);
	$smarty->assign("DEP_YEAR",$dd_arr[0]);
	$smarty->assign("dep_branch",$center);
	$smarty->assign("dep_branch_arr",$dep_branch_arr);
														    
	$smarty->assign("USER",$user);
	$smarty->assign("val",$val);
	$smarty->assign("uname",$uname);
	$smarty->assign("phrase",$phrase);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("billid",$billid);
	$smarty->assign("subs",$subs);
	$smarty->assign("bank",$bank);
														    
	$smarty->assign("MODE","CHEQUE");
	$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
}
/*code added by sriram to find and display the nearest branch*/
function get_nearest_branches($profileid)
{
	global $smarty;
	$sql = "SELECT CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row = mysql_fetch_array($res);

	$sql_address = "SELECT ADDRESS FROM incentive.BRANCH_CITY, newjs.BRANCHES WHERE incentive.BRANCH_CITY.NEAR_BRANCH=newjs.BRANCHES.VALUE and incentive.BRANCH_CITY.VALUE='$row[CITY_RES]'";
	$res_address = mysql_query_decide($sql_address) or die("$sql_address".mysql_error_js());
	$i=0;
	while($row_address = mysql_fetch_array($res_address))
	{
		$near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
		$i++;
	}
	$smarty->assign("near_branches",$near_branches);
}
/*End of - code added by sriram to find and display the nearest branch*/
?>
