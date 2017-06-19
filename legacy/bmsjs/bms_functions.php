<?php
/********************************************************bms_functions.php**************************************************/
	/*
	*	Created By		:	Abhinav Katiyar
	*	Last Modified By   	:	Abhinav Katiyar
	*	Description        	:	This file contains functions for 
						checking availability
	*	Includes/Libraries 	:	./includes/bms_connect.php
/***************************************************************************************************************************/
include_once("./includes/bms_connect.php");

 function checkIfAvail($critarr)
 {
	global $dbbms;

	$zoneid=$critarr["zoneid"];
	$banner=$critarr["bannerid"];
	$priority=$critarr["bannerpriority"];
	$startdt=$critarr["bannerstartdate"];
	$enddt=$critarr["bannerenddate"];
	$result=checkAvailability($zoneid,$priority,$critarr,$startdt,$enddt,$banner);
	if($result!="false") $result="true";

	if($result=="false") return 0;
	else return 1;	
	
 }


 function checkAvailability($zoneid,$priority,$critarr,$startdt,$enddt,$banner)
 {
		global $dbbms;
		$propcategory = $critarr["bannerpropcategory"];
  		$criterias=$critarr["criteriaarr"];//print_r($criterias);
		$sql="Select ZoneMaxBansInRot from bms2.ZONE where ZoneId='$zoneid'";
		$result=mysql_query($sql,$dbbms) or die(mysql_error($dbbms));
	
		$myrow=mysql_fetch_array($result);
		$maxbansinrot=$myrow["ZoneMaxBansInRot"];
		if($critarr["bannerdefault"]!="Y" && $critarr["bannerfixed"]!="Y")
		{	
			for($i=0;$i<count($criterias);$i++)
			{
				if($criterias[$i]=='Location')
	     			{
				        /*$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready') and BannerLocation Like '% $str %' and BannerId!='$banner'";
				        echo "<!--$sql-->";
		   			$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   			if($myrow=mysql_fetch_array($result))
		   			{
						if($myrow["cnt"]>=$maxbansinrot)
						{
							return("false");
						}
   	           			}*/

					$country	= $critarr["bannerloc_country"];
					$city		= $critarr["bannerloc_incity"]; 
					$uscity      	= $critarr["bannerloc_uscity"];
					//print_r($country);
					//print_r($city);
					//rint_r($uscity);
					
					$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked' or BannerStatus='ready')";
					
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
						}
					}
					$sql.=$ctrystr;
					$sql.= " and BannerId!='$banner'";
					echo "<!--$sql-->";
					$result=mysql_query($sql,$dbbms) or die(mysql_error($dbbms));
					if($myrow=mysql_fetch_array($result))
					{
						
						if($myrow["cnt"]>=$maxbansinrot)
						{
							return("false");
						}
					}

				}
				if($criterias[$i]=='IP')
				{
					$strarr=$critarr["bannerip"];
					//print_r($strarr);	
					for($j=0;$j<count($strarr);$j++)
					{				
						if($strarr[$j] && trim($strarr[$j])!="")
						{
							$locids=getLocids(trim($strarr[$j]));	
							if($locids && trim($locids)!='')
							{
								$locarr=explode(",",$locids);
								for($loc=0;$loc<count($locarr);$loc++)
								{
									$str=trim($locarr[$loc]);
									$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerIP Like '% $str %' and BannerId!='$banner'";
									echo "<!--$sql-->";
									$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
									if($myrow=mysql_fetch_array($result))
									{
										if($myrow["cnt"]>=$maxbansinrot)
										{
											return("false");
										}
									}
								}	
							}
						}
					}
				}
				if($criterias[$i]=='Age')
				{
					$minage=$critarr["banneragemin"];
					$maxage=$critarr["banneragemax"];
					$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and ( $maxage >= `BannerAgeMin` AND $minage <= `BannerAgeMax`) and BannerId!='$banner'";
					echo "<!--$sql-->";
					$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
					if($myrow=mysql_fetch_array($result))
					{
						//echo $myrow["cnt"]."  ";	
						if($myrow["cnt"]>=$maxbansinrot)
						{
							return("false");
						}
					}
				}
				
				if($criterias[$i]=='Ctc')
				{
					if(count($critarr["bannerctc"]) > 0)
						$ctc=implode(" , ",$critarr["bannerctc"]);
					else
						$ctc=$critarr["bannerctc"];
//					$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and ( $maxctc >= `BannerCTCMin` AND $minctc <= `BannerCTCMax`) and BannerId!='$banner'";
 					$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerCTC LIKE '% $ctc %' ) and BannerId!='$banner'";echo "<br>";
		   			$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
		   			if($myrow=mysql_fetch_array($result))
		   			{
						if($myrow["cnt"]>=$maxbansinrot)
						{
							return("false");
						}
   	           			}
				}
				if($criterias[$i]=='MEM')
                                {
                                        if(count($critarr["bannermem"]) > 0)
                                                $mem=implode(" , ",$critarr["bannermem"]);
                                        else
                                                $mem=$critarr["bannermem"];
                                        $sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerMEM LIKE '% $mem %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='MARITALSTATUS')
                                {
                                        if(count($critarr["bannermstatus"]) > 0)
                                                $mstatus=implode(" , ",$critarr["bannermstatus"]);
                                        else
                                                $mstatus=$critarr["bannermem"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerMARITALSTATUS LIKE '% $mstatus %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='REL')
                                {
                                        if(count($critarr["bannerrel"]) > 0)
                                                $rel=implode(" , ",$critarr["bannerrel"]);
                                        else
                                                $rel=$critarr["bannerrel"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerREL LIKE '% $rel %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='EDU')
                                {
                                        if(count($critarr["banneredu"]) > 0)
                                                $edu=implode(" , ",$critarr["banneredu"]);
                                        else
                                                $edu=$critarr["banneredu"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerEDU LIKE '% $edu %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='OCC')
                                {
                                        if(count($critarr["bannerocc"]) > 0)
                                                $occ=implode(" , ",$critarr["bannerocc"]);
                                        else
                                                $occ=$critarr["bannerocc"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerOCC LIKE '% $occ %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='COM')
                                {
                                        if(count($critarr["bannercom"]) > 0)
                                                $com=implode(" , ",$critarr["bannercom"]);
                                        else
                                                $com=$critarr["bannercom"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerCOM LIKE '% $com %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='PROPCITY')
                                {
                                        if(count($critarr["bannerpropcity"]) > 0)
                                                $propcity=implode(" , ",$critarr["bannerpropcity"]);
                                        else
                                                $propcity=$critarr["bannerpropcity"]; 
					$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerPROPCITY LIKE '% $propcity %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='PROPCAT')
                                {
					$propcat = $critarr["bannerpropcategory"];
                                        $sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerPROPCAT ='$propcat' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='PROPTYPE')
                                {
                                        if(count($critarr["bannerproptype"]) > 0)
                                                $proptype=implode(" , ",$critarr["bannerproptype"]);
                                        else
                                                $proptype=$critarr["bannerproptype"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerPROPTYPE LIKE '% $proptype %' ) and BannerId!='$banner'";echo "<br>";
                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
				if($criterias[$i]=='PROPINR')
                                {
                                        if(count($critarr["bannerpropinr"]) > 0)
                                                $propinr=implode(" , ",$critarr["bannerpropinr"]);
                                        else
                                                $propinr=$critarr["bannerpropinr"];
                                 	$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and (BannerPROPINR LIKE '% $propinr %' and BannerPROPCAT='$propcategory') and BannerId!='$banner'";echo "<br>";

                                        $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                        if($myrow=mysql_fetch_array($result))
                                        {
                                                if($myrow["cnt"]>=$maxbansinrot)
                                                {
                                                        return("false");
                                                }
                                        }
                                }
			}
		}
		else 
		{
			if ($critarr["bannerdefault"]== "Y")
			{
				$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerDefault='Y' and BannerId!='$banner'";
				echo "<!--$sql-->";
				$result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
				if($myrow=mysql_fetch_array($result))
				{
					if($myrow["cnt"]>=$maxbansinrot)
					//if ($myrow['cnt'] >= 1)
					{
						return("false");
					}
				}
			}
			elseif ($critarr["bannerfixed"]== "Y")
			{
				$sql="Select count(*) as cnt from bms2.BANNER where ZoneId='$zoneid' and BannerPriority='$priority' and ('$enddt' >= BannerStartDate AND '$startdt' <= BannerEndDate) and (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerFixed = 'Y' and BannerId!='$banner'";
                                echo "<!--$sql-->";
                                $result=mysql_query( $sql,$dbbms) or die(mysql_error($dbbms));
                                if($myrow=mysql_fetch_array($result))
                                {
                                        //if($myrow["cnt"]>=$maxbansinrot)
					if ($myrow['cnt'] >= 1)
                                        {
                                                return("false");
                                        }
                                }
			}
		}
 }
?>
