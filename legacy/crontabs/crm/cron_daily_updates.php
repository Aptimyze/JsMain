<?php
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates.php
* DESCRIPTION 	: Change the data of the online dialing daily
* MADE DATE 	: 20 May, 2011
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
echo date("Y-m-d");echo "\n";

//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.com","user_dialer","DIALlerr") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=50000',$db_js);

//Compute all the active campaigns
$sqlc= "SELECT CAMPAIGN FROM incentive.CAMPAIGN WHERE ACTIVE = 'Y'";
$resc=mysql_query($sqlc,$db_js) or die($sqlc.mysql_error($db_js));
while($myrowc = mysql_fetch_array($resc))
	$camp_array[] = $myrowc["CAMPAIGN"];

//Compute all non-eligible profiles
global $ignore_array;
global $eligible_array;
$sql = "SELECT PROFILEID,ELIGIBLE FROM incentive.IN_DIALER";
$res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
while($row = mysql_fetch_array($res))
{
	if($row['ELIGIBLE']=='N')
		$ignore_array[] = $row["PROFILEID"];
	else
		$eligible_array[] = $row["PROFILEID"];
}

if(count($camp_array)>0)
{
	//Connection at DialerDB
        $db_dialer = mssql_connect("dailer.jeevansathi.com","online","js@123") or die("Unable to connect to dialer server");

	/*Stop non-eligible profiles*/
	echo "/////////////////Part-1//////////////////"."\n";
	for($i=0;$i<count($camp_array);$i++)
	{
		$campaign_name = $camp_array[$i];
		echo "/////////////////".$campaign_name."//////////////////"."\n";

		$squery1 = "SELECT easycode,PROFILEID,Dial_Status FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0";
                $sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
                while($srow1 = mssql_fetch_array($sresult1))
                {
                        $ecode = $srow1["easycode"];
			echo $proid = $srow1["PROFILEID"];echo ",";
			if($srow1["Dial_Status"]!='0' && in_array($proid,$ignore_array) && $srow1["Dial_Status"]!='9')
                        {
				$vdDiscount =getVDdiscount($proid,$db_js);
                                if(!$vdDiscount)
                                        $updateStr ='Dial_Status=0,VD_PERCENT=0';
                                else
                                        $updateStr ='Dial_Status=0';
                                echo $query1 = "UPDATE easy.dbo.ct_$campaign_name SET $updateStr WHERE easycode='$ecode'";
				echo "\n";
				mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer);
			}
			else
                        {
                                echo $srow1["Dial_Status"];
                                echo "\n";
                        }
		}
        }

	/*Update data of eligible profiles*/
	echo "/////////////////Part-2//////////////////"."\n";
        for($i=0;$i<count($camp_array);$i++)
        {
                $campaign_name = $camp_array[$i];
                echo "//////////".$campaign_name."//////////"."\n";

		 $squery2 = "SELECT easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT,priority,VD_PERCENT,SCORE,Dial_Status FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0";
                $sresult2 = mssql_query($squery2,$db_dialer) or logerror($squery2,$db_dialer);
                while($srow1 = mssql_fetch_array($sresult2))
                {
			$dialer_data["initialPriority"]=$srow1["priority"];
			$ecode = $srow1["easycode"];
			$proid = $srow1["PROFILEID"];
			$dialer_data["profileid"] = $srow1["PROFILEID"];
			$dialer_data["allocated"] = $srow1["AGENT"];
			$dialer_data["discount"] = $srow1["VD_PERCENT"];
			$dialer_data["analytic_score"] = $srow1["SCORE"];
			$dialer_data["dial_status"] = $srow1["Dial_Status"];
			if(in_array($proid,$eligible_array))
			{
				$query1 = "";
                                @mysql_ping($db_js);
                                $jp_condition=data_comparision($dialer_data,$db_js,$campaign_name,$ecode,$db_dialer);
                                if($jp_condition!='ignore')
                                {
                                        echo $query1 = "UPDATE easy.dbo.ct_$campaign_name SET $jp_condition WHERE easycode='$ecode'";echo "\n";
                                        mssql_query($query1,$db_dialer) or logerror($query1,$db_dialer);
                                }

				$sql_chk="select AGENT from easy.dbo.ct_$campaign_name where easycode='$ecode'";
                                $sresult_chk = mssql_query($sql_chk,$db_dialer) or logerror($sql_chk,$db_dialer);
                                $row_chk= mssql_fetch_array($sresult_chk);

                                //if(!$dialer_data["allocated"])

                                if(!$row_chk["AGENT"])
                                {
                                        echo $query_ph2 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";echo "\n";
                                        mssql_query($query_ph2,$db_dialer) or logerror($query_ph2,$db_dialer);
                                }
			}
			unset($dialer_data);
		}
	}
}

function getVDdiscount($profileid,$db_js)
{
        @mysql_ping($db_js);
        $CDATE = @date("Y-m-d",time());
        $sql_vd="select DISCOUNT from billing.VARIABLE_DISCOUNT WHERE SDATE<='$CDATE' AND EDATE>='$CDATE' AND PROFILEID='$profileid'";
        $res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
        if($row_vd = mysql_fetch_array($res_vd))
                return $row_vd["DISCOUNT"];
        return;
}
function data_comparision($dialer_data,$db_js,$campaign_name,$ecode,$db_dialer)
{
	@mysql_ping($db_js);
	$profileid = $dialer_data["profileid"];
	$update_str='';

	//VD_PERCENT
	$vd_percent='';
	$CDATE = @date("Y-m-d",time());
	$sql_vd="select DISCOUNT from billing.VARIABLE_DISCOUNT WHERE SDATE<='$CDATE' AND EDATE>='$CDATE' AND PROFILEID='$profileid'";
	$res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
	if($row_vd = mysql_fetch_array($res_vd))
		$vd_percent = $row_vd["DISCOUNT"];
	if($vd_percent!=$dialer_data['discount'] && $vd_percent!='')
		$update_str="VD_PERCENT='$vd_percent'";
	else
		$update_str="VD_PERCENT='0'";

	//ANALYTIC_SCORE
        $score='';
        $sql_mdp="select ANALYTIC_SCORE from incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$profileid'";
        $res_mdp = mysql_query($sql_mdp,$db_js) or die("$sql_mdp".mysql_error($db_js));
        if($row_mdp = mysql_fetch_array($res_mdp))
                $score = $row_mdp["ANALYTIC_SCORE"];
        if($score!=$dialer_data['analytic_score'] && $score!='')
        {
                if($update_str=='')
                        $update_str.="SCORE='$score'";
                else
                        $update_str.=",SCORE='$score'";
        }

	//AGENT & Dial_Status
	$alloted_to='';
	$sql_ma="select ALLOTED_TO from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
	$res_ma = mysql_query($sql_ma,$db_js) or die("$sql_ma".mysql_error($db_js));
	if($row_ma = mysql_fetch_array($res_ma))
		$alloted_to = $row_ma["ALLOTED_TO"];
	if($alloted_to!=$dialer_data['allocated'])
	{
		if($alloted_to != '')
		{
			if($update_str=='')
				$update_str.="easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			else
				$update_str.=",easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			if($dialer_data["dial_status"]!='9')
                                $update_str.=",Dial_Status='1'";

		}
		else
		{
			echo $query_ph1 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";echo "\n";
                        mssql_query($query_ph1,$db_dialer) or logerror($query_ph1,$db_dialer);
			if($update_str=='')
                                $update_str.="easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
                        else
                                $update_str.=",easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			if($dialer_data["dial_status"]!='9')
                                $update_str.=",Dial_Status='1'";

		}
	}
	elseif($dialer_data['allocated']!='' && $dialer_data['dial_status']!='2' && $dialer_data["dial_status"]!='9')
	{
		if($update_str=='')
                	$update_str.="Dial_Status='2'";
                else
                	$update_str.=",Dial_Status='2'";
	}
	elseif($dialer_data['dial_status']!='1' && $dialer_data["dial_status"]!='9')
	{
		if($update_str=='')
                        $update_str.="Dial_Status='1'";
                else
                        $update_str.=",Dial_Status='1'";
	}

	//INITIAL PRIORITY UPDATE 
        if($alloted_to=='' && $vd_percent && $score>=1 && $score<=100)
                $priority='6';
        elseif( $alloted_to=='' && !$vd_percent)
        {
                if($score>=81 && $score<=100)
                        $priority='5';
                elseif($score>=61 && $score<=80)
                        $priority='4';
                elseif($score>=41 && $score<=60)
                        $priority='3';
                elseif($score>=21 and $score<=40)
                        $priority='2';
                elseif($score>=11 and $score<=20)
                        $priority='1';
                elseif($score>=1 and $score<=10)
                        $priority='0';
        }
        elseif($alloted_to !='')
        {
                if($score>=1 && $score <=100)
                        $priority='0';
        }
        if($priority!=$dialer_data['initialPriority'])
        {
                $update_str.=",priority='$priority'";
        }

	if($update_str!='')
                return $update_str;
        else
                return "ignore";
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
