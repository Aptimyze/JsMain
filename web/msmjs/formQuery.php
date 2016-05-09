<?php
include("connect.inc");
include("search.inc");

//**********************AUTHENTICATION ROUTINE STARTS HERE****************************
if(!getAuthenticationRoutine($cid))
{
        $smarty->display("msm_relogin.htm");
        die;
}
//********************AUTHENTICATION ROUTINE ENDS HERE***********************************
$smarty->assign("cid",$cid);
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));

	if($fsubmit)
	{
        	$is_error=0;
	        
               	//************  VALIDATIONS AND CHECKS -- START HERE********************
               	/*
          	if($Religion[0]=="")
           	{
          		$smarty->assign("check_religion","Y");
         	 	$is_error++;
           	}*/
           	
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
		if($Country_Birth[0]=="")
                {
                        $smarty->assign("check_countrybirth","Y");
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
      		if($Profile == "All")
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

                //Profile Incomplete validation
                if($Incomplete == "All")
                        $smarty->assign("INCOMPLETE1","y");
                elseif($Incomplete == "Y")
                        $smarty->assign("INCOMPLETE2","y");
                elseif($Incomplete == "N")
                        $smarty->assign("INCOMPLETE3","y");
                if(!$Incomplete)
                {
                        $smarty->assign("INCOMPLETE","y");
                }
                                                                                                 
                $smarty->assign("INCOMP","$Incomplete");


		//SHOWPHONE_RES validation
                if($Res == "All")
                        $smarty->assign("RES1","y");
                elseif($Res == "Y")
                        $smarty->assign("RES2","y");
                elseif($Res == "N")
                        $smarty->assign("RES3","y");
                if(!$Res)
                {
                        $smarty->assign("RES","y");
                }
                                                                                                 
                $smarty->assign("RESIDENCE","$Res");
                                                                                                 
                //SHOWPHONE_MOB validation
                if($Mob == "All")
                        $smarty->assign("MOB1","y");
                elseif($Mob == "Y")
                        $smarty->assign("MOB2","y");
                elseif($Mob == "N")
                        $smarty->assign("MOB3","y");
                if(!$Mob)
                {
                        $smarty->assign("MOB","y");
                }
                                                                                                 
                $smarty->assign("MOBILE","$Mob");
              
                //PAID MEMBER validation
                if($Paid == "All")
                        $smarty->assign("PAID1","y");
                elseif($Paid == "Y")
                        $smarty->assign("PAID2","y");
                elseif($Paid == "N")
                        $smarty->assign("PAID3","y");
                if(!$Paid)
                {
                        $smarty->assign("PAID","y");
                }
                                                                                                 
                $smarty->assign("PAID","$Paid");
 
		//MATCHPOINT MEMBER validation
                if($Matchpoint == "All")
                        $smarty->assign("MATCHPOINT1","y");
                elseif($Matchpoint == "Y")
                        $smarty->assign("MATCHPOINT2","y");
                elseif($Matchpoint == "N")
                        $smarty->assign("MATCHPOINT3","y");
                if(!$Matchpoint)
                {
                        $smarty->assign("MATCHPOINT","y");
                }

                $smarty->assign("MATCHPOINT","$Matchpoint");

                //Relation Validation
                if(count($Relation)==0)
                {
                        $smarty->assign("check_relation","Y");
                        $is_error++;
                }
                else
                {
                        foreach( $Relation as $value )
                                $smarty->assign("re{$value}", 1);
                }




 
               //******************VALIDATIONS AND CHECK -- ENDS********************************

        
               //*************** CHECK FOR ANY ERROR START- HERE**************************
		if($is_error > 0)
    		{
    			/*if($Religion=="")
	                	$smarty->assign("f_religion","0");
	          	elseif($Religion[0] != "All")
	                 	$smarty->assign("f_religion","0");
	          	else
	       		        $smarty->assign("f_religion","1");*/
	       		        
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

			if($Country_Birth=="")
                                $smarty->assign("b_country","0");
                        elseif($Country_Birth[0] != "All")
                                $smarty->assign("b_country","0");
                        else
                                $smarty->assign("b_country","1");
	                	
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
        		//$smarty->assign("religion",create_dd($Religion,"Religion"));
        		$smarty->assign("caste",create_dd($Caste,"Caste"));
        		$smarty->assign("mtongue",create_dd($Mtongue,"Mtongue"));
      		  	$smarty->assign("occupation",create_dd($Occupation,"Occupation"));
        		$smarty->assign("country_residence",create_dd($Country_Residence,"Country_Residence"));
                        $smarty->assign("country_birth",create_dd($Country_Birth,"Country_Residence"));        		
                        $smarty->assign("income",create_dd($Income,"Income"));

        		$city_india=create_dd($City_India,"City_India");
        		$city_usa=create_dd($City_Usa,"City_USA");
			$city_india .=  $city_usa;
			$smarty->assign("city_india",$city_india);
			
			$smarty->assign("education_level",create_dd($Education_Level,"Education_Level"));
			$smarty->assign("maxheight",create_dd($Max_Height,"Height",1));
			$smarty->assign("minheight",create_dd($Min_Height,"Height"));
		

                        $smarty->display("formQuery.htm"); 
		}
		// if no error 
		else
       	   	{ 
       	   		// if advanced search
//          		if($FLAG=="search" || $SAVE_PARTNER=="Y")
				$sql="SELECT SQL_CALC_FOUND_ROWS newjs.JPROFILE.PROFILEID, newjs.JPROFILE.PHONE_MOB FROM newjs.JPROFILE WHERE newjs.JPROFILE.PHONE_MOB!='' AND GET_SMS != 'N' AND ";

                	 	if($Gender=="M")
					$sql.= " GENDER='M' AND ";
				elseif($Gender=="F") 
					$sql.= " GENDER='F' AND ";
				else
					$sql.= " ";

                	 	
				/*if(is_array($Religion) && !in_array("All",$Religion))
				{
					$insert_religion=implode("','", $Religion);
					$sql.="RELIGION IN ('$insert_religion') AND ";
				}*/
					
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
				        			$citysql="select SQL_CACHE VALUE from newjs.CITY_NEW where VALUE like '$City_Res[$i]%'";
				        			$cityresult=mysql_query($citysql);
				        			
				        			while($cityrow=mysql_fetch_array($cityresult))
				        			{
				        				$city_india[]=$cityrow["VALUE"];
				        			}
				        			
				        			mysql_free_result($cityresult);
				        		}
							elseif(strstr($City_Res[$i],"Rest of"))
							{
								$country_india=1;
								$city_india[]=trim($City_Res[$i],"Rest of ");
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
			        			$citysql="select SQL_CACHE VALUE from newjs.CITY_NEW where VALUE like '$City_Res%'";
			        			$cityresult=mysql_query($citysql);
			        			
			        			while($cityrow=mysql_fetch_array($cityresult))
			        			{
			        				$city_india[]=$cityrow["VALUE"];
			        			}
			        			
			        			mysql_free_result($cityresult);
			        		}
						elseif(strstr($City_Res,"Rest of"))
						{
							$city_india[]=trim($City_Res,"Rest of ");
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
				
                                if(is_array($Country_Birth) && !in_array("All",$Country_Birth))
                                {
                                        $insert_country_b=implode("','", $Country_Birth);
                                        $sql.="COUNTRY_BIRTH IN ('$insert_country_b') AND ";
                                }
	
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
				
				$sql.="(AGE BETWEEN '$Min_Age' AND '$Max_Age') AND ";                  
				$sql.="(HEIGHT BETWEEN '$Min_Height[0]' AND '$Max_Height[0]') AND ";
			 
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
				
				if($Incomplete == "Y")
                                {
                                        $insert_incomplete="Y";
                                        $sql.="INCOMPLETE = 'Y' AND ";
                                }
                                elseif($Incomplete =="N")
                                {
                                        $insert_incomplete="N";
                                        $sql.="INCOMPLETE = 'N' AND ";
                                }
				
				if($Res == "Y")
                                {
                                        $insert_res="Y";
                                        $sql.="SHOWPHONE_RES = 'Y' AND ";
                                }
                                elseif($Res =="N")
                                {
                                        $insert_res="N";
                                        $sql.="SHOWPHONE_RES = 'N' AND ";
                                }

				if($Mob == "Y")
                                {
                                        $insert_mob="Y";
                                        $sql.="SHOWPHONE_MOB = 'Y' AND ";
                                }
                                elseif($Mob =="N")
                                {
                                        $insert_mob="N";
                                        $sql.="SHOWPHONE_MOB = 'N' AND ";
                                }


                                if($Paid == "Y")
                                {
                                        $insert_paid="Y";
                                        $sql.="SUBSCRIPTION !='' AND ";
                                }
                                elseif($Paid =="N")
                                {
                                        $insert_paid="N";
                                        $sql.="SUBSCRIPTION = '' AND ";
                                }

                                if($Matchpoint == "Y")
                                {
                                        $sql.="SOURCE = 'ofl_prof' AND ";
                                }
                                elseif($Matchpoint =="N")
                                {
                                        $sql.="SOURCE != 'ofl_prof' AND ";
                                }


                                if(is_array($Relation) && !in_array("0",$Relation))
                                {
                                        $insert_relation=implode("','",$Relation);
                                        $sql.="RELATION IN ('$insert_relation') AND ";
                                }

				if($Income[0]!="All" && isset($Income))
                                {       
                                        $income_array=implode("','",$Income);
                                        $sql.="INCOME IN ('$income_array')  AND ";
                                }


				if($Ntimes1!="All")
				{
					if($Ntimes1=="gt")
					{
						$sql.="NTIMES >='$Ntimes2'  AND ";
					}
					elseif($Ntimes1=="lt")
					{
						$sql.="NTIMES <='$Ntimes2'  AND ";
					}
					elseif($Ntimes1=="et")
					{
						$sql.="NTIMES='$Ntimes2'  AND ";
					}
				}

				if($entry_dt1!="" && $entry_dt2!="")
				{
					$sql.="(ENTRY_DT BETWEEN '$entry_dt1' AND  '$entry_dt2') AND ";
				}
				if($modify_dt1!="" && $modify_dt2!="")
                                {
                                        $sql.="(MOD_DT BETWEEN '$modify_dt1' AND  '$modify_dt2') AND ";
                                }
				if($lastlogin_dt1!="" && $lastlogin_dt2!="")
                                {
                                        $sql.="(LAST_LOGIN_DT BETWEEN '$lastlogin_dt1' AND  '$lastlogin_dt2') AND ";
                                }
				$sql.="ACTIVATED!='D' AND ";	

				/* Bug 45681					
				if($Type=='P')
				{
					$sql.="PROMO_MAILS='S' AND ";
				}
				elseif($Type=='S')
				{
					$sql.="SERVICE_MESSAGES='S' AND ";
				}
				*/
	
				$sql=substr($sql,0,-4);
				if(!$j)
					$j=0;

				//$sql = "SELECT newjs.JPROFILE.PROFILEID, newjs.JPROFILE.PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID<100";
	                 	$result=mysql_query($sql) or die("$sql".mysql_error());

				/********************/
				include "lib/SendMessage.class.php";
				$sendMessageObj = new SendMessage;
				$fileId = $sendMessageObj->insertFileDetail($sql);
				$fileName = 'file_'.$fileId.'.csv';
				while($row=mysql_fetch_array($result))
				{
					$mobile = $sendMessageObj->validateMobilePhone($row['PHONE_MOB']);
					if($mobile)
						$mobileData[$row['PROFILEID']] = $mobile;
				}
				if($mobileData)
					$isSaved = $sendMessageObj->createCSV($mobileData, $fileName, JsConstants::$alertDocRoot."/msmjs/tempCSV");
				if($isSaved)
				{
					$sendMessageObj->updateFileDetail($fileId, $fileName, count($mobileData));
					$smarty->assign('fileName', $fileName);
				}
				/******************************/
				$smarty->assign('fileName', $fileName);
				$count=count($mobileData);
				$smarty->assign('fileId',$fileId);
				$smarty->assign("cid",$cid);
				$smarty->assign("sql",$sql);
				$smarty->assign("count",$count);
				$smarty->assign('setMessageWidget',$smarty->fetch('setMessageWidget.htm'));
				$smarty->display("setMessage.htm");
	     	}//BRACKET CLOSE FOR ELSE-CONDITION IN IS_ERROR
 	}
 	else
 	{         
        $smarty->assign("username","$username");
        $city_india=create_dd($City_India,"City_India");
        $city_usa=create_dd($City_Usa,"City_USA");
        $city_india .=  $city_usa;
        $smarty->assign("f_occupation","1");
        $smarty->assign("f_mtongue","1");
        $smarty->assign("f_caste","1");
        //$smarty->assign("f_religion","1");
        $smarty->assign("f_country","1");
        $smarty->assign("b_country","1");
        $smarty->assign("f_city","1");
        $smarty->assign("f_education","1");

        // set residency status to all
        $smarty->assign("r0", 1);
        // set relation to all
        $smarty->assign("re0", 1);
        $smarty->assign("income",create_dd("","Income"));
        $smarty->assign("city_india",$city_india);
        $smarty->assign("education_level",create_dd("","Education_Level"));
        $smarty->assign("maxheight",create_dd("","Height",1));
        $smarty->assign("minheight",create_dd("","Height"));
        $smarty->assign("country_residence",create_dd("","Country_Residence"));
        $smarty->assign("country_birth",create_dd("","Country_Residence"));
        $smarty->assign("occupation",create_dd("","Occupation"));
        $smarty->assign("mtongue",create_dd("","Mtongue"));
        $smarty->assign("caste",create_dd("","Caste"));
        $smarty->display("formQuery.htm");			
	}



?>
