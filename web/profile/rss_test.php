<?php
/************************************************************************************************************************
*       FILE NAME               : rss_test.php
*       DESCRIPTION             : To generate new RSS feeds.
*       CREATION DATE           : 1 DEC,2005
*       CREATED BY              : Ketaki Aggarwal

************************************************************************************************************************/
ini_set("max_execution_time","0");
include "connect.inc";
include "search.inc";	

$db=connect_db();

$query="SELECT * FROM RSS";
$res=mysql_query_decide($query) or die(mysql_error_js());
while($myrow1=mysql_fetch_array($res))
{
	unset($Gender,$Mtongue,$Caste,$lage,$hage,$Photos,$hp_mstatus,$lheight,$hheight,$btype,$complexion,$diet,$smoke,$drink,$handicapped,$occ,$Country_Res,$City_Res,$edu_level,$searchonline, $FRESHNESS,$income,$subscription,$head,$feedname,$Religion,$Country_Birth,$newsearch,$description,$countrysql,$country_india,$country_usa,$countrycond,$city_usa,$city_india,$Country_Res1,$Country_Birth,$City_Res,$Country_Res);
	
	$Gender=$myrow1['GENDER'];
 	$Mtongue=$myrow1['MTONGUE'];	
	$Caste=$myrow1['CASTE'];
	$lage=$myrow1['LAGE'];
	$hage=$myrow1['HAGE'];
	$Photos=$myrow1['WITHPHOTO'];
	$hp_mstatus=$myrow1['MSTATUS'];
	$lheight=$myrow1['LHEIGHT'];
	$hheight=$myrow1['HHEIGHT'];	
	$btype=$myrow1['BTYPE'];
	$complexion=$myrow1['COMPLEXION'];
	$diet=$myrow1['DIET'];
	$smoke=$myrow1['SMOKE'];
	$drink=$myrow1['DRINK'];
	$handicapped=$myrow1['HANDICAPPED'];
	$occ=$myrow1['OCCUPATION'];
	$Country_Res=$myrow1['COUNTRY_RES'];
	$City_Res=$myrow1['CITY_RES'];
	$edu_level=$myrow1['EDU_LEVEL_NEW'];

	$FRESHNESS=$myrow1['FRESHNESS'];
	$income=$myrow1['INCOME'];
	$subscription=$myrow1['SUBSCRIPTION'];
	$head=$myrow1['TITLE'];
	$feedname=$myrow1['FEED_NAME'];
	$Religion=$myrow1['RELIGION'];
	$Country_Birth=$myrow1['COUNTRY_BIRTH'];
	$newsearch=$myrow1['NEWSEARCH'];	
	$description=$myrow1['DESCRIPTION'];
	if($Caste!='') 
	{
		$searchCaste="'" .$Caste. "'";
	}
	elseif($Religion!="")
	{
		$sql_cache="select SQL_CACHE VALUE from CASTE where PARENT='$Religion' and ISALL='Y'";
		$res_cache=mysql_query_decide($sql_cache);
		
		$res_row=mysql_fetch_array($res_cache);
		$Caste[0]=$res_row["VALUE"];
		
		$seCaste=get_all_caste($Caste);
		if(is_array($seCaste))
			$searchCaste="'" . implode($seCaste,"','") . "'";
	}
	
	if($Mtongue!="")
	{
		$Mtongue1 = "'".$Mtongue."'";
	}
			
	if($Country_Res!="")
	{
			
		if($Country_Res=="51")
			$country_india=1;
		elseif($Country_Res=="128")
			$country_usa=1;
		else 	
	        	$Country_Res1 = "'".$Country_Res."'";
	}
	if($Country_Birth)
	{
		$sql1[]=" COUNTRY_BIRTH='$Country_Birth'";
	}
	if(trim($City_Res)!="")
	{
		if(is_numeric($City_Res))
		{
			$country_usa=1;
			$city_usa=$City_Res;
		}
		else 
		{
			$country_india=1;
			if(strlen($City_Res)==2)
        		{
        			$citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res%'";
        			$cityresult=mysql_query_decide($citysql);
        			
        			while($cityrow=mysql_fetch_array($cityresult))
        			{
        				$city_india=$cityrow["VALUE"];
        			}
        			
        			mysql_free_result($cityresult);
        		}
        		else 
				$city_india=$City_Res;
		}
	}
	if($btype)
		$sql1[]=" BTYPE='$btype'";
	if($complexion)
		$sql1[]=" COMPLEXION='$complexion'";
	if($diet)
		$sql1[]=" DIET='$diet'";
	
	$FIELDS="HEIGHT,CASTE,OCCUPATION,CITY_RES,YOURINFO,FATHER_INFO,SIBLING_INFO,SPOUSE,AGE,GENDER,PROFILEID,SCREENING";
	if($Gender=='F')
		$sql_f ="SELECT PROFILEID FROM SEARCH_FEMALE WHERE ";
	if($Gender=='M')
		$sql_m ="SELECT PROFILEID FROM SEARCH_MALE WHERE ";
	if($Gender=='')
	{
		$sql_f ="SELECT PROFILEID FROM SEARCH_FEMALE WHERE ";
		$sql_m ="SELECT PROFILEID FROM SEARCH_MALE WHERE ";
	}
	
	if($searchCaste!="")
	{
		if(strstr($searchCaste,","))
			$sql1[]=" CASTE IN ($searchCaste) ";
		else 
			$sql1[]="CASTE=$searchCaste ";
	}
	unset($searchCaste);			
	if($Mtongue1 != "")
	{
		if(strstr($Mtongue1,","))
	    	$sql1[] = "(MTONGUE IN ($Mtongue1)) ";
	    else 
	    	$sql1[] = "MTONGUE=$Mtongue1 ";
	}
	unset($Mtongue1);    
	if($lage!="" && $hage !="" && $lage!="0" && $hage!="0")
		$sql1[] = "(AGE BETWEEN $lage AND $hage)";
	unset($lage,$hage);	
	if($Photos)
		$sql1[] = "(HAVEPHOTO='Y')";
	unset($Photos);	
	if($country_india==1)
	{
		if($city_india)
			$countrysql[]="(COUNTRY_RES = '51' and CITY_RES in ('" . $city_india. "'))";
		elseif($Country_Res1=="")
			$Country_Res1="51";
		else 
			$Country_Res1.=",'51'";
	}
	
	if($country_usa==1)
	{
		if($city_usa)
			$countrysql[]="(COUNTRY_RES = '128' and CITY_RES in ('" .$city_usa. "'))";
		elseif($Country_Res1=="")
			$Country_Res1="128";
		else 
			$Country_Res1.=",'128'";
	}
	
	if($Country_Res1!="")
	{
		if(strstr($Country_Res1,","))
			$countrysql[]="(COUNTRY_RES in ($Country_Res1))";
		else 
			$countrysql[]="(COUNTRY_RES=$Country_Res1)";
	}
	
	if(is_array($countrysql))
	{
		$countrycond=implode($countrysql," or ");
		$countrycond="(" . $countrycond . ")";
	}

	if(trim($countrycond)!="")
		$sql1[]=$countrycond;
	unset($countrycond,$countrysql);	
	if($hp_mstatus)
	{
		if($hp_mstatus=="E")
			$sql1[]="MSTATUS in ('D','W','S')";
		else
			$sql1[]="MSTATUS='$hp_mstatus'";
	}
	unset($hp_mstatus);
	if($lheight)
		$sql1[]="HEIGHT between '$lheight' and '$hheight'";
	if($income)
		$sql1[]=" INCOME in($income) ";
	unset($lheight,$hheight);	
	if($newsearch=='nri')
        {
                $sql1[]="COUNTRY_BIRTH=51 AND COUNTRY_RES!=51 ";
	}
	if(is_array($sql1))
		$sql_clause=implode(" AND ",$sql1);
	unset($sql1);	
	if(trim($sql_clause==""))
		$sql_clause="1";
	if($sql_m)	
		$sql_m .= $sql_clause;
	if($sql_f)
		$sql_f.= $sql_clause;

	if($newsearch=='cosmo')
	{
		if($Gender=='F')
		{
			$search_gender='M';
			$sql_cosmo_m="SELECT  J.PROFILEID FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN SEARCH_FEMALE AS S ON J.PROFILEID=S.PROFILEID  WHERE P.CASTE IS NULL AND J.GENDER='$search_gender'";
		}	
		if($Gender=='M')
		{
			 $search_gender='F';
			$sql_cosmo_f="SELECT  J.PROFILEID FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN SEARCH_MALE AS S ON J.PROFILEID=S.PROFILEID  WHERE P.CASTE IS NULL AND J.GENDER='$search_gender'";
		}
		if($Gender=='')
		{
			$sql_cosmo_m="SELECT J.PROFILEID FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN SEARCH_MALE AS S ON J.PROFILEID=S.PROFILEID  WHERE P.CASTE IS NULL AND J.GENDER='F'";
			$sql_cosmo_f="SELECT J.PROFILEID FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN SEARCH_FEMALE AS S ON J.PROFILEID=S.PROFILEID  WHERE P.CASTE IS NULL AND J.GENDER='M'";
		}
		unset($search_gender,$Gender);
		if($sql_cosmo_m)
			 $sql_cosmo_m.=" order by SORT_DT desc ";
		if($sql_cosmo_f)
			 $sql_cosmo_f.=" order by SORT_DT desc ";
	}

	if($onlinestr=="" && $searchonline==1)
	{
		$COUNT=0;
	}
	else 
	{
		if($FRESHNESS=="F") 
		{
			if($sql_m)
				$sql_m.= " order by ENTRY_DT desc";
			if($sql_f)
				$sql_f.= " order by ENTRY_DT desc";
		}
		elseif($FRESHNESS=="M") 
		{
			if($sql_m)
				$sql_m.= " order by MOD_DT desc";
			if($sql_f)
				$sql_f.= " order by MOD_DT desc";
		}
		elseif($FRESHNESS=="L") 
		{
			if($sql_m)
				$sql_m.= " order by LAST_LOGIN_DT desc";
			if($sql_f)
				$sql_f.= " order by LAST_LOGIN_DT desc";
		}
		elseif($photobrowse=="Y")
		{
			$FRESHNESS="S";
			if($sql_m)
				$sql_m.= " order by SORT_DT desc";
			if($sql_f)
				$sql_f.= " order by SORT_DT desc";
		}
		else 
		{
			$FRESHNESS="S";
			if($sql_m)
				$sql_m.= " order by SORT_DT desc";
			if($sql_f)
				$sql_f.= " order by SORT_DT desc";
		}
		if($newsearch!='cosmo')
		{
			if($sql_m && !$sql_f)
				$sql_m.=" limit 5";
			elseif($sql_f && !$sql_m)
				$sql_f.=" limit 5";
			elseif($sql_f && $sql_m)
			{
				$sql_f.=" limit 3";
				$sql_m.=" limit 2";
			}
		}
		 if($newsearch=='cosmo')
		{
		
			if($sql_cosmo_m && !$sql_cosmo_f)
                                $sql_cosmo_m.=" limit 5";
			elseif($sql_cosmo_f && !$sql_cosmo_m)
				$sql_cosmo_f.=" limit 5";
                        elseif($sql_cosmo_f && $sql_cosmo_m)
                        {
                                $sql_cosmo_f.=" limit 3";
                                $sql_cosmo_m.=" limit 2";
                        }

		}
		// close master connection
		//mysql_close($db);
	
		// take connection on slave
		$db=connect_slave();
		if($newsearch!='cosmo')
		{
			
			if($sql_m)			
				$result_m=mysql_query_decide($sql_m) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($sql_f)
				$result_f=mysql_query_decide($sql_f) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		if($newsearch=='cosmo')
		{	
			if($sql_cosmo_m)
				$result_m=mysql_query_decide($sql_cosmo_m) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo,"ShowErrTemplate");
			if($sql_cosmo_f)
                                $result_f=mysql_query_decide($sql_cosmo_f) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}

		unset($sql_m,$sql_f,$sql_cosmo_m,$sql_cosmo_f);
		if($result_m)
			$COUNT=$COUNT+mysql_num_rows($result_m);
		if($result_f)
			$COUNT=$COUNT+mysql_num_rows($result_f);
		
		
	}	
		// close connecton on slave
		//mysql_close($db);
			
		// take connection on master
		$db=connect_db();

		$filepointer = fopen(JsConstants::$docRoot."/RSS/$feedname", "w");
                fputs ($filepointer, "<?xml version='1.0'?>\n<rss version='2.0'>\n<channel>");
                fputs ($filepointer, "\n <description>$description</description>\n<title>$head</title>\n <link>http://www.jeevansathi.com</link>\n");
		$arr_search=array('&','<','>',"'",'"');
		$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
		unset($myrow);

		$myrow=array();
		for($i=0;$i<$COUNT;$i++)
		{
			if($result_f && $myrow_f=mysql_fetch_array($result_f))
				array_push($myrow,$myrow_f);
			if($result_m && $myrow_m=mysql_fetch_array($result_m))
				array_push($myrow,$myrow_m);
		}
	

		unset($newsearch);

		if($COUNT)
		{
			for($j=0;$j<5;$j++)
			{
				 $sql="SELECT $FIELDS FROM JPROFILE WHERE  activatedKey=1 and PROFILEID=".$myrow[$j]['PROFILEID'];
				$result=mysql_query_decide($sql) or die(mysql_error_js());
				while($details=mysql_fetch_array($result))
				{
					$height=label_select("HEIGHT",$details['HEIGHT']);
					$caste=label_select("CASTE",$details['CASTE']);
					$occ=label_select("OCCUPATION",$details['OCCUPATION']);
					$city_u=label_select("CITY_USA",$details['CITY_RES']);
					$city_i=label_select("CITY_INDIA",$details['CITY_RES']);
					if($details['GENDER']=='F')
						$gender='Bride';
					if($details['GENDER']=='M')
						$gender='Groom';
					 $title=$details['AGE'].", ".$gender;
					if($height)
						$title.=", ".str_replace("'",'&apos;',substr($height[0],0,11));
					if($caste)
						$title.=", ".str_replace($arr_search,$arr_repl,$caste[0]);
					if($city_u || $city_i)
						$title.=", ".str_replace($arr_search,$arr_repl,$city_i[0]).str_replace($arr_search,$arr_repl,$city_u[0]);
					if($occ)
						$title.=", ".str_replace($arr_search,$arr_repl,$occ[0]);
				
					$title=check_ascii($title);
					$profileid=$details['PROFILEID'];
					$profilechecksum=md5($profileid) . "i" . $profileid;
					$screening=$details["SCREENING"];
							if(isFlagSet("YOURINFO",$screening))
								$yourinfo=$details["YOURINFO"];
					else
						$yourinfo="";
																	     
					if(isFlagSet("FATHER_INFO",$screening))
						$fatherinfo=$details["FATHER_INFO"];
					else
					$fatherinfo="";
																     
					if(isFlagSet("SIBLING_INFO",$screening))
						$siblinginfo=$details["SIBLING_INFO"];
					else
						$siblinginfo="";
																	     
					if(isFlagSet("SPOUSE",$screening))
						$spouseinfo=$details["SPOUSE"];
					else
						$spouseinfo="";
																	     
					if($spouseinfo!="")
						$spouseinfo="Looking for: " . $spouseinfo;
																	     
					 $yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,300);
					$yourinfo=str_replace($arr_search,$arr_replace,$yourinfo);
					 $yourinfo=str_replace('0','',$yourinfo);
					$yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
					$yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
					$yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
					$yourinfo=str_replace('  ',' ',$yourinfo);
					$protitle="";
					$counter=0;
					$len=0;
					$temp=explode(" ",$yourinfo);
					for($i=0;$i<=2;$i++)
					{
						$protitle.=" ".$temp[$i];
					}
					$protitle=str_replace($arr_search,$arr_replace,$protitle);                                                                                                     
					$protitle=str_replace(',',', ',$protitle);              //replace ',' with ', '(comma, space)
					$protitle=str_replace(' ,',', ',$protitle);             //replace ' ,' (space, comma) with ', '(comma, space);
					$protitle=str_replace('/','/ ',$protitle);              //replace '/' with '/ ' (slash, space)
					$protitle=str_replace('  ',' ',$protitle);              // replace '  ' (2 spaces) with ' ' (single space)
					$yourinfo="";
					$protitle=check_ascii($protitle);
					$yourinfo=check_ascii($yourinfo);
					for($c=3;$c<count($temp);$c++)
					{
						$yourinfo.=" ".$temp[$c];
					}
					 fputs ($filepointer,"\t<item>\n \t\t<title>$title</title>\n \t\t<link>http://www.jeevansathi.com/profile/viewprofile.php?profilechecksum=$profilechecksum&amp;source=RSS</link>\r\n");
					fputs($filepointer," \t\t<description>$protitle.$yourinfo</description>\r\n \t</item>\n");
				}
			
			}
			fputs ($filepointer, "</channel>\n</rss>");
		}
			//fputs ($filepointer, "</channel>\n</rss>");
fclose ($filepointer);
}
//mysql_close($db);
		
function check_ascii($contents)
{
	$len=strlen($contents);
															     
	$str="";
	$i=0;
	while($i<$len)
	{
	$ch=$contents{$i};
	if((ord($ch)<127 && ord($ch)>31) || ord($ch)==9 || ord($ch)==10)
		$str.=$contents{$i};
	$i++;
	}
                                                                                                                             
        return $str;
}
	
?>
