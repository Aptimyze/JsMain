<?php
//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.com","user_dialer","DIALlerr") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js);

//Connection at DialerDB
$db_dialer = mssql_connect("dailer.jeevansathi.com","easy","G0dblessyou") or die("Unable to connect to dialer server");

stop_inactive_profiles('MAH_JSNEW',$db_js,$db_dialer);
stop_inactive_profiles('JS_NCRNEW',$db_js,$db_dialer);

function loginWithin15Days($x,$db_js)
{
        $sql = "SELECT LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PROFILEID=$x";
        $res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
        if($row = mysql_fetch_array($res))
	{
                if($row["LAST_LOGIN_DT"]>=@date('Y-m-d',time()-15*86400))
			return 1;
		else
			return 0;
	}
        else
		return 0;
}

function stop_inactive_profiles($campaign_name,$db_js,$db_dialer)
{
	$squery1 = "SELECT easycode,PROFILEID FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND Dial_Status=1";
        $sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
        while($srow1 = mssql_fetch_array($sresult1))
        {
		$ecode = $srow1["easycode"];
		$proid = $srow1["PROFILEID"];
		if(!loginWithin15Days($proid,$db_js))
		{
			echo $query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status=0 WHERE easycode='$ecode'";
			mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer);
			echo "\n";
		}
	}
}

function logerror($sql="",$db="",$ms)
{
	$today=@date("Y-m-d h:m:s");
	$filename="logerror.txt";
	if(is_writable($filename))
	{
		if (!$handle = fopen($filename, 'a'))
		{
			echo "Cannot open file ($filename)";
			exit;
		}
		if($ms)
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mssql_get_last_message(). " \t $today");
		else
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mysql_error(). " \t $today");
		fclose($handle);
	}
	else
	{
		echo "The file $filename is not writable";
	}
}
?>
