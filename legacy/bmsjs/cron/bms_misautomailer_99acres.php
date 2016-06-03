<?php
chdir(dirname(__FILE__));
	include_once("../includes/bms_connect.php");
	include_once("bms_comfunc.php");

	//$email        = "alok@jeevansathi.com,lavesh.rawat@jeevansathi.com,lijuv@naukri.com,bhaskar.deva@99acres.com,ankit.bhatnagar@naukri.com,siddharth.chaturvedi@naukri.com,asif.ghani@naukri.com,shwetaa.kapoor@naukri.com";
	$email="lavesh.rawat@jeevansathi.com,lijuv@naukri.com,asif.ghani@naukri.com";
	$db = getConnectionBms();

	$currdate 		= date('Y-m-d');
	list($yr,$mn,$dt) 	= explode('-',$currdate);
	$prevdate 		= date('Y-m-d',mktime(0,0,0,$mn,$dt-1,$yr));
	
	$sql_reg = "SELECT RegName AS regname, REGION.RegId AS regid, ZoneName AS zonename, ZONE.ZoneId AS zoneid FROM REGION, ZONE WHERE REGION.RegId = ZONE.RegId AND RegMailer != 'Y' AND ZonePopup != 'Y' AND SITE = '99acres' ORDER BY REGION.RegId ASC ";
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

	$message_outer = "<html><body><table width=\"100%\" cellspacing=\"3\" align=\"center\" border=\"0\" bordercolor=\"black\">";

	for ($i = 0;$i < $regcount;$i++)
	{
		$regid = $regarr[$i];
		$sql3 = "SELECT RegName from REGION where RegId='$regid'";
		$res3 = mysql_query($sql3) or die("$sql3".mysql_error());
		$row3 = mysql_fetch_array($res3);
		$reg  = $row3['RegName'];

		/*$message.="<tr><td>&nbsp;</td></tr><tr><td colspan=\"100%\"><b><u>".strtoupper($reg)."</u></b></td></tr>
			   <tr>
			      <td>
			        <table border=\"1\" width=\"100%\" cellspacing=\"2\" align=\"center\">";*/
		$table_width=0;

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

				$message_inner.="<tr><td width=\"250\">".$row2['ZoneName']."</td>";
				//$prevdate = '2006-04-24';
				$table_width+=250;
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
					
					$table_width+=150;
					$message_1.="<td width=\"150\"><span><b>".$row1['CampaignName']."</b><br>Impressions:".$impressions."<br>Clicks:".$row['CLICKS']."<br></span></td>";
					$message_1.="\n";
				}
				$table_width+=200;
				$message_inner.="<td width=\"200\">Total of &nbsp;<b>".$totunits."</b>&nbsp; units</td>$message_1</tr>";
				$arr[]=$table_width;
                                $table_width=0;
				unset($message_1);
				unset($totimp);
				unset($totunits);
			}
		}
		rsort($arr);
                $max_width=$arr[0];
                unset($arr);
                $message_center="<tr><td>&nbsp;</td></tr><tr><td colspan=\"100%\"><b><u>".strtoupper($reg)."</u></b></td></tr>
                           <tr>
                              <td>
                                <table border=\"1\" width=\"$max_width\" cellspacing=\"2\" align=\"left\">";
                                                                                                                             
                $message.=$message_outer.$message_center.$message_inner;
                unset($message_center);
                unset($message_outer);
                unset($message_inner);
		$message.="</table></td></tr>";
	}
	$message.="</table>";
	$subject = "Campaign MIS for 99acres: ".$prevdate;

	$from = "lavesh.rawat@jeevansathi.com";
	send_email($email,$message,$subject,$from,$email1,$email2);
?>
