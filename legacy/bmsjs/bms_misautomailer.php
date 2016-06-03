<?php
	//include_once("../includes/bms_connect.php");
	include_once("bms_comfunc.php");

	$email	= "lavesh.rawat@jeevansathi.com";

	$db = @mysql_connect("10.208.68.193:3306:/tmp/mysql5.sock","user","CLDLRTa9") or die("Can't connect to Database".mysql_error());
        mysql_select_db("bms2",$db);

	include_once("bms_comfunc.php");

	$currdate 		= date('Y-m-d');
	list($yr,$mn,$dt) 	= explode('-',$currdate);
	$prevdate 		= date('Y-m-d',mktime(0,0,0,$mn,$dt-1,$yr));
	
	$sql_reg = "SELECT RegName AS regname, REGION.RegId AS regid, ZoneName AS zonename, ZONE.ZoneId AS zoneid FROM REGION, ZONE WHERE REGION.RegId = ZONE.RegId AND RegMailer != 'Y' AND ZonePopup != 'Y' AND SITE = 'JS' ORDER BY REGION.RegId ASC ";
	$res = mysql_query($sql_reg) or die ("$sql_reg".mysql_error());
	while ($row = mysql_fetch_array($res))
	{
		$regid    = $row['regid'];
		$zoneid	  = $row['zoneid'];

		if (is_array($regarr))
		{
			if (!in_array($regid,$regarr))
			{
				$regarr[] = $regid;
			}
		}
		else
		{
			$regarr[] = $regid;
		}

		$i = array_search($regid,$regarr);

		if (is_array($zonearr[$i]))
		{
			if (!in_array($zoneid,$zonearr[$i]))
				$zonearr[$i][] = $zoneid;
		}
		else
			$zonearr[$i][] = $zoneid;
	}

	$regcount = count($regarr);

	$message = "<html><body><table width=\"900\" cellspacing=\"3\" align=\"center\" border=\"0\" bordercolor=\"black\">";

	for ($i = 0;$i < $regcount;$i++)
	{
		$regid = $regarr[$i];
		$sql3 = "SELECT RegName from REGION where RegId='$regid'";
		$res3 = mysql_query($sql3) or die("$sql3".mysql_error());
		$row3 = mysql_fetch_array($res3);
		$reg  = $row3['RegName'];

		$message.="<tr><td>&nbsp;</td></tr><tr><td colspan=\"100%\"><b><u>".strtoupper($reg)."</u></b></td></tr>
			   <tr>
			      <td>
			        <table border=\"1\" width=\"100%\" cellspacing=\"2\" align=\"center\">";

		$zonecount = count($zonearr[$i]);
		for ($j = 0;$j < $zonecount;$j++)
		{
			$zoneid = $zonearr[$i][$j];
			$regid = $regarr[$i];
			$totunits = 0;	
			//$sql = "SELECT a.BannerId, CampaignId, Impressions, Clicks FROM BANNER a, BANNERMIS b WHERE ZoneId = '$zonearr[$i][$j]' AND a.BannerId = b.BannerId AND a.BannerStatus = 'live' AND Date = '$prevdate'";
			if ($zoneid)
			{

				$sql2 = "SELECT ZoneName from ZONE where ZoneId='$zoneid'";
                                $res2 = mysql_query($sql2) or die("$sql2".mysql_error());
                                $row2 = mysql_fetch_array($res2);

				$message.="<tr><td width=\"10%\">".$row2['ZoneName']."</td>";
				//$prevdate = '2006-04-24';
				$sql="SELECT CampaignId, sum(Impressions) as IMPRESSIONS , sum(Clicks) as CLICKS FROM BANNER a, BANNERMIS b WHERE ZoneId = '$zoneid' AND a.BannerId = b.BannerId AND a.BannerStatus = 'live' AND  a.BannerPriority='1' AND Date = '$prevdate' GROUP BY CampaignId";
				$res = mysql_query($sql) or die("$sql".mysql_error());
				while ($row = mysql_fetch_array($res))
				{
					$sql1 = "SELECT CampaignName from CAMPAIGN where CampaignId='$row[CampaignId]'";
					$res1 = mysql_query($sql1) or die("$sql1".mysql_error());
					$row1 = mysql_fetch_array($res1);

					$impressions = round($row['IMPRESSIONS']/1000);
					$totimp+=$row['IMPRESSIONS'];
					$totunits = round($totimp/1000);

					$message.="<td width=\"20%\"><span><b>".$row1['CampaignName']."</b><br>Impressions:".$impressions."<br>Clicks:".$row['CLICKS']."<br></span></td>";
				}
				$message.="<td width=\"20%\">Total of &nbsp;<b>".$totunits."</b>&nbsp; units</td></tr>";
				unset($totimp);
				unset($totunits);
			}
		}
		$message.="</table></td></tr>";
	}
	$message.="</table>";
	$subject = "Campaign MIS for ".$prevdate;

	$from = "shobha.kumari@jeevansathi.com";
	send_email($email,$message,$subject,$from,$email1,$email2);
?>
