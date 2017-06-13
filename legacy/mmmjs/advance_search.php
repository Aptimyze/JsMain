<?php
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);

//for preventing timeout to maximum possible
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);
//for preventing timeout to maximum possible


include("connect.inc");
include("search.inc");
//$db=connect_db();
$smarty->assign("FLAG",$FLAG);

//**********************AUTHENTICATION ROUTINE STARTS HERE****************************

$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
//********************AUTHENTICATION ROUTINE ENDS HERE***********************************

$FINAL=$mailer_id."FINAL";

function rename_to_final($table,$FINAL)
{
        $sql1="rename table $table to $FINAL";
        mysql_query($sql1) or die("$sql1".mysql_error());

}

function get_final_table_two($table1,$table2,$condition,$FINAL)
{
        // create table OPEN
        $sql4="CREATE TABLE `$FINAL` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
        mysql_query($sql4) or die("$sql4".mysql_error());

	if($condition=="OR")
	{
echo "IN OR : ";
		// get union of two tables	
echo		$sql2="INSERT INTO $FINAL(PROFILEID) SELECT PROFILEID FROM $table1 UNION SELECT PROFILEID FROM $table2 ";
		mysql_query($sql2) or die("$sql2".mysql_error());

echo	        $sql2="drop table $table1";
	        mysql_query($sql2) or die("$sql2".mysql_error());

echo		$sql2="drop table $table2";
                mysql_query($sql2) or die("$sql2".mysql_error());

	}
	elseif($condition=="AND")
	{
echo "IN AND : ";
		//get intersection of two tables
echo		$sql2="INSERT INTO $FINAL(PROFILEID) SELECT $table1.PROFILEID FROM $table1 LEFT JOIN $table2 ON $table1.PROFILEID=$table2.PROFILEID WHERE $table2.PROFILEID IS NOT NULL; ";
                mysql_query($sql2) or die("$sql2".mysql_error());

echo		$sql2="drop table $table1";
                mysql_query($sql2) or die("$sql2".mysql_error());
                                                                                                 
echo            $sql2="drop table $table2";
                mysql_query($sql2) or die("$sql2".mysql_error());
	}
}


function get_final_table_three($table1,$table2,$table3,$condition1,$condition2,$FINAL)
{
echo "*********";
echo $table1;echo "\n";
echo $table2;echo "\n";
echo $table3;echo "\n";
echo $condition1;echo "\n";
echo $condition2;echo "\n";
echo $condition3;echo "\n";
echo "*********";

$sql4="CREATE TABLE `$FINAL` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
mysql_query($sql4) or die("$sql4".mysql_error());

        if($condition1=="OR")
        {
		if($condition2=="OR")
		{	
                	$sql3="INSERT INTO $FINAL(PROFILEID) SELECT PROFILEID FROM $table1 UNION SELECT PROFILEID FROM $table2 UNION SELECT PROFILEID FROM $table3";
                	mysql_query($sql3) or die("$sql3".mysql_error());
			
		        $sql3="drop table $table1 ";
		        mysql_query($sql3) or die("$sql3".mysql_error());

		        $sql3="drop table $table2 ";
		        mysql_query($sql3) or die("$sql3".mysql_error());
			
		        $sql3="drop table $table3 ";
		        mysql_query($sql3) or die("$sql3".mysql_error());
				
		}
		elseif($condition2=="AND")
		{
			$sql3="INSERT INTO TEMPFINAL(PROFILEID) SELECT PROFILEID FROM $table1 UNION SELECT PROFILEID FROM $table2 ";
                        mysql_query($sql3) or die("$sql3".mysql_error());
			
			$sql3="INSERT INTO $FINAL(PROFILEID) SELECT TEMPFINAL.PROFILEID FROM TEMPFINAL LEFT JOIN $table3 ON TEMPFINAL.PROFILEID=$table3.PROFILEID WHERE $table3.PROFILEID IS NOT NULL";
        	        mysql_query($sql3) or die("$sql3".mysql_error());

			$sql3="drop table $table1";
                	mysql_query($sql3) or die("$sql3".mysql_error());
                                                                                                 
                	$sql3="drop table $table2";
                	mysql_query($sql3) or die("$sql3".mysql_error());
	
			$sql3="drop table $table3";
                        mysql_query($sql3) or die("$sql3".mysql_error());	

		}
        }
        elseif($condition1=="AND")
        {
		if($condition2=="OR")
		{
			$sql3="INSERT INTO TEMPFINAL(PROFILEID) SELECT $table1.PROFILEID FROM $table1 LEFT JOIN $table2 ON $table1.PROFILEID=$table2.PROFILEID WHERE $table2.PROFILEID IS NOT NULL";
                        mysql_query($sql3) or die("$sql3".mysql_error());
			
			$sql3="INSERT INTO $FINAL(PROFILEID) SELECT PROFILEID FROM TEMPFINAL UNION SELECT PROFILEID FROM $table3 ";
                        mysql_query($sql3) or die("$sql3".mysql_error());

                        $sql3="drop table $table1";
                        mysql_query($sql3) or die("$sql3".mysql_error());
                                                                                                 
                        $sql3="drop table $table2";
                        mysql_query($sql3) or die("$sql3".mysql_error());
                                                                                                 
                        $sql3="drop table $table3";
                        mysql_query($sql3) or die("$sql3".mysql_error());

		}
		elseif($condition2=="AND")
		{
			$sql3="INSERT INTO TEMPFINAL(PROFILEID) SELECT $table1.PROFILEID FROM $table1 LEFT JOIN $table2 ON $table1.PROFILEID=$table2.PROFILEID WHERE $table2.PROFILEID IS NOT NULL";
                        mysql_query($sql3) or die("$sql3".mysql_error());


			$sql3="INSERT INTO $FINAL(PROFILEID) SELECT TEMPFINAL.PROFILEID FROM TEMPFINAL LEFT JOIN $table3 ON TEMPFINAL.PROFILEID=$table3.PROFILEID WHERE $table3.PROFILEID IS NOT NULL";
                        mysql_query($sql3) or die("$sql3".mysql_error());

                        $sql3="drop table $table1";
                        mysql_query($sql3) or die("$sql3".mysql_error());
                                                                                                 
                        $sql3="drop table $table2";
                        mysql_query($sql3) or die("$sql3".mysql_error());
                                                                                                 
                        $sql3="drop table $table3";
                        mysql_query($sql3) or die("$sql3".mysql_error());
		}
        }
$sql4="TRUNCATE TABLE TEMPFINAL";
mysql_query($sql4) or die("$sql4".mysql_error());
}

function save_open_contacts($oc1,$oc2)
{	
        // create table OPEN
        $sql4="CREATE TABLE `OPEN` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
        mysql_query($sql4) or die("$sql4".mysql_error());

        // insert the No. of times each guy has contacted and the contact is open
        $sql4="insert into OPEN(PROFILEID,RESID) select RECEIVER,count(*) as cnt from newjs.CONTACTS where TYPE='I' group by RECEIVER HAVING cnt ".$oc1." ".$oc2;
        mysql_query($sql4) or die("$sql4".mysql_error());

}


function save_initiated_contacts($ic1,$ic2)
{
        // create table INITIATED
        $sql5="CREATE TABLE `INITIATED` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
        mysql_query($sql5) or die("$sql5".mysql_error());
        
	// insert the No. of times each guy has contacted and the contact is open
	$sql5="insert into INITIATED(PROFILEID,RESID) select SENDER,count(*) as cnt from newjs.CONTACTS where TYPE='I' group by SENDER HAVING cnt ".$ic1." ".$ic2;
        mysql_query($sql5) or die("$sql5".mysql_error());
                                                                                                 
}


function save_accepted_contacts($ac1,$ac2)
{
        // create table ACCEPTED
        $sql6="CREATE TABLE `ACCEPTED` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
        mysql_query($sql6) or die("$sql6".mysql_error());

/**
Comment added by shiv : Apr 15,2005

this TEMP table cud be created by selecting everything from the above ACCEPTED table - as the datastructure is same , and it contains no data.

**/
        // create table TEMP
        $sql6="CREATE TABLE `TEMP` (
 `PROFILEID` mediumint(8)  NOT NULL default '',
 `RESID` int(11) NOT NULL default '0',
 KEY `CODE` (`PROFILEID`)
) TYPE=MyISAM";
        mysql_query($sql6) or die("$sql6".mysql_error());

	// insert the No. of times each guy has contacted and has been ACCEPTED
        $sql6="insert into ACCEPTED(PROFILEID,RESID) select RECEIVER,count(*) from newjs.CONTACTS where TYPE='A' group by RECEIVER";
        mysql_query($sql6) or die("$sql6".mysql_error());
        // insert the No. of times each guy has been contacted and has ACCEPTED
        $sql6="insert into ACCEPTED(PROFILEID,RESID) select SENDER,count(*) from newjs.CONTACTS where TYPE='A' group by SENDER";
        mysql_query($sql6) or die("$sql6".mysql_error());
        // take the sum of both sender and reciever
        $sql6="insert into TEMP(PROFILEID,RESID) select PROFILEID,sum(RESID) as acc from ACCEPTED group by  PROFILEID HAVING acc ".$ac1." ".$ac2;
        mysql_query($sql6) or die("$sql6".mysql_error());
        $sql6="drop table ACCEPTED";
        mysql_query($sql6) or die("$sql6".mysql_error());


/**
Comment added by shiv : Apr 15,2005

this rename query can be avoided if initially TEMP table was named ACCEPTED and vice versa.

**/
        $sql6="rename table TEMP to ACCEPTED";
        mysql_query($sql6) or die("$sql6".mysql_error());
                                                                                                 
}


$smarty->assign("cid",$cid);

if($FLAG=="search")
{
//	$profileid=$data["PROFILEID"];
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
		

                        $smarty->display("advance_search.htm"); 
		}
		// if no error 
		else
       	   	{ 
       	   		// if advanced search
//          		if($FLAG=="search" || $SAVE_PARTNER=="Y")
			if($FLAG=="search")
	    		{
				$sql="SELECT count(*) FROM ";

				if($oc1!="All" || $ic1!="All" || $ac1!="All")
					$sql.=" newjs.JPROFILE,mmmjs.$FINAL WHERE";
				else
					$sql.=" newjs.JPROFILE WHERE";

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
								$city_india[]=ltrim($City_Res[$i],"Rest of ");
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
							$city_india[]=ltrim($City_Res,"Rest of ");
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


                                if(is_array($Relation) && !in_array("0",$Relation))
                                {
                                        $insert_relation=implode("','",$Relation);
                                        $sql.="RELATION IN ('$insert_relation') AND ";
                                }

				if(is_array($Income) && !in_array("All",$Income))
                                {
					$incomeStr = implode(",",$Income);
                                        $sql.="INCOME IN($incomeStr)  AND ";
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
				$sql.="( ACTIVATED!='D' OR JSARCHIVED=1 ) AND ";	
			
				if($Type=='P')
				{
					$sql.="PROMO_MAILS='S' AND ";
				}
				elseif($Type=='S')
				{
					$sql.="SERVICE_MESSAGES='S' AND ";
				}
	
				// Query formation for the open,initiated and accepted contacts
				if($oc1!="All" || $ic1!="All" || $ac1!="All")
				{
					$sql.=" newjs.JPROFILE.PROFILEID=mmmjs.$FINAL.PROFILEID AND ";					
					if($oc1!="All" && $ic1=="All" && $ac1=="All")
					{
						save_open_contacts($oc1,$oc2);
						rename_to_final("OPEN",$FINAL);
					}
					elseif($oc1=="All" && $ic1!="All" && $ac1=="All")
					{
						save_initiated_contacts($ic1,$ic2);
						rename_to_final("INITIATED",$FINAL);
					}
					elseif($oc1=="All" && $ic1=="All" && $ac1!="All")
					{
						save_accepted_contacts($ac1,$ac2);
						rename_to_final("ACCEPTED",$FINAL);
					}
					elseif($oc1!="All" && $ic1!="All" && $ac1=="All")
					{
						save_open_contacts($oc1,$oc2);
						save_initiated_contacts($ic1,$ic2);
						get_final_table_two("OPEN","INITIATED",$cond1,$FINAL);
					}
					elseif($oc1!="All" && $ic1=="All" && $ac1!="All")
					{
						save_open_contacts($oc1,$oc2);
						save_accepted_contacts($ac1,$ac2);
						get_final_table_two("OPEN","ACCEPTED",$cond1,$FINAL);
					}
					elseif($oc1=="All" && $ic1!="All" && $ac1!="All")
					{
						save_initiated_contacts($ic1,$ic2);
                                                save_accepted_contacts($ac1,$ac2); 
                				get_final_table_two("INITIATED","ACCEPTED",$cond2,$FINAL);
					}
					elseif($oc1!="All" && $ic1!="All" && $ac1!="All")
					{
						save_open_contacts($oc1,$oc2);
						save_initiated_contacts($ic1,$ic2);
						save_accepted_contacts($ac1,$ac2);
                                                get_final_table_three("OPEN","INITIATED","ACCEPTED",$cond1,$cond2,$FINAL);	
					}
				}



				$sql=substr($sql,0,-4);
echo $sql;	 
				if(!$j)
					$j=0;
	                 	$result=mysql_query($sql) or die("$sql".mysql_error());
				$myrow=mysql_fetch_row($result);
				$count=$myrow[0];
	
//				$mailer_arr_clientname=get_subquery_mailers_clientname();
//				$mailer_arr_mailername=get_subquery_mailers_mailername();
//				$smarty->assign("mailer_arr_clientname",$mailer_arr_clientname);
//				$smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
				$smarty->assign("mailer_id",$mailer_id);
				$smarty->assign("cid",$cid);
				$smarty->assign("sql",$sql);
				$smarty->assign("count",$count);
				$smarty->display("save_search.htm");
		      	}
	     	}//BRACKET CLOSE FOR ELSE-CONDITION IN IS_ERROR
 	}
 	else
 	{         
		// if partner profile does not exist or it is advance search
               	if($Partnerid=="") 
               	{
                   	//**CODE TO DISPLAY FORM FOR THE FIRST TIME WHEN RECORD DO NOT EXIST****
                   	// this section will be run for both partner profile as well as advanced search provided the person is logged in
			if($data)
			{
				$gender=$data['GENDER']; 
				
                        	if($gender=='M')
                         		$G='F';
                        	else
                        		$G='M';
                        	
                        	$smarty->assign("G",$G);
                        
	                        
	                        $sql_age="select AGE from newjs.JPROFILE where PROFILEID='$profileid'";
	                	$result_age=mysql_query($sql_age) or die("$sql_age".mysql_error());//logError($ERROR_STRING,$sql_age);
	                	
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
                	//$smarty->assign("f_religion","1");                                          
	                $smarty->assign("f_country","1");
			$smarty->assign("b_country","1");
        	        $smarty->assign("f_city","1");
        	        $smarty->assign("f_education","1");
      	                
        	        // set residency status to all
        	        $smarty->assign("r0", 1);
        		// set relation to all
                        $smarty->assign("re0", 1);
			$income = getIncome();
                        //$smarty->assign("income",create_dd("","Income"));
                        $smarty->assign("income",$income);
                       	$smarty->assign("city_india",$city_india);
       	                $smarty->assign("education_level",create_dd("","Education_Level"));
                       	$smarty->assign("maxheight",create_dd("","Height",1));
       	                $smarty->assign("minheight",create_dd("","Height"));
       	                $smarty->assign("country_residence",create_dd("","Country_Residence"));
			$smarty->assign("country_birth",create_dd("","Country_Residence"));
       	                $smarty->assign("occupation",create_dd("","Occupation"));
       	                $smarty->assign("mtongue",create_dd("","Mtongue"));
       	                $smarty->assign("caste",create_dd("","Caste"));
       	                //$smarty->assign("religion",create_dd("","Religion"));


                        $mailer_arr_mailername=get_subquery_mailers_mailername();
                        $smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
			$smarty->assign("cid",$cid);                                             			 $smarty->display("advance_search.htm");
	 	}
	}
} 

// This function will give the list of all the mailers for which SUB_QUERY IS EMPTY

function get_subquery_mailers_clientname()
{
        $sql="SELECT MAILER_ID,CLIENT_NAME FROM MAIN_MAILER WHERE STATE='in'";
        $result=mysql_query($sql) or die("Could connect to mmm in search.php");
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row['MAILER_ID'], "client_name"=>$row['CLIENT_NAME']);        }
        return $arr;
}


function get_subquery_mailers_mailername()
{
        $sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='in' AND MAILER_FOR='J'";
        $result=mysql_query($sql) or die("Could connect to mmm in search.php");
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row['MAILER_ID'], "mailer_name"=>$row['MAILER_NAME']);       
	}
        return $arr;
}
                                                                                                 


?>
