<?php
include("../jsadmin/connect.inc");
if(authenticated($cid))
{
	$user=getuser($cid);
	$minlimit=0;
	$maxlimit=50;
	if($Go)
	{
                $prev=getprivilage($cid);
                $priv=explode("+",$prev);
	
		$i=0;
		if($Go!="Go")
		{
			$PAGE=1;
			$grp_no=0;
		}
		if( !$grp_no )
			$grp_no = 0;

		$date1=$year1."-".$month1."-".$day1." 00:00:00";
		$date2=$year2."-".$month2."-".$day2." 23:59:59";
		if($year1!="" && $month1!="" && $day1!="" && $year2!="" && $month2!="" && $day2!="")
		{
				$sql_id = "SELECT MAX( BILLID) AS BILLID, PROFILEID FROM billing.PURCHASES where ENTRY_DT>='$date1' and ENTRY_DT<='$date2' GROUP BY PROFILEID";
				$result_id=mysql_query_decide($sql_id) or  $msg .= "\n$sql_id \nError :".mysql_error_js();
				while($myrow_id=mysql_fetch_array($result_id))
				{	
				        $arr_id[]=$myrow_id['BILLID'];
				}
				if($arr_id)
				        $list_id=implode("','",$arr_id);

			
				$sql="SELECT distinct p.PROFILEID, p.USERNAME, p.SERVICEID, p.ADDON_SERVICEID,p.ENTRY_DT, p.DUEAMOUNT,p.DUEDATE,p.ENTRYBY,pd.TYPE from billing.PURCHASES p,billing.PAYMENT_DETAIL pd where p.BILLID in ('$list_id') and p.BILLID=pd.BILLID and p.DUEAMOUNT>0 and p.STATUS in ('DONE','STOPPED') and p.ENTRY_DT>='$date1' and p.ENTRY_DT<='$date2'";
	        		if(in_array('BA',$priv))
				;
				elseif(in_array('BU',$priv))
					$sql .= " and p.ENTRYBY='$user'";
				$noerrorflag=1;

		}	
		else
		{
			$noerrorflag=0;
		}
		
		if($noerrorflag)
		{
			if($PAGE==0)
			{
				$PAGE=1;
			}
			$smarty->assign("SEARCH","YES");
			$sql.=" ORDER BY ENTRY_DT asc";
//echo "sql : ".$sql;
			$result1=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$total=mysql_num_rows($result1);
			$num_page=ceil($total/$maxlimit); 
			$minlimit=$minlimit+($maxlimit*($PAGE-1));	
			$sql.=" limit $minlimit,$maxlimit"; 
			$result=mysql_query_decide($sql);
			$i=1;
			if(mysql_num_rows($result)==0)
			{
				$PAGEREF="zero";
				$smarty->assign("PAGEREF",$PAGEREF);
			}
			while($myrow=mysql_fetch_array($result))
			{
				$username=ereg_replace(" ","&nbsp;",$myrow['USERNAME']);
				$str =JSstrToTime($myrow["ENTRY_DT"]);
                        	$bill_dt = strftime("%d %b' %y %H:%M:%S", $str);
				$profileid=$myrow['PROFILEID'];
				$dueamount=$myrow['TYPE']." ".$myrow['DUEAMOUNT'];
				
				if($myrow['DUEDATE'] != 0)
					$duedate=strftime("%d %b' %y ", JSstrToTime($myrow['DUEDATE']));
				else
					$duedate= "";
				$entryby=$myrow['ENTRYBY'];
				$services=$myrow['SERVICEID'];
				if($myrow['ADDON_SERVICEID'])
					$services .= ",".$myrow['ADDON_SERVICEID'];
				$services_str=str_replace(",","','",$services);
				$sql_ser="SELECT NAME from billing.SERVICES where SERVICEID in ('$services_str')";
				$result_ser=mysql_query_decide($sql_ser) or die("$sql_ser".mysql_error_js());
				while($myrow_ser=mysql_fetch_array($result_ser))
				{
					$servicename_arr[]=$myrow_ser['NAME'];
				}
				$servicename=implode("<br>",$servicename_arr);
				unset($servicename_arr);
				$values[] = array("sno"=>$i+(($PAGE-1)*$maxlimit),
						  "profileid"=>$profileid,
						  "username"=>$username,
						  "service"=>$servicename,
						  "bill_dt" => $bill_dt,
						  "amount"=>$amount,
						  "dueamount"=>$dueamount,	
						  "duedate"=>$duedate, 	
						  "entryby"=>$entryby 
						 );
				$i++;

			}

		//-----------  New code set for XLS formation Start ------------
		if($report_format=='XLS')
		{
			$dateSetHeading1 ="From: $day1-$month1-$year1 To $day2-$month2-$year2";		
			$dateSetHeading2 ="Search Results - $total";

			$dataHeader =array("sno"=>"S.No.","username"=>"Username","service"=>"Service","bill_dt"=>"Billing-Date","dueamount"=>"Due-Amount","duedate"=>"Due-Date","entryby"=>"Entry-By");	
			$totrec =count($values);		
			for($i=0; $i<$totrec; $i++)
			{
				unset($values["$i"]['profileid']);
				unset($values["$i"]['amount']);
			}
	
                	$dataSet =getExcelData($values,$dataHeader);
                	header("Content-Type: application/vnd.ms-excel");
                	header("Content-Disposition: attachment; filename=Due_amount_list_report.xls");
                	header("Pragma: no-cache");
                	header("Expires: 0");
			echo $dateSetHeading1."\n\n".$dateSetHeading2."\n\n".$dataSet ;
			die;
		}
		//-----------  New code set for XLS formation Ends  ------------


			$smarty->assign("ROW",$values); 

			if( $COUNTER == "SET" )
				$grp_no += 10;
			if( $COUNTER == "CLEAR")
				$grp_no -= 10;
			for($i=1,$k=1;$i<=$num_page;$i++,$k++)
			{
				if($pgno>=$num_page)
					break;
				if($grp_no>0 && $i==1)
				{
					$counter="CLEAR";
					$pgno = $grp_no;
					$links[]=array("lnk"=>"<td><a href=\"dueamountlist.php?user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&PAGE=$pgno&grp_no=$grp_no&COUNTER=$counter\"><<</a></td>");
				}
				if($k>10)
				{
					$counter="SET";				
					$pgno = $i + $grp_no;
					$links[]=array("lnk"=>"<td><a href=\"dueamountlist.php?user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&PAGE=$pgno&grp_no=$grp_no&COUNTER=$counter\">>></a></td>");
					break;
				}
				else
				{
					$pgno = $i + $grp_no;
					$links[]=array("lnk"=>"<td><a href=\"dueamountlist.php?user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&PAGE=$pgno&grp_no=$grp_no\">$pgno</a></td>");
				}
			}
			$smarty->assign("LINKS",$links);
			$smarty->assign("grp_no",$grp_no);
			
		}
		else
		{
			$smarty->assign("SEARCH","NO");
			$smarty->assign("message","please enter your search condition");
		}
		
		$smarty->assign("YEAR1",$year1);	
		$smarty->assign("MONTH1",$month1);	
		$smarty->assign("DAY1",$day1);	
		$smarty->assign("YEAR2",$year2);	
		$smarty->assign("MONTH2",$month2);	
		$smarty->assign("DAY2",$day2);	
		$smarty->assign("USER_NAME",$username);
		$smarty->assign("TOTAL",$total);
		$smarty->assign("NUM_PAGE",$num_page);
		$smarty->assign("PAGE",$PAGE);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		
		$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&grp_no=$grp_no";
		$smarty->assign("link_msg",$link_msg);
		$smarty->display("dueamountlist.htm");
	}
	else
	{
		$year=date('Y');
		$month=date('m');
		$day=date('d');
		$smarty->assign("PAGE",$PAGE);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&PAGE=$PAGE&grp_no=$grp_no";
		$smarty->assign("link_msg",$link_msg);
		$smarty->display("dueamountlist.htm");
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
