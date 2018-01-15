<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once ("../connect.inc");
$db = connect_slave();

// query to find all the cities which are covered under NOIDA branch
$sql = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='UP25'";
$result= mysql_query($sql) or logError($sql);//die(mysql_error());

$i = 0;

while($myrow = mysql_fetch_array($result))
{
	$city_res[$i] = $myrow['VALUE'];
	$i++;
}
$citystring = implode("','",$city_res);

// find today's and date 1 week ago
$ts=time();
$ts -= 7 * 24 * 60 * 60;
$start_date = date("Y-m-d",$ts) ." 00:00:00";
$end_date = date("Y-m-d",$ts) ." 23:59:59";

$start_date = "2005-12-28 00:00:00";
$end_date = "2005-12-28 23:59:59";

$header="\"PROFILEID\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"".","."\"CITY\"".","."\"PHOTO\"".","."\"CONTACTS INITIATED\"".","."\"CONTACTS ACCEPTED\"".","."\"CONTACTS RECEIVED\"".","."\"ACCEPTANCE RECEIVED\"".","."\"DATE OF BIRTH\"".","."\"POSTEDBY\"".","."\"GENDER\"".","."\"CASTE\"".","."\"COMMUNITY\"".","."\"PROFILELENGTH\"".","."\"DESIRED PARTNER PROFILE\"".","."\"LAST LOGIN DATE\"\n";

//$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/csv_".date('Y-m-d').".txt";
$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/csv_2006-01-04.txt";
$fp = fopen($filename,"w+");
if(!$fp)
{
	die("no file pointer");
}

fwrite($fp,$header);

// query to find details of users who have registered one week back and whose profiles are active 
$sql = "SELECT PROFILEID , LAST_LOGIN_DT , USERNAME , PHONE_RES  , DTOFBIRTH  , PHONE_MOB , CITY_RES , COUNTRY_RES , MTONGUE , GENDER , RELATION , HAVEPHOTO , CASTE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND CITY_RES IN('$citystring') and ACTIVATED IN ('Y','H')";

$res = mysql_query($sql) or logError($sql);//die("$sql".mysql_error());
while($row = mysql_fetch_array($res))
{
	$pid  = $row['PROFILEID'];
	//$USERNAME = $row['USERNAME'];
	$PHONE_RES = $row['PHONE_RES'];
	$PHONE_MOB = $row['PHONE_MOB'];
	$PHOTO = $row['HAVEPHOTO'];

	if($PHONE_RES || $PHONE_MOB)
	{
		// query to find count of contacts made and accepted
		$sql1 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='A'";
		$res1 = mysql_query($sql1) or logError($sql1);//die("$sql1".mysql_error());
		$row1 = mysql_fetch_array($res1);
		$ACCEPTANCE_RCVD = $row1['CNT'];

		// query to find count of contacts initiated by user and accepted
		$sql2 = "SELECT COUNT(*) as CNT FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='A'";
		$res2 = mysql_query($sql2) or logError($sql2);//die("$sql2".mysql_error());
		$row2 = mysql_fetch_array($res2);
		$ACCEPTANCE_MADE = $row2['CNT'];
		
		$sql3="SELECT COUNT(*) AS CNT3 FROM newjs.CONTACTS WHERE RECEIVER = '$pid' AND TYPE='I'";
		$res3 = mysql_query($sql3) or logError($sql3);//die("$sql3".mysql_error());
		$row3 = mysql_fetch_array($res3);
		$RECEIVE_CNT = $row3['CNT3'];

		$sql4 ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TYPE='I'";
		$res4 = mysql_query($sql4) or logError($sql4);//die("$sql4".mysql_error()); 
		$row4 = mysql_fetch_array($res2);
		$INITIATE_CNT= $row4['CNT4'];

		$DOB = $row['DTOFBIRTH'];

		$posted = $row['RELATION'];
		switch($posted)
		{
			case '1': $POSTEDBY='Self';
				  break;
			case '2': $POSTEDBY='Parent/Guardian';
				  break;
			case '3': $POSTEDBY='Sibling';
				  break;
			case '4': $POSTEDBY='Friend';
				  break;
			case '5': $POSTEDBY='Marriage Bureau';
				  break;
			case '6': $POSTEDBY='Other';
				  break;
		}

		if($row['GENDER']=='F')
			$GENDER='Female';
		else
			$GENDER='Male';

		$caste = $row['CASTE'];
		$CASTE= label_select('CASTE',$caste);

		$mtongue = $row['MTONGUE'];
		$MTONGUE= label_select('MTONGUE',$mtongue);
		$country =  $row['COUNTRY_RES'];
		$COUNTRY= label_select('COUNTRY',$country);
		$city =  $row['CITY_RES'];
		if($country=='51')
			$CITY_RES=label_select('CITY_INDIA',$city);
		elseif($country=='128')
			$CITY_RES=label_select('CITY_USA',$city);
		else
			$CITY_RES='NA';

		$sql5 = "SELECT PARTNERID FROM newjs.JPARTNER WHERE PROFILEID = '$pid'";
		$res5 = mysql_query($sql5) or logError($sql5);
		$row5 = mysql_fetch_array($res5);

		$partner_id = $row5["PARTNERID"];
		if ($partner_id)
		{
			$partner_tbl_arr = array('PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COMP','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_FBACK','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RES_STATUS','PARTNER_SMOKE');
			for($i=0;$i<count($partner_tbl_arr);$i++)
			{
				$sql6 = "SELECT COUNT(*) AS CNT4 FROM newjs.$partner_tbl_arr[$i] WHERE PARTNERID = '$partner_id'";
				$res6 = mysql_query($sql6) or logError($sql6);//die("$sql6".mysql_error());
				if ($row6 = mysql_fetch_array($res6))
				{
					if ($row6["CNT4"] > 0)
					{
						$DPP =  1;
						break;
					}
					else
						$DPP =  0;
				}
			}
		}
		else
			$HAVEPARTNER = 'N';

		if ($DPP ==  1) // member as filled in his/her desired partner profile
			$HAVEPARTNER = 'Y';
		else
			$HAVEPARTNER = 'N';

		$PROFILELENGTH = strlen($row['YOURINFO']) + strlen($row['FAMILYINFO']) + strlen($row['SPOUSE']) + strlen($row['FATHER_INFO']) + strlen($row['SIBLING_INFO']) + strlen($row['JOB_INFO']);

		$LAST_LOGIN_DT = $row['LAST_LOGIN_DT'];

	// cretaing content to be written to the file
		if ($PHONE_MOB && $PHONE_RES)
		{
			$line="\"$pid\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"";
		}
		elseif ($PHONE_MOB && $PHONE_RES =='')
		{
			$line="\"$pid\"".","."\"$PHONE_MOB\"".","."\"\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"";
		}
		elseif ($PHONE_MOB =='' && $PHONE_RES)
		{
			$line="\"$pid\"".","."\"$PHONE_RES\"".","."\"\"".","."\"$CITY_RES\"".","."\"$PHOTO\"".","."\"$INITIATE_CNT\"".","."\"$ACCEPTANCE_MADE\"".","."\"$RECEIVE_CNT\"".","."\"$ACCEPTANCE_RCVD\"".","."\"$DOB\"".","."\"$POSTEDBY\"".","."\"$GENDER\"".","."\"$CASTE\"".","."\"$MTONGUE\"".","."\"$PROFILELENGTH\"".","."\"$HAVEPARTNER\"".","."\"$LAST_LOGIN_DT\"";
		}

		$data = trim($line)."\n";
		$output = $data;
		unset($data);
		unset($DPP);
		// writing content to file
		fwrite($fp,$output);
	}
}
fclose($fp);

function label_select($table,$value)
{
        $sql = "SELECT LABEL FROM newjs.$table WHERE VALUE='$value'";
        $res = mysql_query($sql) or logError($sql);//die("Error in Label select".$sql.mysql_error());
        $row = mysql_fetch_array($res);
        $label = $row['LABEL'];
        return $label;
}
?>
