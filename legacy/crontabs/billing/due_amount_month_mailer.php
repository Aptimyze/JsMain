<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include "$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc";
include "$_SERVER[DOCUMENT_ROOT]/crm/func_sky.php";
$from="payments@jeevansathi.com";
$email="bodhsatv@naukri.com,shyam.kumar@jeevansathi.com,swapnil@naukri.com,alok@jeevansathi.com,aman.sharma@jeevansathi.com";
$msg='';
$last_walkin='';
unset($inmail);
$cur_date=date('d-m-Y');
$date=date('Y-m-d');
		$sql=" SELECT MAX(BILLID),USERNAME,DUEAMOUNT,DATE_FORMAT(DUEDATE,'%d-%m-%Y') as DUEDATE,WALKIN from billing.PURCHASES WHERE ENTRY_DT<=DATE_SUB('$date',INTERVAL 30 DAY) and `DUEAMOUNT` >0 and STATUS='DONE' GROUP BY PROFILEID  ORDER BY WALKIN";
		$result=mysql_query($sql) or $msg .= "\n$sql \nError :".mysql_error();
		if(mysql_num_rows($result)>0)
		{
			$inmail="<table border='1'>";
			while($myrow=mysql_fetch_array($result))
			{
				if($last_walkin!=$myrow['WALKIN'])
				{
					$inmail.= "<tr bgcolor='green'><td>USERNAME</td><td>DUEAMOUNT</td><td>DUEDATE</td><td>WALKIN</td></tr>";
					$last_walkin=$myrow['WALKIN'];
					$inmail.="<tr><td>".$myrow['USERNAME']."</td><td>".$myrow['DUEAMOUNT']."</td><td>".$myrow['DUEDATE']."</td><td>".$myrow['WALKIN']."</td></tr>";
				}
				else
				{
					$inmail.="<tr><td>".$myrow['USERNAME']."</td><td>".$myrow['DUEAMOUNT']."</td><td>".$myrow['DUEDATE']."</td><td>".$myrow['WALKIN']."</td></tr>";
				}
			}
			if(($inmail))
			{
				$inmail.="</table>";
				$sub="Due Payments upto last month ";
				$msg = $inmail;
				send_email_plain($email,$Cc,$Bcc,$msg,$sub,$from,"");
				unset($inmail);
				unset($msg);
			}

		}

?>
