<?php
//die("Temporarily disabled");
/*********************************************************************************************
* FILE NAME   : entryfrm.php
* DESCRIPTION : Displays the billing entry form to get details like services being taken and
*               calculates the amount to be paid along with tax, and the due amount.
* MODIFY DATE        : 3 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Allow bill entry for old billing plans     
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../jsadmin/connect.inc");
//include("../crm/viewprofilenew.php");
//include(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
	$user=getname($cid);
	if($Continue)
	{
		$is_error=0;

/*		if($contadd1)
			$addrs[]=$contadd1;
		if($contadd2)
			$addrs[]=$contadd2;
		if($contadd3)
			$addrs[]=$contadd3;
		if(count($addrs)>0)
			$address=implode(",",$addrs);
*/
		if($City_India=="")
			$city=$ocity;
		else
			$city=$City_India;
		$resphone=$rpstd.$rphone;
		$offphone=$opstd.$ophone;
		
		if(trim($custname)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_CUSTNAME","Y");
		}	
		if($gender=="")
		{
			$is_error++;
			$smarty->assign("CHECK_GENDER","Y");
		}
		if(trim($address)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_ADDRESS","Y");
		}
		if(trim($city)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_CITY","Y");
		}
		if(trim($email)=="")
		{
		//	$is_error++;
		//	$smarty->assign("CHECK_EMAIL","Y");
		}
		else
		{
			$check=checkemail($email);
			if($check==1)
			{
				$is_error++;
				$smarty->assign("CHECK_EMAIL","Y1");
			}
		}
		if($Walkin=="")
		{
			$is_error++;
			$smarty->assign("CHECK_WALKIN","Y");
		}
		if($mode=="")
		{
			$is_error++;
			$smarty->assign("CHECK_MODE","Y");
		}
		elseif($mode != '')
		{
			if($curtype == '')
			{
				$is_error++;
				$smarty->assign("CHECK_CURTYPE","Y");
			}
		}
		if(trim($amount)=='')
		{
			$is_error++;
			$smarty->assign("CHECK_AMOUNT","Y");
		}
		if($mode=="CCOFFLINE")
		{
			if(trim($cdnum)=='')
                        {
                                $is_error++;
                                $smarty->assign("CHECK_CDNUM","Y");
                        }
		}	
		if($mode=="CASH")
		{
			if((trim($cdnum)!='') ||(trim($cd_city)!='') ||(trim($cd_date)!='') ||($Bank!=''))
                        {
                                $is_error++;
                                $smarty->assign("CHECK_MODE","Y");
                        }
		}
		
		if ($mode=="CHEQUE" || $mode=="DD")
		{
			if(trim($cdnum)=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDNUM","Y");
			}
			if($cd_date=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDDATE","Y");
			}
			if(trim($cd_city)=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDCITY","Y");
			}
			if($Bank=='')
			{
				$is_error++;
				$smarty->assign("CHECK_BANK","Y");
			}
			elseif($Bank=="Other")
			{
				if(trim($obank)=='')
				{
					$is_error++;
					$smarty->assign("CHECK_OBANK","Y");
				}
			}
		}

		if(trim($discount) == '' || ($discount)==0)
		{
			$reason='';
			$discount_type='';
		}
		elseif(trim($discount) != '' && ($discount)!=0)
		{
			if($reason=='')
			{
				$is_error++;
				$smarty->assign("CHECK_REASON","Y");
			}
			if($discount_type=='')
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_TYPE","Y");
			}
		
		}
		if($source!='A' && $service_type!="M" )
		{
			if($duration_sel=='')
			{
				$is_error++;
				$smarty->assign("CHECK_DURATION","Y");
        	        }
			if($service_type=='')
	                {
        	                $is_error++;
                	        $smarty->assign("CHECK_SERVICE_TYPE","Y");
	                }
			//if(($duration_sel=='1' && $service_type!='P') ||($duration_sel=='1' && count($addon_services)>0))
			if(($duration_sel=='1' && count($addon_services)>0))
			{
				$is_error++;
                                $smarty->assign("CHECK_SERVICE_TYPE","Y");
				$smarty->assign("CHECK_DURATION","Y");
			}
		}

		if(trim($username)=='')
		{
			$is_error++;
			$smarty->assign("CHECK_USERNAME","Y");
		}
		else
		{
			$sql="SELECT count(*) as cnt from newjs.JPROFILE where USERNAME='$username'"; 
			$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			if($myrow['cnt']==0)
			{
				$is_error++;
				$msg="Username does not exist";
				$smarty->assign("MSG_USERNAME",$msg);
			}
		}
		if($source!='A')
		{
		 	$smarty->assign("DURATION_SEL",$duration_sel);
		  	$smarty->assign("SERVICE_TYPE",$service_type);	
		}
		$sql="SELECT NAME,SERVICEID, desktop_RS, desktop_DOL from billing.SERVICES where PACKAGE = 'Y' AND ID > 6";
		$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
                       $services_list[] = array("NAME" =>$myrow["NAME"],
                                                "SERVICEID"=>$myrow["SERVICEID"],
                                                "PRICE_RS"=>($myrow["desktop_RS"]*(1-($TAX_RATE/100))),
                                                "PRICE_DOL"=>$myrow["desktop_DOL"]*(1-($TAX_RATE/100)));

		} 	
		$smarty->assign("SERVICES_LIST",$services_list);

//added for voice mail
/*
//                $sql="SELECT VOICEMAILID, NAME, PRICE_RS, PRICE_DOL from billing.VOICEMAIL";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($myrow=mysql_fetch_array($result))
                {
                         $values[] = array("voicemailid"=>$myrow["VOICEMAILID"],
                                          "name"=>$myrow["NAME"],
                                          "price_dol"=>$myrow["PRICE_DOL"],
                                          "price_rs"=>$myrow["PRICE_RS"],
                                         );
                }
                $smarty->assign("ROW",$values);
*/
//end
/*
		if($source != "A")
		{		
			if(is_array($service))
			{
				if(count($service)!=1)
				{
					$is_error++;
					$smarty->assign("CHECK_SERVICE","Y");
				}
				else
					$service_selected=$service[0];
			}	
			else
			{
				$is_error++;
				$smarty->assign("CHECK_SERVICE","Y");
			}
		}	
*/	
		
		$selectstring="NAME";
		if($curtype==0)
		{
			$selectstring.=", desktop_RS as price";
			$currency_str = "desktop_RS";
		}
		elseif($curtype==1)
		{
			$selectstring.=", desktop_DOL as price";
			$currency_str = "desktop_DOL";
		}
		if($source!='A')	
		{
			if($service_type!="M")
				$service_selected=$service_type.$duration_sel;	
			else
				$service_selected="M";
		}
		$sql="SELECT $selectstring from billing.SERVICES where SERVICEID='$service_selected'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		$service_name=$myrow['NAME'];
//		$actual_amount=$myrow['price'];
		$service_amount=($myrow["price"]*(1-($TAX_RATE/100)));
		
		$sql = "Select c.DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";
		$result_duration = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow_duration = mysql_fetch_array($result_duration);
		$duration = $myrow_duration["DURATION"];

		if(is_array($addon_services))
		{
			for($i=0;$i<count($addon_services);$i++)
				$addon_services[$i] = "'".$addon_services[$i].$duration."'";
			$addon_services_str = implode(",",$addon_services);
			if(strstr($addon_services_str,'B'))
                                        $smarty->assign("BOLD_LISTING_SELECTED","Y");
                                if(strstr($addon_services_str,'V'))
                                        $smarty->assign("VOICEMAIL_SELECTED","Y");
                                if(strstr($addon_services_str,'K'))
                                        $smarty->assign("KUNDLI_SELECTED","Y");
                                if(strstr($addon_services_str,'H'))
                                        $smarty->assign("HOROSCOPE_SELECTED","Y");
                                if(strstr($addon_services_str,'M'))
                                        $smarty->assign("MATRI_PROFILE_SELECTED","Y");
		



	
			$sql = "Select $currency_str as PRICE, NAME from billing.SERVICES where SERVICEID IN ($addon_services_str)";
			$result_price = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$addon_service_amount = 0;
			while($myrow_price = mysql_fetch_array($result_price))
			{
				$addon_services_amount += ($myrow_price["PRICE"]*(1-($TAX_RATE/100)));
				$addon_services_name[] = $myrow_price["NAME"];
			}
			$service_amount += $addon_services_amount;	
			if(is_array($addon_services_name))
				$addon_services_name_str = implode(",",$addon_services_name);
		}
		
		$actual_amount=$service_amount;


                $tax_amount = round(((($actual_amount-$discount) * $TAX_RATE)/100),2);
				$total_amount = floor($actual_amount + $tax_amount) ;
                
		if(!$discount || ($discount)==0)
                        $part_payment=$total_amount-$amount;
                else
		{
                        $part_payment=$total_amount-$amount-$discount;
			$total_amount -= $discount;
		}

		if($part_payment<0)
			$part_payment=0;
		if($part_payment >0)
		{
			if($due_day=='' || $due_month=='' || $due_year=='' )
			{
				$is_error++;
				$smarty->assign("CHECK_DUEDATE","Y");
			}
		}
		if(!$overseas)
			$overseas='N';
		if($is_error==0)
		{
			$sql="SELECT EMAIL from newjs.JPROFILE where USERNAME='$username'";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
                	$email_jprofile=$myrow['EMAIL'];
	                if($email == '')
			{
                	        $email=$email_jprofile;
				$warning='B';
			}
        	        elseif($email != $email_jprofile)
                	        $warning='Y';
                	$smarty->assign("WARNING",$warning);
	        	if(floor($part_payment)==0)
				$part_payment=0;
//		        $pmsg=viewprofile($username,"internal");
//        	        $smarty->assign("pmsg",$pmsg);
			if($due_year!='' && $due_month!='' && $due_day!='')
				$due_date=$due_year."-".$due_month."-".$due_day;
			$deposit_date=$dep_year."-".$dep_month."-".$dep_day;
			$smarty->assign("DEPOSIT_DATE",$deposit_date);
			$smarty->assign("DEPOSIT_BRANCH",$dep_branch);	
			$smarty->assign("SERVICE",$service);
			$smarty->assign("GENDER",$gender);	
			$smarty->assign("ADDRESS",$address);
			$smarty->assign("CITY",$city);
			$smarty->assign("PIN",$pin);
			$smarty->assign("EMAIL",$email);
			$smarty->assign("RESPHONE",$resphone);
			$smarty->assign("OFFPHONE",$offphone);
			$smarty->assign("MOBPHONE",$mphone);
			$smarty->assign("CONTADD1",$contadd1);
			$smarty->assign("CONTADD2",$contadd2);
			$smarty->assign("CONTADD3",$contadd3);
			$smarty->assign("OCITY",$ocity);
			$smarty->assign("RPSTD",$rpstd);	
			$smarty->assign("RPHONE",$rphone);	
			$smarty->assign("OPSTD",$opstd);	
			$smarty->assign("OPHONE",$ophone);	
			$smarty->assign("MPHONE",$mphone);	
		
			$smarty->assign("COMMENT",$comment);
			$smarty->assign("MODE",$mode);
			$smarty->assign("CURTYPE",$curtype);
			$smarty->assign("AMOUNT",$amount);
			$smarty->assign("DUE_DATE",$due_date);
			$smarty->assign("CDNUM",$cdnum);
			$smarty->assign("CD_DATE",$cd_date);
			$smarty->assign("CD_CITY",$cd_city);
			$smarty->assign("OVERSEAS",$overseas);	
			$smarty->assign("SEPARATEDS",$separateds);	
			$smarty->assign("DISCOUNT",$discount);
			$smarty->assign("REASON",$reason);
			$smarty->assign("DISCOUNT_TYPE",$discount_type);
			$smarty->assign("SERVICE_TYPE",$service_type);
			$smarty->assign("DURATION_SEL",$duration_sel);
			$smarty->assign("USERNAME",stripslashes($username)); //found some problem with "'; changes made by Alok on 15th Feb 2005
			$smarty->assign("SERVICE_SELECTED",$service_selected);

			$smarty->assign("SERVICE_NAME",$service_name);
			$smarty->assign("ADDON_SERVICE_NAMES",$addon_services_name_str);
			$smarty->assign("ADDON_SERVICES",$addon_services_str);
//added for voicemail
			$smarty->assign("VOICEMAIL_NAME",$voicemail_name);
			$smarty->assign("VOICEMAIL_SELECTED",$voicemail);
//end
			$smarty->assign("ACTUAL_AMOUNT",$actual_amount);
			$smarty->assign("PART_PAYMENT",$part_payment);
			$smarty->assign("CUSTNAME",$custname);
			$smarty->assign("BANK",$Bank);
			$smarty->assign("OBANK",$obank);
			$smarty->assign("WALKIN",$Walkin);
			$smarty->assign("LOGINNAME",$loginname);
			$smarty->assign("SOURCE",$source);
			$smarty->assign("CRM_ID",$crm_id);
			$smarty->assign("TAX_AMOUNT",$tax_amount);
			$smarty->assign("TAX_RATE",$TAX_RATE);
			$smarty->assign("TOTAL_AMOUNT",$total_amount);
			$smarty->assign("BOLD_LISTING_SELECTED",$bold_listing);
			//$smarty->assign("MATRI_PROFILE_SELECTED",$bold_listing);
			$smarty->assign("CID",$cid);
			$smarty->display("nextpage.htm");
		}
		else
		{

	                if($source=='A')
        	        {
                	        $sql="SELECT * from incentive.PAYMENT_COLLECT where ID='$crm_id' order by ID desc";
                        	$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	                        $myrow=mysql_fetch_array($result);
        	                $username=$myrow['USERNAME'];
                	        $smarty->assign("CUSTNAME",$myrow['NAME']);
                        	$smarty->assign("city_india",create_dd($myrow['CITY'],"City_India"));
	                        $smarty->assign("PIN",$myrow['PIN']);
        	                 $sid=$myrow['SERVICE'];
	                         $sql1="SELECT NAME,SERVICEID from billing.SERVICES where PACKAGE = 'Y' AND SERVICEID = '$sid'";
        	                $result1=mysql_query_decide($sql1) or die("$sql1<br>".mysql_error_js());
                	        while($myrow1=mysql_fetch_array($result1))
                        	{
	                        $smarty->assign("SERVICE_NAME",$myrow1['NAME']);
        	                }

				$smarty->assign("SERVICE_TYPE",$sid[0]);
				$smarty->assign("DURATION_SEL",$sid[1]);

				$smarty->assign("SERVICE_SELECTED",$myrow['SERVICE']);

                	        /*  Adding code to allow billing for services that were
                        	*   offered prior to May 1.
                        	*/

	                        if($myrow['SERVICE'] == 'S1')
        	                        $smarty->assign("SERVICE_SELECTED",'P3');
                	        elseif($myrow['SERVICE'] == 'S2')
                        	        $smarty->assign("SERVICE_SELECTED",'P6');
	                        elseif($myrow['SERVICE'] == 'S3')
        	                        $smarty->assign("SERVICE_SELECTED",'P12');
                	        elseif($myrow['SERVICE'] == 'S4')
                        	{
                                	$smarty->assign("SERVICE_SELECTED",'P3');
	                                $smarty->assign("BOLD_LISTING_SELECTED","Y");
        	                }
                	        elseif($myrow['SERVICE'] == 'S5')
                        	{
                                	$smarty->assign("SERVICE_SELECTED",'P6');
	                                $smarty->assign("BOLD_LISTING_SELECTED","Y");
        	                }
                	        elseif($myrow['SERVICE'] == 'S6')
                        	{
                        	        $smarty->assign("SERVICE_SELECTED",'P12');
                                	$smarty->assign("BOLD_LISTING_SELECTED","Y");
                        	}


	                        if(strstr($myrow['ADDON_SERVICEID'],'B'))
        	                        $smarty->assign("BOLD_LISTING_SELECTED","Y");
                	        if(strstr($myrow['ADDON_SERVICEID'],'V'))
                        	        $smarty->assign("VOICEMAIL_SELECTED","Y");
	                        if(strstr($myrow['ADDON_SERVICEID'],'K'))
        	                        $smarty->assign("KUNDLI_SELECTED","Y");
                	        if(strstr($myrow['ADDON_SERVICEID'],'H'))
                        	        $smarty->assign("HOROSCOPE_SELECTED","Y");
                	        if(strstr($myrow['ADDON_SERVICEID'],'M'))
                        	        $smarty->assign("MATRI_PROFILE_SELECTED","Y");
                	}

			for($i=0;$i<12;$i++)
                        {
                                $mmarr[$i]=$i+1;
                        }
                        for($i=0;$i<31;$i++)
                        {
                                $ddarr[$i]=$i+1;
                        }
                        for($i=0;$i<10;$i++)
                        {
                                $yyarr[$i]=$i+2006;
                        }
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);


			$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$i=0;
			while($row=mysql_fetch_array($res))
			{
				$dep_branch_arr[$i]=$row['NAME'];
				$i++;
       		        }
			$smarty->assign("dep_branch_arr",$dep_branch_arr);

			$smarty->assign("CRM_ID",$crm_id);		
			$smarty->assign("CUSTNAME",$custname);
			$smarty->assign("GENDER",$gender);	
/*
			$smarty->assign("CONTADD1",$contadd1);
			$smarty->assign("CONTADD2",$contadd2);
			$smarty->assign("CONTADD3",$contadd3);
*/
			$smarty->assign("ADDRESS",$address);
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
			$smarty->assign("DUE_DATE",$due_date);	
			$smarty->assign("CDNUM",$cdnum);	
			$smarty->assign("CD_DATE",$cd_date);	
			$smarty->assign("CD_CITY",$cd_city);	
			$smarty->assign("OVERSEAS",$overseas);	
			$smarty->assign("SEPARATEDS",$separateds);	
			$smarty->assign("BANK",$Bank);	
			$smarty->assign("OBANK",$obank);	

			$smarty->assign("DISCOUNT",$discount);	
			$smarty->assign("DISCOUNT_TYPE",$discount_type);	
			$smarty->assign("REASON",$reason);	
			//$smarty->assign("USERNAME",$username);	
			$smarty->assign("SERVICE_SELECTED",$service_selected);	
//added for voice mail
			$smarty->assign("VOICEMAIL_SELECTED",$voicemail);
//end
			$smarty->assign("city_india",create_dd($City_India,"City_India"));	
			$smarty->assign("bank",create_dd($Bank,"Bank"));	
			$smarty->assign("walkin",create_dd($Walkin,"Walkin"));	
			$smarty->assign("walkin1",$Walkin);
			$smarty->assign("LOGINNAME",$loginname);
			$smarty->assign("SOURCE",$source);
			$smarty->assign("SERVICE_SELECTED",$service_selected);
			$smarty->assign("USERNAME",stripslashes($username)); //found some problem with "'; changes made by Alok on 15th Feb 2005
			$smarty->assign("CID",$cid);
			$smarty->assign("due_day",$due_day);
			$smarty->assign("due_month",$due_month);
			$smarty->assign("due_year",$due_year);
			$smarty->assign("DEP_DAY",$dep_day);
			$smarty->assign("DEP_MONTH",$dep_month);
			$smarty->assign("DEP_YEAR",$dep_year);
			$smarty->assign("dep_branch",$dep_branch);

			$smarty->display("entryfrm.htm");
		}
	}
	elseif($Logout)
	{
		logout($cid);
		$smarty->display("index.htm");	
	}
	else
	{
		$sql = "Select INCOMPLETE from newjs.JPROFILE where PROFILEID = '$pid'";	
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow = mysql_fetch_array($result);
		if($myrow['INCOMPLETE'] == 'Y')
		{
			$msg = "Billing entry not allowed for incomplete profile.";
			$msg .= "<br><a href=\"search_user.php?cid=$cid\">Click here to go back</a>";
		        $smarty->assign("MSG",$msg);
		        $smarty->display("billing_msg.tpl");
			exit;
		}
		if($source=='A')
		{
			$sql="SELECT * from incentive.PAYMENT_COLLECT where ID='$crm_id' order by ID desc";
			$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$username=$myrow['USERNAME'];
			$smarty->assign("CUSTNAME",$myrow['NAME']);
			$smarty->assign("city_india",create_dd($myrow['CITY'],"City_India"));
			$smarty->assign("PIN",$myrow['PIN']);
			$sid=$myrow['SERVICE'];	
			 $sql1="SELECT NAME,SERVICEID from billing.SERVICES where PACKAGE = 'Y' AND SERVICEID = '$sid'";
	                $result1=mysql_query_decide($sql1) or die("$sql1<br>".mysql_error_js());
        	        while($myrow1=mysql_fetch_array($result1))
                	{
			$smarty->assign("SERVICE_NAME",$myrow1['NAME']);
			}

			/*  Adding code to allow billing for services that were 
			 *  offered prior to May 1.	
			*/
			 $smarty->assign("SERVICE_SELECTED",$myrow['SERVICE']);	
			if($myrow['SERVICE'] == 'S1')
				$smarty->assign("SERVICE_SELECTED",'P3');		
			elseif($myrow['SERVICE'] == 'S2')
                                $smarty->assign("SERVICE_SELECTED",'P6');
                        elseif($myrow['SERVICE'] == 'S3')
                                $smarty->assign("SERVICE_SELECTED",'P12');
                        elseif($myrow['SERVICE'] == 'S4')
			{
                                $smarty->assign("SERVICE_SELECTED",'P3');
                                $smarty->assign("BOLD_LISTING_SELECTED","Y");
			}
                        elseif($myrow['SERVICE'] == 'S5')
			{
                                $smarty->assign("SERVICE_SELECTED",'P6');
                                $smarty->assign("BOLD_LISTING_SELECTED","Y");
			}
                        elseif($myrow['SERVICE'] == 'S6')
			{
                                $smarty->assign("SERVICE_SELECTED",'P12');
                                $smarty->assign("BOLD_LISTING_SELECTED","Y");
			}
			if(strstr($myrow['ADDON_SERVICEID'],'B'))
				$smarty->assign("BOLD_LISTING_SELECTED","Y");
			if(strstr($myrow['ADDON_SERVICEID'],'V'))
                                $smarty->assign("VOICEMAIL_SELECTED","Y");
                        if(strstr($myrow['ADDON_SERVICEID'],'K'))
                                $smarty->assign("KUNDLI_SELECTED","Y");
                        if(strstr($myrow['ADDON_SERVICEID'],'H'))
                                $smarty->assign("HOROSCOPE_SELECTED","Y");
                        if(strstr($myrow['ADDON_SERVICEID'],'M'))
                                $smarty->assign("MATRI_PROFILE_SELECTED","Y");
		}
		else
		{
			$sql="SELECT USERNAME,GENDER,CONTACT,CITY_RES,PINCODE,PHONE_RES,PHONE_MOB from newjs.JPROFILE where PROFILEID='$pid'";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$username=$myrow['USERNAME'];
			$smarty->assign("GENDER",$myrow['GENDER']);
//			$smarty->assign("ADDRESS",$myrow['CONTACT']);
			$smarty->assign("city_india",create_dd($myrow['CITY_RES'],"City_India"));
			$smarty->assign("PIN",$myrow['PINCODE']);
//			$smarty->assign("RPHONE",$myrow['PHONE_RES']);
//			$smarty->assign("MPHONE",$myrow['PHONE_MOB']);
		}

			

/*commented by aman for selection through combo

$smarty->assign("USERNAME",stripslashes($username)); //found some problem with "'; changes made by Alok on 15th Feb 2005
			$sql="SELECT NAME, SERVICEID, PRICE_RS, PRICE_DOL from billing.SERVICES where PACKAGE = 'Y' AND ID >'6' ";
			$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
	                        $services_list[] = array("NAME" =>$myrow["NAME"],
                                                "SERVICEID"=>$myrow["SERVICEID"],
                                                "PRICE_RS"=>$myrow["PRICE_RS"],
                                                "PRICE_DOL"=>$myrow["PRICE_DOL"]);
			} 	
			$smarty->assign("SERVICES_LIST",$services_list);*/

//		if ($source != "A")		
//			$smarty->assign("city_india",create_dd($City_India,"City_India"));	


//added for voice mail
/*		$sql="SELECT VOICEMAILID, NAME, PRICE_RS, PRICE_DOL from billing.VOICEMAIL";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			 $values[] = array("voicemailid"=>$myrow["VOICEMAILID"],
                                          "name"=>$myrow["NAME"],
                                          "price_dol"=>$myrow["PRICE_DOL"],
                                          "price_rs"=>$myrow["PRICE_RS"],
                                         );
		}
		$smarty->assign("ROW",$values);
//end
*/
		
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2006;
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);


	
		$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $dep_branch_arr[$i]=$row['NAME'];
                        $i++;
                }
		$center=strtoupper(getcenter_for_walkin($user));	



		$dd_arr=explode("-",Date('Y-m-d'));
                $smarty->assign("DEP_DAY",$dd_arr[2]);
                $smarty->assign("DEP_MONTH",$dd_arr[1]);
                $smarty->assign("DEP_YEAR",$dd_arr[0]);
                
	
		$smarty->assign("dep_branch",$center);
		$smarty->assign("dep_branch_arr",$dep_branch_arr);
		$smarty->assign("USERNAME",$myrow['USERNAME']);
		$smarty->assign("bank",create_dd($Bank,"Bank"));	
		$smarty->assign("walkin",create_dd($Walkin,"Walkin"));	
		$smarty->assign("LOGINNAME",$user);
		$smarty->assign("DISCOUNT","0");
	        $smarty->assign("CID",$cid);
	        $smarty->assign("SOURCE",$source);
		$smarty->assign("CRM_ID",$crm_id);
        	$smarty->display("entryfrm.htm");
	}
}
else
{
        $msg="Your session is timed out";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                 
}
?>
                                                                                                 

