<?php
/****************************************************************************************************************************
*	FILENAME      : seo_edit_matrimonial.php
*	INCLUDED      : connect.inc
*			functions used : 
*			authenticated($checksum) : for the authentication of the user logged in based on the cid generated 
*			label_select()           : generates label of a particular value from its respective table.
*
*	DESCRIPTION   : Edit the fields of the table newjs.MATRIMONIAL except Gender , Field and Community ones.
*
****************************************************************************************************************************/

/***********************************************************************************************************************
	CHANGED BY	: SHAKTI SRIVASTAVA
	CHANGE DATE	: 23 JUNE, 2005
	REASON		: To add and edit two new fields (SOURCE and CAPTION) that were added for SEO
***********************************************************************************************************************/
	
	include("connect.inc");	

	if(authenticated($cid))
	{
		if($submit)
		{	
			if($flag=="ADD")
			{
				if($caste)
				{
					$fld="RELIGION";
					$VAL=$caste;
				}
				else if($city)
				{
					$fld="CITY";
					$VAL=$city;
				}
				else if($country)
				{
					$fld="COUNTRY";
					$VAL=$country;
				}
				else if($mtongue)
				{
					$fld="MTONGUE";
					$VAL=$mtongue;
				}
			
				$filename=JsConstants::$docRoot."/matrimonials/".$NAME_OF_DIR;

				$sql_dir="SELECT DIRECTORY FROM newjs.NEW_COMMUNITY WHERE DIRECTORY='$NAME_OF_DIR'";
				$res_dir=mysql_query_decide($sql_dir);
				$row_dir=mysql_fetch_array($res_dir);
				$dir=$row_dir['DIRECTORY'];

				if(file_exists($filename)||$dir)
					echo $filename." already exists";
				else
				{
					$sql="INSERT INTO newjs.NEW_COMMUNITY VALUES('','$fld','$NAME','$VAL','$TITLE','$DESCRIPTION','$KEYWORD','$LOGO','$NAME_OF_DIR','$NAME_OF_COM','$SOURCE','$CAPTION')";
					$res=mysql_query_decide($sql) or die("Error while inserting data into NEW_COMMUNITY. ".mysql_error_js());
					$msg= " Record Updated<br>  ";
     	                                $msg .="<a href=\"seo_matrimonialdisplay.php?cid=$cid\">";
        	                        $msg .="Continue </a>";
     
     	                                $smarty->assign("MSG",$msg);
	                                $smarty->display("jsadmin_msg.tpl");

				}
			}
			else
			{
			 	$sql  = "UPDATE newjs.MATRIMONIAL SET TITLE = '$MOD_TITLE' , DESCRIPTION = '$MOD_DESCRIPTION' ,KEYWORD = '$MOD_KEYWORD' , NAME = '$MOD_NAME' , LOGO_STRING = '$MOD_LOGO_STRING',NAME_OF_COMMUNITY='$MOD_NAME_OF_COMMUNITY',SOURCE='$MOD_SOURCE', CAPTION='$MOD_CAPTION'  WHERE ID ='$ID'";
		 		$res = mysql_query_decide($sql) or die(mysql_error_js());
	
				$msg= " Record Updated<br>  ";
	        		$msg .="<a href=\"seo_matrimonialdisplay.php?cid=$cid\">";
		        	$msg .="Continue </a>";
	
			        $smarty->assign("MSG",$msg);
        			$smarty->display("jsadmin_msg.tpl");
			}
		}
		else
		{    
			if($flag=="ADD")  
			{
				$sql_rel="SELECT VALUE,LABEL FROM newjs.CASTE";
				$res_sql_rel=mysql_query_decide($sql_rel);
				while($row_rel=mysql_fetch_array($res_sql_rel))
				{
					$REL[]=array("VAL"=>$row_rel[0],"LAB"=>$row_rel[1]);
				}
				$smarty->assign("REL",$REL);

				$sql_city="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
				$res_sql_city=mysql_query_decide($sql_city);
				while($row_city=mysql_fetch_array($res_sql_city))
				{
					$city[]=array("VAL"=>$row_city[0],"LAB"=>$row_city[1]);
				}

				$sql_city_us="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
				$res_city_us=mysql_query_decide($sql_city_us);
				while($row_city_us=mysql_fetch_array($res_city_us))
				{
					$city[]=array("VAL"=>$row_city_us[0],"LAB"=>$row_city_us[1]);
				}
				$smarty->assign("CITY",$city);

				$sql_country="SELECT VALUE,LABEL FROM newjs.COUNTRY";
				$res_country=mysql_query_decide($sql_country);
				while($row_country=mysql_fetch_array($res_country))
				{
					$ctry[]=array("VAL"=>$row_country[0],"LAB"=>$row_country[1]);
				}
				$smarty->assign("CTRY",$ctry);

				$sql_mt="SELECT VALUE,LABEL FROM newjs.MTONGUE";
				$res_mt=mysql_query_decide($sql_mt);
				while($row_mt=mysql_fetch_array($res_mt))
				{
					$mt[]=array("VAL"=>$row_mt[0],"LAB"=>$row_mt[1]);
				}
				$smarty->assign("MT",$mt);
                                $smarty->assign("cid",$cid);
				$smarty->assign("flag",$flag);
				$smarty->display("seo_add_matrimonial.htm");
			}
			else
			{
				 $commarr = array("MTONGUE"  => "MTONGUE",
                        		          "COUNTRY"  => "COUNTRY",
                                		  "CITY"     => "CITY_INDIA",
		                                  "RELIGION" => "CASTE");
	
				$sql = "SELECT GENDER , FLD , COMMUNITY , TITLE , DESCRIPTION , KEYWORD , NAME , LOGO_STRING,NAME_OF_COMMUNITY , SOURCE , CAPTION FROM newjs.MATRIMONIAL WHERE ID ='$ID'";
				$res = mysql_query_decide($sql);
				$row = mysql_fetch_array($res);
	
				$label = label_select($commarr[$row["FLD"]],$row["COMMUNITY"],'newjs');
                	        $COMMUNITY = $label[0];
	
				$smarty->assign('GENDER',$row["GENDER"]);
	        	        $smarty->assign('FLD',$row["FLD"]);
	        	        $smarty->assign('COMMUNITY',$COMMUNITY);
				$smarty->assign('TITLE',$row["TITLE"]);
				$smarty->assign('DESCRIPTION',$row["DESCRIPTION"]);
				$smarty->assign('KEYWORD',$row["KEYWORD"]);
				$smarty->assign('NAME',$row["NAME"]);
				$smarty->assign('LOGO_STRING',$row["LOGO_STRING"]);
				$smarty->assign("ID",$ID);
				$smarty->assign("cid",$cid);
				$smarty->assign("name",$name);
				$smarty->assign("NAME_OF_COM",$row["NAME_OF_COMMUNITY"]);
				$smarty->assign("SOURCE",$row["SOURCE"]);
				$smarty->assign("CAPTION",$row["CAPTION"]);
	
				$smarty->display("seo_edit_matrimonial.htm");
			}
		}
	}
	
	else
	{
		$msg="Your session has been timed out<br>  ";
	        $msg .="<a href=\"index.htm\">";
       		$msg .="Login again </a>";
	        $smarty->assign("MSG",$msg);
	        $smarty->display("jsadmin_msg.tpl");
	}	
?>
