<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/************************************************************************************************************************
FILE NAME : pending_mailer.php
CREATED BY : SRIRAM VISWANATHAN
DATE : 04 SEPTEMBER 2006
DESCRIPTION : FINDS THE COUNT OF PENDING PAYMENTS -- MODE-WISE, ENTRYBY-WISE AND ENTRY_DT-WISE, FOR THE PREVIOUS MONTH.
INCLUDED : connect.inc, func_sky.php
FUNCTION USED : send_mail() -- to send email.
************************************************************************************************************************/
include "$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc";
include("$_SERVER[DOCUMENT_ROOT]/crm/func_sky.php");
//include('connect.inc');
$date = date('m-Y');
$arr = explode("-",$date);
list($mnth,$yr)=$arr;

if($mnth==1)
{
	$prev_month = 12;
	$yr--;
}
else
	$prev_month = $mnth-1;

if($prev_month<=9)
	$prev_month = "0".$prev_month;

$start_date = $yr."-".$prev_month."-"."01 00:00:00";
$end_date = $yr."-".$prev_month."-"."31 23:59:59";

$sql_mode = "SELECT COUNT(*) AS COUNT, MODE FROM billing.PAYMENT_DETAIL WHERE STATUS = 'DONE' AND ENTRYBY <> 'ONLINE' AND COLLECTED = 'P' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY MODE";
$res_mode = mysql_query($sql_mode) or die("$sql_mode".mysql_error());

$i=0;
while($row_mode = mysql_fetch_array($res_mode))
{
	$modewise[$i]['COUNT'] = $row_mode['COUNT'];
	$modewise[$i]['MODE'] = $row_mode['MODE'];
	$i++;
}

$sql_entryby = "SELECT COUNT(*) AS COUNT, ENTRYBY FROM billing.PAYMENT_DETAIL WHERE STATUS = 'DONE' AND ENTRYBY <> 'ONLINE' AND COLLECTED = 'P' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY ENTRYBY";
$res_entryby = mysql_query($sql_entryby) or die("$sql_entryby".mysql_error());
                                                                                                                             
$j=0;
while($row_entryby = mysql_fetch_array($res_entryby))
{
        $entryby_wise[$j]['COUNT'] = $row_entryby['COUNT'];
        $entryby_wise[$j]['ENTRYBY'] = $row_entryby['ENTRYBY'];
        $j++;
}


$sql_entrydt = "SELECT COUNT(*) AS COUNT,DATE_FORMAT(ENTRY_DT,'%d-%m-%Y') as ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE STATUS = 'DONE' AND ENTRYBY <> 'ONLINE' AND COLLECTED = 'P' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY ENTRY_DT";
$res_entrydt = mysql_query($sql_entrydt) or die("$sql_entrydt".mysql_error());
                                                                                                                             
$k=0;
while($row_entrydt = mysql_fetch_array($res_entrydt))
{
        $entrydt_wise[$k]['COUNT'] = $row_entrydt['COUNT'];
        $entrydt_wise[$k]['ENTRY_DT'] = $row_entrydt['ENTRY_DT'];
        $k++;
}

$smarty->assign("modewise",$modewise);
$smarty->assign("entryby_wise",$entryby_wise);
$smarty->assign("entrydt_wise",$entrydt_wise);
//$smarty->display('pending_mailer.htm');

if($prev_month == "01")
	$prev_month_text = "January";
elseif($prev_month == "02")
	$prev_month_text = "February";
elseif($prev_month == "03")
	$prev_month_text = "March";
elseif($prev_month == "04")
	$prev_month_text = "April";
elseif($prev_month == "05")
	$prev_month_text = "May";
elseif($prev_month == "06")
	$prev_month_text = "June";
elseif($prev_month == "07")
	$prev_month_text = "July";
elseif($prev_month == "08")
	$prev_month_text = "August";
elseif($prev_month == "09")
	$prev_month_text = "September";
elseif($prev_month == "10")
	$prev_month_text = "October";
elseif($prev_month == "11")
	$prev_month_text = "November";
elseif($prev_month == "12")
	$prev_month_text = "December";

$message = "The Details of collections still marked as pending for the month of ".$prev_month_text." ".$yr.".";
$message .= $smarty->fetch('pending_mailer.htm');

$subject = "Details of collections still marked as pending for ".$prev_month_text." ".$yr;

//send_mail("aman.sharma@jeevansathi.com","","",$message,$subject,"info@jeevansathi.com");

send_mail("aman.sharma@jeevansathi.com","alok@jeevansathi.com","sriram.viswanathan@jeevansathi.com",$message,$subject,"payments@jeevansathi.com");

?>
