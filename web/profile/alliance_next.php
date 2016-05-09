<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");
	include("alliance_search.inc");
	$db=connect_db();
	
	$data = authenticated($checksum);
	
	$arr=explode("i",$searchchecksum);
	$searchid=$arr[1];
	
	$ERROR_STRING="Due to a temporary problem your request could not be processed. Please try after a couple of minutes";
	
	if($arr[0]!=md5($searchid))
		logError($ERROR_STRING,"Tampering with URL in search","ShowErrTemplate");
		
	$sql="select GENDER,CASTE,MTONGUE,LAGE,HAGE,HAVEPHOTO AS WITHPHOTO,MANGLIK,MSTATUS,HAVECHILD AS CHILDREN,LHEIGHT,HHEIGHT,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,OCCUPATION,COUNTRY_RES,CITY_RES,EDU_LEVEL,ONLINE,INCOME,SUBSCRIPTION from SEARCHQUERY where ID='$searchid'";
	$result=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	
	$searchrow=mysql_fetch_array($result);
	
	if($searchrow["INCOME"]!="")
		$Income=explode(",",$searchrow["INCOME"]);
	if($searchrow["CASTE"]!="")
		$Caste=explode(",",$searchrow["CASTE"]);
	if($searchrow["MTONGUE"]!="")
		$Mtongue=explode(",",$searchrow["MTONGUE"]);
	if($searchrow["OCCUPATION"]!="")
		$Occupation=explode(",",$searchrow["OCCUPATION"]);
	if($searchrow["COUNTRY_RES"]!="")
		$Country_Residence=explode(",",$searchrow["COUNTRY_RES"]);
	if($searchrow["CITY_RES"]!="")
		$City_India=explode(",",$searchrow["CITY_RES"]);
	if($searchrow["EDU_LEVEL"]!="")
		$Education_Level=explode(",",$searchrow["EDU_LEVEL"]);
	if($searchrow["MANGLIK"]!="")
		$MANGLIK_ARRAY=explode(",",$searchrow["MANGLIK"]);
	if($searchrow["MSTATUS"]!="")
		$MSTATUS_ARRAY=explode(",",$searchrow["MSTATUS"]);
	if($searchrow["CHILDREN"]!="")
		$CHILDREN_ARRAY=explode(",",$searchrow["CHILDREN"]);
	if($searchrow["BTYPE"]!="")
		$BODYTYPE_ARRAY=explode(",",$searchrow["BTYPE"]);
	if($searchrow["COMPLEXION"]!="")
		$COMPLEXION_ARRAY=explode(",",$searchrow["COMPLEXION"]);
	if($searchrow["DIET"]!="")
		$DIET_ARRAY=explode(",",$searchrow["DIET"]);
	if($searchrow["SMOKE"]!="")
		$SMOKE_ARRAY=explode(",",$searchrow["SMOKE"]);
	if($searchrow["DRINK"]!="")
		$DRINK_ARRAY=explode(",",$searchrow["DRINK"]);

	$E_CLASS=$searchrow["SUBSCRIPTION"];	
		
	$Handicapped_Search=$searchrow["HANDICAPPED"];
	$Min_Age=$searchrow["LAGE"];
	$Max_Age=$searchrow["HAGE"];
	$Gender=$searchrow["GENDER"];
	$Photos=$searchrow["WITHPHOTO"];
	
	$searchonline=$searchrow["ONLINE"];
	
	if($Photos=="Y")
		$PAGELEN=15;
	else 
		$PAGELEN=40;

	$Min_Height=$searchrow["LHEIGHT"];
	$Max_Height=$searchrow["HHEIGHT"];
	
	mysql_free_result($result);
	unset($searchrow);
	
	if($Gender=="M")
		$sql = "SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM SEARCH_MALE WHERE ";
	else 
		$sql = "SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM SEARCH_FEMALE WHERE ";
		
	if(is_array($Caste) && !in_array("All",$Caste))
	{
		$Caste1=get_all_caste($Caste);
		if(is_array($Caste1))
		{
			$searchCaste="'" . implode($Caste1,"','") . "'";
			$sql.="CASTE IN ($searchCaste) AND ";
		}
	}
		
	if(is_array($Income) && !in_array("All",$Income))
	{
		$insert_income=implode("','", $Income);
		$sql.="INCOME IN ('$insert_income') AND ";
	}
	
	if(is_array($Mtongue) && !in_array("All",$Mtongue))
	{
		$insert_mtongue=implode("','", $Mtongue);
		$sql.="MTONGUE IN ('$insert_mtongue') AND ";
	}
		
	if(is_array($Occupation) && !in_array("All",$Occupation))
	{
		$insert_occupation=implode("','", $Occupation);
		$sql.="OCCUPATION IN ('$insert_occupation') AND ";
	}
	
	$Country_Res=$Country_Residence;
	$City_Res=$City_India;
	
	if(is_array($Country_Res))
	{	
        	if(!in_array("All",$Country_Res) && !in_array("",$Country_Res))
        	{
        		$insertCountry=implode($Country_Res,",");
        		
			for($i=0;$i<count($Country_Res);$i++)
        		{
        			if($Country_Res[$i]=="51")
        				$country_india=1;
        			elseif($Country_Res[$i]=="128")
        				$country_usa=1;
        			else 
                			$Country_Res1 .= "'".$Country_Res[$i]."'".",";
	        	}
    			$Country_Res1 = substr($Country_Res1, 0, strlen($Country_Res1)-1);
		}
		else
		{
			$Country_Res1= "";
		}
	}
	elseif($Country_Res!="" && $Country_Res!="All")
	{
		$insertCountry=$Country_Res;
		
		if($Country_Res=="51")
			$country_india=1;
		elseif($Country_Res=="128")
			$country_usa=1;
		else 	
	        	$Country_Res1 = "'".$Country_Res."'";
	}

	if(is_array($City_Res))
	{
	        if(!in_array("All",$City_Res) && !in_array("",$City_Res))
	        {
	        	$insertCity=implode($City_Res,",");
	        	
			for($i=0;$i<count($City_Res);$i++)
	        	{
	        		if(is_numeric($City_Res[$i]))
	        		{
	        			$country_usa=1;
	        			$city_usa[]=$City_Res[$i];
	        		}
	        		elseif(strlen($City_Res[$i])==2)
	        		{
	        			$country_india=1;
	        			$citysql="select SQL_CACHE VALUE from CITY_INDIA where VALUE like '$City_Res[$i]%'";
	        			$cityresult=mysql_query_decide($citysql);
	        			
	        			while($cityrow=mysql_fetch_array($cityresult))
	        			{
	        				$city_india[]=$cityrow["VALUE"];
	        			}
	        			
	        			mysql_free_result($cityresult);
	        		}
	        		else 
	        		{
	        			$country_india=1;
	        			$city_india[]=$City_Res[$i];
	        		}
		        }
		}
	}
	elseif($City_Res!="" && $City_Res!="All")
	{
		$insertCity=$City_Res;
		if(is_numeric($City_Res))
		{
			$country_usa=1;
			$city_usa[]=$City_Res;
		}
		else 
		{
			$country_india=1;
			if(strlen($City_Res)==2)
        		{
        			$citysql="select SQL_CACHE VALUE from CITY_INDIA where VALUE like '$City_Res%'";
        			$cityresult=mysql_query_decide($citysql);
        			
        			while($cityrow=mysql_fetch_array($cityresult))
        			{
        				$city_india[]=$cityrow["VALUE"];
        			}
        			
        			mysql_free_result($cityresult);
        		}
        		else 
				$city_india[]=$City_Res;
		}
	}
			
	if($country_india==1)
	{
		if(count($city_india) > 0)
			$countrysql[]="(COUNTRY_RES = '51' and CITY_RES in ('" . implode($city_india,"','") . "'))";
		elseif($Country_Res1=="")
			$Country_Res1="51";
		else 
			$Country_Res1.=",'51'";
	}
	
	if($country_usa==1)
	{
		if(count($city_usa) > 0)
			$countrysql[]="(COUNTRY_RES = '128' and CITY_RES in ('" . implode($city_usa,"','") . "'))";
		elseif($Country_Res1=="")
			$Country_Res1="128";
		else 
			$Country_Res1.=",'128'";
	}
	
	if($Country_Res1!="")
	{
		$countrysql[]="(COUNTRY_RES in ($Country_Res1))";
	}
	
	if(is_array($countrysql))
	{
		$countrycond=implode($countrysql," or ");
		$countrycond="(" . $countrycond . ")";
	}

	if(trim($countrycond)!="")
		$sql.="$countrycond AND ";
		
	if(is_array($Education_Level) && !in_array("All",$Education_Level))
	{
		$insert_edu=implode("','", $Education_Level);
		$sql.="EDU_LEVEL IN ('$insert_edu') AND ";
	}
	
	if(is_array($MANGLIK_ARRAY))
	{
		$insert_manglik=implode("','",$MANGLIK_ARRAY);
		$sql.= "MANGLIK IN ('$insert_manglik') AND ";
	}
	
	if(is_array($MSTATUS_ARRAY))
	{
		$insert_mstatus=implode("','",$MSTATUS_ARRAY);
		$sql.= "MSTATUS IN ('$insert_mstatus') AND ";
	}
	
	if(is_array($CHILDREN_ARRAY))
	{
		$insert_children=implode("','",$CHILDREN_ARRAY);
		$sql.= "HAVECHILD IN ('$insert_children') AND ";
	}
	
	$sql.="AGE BETWEEN '$Min_Age' AND '$Max_Age' AND ";                  
	$sql.="HEIGHT BETWEEN '$Min_Height' AND '$Max_Height' AND ";
 
	if(is_array($BODYTYPE_ARRAY))
	{
		$insert_btype=implode("','",$BODYTYPE_ARRAY);
		$sql.="BTYPE IN ('$insert_btype') AND ";
	}
	
	if(is_array($COMPLEXION_ARRAY))
	{
		$insert_complexion=implode("','",$COMPLEXION_ARRAY);
		$sql.="COMPLEXION IN ('$insert_complexion') AND ";
	}
	
	if(is_array($DIET_ARRAY))
	{
		$insert_diet=implode("','",$DIET_ARRAY);
		$sql.="DIET IN ('$insert_diet') AND ";
	}
		
	if(is_array($SMOKE_ARRAY))
	{
		$insert_smoke=implode("','",$SMOKE_ARRAY);
		$sql.="SMOKE IN ('$insert_smoke') AND ";
	}
		
	if(is_array($DRINK_ARRAY))
	{
		$insert_drink=implode("','",$DRINK_ARRAY);
		$sql.="DRINK IN ('$insert_drink') AND ";
	}
		
	if($Handicapped_Search != "")
	{
		$Handicapped_Search=str_replace(",","','",$Handicapped_Search);
		$sql.="HANDICAPPED IN ('$Handicapped_Search') AND ";
	}

	if($Photos == "Y")
	{
		$sql.="HAVEPHOTO = 'Y' AND ";
	}
	elseif($Photos == "N")
	{
		$sql.="HAVEPHOTO = 'N' AND ";
	}

	if($E_CLASS=='D')
        {
                $sql.="SUBSCRIPTION='D' AND ";
        }
		
	$sql=substr($sql,0,-4);
		
	if($searchonline==1)
	{
		$onlinesql="select userID from userplane.users";
		$onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
		
		$onlinestr="";
		
		while($myonline=mysql_fetch_array($onlineresult))
		{
			$onlinestr.="'" . $myonline["userID"] . "',";
		}
		
		mysql_free_result($onlineresult);
		
		$onlinestr=substr($onlinestr,0,strlen($onlinestr)-1);
		
		if($onlinestr!="")
			$sql.=" AND PROFILEID in ($onlinestr)";
	}
	
	if(!$j)
		$j=0;
			
	$sql.= "order by MOD_DT desc limit $j,$PAGELEN";

	//mysql_close($db);
	$db=connect_slave();
	
 	$result=mysql_query_decide($sql) or logError("$ERROR_STRING",$sql,"ShowErrTemplate");
 	
 	$sql="select FOUND_ROWS() as cnt";
	$resultcount=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	
	$countrow=mysql_fetch_row($resultcount);
			
	$COUNT=mysql_num_rows($result);

	//mysql_close($db);
	$db=connect_db();
	
 	if($COUNT > 0)
	{
		displayresults($result,$j,"alliance_next.php",$countrow[0],"","1",$searchchecksum);
		$smarty->assign("RECORDCOUNT",$countrow[0]);
	}
	else 
	{
		$smarty->assign("RECORDCOUNT","0");
		$smarty->assign("NORESULTS","1");
		$smarty->assign("NO_OF_PAGES","0");
		$smarty->assign("CURPAGE","0");
	}
	
	searchBar($Gender,$Religion,$Mtongue,$Min_Age,$Max_Age,$Photos,$Caste);

	$smarty->assign("SEARCHCHECKSUM",$searchchecksum);
	
	if($Photos == "Y")
		$smarty->assign("PHOTOTITLE","Advanced Search");
		
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));

/*	
	if($Photos == "Y")
	{
		//$smarty->display("photosearch_results.htm");
		$smarty->display("photobrowse_results.htm");
	}
	else
*/
		$smarty->display("alliance_results.htm");
		
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
