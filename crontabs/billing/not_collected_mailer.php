<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include "$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc";
$from="payments@jeevansathi.com";
$cc="anubha.jain@naukri.com";

$msg='';
$last_walkin='';
unset($inmail);
$cur_date=date('d-m-Y');
$date=date('Y-m-d');
$db_slave = connect_slave();
		$sql=" SELECT a.USERNAME,b.AMOUNT,b.TYPE,a.WALKIN,b.ENTRYBY,b.MODE,DATE_FORMAT(b.ENTRY_DT,'%d-%m-%Y') as ENTRY_DT from billing.PURCHASES as a,billing.PAYMENT_DETAIL as b WHERE b.ENTRY_DT<'$date' and b.ENTRY_DT>=DATE_SUB('$date',INTERVAL 7 DAY) and b.STATUS='DONE' and b.COLLECTED='P' and b.AMOUNT>0 and a.BILLID=b.BILLID ORDER BY a.WALKIN";
		$result=mysql_query($sql,$db_slave) or $msg .= "\n$sql \nError :".mysql_error();
		if(mysql_num_rows($result)>0)
		{
			while($myrow=mysql_fetch_array($result))
			{
				if($last_walkin!=$myrow['WALKIN'])
				{
					if(count($inmail) > 0)
					{
					        $msg = "\n\nUSERNAME    "."MODE     "."AMOUNT     "."ENTRY DATE     "."ENTRY BY    \n".implode("\n",$inmail);
						$email=get_email($last_walkin,$db_slave);
						if($email=="")
						{
							$email=get_boss_email($last_walkin,$db_slave);
							$cc1=$cc;
						}
						else
						{
							$boss_email=get_boss_email($last_walkin,$db_slave);
							if($boss_email!='')
								$cc1=$boss_email.",".$cc;
							else
								$cc1=$cc;
						}
							
						$sub="Outstanding for $last_walkin on $cur_date";
						mail($email, $sub, $msg,"From: $from\r\n"."Cc: $cc1\r\n"."Bcc: $bcc\r\n"."X-Mailer: PHP/" . phpversion());
						unset($inmail);
						unset($msg);
					}
					$last_walkin=$myrow['WALKIN'];
					$inmail[]=$myrow['USERNAME']."        ".$myrow['MODE']."        ".$myrow['TYPE']." ".$myrow['AMOUNT']."       ".$myrow['ENTRY_DT']."	".$myrow['ENTRYBY'];
				}
				else
				{
					$inmail[]=$myrow['USERNAME']."        ".$myrow['MODE']."        ".$myrow['TYPE']." ".$myrow['AMOUNT']."       ".$myrow['ENTRY_DT']."	".$myrow['ENTRYBY'];
				}
			}
			if(count($inmail) > 0)
			{
			        $msg = "\n\nUSERNAME    "."MODE     "."AMOUNT     "."ENTRY DATE     "."ENTRY BY    \n".implode("\n",$inmail);
				$email=get_email($last_walkin,$db_slave);
				if($email=="")
				{
					$email=get_boss_email($last_walkin,$db_slave);
					$cc1=$cc;
				}
				else
				{
					$boss_email=get_boss_email($last_walkin,$db_slave);
					if($boss_email!='')
						$cc1=$boss_email.",".$cc;
					else
						$cc1=$cc;
                                }
				$sub="Outstanding for $last_walkin on $cur_date";
				mail($email, $sub, $msg,"From: $from\r\n"."Cc: $cc1\r\n"."Bcc: $bcc\r\n"."X-Mailer: PHP/" . phpversion());
				unset($inmail);
				unset($msg);
			}

		}

?>	
