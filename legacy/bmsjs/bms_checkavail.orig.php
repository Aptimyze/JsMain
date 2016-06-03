<?php

/*********************************************************bms_checkavail.php***********************************************/
/**		
	*	 Created By            : Abhinav Katiyar		
    	*	 Last Modified By      : Abhinav Katiyar
    	*	 Description           : This file is for checking availability and showing banners on selected criterias
*************************************************************************************************************************/

   function checkavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt)
   {
	global $dbbms,$smarty,$_TPLPATH,$id,$dowhat;

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
	else
	{
		$show_crit="Default";
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
   	
   		global $dbbms,$smarty;	
		
		for($i=1;$i<=$maxbans;$i++)
		{
			$pri[$i-1]=$i;
			$resarr[$i]["count"]=0;
			$resarr[$i]["avail"]='Y';
		}
   		$smarty->assign('pri',$pri);
		
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
				$show_crit.="<B>CITIES</B> : $indiancitystr<br>";
			if($uscitystring)
				$show_crit.="<B>CITIES</B> : $uscitystring<br>";
			unset($citystr);
			unset($uscitystr);
			unset($citystr);
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
									$incitystr.= " and BannerInCity like '%$city[$j]%'";
								}
								else
									$incitystr= " and BannerInCity like '%$city[$j]%'";
							}
						}
					
					if($incitystr)
						$ctrystr.= $incitystr;
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
					}
					//echo $countrystring.=" , ".get_farea_bms(trim($country[$i]),"country");
					//echo "END";
				}
				else
				{	$country[$i] = trim($country[$i]);
					$ctry = trim($country[$i]);
					$ctrystr = " and BannerCountry like '%$country[$i]%'";
					if($country[$i] == 51)
                                        {       
                                                for($j=0;$j<count($city);$j++)
                                                {
								$city[$j] = trim($city[$j]);
                                                        	if ($incitystr)
								{
									$incitystr.= " and BannerInCity like '%$city[$j]%'";
                                                       		}
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
					}
					//$countrystring=get_farea_bms(trim($country[$i]),"country");

				}
			}
			$sql.=$ctrystr;	
			echo $sql.= " Group By BannerPriority";
			$sql123=$sql;
			$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:checkoncriterias:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
			$row=mysql_fetch_array($result);
			print_r($row);
			//if($row = mysql_fetch_array($result))
			{	echo "here";
				do//for ($i =0;$i<count($row); $i++) 
				{	echo "here";
					echo "PRi".$priority=$row["BannerPriority"];
					echo "Count".$count=$row["cnt"];
					if($count>$resarr[$priority]["count"])
					{
						$resarr[$priority]["count"]=$count;
						
					}
					if($resarr[$priority]["count"]>=$maxbansinrot)
					{
						$resarr[$priority]["avail"]='N';
					}
				}while($row=mysql_fetch_array($result));
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


		$smarty->assign('show_crit',$show_crit);
		return ($resarr);
		
   }

   function viewavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt,$showall='N')
   {
		global $dbbms,$smarty,$_TPLPATH,$id,$dowhat;
		$sql="Select r.RegName,z.ZoneName,z.ZoneMaxBans,z.ZoneMaxBansInRot from bms2.ZONE z,bms2.REGION r where ZoneId='$zoneid' and r.RegId=z.RegId";
		$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:1:Could not get Zone listings. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");
		if($myrow=mysql_fetch_array($result))
		{
			$zonename=$myrow["ZoneName"];
			$region=$myrow["RegName"];	
			$maxbans=$myrow["ZoneMaxBans"];
			$maxbansinrot=$myrow["ZoneMaxBansInRot"];	
		}
		if($showall && $showall!='N')
		{
			   $show_crit.="All Banners";
			   $sql="Select c.CampaignName,BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and c.CampaignId=b.CampaignId Order By BannerPriority,BannerId";
			   $res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
		}
		else
		{
			if(is_array($criteria))
			{
				$sql=viewoncriterias($zoneid,$maxbans,$maxbansinrot,$criteria,$selectedvalues,$startdt,$enddt);	
				$res=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavail.php:viewavail:2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");		
			}
			else
			{
		   		$show_crit.="Default";
		   		$sql="Select c.CampaignName,BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='Y' and c.CampaignId=b.CampaignId Order By BannerPriority,BannerId";
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
				//echo "@@@@@@@@@22"."<br>".$criteria."<br>";
				$resarr[$banner]["criteria"]=$criteria;
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
					}
                                                                                                                            
                                }
                        }
                        $locstring.= $ctrystr;
			
			
			/*$location=$selectedvalues["location"];
			$location=parseKeywords($location);
			$show_crit.="Location : ".$location."<BR>";
			$locarr=explode(",",$location);
			
			for($i=0;$i<count($locarr);$i++)
			{
				$loc=$locarr[$i];
				if($locstring && trim($locstring)!='')
				{
					$locstring.=" or BannerLocation like '% $loc %'";
				}
				else 
				{
					$locstring="(BannerLocation like '% $loc %'";
				}
				if($locstring && trim($locstring)!='')
				{
					$locstring.=")";
				}
			}*/
		}
		if(in_array("Farea",$criteria))
		{
			$farea=$selectedvalues["farea"];
			
			$show_crit.="FAREA : ";
			for($i=0;$i<count($farea);$i++)
			{
				$fkey=$farea[$i];
				$show_crit.=" # ".get_farea_bms($fkey,"farea");
				if($fareastring && trim($fareastring)!='')
				{
					$fareastring.=" or BannerFarea like '% $fkey %'";
				}
				else 
				{
					$fareastring="(BannerFarea like '% $fkey %'";
				}
			}
			if($fareastring && trim($fareastring)!='')
			{
				$fareastring.=")";
			}
			$show_crit.="<BR>";		
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
		if(in_array("FareaResman",$criteria))
		{
			$farea=$selectedvalues["res_farea"];
			$show_crit.="Resman Farea : ";
			for($i=0;$i<count($farea);$i++)
			{
				$fkey=$farea[$i];
				$show_crit.=" # ".get_farea_bms($fkey,"farea");
				if($rfareastring && trim($rfareastring)!='')
				{
					$rfareastring.=" or BannerResmanFarea like '% $fkey %'";
				}
				else 
				{
					$rfareastring="(BannerResmanFarea like '% $fkey %'";
				}
			}
			if($rfareastring && trim($rfareastring)!='')
			{
				$rfareastring.=")";
			}
			$show_crit.="<BR>";
		}
		if(in_array("Industry",$criteria))
		{
			$industry=$selectedvalues["industry"];
			$show_crit.="Industry Type : ";
			
			for($i=0;$i<count($industry);$i++)
			{
				$ind=$industry[$i];
				$show_crit.=" # ".get_farea_bms($ind,"indtype");
				if($indstring && trim($indstring)!='')
				{
					$indstring.=" or BannerIndtype like '% $ind %'";
				}
				else 
				{
					$indstring="(BannerIndtype like '% $ind %'";
				}
			}
			if($indstring && trim($indstring)!='')
			{
				$indstring.=")";
			}
			$show_crit.="<BR>";
		}
		if(in_array("IndustryResman",$criteria))
		{
			$industry=$selectedvalues["res_industry"];
			//$fkey=implode(",",$farea);
			$show_crit.="Resman Industry Type : ";
			
			for($i=0;$i<count($industry);$i++)
			{
				$ind=$industry[$i];
				$show_crit.=" # ".get_farea_bms($ind,"indtype");
				if($rindstring && trim($rindstring)!='')
				{
					$rindstring.=" or BannerResmanIndustry like '% $ind %'";
				}
				else
				{
					$rindstring="(BannerResmanIndustry like '% $ind %'";
				}
			}
			if($rindstring && trim($rindstring)!='')
			{
				$rindstring.=")";
			}
			$show_crit.="<BR>";
		}
		if(in_array("Categories",$criteria))
		{
			$categories=$selectedvalues["categories"];
			//$fkey=implode(",",$farea);
			$show_crit.="Categories : ";
			
			for($i=0;$i<count($categories);$i++)
			{
				$cat=$categories[$i];
				$show_crit.=" # ".get_farea_bms($cat,"category");
				if($catstring && trim($catstring)!='')
				{
					$catstring.=" or BannerCategories like '% $cat %'";
				}
				else 
				{
					$catstring="(BannerCategories like '% $cat %'";
				}
			}
			if($catstring && trim($catstring)!='')
			{
				$catstring.=")";
			}
			$show_crit.="<BR>";
		}
		if(in_array("Exp",$criteria))
		{
			$expmin=$selectedvalues["expmin"];
			$expmax=$selectedvalues["expmax"];
			$show_crit.="Experience : ".$expmin." to ".$expmax."<BR>";
			$expstring="( '$expmax' >= `BannerExpMin` AND '$expmin' <= `BannerExpMax`)";
		}
		if(in_array("ExpResman",$criteria))
		{
			$expmin=$selectedvalues["resexpmin"];
			$expmax=$selectedvalues["resexpmax"];
			$show_crit.="Experience Resman : ".$expmin." to ".$expmax."<BR>";
			$rexpstring="( '$expmax' >= `BannerResmanExpMin` AND '$expmin' <= `BannerResmanExpMax`)";
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
		if(in_array("GENDER",$criteria))
		{
			$gender=$selectedvalues["gender"];
			$show_crit.="Gender : ".$gender."<BR>";
			$genderstring="( `BannerGender`='$gender' )";
		}
		if($keystring || $fareastring || $locstring || $indstring || $catstring || $expstring || $genderstring || $ctcstring || $agestring || $rexpstring || $rfareastring || $rindstring || $ipstring)
		{
			$criterias=" and (";
			if($keystring)
			{
				$criterias.=$keystring;
				$count=1;	
			}
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

			if($fareastring)
			{
				if($count==1)
				{
					$criterias.=" or ".$fareastring;
					$count=1;	
				}
				else 
				{
					$criterias.=$fareastring;
					$count=1;
				}
			}
			if($indstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$indstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$indstring;
					$count=1;
				}
			}
			if($catstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$catstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$catstring;
					$count=1;
				}
			}
			if($expstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$expstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$expstring;
					$count=1;
				}
			}
			if($rexpstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$rexpstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$rexpstring;
					$count=1;
				}
			}
			if($rfareastring)
			{
				if($count==1)
				{
					$criterias.=" or ".$rfareastring;
					$count=1;	
				}
				else 
				{
					$criterias.=$rfareastring;
					$count=1;
				}
			}
			if($rindstring)
			{
				if($count==1)
				{
					$criterias.=" or ".$rindstring;
					$count=1;	
				}
				else 
				{
					$criterias.=$rindstring;
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
			$criterias.=")";
		}
		echo "<br>".$sql="Select BannerId,BannerPriority,BannerWeightage,BannerGif,BannerClass,BannerStartDate,BannerEndDate,BannerFreeOrPaid,BannerStatus,BannerDefault,c.CampaignName from bms2.BANNER b,bms2.CAMPAIGN c where ZoneId='$zoneid' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerDefault='N' and c.CampaignId=b.CampaignId $criterias Order By BannerPriority,BannerId";			echo "<br>"."<br>";
		$smarty->assign('show_crit',$show_crit);
		return ($sql);
   }
 ?>
