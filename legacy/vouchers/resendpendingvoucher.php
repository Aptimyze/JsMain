<?php
include("connect.inc");
//include ("../crm/func_sky.php");
$db=connect_db();
$sqloptin="SELECT OPTIONS_AVAILABLE,PROFILEID FROM billing.VOUCHER_OPTIN WHERE OPTIN_DATE>='2007-04-20'";
//$sqloptin="SELECT OPTIONS_AVAILABLE,PROFILEID FROM billing.VOUCHER_OPTIN WHERE PROFILEID='136580' OR PROFILEID='1731612' OR PROFILEID='1954505'";
$resultoptin=mysql_query_decide($sqloptin) or die("$sqloptin".mysql_error_js());
while($rowoptin=mysql_fetch_assoc($resultoptin))
{
	$stringoptions=$rowoptin["OPTIONS_AVAILABLE"];
	$profileid=$rowoptin["PROFILEID"];
	$sqldetail="SELECT USERNAME,EMAIL FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$resultdetail=mysql_query_decide($sqldetail) or die("$sqldetail".mysql_error_js());
	$rowdetail=mysql_fetch_assoc($resultdetail);
	$email=$rowdetail["EMAIL"];
	$Name=$rowdetail["USERNAME"];
	$options=explode(",",$stringoptions);
	foreach($options as $value)
	{
	        $sqlnumber="SELECT CLIENTID FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$value' AND PROFILEID='$profileid'";
		//$sqlnumber="SELECT CLIENTID FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$value' AND SOURCE='' AND PROFILEID='$profileid'";
		$resultnumber=mysql_query_decide($sqlnumber) or die("$sqlnumber".mysql_error);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gv,"ShowErrTemplate");
		if(mysql_num_rows($resultnumber)==0)
		{
			$sql_voucher="SELECT VOUCHER_NO,TYPE FROM billing.VOUCHER_NUMBER WHERE ID=(SELECT MIN(ID) min FROM billing.VOUCHER_NUMBER WHERE ISSUED='' AND CLIENTID='$value')";
	                $res_voucher=mysql_query_decide($sql_voucher) or die("$sql_voucher".mysql_error);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_voucher,"ShowErrTemplate");
			 $row_voucher=mysql_fetch_assoc($res_voucher);
                         $voucher_no=$row_voucher['VOUCHER_NO'];
			 
                         if($voucher_no)//If Voucher No. exists for the particular Client
                         {
                                if($row_voucher['TYPE']=='E')//For the case of E-Vouchers
                                {
					$sql_gv="SELECT TEMPLATE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$value' AND SERVICE='Y'";
	                        	$res_gv=mysql_query_decide($sql_gv) or die("$sql_gv".mysql_error);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gv,"ShowErrTemplate");
        	                	$row_gv=mysql_fetch_array($res_gv);
                         		if($row_gv['TEMPLATE'])
                         		{
                                		 $smarty->assign('voucher_no',"$voucher_no");
	                                         $smarty->assign('Name',"$Name");
						if($SITE_URL=="http://prodjs.infoedge.com" || $SITE_URL=="http://devjs.infoedge.com")
	                                		$msg=$smarty->fetch($row_gv['TEMPLATE']);
                                		else
                                        		$msg=$smarty->fetch($row_gv['TEMPLATE']);
						$today=date("Y-m-d");
						//send_mail($email,'','',$msg,'Gift Vouchers','Promotions@jeevansathi.com');
						send_email($email,$msg,'Gift Vouchers','Promotions@jeevansathi.com');
			//echo "mail sent to ".$profileid." at ".$email." for voucher client ".$value." and voucher number is ".$voucher_no;
						mysql_ping_js();
                                		$sql_update="UPDATE billing.VOUCHER_NUMBER SET ISSUED='Y',PROFILEID='$profileid',ISSUE_DATE='$today' WHERE CLIENTID='$value' AND VOUCHER_NO='$voucher_no'";
                                 		mysql_query_decide($sql_update) or die("$sql_update".mysql_error);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
                           		}
			   	  }
			}
		}
		
	}
		
}

?>
