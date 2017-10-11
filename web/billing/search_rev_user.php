<?php
include("../jsadmin/connect.inc");
include("comfunc_sums.php");
$smarty->assign("bmsUrl",JsConstants::$bmsUrl);
//bms condition added by lavesh
if($from_bms)
{
	$criteria="billid";
	$submit="Go";
	include_once("bms_connect_billing.php");
	$ip     = FetchClientIP();
	$data   = authenticatedBms($id,$ip,"banadmin");
	if(!$data)
	{
                echo "TIMED OUT<br><br>PLZ GO BACK TO PREVIOUS PAGE AND LOGIN AGAIN";
                exit();
	}
	//$phrase="JR-13"; 
}
else
	$data=authenticated($cid);

if(isset($data))
{
	if(!$from_bms)
	{
		maStripVARS_sums('stripslashes');
		$rev_cri=populate_rev_search_criteria();
		$smarty->assign("rev_cri",$rev_cri);
		$user=getuser($cid);
		$privilage=getprivilage($cid);
		$priv=explode("+",$privilage);

		if(in_array('BA',$priv))
		{
			$smarty->assign("ADMIN","Y");
		}
	}

	//build query depending on search criteria.
	if(trim($phrase)!="")
	{
		$flag=1;
		$sql="SELECT a.COMP_NAME,a.SALEID,a.SHIP_TO_ADDRESS,a.BUREAU_PID,a.SHIP_TO_PHONE,a.SHIP_TO_EMAIL,a.SHIP_TO_COUNTRY,a.SHIP_TO_PIN";
		if($criteria=="uname"&& isset($companySelected))
		{
			$from=" FROM billing.REV_MASTER as a ";
			$where=" WHERE a.COMP_NAME ='$phrase' ";
		}
		elseif($criteria=="uname")
                {
			$starLocation=strpos($phrase,"*");
			if($starLocation===0)
                                $finalPhrase="%".$phrase;
			elseif($starLocation==false)
                                $finalPhrase="%".$phrase."%";
			elseif($starLocation==(strlen($phrase)-1))
				$finalPhrase=$phrase."%";
			$finalPhrase=str_replace("*","",$finalPhrase);
			$phrase=str_replace("*","",$phrase);
                        $from=" FROM billing.REV_MASTER as a ";
                        $where=" WHERE a.COMP_NAME like '$finalPhrase' ";
                }
		elseif($criteria=="billid")
		{
			$sid=explode("-",$phrase);
			$from=" FROM billing.REV_MASTER as a ";
			$where=" WHERE a.SALEID ='$sid[1]' ";
		}
		elseif($criteria=="cdnum")
		{
			$from=" FROM billing.REV_MASTER as a,billing.REV_PAYMENT as b ";
			$where=" WHERE b.CD_NUM='$phrase' and a.SALEID=b.SALEID ";
		}
		$sql.=$from.$where." order by SALEID desc";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		$count=mysql_num_rows($res);
		if($count==1||isset($companySelected))
		{
			$row=mysql_fetch_array($res);
			$comp_name=$row['COMP_NAME'];
			$billid_arr[]=$row['SALEID'];
			$bureauprofileid=$row['BUREAU_PID'];
			$smarty->assign("shipAddress",$row['SHIP_TO_ADDRESS']);
			$smarty->assign("shipPIN",$row['SHIP_TO_PIN']);
			$smarty->assign("shipCountry",$row['SHIP_TO_COUNTRY']);
			$smarty->assign("shipEmail",$row['SHIP_TO_EMAIL']);
			$smarty->assign("shipPhone",$row['SHIP_TO_PHONE']);
			$smarty->assign("SHOWLINK","UPGRADE"); 
			$smarty->assign("USERNAME",$phrase); 
			$smarty->assign("PID",$comp_name);
			$smarty->assign("bureauprofileid",$bureauprofileid);
		}
		elseif($count>1)
		{
			$companies=array();
			while($row=mysql_fetch_array($res))
			{
				if(!in_array($row['COMP_NAME'],$companies))
					$companies[]=$row['COMP_NAME'];
			}
			$smarty->assign("COMPANIES",$companies);
			$smarty->assign("CID",$cid);
			$smarty->display("multipleCompanies.htm");
			exit;
		}
		else
		{
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("PID",$pid);
			$smarty->assign("flag","NO_RECORD");
			$smarty->assign("SOURCE",$source);
			$smarty->assign("CRM_ID",$crm_id);
			if($criteria=="uname")
				$smarty->assign("uname",$phrase);
			$smarty->assign("SHOWLINK","NEW");
			$smarty->assign("USERNAME",$phrase); 
			$smarty->assign("PID",$pid);
			$smarty->assign("bureauprofileid",$bureauprofileid);
			$smarty->display("search_rev_user.htm");
			exit;
		}
		
	 	$billid=$billid_arr[0];
		$comp_name1=addslashes(stripslashes($comp_name));

		//query to find bill and receipt details.
		$sql="SELECT distinct RECEIPTID,COMP_NAME,SALE_DES,CUR_TYPE,SALE_AMT,rm.SERVICE_TAX,rm.SHIP_TO_PHONE AS shipPhone,rm.SHIP_TO_PIN AS shipPIN,rm.SHIP_TO_EMAIL AS shipEmail,rm.SHIP_TO_COUNTRY as shipCountry,rp.BILL_TO_NAME as billName,rp.BILL_TO_ADDRESS as billAddress,rp.BILL_TO_COUNTRY AS billCountry,rp.BILL_TO_PIN as billPIN, rp.TDS,TOTAL_AMT,SALE_BY,SALE_TYPE,rm.DUE_DT,rm.SALEID,MODE,TYPE, AMOUNT, CD_NUM, TRANS_NUM, CD_CITY, CD_DT, BANK, rm.STATUS as RM_STATUS, rp.STATUS, rm.ENTRY_DT AS BILLING_DT,rp.ENTRY_DT, rp.ENTRYBY,rm.CATEGORY,rm.BMS_COMP_ID,rm.DUEAMOUNT, rm.DISCOUNT,rm.DISCOUNT_REASON,rp.REASON FROM billing.REV_MASTER as rm left join billing.REV_PAYMENT as rp on rm.SALEID = rp.SALEID WHERE rm.COMP_NAME='$comp_name1'  ORDER BY rm.SALEID, RECEIPTID";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			//query to show/hide View edit history link.
			$sql_edited = "SELECT * FROM billing.REV_EDIT_DETAILS_LOG WHERE SALEID='$row[SALEID]'";
			$res_edited = mysql_query_decide($sql_edited) or logError_sums($sql_edited,0);
			if($row_edited = mysql_fetch_array($res_edited))
				$arr[$i]["edited"] = 1;

			$billid_thru_pd[]=$row['SALEID'];
		
			if($row['SALEID'] <> $last_billid)
				$arr[$i]["blankrow"]="Y";

			$last_billid=$row['SALEID'];
			$last_receiptid=$row['RECEIPTID'];	
			$cur_type=$row['CUR_TYPE'];
			$arr[$i]["receiptid"]=$row['RECEIPTID'];
			$arr[$i]["billid"]=get_rev_saleid($row['SALEID']);
			$arr[$i]["saleid"]=$row['SALEID'];
			$arr[$i]["sale_by"]=$row['SALE_BY'];
			$arr[$i]["sale_type"]=$row['SALE_TYPE'];
			$arr[$i]["due_date"]=$row['DUE_DT'];
			$arr[$i]["sname"]=$row['SALE_DES'];
			$arr[$i]["mode"]=$row['MODE'];
			$arr[$i]["type"]=$row['TYPE'];
			$arr[$i]["amt"]=$row['AMOUNT'];
			$arr[$i]["discount"] = $cur_type." ".$row['DISCOUNT'];
			$arr[$i]["discount_reason"]=$row['DISCOUNT_REASON'];
			$arr[$i]["cd_num"]=$row['CD_NUM'];
			$arr[$i]["t_num"]=$row['TRANS_NUM'];
			$arr[$i]["shipEmail"]=$row['shipEmail'];
			$arr[$i]["shipAddress"]=$row['shipAddress']."\n".$row['shipCountry']."\n".$row['shipPIN'];
			$arr[$i]["shipPhone"]=$row['shipPhone'];
			$arr[$i]["billName"]=$row['billName'];
			$arr[$i]["billAddress"]=$row['billAddress'];
			$arr[$i]["billCountry"]=$row['billCountry'];
			$arr[$i]["billPIN"]=$row['billPIN'];
			$arr[$i]["t_num"]=$row['TRANS_NUM'];
			$arr[$i]["cd_dt"]=$row['CD_DT'];
			$arr[$i]["cd_city"]=$row['CD_CITY'];
			$arr[$i]["bank"]=$row['BANK'];
			$arr[$i]["status"]=$row['STATUS'];
			$arr[$i]["Billing_Dt"]=substr($row['BILLING_DT'],0,10);
			if($row['RM_STATUS']=="CANCEL")
				$arr[$i]["cancelled"]=1;

			$arr[$i]["entry_dt"]=substr($row['ENTRY_DT'],0,10);
			$arr[$i]["entryby"]=$row['ENTRYBY'];
			$arr[$i]["reason"]=$row['REASON'];
			$arr[$i]["sale_amt"]=$cur_type." ". $row['SALE_AMT'];		
			$arr[$i]["service_tax"]=$cur_type." ".$row['SERVICE_TAX'];
			$arr[$i]["tds"]=$cur_type." ".$row['TDS'];
			$total_amount = $row['TOTAL_AMT'];
			$arr[$i]["category"]=$row['CATEGORY'];
			$arr[$i]["bms_comp_id"]=$row['BMS_COMP_ID'];
			if($row['CUR_TYPE']=="DOL")
			{
				$arr[$i]["total_amount"]="DOL ".$total_amount;
			}
			else
			{
				$arr[$i]["total_amount"] = "RS ".$total_amount;
			}
				
			if($i==0)
			{
				$ctr[$i]=1;
			}
			if($i>0)
			{
				$ctr[$i]=0;
				if($arr[$i-1]["billid"]!=$arr[$i]["billid"])
				{
					$ctr[$i]=1;
				}
			}
			if($row['STATUS']=='DONE')
				$paid+=$arr[$i]["amt"];
			$arr[$i]["amt"]=$arr[$i]["type"]." ".$arr[$i]["amt"];

			$arr[$i]["dueamount"] = $row['DUEAMOUNT'];
			$arr[$i]["cur_type"] = $row['CUR_TYPE'];

			$i++;
		}

		$uname=$comp_name;

		if($total_amount > $paid)
			$smarty->assign("write_off",1);

		$smarty->assign("showlink",$showlink);
		$smarty->assign("paid",$paid);
		$smarty->assign("dueamount",$dueamount);
		$smarty->assign("refund",$refund);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("ctr",$ctr);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("uname",$uname);
		$smarty->assign("arr",$arr);
		$smarty->assign("flag",$flag);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("CRM_ID",$crm_id);
		$smarty->assign("last_billid",$last_billid);
		$smarty->assign("last_receiptid",$last_receiptid);
		$smarty->assign("currency_type",$cur_type);
		$smarty->display("search_rev_user.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->display("search_rev_user.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("username","$username");
	$smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
