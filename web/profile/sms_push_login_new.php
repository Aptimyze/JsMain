<?php
ini_set('max_execution_time','0');
include "connect.inc";
connect_slave();

$profile_cnt = 0;

$sql_mob = "SELECT DISTINCT (MOB_SERIES) AS MOB_NO FROM test.MOBILE_SERIES WHERE MOB_SERIES <> ''";
$res_mob = mysql_query_decide($sql_mob) or die("$sql_mob".mysql_error_js());
while ($row_mob=mysql_fetch_array($res_mob))
{
	$mob_series[] = $row_mob['MOB_NO'];
}

$sql = "SELECT PROFILEID ,  LAST_LOGIN_DT FROM SEARCH_MALE WHERE  COUNTRY_RES='51' AND LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 7 DAY) and HAVE_PHONE_MOB='Y' UNION SELECT PROFILEID ,  LAST_LOGIN_DT FROM SEARCH_FEMALE WHERE COUNTRY_RES='51' AND LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND HAVE_PHONE_MOB='Y'";

$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
while ($row_main = mysql_fetch_array($res))
{
	$sql1 = "select count(*) as CNT from SMS_PROFILEIDS WHERE PROFILEID='" . $row_main[PROFILEID] . "' and DATE in ('2006-11-22','2006-11-23','2006-11-24')";
    $res1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());

    $countrow=mysql_fetch_row($res1);
    if($countrow[0] > 0)
		continue;

	$sql_phone = "SELECT USERNAME , PHONE_MOB FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$row_main[PROFILEID]'";
	$res_phone = mysql_query_decide($sql_phone) or die("$sql_phone".mysql_error_js());
	$row = mysql_fetch_array($res_phone);

	if ($row['PHONE_MOB'])
	{
		$sql1 = "SELECT COUNT(*) AS CNT FROM JPROFILE WHERE PHONE_MOB='$row[PHONE_MOB]' AND ACTIVATED='Y'";

		$res1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
		$row1 = mysql_fetch_array($res1);
		if ($row1['CNT'] > 1)
		{
			continue;
		}	
		$contact_cnt = 0;

		if (strlen($row['PHONE_MOB']) == 10)
		{
			$checkphone = checkmphone($row['PHONE_MOB']);

			if (!$checkphone)
			{
				$initialdigit = substr($row['PHONE_MOB'],0,2);
				if ($initialdigit != '91')
					$mobile = "91".substr($row['PHONE_MOB'],0,4);
				else
					$mobile = substr($row['PHONE_MOB'],0,6);
				//Sharding on CONTACTS done by Neha Verma
				$contactResult=getResultSet("count(*) as cnt","","",$row_main["PROFILEID"],"","'I'","","TIME > '$row_main[LAST_LOGIN_DT]'");
			        $contact_cnt=$contactResult[0]["cnt"];
			        unset($contactResult);
				
				$contactResult=getResultSet("count(*) as cnt",$row_main["PROFILEID"],"","","","'A'","","TIME > '$row_main[LAST_LOGIN_DT]'");
                                if(is_array($contactResult))
                                {
                                        foreach($contactResult as $key=>$value)
                                        {
                                                $accept_cnt=$contactResult[$key]["cnt"];
                                        }
                                }
                                unset($contactResult);
				//End


				if (($contact_cnt > 0 || $accept_cnt > 0) && in_array($mobile,$mob_series))
				{
					//$message = "Dear user ".$row_main['USERNAME']." , ".$contact_cnt." Profiles have contacted you and are waiting for your acceptance, Log on to Jeevansathi.com to find your dream match.";

					$message = "Dear User, $contact_cnt Profiles have contacted you, $accept_cnt profiles have accepted you since your last login, Logon to www.Jeevansathi.com to know more.";

					$mobile_no = "91".$row['PHONE_MOB'];
					//$mobile_no = "919811637297";
					$fd=fopen("http://203.122.58.209/servlet/com.aclwireless.comm.listeners.TestServlet?userId=idg1sat&pass=pag1sat&msgtype=3&selfid=true&contenttype=1&dlrreq=false&intpush=false&to=".urlencode($mobile_no)."&from=62&text=".urlencode($message),"r");
					if($fd)
						fclose($fd);
					@mysql_ping_js();
					
					//break;
					$sql_ins = "INSERT INTO SMS_PROFILEIDS VALUES('$row_main[PROFILEID]','$mobile_no',NOW())";
					mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
				}
			}
		}
	}
}
?>
