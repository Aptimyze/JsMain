<?php

/*********************************************************bms_checkavail.php***********************************************/
/**		
	*	 Created By            : Abhinav Katiyar		
    	*	 Last Modified By      : Abhinav Katiyar
    	*	 Description           : This file is for checking availability and showing banners on selected criterias
*************************************************************************************************************************/

   function checkavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt,$showdefault="")
   {
	global $dbbms,$smarty,$_TPLPATH,$id,$dowhat;//print_r($criteria);
	//print_r($selectedvalues);

	$sql="Select r.RegName,z.ZoneName,z.ZoneMaxBans,z.ZoneMaxBansInRot from bms2.ZONE z,bms2.REGION r where ZoneId='$zoneid' and r.RegId=z.RegId";
    $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkavail:1:Could not get Zone listings. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
	if($myrow=mysql_fetch_array($result))
	{
		$zonename=$myrow["ZoneName"];
		$region=$myrow["RegName"];	
		$maxbans=$myrow["ZoneMaxBans"];
		$maxbansinrot=$myrow["ZoneMaxBansInRot"];	
	}
	if(is_array($criteria))
	{
		$resarr=checkoncriterias($zoneid,$maxbans,$maxbansinrot,$criteria,$selectedvalues,$startdt,$enddt);
	}	
	elseif ($showdefault == 'Y')
	{
		$show_crit="Default";
		for($i=1;$i<=$maxbans;$i++)
		{
			$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$i' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerFixed='Y'";
		   	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
	      	
		   	$pri[$i-1]=$i;
		        
           		if($rowcount=mysql_fetch_array($res))
			{
				$resarr[$i]["count"]=$rowcount["cnt"];
				//if($resarr[$i]["count"]>=$maxbansinrot)
				if($resarr[$i]["count"]>=1)
				{
					$resarr[$i]["avail"]='N';
				}
				else
				{
					$resarr[$i]["avail"]='Y';
				}
			}	
		}
	}
	else
	{
		$show_crit="No Criteria";
                for($i=1;$i<=$maxbans;$i++)
                {
                        $sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$i' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='Y'";
                        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                                                                                                            
                        $pri[$i-1]=$i;
                                                                                                                            
                        if($rowcount=mysql_fetch_array($res))
                        {
                                $resarr[$i]["count"]=$rowcount["cnt"];
                                if($resarr[$i]["count"]>=$maxbansinrot)
                                {
                                        $resarr[$i]["avail"]='N';
                                }
                                else
                                {
                                        $resarr[$i]["avail"]='Y';
                                }
                        }
                }
	}
	$smarty->assign('show_crit',$show_crit);
	$smarty->assign('maxbans',$maxbans);
	$smarty->assign('zonename',$zonename);
	$smarty->assign('region',$region);
	$smarty->assign('startdt',$startdt);
	$smarty->assign('enddt',$enddt);
	$smarty->assign('maxbansinrot',$maxbansinrot);
	$smarty->assign('resarr',$resarr);
	$smarty->assign('pri',$pri);
	$bmsheader=fetchHeaderBms($data);
        $bmsfooter=fetchFooterBms();
        $smarty->assign("bmsheader",$bmsheader);
        $smarty->assign("bmsfooter",$bmsfooter);
        $smarty->assign("id",$id);
	$smarty->assign("dowhat",$dowhat);	
	$smarty->display("./$_TPLPATH/bms_checkavail.htm");
	  
   }		

   function checkoncriterias($zoneid,$maxbans,$maxbansinrot,$criteria,$selectedvalues,$startdt,$enddt)
   {
   		global $dbbms,$smarty,$show_crit,$pri;	
		for($i=1;$i<=$maxbans;$i++)
		{
			$pri[$i-1]=$i;
			$resarr[$i]["count"]=0;
			$resarr[$i]["avail"]='Y';
		}
		$smarty->assign('prior',$pri);
   		$smarty->assign('pri',$pri);

		if(in_array("LOCATION",$criteria))
		{
			$location=$selectedvalues["location"];
                        $location = substr($location , 1 ,-1);
                        list($countryarr,$cityarr) = explode("|X|",$location);
                        $uscityarr      = explode("$",$cityarr);
                        $cityarr        = $uscityarr[0];
			//print_r($countryarr);
                        if(count($countryarr >= 1))
                                $country        = explode(",",$countryarr);
                        else
                                $country = trim($countryarr);
                        $cityarr  = substr($cityarr,0,-1);
                        if(count($cityarr)>= 1)
                                $city        = explode(",",$cityarr);
                        else
                                $city = trim($cityarr);
                        if(count($uscityarr[1] >= 1))
                                $uscity        = explode(",",$uscityarr[1]);
                        else
                                $uscity = trim($uscityarr[1]);
                        for ($i = 0;$i < count($country);$i++)
                        {
                                        if ($countrystr)
                                        {
                                                $countrystr.=" , ".get_farea_bms(trim($country[$i]),"country");
                                        }
                                        else
                                        {
                                                $countrystr = get_farea_bms(trim($country[$i]),"country");
                                        }
                        }
			for ($i = 0;$i < count($city);$i++)
                        {
                                        if ($indiancitystr)
                                        {
                                                $indiancitystr.=" , ".getLocCity(trim($city[$i]));
                                        }
                                        else
                                        {
                                                $indiancitystr = getLocCity(trim($city[$i]));
                                        }
                        }
                                                                                                                            
                        for ($i = 0;$i < count($uscity);$i++)
                        {
                                if ($uscitystring)
                                {
                                                $uscitystring.=" , ".getLocCity(trim($uscity[$i]));
                                }
                                else
                                {
                                                $uscitystring = getLocCity(trim($uscity[$i]));
                                }
                        }
                        $show_crit.="<BR>Location : ";
                        $show_crit.="COUNTRY : $countrystr<br>";
                        if($indiancitystr)
                                $show_crit.="<B>CITIES</B> : $indiancitystr<br>";
                        if($uscitystring)
                                $show_crit.="<B>CITIES</B> : $uscitystring<br>";
			
			$sql = "Select count(*) as cnt , BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N'";
                        for($i=0;$i<count($country);$i++)
                        {
                                if($ctrystr)
                                {
                                        $country[$i] = trim($country[$i]);
                                        $ctrystr.= " and BannerCountry like '%$country[$i]%'";
                                        if($country[$i] == 51)
                                        {
                                                for($j=0;$j<count($city);$j++)
                                                {
                                                        $city[$j]= trim($city[$j]);
                                                        {
                                                                if ($incitystr)
                                                                {
                                                                        $incitystr.= " and BannerInCity like '%$city[$j]%'";                                                                }
                                                                else
                                                                        $incitystr= " and BannerInCity like '%$city[$j]%'";
                                                        }
                                                }
                                                                                                                            
                                        if($incitystr)
                                                $ctrystr.= $incitystr;
					else
                                                $ctrystr.= " and BannerInCity =''";
                                        }
                                        if($country[$i] == 127)
                                        {
                                                for($j=0;$j<count($uscity);$j++)
                                                {
                                                        $uscity[$j] = trim($uscity[$j]);
                                                                                                                            
                                                        {
                                                                if ($uscitystr)
                                                                {
                                                                        $uscitystr.= " and BannerUsCity like '%$uscity[$j]%'";
								}
                                                                else
                                                                        $uscitystr= " and BannerUsCity like '%$uscity[$j]%'";
                                                        }
                                                }
                                                                                                                            
                                        if($uscitystr)
                                                $ctrystr.= $uscitystr;
					else
                                                $ctrystr.= " and BannerUsCity =''";
                                        }
                                        //echo $countrystring.=" , ".get_farea_bms(trim($country[$i]),"country");
                                        //echo "END";
                                }
                                else
                                {       $country[$i] = trim($country[$i]);
                                        $ctry = trim($country[$i]);
                                        $ctrystr = " and BannerCountry like '%$country[$i]%'";
                                        if($country[$i] == 51)
                                        {
                                                for($j=0;$j<count($city);$j++)
                                                {
                                                                $city[$j] = trim($city[$j]);
                                                                if ($incitystr)
                                                                {
                                                                        $incitystr.= " and BannerInCity like '%$city[$j]%'";                                                                }
                                                                else
                                                                {
                                                                        $state=trim($city[$j]);
                                                                        if($state!="")
                                                                        {
                                                                                $incitystr= " and BannerInCity like '%$state%'";
                                                                        }
                                                                }
                                                }
                                                                                                                            
                                        if($incitystr)
                                                $ctrystr.= $incitystr;
					else
						$ctrystr.= " and BannerInCity =''";
					}
                                        if($country[$i] == 127)
                                        {
                                                for($j=0;$j<count($uscity);$j++)
                                                {
                                                        $uscity[$j] = trim($uscity[$j]);
                                                        {
                                                                if ($uscitystr)
                                                                {
                                                                        $uscitystr.= " and BannerUsCity like '%$uscity[$j]%'";
                                                                }
                                                                else
                                                                        $uscitystr= " and BannerUsCity like '%$uscity[$j]%'";
                                                                                                                            
                                                        }
                                                }
                                                                                                                            
                                        if($uscitystr)
                                                $ctrystr.= $uscitystr;
					else
                                                $ctrystr.= " and BannerUsCity =''";
                                        }
                                        //$countrystring=get_farea_bms(trim($country[$i]),"country");
                                }
                        }
                        $sql.=$ctrystr;
                        $sql.= " Group By BannerPriority";
                        $sql123=$sql;
                        $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                        $row=mysql_fetch_array($result);
			//if($row=mysql_fetch_array($result))
                        {
                                do//for ($i =0;$i<count($row); $i++)
                                {
                                        $priority=$row["BannerPriority"];
                                        $count=$row["cnt"];
                                        if($count>$resarr[$priority]["count"])
                                        {
                                                $resarr[$priority]["count"]=$count;
                                                                                                                            
                                        }
                                        if($resarr[$priority]["count"]>=$maxbansinrot)
                                        {
                                                $resarr[$priority]["avail"]='N';
                                        }
                                }while($row = mysql_fetch_array($result));
                        }

		}

		if(in_array("IP",$criteria))
		{
			$ip=$selectedvalues["ip"];
			$show_crit.="IP : ";
			for($i=0;$i<count($ip);$i++)
			{
				$ipkey=$ip[$i];
				$show_crit.=" # ".get_farea_bms($ipkey,"city");
				$locids=getLocids($ipkey);
				$locarr=explode(",",$locids);
				for($loc_cnt=0;$loc_cnt<count($locarr);$loc_cnt++)
				{
					$locval=$locarr[$loc_cnt];
					$sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and BannerIP like '% $locval %' Group By BannerPriority";
					$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 			
					if($myrow=mysql_fetch_array($result))
					{
						do 
						{
							$priority=$myrow["BannerPriority"];
							$count=$myrow["cnt"];
							if($count>$resarr[$priority]["count"])
							{
								$resarr[$priority]["count"]=$count;
							
							}
							if($resarr[$priority]["count"]>=$maxbansinrot)
							{
								$resarr[$priority]["avail"]='N';
							}
						}while($myrow=mysql_fetch_array($result));
					}
				
				}
					
			}
			$show_crit.="<BR>";
		}

		if(in_array("INCOME",$criteria))
		{
			$ctc=$selectedvalues["ctc"];
                        $show_crit.="CTC : ";
			
                        for($i=0;$i<count($ctc);$i++)
                        {
                                $ctckey=$ctc[$i];
                                $show_crit.=" # ".get_farea_bms($ctckey,"ctc");

				$sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerCTC` like '% $ctckey %') Group By BannerPriority";
				$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 			
				
				if($myrow=mysql_fetch_array($result))
				{
					do 
					{
						$priority=$myrow["BannerPriority"];
						$count=$myrow["cnt"];
						if($count>$resarr[$priority]["count"])
						{
							$resarr[$priority]["count"]=$count;
						}
						if($resarr[$priority]["count"]>=$maxbansinrot)
						{
							$resarr[$priority]["avail"]='N';
						}
					}while($myrow=mysql_fetch_array($result));
				}
			}
		}
		if(in_array("SUBSCRIPTION",$criteria))
                {
                        $mem=$selectedvalues["mem"];
                        $show_crit.="SUBSCRIPTION : ";
                                                                                                                            
                        for($i=0;$i<count($mem);$i++)
                        {
                                $memkey=$mem[$i];
                                $show_crit.=" # ".get_farea_bms($memkey,"mem");
                                                                                                                            
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerMEM` like '% $memkey %') Group By BannerPriority";
				$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("RELIGION",$criteria))
                {
                        $rel=$selectedvalues["rel"];
                        $show_crit.="RELIGION :  ";

                        for($i=0;$i<count($rel);$i++)
                        {
                                $relkey=$rel[$i];
                                $show_crit.=" # ".get_farea_bms($relkey,"rel");
                                                                                                                            
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerREL` like '% $relkey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("EDUCATION",$criteria))
                {
                        $edu=$selectedvalues["edu"];
                        $show_crit.="EDUCATION : ";
                        for($i=0;$i<count($edu);$i++)
                        {
                                $edukey=$edu[$i];
                                $show_crit.=" # ".get_farea_bms($edukey,"edu");
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerEDU` like '% $edukey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("OCCUPATION",$criteria))
                {
                        $occ=$selectedvalues["occ"];
                        $show_crit.="OCCUPATION: ";
                        for($i=0;$i<count($occ);$i++)
                        {
                                $occkey=$occ[$i];
                                $show_crit.=" # ".get_farea_bms($occkey,"occ");
                                                                                                                            
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerOCC` like '% $occkey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("COMMUNITY",$criteria))
                {
                        $com=$selectedvalues["com"];
                        $show_crit.="COMMUNITY :";
                        for($i=0;$i<count($com);$i++)
                        {
                                $comkey=$com[$i];
                                $show_crit.=" # ".get_farea_bms($comkey,"com");
                                                                                                                            
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerCOM` like '% $comkey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if (in_array("PROPCAT",$criteria))
		{
			$propcat=$selectedvalues["propcat"];
                        $show_crit.="PROPERTY CATEGORY :".$propcat;
                        $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( BannerPROPCAT='$propcat') Group By BannerPriority";
			$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                        if($myrow=mysql_fetch_array($result))
                        {
                        	do
                                {
                                	$priority=$myrow["BannerPriority"];
                                        $count=$myrow["cnt"];
                                        if($count>$resarr[$priority]["count"])
                                        {
                                        	$resarr[$priority]["count"]=$count;
                                        }
                                        if($resarr[$priority]["count"]>=$maxbansinrot)
                                        {
                                                        $resarr[$priority]["avail"]='N';
                                        }
                                 }while($myrow=mysql_fetch_array($result));
                         }
			//print_r($resarr);

		}
		if(in_array("PROPCITY",$criteria))
                {
                        $propcity=$selectedvalues["propcity"];
                        $show_crit.="PROPERTY CITY :";
                        for($i=0;$i<count($propcity);$i++)
                        {
                                $propcitykey=$propcity[$i];
                                $show_crit.=" # ".get_farea_bms($propcitykey,"propcity");
				$sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerPROPCITY` like '% $propcitykey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("PROPINR",$criteria) && !(in_array("PROPCAT",$criteria)))
                {
			$propcategory = $selectedvalues["propcat"];
			if ($propcategory == 'Rent')
				$propinr=$selectedvalues["proprentinr"];
			elseif ($propcategory == 'Buy')
				$propinr=$selectedvalues["propinr"];
                        $show_crit.=" PROPERTY INR :";
                        for($i=0;$i<count($propinr);$i++)
                        {
                                $propinrkey=$propinr[$i];
				if ($propcategory == 'Buy')
                                	$show_crit.=" # ".get_farea_bms($propinrkey,"propinr");
				else
					$show_crit.=" # ".get_farea_bms($propinrkey,"proprentinr");
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerPROPINR` like '% $propinrkey %' and BannerPROPCAT='$propcategory') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("PROPTYPE",$criteria))
                {
                        $proptype=$selectedvalues["proptype"];
                        $show_crit.=" PROPERTY TYPE :";
                        for($i=0;$i<count($proptype);$i++)
                        {
                                $proptypekey=$proptype[$i];
                                $show_crit.=" # ".get_farea_bms($proptypekey,"proptype");

				$sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerPROPTYPE` like '% $proptypekey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("MARITALSTATUS",$criteria))
                {
                        $mstatus=$selectedvalues["mstatus"];
                        $show_crit.="MARITALSTATUS  ";
                                                                                                                            
                        for($i=0;$i<count($mstatus);$i++)
                        {
                                $mstatuskey=$mstatus[$i];
                                $show_crit.=" # ".get_farea_bms($mstatuskey,"mstatus");
                                                                                                                            
                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerMARITALSTATUS` like '% $mstatuskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
		if(in_array("AGE",$criteria))
		{
			$agemin=$selectedvalues["agemin"];
			$agemax=$selectedvalues["agemax"];
			$show_crit.="Age : ".$selectedvalues["agemin"]." to ".$selectedvalues["agemax"]."<BR>";
			$sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( '$agemax' >= `BannerAgeMin` AND '$agemin' <= `BannerAgeMax`) Group By BannerPriority";
			$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");	
			if($myrow=mysql_fetch_array($result))
			{
				do 
				{
					$priority=$myrow["BannerPriority"];
					$count=$myrow["cnt"];
					if($count>$resarr[$priority]["count"])
					{
						$resarr[$priority]["count"]=$count;
						
					}
					if($resarr[$priority]["count"]>=$maxbansinrot)
					{
						$resarr[$priority]["avail"]='N';
					}
				}while($myrow=mysql_fetch_array($result));
			}
				
		}
			
		if(in_array("GENDER",$criteria))
                {
                        $gender=$selectedvalues["gender"];
                        $show_crit.="Gender : ".$selectedvalues["gender"]."<BR>";
                        $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( BannerGender='$gender') Group By BannerPriority";
                        $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php****:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                                                                                                             
                        if($myrow=mysql_fetch_array($result))
                        {
                                do
                                {
                                        $priority=$myrow["BannerPriority"];
                                        $count=$myrow["cnt"];
                                        if($count>$resarr[$priority]["count"])
                                        {
                                                $resarr[$priority]["count"]=$count;
                                                                                                                             
                                        }
                                        if($resarr[$priority]["count"]>=$maxbansinrot)
                                        {
                                                $resarr[$priority]["avail"]='N';
                                        }
                                }while($myrow=mysql_fetch_array($result));
                        }
                }
		//added by lavesh rawat
		global $vdArray,$profileStatus,$mailID,$eoiStatus,$registrationStatus,$ftoState_array,$ftoExpiry_array;
                if(in_array("VARIABLE_DISCOUNT",$criteria))
                {
                        $vd=$selectedvalues["vd"];
                        $show_crit.=" Variable Discount :";
                        for($i=0;$i<count($vd);$i++)
                        {
                                $vdkey=$vd[$i];
                                $show_crit.=" # ".$vdArray[$vdkey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerJsVd` like '% $vdkey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }

                if(in_array("PROFILE_STATUS",$criteria))
                {
                        $ps=$selectedvalues["profileStatus"];
                        $show_crit.=" Profile Status :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$profileStatus[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerJsProfileStatus` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }

                if(in_array("GMAIL_ID",$criteria))
                {
                        $ps=$selectedvalues["jsMailID"];
                        $show_crit.=" Gmail Id :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$mailID[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerJsMailID` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }

               if(in_array("EOI_STATUS",$criteria))
                {
                        $ps=$selectedvalues["jsEoiStatus"];
                        $show_crit.=" Eoi Status :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$eoiStatus[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `bannerJsEoiStatus` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
                if(in_array("REGISTRATION_STATUS",$criteria))
                {
                        $ps=$selectedvalues["jsRegistrationStatus"];
                        $show_crit.=" Registration Status :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$registrationStatus[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `BannerJsRegistrationStatus` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }

                if(in_array("FTO_STATE",$criteria))
                {
                        $ps=$selectedvalues["jsFtoStatus"];
                        $show_crit.=" Fto Status :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$ftoState_array[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `bannerJsFtoStatus` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }
                if(in_array("FTO_EXPIRY",$criteria))
                {
                        $ps=$selectedvalues["jsFtoExpiry"];
                        $show_crit.=" Fto Expiry :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$ftoExpiry_array[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `bannerJsFtoExpiry` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }

                if(in_array("PROFILE_COMPLETE_STATE",$criteria))
                {
                        $ps=$selectedvalues["jsProfileCompletionState"];
                        $show_crit.=" Profile Completion State :";
                        for($i=0;$i<count($ps);$i++)
                        {
                                $pskey=$ps[$i];
                                $show_crit.=" # ".$profileCompletionState_array[$pskey];

                                $sql="Select count(*) as cnt,BannerPriority from bms2.BANNER where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and ( `bannerJsProfileCompletionState` like '% $pskey %') Group By BannerPriority";
                                $result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
                                if($myrow=mysql_fetch_array($result))
                                {
                                        do
                                        {
                                                $priority=$myrow["BannerPriority"];
                                                $count=$myrow["cnt"];
                                                if($count>$resarr[$priority]["count"])
                                                {
                                                        $resarr[$priority]["count"]=$count;
                                                }
                                                if($resarr[$priority]["count"]>=$maxbansinrot)
                                                {
                                                        $resarr[$priority]["avail"]='N';
                                                }
                                        }while($myrow=mysql_fetch_array($result));
                                }
                        }
                }



		//added by lavesh rawat

		$smarty->assign('show_crit',$show_crit);
		$smarty->assign('crit',$show_crit);
		return ($resarr);
		
   }

   function viewavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt,$showall='N',$showdefault='N')
   {		
		global $dbbms,$smarty,$_TPLPATH,$id,$dowhat;

		$smarty->assign("SITE_JSURL","http://www.jeevansathi.com");
		$smarty->assign("SITE_99URL","http://www.99acres.com");

		//$smarty->assign("SITE_JSURL","http://172.16.0.192");
		//$smarty->assign("SITE_99URL","http://dev99.infoedge.com");

		$sql="Select r.RegName,z.ZoneName,z.ZoneMaxBans,z.ZoneMaxBansInRot from bms2.ZONE z,bms2.REGION r where ZoneId='$zoneid' and r.RegId=z.RegId";
		$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:1:Could not get Zone listings. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
		if($myrow=mysql_fetch_array($result))
		{
			$zonename=$myrow["ZoneName"];
			$region=$myrow["RegName"];	
			$maxbans=$myrow["ZoneMaxBans"];
			$maxbansinrot=$myrow["ZoneMaxBansInRot"];	
		}
		//c.CampaignId,REF_ID added by lavesh
		if($showall && $showall!='N')
		{
			   $show_crit.="All Banners";
			   $sql="Select c.SITE ,c.CampaignId,REF_ID,c.CampaignName,BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and c.CampaignId=b.CampaignId Order By BannerPriority,BannerId";
			   $res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
		}
		else
		{
			if(is_array($criteria))
			{
				$sql=viewoncriterias($zoneid,$maxbans,$maxbansinrot,$criteria,$selectedvalues,$startdt,$enddt);	
				$res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
			}
			elseif ($showdefault == 'Y')
			{
		   		$show_crit.="Default";
		   		$sql="Select c.SITE , c.CampaignId,REF_ID,c.CampaignName,BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault,BannerFixed from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerFixed='Y' and c.CampaignId=b.CampaignId Order By BannerPriority,BannerId";
		   		$res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
			}
			else
			{
				$show_crit.="No Criteria";
                                $sql="Select c.SITE , c.CampaignId,REF_ID,c.CampaignName,BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault,BannerFixed from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='Y' and c.CampaignId=b.CampaignId Order By BannerPriority,BannerId";
		   		$res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
			}
		}
     		if($rowcount=mysql_fetch_array($res))
	 	{
	 		do 
	 		{
	 			$banner=$rowcount["BannerId"];
	 			$default=$rowcount["BannerDefault"];
				$rowcrits=showcriterias($banner);
				$criteria=$rowcrits["criteria"];

	 			if(is_array($bannarr))
	 			{
	 				if(!in_array($banner,$bannarr))
	 				{
	 					$cnt=count($bannarr);
	 					$bannarr[$cnt]=$banner;
	 				}
	 			}
	 			else
				{
					$bannarr[0]=$banner;
				}
				$resarr[$banner]["priority"]=$rowcount["BannerPriority"];
				$resarr[$banner]["priname"]="pri".$rowcount["BannerId"];
				$resarr[$banner]["wtname"]="weight".$rowcount["BannerId"];
				$resarr[$banner]["weight"]=$rowcount["BannerWeightage"];
				$class=$rowcount["BannerClass"];
				$gif=$rowcount["BannerGif"];
			
				if($class=="Image")
				{
					$resarr[$banner]["banner"]="<a href=$gif target=_blank><Img src=\"$gif\" border=0 width=100 height=25></a>";
				}
				elseif ($class=="Flash")
				{
					$resarr[$banner]["banner"]="<object><embed src=\"$gif\" width=100 height=25></embed></object>";
				}
				elseif ($class=="Popup" || $class=="Popunder")
				{
					$resarr[$banner]["banner"]="<a href=\"$gif\">View Popup/Popunder</a>";
				}
			
				$resarr[$banner]["startdt"]=$rowcount["BannerStartDate"];
				$resarr[$banner]["enddt"]=$rowcount["BannerEndDate"];
				$resarr[$banner]["fop"]=$rowcount["BannerFreeOrPaid"];
				$resarr[$banner]["status"]=$rowcount["BannerStatus"];
				$resarr[$banner]["campaign"]=$rowcount["CampaignName"];
				$resarr[$banner]["campaignid"]=$rowcount["CampaignId"];
				$resarr[$banner]["ref_id"]=$rowcount["REF_ID"];
				$resarr[$banner]["site"]=$rowcount["SITE"];
				$resarr[$banner]["criteria"]=$criteria;
				//print_r($resarr);
			}while($rowcount=mysql_fetch_array($res));
			$banners=implode(",",$bannarr);
		  }	  
		 $smarty->assign('banners',$banners);
		 $smarty->assign('maxbans',$maxbans);
		 $smarty->assign('zonename',$zonename);
		 $smarty->assign('region',$region);
		 $smarty->assign('show_crit',$show_crit);
		 $smarty->assign('startdt',$startdt);
		 $smarty->assign('enddt',$enddt);
		 $smarty->assign('maxbansinrot',$maxbansinrot);
		 $smarty->assign('resarr',$resarr);
		 $smarty->assign('bannarr',$bannarr);
		 $bmsheader=fetchHeaderBms($data);
		 $bmsfooter=fetchFooterBms();
	         $smarty->assign("bmsheader",$bmsheader);
	         $smarty->assign("bmsfooter",$bmsfooter);
	         $smarty->assign("id",$id);
	         $smarty->assign("dowhat",$dowhat);
	
		 $smarty->display("./$_TPLPATH/bms_viewavail.htm");
   }		

   function viewoncriterias($zoneid,$maxbans,$maxbansinrot,$criteria,$selectedvalues,$startdt,$enddt)
   {
   		global $dbbms,$smarty;	
		//print_r($criteria);

		if(in_array("Keywords",$criteria))
		{
			$keywords=$selectedvalues["keywords"];
			$keywords=parseKeywords($keywords);
			$show_crit.="Keywords : ".$keywords."<BR>";
			$keysarray=explode(",",$keywords);
			
			for($i=0;$i<count($keysarray);$i++)
			{
				$key=$keysarray[$i];
				
				if($keystring && trim($keystring)!='')
				{
				  $keystring.=" or BannerKeyword like '% $key %'";
				}
				else 
				{
					$keystring="(BannerKeyword like '% $key %'";
				}
					
			}
			if($keystring && trim($keystring)!='')
			{
				$keystring.=")";
			}
			 	
			
		}
		if(in_array("LOCATION",$criteria))
		{
			$location=$selectedvalues["location"];
                        $location = substr($location , 1 ,-1);
                        list($countryarr,$cityarr) = explode("|X|",$location);
                        $uscityarr      = explode("$",$cityarr);
                        $cityarr        = $uscityarr[0];
                        if(count($countryarr >= 1))
                                $country        = explode(",",$countryarr);
                        else
                                $country = trim($countryarr);
                                                                                                        
                        $cityarr  = substr($cityarr,0,-1);
                        if(count($cityarr)>= 1)
                                $city        = explode(",",$cityarr);
                        else
                                $city = trim($cityarr);
                        if(count($uscityarr[1] >= 1))
                                $uscity        = explode(",",$uscityarr[1]);
                        else
                                $uscity = trim($uscityarr[1]);
                        for ($i = 0;$i < count($country);$i++)
                        {	
                                	if ($countrystr)
                                        {
                                                $countrystr.=" , ".get_farea_bms(trim($country[$i]),"country");
                                        }
                                        else
                                        {
                                                $countrystr = get_farea_bms(trim($country[$i]),"country");
                                        }
                        }
			for ($i = 0;$i < count($city);$i++)
                        {
                                        if ($indiancitystr)
                                        {
                                                $indiancitystr.=" , ".getLocCity(trim($city[$i]));
                                        }
                                        else
                                        {
                                                $indiancitystr = getLocCity(trim($city[$i]));
                                        }
                        }
                        for ($i = 0;$i < count($uscity);$i++)
                        {
                                if ($uscitystring)
                                {
                                                $uscitystring.=" , ".getLocCity(trim($uscity[$i]));
                                }
                                else
                                {
                                                $uscitystring = getLocCity(trim($uscity[$i]));
                                }
                        }
                                                                                                                            
                        $show_crit.="<BR>Location : ";
                        $show_crit.="COUNTRY : $countrystr<br>";
                        if($indiancitystr)
                                $show_crit.="CITIES : $indiancitystr<br>";
                        if($uscitystring)
                                $show_crit.="CITIES : $uscitystring<br>";
			for($i=0;$i<count($country);$i++)
                        {
                                if($ctrystr)
                                {
                                        $country[$i] = trim($country[$i]);
                                        $ctrystr.= " or BannerCountry like '%$country[$i]%'";
                                        if($country[$i] == 51)
                                        {
                                                for($j=0;$j<count($city);$j++)
                                                {
                                                        $city[$j]= trim($city[$j]);
                                                        {
                                                                if ($incitystr)
                                                                {
                                                                        $incitystr.= " or BannerInCity like '%$city[$j]%'";                                                                  }
                                                                else
                                                                        $incitystr= " and (BannerInCity like ' %$city[$j] %'";
                                                        }
                                                }
                                                if($incitystr)
                                                	$ctrystr.= $incitystr.")";
						else
                                                	$ctrystr.= " and BannerInCity =''";
                                        }
                                        if($country[$i] == 127)
                                        {
                                                for($j=0;$j<count($uscity);$j++)
                                                {
                                                        $uscity[$j] = trim($uscity[$j]);                                                                                                                             
                                                       {
                                                                if ($uscitystr)
                                                                {
                                                                        $uscitystr.= " or BannerUsCity like '%$uscity[$j]%'";
                                                                }
                                                                else
                                                                        $uscitystr= " and (BannerUsCity like '%$uscity[$j]%'";
							}
                                                }
                                                                                                                            
                                        if($uscitystr)
                                                $ctrystr.= $uscitystr.")";
					else
                                                $ctrystr.= " and BannerUsCity =''";
                                        }
                                }
                                else
                                {       $country[$i] = trim($country[$i]);
                                        $ctrystr = "  BannerCountry like '%$country[$i]%'";
                                        if($country[$i] == 51)
                                        {
                                                for($j=0;$j<count($city);$j++)
                                                {
                                                                $city[$j] = trim($city[$j]);
                                                                if ($incitystr)
                                                                {
                                                                        $incitystr.= " or BannerInCity like '%$city[$j]%'";                                                                }
                                                                else
                                                                {
                                                                        $state=trim($city[$j]);
                                                                        if($state!="")
                                                                        {
                                                                                $incitystr= " and (BannerInCity like '%$state%'";
                                                                        }
                                                                }
                                                }
                                                                                                                            
                                        if($incitystr)
                                                $ctrystr.= $incitystr.")";
					else
                                                $ctrystr.= " and BannerInCity =''";
                                        }
					if($country[$i] == 127)
                                        {
                                                for($j=0;$j<count($uscity);$j++)
                                                {
                                                        $uscity[$j] = trim($uscity[$j]);
                                                        {
                                                                if ($uscitystr)
                                                                {
                                                                        if ($uscity[$j]!=' ')
                                                                                $uscitystr.= " or BannerUsCity like '%$uscity[$j]%'";
                                                                }
                                                                else
                                                                {
                                                                        if ($uscity[$j]!=' ')
                                                                                $uscitystr= " and ( BannerUsCity like '%$uscity[$j]%'";
                                                                 }                                                                                                                             
                                                        }
                                                }
                                                                                                                            
                                        if($uscitystr)
                                                $ctrystr.= $uscitystr.")";
					else
                                                $ctrystr.= " and BannerUsCity =''";
                                        }
                                                                                                                            
                                }
                        }
                        $locstring.= $ctrystr;
		}
		if(in_array("IP",$criteria))
		{
			$ip=$selectedvalues["ip"];
			$show_crit.="IP : ";
			for($i=0;$i<count($ip);$i++)
			{
				$ipkey=$ip[$i];
				$show_crit.=" # ".get_farea_bms($ipkey,"city");
				if($ipstring && trim($ipstring)!='')
				{
					$ipstring.=" or BannerCity like '% $ipkey %'";
				}
				else
				{
					$ipstring="(BannerCity like '% $ipkey %'";
				}
			}
			if($ipstring && trim($ipstring)!='')
			{
				$ipstring.=")";
			}
			
			$show_crit.="<BR>";
		}
		if(in_array("AGE",$criteria))
		{
			$agemin=$selectedvalues["agemin"];
			$agemax=$selectedvalues["agemax"];
			$show_crit.="Age : ".$agemin." to ".$agemax."<BR>";
			$agestring="( '$agemax' >= `BannerAgeMin` AND '$agemin' <= `BannerAgeMax`)";
		}
		if(in_array("INCOME",$criteria))
		{
			$ctc=$selectedvalues["ctc"];
                        $show_crit.="CTC : ";
                        for($i=0;$i<count($ctc);$i++)
                        {
                                $ctckey=$ctc[$i];
                                $show_crit.=" # ".get_farea_bms($ctckey,"ctc");
                                if($ctcstring && trim($ctcstring)!='')
                                {
                                	$ctcstring.=" or BannerCTC like '% $ctckey %'";
                                }
                                else
                                {
                                        $ctcstring="(BannerCTC like '% $ctckey %'";
                                }
                        }
                        if($ctcstring && trim($ctcstring)!='')
                        {
                                $ctcstring.=")";
                        }
		}
		if(in_array("SUBSCRIPTION",$criteria))
                {
                        $mem=$selectedvalues["mem"];
                        $show_crit.="SUBSCRIPTION : ";
                        for($i=0;$i<count($mem);$i++)
                        {
                                $memkey=$mem[$i];
                                $show_crit.=" # ".get_farea_bms($memkey,"mem");
                                if($memstring && trim($memstring)!='')
                                {
                                        $memstring.=" or BannerMEM like '% $memkey %'";
                                }
                                else
                                {
                                        $memstring="(BannerMEM like '% $memkey %'";
                                }
                        }
                        if($memstring && trim($memstring)!='')
                        {
                                $memstring.=")";
                        }
                }
		if(in_array("MARITALSTATUS",$criteria))
                {
                        $mstatus=$selectedvalues["mstatus"];
                        $show_crit.="MARITALSTATUS : ";
                        for($i=0;$i<count($mstatus);$i++)
                        {
                                $mstatuskey=$mstatus[$i];
                                $show_crit.=" # ".get_farea_bms($mstatuskey,"mstatus");
                                if($mstatusstring && trim($mstatusstring)!='')
                                {
                                        $mstatusstring.=" or BannerMARITALSTATUS like '% $mstatuskey %'";
                                }
                                else
                                {
                                        $mstatusstring="(BannerMARITALSTATUS like '% $mstatuskey %'";
                                }
                        }
                        if($mstatusstring && trim($mstatusstring)!='')
                        {
                                $mstatusstring.=")";
                        }
                }
		if(in_array("RELIGION",$criteria))
                {
                        $rel=$selectedvalues["rel"];
                        $show_crit.="RELIGION : ";
                        for($i=0;$i<count($rel);$i++)
                        {
                                $relkey=$rel[$i];
                                $show_crit.=" # ".get_farea_bms($relkey,"rel");
                                if($relstring && trim($relstring)!='')
                                {
                                        $relstring.=" or BannerREL like '% $relkey %'";
                                }
                                else
                                {
                                        $relstring="(BannerREL like '% $relkey %'";
                                }
                        }
                        if($relstring && trim($relstring)!='')
                        {
                                $relstring.=")";
                        }
                }
		if(in_array("EDUCATION",$criteria))
                {
                        $edu=$selectedvalues["edu"];
                        $show_crit.="EDUCATION : ";
                        for($i=0;$i<count($edu);$i++)
                        {
                                $edukey=$edu[$i];
                                $show_crit.=" # ".get_farea_bms($edukey,"edu");
                                if($edustring && trim($edustring)!='')
                                {
                                        $edustring.=" or BannerEDU like '% $edukey %'";
                                }
                                else
                                {
                                        $edustring="(BannerEDU like '% $edukey %'";
                                }
                        }
                        if($edustring && trim($edustring)!='')
                        {
                                $edustring.=")";
                        }
                }
		if(in_array("OCCUPATION",$criteria))
                {
                        $occ=$selectedvalues["occ"];
                        $show_crit.="OCCUPATION : ";
                        for($i=0;$i<count($occ);$i++)
                        {
                                $occkey=$occ[$i];
                                $show_crit.=" # ".get_farea_bms($occkey,"occ");
                                if($occstring && trim($occstring)!='')
                                {
                                        $occstring.=" or BannerOCC like '% $occkey %'";
                                }
                                else
                                {
                                        $occstring="(BannerOCC like '% $occkey %'";
                                }
                        }
                        if($occstring && trim($occstring)!='')
                        {
                                $occstring.=")";
                        }
                }
		if(in_array("COMMUNITY",$criteria))
                {
                        $com=$selectedvalues["com"];
                        $show_crit.="COMMUNITY : ";
                        for($i=0;$i<count($com);$i++)
                        {
                                $comkey=$com[$i];
                                $show_crit.=" # ".get_farea_bms($comkey,"com");
                                if($comstring && trim($comstring)!='')
                                {
                                        $comstring.=" or BannerCOM like '% $comkey %'";
                                }
                                else
                                {
                                        $comstring="(BannerCOM like '% $comkey %'";
                                }
                        }
                        if($comstring && trim($comstring)!='')
                        {
                                $comstring.=")";
                        }
                }
		if(in_array("PROPCAT",$criteria))
                {
                        $propcat=$selectedvalues["propcat"];
                        $show_crit.="Property Category : ".$propcat;
                        $propcatstring="( `BannerPROPCAT`='$propcat' AND BannerPROPINR='' )";
                }
		if(in_array("PROPCITY",$criteria))
                {
                        $propcity=$selectedvalues["propcity"];
                        $show_crit.="PROPERTY CITY : ";
                        for($i=0;$i<count($propcity);$i++)
                        {
                                $propcitykey=$propcity[$i];
                                $show_crit.=" # ".get_farea_bms($propcitykey,"propcity");
                                if($propcitystring && trim($propcitystring)!='')
                                {
                                        $propcitystring.=" or BannerPROPCITY like '% $propcitykey %'";
                                }
                                else
                                {
                                        $propcitystring="(BannerPROPCITY like '% $propcitykey %'";
                                }
                        }
                        if($propcitystring && trim($propcitystring)!='')
                        {
                                $propcitystring.=")";
                        }
                }
		if(in_array("PROPINR",$criteria) && !(in_array("PROPCAT",$criteria)))
                {
                       // $propinr=$selectedvalues["propinr"];
			$propcat = $selectedvalues["propcat"];
			if ($propcat == 'Buy')
				$propinr=$selectedvalues["propinr"];
			else
				$propinr=$selectedvalues["proprentinr"];
                        $show_crit.="propinr : ";
                        for($i=0;$i<count($propinr);$i++)
                        {
                                $propinrkey=$propinr[$i];
				if ($propcat == 'Buy')
                                	$show_crit.=" # ".get_farea_bms($propinrkey,"propinr");
				else
					$show_crit.=" # ".get_farea_bms($propinrkey,"proprentinr");
                                if($propinrstring && trim($propinrstring)!='')
                                {
                                        $propinrstring.=" or BannerPROPINR like '% $propinrkey %' AND BannerPROPCAT ='$propcat'";
                                }
                                else
                                {
                                        $propinrstring="(BannerPROPINR like '% $propinrkey %' AND BannerPROPCAT ='$propcat' ";
                                }
                        }
                        if($propinrstring && trim($propinrstring)!='')
                        {
                                $propinrstring.=")";
                        }
                }
		
		if(in_array("PROPTYPE",$criteria))
                {
                        $proptype=$selectedvalues["proptype"];
                        $show_crit.="PROPTYPE : ";
                        for($i=0;$i<count($proptype);$i++)
                        {
                                $proptypekey=$proptype[$i];
                                $show_crit.=" # ".get_farea_bms($proptypekey,"proptype");
                                if($proptypestring && trim($proptypestring)!='')
                                {
                                        $proptypestring.=" or BannerPROPTYPE like '% $proptypekey %'";
                                }
                                else
                                {
                                        $proptypestring="(BannerPROPTYPE like '% $proptypekey %'";
                                }
                        }
                        if($proptypestring && trim($proptypestring)!='')
                        {
                                $proptypestring.=")";
                        }
                }

		if(in_array("GENDER",$criteria))
		{
			$gender=$selectedvalues["gender"];
			$show_crit.="Gender : ".$gender."<BR>";
			$genderstring="( `BannerGender`='$gender' )";
		}
	
		//added by lavesh rawat
		if(in_array("VARIABLE_DISCOUNT",$criteria))
		{
			unset($tempArr2);unset($tempArr);
			$tempArr=$selectedvalues["vd"];
			foreach($tempArr as $v)
				$tempArr2[]="( BannerJsVd LIKE '% $v %' )";
			$tempArrStr=implode(" OR ",$tempArr2);
			$vdstring="(".$tempArrStr.")";
		}
		if(in_array("PROFILE_STATUS",$criteria))
		{
			unset($tempArr2);unset($tempArr);
			$tempArr=$selectedvalues["profileStatus"];
			foreach($tempArr as $v)
				$tempArr2[]="( BannerJsProfileStatus LIKE '% $v %' )";
			$tempArrStr=implode(" OR ",$tempArr2);
			$profileStatusstring="(".$tempArrStr.")";
		}
		if(in_array("GMAIL_ID",$criteria))
		{
			unset($tempArr2);unset($tempArr);
			$tempArr=$selectedvalues["jsMailID"];
			foreach($tempArr as $v)
				$tempArr2[]="( BannerJsMailID LIKE '% $v %' )";
			$tempArrStr=implode(" OR ",$tempArr2);
			$jsMailIDstring="(".$tempArrStr.")";
		}
		if(in_array("EOI_STATUS",$criteria))
		{
		unset($tempArr2);unset($tempArr);
			$tempArr=$selectedvalues["jsEoiStatus"];
			foreach($tempArr as $v)
				$tempArr2[]="( BannerJsEoiStatus LIKE '% $v %' )";
			$tempArrStr=implode(" OR ",$tempArr2);
			$jsEoiStatusstring="(".$tempArrStr.")";
		}

		if(in_array("PROFILE_COMPLETE_STATE",$criteria))
		{
			unset($tempArr2);unset($tempArr);
			$tempArr=$selectedvalues["jsProfileCompletionState"];
			foreach($tempArr as $v)
				$tempArr2[]="( BannerJsProfileCompletionState LIKE '% $v %' )";
			$tempArrStr=implode(" OR ",$tempArr2);
			$bannerJsProfileCompletionStatestring="(".$tempArrStr.")";
		}
		//added by lavesh rawat

		if($keystring || $fareastring || $locstring || $indstring || $catstring || $expstring || $genderstring || $ctcstring || $agestring || $rexpstring || $rfareastring || $rindstring || $ipstring || $memstring || $mstatusstring || $edustring || $relstring || $occstring || $comstring || $proptypestring || $propcitystring || $propinrstring || $propcatstring || $jsEoiStatusstring || $jsMailIDstring || $profileStatusstring || $vdstring || $bannerJsProfileCompletionStatestring)//added by lavesh rawat
		{
			$criterias=" and (";
			if($locstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$locstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$locstring;
					$count=1;
				}
			}
			if($ipstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$ipstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$ipstring;
					$count=1;
				}
			}
			if($agestring)
			{
				if($count==1)
				{
					$criterias.=" or ".$agestring;
					$count=1;	
				}
				else 
				{
					$criterias.=$agestring;
					$count=1;
				}
			}
		        if($memstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$memstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$memstring;
                                        $count=1;
                                }
                        }
			if($relstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$relstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$relstring;
                                        $count=1;
                                }
                        }
			if($edustring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$edustring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$edustring;
                                        $count=1;
                                }
                        }
			if($occstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$occstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$occstring;
                                        $count=1;
                                }
                        }
			if($comstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$comstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$comstring;
                                        $count=1;
                                }
                        }
			if($propcitystring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$propcitystring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$propcitystring;
                                        $count=1;
                                }
                        }
			if($propinrstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$propinrstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$propinrstring;
                                        $count=1;
                                }
                        }
			if($proptypestring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$proptypestring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$proptypestring;
                                        $count=1;
                                }
                        }
			if($propcatstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$propcatstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$propcatstring;
                                        $count=1;
                                }
                        }
			if($mstatusstring)
                        {
                                if($count==1)
                                {
                                        $criterias.=" or ".$mstatusstring;
                                        $count=1;
                                }
                                else
                                {
                                        $criterias.=$mstatusstring;
                                        $count=1;
                                }
                        }
			if($ctcstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$ctcstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$ctcstring;
					$count=1;
				}
			}
			if($genderstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$genderstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$genderstring;
					$count=1;
				}
			}
			//added by lavesh rawat
			if($vdstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$vdstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$vdstring;
					$count=1;
				}
			}
			if($jsEoiStatusstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$jsEoiStatusstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$jsEoiStatusstring;
					$count=1;
				}
			}
			if($bannerJsProfileCompletionStatestring)
			{
				if($count==1)
				{
					$criterias.=" or ".$bannerJsProfileCompletionStatestring;
					$count=1;	
				}
				else 
				{
					$criterias.=$bannerJsProfileCompletionStatestring;
					$count=1;
				}
			}

			if($jsMailIDstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$jsMailIDstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$jsMailIDstring;
					$count=1;
				}
			}
			if($profileStatusstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$profileStatusstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$profileStatusstring;
					$count=1;
				}
			}
			//added by lavesh rawat

			$criterias.=")";
		}
		$sql="Select BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault,c.CampaignName,c.SITE from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and c.CampaignId=b.CampaignId $criterias Order By BannerPriority,BannerId";		
		$smarty->assign('show_crit',$show_crit);
		$smarty->assign('crit',$show_crit);
		return ($sql);
   }
 ?>
