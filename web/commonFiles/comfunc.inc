<?php
include_once(JsConstants::$docRoot."/classes/class.phpmailer.php");
include_once(JsConstants::$docRoot."/classes/class.smtp.php");
include_once(JsConstants::$docRoot."/../lib/model/validator/InvalidEmails.class.php");

function send_email($to,$msg="",$subject="",$from="",$cc="",$bcc="",$attach="",$filetype="",$filename="",$registration="",$html="1",$reply_to="",$from_name="")
{
	$domain = strstr($to, '@');
	$dotpos = strrpos($domain,".");
	$domain = substr($domain,1,$dotpos-1);
	$domain = strtolower($domain);
	//check for invalid domains
	$emailArr = InvalidEmails::getInvalidSendMailArr();
	$flagValidEmail=true;
	if(in_array(strtolower($domain),$emailArr)) 
	{
		$flagValidEmail=false;
	}
	if(trim(strtolower($to))!="abc@mail.com" && !stristr($to,"@jsxyz.com")  && $flagValidEmail)
        {
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->Host= JsConstants::$mailHost.";".JsConstants::$localHostIp;
		$mail->Port= JsConstants::$mailPort;

		if($subject=="")
			$mail->Subject= "Info from jeevansathi.com";
		else
			$mail->Subject= $subject;
		if($from=="")
			$mail->From="webmaster@jeevansathi.com";
		else
			$mail->From="$from";
		//$mail->Body="$msg";

		$receiver=explode(',',$to);
		$rec_count=count($receiver);
		do
		{
			$rec_count--;
			$mail->AddAddress($receiver[$rec_count]);
		}while($rec_count);

		if(trim($from_name))
			$mail->FromName=trim($from_name);
		else
			$mail->FromName="Jeevansathi.com";

		//If reply is set.
		if($reply_to)
		{
			$mail->AddReplyTo($reply_to);
		}

		if($cc != "")
		{
			$CC=explode(',',$cc);
                	$cc_count=count($CC);
                	do
                	{
                	        $cc_count--;
                	        $mail->AddCC($CC[$cc_count]);
                	}while($cc_count);
		}
		if($bcc != "")
		{
                        $BCC=explode(',',$bcc);
                        $bcc_count=count($BCC);
                        do
                        {
                                $bcc_count--;
                                $mail->AddBCC($BCC[$bcc_count]);
                        }while($bcc_count);
                }

		if($attach)
		{
			if(!$filename)
				$filename="BILL.pdf";
				//$filename="BILL.rtf";
			if(!$filetype)
				$filetype='application/octet-stream';
			$mail->AddStringAttachment($attach,$filename,'base64',$filetype);
		}

		if($html=='1') {
			$mail->IsHTML(true);
    }
		$mail->Body=$msg;
		if(!$mail->Send())
		{
 			//echo "Mailer Error: " . $mail->ErrorInfo;
		}
		else
		{
			return true;
		}
	
	}
}

/****************************************Function to send multiple attchments in a mail****************************/
function send_email_attach($to,$msg="",$subject="",$from="",$cc="",$bcc="",$attachments)
{

        if(trim(strtolower($to))!="abc@mail.com" && !stristr($to,"@jsxyz.com"))
        {
                $mail=new PHPMailer();
                $mail->IsSMTP();
                $mail->Host= JsConstants::$mailHost.";".JsConstants::$localHostIp;
                $mail->Port= 25;

                if($subject=="")
                        $mail->Subject= "Info from jeevansathi.com";
                else
                        $mail->Subject= $subject;
                if($from=="")
                        $mail->From="webmaster@jeevansathi.com";
                else
                        $mail->From="$from";
                //$mail->Body="$msg";

                $receiver=explode(',',$to);
                $rec_count=count($receiver);
                do
                {
                        $rec_count--;
                        $mail->AddAddress($receiver[$rec_count]);
                }while($rec_count);

                $mail->FromName="Jeevansathi.com";

                if($cc != "")
                {
                        $CC=explode(',',$cc);
                        $cc_count=count($CC);
			do
                        {
                                $cc_count--;
                                $mail->AddCC($CC[$cc_count]);
                        }while($cc_count);
                }
                if($bcc != "")
                {
                        $BCC=explode(',',$bcc);
                        $bcc_count=count($BCC);
                        do
                        {
                                $bcc_count--;
                                $mail->AddBCC($BCC[$bcc_count]);
                        }while($bcc_count);
                }
		if(is_array($attachments))
		{
			foreach($attachments as $k=>$v)
			{
				$k=$k.".htm";
				$mail->AddStringAttachment($v,$k);
			}
		}

                $mail->IsHTML(true);
                $mail->Body=$msg;
                if(!$mail->Send())
                {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                        return true;        
		}
	}
}

function astro_mail($username,$servefor,$bill_email)
{
	if(strstr($servefor,"H") && strstr($servefor,"K"))	
	{
		$subject="Jeevansathi.com - Kundali Matcher and Horoscope Activation";
		$msg_astro = "Dear $username<br><br>Thank you for subscribing to JeevanSathi Kundali Matcher and Horoscope Services.<br><br>The Kundali Matcher will enable you to match your kundali with all the other members who are willing to provide their astro-data on jeevansathi.com. The Horoscope service will enable you to create and display your horoscope along with your matrimonial profile.<br><br>Kindly <a href=\"http://www.jeevansathi.com/P/activate_astro.php?servefor=$servefor\">click here </a>to activate JeevanSathi Kundali Matcher and Horoscope Services.<br><br>(IMPORTANT: If you ignore the activation process, you will not be able to match your kundali with the other profiles neither your horoscope will be displayed with your profile on our website.)<br><br><br>With Regards <br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\">www.jeevansathi.com</a>";
	}
	elseif(strstr($servefor,"H"))
	{
		$subject="Jeevansathi.com-Horoscope Activation";
		$msg_astro= "Dear $username<br><br>Thank you for subscribing to the JeevanSathi Horoscope Service.<br><br>The Jeevansathi Horoscope service will enable you to create and display your horoscope along with your matrimonial profile.<br><br>Kindly <a href=\"http://www.jeevansathi.com/P/activate_astro.php?servefor=$servefor\">click here </a>to activate JeevanSathi Horoscope Service.<br><br>(IMPORTANT: If you ignore the activation process, your Horoscope will not be viewable to the other members.)<br><br><br>With Regards <br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\">www.jeevansathi.com</a>";
	} 
	elseif(strstr($servefor,"K"))
	{
		$subject="Jeevansathi.com - Kundali Matcher Activation";
		$msg_astro= "Dear $username <br><br>Thank you for subscribing to the JeevanSathi Kundali Matcher Service.<br><br>This service will enable you to match your kundali with all the other members who are willing to provide their Astro Data on Jeevansathi.com.<br><br>>Kindly <a href=\"http://www.jeevansathi.com/P/activate_astro.php?servefor=$servefor\">click here </a>to activate JeevanSathi Kundali Matcher Service.<br><br>(IMPORTANT: If you ignore the activation process, you will not be able to match your kundali with the other profiles.)<br><br><br>With Regards <br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\">www.jeevansathi.com</a>";
	}
	send_email($bill_email,$msg_astro,$subject,'','','aman.sharma@jeevansathi.com');
		
}

if(!function_exists("numtxt1"))
{
function numtxt1($b,$fto="0")
{
	if($fto)
		$Txt = array(1=>"one ", 2=>"two ", 3=>"three ", 4=>"four ", 5=>"five ", 6=>"six ", 7=>"seven ",8=>"eight ", 9=>"nine ", 10=>"ten ", 11=>"eleven ", 12=>"twelve ", 13=>"thirteen ", 14=>"fourteen ", 15=>"fifteen ", 16=>"sixteen ", 17=>"seventeen ", 18=>"eighteen ", 19=>"nineteen ", 20=>"twenty ", 30=>"thirty ", 40=>"forty ", 50=>"fifty ", 60=>"sixty ", 70=>"seventy ", 80=>"eighty ", 90=>"ninety ");
	else
		$Txt = array(1=>"One ", 2=>"Two ", 3=>"Three ", 4=>"Four ", 5=>"Five ", 6=>"Six ", 7=>"Seven ",8=>"Eight ", 9=>"Nine ", 10=>"Ten ", 11=>"Eleven ", 12=>"Twelve ", 13=>"Thirteen ", 14=>"Fourteen ", 15=>"Fifteen ", 16=>"Sixteen ", 17=>"Seventeen ", 18=>"Eighteen ", 19=>"Nineteen ", 20=>"Twenty ", 30=>"Thirty ", 40=>"Forty ", 50=>"Fifty ", 60=>"Sixty ", 70=>"Seventy ", 80=>"Eighty ", 90=>"Ninety ");

        $L = floor($b / 100000);
        $T = floor(($b % 100000) / 1000);
        $H = floor(($b % 1000) / 100);
        if($b%100 > 19)
                $ten = (floor(($b % 100) / 10)) * 10;
        else
                $ten = $b % 100;
        if($L > 0)
        {
                if($T > 0)
                        $t0 = "Lakh ";
                else
                        $t0 = "Lakh And ";
        }
        if($T > 0)
        {
                if($H >= 0)
                        $t1 = "Thousand ";
                else
                        $t1 = "Thousand And ";
        }
        if($H > 0)
        {
                if($ten > 0)
                        $t2 = "Hundred And ";
                else
                        $t2 = "Hundred ";
        }
        if($ten > 19)
        {
                $t3 = $Txt[$b % 10];
        }
        $numtxt = $Txt[$L].$t0.$Txt[$T].$t1.$Txt[$H].$t2.$Txt[$ten]." ".$t3;
        return $numtxt;
}
}
if(!function_exists("convert"))
{
function convert($b,$fto="0")
{
        $b = $b*1;
//        if($b == 0)
//                $x = "Rupees Zero";
        if($b < 10000)
                $x = numtxt1($b,$fto);
        else
                $x = numtxt1(floor($b/1000))." Thousand ".numtxt1($b%1000);
        return $x;
}
}
function misc_rev_printbill($receiptid,$billid="")
{
        global $smarty;
	$sql="SELECT SALEID, ENTRY_DT,TDS from billing.REV_PAYMENT where RECEIPTID='$receiptid'";
	$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	$myrow=mysql_fetch_array($result);
	$billid=$myrow['SALEID'];
	$billdate=$myrow['ENTRY_DT'];
        $billyear= substr($billdate,2,2);                                  
        $inv_dt_arr=explode(" ",$billdate);
	$tds=$myrow["TDS"];	
        list($inv_year,$inv_month,$inv_day)=explode("-",$inv_dt_arr[0]);	 
        $inv_date=my_format_date($inv_day,$inv_month,$inv_year);

        $sql="SELECT a.PROFILEID,a.NAME, a.ADDRESS,a.USERNAME,a.CPP,b.SALE_AMT,b.SERVICE_TAX,b.TOTAL_AMT from marriage_bureau.BUREAU_PROFILE as a,billing.REV_MASTER as b where a.PROFILEID=b.BUREAU_PID and b.SALEID='$billid'";
        $result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        $myrow=mysql_fetch_array($result);
        
	
	$billid_toassign.=$billid;
        $smarty->assign("name",$myrow['NAME']);
        $smarty->assign("address",$myrow['ADDRESS']);
        $smarty->assign("receiptid",$receiptid);
        $smarty->assign("accountid","BR-".$myrow['PROFILEID']);
        $smarty->assign("username",$myrow['USERNAME']);
        $smarty->assign("date",$inv_date);

                                                                                                 
        $sql="SELECT MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, ENTRY_DT from billing.REV_PAYMENT where RECEIPTID= '$receiptid'";
        $result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        $myrow1=mysql_fetch_array($result);
	list($rec_dt,$rec_time)=explode(" ",$myrow1['ENTRY_DT']);
        $r_dt=explode("-",$rec_dt);
        $r_dt1='';
        for($i=count($r_dt),$k=0;$i>=0;$i--,$k++)
                $r_dt1.=$r_dt[$i]."-";
        $rdt=substr($r_dt1,1,(strlen($r_dt1)-2));
        $cd_dt=explode("-",$myrow1['CD_DT']);
        $cd_dt1='';
        for($i=count($cd_dt),$k=0;$i>=0;$i--,$k++)
                $cd_dt1.=$cd_dt[$i]."-";
        $cddt=substr($cd_dt1,1,(strlen($cd_dt1)-2));
        $smarty->assign("mode",$myrow1['MODE']);
        $smarty->assign("type",$myrow1['TYPE']);
        $smarty->assign("cdnum",$myrow1['CD_NUM']);
        $smarty->assign("cddt",$cddt);
        $smarty->assign("receipt_date",$rdt);
        $smarty->assign("cdcity",$myrow1['CD_CITY']);
                                                                                                 
        $feevalue=$myrow1[AMOUNT];
        $smarty->assign("feevalue",$feevalue);
	list($costvalue_rs,$costvalue_paise)=explode(".",$feevalue);
	if($costvalue_paise=='')
		$costvalue_paise= '00';
	elseif(strlen($costvalue_paise) == 1)
		$costvalue_paise .= '0';
        $smarty->assign("costvalue",$feevalue);
        $smarty->assign("costvalue_rs",$costvalue_rs);
        $smarty->assign("costvalue_paise",$costvalue_paise);
	
	$cpp=$myrow["CPP"];	
	$sale_amt=$myrow["SALE_AMT"];
	//$no_of_views=((int)($sale_amt/$cpp));	
	$tax_amt=$myrow["SERVICE_TAX"];	
	$total_amt=$myrow["TOTAL_AMT"];	
	$no_of_views=((int)($sale_amt/$cpp));	
	
	list($cpp_rs,$cpp_paise)=explode(".",$cpp);
        if($cpp_paise=='')
                $cpp_paise= '00';
        elseif(strlen($cpp_paise) == 1)
                $cpp_paise .= '0';

	list($cpp_rs,$cpp_paise)=explode(".",$cpp);
        if($cpp_paise=='')
                $cpp_paise= '00';
        elseif(strlen($cpp_paise) == 1)
                $cpp_paise .= '0';
 
	list($sale_amt_rs,$sale_amt_paise)=explode(".",$sale_amt);
        if($sale_amt_paise=='')
                $sale_amt_paise= '00';
        elseif(strlen($sale_amt_paise) == 1)
                $sale_amt_paise .= '0';


	list($tax_amt_rs,$tax_amt_paise)=explode(".",$tax_amt);
        if($tax_amt_paise=='')
                $tax_amt_paise= '00';
        elseif(strlen($tax_amt_paise) == 1)
                $tax_amt_paise .= '0';

	list($tds_rs,$tds_paise)=explode(".",$tds);
        if($tds_paise=='')
                $tds_paise= '00';
        elseif(strlen($tds_paise) == 1)
                $tds_paise .= '0';

	list($total_amt_rs,$total_amt_paise)=explode(".",$total_amt);
        if($total_amt_paise=='')
                $total_amt_paise= '00';
        elseif(strlen($total_amt_paise) == 1)
                $total_amt_paise .= '0';
	

        $smarty->assign("cpp",$cpp);
        $smarty->assign("no_of_views",$no_of_views);
        $smarty->assign("cpp_rs",$cpp_rs);
        $smarty->assign("cpp_paise",$cpp_paise);
        $smarty->assign("sale_amt",$sale_amt);
        $smarty->assign("sale_amt_rs",$sale_amt_rs);
        $smarty->assign("sale_amt_paise",$sale_amt_paise);
        $smarty->assign("tax_amt",$tax_amt);
        $smarty->assign("tax_amt_rs",$tax_amt_rs);
        $smarty->assign("tax_amt_paise",$tax_amt_paise);
        $smarty->assign("tds",$tds);
        $smarty->assign("tds_rs",$tds_rs);
        $smarty->assign("tds_paise",$tds_paise);
        $smarty->assign("total_amt",$total_amt);
        $smarty->assign("total_amt_rs",$total_amt_rs);
        $smarty->assign("total_amt_paise",$total_amt_paise);
	$billid_toassign="JR-".$billid;
        $smarty->assign("billid",$billid_toassign);
        $cost_rs=convert($costvalue_rs);
        if($costvalue_paise != '' && $costvalue_paise != '00' && $costvalue_paise != '0')
        {
                if($myrow1['TYPE']=="RS")
                        $cost_paise=convert ($costvalue_paise)."paise";
                elseif($myrow1['TYPE']=="DOL")
                        $cost_paise=convert ($costvalue_paise)."cents";
                $cost=$cost_rs."and ".$cost_paise." only";
        }
        else
                $cost=convert($costvalue_rs)." only";
        $smarty->assign("cost",$cost);

	$total_amt_rs1=convert($total_amt_rs);
        if($total_amt_paise != '' && $total_amt_paise != '00' && $total_amt_paise != '0')
        {                 if($myrow1['TYPE']=="RS")
                        $total_amt_paise1=convert ($total_amt_paise)."paise";
                elseif($myrow1['TYPE']=="DOL")
                        $total_amt_paise1=convert ($total_amt_paise)."cents";
                $total_amt_words=$total_amt_rs1."and ".$total_amt_paise1." only";
        }
        else
                $total_amt_words=convert($total_amtvalue_rs)." only";
        $smarty->assign("total_amt_words",$total_amt_words);

        $output = $smarty->fetch("misc_rev_bill.rtf");
        if($output == "ERROR")
        {
                echo "ERROR";
                exit;
        }
        return $output;
}


function misc_rev_sales_printbill($receiptid='',$saleid,$saleType='')
{
        global $smarty;
	if($saleType=='P')
		$receiptid='';

        $sql="SELECT SHIP_TO_ADDRESS,SHIP_TO_COUNTRY,SHIP_TO_PIN,SALE_AMT,SERVICE_TAX,DISCOUNT,ENTRY_DT,TOTAL_AMT,COMP_NAME,SALE_DES,START_DATE,END_DATE,SALE_BY,CUR_TYPE,TAX_RATE,SERVICE_TAX_CONTENT from billing.REV_MASTER r WHERE SALEID='$saleid'";
        $result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        $myrow=mysql_fetch_array($result);

        $smarty->assign("comp_name",$myrow['COMP_NAME']);
        $smarty->assign("address",$myrow['SHIP_TO_ADDRESS']);
        $smarty->assign("receiptid",$receiptid);
        $smarty->assign("accountid","JR-".$saleid);
        $smarty->assign("country",$myrow['SHIP_TO_COUNTRY']);
        $smarty->assign("pin",$myrow['SHIP_TO_PIN']);
        $smarty->assign("sale_des",$myrow['SALE_DES']);
        $smarty->assign("sale_by",$myrow['SALE_BY']);
        $smarty->assign("discount",$myrow['DISCOUNT']);
        $start_dt =date("d-M-Y",JSstrToTime($myrow['START_DATE']));
        $end_dt =date("d-M-Y",JSstrToTime($myrow['END_DATE']));
        $smarty->assign("start_dt",$start_dt);
        $smarty->assign("end_dt",$end_dt);
        $order_date =date("d-M-Y",JSstrToTime($myrow['ENTRY_DT']));
        $smarty->assign("order_date",$order_date);
	    $tax_rate =$myrow['TAX_RATE'];
	    $service_tax_content ="Service Tax ".$myrow['SERVICE_TAX_CONTENT'];
	    $yearFin =date("y");
        $yearFinNxt =$yearFin+1;
        $finYear =$yearFin.$yearFinNxt;
	if($receiptid)
		$uniqueId =$receiptid;
	else
		$uniqueId =$saleid+1;
        $saleid_toassign .=$finYear."-".$saleid."P".$uniqueId;
        $smarty->assign("saleid_toassign",$saleid_toassign);

	if($receiptid){
	        $sql="SELECT SERVICE_TAX,BILL_TO_NAME,BILL_TO_ADDRESS,BILL_TO_COUNTRY,BILL_TO_PIN,TDS,MODE,TYPE,AMOUNT,CD_NUM,CD_DT,CD_CITY,ENTRY_DT,DEPOSIT_BRANCH from billing.REV_PAYMENT where RECEIPTID= '$receiptid'";
        	$result=mysql_query_decide($sql)  or die("$sql<br>".mysql_error_js());
        	$myrow1=mysql_fetch_array($result);
	        $bill_date =date("d-M-Y",JSstrToTime($myrow1['ENTRY_DT']));
	        /*
	        $cd_dt=explode("-",$myrow1['CD_DT']);
	        $cd_dt1='';
	        for($i=count($cd_dt),$k=0;$i>=0;$i--,$k++)
                $cd_dt1.=$cd_dt[$i]."-";
	        $cddt=substr($cd_dt1,1,(strlen($cd_dt1)-2));
        	*/
        	$smarty->assign("mode",$myrow1['MODE']);
		$type =$myrow1['TYPE'];
		$tds_amt=$myrow1["TDS"];

	        $smarty->assign("address",$myrow1['BILL_TO_ADDRESS']);
	        $smarty->assign("country",$myrow1['BILL_TO_COUNTRY']);
	        $smarty->assign("pin",$myrow1['BILL_TO_PIN']);
		$smarty->assign("comp_name",$myrow1['BILL_TO_NAME']);

        	//$smarty->assign("cdnum",$myrow1['CD_NUM']);
       		//$smarty->assign("cddt",$cddt);
        	//$smarty->assign("cdcity",$myrow1['CD_CITY']);
	}
	else
		$type =$myrow['CUR_TYPE'];
	$smarty->assign("type",$type);

	if($type!='RS'){
		$serviceType ='Export';
		$smarty->assign("service_type",$serviceType);
	}
	if(!$bill_date)
		$bill_date =$order_date;
	$smarty->assign("bill_date",$bill_date);

	// Address branch address
	$sale_by =trim($myrow['SALE_BY']);
	if($sale_by!='ONLINE')
	{
		$sql_jsadmin ="select SUB_CENTER from jsadmin.PSWRDS WHERE USERNAME='$sale_by'";
		$res_jsadmin=mysql_query_decide($sql_jsadmin) or die("$sql_jsadmin<br>".mysql_error_js());
		$row_jsadmin=mysql_fetch_assoc($res_jsadmin);
		$center =strtoupper($row_jsadmin['SUB_CENTER']);

		$sql_add="SELECT c.ADDRESS,c.PHONE,c.MOBILE FROM billing.BRANCHES b LEFT JOIN newjs.CONTACT_US c ON b.VALUE=c.VALUE WHERE b.NAME LIKE '%$center%'";
		$res_add=mysql_query_decide($sql_add) or die("$sql_add<br>".mysql_error_js());
		$row_add=mysql_fetch_assoc($res_add);
		$address_br=$row_add['ADDRESS'];
		$phone_br=$row_add['PHONE'];
		$mobile_br=$row_add['MOBILE'];
	}
	if($sale_by=='ONLINE' || $address_br=='')	
	{
		$address_br='Head office : B - 8, Sector - 132, Noida - 201301';
		$phone_br='120-3082000';
	}
	// Address breakup      
	$cnt =0;
	$j =0;

	$address_words_cnt =@explode(",",$address_br);
	$one_row_cnt =round(count($address_words_cnt)/4)+1;
	foreach($address_words_cnt as $key=>$val){
		if($cnt==$one_row_cnt){
			$cnt =0;
			$j++;
		}
		$addressArr[$j] .=$val." ";
		$cnt++;
	}
	$address_br_1 =$addressArr[0];
	$address_br_2 =$addressArr[1];
	$address_br_3 =$addressArr[2];
	$address_br_4 =$addressArr[3];
	$smarty->assign("address_br_1",$address_br_1);
	$smarty->assign("address_br_2",$address_br_2);
	$smarty->assign("address_br_3",$address_br_3);
	$smarty->assign("address_br_4",$address_br_4);
	$smarty->assign("phone_br",$phone_br);
	$smarty->assign("mobile_br",$mobile_br);

	// Cost amount
	$feevalue=$myrow1['AMOUNT']+$tds_amt;
	if(!$feevalue)
		$feevalue =$myrow["TOTAL_AMT"];	// can be changed to $myrow["DUEAMOUNT"]	

	// Cost value paid
        list($costvalue_rs,$costvalue_paise)=explode(".",$feevalue);
        if($costvalue_paise=='')
                $costvalue_paise= '00';
        elseif(strlen($costvalue_paise) == 1)
                $costvalue_paise .= '0';
        $smarty->assign("costvalue",$feevalue);
        $smarty->assign("costvalue_rs",$costvalue_rs);
        $smarty->assign("costvalue_paise",$costvalue_paise);


        if($type=="RS"){

	        // Cost value without tax
        if($saleType=='P'){
            $temp_tax = $myrow['SERVICE_TAX'];
        } else {
           $temp_tax = $myrow1['SERVICE_TAX']; 
        }
		if($temp_tax>0){
        	$feevalue_exTax=round(($feevalue*100/($tax_rate+100)),2);
        	list($feevalue_exTax_rs,$feevalue_exTax_paise)=explode(".",$feevalue_exTax);
        	if($feevalue_exTax_paise=='')
        	        $feevalue_exTax_paise= '00';
        	elseif(strlen($feevalue_exTax_paise) == 1)
        	        $feevalue_exTax_paise .= '0';
        	$smarty->assign("costvalue_exTax",$feevalue_exTax);
        	$smarty->assign("costvalue_exTax_rs",$feevalue_exTax_rs);
        	$smarty->assign("costvalue_exTax_paise",$feevalue_exTax_paise);

                $tax_ratevalue1 =round((($tax_rate/100)*$feevalue_exTax),2);
                list($tax_ratevalue_rs1,$tax_ratevalue_paise1)=explode(".",$tax_ratevalue1);
                if($tax_ratevalue_paise1 == '')
                        $tax_ratevalue_paise1= '00';
                elseif(strlen($tax_ratevalue_paise1)== 1)
                        $tax_ratevalue_paise1 .= '0';
                $smarty->assign("TAX_RATEVALUE_rs1",$tax_ratevalue_rs1);
                $smarty->assign("TAX_RATEVALUE_paise1",$tax_ratevalue_paise1);
		}
        }


        // SALE amount 
        //$cpp=$myrow["CPP"]; 
        if($saleType=='P'){
            $sale_amt=$myrow["SALE_AMT"];
            $tax_amt=$myrow["SERVICE_TAX"];
            $total_amt=$myrow["TOTAL_AMT"];       
        } else {
            $sale_amt=$myrow1["AMOUNT"]+$myrow1["TDS"];
            $tax_amt=$myrow1["SERVICE_TAX"];
            $total_amt=$myrow1["AMOUNT"]+$myrow1["TDS"];       
        }


        list($sale_amt_rs,$sale_amt_paise)=explode(".",$sale_amt);
        if($sale_amt_paise=='')
                $sale_amt_paise= '00';
        elseif(strlen($sale_amt_paise) == 1)
                $sale_amt_paise .= '0';

        list($tax_amt_rs,$tax_amt_paise)=explode(".",$tax_amt);
        if($tax_amt_paise=='')
                $tax_amt_paise= '00';
        elseif(strlen($tax_amt_paise) == 1)
                $tax_amt_paise .= '0';

        list($tds_rs,$tds_paise)=explode(".",$tds);
        if($tds_paise=='')
                $tds_paise= '00';
        elseif(strlen($tds_paise) == 1)
                $tds_paise .= '0';

        list($total_amt_rs,$total_amt_paise)=explode(".",$total_amt);
        if($total_amt_paise=='')
                $total_amt_paise= '00';
        elseif(strlen($total_amt_paise) == 1)
                $total_amt_paise .= '0';
        $smarty->assign("sale_amt",$sale_amt);
        $smarty->assign("sale_amt_rs",$sale_amt_rs);
        $smarty->assign("sale_amt_paise",$sale_amt_paise);
        $smarty->assign("tax_amt",$tax_amt);
        $smarty->assign("tax_amt_rs",$tax_amt_rs);
        $smarty->assign("tax_amt_paise",$tax_amt_paise);
        $smarty->assign("tds",$tds);
        $smarty->assign("tds_rs",$tds_rs);
        $smarty->assign("tds_paise",$tds_paise);
        $smarty->assign("total_amt",$total_amt);
        $smarty->assign("total_amt_rs",$total_amt_rs);
        $smarty->assign("total_amt_paise",$total_amt_paise);

	// Cost value paid
        $cost_rs=convert($costvalue_rs);
        if($costvalue_paise != '' && $costvalue_paise != '00' && $costvalue_paise != '0')
        {
                if($type=='RS')
                        $cost_paise=convert ($costvalue_paise)."paise";
                elseif($type=="DOL")
                        $cost_paise=convert ($costvalue_paise)."cents";
                $cost=$cost_rs."and ".$cost_paise." only";
        }
        else
                $cost=convert($costvalue_rs)." only";
        $smarty->assign("cost",$cost);
	
    if($saleType=='P'){
        if(($myrow["SERVICE_TAX"]>0 && $type=='RS') || $type=='DOL')
        $smarty->assign("service_tax_content",$service_tax_content);
        $smarty->assign("sale_type_flag", $saleType);
    }else{
        if(($myrow1["SERVICE_TAX"]>0 && $type=='RS') || $type=='DOL')
        $smarty->assign("service_tax_content",$service_tax_content);
    }
    
	// Sale amount
        $total_amt_rs1=convert($total_amt_rs);
        if($total_amt_paise != '' && $total_amt_paise != '00' && $total_amt_paise != '0')
        {                 if($type=="RS")
                        $total_amt_paise1=convert ($total_amt_paise)."paise";
                elseif($type=="DOL")
                        $total_amt_paise1=convert ($total_amt_paise)."cents";
                $total_amt_words=$total_amt_rs1."and ".$total_amt_paise1." only";
        }
        else
                $total_amt_words=convert($total_amtvalue_rs)." only";
        $smarty->assign("total_amt_words",$total_amt_words);


        $output = $smarty->fetch("misc_salesInvoice_bill.htm");
        if($output == "ERROR")
        {
                echo "ERROR";
                exit;
        }
        return $output;
}

function check_cheque_date($cd_day,$cd_month,$cd_year)
{
	$entered_timestamp = mktime(0,0,0,$cd_month,$cd_day,$cd_year);
	$arr1 = explode("-",date('Y-m-d'));
	list($y,$m,$d) = $arr1;
	$current_timestamp = mktime(0,0,0,$m,$d,$y);
	$f=4;
	$r = $m-$f;
	if($r<=0)
	{
		$r=$r+12;
		$y--;
	}
	$checking_timestamp = mktime(0,0,0,$r,$d,$y);
	if(($current_timestamp-$entered_timestamp)>($current_timestamp-$checking_timestamp))
	{
		return 1;
	}
	elseif($entered_timestamp > $current_timestamp)
	{
		return 1;
	}
	else
		return 0;
}

function calc_user_score($age,$gender,$profilelength,$photo,$postedby,$reg_dt,$login_cnt,$contact_cnt)
{
        global $static_score;

	$user_score = 0;

        if ($gender == 'F')
        {
                if ($age >= 28)
                {
                        $user_score += 300;
                }
                elseif ($age >= 23 && $age <= 27)
                {
                        $user_score += 200;
                }
                elseif ($age <= 22)
                {
                        $user_score += 100;
                }
        }
        else
        {
                if ($age >= 31)
                {
                        $user_score += 200;
		}
                elseif ($age >= 25 && $age <= 30)
                {
                        $user_score += 150;
                }
                elseif ($age < 25)
                {
                        $user_score += 25;
                }
        }

        if ($profilelength >= 1000)
        {
                $user_score += 75;
        }
        elseif ($profilelength >= 600 && $profilelength < 1000)
        {
                $user_score += 50;
        }
        elseif ($profilelength < 600)
        {
                $user_score += 0;
        }                                        

        if ($photo == 'Y')
                $user_score += 200;
        else//if ($photo ==  'N')
                $user_score += 100;

        if ($postedby == '2' || $postedby == '3')
        {
                $user_score+= 250;
        }
        elseif ($postedby == '1')
        {
                $user_score+= 150;
        }
        else
                $user_score+= 25;

	$static_score = round(($user_score*600)/825);

	$today_ts = mktime(23,59,59,date("m"),date("d"),date("Y"));
	//$today_ts = mktime(23,59,59,date("m"),date("d")-1,date("Y"));

	list($yy,$mm,$dd)=explode("-",substr($reg_dt,0,10));
        $regn_ts = mktime(0,0,0,$mm,$dd,$yy);

	$days_diff = intval(($today_ts - $regn_ts)/(24*60*60));

	if ($days_diff > 30)
		$days_in_sys = 30;
	else
		$days_in_sys = $days_diff;

        if ($days_in_sys && $login_cnt)
	{
		$login_freq = $login_cnt/$days_in_sys;
		if ($login_freq >= 0.4)
		{
			$user_score+=250;
		}
		elseif ($login_freq >= 0.2 && $login_freq < 0.4)
		{
			$user_score+=150;
		}
		else
			$user_score+=0;
        }

	if ($days_in_sys && $contact_cnt)
	{
		$contact_freq = $contact_cnt/$days_in_sys;
        	if ($contact_freq >= 0.8)
        	{
                	$user_score+= 175;
        	}
        	elseif ($contact_freq >=0.2 && $contact_freq < 0.8)
        	{
                	$user_score+= 125;
        	}
        	else
                	$user_score+= 0;
	}

	$user_score = round(($user_score*600)/1250);

        return $user_score;
}

function calc_user_score_search($age,$gender,$profilelength,$photo,$postedby,$reg_dt,$login_cnt,$contact_cnt)
{
        $user_score = 0;
                                                                                                                             
        if ($gender == 'F')
        {
                if ($age >= 26)
                {
                        $user_score += 300;
                }
                elseif ($age >= 23 && $age <= 25)
                {
                        $user_score += 200;
                }
                elseif ($age <= 22)
                {
                        $user_score += 100;
                }
        }
        else
        {
                if ($age >= 28)
                {
                        $user_score += 200;
                }
                elseif ($age >= 25 && $age <= 27)
                {
                        $user_score += 150;
                }
                elseif ($age < 25)
                {
                        $user_score += 25;
                }
        }
        
	if ($profilelength >= 1000)
        {
                $user_score += 75;
        }
        elseif ($profilelength >= 600 && $profilelength < 1000)
        {
                $user_score += 50;
        }
        elseif ($profilelength < 600)
        {
                $user_score += 0;
        }                                        

        if ($photo == 'Y')
                $user_score += 200;
        else//if ($photo ==  'N')
                $user_score += 100;

        if ($postedby == '2' || $postedby == '3')
        {
                $user_score+= 250;
        }
        elseif ($postedby == '1')
        {
                $user_score+= 150;
        }
        else
                $user_score+= 25;

	$today_ts = mktime(23,59,59,date("m"),date("d"),date("Y"));
	//$today_ts = mktime(23,59,59,date("m"),date("d")-1,date("Y"));

	list($yy,$mm,$dd)=explode("-",substr($reg_dt,0,10));
        $regn_ts = mktime(0,0,0,$mm,$dd,$yy);

	$days_diff = intval(($today_ts - $regn_ts)/(24*60*60));

	if ($days_diff > 30)
		$days_in_sys = 30;
	else
		$days_in_sys = $days_diff;

        if ($days_in_sys && $login_cnt)
	{
		$login_freq = $login_cnt/$days_in_sys;
		if ($login_freq >= 0.4)
		{
			$user_score+=250;
		}
		elseif ($login_freq >= 0.2 && $login_freq < 0.4)
		{
			$user_score+=150;
		}
		else
			$user_score+=0;
        }

	if ($days_in_sys && $contact_cnt)
	{
		$contact_freq = $contact_cnt/$days_in_sys;
        	if ($contact_freq >= 0.8)
        	{
                	$user_score+= 175;
        	}
        	elseif ($contact_freq >=0.2 && $contact_freq < 0.8)
        	{
                	$user_score+= 125;
        	}
        	else
                	$user_score+= 0;
	}

	$user_score = round(($user_score*600)/1250);

        return $user_score;
}

function get_round_score($score)
{
        if($score<=60)
		$val=50;
        elseif($score<=108)
                $val=100;
        elseif($score<=156)
                $val="150";
        elseif($score<=216)
                $val="200";
        elseif($score<=264)
                $val="250";
        elseif($score<=312)
                $val="300";
        elseif($score<=360)
                $val="350";
        elseif($score<=420)
                $val="400";
        elseif($score<=456)
		$val="450";
        elseif($score<=516)
                $val="500";
        elseif($score<=564)
                $val="550";
        else
                $val="600";

        return $val;
}
?>
