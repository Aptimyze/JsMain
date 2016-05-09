<?php

/*****
	Modified on Jun 09, 2005 by shiv. City dropdown added.
*****/

/*****
	Modified on Jun 28, 2005 by shiv. Community dropdown added.
*****/
//die("This module is temporarily down.");
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data))
{
	if($CMDSubmit)
	{
		$static_sourcearr =  array('google','overture','Rediff_tgt','Yahoo_Tgt','Google_NRI','MSN_group','rediff_pilot_july','jeevansathi','yahoo_US_06','yahoo_cpm06','Rediff06','rediff_new_06','yahoo','NONE','rediff','yahoosrch','Sify','mediaturf','yahoo_amj_07','rediff_march_07','New Google','123greetings','Komli','yahoo_jas_07','rediff_july_07','naukri','rediff_affiliate','yahoo_Del_Mum','rediff_jas_tgt','tyroo','yahoo_ond_07','sify_ond_07','aol_ond_07','rediff_ond_07','Yahoo_branding_ond','Tyroo_NRI_JFM08','Tyroo_India_JFM08','Komli_JFM_08','Integrid_CPA_08','rediff_jfm_08','Google NRI US','Rediff_us_fm','Yahoo_nri','Sulekha_us_fm','yahoo_amj_08_cpm','komli_amj_08_cpa','sify_amj_08_cpa','DGM_amj_08_cpa','Vdopia_april08','rediff_amj_08','rediff_branding_june08','rediff_branding_JAS08','rediff_jas_2008_cpc','mediaturf_aug_2008','yahoosearch_2008','ozonemedia_aug_2008','yahooCPA@90_Sep08','Yahoo_US_CPA@200_Sep08','yahooNRI_amj_08_cpm','YAHOO_BrandingJune08','Indiatimes_BrandingJune08','Sify_BrandingJune08','MSN_BrandingJune08');
                if (!$source && !$city && !$community)
                {
                        $filename="/usr/local/indicators/paynext_mis.htm";
                        //$filename= "/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/paynext_mis.htm";

                        $fp = @fopen($filename,"r");
                        if (!$fp)
                        {
                                echo("no record found");
                                exit;
                        }
                        echo $data=fread($fp,filesize($filename));
                }
                elseif (in_array($source,$static_sourcearr))
                {
                        $filename="/usr/local/indicators/".$source."_paynext_mis.htm";
			//$filename="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/".$source."_paynext_mis.htm";
                        $fp = @fopen($filename,"r");
                        if (!$fp)
                        {
                                echo("no record found");
                                exit;
                        }
                        echo "<center>SOURCE : ".$source."<br>";
                        echo $data=fread($fp,filesize($filename));
                }
		elseif($community)
		{
                        $filename="/usr/local/indicators/comm_".$community."_paynext_mis.htm";
			//$filename="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/".$source."_paynext_mis.htm";
                        $fp = @fopen($filename,"r");
                        if (!$fp)
                        {
                                echo("no record found");
                                exit;
                        }
                        echo "<center>Community : ".$community."<br>";
                        echo $data=fread($fp,filesize($filename));
		}
                else
		{
die;
			$yearp1=$year+1;
			$curdate=date("Y-m-d");
			list($cur_year,$cur_mm,$cur_day) = explode("-",$curdate);

			if($cur_mm<10)
				$cur_mm = substr($cur_mm,1);

			if($cur_mm>=3)
				$cur_mm-=3;

			$temp_arr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

			if($year=="2004")
			{
				$jmmarr=array('Before Oct 2004','Oct','Nov','Dec','Jan','Feb','Mar');
				$pmmarr=array('Oct','Nov','Dec','Jan','Feb','Mar');

				$start_dt=$year."-04-01 00:00:00";
				$end_dt=$yearp1."-03-31 23:59:59";
			}
			else
			{
				$jmmarr=array('Before Oct 2004','Oct','Nov','Dec','Jan','Feb','Mar');
				$pmmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

				$yr_diff=$year-2004+1;

				while($yr_diff>0)
				{
					$yr_diff--;
					if($yr_diff>=1)
					{
						for ($i=0;$i<12;$i++)
							$jmmarr[]=$temp_arr[$i];
					}
					else
					{
						for ($i=0;$i<$cur_mm;$i++)
							$jmmarr[]=$temp_arr[$i];
					}
				}

				$start_dt=$year."-04-01 00:00:00";
				$end_dt=$yearp1."-03-31 23:59:59";
			}
			unset($temp_arr);

			if($city)
			{
				$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$city'";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$cityvalarr[]=$row['VALUE'];
				}

				$citystr="'".implode("','",$cityvalarr)."'";
			}

			if($source)
			{
				$sql="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$source'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$srcidarr[]=$row['SourceID'];
				}

				$srcstr="'".implode("','",$srcidarr)."'";
			}

			//$sql="SELECT COUNT(*) as cnt,MONTH(ENTRY_DT) as mm, YEAR(ENTRY_DT) as yy FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE<>'Y' ";

			//if($year=="2004")
				$sql="SELECT COUNT(*) as cnt,LEFT(ENTRY_DT,7) as entry_dt FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE IN ('','N') AND ENTRY_DT<='$end_dt'";
			//else
			//	$sql="SELECT COUNT(*) as cnt,LEFT(ENTRY_DT,7) as entry_dt FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE IN ('','N') AND ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' ";
			if($source)
			{
				$sql.=" AND SOURCE IN ($srcstr) ";
			}
			if($city)
			{
				$sql.=" AND CITY_RES IN ($citystr) ";
			}
			if($community)
			{
				$sql.=" AND MTONGUE='$community' ";
			}
			//$sql.=" GROUP BY yy,mm";
			$sql.=" GROUP BY entry_dt";

			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					list($yy,$mm)=explode("-",$row['entry_dt']);
					$cnt=$row['cnt'];

					if($yy<2004)
						$mm=0;
					elseif($yy==2004)
					{
						if($mm<10)
							$mm=0;
						else
							$mm-=9;
					}
					else
					{
						$num=$yy-2004-1;
						$mm+=3;
						$mm+=($num*12);
					}
					$regtot[$mm]+=$cnt;
					$reg_mem_tot+=$cnt;
				}while($row=mysql_fetch_array($res));
			}

			//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,month(j.ENTRY_DT) as jmm,year(j.ENTRY_DT) as jyy,month(p.ENTRY_DT) as pmm,year(p.ENTRY_DT) as pyy FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE INCOMPLETE<>'Y' AND p.ENTRY_DT BETWEEN '2004-10-01 00:00:00' AND '2006-03-31 23:59:59' AND p.STATUS='DONE' ";

			$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,LEFT(j.ENTRY_DT,7) as reg_dt,LEFT(p.ENTRY_DT,7) as paid_dt FROM billing.PURCHASES p ,newjs.JPROFILE j WHERE j.PROFILEID=p.PROFILEID AND p.STATUS='DONE' ";
			if($year=="2004")
				$sql.=" AND p.ENTRY_DT <= '$end_dt' ";
			else
				$sql.=" AND p.ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' ";

			if($source)
			{
				$sql.=" AND j.SOURCE IN ($srcstr) ";
			}
			if($city)
			{
				$sql.=" AND j.CITY_RES IN ($citystr) ";
			}
			if($community)
			{
				$sql.=" AND j.MTONGUE='$community' ";
			}

			$sql.=" GROUP BY reg_dt,paid_dt ";
			if($year=="2004")
			{
				$sql.=" UNION SELECT COUNT(DISTINCT p.PROFILEID) as cnt,LEFT(j.ENTRY_DT,7) as reg_dt,LEFT(p.ENTRY_DT,7) as paid_dt FROM billing.oct_nov_record p, newjs.JPROFILE j WHERE j.PROFILEID=p.PROFILEID AND p.ENTRY_DT BETWEEN '2004-10-01 00:00:00' AND '2004-12-31 23:59:59' AND p.STATUS='DONE'";

				if($source)
				{
					$sql.=" AND j.SOURCE IN ($srcstr) ";
				}
				if($city)
				{
					$sql.=" AND j.CITY_RES IN ($citystr) ";
				}
				if($community)
				{
					$sql.=" AND j.MTONGUE='$community' ";
				}

				//$sql.=" GROUP BY jyy,jmm,pyy,pmm";
				$sql.=" GROUP BY reg_dt,paid_dt";
			}

			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					list($pyy,$pmm)=explode("-",$row['paid_dt']);
					list($jyy,$jmm)=explode("-",$row['reg_dt']);
					$cnt=$row['cnt'];

					if($jyy<2004)
						$jmm=0;
					elseif($jyy==2004)
					{
						if($jmm<10)
							$jmm=0;
						else
							$jmm-=9;
					}
					else
					{
						$num=$jyy-2004-1;
						$jmm+=3;
						$jmm+=($num*12);
					}

					if($pyy==2004)
						$pmm-=10;
					else
					{
						if($year=="2004")
						{
							$num=$pyy-$year-1;
							$pmm=$pmm+($num*12)+2;
						}
						else
						{
							if($pmm<=3)
								$pmm+=8;
							else
								$pmm-=4;
						}
					}
					$tot[$jmm][$pmm]+=$cnt;
					$ptot[$pmm]+=$cnt;
					$jtot[$jmm]+=$cnt;
					$totall+=$cnt;
				}while($row=mysql_fetch_array($res));
			}

			for($i=0;$i<count($jmmarr);$i++)
			{
				for($j=0;$j<count($pmmarr);$j++)
				{
					if($regtot[$i])
					{
						$percent[$i][$j]=$tot[$i][$j]/$regtot[$i] * 100;
						$percent[$i][$j]=round($percent[$i][$j],1);
					}
				}
				if($regtot[$i])
				{
					$jtotpercent[$i]=$jtot[$i]/$regtot[$i] * 100;
					$jtotpercent[$i]=round($jtotpercent[$i],1);
				}
			}

			$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$srcarr[]=$row['GROUPNAME'];
			}

			$i=0;
			$sql="SELECT VALUE,LABEL FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>'' AND NEAR_BRANCH=VALUE";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$cityarr[$i]['VAL']=$row['VALUE'];
				$cityarr[$i]['LABEL']=$row['LABEL'];
				$i++;
			}

			$i=0;
			$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$communityarr[$i]['VAL']=$row['VALUE'];
				$communityarr[$i]['LABEL']=$row['SMALL_LABEL'];
				$i++;
			}

			for($i=2004;$i<=date("Y");$i++)
			{
				$yrarr[$i-2004]=$i;
			}

			$smarty->assign("yrarr",$yrarr);
			$smarty->assign("year",$year);
			$smarty->assign("jmmarr",$jmmarr);
			$smarty->assign("pmmarr",$pmmarr);
			$smarty->assign("regearlier",$regearlier);
			$smarty->assign("tot",$tot);
			$smarty->assign("totall",$totall);
			$smarty->assign("reg",$reg);
			$smarty->assign("regtot",$regtot);
			$smarty->assign("reg_mem_tot",$reg_mem_tot);
			$smarty->assign("ptot",$ptot);
			$smarty->assign("jtot",$jtot);
			$smarty->assign("percent",$percent);
			$smarty->assign("jtotpercent",$jtotpercent);
			$smarty->assign("source",$source);
			$smarty->assign("city",$city);
			$smarty->assign("community",$community);
			$smarty->assign("srcarr",$srcarr);
			$smarty->assign("cityarr",$cityarr);
			$smarty->assign("communityarr",$communityarr);
			$smarty->assign("checksum",$checksum);
			$smarty->display("paynext.htm");
		}
	}
	else
	{
		for($i=2004;$i<=date("Y");$i++)
		{
			$yrarr[$i-2004]=$i;
		}

		$srcarr =  array('google','overture','Rediff_tgt','Yahoo_Tgt','Google_NRI','MSN_group','rediff_pilot_july','jeevansathi','yahoo_US_06','yahoo_cpm06','Rediff06','rediff_new_06','yahoo','NONE','rediff','yahoosrch','Sify','mediaturf','yahoo_amj_07','rediff_march_07','New Google','123greetings','Komli','yahoo_jas_07','rediff_july_07','naukri','rediff_affiliate','yahoo_Del_Mum','rediff_jas_tgt','tyroo','yahoo_ond_07','sify_ond_07','aol_ond_07','rediff_ond_07','Yahoo_branding_ond','Tyroo_NRI_JFM08','Tyroo_India_JFM08','Komli_JFM_08','Integrid_CPA_08','rediff_jfm_08','Google NRI US','Rediff_us_fm','Yahoo_nri','Sulekha_us_fm','yahoo_amj_08_cpm','komli_amj_08_cpa','sify_amj_08_cpa','DGM_amj_08_cpa','Vdopia_april08','rediff_amj_08','rediff_branding_june08','rediff_branding_JAS08','rediff_jas_2008_cpc','mediaturf_aug_2008','yahoosearch_2008','ozonemedia_aug_2008','yahooCPA@90_Sep08','Yahoo_US_CPA@200_Sep08','yahooNRI_amj_08_cpm','YAHOO_BrandingJune08','Indiatimes_BrandingJune08','Sify_BrandingJune08','MSN_BrandingJune08');

		$i=0;

		$sql="SELECT VALUE,LABEL FROM newjs.MTONGUE WHERE 1";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$commarr[$i]["VALUE"]=$row['VALUE'];
			$commarr[$i]["LABEL"]=$row['LABEL'];
			$i++;
		}

		$smarty->assign("yrarr",$yrarr);
		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("commarr",$commarr);
		$smarty->assign("checksum",$checksum);
		$smarty->display("paynext.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
