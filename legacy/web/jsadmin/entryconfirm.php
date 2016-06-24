<?php
include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
if(authenticated($cid))
{
	if($Done)
	{
		$loginname=getname($cid);
		$sql="SELECT CENTER,EMAIL from jsadmin.PSWRDS where USERNAME='$loginname'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		//$center=$myrow['CENTER'];
		$center=getcenter_for_walkin($walkin);
	
		$sql="SELECT PROFILEID from newjs.JPROFILE where USERNAME='$username'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		$profileid=$myrow['PROFILEID'];
		
		if($curtype == "0")		
			$type="RS";
		elseif($curtype== "1")
			$type= "DOL";
		if($bank=="Other")
		{
			$bankfeed=$obank;
			$obank="Y";
		}
		else
		{
			$bankfeed=$bank;
			$obank="N";
		}
		if($service_selected=='S1' || $service_selected=='S2' || $service_selected=='S3')
			$subscription='F';
		elseif($service_selected=='S4' ||$service_selected=='S5' || $service_selected=='S6')
			$subscription='B,F';
		
		$sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$subscription' where PROFILEID='$profileid'";
		mysql_query_decide($sql) or die("DIE");

		$sql="INSERT into billing.PURCHASES (SERVICEID, PROFILEID, USERNAME, NAME, ADDRESS, GENDER, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE, COMMENT, OVERSEAS, DISCOUNT, DISCOUNT_TYPE, DISCOUNT_REASON,WALKIN, CENTER, ENTRYBY, DUEAMOUNT, DUEDATE, ENTRY_DT, STATUS) values ('$service_selected','$profileid','$username','$custname','$address','$gender','$city','$pin','$email','$resphone','$offphone','$mobphone','$comment','$overseas','$discount','$discount_type','$reason','$walkin','$center','$loginname','$part_payment','$due_date',now(),'DONE')";
		mysql_query_decide($sql);
		
		$billid=mysql_insert_id_js();
		
		$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, STATUS, ENTRY_DT, ENTRYBY) values ('$profileid','$billid','$mode','$type','$amount','$cdnum','$cd_date','$cd_city','$bankfeed','$obank','DONE',now(),'$loginname')"; 	
		mysql_query_decide($sql);
	
		$receiptid= mysql_insert_id_js();	
		$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$service_selected'";		
		$result=mysql_query_decide($sql);	
		$myrow=mysql_fetch_array($result);
		if($myrow['PACKAGE']!="Y")
		{
			$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
			$myrow1=mysql_fetch_array(mysql_query_decide($sql));
			
			$sql="INSERT into billing.SERVICE_STATUS (BILLID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT) values ('$billid','$service_selected','$myrow[COMPID]','Y',now(),'$loginname',ADDDATE(now(), INTERVAL $myrow1[DURATION] MONTH))";
			mysql_query_decide($sql);
		}
		if($myrow['PACKAGE']=="Y")	
		{
			$packid=$myrow['PACKID'];
			$sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";			 $result=mysql_query_decide($sql);
			while($myrow1=mysql_fetch_array($result))
			{
				$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
				$myrow2=mysql_fetch_array(mysql_query_decide($sql));
				$sql="INSERT into billing.SERVICE_STATUS (BILLID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$billid','$service_selected','$myrow1[COMPID]','Y',now(),'$loginname',ADDDATE(now(), INTERVAL $myrow2[DURATION] MONTH))";
				mysql_query_decide($sql);
			}
		}	
		$subject = "Bill for your membership subscription";
		
		$msg = "Dear $username,\n\nThank you for subscribing to Jeevansathi.com.\n\nWe have received your payment of $type $amount on ".date('d-m-Y').".\n \nCopy of your bill (Bill.rtf) has been attached with this mail. Kindly revert back for any discrepancies in the bill.\n\nRegards,\nJeevansathi.com Team";
		$bill = printbill($receiptid,$billid);
		$sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME='$walkin'";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result) or die("$sql".mysql_error_js());
		$walkinemail= $myrow['EMAIL'];
		send_email("$email",$msg,$subject,'',$walkinemail,"kush.asthana@naukri.com",$bill);

		$msg = "Entries successfully done<br><br>";
		$msg .= "<a href=\"billingview.php?user=$loginname&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";
		$smarty->assign("name",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	
	}	
	elseif($Edit)
	{
		$smarty->assign("CUSTNAME",$custname);
		$smarty->assign("GENDER",$gender);
		$smarty->assign("CONTADD1",$contadd1);
		$smarty->assign("CONTADD2",$contadd2);
		$smarty->assign("CONTADD3",$contadd3);
		$smarty->assign("OCITY",$ocity);
		$smarty->assign("PIN",$pin);
		$smarty->assign("EMAIL",$email);
		$smarty->assign("RPSTD",$rpstd);
		$smarty->assign("RPHONE",$rphone);
		$smarty->assign("OPSTD",$opstd);
		$smarty->assign("OPHONE",$ophone);
		$smarty->assign("MPHONE",$mphone);
		$smarty->assign("COMMENT",$comment);
		$smarty->assign("MODE",$mode);
		$smarty->assign("CURTYPE",$curtype);
		$smarty->assign("AMOUNT",$amount);
		$smarty->assign("DUE_DAY",$due_day);
                $smarty->assign("DUE_MONTH",$due_month);
                $smarty->assign("DUE_YEAR",$due_year);
                $smarty->assign("CDNUM",$cdnum);
		$smarty->assign("CD_DAY",$cd_day);
		$smarty->assign("CD_MONTH",$cd_month);
		$smarty->assign("CD_YEAR",$cd_year);
		$smarty->assign("CD_CITY",$cd_city);
		$smarty->assign("OVERSEAS",$overseas);
		$smarty->assign("SEPERATEDS",$separateds);
		$smarty->assign("OBANK",$obank);
		$smarty->assign("DISCOUNT",$discount);
		$smarty->assign("REASON",$reason);
		$smarty->assign("DISCOUNT_TYPE",$discount_type);
		$smarty->assign("USERNAME",$username);
		$smarty->assign("BANK",$bank);

		$sql="SELECT NAME,SERVICEID, PRICE_RS, PRICE_DOL from billing.SERVICES";
                $result=mysql_query_decide($sql);
                $i=1;
                while($myrow=mysql_fetch_array($result))
                {
                        $smarty->assign("NAME$i",$myrow['NAME']);
                        $smarty->assign("VALUE$i",$myrow['SERVICEID']);
                        $smarty->assign("RS$i",$myrow['PRICE_RS']);
                        $smarty->assign("DOL$i",$myrow['PRICE_DOL']);
                        $i++;
                }
		$smarty->assign("SERVICE_SELECTED",$service_selected);	
 
		$City_India=$city;
		$Bank=$bank;
		$Walkin=$walkin;

		$smarty->assign("city_india",create_dd($City_India,"City_India"));
		$smarty->assign("bank",create_dd($Bank,"Bank"));
		$smarty->assign("walkin",create_dd($Walkin,"Walkin"));
		$smarty->assign("LOGINNAME",$loginname);
		$smarty->assign("CID",$cid);
		$smarty->display("entryfrm.htm");
	}		
	elseif($Logout)
	{
		logout($cid);
		$smarty->display("index.htm");
	}
	
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("../billing_msg.tpl");
                                                                                                 
}
?>

