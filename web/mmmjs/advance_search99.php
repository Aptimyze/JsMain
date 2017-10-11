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
$smarty->assign('ajax_javascript', $ajax->getJavascript(''));


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
		if($lastlogin_dt1 && $lastlogin_dt2)
                {
                        $temp = explode("-",$lastlogin_dt1);
                        $dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
                        $temp = explode("-",$lastlogin_dt2);
                        $dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

                        if($dt1>$dt2)
                        {
                                $is_error++;
                                $smarty->assign("check_lastlogindate","Y");
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

		if(!($seller_class_agent || $seller_class_builder || $seller_class_owner))
		{
			$is_error++;
			$smarty->assign("check_sellerclass","Y");
		}
		//check if either is non-numeric
		if(($seller_area_from && !is_numeric($seller_area_from)) || ($seller_area_to && !is_numeric($seller_area_to)))
		{
			$is_error++;
			$smarty->assign("check_sellerarea1","Y");
		}
		//check if max < min
		else if($seller_area_from && $seller_area_to && ($seller_area_from>$seller_area_to))
		{
			$is_error++;
			$smarty->assign("check_sellerarea2","Y");
		}
		
		if(($seller_pricesqft_from && !is_numeric($seller_pricesqft_from)) || ($seller_pricesqft_to && !is_numeric($seller_pricesqft_to)))
                {
                        $is_error++;
                        $smarty->assign("check_sellerpricesqft1","Y");
                }
                else if($seller_pricesqft_from && $seller_pricesqft_to && ($seller_pricesqft_from>$seller_pricesqft_to))
                {
                        $is_error++;
                        $smarty->assign("check_sellerpricesqft2","Y");
                }
		
		if(($seller_price_from && !is_numeric($seller_price_from)) || ($seller_price_to && !is_numeric($seller_price_to)))                 {
                        $is_error++;
                        $smarty->assign("check_sellerprice1","Y");
                }
                else if($seller_price_from && $seller_price_to && ($seller_price_from>$seller_price_to))
                {
                        $is_error++;
                        $smarty->assign("check_sellerprice2","Y");
                }

		if($seller_register_dt1 && $seller_register_dt2)
                {
                        $temp = explode("-",$seller_register_dt1);
                        $dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
                        $temp = explode("-",$seller_register_dt2);
                        $dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

                        if($dt1>$dt2)
                        {
                                $is_error++;
                                $smarty->assign("check_sellerregisterdate","Y");
                        }
                }

		if($seller_modify_dt1 && $seller_modify_dt2)
                {
                        $temp = explode("-",$seller_modify_dt1);
                        $dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
                        $temp = explode("-",$seller_modify_dt2);
                        $dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

                        if($dt1>$dt2)
                        {
                                $is_error++;
                                $smarty->assign("check_sellermodifydate","Y");
                        }
                }

		if($seller_expiry_dt1 && $seller_expiry_dt2)
                {
                        $temp = explode("-",$seller_expiry_dt1);
                        $dt1 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
                        $temp = explode("-",$seller_expiry_dt2);
                        $dt2 = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);

                        if($dt1>$dt2)
                        {
                                $is_error++;
                                $smarty->assign("check_sellerexpirydate","Y");
                        }
                }

		if(($seller_ntimes_from && !is_numeric($seller_ntimes_from)) || ($seller_ntimes_to && !is_numeric($seller_ntimes_to)))
		{
			$is_error++;
			$smarty->assign("check_sellerntimes1",1);
		}
		else if($seller_ntimes_from && $seller_ntimes_to && ($seller_ntimes_from>$seller_ntimes_to))
		{
			$is_error++;
                        $smarty->assign("check_sellerntimes2",1);
		}

		if(($seller_stimes_from && !is_numeric($seller_stimes_from)) || ($seller_stimes_to && !is_numeric($seller_stimes_to)))
                {
                        $is_error++;
                        $smarty->assign("check_sellerstimes1",1);
                }
                else if($seller_stimes_from && $seller_stimes_to && ($seller_stimes_from>$seller_stimes_to))
                {
                        $is_error++;
                        $smarty->assign("check_sellerstimes2",1);
                }

	

               //******************VALIDATIONS AND CHECK -- ENDS********************************

        
               //*************** CHECK FOR ANY ERROR START- HERE**************************
		if($is_error > 0)
    		{
			//smarty assign the usual dds and stuff
			if($city && $city[0]!='')
				$smarty->assign("deselect_cityall",1);
			$smarty->assign("CITY",create_dd($city,"city99"));

		        $smarty->assign("SALES_EXEC",create_dd("","99sales_executives"));

			if($buyer_income && $buyer_income[0]!='')
				$smarty->assign("deselect_incomeall",1);
                        $smarty->assign("INCOME",create_dd($buyer_income,"99monthly_income"));

                        $smarty->assign("PROPERTY_TYPE",create_dd("","99property_type"));
                        $smarty->assign("BUYING_BUDGET",create_dd("","99buying_budget"));
                        $smarty->assign("MONTHLY_BUDGET",create_dd("","99monthly_budget"));
                        $smarty->assign("AREA_RANGE",create_dd("","99area_range"));
                        $smarty->assign("TRANSACT_TYPE",create_dd("","99transact_type"));
                        $smarty->assign("OWN_TYPE",create_dd("","99owntype"));
                        $smarty->assign("FURNISH",create_dd("","99furnishing"));
                        $smarty->assign("FACING",create_dd("","99facing"));
                        $smarty->assign("AGE",create_dd("","99age"));
                        $smarty->assign("FEATURES",create_dd("","99features"));
                        $smarty->assign("FEATURES_COMMERCIAL",create_dd("","99features_commercial"));

                        $mailer_arr_mailername=get_subquery_mailers_mailername();
                        $smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
			$smarty->assign("mailer_id",$mailer_id);
                        $smarty->assign("cid",$cid);

			//smarty assign the posted variables
			$smarty->assign("recipient_type",$recipient_type);
			$smarty->assign("register_dt1",$register_dt1);
			$smarty->assign("register_dt2",$register_dt2);
			$smarty->assign("lastlogin_dt1",$lastlogin_dt1);
			$smarty->assign("lastlogin_dt2",$lastlogin_dt2);
			$smarty->assign("modify_dt1",$modify_dt1);
			$smarty->assign("modify_dt2",$modify_dt2);
			$smarty->assign("source",$source);
			$smarty->assign("screening",$screening);
			$smarty->assign("activated",$activated);
			$smarty->assign("sub_partners",$sub_partners);
			$smarty->assign("sub_newsletter",$sub_newsletter);
//			$smarty->assign("sub_promo",$sub_promo);
			$smarty->assign("sub_propalert",$sub_propalert);
			$smarty->assign("sub_service",$sub_service);
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

			if($buyer_monthly_budget && $buyer_monthly_budget[0]!='')
                                $smarty->assign("deselect_monthlybudgetall",1);
                        $smarty->assign("MONTHLY_BUDGET",create_dd($buyer_monthly_budget,"99monthly_budget"));

			if($buyer_area_range && $buyer_area_range[0]!='')
                                $smarty->assign("deselect_areaall",1);
                        $smarty->assign("AREA_RANGE",create_dd($buyer_area_range,"99area_range"));

			$smarty->assign("seller_class_agent",$seller_class_agent);
			$smarty->assign("seller_class_builder",$seller_class_builder);
			$smarty->assign("seller_class_owner",$seller_class_owner);
			$smarty->assign("seller_rescom",$seller_rescom);
                        $smarty->assign("seller_preference_all",$seller_preference_all);
                        $smarty->assign("seller_preference_sell",$seller_preference_sell);
                        $smarty->assign("seller_preference_rent",$seller_preference_rent);
                        $smarty->assign("seller_preference_lease",$seller_preference_lease);
                        $smarty->assign("seller_preference_pg",$seller_preference_pg);

			//a blank array is to be sent in cases like these to select the 'Doesn't matter' option
			$smarty->assign("seller_listing",(count($seller_listing)>0?$seller_listing:array('')));

			if($seller_property_type && $seller_property_type[0]!='')
				$smarty->assign("deselect_sellerpropertytypeall",1);
			$smarty->assign("SELLER_PROPERTY_TYPE",create_dd($seller_property_type,"99property_type"));

			if($seller_prop_city && $seller_prop_city[0]!='')
                                $smarty->assign("deselect_sellerpropcityall",1);
                        $smarty->assign("SELLER_CITY",create_dd($seller_prop_city,"city99"));

			$smarty->assign("seller_area_from",$seller_area_from);
			$smarty->assign("seller_area_to",$seller_area_to);
			$smarty->assign("seller_pricesqft_from",$seller_pricesqft_from);
                        $smarty->assign("seller_pricesqft_to",$seller_pricesqft_to);
			$smarty->assign("seller_price_from",$seller_price_from);
                        $smarty->assign("seller_price_to",$seller_price_to);

			if($seller_transact_type && $seller_transact_type[0]!='')
				$smarty->assign("deselect_sellertransactall",1);
			$smarty->assign("TRANSACT_TYPE",create_dd($seller_transact_type,"99transact_type"));

			if($seller_owntype && $seller_owntype[0]!='')
				$smarty->assign("deselect_sellerowntypeall",1);
			$smarty->assign("OWN_TYPE",create_dd($seller_owntype,"99owntype"));

			$smarty->assign("seller_bedroom_num",(count($seller_bedroom_num)>0?$seller_bedroom_num:array('')));
			$smarty->assign("seller_bathroom_num",(count($seller_bathroom_num)>0?$seller_bathroom_num:array('')));

			if($seller_furnish && $seller_furnish[0]!='')
                                $smarty->assign("deselect_sellerfurnishall",1);
                        $smarty->assign("FURNISH",create_dd($seller_furnish,"99furnishing"));

			if($seller_facing && $seller_facing[0]!='')
                                $smarty->assign("deselect_sellerfacingall",1);
                        $smarty->assign("FACING",create_dd($seller_facing,"99facing"));

                        if($seller_age && $seller_age[0]!='')                 
		                $smarty->assign("deselect_sellerageall",1);
                        $smarty->assign("AGE",create_dd($seller_age,"99age"));

			$smarty->assign("seller_floor",(count($seller_floor)>0?$seller_floor:array('')));
			$smarty->assign("seller_totalfloors",(count($seller_totalfloors)>0?$seller_totalfloors:array('')));

                        if($seller_features && $seller_features[0]!='')                      
		        	$smarty->assign("deselect_resfeaturesall",1);
                        $smarty->assign("FEATURES",create_dd($seller_features,"99features"));

			if($seller_features_commercial && $seller_features_commercial[0]!='')                      
			        $smarty->assign("deselect_comfeaturesall",1);
                        $smarty->assign("FEATURES_COMMERCIAL",create_dd($seller_features_commercial,"99features_commercial"));	

			$smarty->assign("seller_register_dt1",$seller_register_dt1);
			$smarty->assign("seller_register_dt2",$seller_register_dt2);
			$smarty->assign("seller_modify_dt1",$seller_modify_dt1);
			$smarty->assign("seller_modify_dt2",$seller_modify_dt2);
			$smarty->assign("seller_expiry_dt1",$seller_expiry_dt1);
			$smarty->assign("seller_expiry_dt2",$seller_expiry_dt2);
			$smarty->assign("seller_source",$seller_source);
			$smarty->assign("seller_ntimes_from",$seller_ntimes_from);
			$smarty->assign("seller_ntimes_to",$seller_ntimes_to);
			$smarty->assign("seller_stimes_from",$seller_stimes_from);
                        $smarty->assign("seller_stimes_to",$seller_stimes_to);
			$smarty->assign("seller_havephoto",(count($seller_havephoto)>0?$seller_havephoto:array('')));
			$smarty->assign("seller_screening",$seller_screening);
			$smarty->assign("seller_activated",$seller_activated);
			$smarty->assign("seller_incomplete",$seller_incomplete);

                        $smarty->display("advance_search99.htm"); 
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

			if($city)	//if some city selected
			{
				if(!in_array('',$city))	//something other than 'All' selected
				{
					$isPROFILE++;
					$sql.="PROFILE.CITY IN ";
					$city_list = "(";
					for($i=0;$i<count($city);$i++)
					{
						if($city[$i]=='')
							continue;
						$sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='$city[$i]'";
						$res1 = mysql_query($sql1,$db99) or die("$sql1".mysql_error());
						$row1 = mysql_fetch_array($res1);
						 if($row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']==3 || preg_match('/\(All\)/',$row1['LABEL'])==1)	//This is a state
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
								$city_list.= "$row2['VALUE'],";
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
					$sql.="$city_list AND ";
				}
			}

			if($register_dt1 && $register_dt2)
			{
				$isPROFILE++;
				$sql.="PROFILE.REGISTER_DATE BETWEEN '$register_dt1 00:00:00' AND '$register_dt2 23:59:59' AND ";
			}
			else
			{
				if($register_dt1)
				{
					$isPROFILE++;
					$sql.="PROFILE.REGISTER_DATE >= '$register_dt1 00:00:00' AND ";
				}
				if($register_dt2)
        	                {
					$isPROFILE++;
                	                $sql.="PROFILE.REGISTER_DATE <= '$register_dt2 23:59:59' AND ";
                        	}
			}

			if($lastlogin_dt1 && $lastlogin_dt2)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.LAST_LOGIN_DT BETWEEN '$lastlogin_dt1 00:00:00' AND '$lastlogin_dt2 23:59:59' AND ";
                        }
			else
			{
				if($lastlogin_dt1)
        	                {
					$isPROFILE++;
                	                $sql.="PROFILE.LAST_LOGIN_DT >= '$lastlogin_dt1 00:00:00' AND ";
                        	}
	                        if($lastlogin_dt2)
        	                {
					$isPROFILE++;
                	                $sql.="PROFILE.LAST_LOGIN_DT <= '$lastlogin_dt2 23:59:59' AND ";
	                        }
			}

			if($modify_dt1 && $modify_dt2)
			{
				$isPROFILE++;
				$sql.="PROFILE.MODIFY_DATE BETWEEN '$modify_dt1 00:00:00' AND '$modify_dt2 23:59:59' AND ";
			}
			else
			{			
				if($modify_dt1)
        	                {
					$isPROFILE++;
                	                $sql.="PROFILE.MODIFY_DATE >= '$modify_dt1 00:00:00' AND ";
                        	}
	                        if($modify_dt2)
        	                {
					$isPROFILE++;
                	                $sql.="PROFILE.MODIFY_DATE <= '$modify_dt2 23:59:59' AND ";
        	                }
			}
	
			if($source=='I')	//Internal profile
			{
				$isPROFILE++;
				$sql.="SUBSTRING(PROFILE.SOURCE,1,3)='OP-' AND ";
			}
			else if($source=='E')        //External profile
                        {
				$isPROFILE++;
                                $sql.="SUBSTRING(PROFILE.SOURCE,1,3)<>'OP-' AND ";
                        }

			if($screening)
			{
				$isPROFILE++;
				$sql.="PROFILE.SCREENING = '$screening' AND ";
			}
			if($activated)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.ACTIVATED = '$activated' AND ";
                        }
			if($sub_partners)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.SUB_PARTNERS = '$sub_partners' AND ";
                        }
			if($sub_newsletter)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.SUB_NEWSLETTER = '$sub_newsletter' AND ";
                        }
			if($sub_promo)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.SUB_PROMO = 'Y' AND ";
                        }
			if($sub_propalert)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.SUB_PROPALERT = '$sub_propalert' AND ";
                        }
			if($sub_service)
                        {
				$isPROFILE++;
                                $sql.="PROFILE.SUB_SERVICE = '$sub_service' AND ";
                        }

			if($recipient_type=='B')	//intended recipients are buyers
			{
				$sqlb = "";

				$sqlb.="BUYER.UNSUBSCRIBE <> 'Y' AND ";
				$sqlb.="BUYER.SHAREINFO='Y' AND ";

				if($buyer_income && !in_array('',$buyer_income))	//buyer income selected
				{
					$isPROFILE++;
					$income_list = implode(",",$buyer_income);
					$income_list = '('.$income_list.')';
					$sql.="PROFILE.MONTHLY_INCOME IN $income_list AND ";
				}

				if($buyer_rescom)
				{
					$isBUYER++;
					$sqlb.= "BUYER.RES_COM='$buyer_rescom' AND ";
				}

				if(!$buyer_preference_all)
				{
					if($buyer_preference_buy)
						$pref_list.="'B',";
					if($buyer_preference_rent)
                                                $pref_list.="'R',";
					if($buyer_preference_lease)
                                                $pref_list.="'L',";
					if($buyer_preference_pg)
                                                $pref_list.="'P',";

					if(strlen($pref_list)>0)
					{
						$isBUYER++;
						$pref_list = '('.substr($pref_list,0,strlen($pref_list)-1).')';
						$sqlb.= "BUYER.PREFERENCE IN$pref_list AND ";
					} 
				}

				if($buyer_prop_city && !in_array('',$buyer_prop_city))
				{
					$isBUYER++;
					$sqlb.= "(";
					for($i=0;$i<count($buyer_prop_city);$i++)
					{
						$sqlb.= "FIND_IN_SET($buyer_prop_city[$i],BUYER.PROP_CITY)>0 OR ";

						$sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='$buyer_prop_city[$i]'";
                                                $res1 = mysql_query($sql1,$db99) or die("$sql1".mysql_error());
                                                $row1 = mysql_fetch_array($res1);
                                                if($$row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']==3 || preg_match('/\(All\)/',$row1['LABEL'])==1)   //This is a state
						{
							//exceptional cases of other cities
                                                        if($row1['VALUE']=='216')       //this has no subcities
                                                        {
                                                                $sqlb.= "FIND_IN_SET($buyer_prop_city[$i],BUYER.PROP_CITY)>0 OR ";
                                                        }

							$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']"'";
                                                        $res2 = mysql_query($sql2,$db99) or die("$sql2".mysql_error());
                                                        while($row2 = mysql_fetch_array($res2))
                                                        {
                                                                $sqlb.= "FIND_IN_SET($row2[VALUE],BUYER.PROP_CITY)>0 OR ";
                                                        }

                                                        if($row1['VALUE']=='1')       //special case for delhi, add cities like noida, faridabad etc. to the list
                                                        {
                                                                $sqlb.= "FIND_IN_SET('7',BUYER.PROP_CITY)>0 OR FIND_IN_SET('222',BUYER.PROP_CITY)>0 OR FIND_IN_SET('8',BUYER.PROP_CITY)>0 OR FIND_IN_SET('9',BUYER.PROP_CITY)>0 OR FIND_IN_SET('10',BUYER.PROP_CITY)>0 OR ";
                                                        }
                                                }
					}
					$sqlb = substr($sqlb,0,strlen($sqlb)-4);
					$sqlb.= ") AND ";
				}
				if($buyer_property_locality && !in_array('',$buyer_property_locality))
                                {
					$isBUYER++;
                                        $sqlb.= "(";
                                        for($i=0;$i<count($buyer_property_locality);$i++)
                                        {
                                                $sqlb.= "FIND_IN_SET($buyer_property_locality[$i],BUYER.LOCALITY_ID)>0 OR ";
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
								$sqlb.= "FIND_IN_SET($temp,BUYER.PROPERTY_TYPE)>0 OR ";
							}
						}
						else if($buyer_property_type[$i]=='C')    //all commercial
                                                {
                                                        for($j=10;$j<=24;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqlb.= "FIND_IN_SET($temp,BUYER.PROPERTY_TYPE)>0 OR ";
                                                        }
                                                }
						else if($buyer_property_type[$i]=='L')    //all land
                                                {
                                                        for($j=26;$j<=29;$j++)
                                                        {
                                                                $temp = $PROPERTY_TYPE[$j]['VALUE'];
                                                                $sqlb.= "FIND_IN_SET($temp,BUYER.PROPERTY_TYPE)>0 OR ";
                                                        }
                                                }
						else
	                                                $sqlb.= "FIND_IN_SET($buyer_property_type[$i],BUYER.PROPERTY_TYPE)>0 OR ";
                                        }
                                        $sqlb = substr($sqlb,0,strlen($sqlb)-4);
                                        $sqlb.= ") AND ";
				}

				if($buyer_buying_budget && !in_array('',$buyer_buying_budget))
				{
					$isBUYER++;
					$sqlb.='BUYER.BUYING_BUDGET IN (';
					for($i=0;$i<count($buyer_buying_budget);$i++)
						$sqlb.= "$buyer_buying_budget[$i],";

					$sqlb = substr($sqlb,0,strlen($sqlb)-1);
                                        $sqlb.= ") AND ";
				}

				if($buyer_monthly_budget && !in_array('',$buyer_monthly_budget))
                                {
					$isBUYER++;
                                        $sqlb.='BUYER.MONTHLY_BUDGET IN (';
                                        for($i=0;$i<count($buyer_monthly_budget);$i++)
                                                $sqlb.= "$buyer_monthly_budget[$i],";

                                        $sqlb = substr($sqlb,0,strlen($sqlb)-1);
                                        $sqlb.= ") AND ";
                                }

				if($buyer_area_range && !in_array('',$buyer_area_range))
                                {
					$isBUYER++;
                                        $sqlb.='BUYER.AREA_RANGE IN (';
                                        for($i=0;$i<count($buyer_area_range);$i++)
                                                $sqlb.= "$buyer_area_range[$i],";

                                        $sqlb = substr($sqlb,0,strlen($sqlb)-1);
                                        $sqlb.= ") AND ";
                                }

			}	//buyer query ends

			if($recipient_type=='S')	//intended recipients are sellers
			{
				$sqls = "";

				if(!($seller_class_agent && $seller_class_builder && $seller_class_owner))	//1 or 2 classes selected
				{
					$isPROFILE++;
					$temp="PROFILE.CLASS IN(";
					if($seller_class_agent)
						$temp.="'A',";
					if($seller_class_builder)
                                                $temp.="'B',";
					if($seller_class_owner)
                                                $temp.="'O',";

					$temp = substr($temp,0,strlen($temp)-1).') AND ';
					$sql.=$temp;
				}

				if($seller_rescom)
				{
					$isSELLER++;
					$sqls.="SELLER.RES_COM='$seller_rescom' AND ";
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
                                                $sqls.= "SELLER.PREFERENCE IN$pref_list AND ";
                                        }
                                }

				if($seller_listing && !in_array('',$seller_listing))
				{
					$isSELLER++;
					$sqls.="SELLER.LISTING IN('".implode("','",$seller_listing)."') AND ";
				}

				if($seller_property_type && !in_array('',$seller_property_type))
                                {
                                        global $PROPERTY_TYPE;
					$isSELLER++;
                                        $sqls.= "SELLER.PROPERTY_TYPE IN(";
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
                                        $sqls.= "SELLER.CITY IN(";
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

				if($seller_property_locality && !in_array('',$seller_property_locality))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.LOCALITY_ID IN(";
                                        for($i=0;$i<count($seller_property_locality);$i++)
                                        {
                                                $sqls.= "'$seller_property_locality[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_area_from)
				{	
					$isSELLER++;
					$sqls.= "SELLER.SUPER_SQFT >= '$seller_area_from' AND ";
				}

				if($seller_area_to)
				{
                                        $sqls.= "SELLER.SUPER_SQFT <= '$seller_area_to' AND ";
					$isSELLER++;
				}

				if($seller_pricesqft_from)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.PRICE_SQFT >= '$seller_pricesqft_from' AND ";
				}

                                if($seller_pricesqft_to)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.PRICE_SQFT <= '$seller_pricesqft_to' AND ";
				}
				
				if($seller_price_from)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.PRICE >= '$seller_price_from' AND ";
				}

                                if($seller_price_to)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.PRICE <= '$seller_price_to' AND ";
				}

				if($seller_transact_type && !in_array('',$seller_transact_type))
				{
					$isSELLER++;
					$sqls.= "SELLER.TRANSACT_TYPE IN(";
					for($i=0;$i<count($seller_transact_type);$i++)
                                        {
                                                $sqls.= "'$seller_transact_type[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
				}

				if($seller_owntype && !in_array('',$seller_owntype))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.OWNTYPE IN(";
                                        for($i=0;$i<count($seller_owntype);$i++)
                                        {
                                                $sqls.= "'$seller_owntype[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_bedroom_num && !in_array('',$seller_bedroom_num))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.BEDROOM_NUM IN(";
                                        for($i=0;$i<count($seller_bedroom_num);$i++)
                                        {
                                                $sqls.= "'$seller_bedroom_num[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_bathroom_num && !in_array('',$seller_bathroom_num))
                                {
					$isSELLER++;
					if($seller_rescom=='C')
						$sqls.= "SELLER.WASHROOM_NUMBER IN(";
					else
	                                        $sqls.= "SELLER.BATHROOM_NUM IN(";

                                        for($i=0;$i<count($seller_bathroom_num);$i++)
                                        {
                                                $sqls.= "'$seller_bathroom_num[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_furnish && !in_array('',$seller_furnish))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.FURNISH IN(";
                                        for($i=0;$i<count($seller_furnish);$i++)
                                        {
                                                $sqls.= "'$seller_furnish[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_facing && !in_array('',$seller_facing))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.FACING IN(";
                                        for($i=0;$i<count($seller_facing);$i++)
                                        {
                                                $sqls.= "'$seller_facing[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_age && !in_array('',$seller_age))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.AGE IN(";
                                        for($i=0;$i<count($seller_age);$i++)
                                        {
                                                $sqls.= "'$seller_age[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_floor && !in_array('',$seller_floor))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.FLOOR_NUM IN(";
                                        for($i=0;$i<count($seller_floor);$i++)
                                        {
                                                $sqls.= "'$seller_floor[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_totalfloors && !in_array('',$seller_totalfloors))
                                {
					$isSELLER++;
                                        $sqls.= "SELLER.TOTAL_FLOOR IN(";
                                        for($i=0;$i<count($seller_totalfloors);$i++)
                                        {
                                                $sqls.= "'$seller_totalfloors[$i]',";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-1);
                                        $sqls.= ") AND ";
                                }

				if($seller_features && !in_array('',$seller_features))
                                {
					$isSELLER++;
                                        $sqls.= "(";
                                        for($i=0;$i<count($seller_features);$i++)
                                        {
                                                $sqls.= "FIND_IN_SET($seller_features[$i],SELLER.FEATURES)>0 OR ";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-4);
                                        $sqls.= ") AND ";
                                }

				if($seller_features_commercial && !in_array('',$seller_features_commercial))
                                {
					$isSELLER++;
                                        $sqls.= "(";
                                        for($i=0;$i<count($seller_features_commercial);$i++)
                                        {
                                                $sqls.= "FIND_IN_SET($seller_features_commercial[$i],SELLER.FEATURES)>0 OR ";
                                        }
                                        $sqls = substr($sqls,0,strlen($sqls)-4);
                                        $sqls.= ") AND ";
                                }

				if($seller_register_dt1 && $seller_register_dt2)
				{
					$isSELLER++;
					$sqls.= "SELLER.REGISTER_DATE BETWEEN '$seller_register_dt1 00:00:00' AND '$seller_register_dt2 23:59:59' AND ";
				}
				else
				{
					if($seller_register_dt1)
					{
						$isSELLER++;
						$sqls.= "SELLER.REGISTER_DATE >= '$seller_register_dt1 00:00:00' AND ";
					}
					if($seller_register_dt2)
					{
						$isSELLER++;
                        	                $sqls.= "SELLER.REGISTER_DATE <= '$seller_register_dt2 23:59:59' AND ";
					}
				}

				if($seller_modify_dt1 && $seller_modify_dt2)
				{
					$isSELLER++;
					$sqls.= "SELLER.MODIFY_DATE BETWEEN '$seller_modify_dt1 00:00:00' AND '$seller_modify_dt2 23:59:59' AND ";
				}
				else
				{
					if($seller_modify_dt1)
					{
        	                                $sqls.= "SELLER.MODIFY_DATE >= '$seller_modify_dt1 00:00:00' AND ";
						$isSELLER++;
					}
                	                if($seller_modify_dt2)
					{
						$isSELLER++;
                        	                $sqls.= "SELLER.MODIFY_DATE <= '$seller_modify_dt2 23:59:59' AND ";
					}
				}

				if($seller_expiry_dt1 && $seller_expiry_dt2)
				{
					$isSELLER++;
					$sqls.= "SELLER.EXPIRY_DATE BETWEEN '$seller_expiry_dt1' AND '$seller_expiry_dt2' AND ";
				}
				else
				{
					if($seller_expiry_dt1)
					{
						$isSELLER++;
        			                $sqls.= "SELLER.EXPIRY_DATE >= '$seller_expiry_dt1' AND ";
					}
                                	if($seller_expiry_dt2)
					{
						$isSELLER++;
	                                        $sqls.= "SELLER.EXPIRY_DATE <= '$seller_expiry_dt2' AND ";
					}
				}

				if($seller_source=='I')        //Internal profile
	                        {
					$isSELLER++;
        	                        $sqls.="SUBSTRING(SELLER.SOURCE,1,3)='OP-' AND ";
                	        }
                        	else if($seller_source=='E')        //External profile
	                        {
					$isSELLER++;
        	                        $sqls.="SUBSTRING(SELLER.SOURCE,1,3)<>'OP-' AND ";
                	        }

				if($seller_ntimes_from)
				{
					$isSELLER++;
					$sqls.="SELLER.NTIMES >= '$seller_ntimes_from' AND ";
				}
				if($seller_ntimes_to)
				{
					$sqls.="SELLER.NTIMES <= '$seller_ntimes_to' AND ";
					$isSELLER++;
				}

				if($seller_stimes_from)
				{
					$isSELLER++;
                                        $sqls.="SELLER.STIMES >= '$seller_stimes_from' AND ";
				}
                                if($seller_stimes_to)
				{
					$isSELLER++;
                                        $sqls.="SELLER.STIMES <= '$seller_stimes_to' AND ";
				}

				if($seller_havephoto && !in_array('',$seller_havephoto))
				{
					$isSELLER++;
					$sqls.= "SELLER.HAVEPHOTO IN('".implode("','",$seller_havephoto)."') AND ";
				}

				if($seller_screening)
				{
					$isSELLER++;
					$sqls.= "SELLER.SCREENING = '$seller_screening' AND ";
				}
				if($seller_activated)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.ACTIVATED = '$seller_activated' AND ";
				}
				if($seller_incomplete)
				{
					$isSELLER++;
                                        $sqls.= "SELLER.INCOMPLETE = '$seller_incomplete' AND ";
				}

			}	//seller query ends

		}
		//remove ANDs
		if($isPROFILE)
			$sql = substr($sql,0,strlen($sql)-5);
		if($isBUYER)
			$sqlb = substr($sqlb,0,strlen($sqlb)-5);
		if($isSELLER)
			$sqls = substr($sqls,0,strlen($sqls)-5);

		
		if($recipient_type=='A')	//no buyer or seller criteria
		{
			if($isPROFILE)	//some criteria selected
			{
				$sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE EMAIL<>'' AND ".$sql;
			}
			else
				$sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE EMAIL<>''";
		}

		if($recipient_type=='B')
		{
			if($isBUYER)	//both profile and buyer criteria selected
			{
				$sql_final = "SELECT DISTINCT(PROFILE.PROFILEID) FROM property.PROFILE INNER JOIN property.BUYER USING (PROFILEID) WHERE PROFILE.EMAIL<>'' AND ".$sql." AND ".$sqlb;
				if($sql=='')
					$sql_final = "SELECT DISTINCT(PROFILE.PROFILEID) FROM property.PROFILE INNER JOIN property.BUYER USING (PROFILEID) WHERE PROFILE.EMAIL<>'' AND ".$sqlb;
			}
			else
			{
				if($isPROFILE)
					$sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE TYPE='B' AND EMAIL<>'' AND ".$sql;
				else
					$sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE TYPE='B' AND EMAIL<>''";
			}
		}

		else if($recipient_type=='S')
		{
                        if($isSELLER)      //both profile and seller criteria selected
                        {
				$sql_final = "SELECT DISTINCT(PROFILE.PROFILEID) FROM property.PROFILE INNER JOIN property.SELLER USING (PROFILEID) WHERE PROFILE.EMAIL<>'' AND ".$sql." AND ".$sqls;
				if($sql=='')
					$sql_final = "SELECT DISTINCT(PROFILE.PROFILEID) FROM property.PROFILE INNER JOIN property.SELLER USING (PROFILEID) WHERE PROFILE.EMAIL<>'' AND ".$sqls;
                        }
                        else
                        {
                                if($isPROFILE)
                                        $sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE TYPE='S' AND EMAIL<>'' AND ".$sql;

                                else
                                        $sql_final = "SELECT PROFILEID FROM property.PROFILE WHERE TYPE='S' AND EMAIL<>''";
                        }
		}

//		echo $sql_final;

                 	$result=mysql_query($sql_final,$db99) or die("$sql_final".mysql_error());
			$count=mysql_num_rows($result);

			$smarty->assign("mailer_id",$mailer_id);
			$smarty->assign("cid",$cid);
			$smarty->assign("sql",$sql_final);
			$smarty->assign("count",$count);
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
			$smarty->assign("SALES_EXEC",create_dd("","99sales_executives"));
			$smarty->assign("INCOME",create_dd("","99monthly_income"));
			$smarty->assign("PROPERTY_TYPE",create_dd("","99property_type"));
			$smarty->assign("SELLER_PROPERTY_TYPE",create_dd("","99property_type"));
			$smarty->assign("BUYING_BUDGET",create_dd("","99buying_budget"));
			$smarty->assign("MONTHLY_BUDGET",create_dd("","99monthly_budget"));
			$smarty->assign("AREA_RANGE",create_dd("","99area_range"));
			$smarty->assign("TRANSACT_TYPE",create_dd("","99transact_type"));
			$smarty->assign("OWN_TYPE",create_dd("","99owntype"));
			$smarty->assign("FURNISH",create_dd("","99furnishing"));
			$smarty->assign("FACING",create_dd("","99facing"));
			$smarty->assign("AGE",create_dd("","99age"));
			$smarty->assign("FEATURES",create_dd("","99features"));
			$smarty->assign("FEATURES_COMMERCIAL",create_dd("","99features_commercial"));
                        $mailer_arr_mailername=get_subquery_mailers_mailername();
                        $smarty->assign("mailer_arr_mailername",$mailer_arr_mailername);
			$smarty->assign("cid",$cid);
			$smarty->assign("buyer_preference_all","on");
                        $smarty->assign("seller_preference_all","on");
			$smarty->assign("seller_class_agent","on");
			$smarty->assign("seller_class_builder","on");
			$smarty->assign("seller_class_owner","on");
			$smarty->assign("seller_listing",array(''));
			$smarty->assign("seller_bedroom_num",array(''));
			$smarty->assign("seller_bathroom_num",array(''));
			$smarty->assign("seller_floor",array(''));
			$smarty->assign("seller_totalfloors",array(''));
			$smarty->assign("seller_havephoto",array(''));

			$smarty->display("advance_search99.htm");
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
