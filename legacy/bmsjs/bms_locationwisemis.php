<?php
include("includes/bms_connect.php");
include("includes/bms_mis.php");

$ipaddr=FetchClientIP();
$data=authenticatedBms($id,$ipaddr,"banadmin");
$smarty->assign('zone',$zone);
                                                                                                                            
if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

	$days           = getDaysBms();
	$months         = getMonthsBms();
	$years          = getYearsBms();
	$curdate        = date("Y-m-d");
	assignRegionZoneDropDowns("","showcriteria");
	$smarty->assign("days",$days);
	$smarty->assign("months",$months);
	$smarty->assign("years",$years);
	$smarty->assign("startyear",date("Y"));
	$smarty->assign("startmonth",date("m"));

	$is_error =0;
	if($submit1)
	{
		if ($zone == 'select' || $region== 'select') 
			$is_error = 1;
		if ($is_error == 1)
		{	
			$smarty->display("./$_TPLPATH/bms_locationwise.htm");
		}
		else
		{
			$regionval = explode('|',$region);
			$zoneval = explode('|',$zone);

			$sql="Select  r.RegName,z.ZoneName from bms2.ZONE z,bms2.REGION r where ZoneId='$zoneval[0]' and r.RegId=z.RegId";
			$result=mysql_query($sql) or logErrorBms("bms_locationwisemis.php:locationMIS:1:Could not get Zone listings. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"exit","NO");
        		if ($myrow=mysql_fetch_array($result))
        		{
				$zonename=$myrow["ZoneName"];
                		$regionname=$myrow["RegName"];
			}
			if ($regionval[0] == '2' || $regionval[0] =='3' || $regionval[0] == '6' || $regionval[0] == '8' || $regionval[0] == '4')
			{
				if ($zoneval[0] == '4' || $zoneval[0] == '7' || $zoneval[0] == '20' || $zoneval[0] == '14' || $zoneval[0] == '18' || $zoneval[0] == '15')
				{
					//$sql = "SELECT c.LABEL, r.BannerId, b.ZoneId, r.Impressions, DAYOFMONTH(r.Date) as dd , MONTH(r.Date) as mm , YEAR(r.Date) as yy  FROM bms2.BANNER b LEFT JOIN bms2.BANNERMIS r ON b.BannerId = r.BannerId LEFT JOIN bms2.LOC_CITIES c ON b.BannerInCity = c.VALUE WHERE (b.ZoneId = '$zoneval[0]' AND c.VALUE IS NOT NULL AND b.BannerStatus LIKE '%live%' AND r.Date BETWEEN '$startyear-$startmonth-01' AND '$startyear-$startmonth-31') GROUP BY dd , r.BannerId, b.BannerInCity ORDER BY r.Date desc";
					$sql = "SELECT b.BannerInCity as CITY, r.BannerId, b.ZoneId, r.Impressions, DAYOFMONTH(r.Date) as dd , MONTH(r.Date) as mm , YEAR(r.Date) as yy  FROM bms2.BANNER b LEFT JOIN bms2.BANNERMIS r ON b.BANNERId = r.BANNERId WHERE (b.ZoneId = '$zoneval[0]' AND  b.BannerInCity <> ' ' AND b.BannerStatus LIKE '%live%' AND r.Date BETWEEN '$startyear-$startmonth-01' AND '$startyear-$startmonth-31') GROUP BY dd ,b.BannerInCity ORDER BY r.Date desc";
					$res = mysql_query($sql) or  logErrorBms("bms_locationwisemis.php:locationMIS:2:Could not get locatiowise result for Top right banners. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"exit","NO");
					$i = 0;
					while($row = mysql_fetch_array($res))
					{
						$city = $row["CITY"];
						$dd = $row["dd"]-1;
						if (is_array($cityarr))
						{
							if (!in_array($city,$cityarr))
								$cityarr[] = $city;
						}
						else
							$cityarr[] = $city;
						if (in_array($city,$cityarr))
						{
							$k = array_search($city,$cityarr);
							$cities[$r]  = explode(',',$city);
                                                        for ($i = 0;$i < count($cities[$r]);$i++)
                                                        {
                                                                /*if ($citylabel1[$k])
                                                                {
                                                                        if(strstr($citylabel[$k],'getLocCity(trim($cities[$i+1])'))
                                                                                $citylabel1[$k][].=" , ".getLocCity(trim($cities[$i]));
                                                                }*/
                                                                //else
                                                                {
                                                                        $citylabel1[$k][$i] = getLocCity(trim($cities[$r][$i]));
                                                                }
                                                        }
							$citylabel[$k] = implode(" <br> ",$citylabel1[$k]);
							$ddimpressioncount[$k][$dd] = $row["Impressions"];
							$total[$dd] += $row["Impressions"];
							$totimpressions[$k] += $row["Impressions"];
							$monthtotal += $row["Impressions"];
							$m = count($total);
							$avg = $monthtotal/$m;
							$avg= floor($avg);
						}
					}
					//$counter = 0;	
					for ($i = 0;$i <= $k;$i++)
					{
						for ($j = 0;$j <= count($days);$j++)
						{
							$dividend = $ddimpressioncount[$i][$j];
							$divisor = $total[$j];
							if($divisor != 0)
							{
								$percent[$i][$j] = $dividend/$divisor * 100;
								$percent[$i][$j] = round($percent[$i][$j],2);
								//$totalpercent[$i] += $percent[$i][$j];
							}
						}
						//$counter = count($percent[$i]);
						//if ($counter != 0)
						{
							//$averagepercent[$i] = $totalpercent[$i]/$counter;
							$averagepercent[$i]= $totimpressions[$i]/$monthtotal * 100;
							$averagepercent[$i] = round($averagepercent[$i],2);
						}
					}
					$result = 1;
				}
				else
				{
					$result = 2;
					$sql = "SELECT r.BannerId , b.ZoneId, r.Impressions, DAYOFMONTH(r.Date) as dd , MONTH(r.Date) as mm , YEAR(r.Date) as yy  FROM bms2.BANNER b LEFT JOIN bms2.BANNERMIS r ON b.BANNERId = r.BANNERId  WHERE (b.ZoneId = '$zoneval[0]' AND b.BannerStatus LIKE '%live%' AND r.Date BETWEEN '$startyear-$startmonth-01' AND '$startyear-$startmonth-31') GROUP BY dd , r.BANNERId ORDER BY r.Date desc";
					$res = mysql_query($sql) or  logErrorBms("bms_locationwisemis.php:locationMIS:3:Could not get locatiowise result for non location banners. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"exit","NO");
					while($row = mysql_fetch_array($res))
					{
						$dd = $row["dd"]-1;
						$ddimpressioncount[$dd] = $row["Impressions"];
						$totimpressions += $row["Impressions"];
					}
				}
			}
			if ($regionval[0] == '1' || $regionval[0] == '5' || $regionval[0] == '7')
			{	
				$sql = "SELECT r.BannerId , b.ZoneId, r.Impressions, DAYOFMONTH(r.Date) as dd , MONTH(r.Date) as mm , YEAR(r.Date) as yy  FROM bms2.BANNER b LEFT JOIN bms2.BANNERMIS r ON b.BANNERId = r.BANNERId  WHERE (b.ZoneId = '$zoneval[0]' AND b.BannerStatus LIKE '%live%' AND r.Date BETWEEN '$startyear-$startmonth-01' AND '$startyear-$startmonth-31') GROUP BY dd , r.BANNERId ORDER BY r.Date desc";
				$res = mysql_query($sql) or  logErrorBms("bms_locationwisemis.php:locationMIS:4:Could not get locatiowise result for default banners. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"exit","NO");
				while($row = mysql_fetch_array($res))
				{
					$dd = $row["dd"]-1;
					$ddimpressioncount[$dd] = $row["Impressions"];
					$totimpressions += $row["Impressions"];
				}
				$result = 2;
			}
			$smarty->assign('avg',$avg);
			$smarty->assign('citylabel',$citylabel);
			$smarty->assign('zonename',$zonename);
			$smarty->assign('regionname',$regionname);
			$smarty->assign("cityarr",$cityarr);
			$smarty->assign("ddimpressioncount",$ddimpressioncount);
			$smarty->assign("startmonth",$startmonth);
			$smarty->assign("startyear",$startyear);
			$smarty->assign("percent",$percent);
			$smarty->assign("total",$total);
			$smarty->assign("totalpercent",$totalpercent);
			$smarty->assign("totimpressions",$totimpressions);
			$smarty->assign("averagepercent",$averagepercent);
			$smarty->assign("monthtotal",$monthtotal);
			$smarty->assign("result",$result);
			$smarty->assign("id",$id);
			$smarty->display("./$_TPLPATH/bms_locationwise.htm");
		}
	}
	else
	{
		$id=$data["ID"];
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_locationwise.htm");
	}
}
else
{
	TimedOutBms();
}
?>
