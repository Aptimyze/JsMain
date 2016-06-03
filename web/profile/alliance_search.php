<?php
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("connect.inc");
include("alliance_search.inc");
$db=connect_db();
$FLAG = "search";
$smarty->assign("FLAG",$FLAG);
$data = authenticated($checksum);

// check for with photo search
if($Profile=="Y")
	$PAGELEN=15;
else
	$PAGELEN=40;

$ERROR_STRING="Due to a temporary problem your request could not be processed. Please try after a couple of minutes";

if(isset($data))
{
	$smarty->assign("SAVE_PARTNER_SEARCH","Y");
}

if(isset($data) || $FLAG=="search")
{
	$profileid=$data["PROFILEID"];
  	
	if($fsubmit)
	{
		$is_error=0;
        
		//************  VALIDATIONS AND CHECKS -- START HERE********************
           	
		if($Caste[0]=="")
		{ 
			$smarty->assign("check_caste","Y");
			$is_error++;
		}

		if($Mtongue[0]=="")
		{
			$smarty->assign("check_mtongue","Y");
			$is_error++;
		}

		if($Occupation[0]=="")
		{
			$smarty->assign("check_occupation","Y");
			$is_error++; 
		}

		if($Country_Residence[0]=="")
		{ 
			$smarty->assign("check_countryres","Y");
			$is_error++;
		}

		if($City_India[0]=="")
		{
			$smarty->assign("check_city","Y");
			$is_error++;
		}
		
		if($Education_Level[0]=="")
		{
			$smarty->assign("check_education_level","Y");  
			$is_error++;
		} 
		
		//Manglik Status validation
		if($Manglik_Status1)
		{
			$smarty->assign("MANGLIK_STATUS1","y");
		}
		else
		{
			if($Manglik_Status2)
			{
				$MANGLIK_ARRAY[]=$Manglik_Status2;
				$smarty->assign("MANGLIK_STATUS2","y");
			}
			if($Manglik_Status3)
			{
				$MANGLIK_ARRAY[]=$Manglik_Status3;
				$smarty->assign("MANGLIK_STATUS3","y");
			}
			if($Manglik_Status4)
			{
				$MANGLIK_ARRAY[]=$Manglik_Status4;
				$smarty->assign("MANGLIK_STATUS4","y");
			}
		}
        	
		if(!$Manglik_Status1 && !$Manglik_Status2 && !$Manglik_Status3 && !$Manglik_Status4)
		{
			$smarty->assign("MANGLIK_S","y");
			$is_error++;
		}
		
		//Marital status validation
		$flag=0;
		if($Marital_Status1)
		{
			$smarty->assign("MARITAL_STATUS1","y");
		}
		else
		{
			if($Marital_Status2)
			{ 
				$MSTATUS_ARRAY[]=$Marital_Status2;
				$smarty->assign("MARITAL_STATUS2","y");
			}
			
			if($Marital_Status3)
			{
				$MSTATUS_ARRAY[]=$Marital_Status3;
				$smarty->assign("MARITAL_STATUS3","y");
			}
			    		
			if($Marital_Status4)
			{
				$MSTATUS_ARRAY[]=$Marital_Status4;
				$smarty->assign("MARITAL_STATUS4","y");
			}
			
			if($Marital_Status5)
			{
				$MSTATUS_ARRAY[]=$Marital_Status5;
				$smarty->assign("MARITAL_STATUS5","y");
			}

			if($Marital_Status6)
			{
				$MSTATUS_ARRAY[]=$Marital_Status6;
				$smarty->assign("MARITAL_STATUS6","y");
			}
		}
		
		if(!$Marital_Status1 && !$Marital_Status2 && !$Marital_Status3 && !$Marital_Status4 && !$Marital_Status5 && !$Marital_Status6)
		{
			$smarty->assign("MARITAL_S","y");
			$is_error++;
		}
		
		// Age  Validation
		if($Min_Age > $Max_Age)
		{
			$smarty->assign("check_age","Y");
			$is_error++;
		}
		
		// Height Validation
		if($Min_Height > $Max_Height)
		{
			$smarty->assign("check_height","Y");
			$is_error++;
		}
      	
		// Has Children validation
		if($Has_Children == "All")
		{
			$smarty->assign("CHILDREN1","y");
		}
		elseif($Has_Children == "N")
		{
			$CHILDREN_ARRAY[]="N";
			$Has_Children_Partner="N";
			$smarty->assign("CHILDREN2","y");
		}
		elseif($Has_Children == "Y")
		{
			$CHILDREN_ARRAY[]="YT";
			$CHILDREN_ARRAY[]="YS";
			$CHILDREN_ARRAY[]="Y";
			$Has_Children_Partner="Y";
			$smarty->assign("CHILDREN3","y");
		}

		if(!$Has_Children)
		{
			$smarty->assign("HAS_CHILDREN","y");
		}
		
		//Body Type validation
		if($Body_Type1)
		{
			$smarty->assign("BODY_TYPE1","y");
		}
		else
		{
			if($Body_Type2)
			{
				$BODYTYPE_ARRAY[]=$Body_Type2;
				$smarty->assign("BODY_TYPE2","y");
			}

			if($Body_Type3)
			{
				$BODYTYPE_ARRAY[]=$Body_Type3;
				$smarty->assign("BODY_TYPE3","y");
			}
			
			if($Body_Type4)
			{
				$BODYTYPE_ARRAY[]=$Body_Type4;
				$smarty->assign("BODY_TYPE4","y");
			}
                	
			if($Body_Type5)
			{
				$BODYTYPE_ARRAY[]=$Body_Type5;
				$smarty->assign("BODY_TYPE5","y");
			}
		}
       		
		if(!$Body_Type1 && !$Body_Type2 && !$Body_Type3 && !$Body_Type4 && !$Body_Type5)
		{
			$smarty->assign("BODY_T","y");
			$is_error++;
		}
		
		//Complexion validation
		if($Complexion1)
		{
			$smarty->assign("COMPLEXION1","y");
		}
		else
		{
			if($Complexion2)
			{
				$COMPLEXION_ARRAY[]=$Complexion2;
				$smarty->assign("COMPLEXION2","y");
			}
			
			if($Complexion3)
			{
				$COMPLEXION_ARRAY[]=$Complexion3;
				$smarty->assign("COMPLEXION3","y");
			}
			
			if($Complexion4)
			{
				$COMPLEXION_ARRAY[]=$Complexion4;
				$smarty->assign("COMPLEXION4","y");
			}
          		
			if($Complexion5)
			{
				$COMPLEXION_ARRAY[]=$Complexion5;
				$smarty->assign("COMPLEXION5","y");
			}
		}

		if(!$Complexion1 && !$Complexion2 && !$Complexion3 && !$Complexion4 && !$Complexion5)
		{
			$smarty->assign("COMPLEXION","y");
			$is_error++;
		}
    
		//Diet validation
		if($Diet == "Doesnt Matter")
		{
			$smarty->assign("DIET1","y");
		}
		elseif($Diet == "V")
		{
			$DIET_ARRAY[]="V";
			$DIET_ARRAY[]="J";
			$smarty->assign("DIET2","y");
		}
		elseif($Diet == "N")
		{
			$DIET_ARRAY[]="N";
			$smarty->assign("DIET3","y");
		}
		
		if(!$Diet)
		{
			$smarty->assign("DIET","y");
			$is_error++;
		}

		//Smoke validation
		if($Smoke1)
		{
			$smarty->assign("SMOKE1","y");
		}
		else
		{
			if($Smoke2)
			{
				$SMOKE_ARRAY[]=$Smoke2;
				$smarty->assign("SMOKE2","y");
			}
			if($Smoke3)
			{
				$SMOKE_ARRAY[]=$Smoke3;
				$smarty->assign("SMOKE3","y");
			}
			if($Smoke4)
			{
				$SMOKE_ARRAY[]=$Smoke4;
				$smarty->assign("SMOKE4","y");
			}
		}
        	
		if(!$Smoke1 && !$Smoke2 && !$Smoke3 && !$Smoke4)
		{
			$smarty->assign("SMOKE","y");
			$is_error++;
		}
		
		//Drink validation
		if($Drink1)
		{
			$smarty->assign("DRINK1","y");
		}
		else
		{
			if($Drink2)
			{
				$DRINK_ARRAY[]=$Drink2;
				$smarty->assign("DRINK2","y");
			}
       		
			if($Drink3)
			{
				$DRINK_ARRAY[]=$Drink3;
				$smarty->assign("DRINK3","y");
			}
			
			if($Drink4)
			{
				$DRINK_ARRAY[]=$Drink4;
				$smarty->assign("DRINK4","y");
			}
		}
	  	
		if(!$Drink1 && !$Drink2 && !$Drink3 && !$Drink4)
		{
			$smarty->assign("DRINK","y");
			$is_error++;
		}
		
		//Handicapped validation
		if($Handicapped =="All")
		{
			$Handicapped_Search="";
			$Handicapped_Partner="";
			$smarty->assign("HANDICAPPED1","y");
		}
		elseif($Handicapped == "Not Handicapped")
		{
			$Handicapped_Search= "'N'";
			$Handicapped_Partner="N";
			$smarty->assign("HANDICAPPED2","y");
		}
		elseif($Handicapped == "Only Handicapped")
		{
			$Handicapped_Search= "'1','2','3','4'";
			$Handicapped_Partner="Y";
			$smarty->assign("HANDICAPPED3","y");
		}
    	
		if(!$Handicapped)
		{
			$smarty->assign("HANDICAPPED","y");
			$is_error++;
		}
		
		//Rstatus Validation
		if(count($Rstatus)==0)
		{
			$smarty->assign("check_rstatus","Y");
			$is_error++;
		}
		else	
		{
			foreach( $Rstatus as $value )
				$smarty->assign("r{$value}", 1);
		}

		//Profile validation
		if($Profile == "Doesnt Matter")
			$smarty->assign("PROFILE1","y");
		elseif($Profile == "Y")
			$smarty->assign("PROFILE2","y");
		elseif($Profile == "N")
			$smarty->assign("PROFILE3","y");
			
		if(!$Profile)
		{
			$smarty->assign("PROFILE","y");
		}
        	
		$smarty->assign("PROF","$Profile");
		
		//******************VALIDATIONS AND CHECK -- ENDS********************************
		
		
		//*************** CHECK FOR ANY ERROR START- HERE**************************
		if($is_error > 0)
		{
			if($Income=="")
				$smarty->assign("f_income","0");
			elseif($Income[0] != "All")
				$smarty->assign("f_income","0");
			else
				$smarty->assign("f_income","1");
				
			if($Caste=="")
				$smarty->assign("f_caste","0");
			elseif($Caste[0] != "All")
				$smarty->assign("f_caste","0");
			else
				$smarty->assign("f_caste","1");
			
			if($Mtongue=="")
				$smarty->assign("f_mtongue","0");
			elseif($Mtongue[0] != "All")
				$smarty->assign("f_mtongue","0");
			else
				$smarty->assign("f_mtongue","1");
			
			if($Occupation=="")
				$smarty->assign("f_occupation","0");
			elseif($Occupation[0] != "All")
				$smarty->assign("f_occupation","0");
			else
				$smarty->assign("f_occupation","1");
		                
			if($Country_Residence=="")
				$smarty->assign("f_country","0");
			elseif($Country_Residence[0] != "All")
				$smarty->assign("f_country","0");
			else
				$smarty->assign("f_country","1");
			
			if($City_India=="")
				$smarty->assign("f_city","0");
			elseif($City_India[0] != "All")
				$smarty->assign("f_city","0");
			else
				$smarty->assign("f_city","1");
	                	
			if($Education_Level=="")
				$smarty->assign("f_education","0");
			elseif($Education_Level[0] != "All")
				$smarty->assign("f_education","0");
			else
				$smarty->assign("f_education","1");
			
			$smarty->assign("MIN_AGE",$Min_Age);
			$smarty->assign("MAX_AGE",$Max_Age);
			
			$smarty->assign("GENDER",$Gender);
			$smarty->assign("caste",create_dd($Caste,"Caste"));
			$smarty->assign("mtongue",create_dd($Mtongue,"Mtongue"));
			$smarty->assign("occupation",create_dd($Occupation,"Occupation"));
			$smarty->assign("country_residence",create_dd($Country_Residence,"Country_Residence"));
			$smarty->assign("income",create_dd($Income,"Income"));
			
			$city_india=create_dd($City_India,"City_India");
			$city_usa=create_dd($City_Usa,"City_USA");
			$city_india .=  $city_usa;
			$smarty->assign("city_india",$city_india);
			
			$smarty->assign("education_level",create_dd($Education_Level,"Education_Level"));
			$smarty->assign("maxheight",create_dd($Max_Height,"Height",1));
			$smarty->assign("minheight",create_dd($Min_Height,"Height"));
		
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
			
			$smarty->display("alliance_search.htm"); 
		}
		// if no error 
		else
		{ 
			// if advanced search
			if($FLAG=="search" || $SAVE_PARTNER=="Y")
			{
				if($Gender=="M")
					$sql = "SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM SEARCH_MALE WHERE ";
				else 
					$sql = "SELECT SQL_CALC_FOUND_ROWS PROFILEID FROM SEARCH_FEMALE WHERE ";
				
				if(is_array($Caste) && !in_array("All",$Caste))
				{
					$insert_caste=implode("','", $Caste);
					
					$seCaste=get_all_caste($Caste);
					if(is_array($seCaste))
					{
						$searchCaste=implode($seCaste,"','");
						$searchCaste="'" . $searchCaste . "'";
						
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
								$citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res[$i]%'";
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
							$citysql="select SQL_CACHE VALUE FROM CITY_NEW where VALUE like '$City_Res%'";
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
				$sql.="HEIGHT BETWEEN '$Min_Height[0]' AND '$Max_Height[0]' AND ";
			 
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
					$sql.="HANDICAPPED IN ($Handicapped_Search) AND ";

				if(is_array($Rstatus) && !in_array("0",$Rstatus))
				{
					$insert_rstatus=implode("','",$Rstatus);
					$sql.="RES_STATUS IN ('$insert_rstatus') AND ";
				}
					
				if($Profile == "Y")
				{
					$insert_photo="Y";
					$sql.="HAVEPHOTO = 'Y' AND ";
				}
				elseif($Profile =="N")
				{
					$insert_photo="N";
					$sql.="HAVEPHOTO = 'N' AND ";
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
				if($search_eclassified==1)
                                {
					$sql.=" AND SUBSCRIPTION='D'";
					$subscription="D";
                                }

	 
				$insertquery="insert into SEARCHQUERY(ID,GENDER,CASTE,MTONGUE,LAGE,HAGE,HAVEPHOTO,MANGLIK,MSTATUS,HAVECHILD,LHEIGHT,HHEIGHT,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,OCCUPATION,COUNTRY_RES,CITY_RES,EDU_LEVEL,DATE,ONLINE,INCOME,SUBSCRIPTION) values ('','$Gender','" . str_replace("'","",$insert_caste) . "','" . str_replace("'","",$insert_mtongue) . "','$Min_Age','$Max_Age','$insert_photo','" . str_replace("'","",$insert_manglik) . "','" . str_replace("'","",$insert_mstatus) . "','" . str_replace("'","",$insert_children) . "','$Min_Height[0]','$Max_Height[0]','" . str_replace("'","",$insert_btype) . "','" . str_replace("'","",$insert_complexion) . "','" . str_replace("'","",$insert_diet) . "','" . str_replace("'","",$insert_smoke) . "','" . str_replace("'","",$insert_drink) . "','" . str_replace("'","",$Handicapped_Search) . "','" . str_replace("'","",$insert_occupation) . "','" . str_replace("'","",$insertCountry) . "','" . str_replace("'","",$insertCity) . "','" . str_replace("'","",$insert_edu) . "',now(),'$searchonline','" . str_replace("'","",$insert_income) . "','$subscription')";
		
				mysql_query_decide($insertquery) or logError($ERROR_STRING,$insertquery,"ShowErrTemplate");
				
				$searchid=mysql_insert_id_js();
				
				$searchchecksum=md5($searchid) . "i" . $searchid;
		
				if(!$j)
					$j=0;
				
				if($FRESHNESS)
					$sql.= "order by ENTRY_DT desc limit $j,$PAGELEN";
				else 
					$sql.= "order by MOD_DT desc limit $j,$PAGELEN";

				//mysql_close($db);
				$db=connect_slave();
	
				$result=mysql_query_decide($sql) or logError("$ERROR_STRING",$sql,"ShowErrTemplate");
				
				$sql="select FOUND_ROWS() as cnt";
				$resultcount=mysql_query_decide($sql) or logError("$ERROR_STRING",$sql,"ShowErrTemplate");
				
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
				
				searchBar($Gender,$Religion,$Mtongue,$Min_Age,$Max_Age,$Profile,$Caste);
	
				if($insert_photo=="Y")
					$smarty->assign("PHOTOTITLE","Advanced Search");
		
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("SEARCHCHECKSUM",$searchchecksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("head.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
				
					$smarty->display("alliance_results.htm");
		      	}
		      	
			// if partner profile
			if($FLAG=="partner" || $SAVE_PARTNER=="Y")                	
			{
				$sql_p = "SELECT PARTNERID FROM JPARTNER WHERE PROFILEID='$profileid'";
				$result_p=mysql_query_decide($sql_p,$db) or logError($ERROR_STRING,$sql_p);

				// if partner profile exists
				if ($myrow_p=mysql_fetch_row($result_p))
				{
					$column_one =$myrow_p[0];

					// function to update filter criteria if partner profile is changed
					check_filter($profileid,$column_one,$Min_Age,$Max_Age,$MSTATUS_ARRAY,$Caste,$Country_Residence);

					$sql_pu = " UPDATE JPARTNER SET  GENDER='$Gender', CHILDREN='$Has_Children_Partner', LAGE='$Min_Age',  HAGE='$Max_Age',  LHEIGHT='$Min_Height[0]',  HHEIGHT='$Max_Height[0]',  HANDICAPPED='$Handicapped_Partner',  DATE='".date("Y-m-d")."'  WHERE PARTNERID='$column_one'";

					mysql_query_decide($sql_pu,$db) or logError($ERROR_STRING,$sql_pu);
					
					$PARTNER_TABLES=array("PARTNER_BTYPE","PARTNER_MANGLIK","PARTNER_MSTATUS","PARTNER_DRINK","PARTNER_SMOKE","PARTNER_COMP","PARTNER_CASTE","PARTNER_MTONGUE","PARTNER_OCC","PARTNER_COUNTRYRES","PARTNER_CITYRES","PARTNER_RES_STATUS","PARTNER_ELEVEL","PARTNER_DIET");
					
					for($partner_count=0;$partner_count<count($PARTNER_TABLES);$partner_count++)
					{
						$sql_delete="DELETE FROM $PARTNER_TABLES[$partner_count] WHERE PARTNERID='$column_one'";
						mysql_query_decide($sql_delete) or logError($ERROR_STRING,$sql_delete,"ShowErrTemplate");
					}
					
					// function to do the inserts in partner profile tables
					partner_insert($column_one,$BODYTYPE_ARRAY,$MANGLIK_ARRAY,$MSTATUS_ARRAY,$DRINK_ARRAY,$SMOKE_ARRAY,$COMPLEXION_ARRAY,$Caste,$Mtongue,$Occupation,$Country_Residence,$City_India,$Rstatus,$Education_Level,$DIET_ARRAY);

				} 
				// if partner profile does not exist
				else
				{
					$sql="INSERT INTO JPARTNER ( PROFILEID , GENDER , CHILDREN ,LAGE, HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,DATE) VALUES ('$profileid', '$Gender' , '$Has_Children_Partner' ,'$Min_Age' , '$Max_Age' , '$Min_Height[0]' , '$Max_Height[0]' , '$Handicapped_Partner' ,'".date("Y-m-d")."')";
					mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql); 
					
					$column_one=mysql_insert_id_js();
					
					partner_insert($column_one,$BODYTYPE_ARRAY,$MANGLIK_ARRAY,$MSTATUS_ARRAY,$DRINK_ARRAY,$SMOKE_ARRAY,$COMPLEXION_ARRAY,$Caste,$Mtongue,$Occupation,$Country_Residence,$City_India,$Rstatus,$Education_Level,$DIET_ARRAY);
				
				}//BRACKET CLOSE FOR CONDITION IN EDIT PARTNER OR INPUT PARTNER
				
				if($FLAG=="partner")
				{	
					$smarty->assign("CHECKSUM",$checksum);
					$smarty->assign("HEAD",$smarty->fetch("head.htm"));
					$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
					$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
					$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
					$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
					$smarty->display("confirm_partnerprofile.htm");
				}
			}//BRACKET CLOSE FOR CONDITION IN SEARCH OR PARTNER PROFILE
		}//BRACKET CLOSE FOR ELSE-CONDITION IN IS_ERROR
 	}
 	else
 	{         
        //*************CODE TO DISPLAY FORM FOR THE FIRST TIME BEFORE SUBMIT********        
		// if partner profile	
		$smarty->assign("G",'');
		if ($FLAG=="partner")
		{
			$sql_p = "SELECT PARTNERID FROM JPARTNER WHERE PROFILEID='$profileid'";
			$result_p=mysql_query_decide($sql_p,$db) or logError($ERROR_STRING,$sql_p);
                  	
			if(mysql_num_rows($result_p) > 0)
			{
				$myrow_p= mysql_fetch_array($result_p);
				$Partnerid=$myrow_p['PARTNERID'];
			}
			else 
				$Partnerid="";
                
			// free the recordset  		
			mysql_free_result($result_p);
		}
		
		//*****CODE TO DISPLAY ALREADY EXISTING VALUE IN DB FOR PARTNER PROFILE***
		if ($Partnerid!="" && $FLAG=="partner")
		{
			$sql = "SELECT GENDER, CHILDREN, LAGE, HAGE, LHEIGHT, HHEIGHT, HANDICAPPED FROM JPARTNER WHERE PROFILEID='$profileid' ";
			
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			$myrow=mysql_fetch_array($result);
			
			$gender=$myrow['GENDER'];
			$children=$myrow['CHILDREN'];
			$lage=$myrow['LAGE']; 				
			$hage=$myrow['HAGE'];
			$lheight=$myrow['LHEIGHT'];
			$hheight=$myrow['HHEIGHT'];
			$handicapped=$myrow['HANDICAPPED'];

			$smarty->assign("G",$gender);
			
			$sql = "SELECT CASTE from PARTNER_CASTE where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$caste_value[]=$myrow['CASTE'];
			}
			
			mysql_free_result($result);
			
			if( count( $caste_value ) )
			{
				$smarty->assign("caste",create_dd($caste_value,"Caste"));
			}	
			else             
			{       
				$smarty->assign("f_caste","1");
				$smarty->assign("caste",create_dd("","Caste"));
			} 
                   
			$sql = "SELECT MTONGUE from PARTNER_MTONGUE where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$mtongue_value[]=$myrow['MTONGUE'];
			}
			
			mysql_free_result($result);
			
			if( count( $mtongue_value ) )
			{
				$smarty->assign("mtongue",create_dd($mtongue_value,"Mtongue"));
			} 
			else
			{
				$smarty->assign("f_mtongue","1");
				$smarty->assign("mtongue",create_dd("","Mtongue"));
			}        

			$sql = "SELECT OCC from PARTNER_OCC where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$occ_value[]=$myrow['OCC'];
			}
			
			mysql_free_result($result);
			
			if(count($occ_value))
			{
				$smarty->assign("occupation",create_dd($occ_value,"Occupation"));
			}
			else
			{
				$smarty->assign("f_occupation","1");
				$smarty->assign("occupation",create_dd("","Occupation"));
			}


			$sql = "SELECT COUNTRYRES from PARTNER_COUNTRYRES where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$country_value[]=$myrow['COUNTRYRES'];
			}
			
			mysql_free_result($result);
			
			if(count($country_value))
			{
				$smarty->assign("country_residence",create_dd($country_value,"Country_Residence"));
			}	
			else
			{
				$smarty->assign("f_country","1");
				$smarty->assign("country_residence",create_dd("","Country_Residence"));
			}                  

			$sql = "SELECT CITYRES from PARTNER_CITYRES where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$city_value[]=$myrow['CITYRES'];
			}
			
			mysql_free_result($result);
			
			if(count($city_value))
			{
				$city_india=create_dd($city_value,"City_India");
				$city_usa=create_dd($city_value,"City_USA");
				$city_india .=  $city_usa;
				$smarty->assign("city_india",$city_india);
			}
			else
			{
				$smarty->assign("f_city","1");
				$city_india=create_dd("","City_India");
				$city_usa=create_dd("","City_USA");
				$city_india .=  $city_usa;
				$smarty->assign("city_india",$city_india);
			}

			$sql = "SELECT ELEVEL from PARTNER_ELEVEL where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				$education_value[]=$myrow['ELEVEL'];
			}
			
			mysql_free_result($result);
			
			if(count($education_value))
			{
				$smarty->assign("education_level",create_dd($education_value,"Education_Level"));
			}
			else
			{ 	
				$smarty->assign("f_education","1");
				$smarty->assign("education_level",create_dd("","Education_Level"));
			}
			
			$smarty->assign("maxheight",create_dd($hheight,"Height",1));
			$smarty->assign("minheight",create_dd($lheight,"Height"));
			$smarty->assign("MAX_AGE",$hage);
			$smarty->assign("MIN_AGE",$lage);

			$sql = "SELECT MSTATUS from PARTNER_MSTATUS where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['MSTATUS']=='N')
					$smarty->assign("MARITAL_STATUS2","y");
				
				if($myrow['MSTATUS']=='W')
					$smarty->assign("MARITAL_STATUS3","y");
				
				if($myrow['MSTATUS']=='D')
					$smarty->assign("MARITAL_STATUS4","y");
				
				if($myrow['MSTATUS']=='S')
					$smarty->assign("MARITAL_STATUS5","y"); 
				
				if($myrow['MSTATUS']=='O')
					$smarty->assign("MARITAL_STATUS6","y");
			}

			mysql_free_result($result);
			
			$sql = "SELECT MANGLIK from PARTNER_MANGLIK where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['MANGLIK']=='M')
					$smarty->assign("MANGLIK_STATUS2","y");
				if($myrow['MANGLIK']=='N')
					$smarty->assign("MANGLIK_STATUS3","y");
				if($myrow['MANGLIK']=='D')
					$smarty->assign("MANGLIK_STATUS4","y");
			}
			
			mysql_free_result($result);
			
			$sql = "SELECT BTYPE from PARTNER_BTYPE where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
            
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['BTYPE']=='1')
					$smarty->assign("BODY_TYPE2","y");
				if($myrow['BTYPE']=='2')
					$smarty->assign("BODY_TYPE3","y");
				if($myrow['BTYPE']=='3')
					$smarty->assign("BODY_TYPE4","y");
				if($myrow['BTYPE']=='4')
					$smarty->assign("BODY_TYPE5","y");
			}
			
			mysql_free_result($result);
			
			$sql = "SELECT COMP from PARTNER_COMP where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
            
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['COMP']=='1')
					$smarty->assign("COMPLEXION2","y");
				if($myrow['COMP']=='2')
					$smarty->assign("COMPLEXION3","y");
				if($myrow['COMP']=='3')
					$smarty->assign("COMPLEXION4","y");
				if($myrow['COMP']=='4')
					$smarty->assign("COMPLEXION5","y");
				if($myrow['COMP']=='5')
					$smarty->assign("COMPLEXION6","y");
			}
			
			mysql_free_result($result);
			
			$sql = "SELECT SMOKE from PARTNER_SMOKE where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
            
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['SMOKE']=='Y')
					$smarty->assign("SMOKE2","y");
				if($myrow['SMOKE']=='N')
					$smarty->assign("SMOKE3","y");
				if($myrow['SMOKE']=='O')
					$smarty->assign("SMOKE4","y");
			}
			
			mysql_free_result($result);
			
			$sql = "SELECT DRINK from PARTNER_DRINK where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['DRINK']=='Y')
					$smarty->assign("DRINK2","y");
				if($myrow['DRINK']=='N')
					$smarty->assign("DRINK3","y");
				if($myrow['DRINK']=='O')
					$smarty->assign("DRINK4","y");
			}

			mysql_free_result($result);
			
			if($children=='N')
				$smarty->assign("CHILDREN2","y");
			if($children=='Y' || $children=='YT' || $children=='YS')
				$smarty->assign("CHILDREN3","y");
			
			if($handicapped=='')
				$smarty->assign("HANDICAPPED1","y");
			elseif($handicapped=='N')
				$smarty->assign("HANDICAPPED2","y");
			else
				$smarty->assign("HANDICAPPED3","y");
			
			$sql = "SELECT DIET from PARTNER_DIET where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow['DIET']=='V')
					$smarty->assign("DIET2","y");
				if($myrow['DIET']=='N')
					$smarty->assign("DIET3","y");
			}

			mysql_free_result($result);
			
			$sql = "SELECT RES_STATUS from PARTNER_RES_STATUS where PARTNERID= '$Partnerid'";
			$result=mysql_query_decide($sql,$db) or logError($ERROR_STRING,$sql);
			
			if(mysql_num_rows($result)>0)
			{
				while($myrow=mysql_fetch_array($result))
				{
					if($myrow['RES_STATUS']=='0')
						$smarty->assign("r0","1");
					if($myrow['RES_STATUS']=='1')
						$smarty->assign("r1","1");
					if($myrow['RES_STATUS']=='2')
						$smarty->assign("r2","1");
					if($myrow['RES_STATUS']=='3')
						$smarty->assign("r3","1");
					if($myrow['RES_STATUS']=='4')
						$smarty->assign("r4","1");
					if($myrow['RES_STATUS']=='5')
						$smarty->assign("r5","1");
				}
			}
			else 
			$smarty->assign("r0", 1);
			 
			mysql_free_result($result);
			
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
			
			$smarty->display("alliance_search.htm");	
		}
		// if partner profile does not exist or it is advance search
		elseif($Partnerid=="") 
		{
			//**CODE TO DISPLAY FORM FOR THE FIRST TIME WHEN RECORD DO NOT EXIST****
			// this section will be run for both partner profile as well as advanced search provided the person is logged in
			if($data)
			{
				$gender=$data['GENDER']; 
				
				if($gender=='M')
					$G='F';
				else
				{
					// if girl is logged in, give option to search on income
					$smarty->assign("SHOWINCOME","1");
					$smarty->assign("income",create_dd($Income,"Income"));
					$G='M';
				}

				$smarty->assign("G",$G);
				
				$sql_age="select AGE from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
				$result_age=mysql_query_decide($sql_age) or logError($ERROR_STRING,$sql_age);
				
				$age_row=mysql_fetch_array($result_age);

				if($gender=="M")
				{
					$smarty->assign("MIN_AGE",$age_row["AGE"]-5);
					$smarty->assign("MAX_AGE",$age_row["AGE"]);
				}
				else 
				{
					$smarty->assign("MIN_AGE",$age_row["AGE"]);
					$smarty->assign("MAX_AGE",$age_row["AGE"]+5);
				}
			}

			$city_india=create_dd($City_India,"City_India");
			$city_usa=create_dd($City_Usa,"City_USA");
			$city_india .=  $city_usa;

			$smarty->assign("f_occupation","1");
			$smarty->assign("f_mtongue","1");
			$smarty->assign("f_caste","1");
			$smarty->assign("f_country","1");
			$smarty->assign("f_city","1");
			$smarty->assign("f_education","1");
			$smarty->assign("f_income","1");
			
			// set residency status to all
			$smarty->assign("r0", 1);
			
			$smarty->assign("city_india",$city_india);
			$smarty->assign("education_level",create_dd("","Education_Level"));
			$smarty->assign("maxheight",create_dd("","Height",1));
			$smarty->assign("minheight",create_dd("","Height"));
			$smarty->assign("country_residence",create_dd("","Country_Residence"));
			$smarty->assign("occupation",create_dd("","Occupation"));
			$smarty->assign("mtongue",create_dd("","Mtongue"));
			$smarty->assign("caste",create_dd("","Caste"));
			                                                
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                                                                                     
			$smarty->display("alliance_search.htm");
	 	}
	}
} 
else
{
	TimedOut(); 
}

function partner_insert($column_one,$BTYPEARRAY,$MANGLIKARRAY,$MSTATUSARRAY,$DRINKARRAY,$SMOKEARRAY,$COMPLEXIONARRAY,$Caste,$Mtongue,$Occupation,$Country_Residence,$City_India,$Rstatus,$Education_Level,$DIET_ARRAY)
{
	global $ERROR_STRING;
	
	if(is_array($BTYPEARRAY))
	{
	 	foreach ($BTYPEARRAY as $value)
		{
			$sql="INSERT INTO PARTNER_BTYPE (PARTNERID , BTYPE) VALUES ('$column_one' , '$value')";
		
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
		}
	}

	if(is_array($MANGLIKARRAY))
	{
	 	foreach ($MANGLIKARRAY as $value)
	 	{
			$sql="INSERT INTO PARTNER_MANGLIK (PARTNERID , MANGLIK) VALUES ('$column_one' , '$value')";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($MSTATUSARRAY))
	{
		foreach ($MSTATUSARRAY as $value)
	 	{
	 		$sql="INSERT INTO PARTNER_MSTATUS (PARTNERID , MSTATUS) VALUES ('$column_one' , '$value')";
	 	 	mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($DRINKARRAY))
	{
	 	foreach ($DRINKARRAY as $value)
		{
			$sql="INSERT INTO PARTNER_DRINK (PARTNERID , DRINK) VALUES ('$column_one' , '$value')";
	 		mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
		}
	}
        
	if(is_array($SMOKEARRAY))
	{
	 	foreach ($SMOKEARRAY as $value)
		{	
	 		$sql="INSERT INTO PARTNER_SMOKE (PARTNERID , SMOKE) VALUES ('$column_one' , '$value')";
	 		mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($COMPLEXIONARRAY))
	{
	 	foreach ($COMPLEXIONARRAY as $value)
		{
	 		$sql="INSERT INTO PARTNER_COMP (PARTNERID , COMP) VALUES ('$column_one' , '$value')";
	 		mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Caste))
	{
	 	foreach ($Caste as $value)
	 	{
			if($value=="All")
				break;
				
		 	$sql="INSERT INTO PARTNER_CASTE (PARTNERID , CASTE) VALUES ('$column_one' , $value)";
	 		mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Mtongue))
	{
	 	foreach ($Mtongue as $value)
		{
			if($value=="All")
				break;
				
			$sql="INSERT INTO PARTNER_MTONGUE (PARTNERID , MTONGUE) VALUES ('$column_one' , $value)";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Occupation))
	{
	 	foreach ($Occupation as $value)
	 	{
		 	if($value=="All")
				break;
				
			$sql="INSERT INTO PARTNER_OCC (PARTNERID , OCC) VALUES ('$column_one' , $value)";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Country_Residence))
	{
		foreach ($Country_Residence as $value)
	 	{
			if($value=="All")
				break;
				
			$sql="INSERT INTO PARTNER_COUNTRYRES (PARTNERID , COUNTRYRES) VALUES ('$column_one' , $value)";
		 	mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($City_India))
	{
	 	foreach ($City_India as $value)
	 	{
			if($value=="All")
				break;
	        	
	 		$sql="INSERT INTO PARTNER_CITYRES (PARTNERID , CITYRES) VALUES ('$column_one' , '$value')";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Rstatus))
	{
	 	foreach ($Rstatus as $value)
	 	{
			if($value=="0")
				break;
	        	
	 		$sql="INSERT INTO PARTNER_RES_STATUS (PARTNERID , RES_STATUS) VALUES ('$column_one' , $value)";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

	if(is_array($Education_Level))
	{
	 	foreach ($Education_Level as $value)
	 	{
			if($value=="All")
				break;
	        	
	 		$sql="INSERT INTO PARTNER_ELEVEL (PARTNERID , ELEVEL) VALUES ('$column_one' , $value)";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
	}

 	if(is_array($DIET_ARRAY))
	{
	 	foreach ($DIET_ARRAY as $value)
	 	{
			$sql="INSERT INTO PARTNER_DIET (PARTNERID , DIET) VALUES ('$column_one' , '$value')";
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	 	}
 	}
}

function check_filter($profileid,$column_one,$Min_Age,$Max_Age,$MSTATUSARRAY,$Caste,$Country_Residence)
{
	global $ERROR_STRING;
	
	$sql="select * from FILTERS where PROFILEID='$profileid'";
	$resultfilter=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
	
	// if filter exists
	if(mysql_num_rows($resultfilter) > 0)
	{
		$filterrow=mysql_fetch_array($resultfilter);
		
		if($filterrow["AGE"]=="Y")
		{
			$sql="SELECT LAGE,HAGE FROM JPARTNER WHERE PARTNERID='$column_one'";
			$result=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_array($result);
			$lage1=$myrow['LAGE'];
			$hage1=$myrow['HAGE'];
			
			if($lage1!=$Min_Age || $hage1!=$Max_Age)
				$age_cond="N";
				
			mysql_free_result($result);
		}
		
		if($filterrow["MSTATUS"]=="Y")
		{
			$sql="SELECT MSTATUS FROM PARTNER_MSTATUS WHERE PARTNERID='$column_one'";
			$result=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
			while($myrow=mysql_fetch_array($result))
			{
				$mstatus1[]=$myrow['MSTATUS'];
			}
            
			if(is_array($mstatus1) && is_array($MSTATUSARRAY))
			{
				if(count(array_diff($mstatus1,$MSTATUSARRAY)) > 0 || count(array_diff($MSTATUSARRAY,$mstatus1)) > 0)
					$mstatus_cond="N";
			}
			else 
				$mstatus_cond="N";

			mysql_free_result($result);
		}

		if($filterrow["RELIGION"]=="Y")
		{
			$sql="SELECT CASTE FROM PARTNER_CASTE WHERE PARTNERID='$column_one'";
			$result=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
			while($myrow=mysql_fetch_array($result))
			{
				$religion1[]=$myrow['CASTE'];
			}
            
			if(is_array($religion1) && is_array($Caste))
			{
				if(count(array_diff($religion1,$Caste)) > 0 || count(array_diff($Caste,$religion1)) > 0)
					$religion_cond="N";
			}
			else 
				$religion_cond="N";

			mysql_free_result($result);
		}

		if($filterrow["COUNTRY_RES"]=="Y")
		{
			$sql="SELECT COUNTRYRES FROM PARTNER_COUNTRYRES WHERE PARTNERID='$column_one'";
			$result=mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
			while($myrow=mysql_fetch_array($result))
			{
				$country1[]=$myrow['COUNTRYRES'];
			}
            
			if(is_array($country1) && is_array($Country_Residence))
			{
				if(count(array_diff($country1,$Country_Residence)) > 0 || count(array_diff($Country_Residence,$country1)) > 0)
					$country_cond="N";
			}
			else 
				$country_cond="N";

			mysql_free_result($result);
		}

		if($age_cond=="N" || $religion_cond=="N" || $mstatus_cond=="N" || $country_cond=="N")
		{
			$sql="UPDATE FILTERS SET";
			if($age_cond=="N")
				$sql .= " AGE='N',";
		
			if($religion_cond=="N")
				$sql .= " RELIGION='N',";
		
			if($mstatus_cond=="N")
				$sql .= " MSTATUS='N',";
		
			if($country_cond=="N")
				$sql .= " COUNTRY_RES='N',";
                	
			$sql=substr($sql,0,strlen($sql)-1);
			
			$sql .= " WHERE PROFILEID='$profileid'";
			
			mysql_query_decide($sql) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
		}
	}

	mysql_free_result($resultfilter);
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
