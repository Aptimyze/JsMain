<?php
	
	require_once("display_result.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
	
	function displayresults($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="")
	{
		global $PAGELEN,$smarty,$checksum,$data;
		
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		
		$FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING";
		while($myrow=mysql_fetch_row($result))
		{
			$str.="'" . $myrow[0] . "',";
		}
		
		$str=substr($str,0,strlen($str)-1);
		
		$sql="select";
		
		if($nocalc=="")
			$sql.=" SQL_CALC_FOUND_ROWS";
			
		$sql.=" $FIELDS from JPROFILE where PROFILEID in ($str)";
		
		if($putactivate=="1")
			$sql.=" and ACTIVATED='Y'";

		$sql.=" order by MOD_DT desc";
		
		if($nocalc=="")
			$sql.=" limit $curcount,$PAGELEN";
		
		$result1=mysql_query($sql) or die("$sql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		if($nocalc=="")
		{
			$sql="select FOUND_ROWS() as cnt";
			$resultcount=mysql_query($sql) or die("$sql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$countrow=mysql_fetch_row($resultcount);
			$totalrec=$countrow[0];
		}
		
		$sno=1;
		
		while($myrow=mysql_fetch_array($result1))
		{
			$resultprofiles.="'" . $myrow["PROFILEID"] . "',";
		}
		
		$resultprofiles=substr($resultprofiles,0,strlen($resultprofiles)-1);
		
		// move the pointer of the recordset back to record 1
		mysql_data_seek($result1,0);
			
		if($data["PROFILEID"]!="" && mysql_num_rows($result1)>0)
		{
			$imagesql="select RECEIVER,TYPE from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and RECEIVER in ($resultprofiles)";
			$imageresult=mysql_query($imagesql)  or die("$imagesql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$imagesql,"ShowErrTemplate");
			
			if(mysql_num_rows($imageresult)>0)
			{
				while($imagerow=mysql_fetch_array($imageresult))
					$contacted1[$imagerow["RECEIVER"]]=$imagerow["TYPE"];
			}
			
			mysql_free_result($imageresult);
			
			$imagesql="select SENDER,TYPE from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and SENDER in ($resultprofiles)";
			$imageresult=mysql_query($imagesql) or die("$imagesql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$imagesql,"ShowErrTemplate");
			
			if(mysql_num_rows($imageresult)>0)
			{
				while($imagerow=mysql_fetch_array($imageresult))
					$contacted1[$imagerow["SENDER"]]=$imagerow["TYPE"];
			}
			
			mysql_free_result($imageresult);
			
			$bookmarksql="select BOOKMARKEE from BOOKMARKS where BOOKMARKER='" . $data["PROFILEID"] . "' and BOOKMARKEE in ($resultprofiles)";
			$bookresult=mysql_query($bookmarksql) or die("$bookmarksql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$bookmarksql,"ShowErrTemplate");
			
			if(mysql_num_rows($bookresult) > 0)
			{
				while($mybooks=mysql_fetch_array($bookresult))
				{
					$bookmarks[]=$mybooks["BOOKMARKEE"];
				}
			}
			
			mysql_free_result($bookresult);
		}
		
		if($resultprofiles)
		{
			$onlinesql="select userID from userplane.users where userID in ($resultprofiles)";
			$onlineresult=mysql_query($onlinesql) or die("$onlinesql".mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$bookmarksql,"ShowErrTemplate");
			
			if(mysql_num_rows($onlineresult) > 0)
			{
				while($myonline=mysql_fetch_array($onlineresult))
				{
					$onlinemembers[]=$myonline["userID"];
				}
			}
			
			mysql_free_result($onlineresult);
		}
		
		while($myrow=mysql_fetch_array($result1))
		{
			//$occupation=label_select("OCCUPATION",$myrow["OCCUPATION"]);
			$occ=$myrow["OCCUPATION"];
			$occupation=$OCCUPATION_DROP["$occ"];
			$caste1=$myrow["CASTE"];
			//$caste=label_select("CASTE",$myrow["CASTE"]);
			$caste=$CASTE_DROP["$caste1"];
			$screening=$myrow["SCREENING"];
			if(isFlagSet("YOURINFO",$screening))
				$yourinfo=$myrow["YOURINFO"];
			else 
				$yourinfo="";
			
			$heightn=$myrow["HEIGHT"];
			//$height=label_select("HEIGHT",$myrow["HEIGHT"]);
			$height=$HEIGHT_DROP["$heightn"];
			$height1=explode("(",$height);
			$height2=trim($height1[0]);
			$mod_date=substr($myrow["MOD_DT"],0,10);
			if($mod_date!="0000-00-00" && $mod_date!="")
			{
				$mod_date1=explode("-",$mod_date);
				$mod_date=$mod_date1[2] . " " . getMonthName($mod_date1[1]) . " " . substr($mod_date1[0],2,2);
			}
			else 
				$mod_date="";
			
			if($myrow["CITY_RES"]!="")
			{
				$city_res1=$myrow["CITY_RES"];
				
				if($myrow["COUNTRY_RES"]=="51")
					$residence=$CITY_INDIA_DROP["$city_res1"];
				else 
					$residence=$CITY_USA_DROP["$city_res1"];
			}
			else 
			{
				$country1=$myrow["COUNTRY_RES"];
				//$residence=label_select("COUNTRY",$myrow["COUNTRY_RES"]);
				$residence=$COUNTRY_DROP["$country1"];
			}
			
			$newCaste=explode(":",$caste);
			if(trim($newCaste[1])!="")
				$myCaste=$newCaste[1];
			else 
				$myCaste=$newCaste[0];
			
			$subscription=explode(",",$myrow["SUBSCRIPTION"]);
			
			if(in_array("B",$subscription))
				$bold_listing=1;
			else 
				$bold_listing=0;
				
			if($myrow["HAVEPHOTO"]=="Y")
				$havephoto="Y";
			else 
				$havephoto="N";
				
			if(is_array($bookmarks) && in_array($myrow["PROFILEID"],$bookmarks))
				$bookmarked=1;
			else 
				$bookmarked=0;
				
			if(is_array($onlinemembers) && in_array($myrow["PROFILEID"],$onlinemembers))
				$online=1;
			else 
				$online=0;
			
			$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
			$RESULT_ARRAY[]=array("SNO" => $sno,
						"PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
						"PROFILEID" => $myrow["PROFILEID"],
						"PHOTOCHECKSUM" => $photochecksum,
						"USERNAME" => $myrow["USERNAME"],
						"AGE" => $myrow["AGE"],
						"HEIGHT" => $height2,
						"CASTE" => $myCaste,
						"OCCUPATION" => $occupation,
						"RESIDENCE" => $residence,
						"YOURINFO" => substr($yourinfo,0,150),
						"MOD_DT" => $mod_date,
						"HAVEPHOTO" => $havephoto,
						"CONTACTSTATUS" => $contacted1[$myrow["PROFILEID"]],
						"BOOKMARKED" => $bookmarked,
						"BOLDLISTING" => $bold_listing,
						"ONLINE" => $online);
									
			$sno++;
		}
		
		mysql_free_result($result1);
		
		$smarty->assign("RECORDCOUNT",$totalrec);
		$smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY);
		
		if( $curcount )
			$cPage = ($curcount/$PAGELEN) + 1;
		else
			$cPage = 1;
			
		if($moreurl=="")
			pagelink($PAGELEN,$totalrec,$cPage,10,$checksum,$scriptname,$searchchecksum);
		else 
			pagelink($PAGELEN,$totalrec,$cPage,10,"$checksum&$moreurl",$scriptname,$searchchecksum);
		
		$smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));
		$smarty->assign("CURPAGE",$cPage);
		$smarty->assign("BACK_TO_SEARCH_PAGE",$curcount);
		$smarty->assign("SCRIPTNAME",$scriptname);
		
		return "";
	}
	
	function getMonthName($month)
	{
		switch ($month)
		{
			case "01":
			case "1":
				return "Jan";
			case "02":
			case "2":
				return "Feb";
			case "03":
			case "3":
				return "Mar";
			case "04":
			case "4":
				return "Apr";
			case "05":
			case "5":
				return "May";
			case "06":
			case "6":
				return "Jun";
			case "07":
			case "7":
				return "Jul";
			case "08":
			case "8":
				return "Aug";
			case "09":
			case "9":
				return "Sep";
			case "10":
				return "Oct";
			case "11":
				return "Nov";
			case "12":
				return "Dec";
		}
	}
	
	// gender of the person who is being searched is passed
	function searchBar($gender="F",$religion="",$mtongue="",$lage="",$hage="",$withphoto="",$caste="")
	{	
		global $smarty;
		
		if($lage=="" && $hage=="")
		{
			if($gender=="F")
			{
				$lage="20";
				$hage="25";
			}
			else 
			{
				$lage="23";
				$hage="27";
			}
		}
		
		if(is_array($mtongue))
			$smarty->assign("SEARCHMTONGUE",create_dd($mtongue[0],"Mtongue"));	
		else 
			$smarty->assign("SEARCHMTONGUE",create_dd($mtongue,"Mtongue"));
			
		if(is_array($caste))
			$smarty->assign("SEARCHCASTE",create_dd($caste[0],"Caste"));
		else 
			$smarty->assign("SEARCHCASTE",create_dd($caste,"Caste"));
		
		$smarty->assign("SEARCHLAGE",$lage);
		$smarty->assign("SEARCHHAGE",$hage);
		$smarty->assign("SEARCHGENDER",$gender);
		$smarty->assign("SEARCHWITHPHOTO",$withphoto);
	}
	
?>
