<?php
include("connect.inc");
include("search.inc");
include ('ajax.inc.php');
//$db=connect_db();
$smarty->assign("FLAG",$FLAG);

// Instantiate the ajax object.  No parameters defaults requestURI to this page, method to POST,and debug to off
$ajax = new ajax("","ajax_",AJAX_DEFAULT_CHAR_ENCODING,false);

// Specify the PHP functions to wrap. The JavaScript wrappers will be named xajax_functionname
$ajax->registerFunction("populate_locality");

// Process any requests.  Because our requestURI is the same as our html page,
// this must be called before any headers or HTML output have been sent
$ajax->processRequests();
//$smarty->assign('ajax_javascript', $ajax->getJavascript(''));


//**********************AUTHENTICATION ROUTINE STARTS HERE****************************

$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $TM=getIST();
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
//********************AUTHENTICATION ROUTINE ENDS HERE***********************************


$smarty->assign("cid",$cid);

if($FLAG=="search")
{
//	$profileid=$data["PROFILEID"];
	if($fsubmit)
	{
		$db99=connect_db_99('property');
        $is_error=0;
	        
        //************  VALIDATIONS AND CHECKS -- START HERE********************

   		if($mailer_id=="")
        {
        	$smarty->assign("check_mailerid","Y");
           	$is_error++;
        }

		if($register_dt1 && $register_dt2)
		{
			$temp = explode("-",$register_dt1);
			$dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
			$temp = explode("-",$register_dt2);
			$dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

			if($dt1>$dt2)
			{
				$is_error++;
				$smarty->assign("check_registerdate","Y");
			}
		}
		
		if($modify_dt1 && $modify_dt2)
        {
         	$temp = explode("-",$modify_dt1);
            $dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
            $temp = explode("-",$modify_dt2);
            $dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

            if($dt1>$dt2)
            {
            	$is_error++;
                $smarty->assign("check_modifydate","Y");
            }
        }
		if($recipient_type == 'S'){
			if(!($seller_class_agent || $seller_class_builder || $seller_class_owner))
			{
				$is_error++;
				$smarty->assign("check_sellerclass","Y");
			}
		}
		

       //******************VALIDATIONS AND CHECK -- ENDS********************************

		
       //*************** CHECK FOR ANY ERROR START- HERE**************************
		if($is_error > 0)
    		{
			//smarty assign the usual dds and stuff
			if($city && $city[0]!='')
				$smarty->assign("deselect_cityall",1);
			$smarty->assign("CITY",create_dd($city,"city99"));
                        $smarty->assign("PROPERTY_TYPE",create_dd("","99property_type"));
            		$smarty->assign("BUYING_BUDGET",create_dd("","99buying_budget"));
			$mailer_arr_mailername=get_subquery_mailers_mailername();
           		$smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
			$smarty->assign("mailer_id",$mailer_id);
		        $smarty->assign("cid",$cid);
                        $smarty->assign("PROPERTY_TYPE",create_dd("","99property_type"));
			//smarty assign the posted variables
			$smarty->assign("register_dt1",$register_dt1);
			$smarty->assign("register_dt2",$register_dt2);
			$smarty->assign("modify_dt1",$modify_dt1);
			$smarty->assign("modify_dt2",$modify_dt2);
			$smarty->assign("screening",$screening);
			$smarty->assign("activated",$activated);
			$smarty->assign("sub_partners",$sub_partners);
			$smarty->assign("buyer_rescom",$buyer_rescom);
			$smarty->assign("buyer_preference_all",$buyer_preference_all);
			$smarty->assign("buyer_preference_buy",$buyer_preference_buy);
			$smarty->assign("buyer_preference_rent",$buyer_preference_rent);
			$smarty->assign("buyer_preference_lease",$buyer_preference_lease);
			$smarty->assign("buyer_preference_pg",$buyer_preference_pg);

			//the deselect flags are to unselect the 'All' or 'Doesn't matter' options
			if($buyer_prop_city && $buyer_prop_city[0]!='')
            			$smarty->assign("deselect_buyercityall",1);
           		$smarty->assign("BUYER_CITY",create_dd($buyer_prop_city,"city99"));

			if($buyer_property_type && $buyer_property_type[0]!='')
                                $smarty->assign("deselect_buyerpropertytypeall",1);
                        $smarty->assign("PROPERTY_TYPE",create_dd($buyer_property_type,"99property_type"));

			if($buyer_buying_budget && $buyer_buying_budget[0]!='')
				$smarty->assign("deselect_buyingbudgetall",1);
			$smarty->assign("BUYING_BUDGET",create_dd($buyer_buying_budget,"99buying_budget"));
			
			$smarty->assign("seller_rescom",$seller_rescom);
		    	$smarty->assign("seller_preference_all",$seller_preference_all);
		  	$smarty->assign("seller_preference_sell",$seller_preference_sell);
		    	$smarty->assign("seller_preference_rent",$seller_preference_rent);
		    	$smarty->assign("seller_preference_lease",$seller_preference_lease);
		    	$smarty->assign("seller_preference_pg",$seller_preference_pg);

			//a blank array is to be sent in cases like these to select the 'Doesn't matter' option

			if($seller_property_type && $seller_property_type[0]!='')
				$smarty->assign("deselect_sellerpropertytypeall",1);
			$smarty->assign("SELLER_PROPERTY_TYPE",create_dd($seller_property_type,"99property_type"));
			
			if($seller_prop_city && $seller_prop_city[0]!='')
            			$smarty->assign("deselect_sellerpropcityall",1);
          		$smarty->assign("SELLER_CITY",create_dd($seller_prop_city,"city99"));

			$smarty->assign("seller_register_dt1",$seller_register_dt1);
			$smarty->assign("seller_register_dt2",$seller_register_dt2);
			$smarty->assign("seller_modify_dt1",$seller_modify_dt1);
			$smarty->assign("seller_modify_dt2",$seller_modify_dt2);
			$smarty->assign("seller_screening",$seller_screening);
			$smarty->assign("seller_activated",$seller_activated);
			$smarty->assign("seller_incomplete",$seller_incomplete);

            $smarty->display("advance_search99_new.htm"); 
		}
		// if no error 
		else
       	   	{//NO ERROR, Go ahead and create the damn query 
			/*FLAGS TO INDICATE WHETHER WHERE CONDITION EXISTS FOR PROFILE, BUYER & SELLER TABLE respectively*/
			$isPROFILE=0;
			$isBUYER=0;
			$isSELLER=0;
//			$db99=connect_db_99('property');

/**************************************MAIN QUERY CREATION STARTS***************************************************/
			$sql = "";

			if($recipient_type=='B')	//intended recipients are buyers
			{
				$sqlb = "";

				if($sub_partners){
					$isBUYER++;
					$sqlb.= "MMM_BUYER.SUB_PARTNERS='$sub_partners' AND ";
				}
				if($sub_promo){
					$isBUYER++;
					$sqlb.= "MMM_BUYER.SUB_PROMO='$sub_promo' AND ";
				}
				if($buyer_rescom)
				{
					$isBUYER++;
					$sqlb.= "MMM_BUYER.RES_COM='$buyer_rescom' AND ";
				}

				if(!$buyer_preference_all)
				{
					if($buyer_preference_buy){
						$sqlb.= "((MMM_BUYER.PREFERENCE='B' AND ";
						{
							$isBUYER++;
							if(($budget_max==0) && ($budget_min==0)){
								$budget_max=9999999999;
								$budget_min=0;
								$sqlb.="MMM_BUYER.BUDGET_MIN >=$budget_min AND MMM_BUYER.BUDGET_MAX <=$budget_max";
							}
							elseif(($budget_min > 0) && ($budget_max==0)){
								if($budget_min==499999)
									$sqlb.="MMM_BUYER.BUDGET_MIN <$budget_min";
								else{
									$budget_max=9999999999;
									$sqlb.="MMM_BUYER.BUDGET_MIN <=$budget_max AND MMM_BUYER.BUDGET_MAX >= $budget_min";
								}
							}
							elseif(($budget_max > 0) && ($budget_min==0)){
								if($budget_min==499999)
									$sqlb.="MMM_BUYER.BUDGET_MAX <=$budget_max" ; 
								else
									$sqlb.="MMM_BUYER.BUDGET_MIN <=$budget_max AND MMM_BUYER.BUDGET_MAX >= $budget_min";
							}
							elseif(($budget_max) && ($budget_min))
								$sqlb.="MMM_BUYER.BUDGET_MIN >=$budget_min AND MMM_BUYER.BUDGET_MAX <=$budget_max";

							$sqlb.= ") ";
						}
					}

					if($buyer_preference_rent)
                                                $pref_list.="'R',";
					if($buyer_preference_lease)
                                                $pref_list.="'L',";
					if($buyer_preference_pg)
                                                $pref_list.="'P',";

					if(strlen($pref_list)>0)
					{
						$isBUYER++;
						if($buyer_preference_buy)
							$sqlb.="OR ";
						$pref_list = '('.substr($pref_list,0,strlen($pref_list)-1).')';

						if($buyer_preference_buy)
							$sqlb.= " MMM_BUYER.PREFERENCE IN$pref_list) AND ";	
						else
							$sqlb.= " MMM_BUYER.PREFERENCE IN$pref_list AND ";
					} 
					else{
						$isBUYER++;
						if($buyer_preference_buy)
							$sqlb.= ") AND ";
					}
				}

				if($buyer_prop_city && !in_array('',$buyer_prop_city))
				{
					$isBUYER++;
					$sqlb.= "(";
					for($i=0;$i<count($buyer_prop_city);$i++)
					{
						$sqlb.= "FIND_IN_SET($buyer_prop_city[$i],MMM_BUYER.PROP_CITY)>0 OR ";

						$sql1 = "SELECT LEVELID,VALUE,LABEL FROM locations.LOCATION WHERE VALUE='$buyer_prop_city[$i]'";
                                                $res1 = mysql_query($sql1,$db99) or die("$sql1".mysql_error());
                                                $row1 = mysql_fetch_array($res1);
                                                if($row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']=='3' || preg_match('/\(All\)/',$row1['LABEL'])==1)   //This is a state
						{
							//exceptional cases of other cities
                                                        if($row1['VALUE']=='216')       //this has no subcities
                                                        {
                                                                $sqlb.= "FIND_IN_SET($buyer_prop_city[$i],MMM_BUYER.PROP_CITY)>0 OR ";
                                                        }

							$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
                                                        $res2 = mysql_query($sql2,$db99) or die("$sql2".mysql_error());
                                                        while($row2 = mysql_fetch_array($res2))
                                                        {
                                                                $sqlb.= "FIND_IN_SET($row2[VALUE],MMM_BUYER.PROP_CITY)>0 OR ";
                                                        }

                                                        if($row1['VALUE']=='1')       //special case for delhi, add cities like noida, faridabad etc. to the list
                                                        {
                                                                $sqlb.= "FIND_IN_SET('7',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('222',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('8',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('9',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('10',MMM_BUYER.PROP_CITY)>0 OR ";
                                                        }
                                                }
					}
					$sqlb = substr($sqlb,0,strlen($sqlb)-4);
					$sqlb.= ") AND ";
				}

				if($buyer_property_type && !in_array('',$buyer_property_type))
				{
					global $PROPERTY_TYPE;
					$isBUYER++;
					$sqlb.= "(";
                                        for($i=0;$i<count($buyer_property_type);$i++)
                                        {
						if($buyer_property_type[$i]=='R')	//all residential
						{
							for($j=2;$j<=8;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
							}
						}
						else if($buyer_property_type[$i]=='C')    //all commercial
                                                {
                                                        for($j=10;$j<=24;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
                                                        }
                                                }
						else if($buyer_property_type[$i]=='L')    //all land
                                                {
                                                        for($j=26;$j<=29;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
                                                        }
                                                }
						else
	                                                $sqlb.= "FIND_IN_SET($buyer_property_type[$i],MMM_BUYER.PROPERTY_TYPE)>0 OR ";
                                        }
                                        $sqlb = substr($sqlb,0,strlen($sqlb)-4);
                                        $sqlb.= ") AND ";
				}
				
				
				if($buyer_country_source == 'Y')
	  	         	{
					$isBUYER++;
		                	$sqlb .= "MMM_BUYER.COUNTRY_CODE <> 'IN' AND ";
			        }
				else
				{
					$isBUYER++;
				}

			}	//buyer query ends

			if($recipient_type=='S')	//intended recipients are sellers
			{
				$sqls = "";

				if($sub_partners){
					$isSELLER++;
					$sqls.= "MMM_SELLER.SUB_PARTNERS='$sub_partners' AND ";
				}
				if($sub_promo){
					$isSELLER++;
					$sqls.= "MMM_SELLER.SUB_PROMO='$sub_promo' AND ";
				}
				
				if($city)	//if some city selected
				{
					if(!in_array('',$city))	//something other than 'All' selected
					{
						$isSELLER++;
						$sqls.="MMM_SELLER.CITY IN ";
						$city_list = "(";
						for($i=0;$i<count($city);$i++)
						{
							if($city[$i]=='')
								continue;
							$sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='$city[$i]'";
							$res1 = mysql_query($sql1,$db99) or die("$sql1".mysql_error());
							$row1 = mysql_fetch_array($res1);
							 if($row1['LEVELID']=='3' || $row1['VALUE']=='216' || $row1['VALUE']=='221' || preg_match('/\(All\)/',$row1['LABEL'])==1)	//This is a state
							{
								//exceptional cases of other cities
								if($row1['VALUE']=='216')	//this has no subcities
								{
									$city_list.= "$city[$i],";
								}
								$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
								$res2 = mysql_query($sql2,$db99) or die("$sql2".mysql_error());
								while($row2 = mysql_fetch_array($res2))
								{
									$city_list.= "$row2[VALUE],";
								}
	
								if($row1['VALUE']=='1')	//special case for delhi, add cities like noida, faridabad etc. to the list
								{
									$city_list.= "7,222,8,9,10,";
								}
							}
							else	//This is a city
							{
								$city_list.= "$city[$i],";
							}
						}
						$city_list = substr($city_list,0,strlen($city_list)-1);
						$city_list.=")";
						$sqls.="$city_list AND ";
					}
				}
								
				if(!($seller_class_agent && $seller_class_builder && $seller_class_owner))	//1 or 2 classes selected
				{
					$isSELLER++;
					$temp="MMM_SELLER.CLASS IN(";
					if($seller_class_agent)
						$temp.="'A',";
					if($seller_class_builder)
                                                $temp.="'B',";
					if($seller_class_owner)
                                                $temp.="'O',";

					$temp = substr($temp,0,strlen($temp)-1).') AND ';
					$sqls.=$temp;
				}

				if($seller_rescom)
				{
					$isSELLER++;
					$sqls.="MMM_SELLER.RES_COM='$seller_rescom' AND ";
				}

				if(!$seller_preference_all)
                                {
                                        if($seller_preference_sell)
                                                $pref_list.="'S',";
                                        if($seller_preference_rent)
                                                $pref_list.="'R',";
                                        if($seller_preference_lease)
                                                $pref_list.="'L',";
                                        if($seller_preference_pg)
                                                $pref_list.="'P',";

                                        if(strlen($pref_list)>0)
                                        {
						$isSELLER++;
                                                $pref_list = '('.substr($pref_list,0,strlen($pref_list)-1).')';
                                                $sqls.= "MMM_SELLER.PREFERENCE IN$pref_list AND ";
                                        }
                                }

				if($seller_property_type && !in_array('',$seller_property_type))
                                {
                                        global $PROPERTY_TYPE;
					$isSELLER++;
                                        $sqls.= "MMM_SELLER.PROPERTY_TYPE IN(";
                                        for($i=0;$i<count($seller_property_type);$i++)
                                        {
                                                if($seller_property_type[$i]=='R')       //all residential
                                                {
                                                        for($j=2;$j<=8;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqls.= "'$temp',";
                                                        }
                                                }
                                                else if($seller_property_type[$i]=='C')    //all commercial
                                                {
                                                        for($j=10;$j<=24;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqls.= "'$temp',";
                                                        }
                                                }
                                                else if($seller_property_type[$i]=='L')    //all land
                                                {
                                                        for($j=26;$j<=29;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqls.= "'$temp',";
                                                        }
                                                }
                                                else
                                                        $sqls.= "'$seller_property_type[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }


				if($seller_prop_city && !in_array('',$seller_prop_city))
                                {
					$isSELLER++;
                                        $sqls.= "MMM_SELLER.SELLER_CITY IN(";
                                        for($i=0;$i<count($seller_prop_city);$i++)
                                        {
                                                $sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='$seller_prop_city[$i]'";
                                                $res1 = mysql_query($sql1,$db99) or die("$sql1".mysql_error());
                                                $row1 = mysql_fetch_array($res1);
                                                if($row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']==3 || preg_match('/\(All\)/',$row1['LABEL'])==1)   //This is a state
                                                {
                                                        //exceptional cases of other cities
                                                        if($row1['VALUE']=='216')       //this has no subcities
                                                        {
                                                                $sqls.= "'$seller_prop_city[$i]',";
                                                        }

                                                        $sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
                                                        $res2 = mysql_query($sql2,$db99) or die("$sql2".mysql_error());
                                                        while($row2 = mysql_fetch_array($res2))
                                                        {
                                                                $sqls.= "'$row2[VALUE]',";
                                                        }

                                                        if($row1['VALUE']=='1')       //special case for delhi, add cities like noida, faridabad etc. to the list
                                                        {
                                                                $sqls.= "'7','222','8','9','10',";
                                                        }
                                                }
						else
						{
							$sqls.= "'$seller_prop_city[$i]',";
						}
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }
                                
                                if($seller_country_source == 'Y')
                                {
                                	$isSELLER++;
                                	$sqls .= "MMM_SELLER.COUNTRY_CODE <> 'IN' AND ";
                                	
                                }
				else
				{
					$isSELLER++; 
				}
			}	//seller query ends

		}
		//remove ANDs
		if($isBUYER)
			$sqlb = substr($sqlb,0,strlen($sqlb)-5);
		if($isSELLER)
			$sqls = substr($sqls,0,strlen($sqls)-5);

		
		if($recipient_type=='B')
		{
			if($isBUYER)	//both profile and buyer criteria selected
			{
				$sql_final = "SELECT SQL_CACHE DISTINCT(PROFILEID) FROM mmm_99.MMM_BUYER WHERE MMM_BUYER.SCREENING = 'Y' AND MMM_BUYER.ACTIVATED = 'Y' AND ".$sqlb;
				if($sqlb=='')
					$sql_final = "SELECT SQL_CACHE DISTINCT(PROFILEID) FROM mmm_99.MMM_BUYER WHERE MMM_BUYER.SCREENING = 'Y' AND MMM_BUYER.ACTIVATED = 'Y'";
			}		
		}
		else if($recipient_type=='S')
		{
            if($isSELLER)      //both profile and seller criteria selected
            {
				$sql_final = "SELECT SQL_CACHE DISTINCT(PROFILEID) FROM mmm_99.MMM_SELLER WHERE MMM_SELLER.SCREENING = 'Y' AND MMM_SELLER.ACTIVATED = 'Y' AND ".$sqls;
				if($sqls=='')
					$sql_final = "SELECT SQL_CACHE DISTINCT(PROFILEID) FROM mmm_99.MMM_SELLER WHERE MMM_SELLER.SCREENING = 'Y' AND MMM_SELLER.ACTIVATED = 'Y'";
            }
		}

		//echo $sql_final;die;

            $result=mysql_query($sql_final,$db99) or die("$sql_final".mysql_error());
			$count=mysql_num_rows($result);

			$smarty->assign("mailer_id",$mailer_id);
			$smarty->assign("cid",$cid);
			$smarty->assign("sql",$sql_final);
			$smarty->assign("count",$count);
			$smarty->assign("mmm_split",$mmm_split?$mmm_split:'N');
			$smarty->display("save_search99.htm");
/**************************************MAIN QUERY CREATION ENDS***************************************************/
		mysql_close($db99);
 	}
 	else
 	{         
                	//**CODE TO DISPLAY FORM FOR THE FIRST TIME WHEN RECORD DO NOT EXIST****
			
			$smarty->assign("CITY",create_dd("","city99"));
			$smarty->assign("BUYER_CITY",create_dd("","city99"));
			$smarty->assign("SELLER_CITY",create_dd("","city99"));
			$smarty->assign("PROPERTY_TYPE",create_dd("","99property_type"));
			$smarty->assign("SELLER_PROPERTY_TYPE",create_dd("","99property_type"));
			$smarty->assign("BUYING_BUDGET",create_dd("","99buying_budget"));
            		$mailer_arr_mailername=get_subquery_mailers_mailername();
            		$smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
			$smarty->assign("cid",$cid);
			$smarty->assign("buyer_preference_buy","on");
			$smarty->assign("buyer_preference_rent","on");
			$smarty->assign("buyer_preference_lease","on");
			$smarty->assign("buyer_preference_pg","on");
           		$smarty->assign("seller_preference_all","on");
		        $smarty->assign("seller_class_agent","on");
			$smarty->assign("seller_class_builder","on");
			$smarty->assign("seller_class_owner","on");
			$smarty->display("advance_search99_new.htm");
 	}
}

// This function will give the list of all the mailers for which SUB_QUERY IS EMPTY


function get_subquery_mailers_mailername()
{
	global $db;
        $sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='in' AND MAILER_FOR='9'";
        $result=mysql_query($sql,$db) or die("Could connect to mmm in search.php");
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row['MAILER_ID'], "mailer_name"=>$row['MAILER_NAME']);        }
        return $arr;
}
                                                                                                 
/*
        Name            : populate_locality
        Description     : This function populates locality drop down for a particular city.
        Input           : $city: city under which all localities are to be populated
                          $buyer = Tells if the request is coming from buyer module
        Returns         : $text which can be a dropdown or null
*/

function populate_locality($city,$buyer="")
{
        $objResponse = new ajaxResponse();
        {
               	$sql="SELECT ID, NAME FROM property.LOCALITY WHERE ACTIVE='Y' AND CITY='$city' AND NAME NOT LIKE '%others%' ORDER BY NAME";

		$db99=connect_db_99('property');
                $res=mysql_query($sql,$db99) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		mysql_close($db99);
                if(mysql_num_rows($res))
                {
			if($buyer==1)	//request from buyer section
	                	$text="<select name=\"buyer_property_locality[]\" class=\"TextBox\" size=\"3\" multiple>";
			else
				$text="<select name=\"seller_property_locality[]\" class=\"TextBox\" size=\"3\" multiple>";

                        $text.="<option value=\"\" selected>All</option>";
                        while($row=mysql_fetch_array($res))
                        {
	                        $text.="<option value=\"$row[ID]\">$row[NAME]</option>";
                        }
                                $text.="</select>";
                }
		else
	        {
        	        $text="There are no localities defined for this city";
	        }
        }
	if($buyer==1)
	        $objResponse->addAssign("locality_div","innerHTML",$text);
	else
		$objResponse->addAssign("locality_div2","innerHTML",$text);
        return $objResponse;
}
?>
