<?php

include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
if(!function_exists('label_select'))
{
function label_select($columnname,$value)
{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

	$tablearr=array("CASTE","OCCUPATION","HEIGHT","CITY_INDIA","CITY_USA","COUNTRY","RELIGION","MTONGUE","EDUCATION_LEVEL_NEW","EDUCATION_LEVEL","INCOME","FAMILY_BACK","MOTHER_OCC","MTONGUE_S");

	if(in_array($columnname,$tablearr))
	{
		if($columnname=="RELIGION")
			return array(${$columnname . S}[$value]);
		elseif($columnname=="MTONGUE_S")
			return array($MTONGUE_DROP_SMALL[$value]);	
		else
			return array(${$columnname . _DROP}[$value]);
	}
	elseif(in_array(str_replace("newjs.","",$columnname),$tablearr))
	{
		$columnname=str_replace("newjs.","",$columnname);
		if($columnname=="RELIGION")
			return array(${$columnname . S}[$value]);
		else
			return array(${$columnname . _DROP}[$value]);
	}
	else
	{
        	$sql = "select SQL_CACHE LABEL from $columnname WHERE VALUE='$value'";
	        $res = mysql_query_optimizer($sql) or logError("error",$sql) ;
        	$myrow= mysql_fetch_row($res);
	        return $myrow;
        }
}
}
function small_label_select($columnname,$value)
{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

        $tablearr=array("CASTE","MTONGUE");

        if(in_array($columnname,$tablearr))
                return array(${$columnname . _DROP_SMALL}[$value]);
        elseif(in_array(str_replace("newjs.","",$columnname),$tablearr))
        {
                $columnname=str_replace("newjs.","",$columnname);
                return array(${$columnname . _DROP_SMALL}[$value]);
        }
        else
        {
	        $sql = "select SQL_CACHE SMALL_LABEL from $columnname WHERE VALUE='$value'";
        	$res = mysql_query_decide($sql) or logError("error",$sql) ;
	        $myrow= mysql_fetch_row($res);
        	return $myrow;
	}
}

function get_dd($tablename)
{
	$sql = "select SQL_CACHE VALUE from $tablename order by SORTBY";
	$res = mysql_query_decide($sql) or logError("error",$sql) ;
	while($myrow =mysql_fetch_array($res))
	{
		$coursearr[] =$myrow[VALUE];
	}		
	return $coursearr;
	
}

if(!function_exists('create_dd'))
{
function create_dd($selected,$cname,$minormax=0,$labelselect="",$registration_page="",$def_mtongue='')
{
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	global $lang;
	global $smarty;
        global $cityresval;
        global $cityreslab;
	
	
	if(is_array($selected))
	{
		$s_arr = $selected;
		//$selected = array();
	}
	elseif($selected!="")
	{
		$s_arr=explode(",",$selected);
	}
	else 
		$s_arr=array();
	
	$muli ="[]";
	
	if ($cname == "top_country")
	{
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_TOP_COUNTRY order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from TOP_COUNTRY order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"],$s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

	//Following Statement added by Nikhil on 23 June 2007
	
	if ($cname=="MOTHER_OCC")
	{
                if($lang=="hin")
                        $sql = "select SQL_CACHE VALUE, LABEL from $cname order by SORTBY";
                else
                        $sql = "select SQL_CACHE VALUE, LABEL from $cname  order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	//The statements added by Nikhil ends here
	
	// changes made by shobha on 15.11.2005 for community dropdown modification
	
	if ($cname == "top_mtongue")
        {
                global $mtongueval;
                global $mtonguelab;
                $mtongueval="All#";
                $mtonguelab="ALL#";
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_TOP_MTONGUE order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from TOP_MTONGUE order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                               $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
			$mtongueval.=$myrow["VALUE"]."#";
                        $mtonguelab.=$myrow["LABEL"]."#";
                }
        }
	
	if ($cname == "Religion")
	{
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_RELIGION order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from RELIGION order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"],$s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

	if ($cname == "Caste")
	{
		//$sql = "select SQL_CACHE VALUE, LABEL from CASTE order by SORTBY";

		$religionval="All#";
                $religionlab="ALL#";
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE , ISALL , GROUPID , LABEL from HIN_CASTE order by SORTBY";
		else
			$sql = "select SQL_CACHE a.LABEL , a.VALUE, a.ISALL from CASTE as a,CASTE as b where a.PARENT=b.PARENT and b.ISALL='Y' order by b.TOP_SORTBY,a.SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret="";
		while($myrow = mysql_fetch_array($res))
		{//echo $myrow["VALUE"];
                        if ($myrow['ISALL'] == 'Y')
                        {
                                $class = "dropbg";
                        }
                        /*elseif ($myrow['GROUPID'] != '0')
                        {
                                $class = "dropcolour";
                        }*/
                        else
                                $class = "";

			if(in_array($myrow["VALUE"],$s_arr))
			{
				//Added By lavesh as else they are not be displayed selected
                                if(!$class)
                                        $class="dropcolor";
                                //Ends here
				$ret .= "<option value=\"$myrow[VALUE]\" class=$class selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\" class=$class>$myrow[LABEL]</option>\n";
			}
                        $religionval.=$myrow["VALUE"]."#";
                        $religionlab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("religionval",$religionval);
                $smarty->assign("religionlab",$religionlab);
	}

	if ($cname == "Mtongue")
	{	
                global $mtongueval;
                global $mtonguelab;
		if($labelselect=="small")
		{
			if($lang=="hin")
				$sql = "select SQL_CACHE VALUE, SMALL_LABEL from HIN_MTONGUE order by SORTBY";
			else
				$sql = "select SQL_CACHE VALUE, SMALL_LABEL from MTONGUE order by SORTBY";
		}
		else
		{
			if($registration_page=="Y")
			{
				if($lang=="hin")
					$sql = "select SQL_CACHE VALUE, LABEL, REGION from HIN_MTONGUE WHERE REGION NOT IN ('0','5') order by REGION desc,SORTBY_NEW";
				else
					$sql = "select SQL_CACHE VALUE, LABEL, REGION from MTONGUE WHERE REGION NOT IN ('5') order by REGION desc,SORTBY_NEW";
			}
			else
			{
				if($lang=="hin")
					$sql = "select SQL_CACHE VALUE, LABEL, REGION from HIN_MTONGUE order by REGION desc,SORTBY_NEW";
				else
					$sql = "select SQL_CACHE VALUE, LABEL, REGION from MTONGUE order by REGION desc,SORTBY_NEW";
			}
		}
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			$mtongue_region=$myrow['REGION'];
			if(in_array($myrow["VALUE"],$s_arr))
			{
				if($labelselect=="small")
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[SMALL_LABEL]</option>\n";
				else				
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				if($labelselect=="small")
					$ret .= "<option value=\"$myrow[VALUE]\">$myrow[SMALL_LABEL]</option>\n";
				else
				{
					//section added by Gaurav on 5 May 2006 for grouping the MTONGUE on the basis of REGION
                                        if($mtongue_region!=$mtongue_region_old)
                                        {
						//added by Sriram on 3 Jan 2007 for removing All Hindi in Registration Page
						if($mtongue_region==5)
						{
							//2339:lavesh -- All Hindi will come below North Option.
							if($registration_page=="AS")
							{//echo $registration_page;
								if($def_mtongue=='yes')
                                                                        $flag_allhindi.= "<option value=\"".$MTONGUE_REGION_DROP["5"]."\" selected >All Hindi</option>\n";
                                                                else
                                                                        $flag_allhindi.= "<option value=\"".$MTONGUE_REGION_DROP["5"]."\"  >All Hindi</option>\n";	
								
							}
							else if($registration_page!="Y")
								$flag_allhindi.= "<option value=\"".$MTONGUE_REGION_DROP["5"]."\" disabled>All Hindi</option>\n";
						}
                                                elseif($mtongue_region==4)
						{
							//$ret .= "<option value=\"\" disabled></option>\n";
                                                        if($registration_page=="Y")
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<optgroup label=\"North India\">";
                                                        }
                                                        elseif($registration_page=="AS")
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
								$ret .= "<optgroup label=\"North India\" style=\"color:#5494C6\">North India</optgroup>\n";
                                                        }
                                                        else
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<option value=\"".$MTONGUE_REGION_DROP["4"]."\" style=\"font-weight: bold;color:#5494C6\" disabled>North India</option>\n";
                                                        }
							if($flag_allhindi)
								$ret.=$flag_allhindi;
                                                        $mtongueval.="%";
                                                        $mtonguelab.="%";
						}
                                                elseif($mtongue_region==3)
						{
							//$ret .= "<option value=\"\" disabled></option>\n";
                                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                        if($registration_page=="Y")
                                                        {
                                                                $ret .= "</optgroup>\n";
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<optgroup label=\"West India\">\n";
                                                        }
                                                        elseif($registration_page=="AS")
                                                        {
                                                                $ret .= "<optgroup label=\"West India\" style=\"color:#5494C6\">West India</optgroup>\n";
                                                        }
                                                        else
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<option  value=\"".$MTONGUE_REGION_DROP["3"]."\" style=\"font-weight: bold;color:#5494C6\" disabled>West India</option>\n";
                                                        }
                                                        $mtongueval.="%";
                                                        $mtonguelab.="%";
						}
                                                elseif($mtongue_region==2)
						{
							//$ret .= "<option value=\"\" disabled></option>\n";
                                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                        if($registration_page=="Y")
                                                        {
                                                                $ret .= "</optgroup>\n";
                                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                        $ret .= "<optgroup label=\"South India\">\n";
                                                        }
                                                        elseif($registration_page=="AS")
                                                        {
                                                                $ret .= "<optgroup label=\"South India\" style=\"color:#5494C6\">South India</optgroup>\n";
                                                        }
                                                        else
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<option  value=\"".$MTONGUE_REGION_DROP["2"]."\" style=\"font-weight: bold;color:#5494C6\" disabled>South India</option>\n";
                                                        }
                                                        $mtongueval.="%";
                                                        $mtonguelab.="%";
						}
                                                elseif($mtongue_region==1)
						{
							//$ret .= "<option value=\"\" disabled></option>\n";
                                                        if($registration_page=="Y")
                                                        {
                                                                $ret .= "</optgroup>\n";
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<optgroup label=\"East India\">\n";
                                                        }
                                                        elseif($registration_page=="AS")
                                                        {
                                                        	$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
								$ret .= "<optgroup label=\"East India\" style=\"color:#5494C6\">East India</optgroup>\n";
                                                        }
                                                        else
                                                        {
                                                                $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                                $ret .= "<option  value=\"".$MTONGUE_REGION_DROP["1"]."\" style=\"font-weight: bold;color:#5494C6\" disabled>East India</option>\n";
                                                        }
                                                        $mtongueval.="%";
                                                        $mtonguelab.="%";
						}
                                                elseif($mtongue_region==0)
						{
							//$ret .= "<option value=\"\" disabled></option>\n";
                                                        //$ret .= "<option  value=\"\" disabled style=\"font-weight: bold;color:#000000\">----------</option>\n";
							if($registration_page=="Y")
                                                        {
                                                                $ret .= "</optgroup>\n";
							}
							$ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                                        $ret .= "<optgroup label=\"----------\"></optgroup>\n";
							$mtongueval.="%";
                                                        $mtonguelab.="%";
						}
						$mtongue_region_old=$mtongue_region;
                                        }
                                        //end of section added by Gaurav on 5 May 2006 for grouping the MTONGUE on the basis of REGION
					if($registration_page=="AS")
					{
						if(!in_array($myrow["VALUE"],explode(',',$MTONGUE_REGION_DROP["5"])))//added by lavesh(restrict 3 option of all hindi)
                                                {
                                                        if(in_array($myrow["VALUE"],$s_arr))
                                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                                                        else
                                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                                                        $mtongueval.=$myrow["VALUE"]."#";
                                                        $mtonguelab.=$myrow["LABEL"]."#";
                                                }
					}
					else
					{
                                                if(in_array($myrow["VALUE"],$s_arr))
                                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                                                else
                                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                                        }
					/*if($registration_page=="Y")
						$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
					else
					{
						if(!in_array($myrow["VALUE"],array(10,19,33)))//added by lavesh(restrict 3 option of all hindi)
							$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
					}*/
					
				}
			}
		}
		$smarty->assign("mtongueval",$mtongueval);
                $smarty->assign("mtonguelab",$mtonguelab);
	}

	if ($cname == "Family_Back")
	{
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_FAMILY_BACK order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from FAMILY_BACK order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql) ;
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"],$s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else

			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
		$ret .= "";
	}

	if ($cname == "Country_Residence")
	{
		$ret="";
                if($lang=="hin")
                        $sql = "select SQL_CACHE VALUE, LABEL from HIN_COUNTRY order by SORTBY";
                else
                {
                        $countryresval="All#";
                        $countryreslab="ALL#";
                        $ret="<option value=\"51\"";
                        if(in_array("51",$s_arr))
                        $ret.=" selected";
                        $ret.=">India</option>";
                        $countryresval.="51#";
                        $countryreslab.="India#";

                        $ret.="<option value=\"128\"";
                        if(in_array("128",$s_arr))
                        $ret.=" selected";
                        $ret.=">USA</option>";
                        $countryresval.="128#";
                        $countryreslab.="USA#";

                        $ret.="<option value=\"125\"";
                        if(in_array("125",$s_arr))
                        $ret.=" selected";
                        $ret.=">United Arab Emirates</option>";
                        $countryresval.="125#";
	                $countryreslab.="United Arab Emirates#";
			
			$ret.="<option value=\"126\"";
                        if(in_array("126",$s_arr))
                        $ret.=" selected";
                        $ret.=">United Kingdom</option>";
                        $countryresval.="126#";
                        $countryreslab.="United Kingdom#";

                        $ret.="<option value=\"7\"";
                        if(in_array("7",$s_arr))
                        $ret.=" selected";
                        $ret.=">Australia</option>";
                        $countryresval.="7#";
                        $countryreslab.="Australia#";

                        $ret.="<option value=\"22\"";
                        if(in_array("22",$s_arr))
                        $ret.=" selected";
                        $ret.=">Canada</option>";
                        $countryresval.="22#";
                        $countryreslab.="Canada#";

                        $ret.="<option value=\"70\"";
                        if(in_array("70",$s_arr))
                        $ret.=" selected";
                        $ret.=">Malaysia</option>";
                        $countryresval.="70#";
                        $countryreslab.="Malaysia#";

			 $ret.="<option value=\"63\"";
                        if(in_array("63",$s_arr))
                        $ret.=" selected";
                        $ret.=">Kuwait</option>";
                        $countryresval.="63#";
                        $countryreslab.="Kuwait#";

                        $ret.="<option value=\"99\"";
                        if(in_array("99",$s_arr))
                        $ret.=" selected";
                        $ret.=">Saudi Arabia</option>";
                        $countryresval.="99#";
                        $countryreslab.="Saudi Arabia#";

                        $ret.="<option value=\"103\"";
                        if(in_array("103",$s_arr))
                        $ret.=" selected";
                        $ret.=">Singapore</option>";
                        $countryresval.="103#";
                        $countryreslab.="Singapore#";

                        $ret.="<option value=\"110\"";
                        if(in_array("110",$s_arr))
                        $ret.=" selected";
                        $ret.=">Sri Lanka</option>";
                        $countryresval.="110#";
                        $countryreslab.="Sri Lanka#";

                        $ret.="<option value=\"80\"";
                        if(in_array("80",$s_arr))
                        $ret.=" selected";
                        $ret.=">Nepal</option>";
                        $countryresval.="80#";
                        $countryreslab.="Nepal#";

			 $ret.="<option value=\"88\"";
                        if(in_array("88",$s_arr))
                        $ret.=" selected";
                        $ret.=">Pakistan</option>";
                        $countryresval.="88#";
                        $countryreslab.="Pakistan#";

                        $ret.="<option value=\"11\"";
                        if(in_array("11",$s_arr))
                        $ret.=" selected";
                        $ret.=">Bangladesh</option>";
                        $countryresval.="11#";
                        $countryreslab.="Bangladesh#";

                        $ret.="<optgroup label=\"&nbsp;\"></optgroup>";

                        $sql = "select SQL_CACHE VALUE, LABEL from COUNTRY_NEW WHERE LABEL NOT IN('INDIA', 'USA', 'United Arab Emirates', 'United Kingdom', 'Australia', 'Canada', 'Malaysia', 'Kuwait', 'Saudi Arabia', 'Singapore', 'Sri Lanka', 'Nepal', 'Pakistan', 'Bangladesh') order by ALPHA_ORDER";
                }
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		//$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
			$countryresval.=$myrow["VALUE"]."#";
                        $countryreslab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("countryresval",$countryresval);
                $smarty->assign("countryreslab",$countryreslab);
	}

	if ($cname == "Height")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from HEIGHT order by SORTBY";
		if($minormax ==1)
			$sql .= " desc";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected=\"selected\">$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
			//added by sriram for grouping in height dropdown on Registration page.
			if($registration_page=="Y")
			{
				$arr_space = explode("'",$myrow['LABEL']);
				$arr_space1 = explode("&quot;",$arr_space[1]);
				if($arr_space1[0]==11)
					$ret .= "<optgroup label=\"&nbsp\"></optgroup>\n";
			}
			//added by sriram for grouping in height dropdown on Registration page.
		}
	}
	
	if ($cname == "Occupation")
	{
		$occupationval="All#";
                $occupationlab="ALL#";
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_OCCUPATION order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from OCCUPATION order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
			$occupationval.=$myrow["VALUE"]."#";
                        $occupationlab.=$myrow["LABEL"]."#";
		}
                $smarty->assign("occupationval",$occupationval);
                $smarty->assign("occupationlab",$occupationlab);
	}

	if ($cname == "Income")
	{
		// Change made by Rahul Tara to display only visible values for INCOME dropdown
                //$sql = "select SQL_CACHE VALUE, LABEL from INCOME where VISIBLE <> 'N' order by SORTBY";
		//$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
		//Changed by Sriam for grouping in registration page
                $sql = "select SQL_CACHE VALUE, LABEL, TYPE from INCOME where VISIBLE <> 'N' order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
                //added by sriram for grouping on currency in registration page
                $indian_currency_label=0;
                $us_currency_label=0;
                //end of - added by sriram for grouping on currency in registration page
		while($myrow = mysql_fetch_array($res))
		{
			//added by sriram for grouping on currency in registration page
                        if($registration_page=="Y")
                        {
                                if($myrow['TYPE']=='RUPEES' && !$indian_currency_label)
                                {
                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                        $ret .= "<optgroup label=\"Income in Indian Rupees\">\n";
                                        $indian_currency_label=1;
                                }
                                elseif($myrow['TYPE']=='DOLLARS' && !$us_currency_label)
                                {
                                        $ret .= "</optgroup>\n";
                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                        $ret .= "<optgroup label=\"Income in US Dollars\">\n";
                                        $us_currency_label=1;
                                }
                        }
                        //end of - added by sriram for grouping on currency in registration page

			if(in_array($myrow["VALUE"], $s_arr))
			{
                                //added by sriram
                                if($myrow['VALUE']=='15' && $registration_page=="Y")
                                {
                                        $ret .= "</optgroup>\n";
                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                }
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
                                //added by sriram
                                if($myrow['VALUE']=='15' && $registration_page=="Y")
                                {
                                        $ret .= "</optgroup>\n";
                                        $ret .= "<optgroup label=\"&nbsp;\"></optgroup>\n";
                                }
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}
	
	if ($cname == "Country_Birth")
	{
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_COUNTRY order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from COUNTRY order by SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

  	if ($cname == "City_India")
	{
		global $cityresval;
                global $cityreslab;
                $cityresval="All#";
                $cityreslab="ALL#";
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL, TYPE from HIN_CITY_INDIA order by SORTBY";
		else
			$sql = "SELECT SQL_CACHE VALUE, LABEL, TYPE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
                $flag=1;
                while($myrow = mysql_fetch_array($res))
                {
                        if(($myrow["TYPE"]=="STATE") && $flag)
                        {
                                $ret.="<optgroup label=\"&nbsp;\"></optgroup>";
                                $flag=0;
                                $cityresval.="%";
                                $cityreslab.="%";
                        }
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                        $cityresval.=$myrow["VALUE"]."#";
                        $cityreslab.=$myrow["LABEL"]."#";
                }
                $cityresval.="%";
                $cityreslab.="%";
		$ret.="<optgroup label=\"&nbsp;\"></optgroup>";
	}
	//added by sriram for Registration page, on selecting "Other" from the options
        if ($cname == "State_India")
        {
                if($lang=="hin")
                        $sql = "select SQL_CACHE VALUE, LABEL from HIN_CITY_INDIA WHERE TYPE='STATE' order by SORTBY";
                else
                        $sql = "select SQL_CACHE VALUE, LABEL FROM CITY_NEW WHERE TYPE='STATE' AND COUNTRY_VALUE = 51 order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
        //end of - added by sriram for Registration page, on selecting "Other" from the options

  	if ($cname == "City_USA")
	{
		global $cityresval;
                global $cityreslab;
		$sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
		    if(in_array($myrow["VALUE"],$s_arr))
		    {
				if($registration_page=="Y")
                                {
                                        $temp_label = explode(" - ",$myrow['LABEL']);
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$temp_label[1]</option>\n";
                                }
				else
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";

		    }
		    else
		    {
				if($registration_page=="Y")
                                {
                                        $temp_label = explode(" - ",$myrow['LABEL']);
                                        $ret .= "<option value=\"$myrow[VALUE]\">$temp_label[1]</option>\n";
                                }
                                else
                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
		    }
		    $cityresval.=$myrow["VALUE"]."#";
                    $cityreslab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("cityresval",$cityresval);
                $smarty->assign("cityreslab",$cityreslab);
	}

	if ($cname == "Education_Level")
	{
		$edulevellab="Doesn't Matter#";
                $edulevelval="All#";
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_EDUCATION_LEVEL order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from EDUCATION_LEVEL order by LABEL";
		$res = mysql_query_optimizer($sql) or logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
			$edulevelval.=$myrow["VALUE"]."#";
                        $edulevellab.=$myrow["LABEL"]."#";
		}
		$smarty->assign("edulevelval",$edulevelval);
                $smarty->assign("edulevellab",$edulevellab);
	}
        if ($cname == "Education_Level_New")
        {
		//modified by sriram for grouping in registration page.
		$eduqualval="All#";
                $eduquallab="Doesn't Matter#";
		if($registration_page=="Y")
                {
                        if($lang=="hin")
                                $sql = "select SQL_CACHE VALUE, LABEL, GROUPING from HIN_EDUCATION_LEVEL_NEW order by GROUPING, SORTBY";
                        else
                                $sql = "select SQL_CACHE VALUE, LABEL, GROUPING from EDUCATION_LEVEL_NEW order by GROUPING,LABEL";
                }
                else
                {
			if($lang=="hin")
				$sql = "select SQL_CACHE VALUE, LABEL from HIN_EDUCATION_LEVEL_NEW order by SORTBY";
			else
				$sql = "select SQL_CACHE VALUE, LABEL from EDUCATION_LEVEL_NEW order by SORTBY";
		}
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
		$group_count=0;//added by sriram
                while($myrow = mysql_fetch_array($res))
                {
			//added by sriram for grouping on registration page.
                        if($registration_page=="Y")
                        {
                                $group = $myrow['GROUPING'];
                                if($group != $group_old)
                                {
                                        $group_count++;
                                        if($group=='0')
                                        {
                                                $ret .= "<optgroup label=\"Professional Degrees\">\n";
                                        }
                                        elseif($group=='1')
                                        {
                                                $ret .= "</optgroup>\n";
                                                $ret .= "<optgroup label=\"&nbsp;\">\n";
                                                $ret .= "<optgroup label=\"Post-Graduation Degrees\">\n";
                                        }
                                        elseif($group=='2')
                                        {
                                                $ret .= "</optgroup>\n";
                                                $ret .= "<optgroup label=\"&nbsp;\">\n";
                                                $ret .= "<optgroup label=\"Graduation Degrees\">\n";
                                        }
                                        elseif($group=='3')
                                        {
                                                $ret .= "</optgroup>\n";
                                                $ret .= "<optgroup label=\"&nbsp;\">\n";
                                        }
                                }
                        }
                        if($group_count=='4' && $registration_page=="Y")
                                $ret .= "</optgroup>\n";
                        //added by sriram for grouping on registration page.
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
			$group_old = $group;//added by sriram
			$eduqualval.=$myrow["VALUE"]."#";
                        $eduquallab.=$myrow["LABEL"]."#";
                }
		$smarty->assign("eduqualval",$eduqualval);
                $smarty->assign("eduquallab",$eduquallab);
        }
        if ($cname == "Occupation_New")
        {
		if($lang=="hin")
			$sql = "select SQL_CACHE VALUE, LABEL from HIN_OCCUPATION order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from OCCUPATION order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
        if ($cname == "Income_Lacs")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from INCOME_NEW WHERE TYPE='L' order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
        if ($cname == "Income_Thousand")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from INCOME_NEW WHERE TYPE='T'order by SORTBY";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "rashi")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from RASHI order by RASHI_SORT";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if($cname=="city_for_country")
	{
		$dont_made_other_selected=0;
		$sql_ci = "select VALUE, LABEL from CITY_NEW WHERE COUNTRY_VALUE='$labelselect' AND TYPE!='STATE' order by SORTBY";
		$res_ci = mysql_query_optimizer($sql_ci) or logError("error",$sql_ci);
		while($myrow_ci = mysql_fetch_array($res_ci)){
			if($myrow_ci["VALUE"]==$selected){
				$ret .= "<option value=\"$myrow_ci[VALUE]\" selected>$myrow_ci[LABEL]</option>\n";
				$dont_made_other_selected=1;
			}
			else
				$ret .= "<option value=\"$myrow_ci[VALUE]\">$myrow_ci[LABEL]</option>\n";
		}
		if(!$dont_made_other_selected && $myrow["CITY_RES"]!="")
			$ret .= "<option value=\"0\" selected>Others</option>\n";
		else
			$ret .= "<option value=\"0\">Others</option>\n";
	}
	return $ret;
}
}
function populate_religion($sel_val=0,$registration_page="")
{
	global $lang;

	if($lang=="hin")
		$sql="SELECT SQL_CACHE VALUE,LABEL from HIN_RELIGION ORDER BY SORTBY";
	else
		$sql="SELECT SQL_CACHE VALUE,LABEL from RELIGION ORDER BY SORTBY";
	$result=mysql_query_decide($sql);
	$j=0;
	$i=0;
	while($myrow=mysql_fetch_row($result))
	{
        	$strtemp = '';
	        $religion_value[]="$myrow[0]";
		$religion_label[]=$myrow[1];
	        $strtemp .= $religion_value[$j]."|X|";
/*
		if($lang=="hin")
			$sql="SELECT SQL_CACHE VALUE,LABEL from HIN_CASTE where PARENT='$myrow[0]' order by SORTBY";
		else        
			$sql="SELECT SQL_CACHE VALUE,LABEL from CASTE where PARENT='$myrow[0]' order by SORTBY";
	        $result1= mysql_query_decide($sql);
                                                                                                 
		while($myrow1=mysql_fetch_row($result1))
	        {
        	        $caste_value[]="$myrow1[0]";
			//added by sriram for showing only the part after ":" under caste dropdown on Registration page.
                        if($registration_page=="Y" && mysql_num_rows($result1) > 1)
                        {
                                $temp_caste = explode(": ",$myrow1[1]);
				if(!$temp_caste[1])
					$caste_label[]="$myrow1[1]";
				else
					$caste_label[] = $temp_caste[1];
                        }
                        else
                        {
                                $caste_label[]="$myrow1[1]";
                        }
                        //end of added by sriram for showing only the part after ":" under caste dropdown on Registration page.
	                $strtemp .= $caste_value[$i]."$".$caste_label[$i]."#";
        	        $i++;
			}
			$strtemp .="|S|";
			$sql="SELECT SQL_CACHE LABEL,VALUE FROM newjs.SECT WHERE PARENT_RELIGION='$myrow[0]' ORDER BY SORTBY";
			$result2= mysql_query_decide($sql);
			while($myrow2=mysql_fetch_row($result2))
			{
	                $strtemp .= $myrow2[1]."^".$myrow2[0]."@";
			}

			$strtemp = substr($strtemp,0,(strlen($strtemp)-1));*/
        	$j++;
		
	        $str[] = $strtemp;
	}

	for($x=0;$x<count($str);$x++)
	{
		$str_temp = explode('|X|',$str[$x]);
        	$str_val = $str_temp[0];

		if($sel_val == $str_val)
			$newstr.="<option value=\"" . $str[$x] . "\" selected>" . $religion_label[$x] . "</option>\n";
		else
			$newstr.="<option value=\"" . $str[$x] . "\">" . $religion_label[$x] . "</option>\n";
	}
	return $newstr;
}

function populate_caste($Caste)
{
	global $lang;

	$ret='';
	//$str_value= explode("|X|",$Religion);
	//$rel_value=$str_value[0];
	//$cas_value=$Caste;
	//$sql= "SELECT SQL_CACHE VALUE,LABEL from CASTE ORDER BY SORTBY";

	if($lang=="hin")
		$sql= "SELECT SQL_CACHE VALUE,LABEL , ISALL , GROUPID from HIN_CASTE ORDER BY SORTBY";
	else
	{
		//REVAMP JS_DB_CASTE
		$sql= "SELECT SQL_CACHE VALUE,LABEL , ISALL from CASTE ORDER BY SORTBY";
		//REVAMP JS_DB_CASTE
	}
	$result=mysql_query_decide($sql);
	$i=0;
	while($myrow=mysql_fetch_array($result))
	{
                if ($myrow['ISALL'] == 'Y')
                {
                        $class = "dropbg";
                }
		//REVAMP JS_DB_CASTE
                elseif (is_part_of_a_group($myrow['VALUE']))
                {
                        $class = "dropcolour";
                }
                else
                        $class = "";

		if($myrow["VALUE"]== $Caste)
	        {
			$ret .= "<option value=\"$myrow[VALUE]\" caste =$class selected>$myrow[LABEL]</option>\n";

                }
                else
                {
                	$ret .= "<option value=\"$myrow[VALUE]\" caste =$class>$myrow[LABEL]</option>\n";
                }

	}
	return $ret;
}
if(!function_exists('caste_populate_religion'))
{
function caste_populate_religion($religion,$Caste,$registration_page="")
{
	global $lang;
	
	if($lang)
	{
		if(!$Caste && $religion)
		{
			$class = "dropcolour";
			$ret .= "<option value=\"\" class =\"$class\" selected>कोई एक विकल्प चुनिए</option>\n";
		}

		$sql= "SELECT SQL_CACHE VALUE,LABEL , ISALL , GROUPID from newjs.HIN_CASTE WHERE PARENT='$religion' ORDER BY SORTBY";        
	}
	else
	{
		if(!$Caste && $religion)
		{
			$class = "dropcolour";
			$ret .= "<option value=\"\" class=\"$class\" selected>Select any one option</option>\n";
		}

		//REVAMP JS_DB_CASTE
		$sql= "SELECT SQL_CACHE VALUE,LABEL , ISALL from newjs.CASTE WHERE PARENT='$religion' AND REG_DISPLAY!='N' ORDER BY SORTBY";
		//REVAMP JS_DB_CASTE
	}

	$result=mysql_query_decide($sql);

        while($myrow=mysql_fetch_array($result))
	{
                $value_1=$myrow["VALUE"];
                $one_caste=in_array($value_1,array(153,148,1,162));//Religion having only one caste.
		$caste_sameas_religion=in_array($value_1,array(14,149,2,154,173));
                                                                                                                             
                if($one_caste)
                        unset($ret);
                                                                                                                             
                if(!$caste_sameas_religion || $one_caste)
                {
                        if ($myrow['ISALL'] == 'Y')
                        {
                                $class = "dropbg";
                        }
			//REVAMP JS_DB_CASTE
                        elseif (is_part_of_a_group($myrow['VALUE']))
                        {
                                $class = "dropcolour";
                        }
                        else
                                $class = "";
                                                                                                                             
                        if($myrow["VALUE"]==$Caste)
                        {
                                //selected option
                                if($class=='')
                                        $class = "dropcolour";
				//added by sriram for showing only the part after ":" in Caste dropdown on registration page
                                if($registration_page=="Y")
                                {
                                        $temp_label = explode(": ",$myrow['LABEL']);
                                        $ret .= "<option value=\"$myrow[VALUE]\" class =$class selected>$temp_label[1]</option>\n";
                                }
                                else
				{
                                        	$ret .= "<option value=\"$myrow[VALUE]\" class =$class selected>$myrow[LABEL]</option>\n";
				}
                                //end of - added by sriram for showing only the part after ":" in Caste dropdown on registration page
                        }
                        else
                        {
				//added by sriram for showing only the part after ":" in Caste dropdown on registration page
                                if($registration_page=="Y")
                                {
                                        $temp_label = explode(": ",$myrow['LABEL']);
                                        $ret .= "<option value=\"$myrow[VALUE]\" caste =$class>$temp_label[1]</option>\n";
                                }
                                else
				{
                                        	$ret .= "<option value=\"$myrow[VALUE]\" caste =$class>$myrow[LABEL]</option>\n";
				}
                                //end of - added by sriram for showing only the part after ":" in Caste dropdown on registration page
                        }
                }
		else
                {
                        $class="dropbg";
			//if($religion == 2 || $religion == 9)
				$ret .= "";
			//else
                        //	$ret .= "<option value=\"0\" caste =$class disabled>$myrow[LABEL]</option>\n";
                }
        }
return $ret;
}
}
function get_all_caste($caste,$optional="",$from_matchalert="",$db="")
{
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/profile/connect_db.php");
$db = connect_db();
        return get_all_caste_revamp_js_db($caste,$db,1,$from_matchalert);
        //REVAMP JS_DB_CASTE
}

function get_old_value($value,$tablename)
{
	//changed by Gaurav on 30 Jan 2006 for search clustering
        if($tablename=="EDUCATION_LEVEL_NEW")
        {
			$pattern="/.*,.*/";
                if(preg_match($pattern,$value))
                        $sql="SELECT SQL_CACHE OLD_VALUE from $tablename WHERE VALUE IN ($value)";
                else
                        $sql="SELECT SQL_CACHE OLD_VALUE from $tablename WHERE VALUE=$value";                 $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($myrow=mysql_fetch_array($result))
                {
                        if($myrow['OLD_VALUE']!=0)
                                $old_values[]=$myrow['OLD_VALUE'];
                }
                if( is_array($old_values) && count($old_values>1))
                {       $old_values= array_unique($old_values);
                        $old=implode(",",$old_values);
                }
                elseif(is_array($old_values))
                        $old=$old_values[0];
        }
	//end of changes by Gaurav on 30 Jan 2006 for search clustering
        elseif($tablename=="OCCUPATION")
        {
                $sql="SELECT SQL_CACHE VALUE from $tablename WHERE VALUE=$value";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow=mysql_fetch_array($result);
                $old=$myrow['VALUE'];
        }
        return $old;
}

function get_old_income_value($Ctc_Type,$Ctc_Lacs,$Ctc_thousand)
{
        if($Ctc_Type=="R")
        {
                if($Ctc_Lacs==0 && $Ctc_Thousand<=50)
                        $old=1;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=50 )
                        $old=2;
                if($Ctc_Lacs==1 && $Ctc_Thousand<=95)
                        $old=3;
                if($Ctc_Lacs==2 && $Ctc_Thousand<=95)
                        $old=4;
                if($Ctc_Lacs==3 && $Ctc_Thousand<=95)
                        $old=5;
                if($Ctc_Lacs==4 && $Ctc_Thousand<=95)
                        $old=6;
                if($Ctc_Lacs>=5 )
                        $old=7;
        }
        elseif($Ctc_Type=="D")
        {
                if($Ctc_Lacs==0 && $Ctc_Thousand<=25)
                        $old=8;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=25 && $Ctc_Thousand<=50)
                        $old=9;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=50 && $Ctc_Thousand<=75)
                        $old=10;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=75 && $Ctc_Thousand<=95)
                        $old=11;
                if($Ctc_Lacs==1 && $Ctc_Thousand<=50)
                        $old=12;
                if($Ctc_Lacs==1 && $Ctc_Thousand>=50 && $Ctc_Thousand<=95)
                        $old=13;
                if($Ctc_Lacs>=2 )
                        $old=14;
        }
        return $old;
}
if(!function_exists('populate_day_month_year'))
{
function populate_day_month_year()
{
        global $smarty;
        for($i=1;$i<=31;$i++)
                $ddarr[] = $i;
                                                                                                                             
        $smarty->assign("ddarr",$ddarr);
                                                                                                                             
        $mmarr = array(
                        array('LABEL' => 'January', 'VALUE' => '1'),
                        array('LABEL' => 'February', 'VALUE' => '2'),
                        array('LABEL' => 'March', 'VALUE' => '3'),
                        array('LABEL' => 'April', 'VALUE' => '4'),
                        array('LABEL' => 'May', 'VALUE' => '5'),
                        array('LABEL' => 'June', 'VALUE' => '6'),
                        array('LABEL' => 'July', 'VALUE' => '7'),
                        array('LABEL' => 'August', 'VALUE' => '8'),
                        array('LABEL' => 'September', 'VALUE' => '9'),
                        array('LABEL' => 'October', 'VALUE' => '10'),
                        array('LABEL' => 'November', 'VALUE' => '11'),
                        array('LABEL' => 'December', 'VALUE' => '12'),
                        );
                                                                                                                             
        $smarty->assign("mmarr",$mmarr);
                                                                                                                             
        $curyear = date('Y');
        $start_year = $curyear-13;
        $end_year = $curyear-70;
        $preselected_year = $curyear-30;
        for($i=$start_year;$i>=$end_year;$i--)
                $yyarr[]=$i;
                                                                                                                             
        $smarty->assign("preselected_year",$preselected_year);
        $smarty->assign("yyarr",$yyarr);
}
}

function left_panel_search($selected_community="",$label="")
{
        global $smarty,$Gender,$Photos,$lage,$hage,$lheight,$hheight,$Caste,$Mtongue,$hp_mstatus,$City_Res,$searchorder;
        global $data,$_COOKIE;
                                                                                                                             
        $lang=$_COOKIE['JS_LANG'];

        if(isset($data))
        {
                $G=$data["GENDER"];
                if($G=='M')
                        $G='F';
                else
                        $G='M';
        }
        else
        {
                if(isset($_COOKIE["JS_GENDER"]))
                {
                        $G=$_COOKIE["JS_GENDER"];
                }
                else
                {
                        $G='F';
                }
        }
        $smarty->assign("G",$G);
        unset($G);
                                                                                                                             
        //added by Gaurav on 21 Nov 2005
        if($label=='MTONGUE')
	{
                if(is_array($selected_community))
                {
                        $sel_arr_mtongue = $selected_community;
                }
                elseif($selected_community!="")
                {
                        $sel_arr_mtongue =explode(",",$selected_community);
                }
                else
                        $sel_arr_mtongue =array();
        }
        
        if($label=='CITY')
	{
                if(is_array($selected_community))
                {
                        $sel_arr_city = $selected_community;
                }
                elseif($selected_community!="")
                {
                        $sel_arr_city =explode(",",$selected_community);
                }
                else
                        $sel_arr_city =array();
        }
        else if($label=='COUNTRY')
        {
                if($selected_community=='51')
                        $City_Res="51";
                //section added by Gaurav on 6 March 2007 to make Country selected in top band
                if($selected_community=='IN')
                        $City_Res="51";
                elseif($selected_community=='USA')
                        $City_Res="USA";
                elseif($selected_community=='UK')
                        $City_Res="UK";
                elseif($selected_community=='ANZ')
                        $City_Res="ANZ";
                elseif($selected_community=='A')
                        $City_Res="A";
                elseif($selected_community=='UAE')
                        $City_Res="UAE";
		elseif($selected_community=='CANADA')
                        $City_Res="CANADA";
		 elseif($selected_community=='INT')
                        $City_Res="INT";
                //end of section added by Gaurav on 6 March 2007 to make Country selected in top band
        }


	/*USELESS CODE MARKED BY ANAND                                                                                                                             
        if($lang=='hin')
                $sql_city = "select SQL_CACHE VALUE, LABEL from HIN_CITY_INDIA  WHERE TOP='Y'  order by TOP_SORTBY";
        else
                $sql_city = "select SQL_CACHE VALUE, LABEL FROM CITY_NEW  WHERE TOP='Y'  order by TOP_SORTBY";
                
        $res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
	*/
        $ret_city="";
        if($City_Res=="51")
        {
                $ret_city .= "<option value=\"51\" selected>All India</option>\n";
        }
        else
        {
		$ret_city .= "<option value=\"51\">All India</option>\n";
        }

        
/*******************************Code added to get values for "Internationl" by Neha*******************************/
	$sql_int= "SELECT SQL_CACHE VALUE FROM newjs.COUNTRY WHERE VALUE <> '51' ";
	$res_int=mysql_query_decide($sql_int) or logError("error",$sql_int);
	while($row_int= mysql_fetch_array($res_int))
		$int_data[]=$row_int['VALUE'];
//	print_r($int_data);	
	$international= implode(",",$int_data);
//	echo $international;
//           End of code added by neha

        while($myrow_city = mysql_fetch_array($res_city))
        {
                if($City_Res)
                {
                        if(is_array($City_Res) && in_array($myrow_city["VALUE"],$City_Res))
                        {
                                $city_label.="'"."$myrow_city[LABEL]"."',";
                                $city_value.="'"."$myrow_city[VALUE]"."',";
                                $ret_city .= "<option value=\"$myrow_city[VALUE]\" selected>$myrow_city[LABEL]</option>\n";
                        }
                        else
                        {
                                $city_label.="'"."$myrow_city[LABEL]"."',";
                                $city_value.="'"."$myrow_city[VALUE]"."',";
                                $ret_city .= "<option value=\"$myrow_city[VALUE]\">$myrow_city[LABEL]</option>\n";
                        }
                }
                else
                {
                        if(is_array($sel_arr_city) && in_array($myrow_city["VALUE"],$sel_arr_city))
                        {
                                $city_label.="'"."$myrow_city[LABEL]"."',";
                                $city_value.="'"."$myrow_city[VALUE]"."',";
                                $ret_city .= "<option value=\"$myrow_city[VALUE]\" selected>$myrow_city[LABEL]</option>\n";
                        }
                        else
                        {
                                $city_label.="'"."$myrow_city[LABEL]"."',";
                                $city_value.="'"."$myrow_city[VALUE]"."',";
                                $ret_city .= "<option value=\"$myrow_city[VALUE]\">$myrow_city[LABEL]</option>\n";
                        }
                }
        }

//	$ret_city .= "<option value=\"\" disabled></option>\n";
  //      $ret_city .= "<option value=\"\" style=\"font-weight: bold;color:#000000\" disabled>--International--</option>\n";
// Code added by Neha
	if($City_Res=="INT")
        {	
		$city_label.="'"."INT"."',";
       		$city_value.="'".$international."',";
                $ret_city .= "<option value=\"INT\" style=\"font-weight: bold;color:#000000\" selected>--International--</option>\n";
		 // echo "\n ppp" . htmlentities($ret_city);
        }
        else
        {
                $city_label.="'"."INT"."',";
           	$city_value.="'"."$international"."',";
                $ret_city .= "<option value=\"INT\"  >--International--</option>\n";
		
        }

 //end of code added by neha.
	if($City_Res=="USA")
        {
                $city_label.="'"."USA"."',";
                $city_value.="'"."128"."',";
                $ret_city .= "<option value=\"USA\" selected>USA</option>\n";
        }
        else
        {
                $city_label.="'"."USA"."',";
                $city_value.="'"."128"."',";
                $ret_city .= "<option value=\"USA\">USA</option>\n";
        }
                                                                                                                     
        if($City_Res=="UK")
        {
                $city_label.="'"."United Kingdom"."',";
                $city_value.="'"."126"."',";
                $ret_city .= "<option value=\"UK\" selected>UK</option>\n";
                                                                                                                     
        }
        else
        {
                $city_label.="'"."United Kingdom"."',";
                $city_value.="'"."126"."',";
                $ret_city .= "<option value=\"UK\">UK</option>\n";
        }
	if($City_Res=="CANADA")
        {
                $city_label.="'"."CANADA"."',";
                $city_value.="'"."22"."',";
                $ret_city .= "<option value=\"CANADA\" selected>Canada</option>\n";
        }
        else
        {
                $$city_label.="'"."CANADA"."',";
                $city_value.="'"."22"."',";
                $ret_city .= "<option value=\"CANADA\">Canada</option>\n";
        }
	if($City_Res=="UAE")
        {
                $city_label.="'"."UAE"."',";
                $city_value.="'"."125"."',";
                $ret_city .= "<option value=\"UAE\" selected>UAE</option>\n";
        }
        else
        {
                $city_label.="'"."UAE"."',";
                $city_value.="'"."125"."',";
                $ret_city .= "<option value=\"UAE\">UAE</option>\n";
        }
        if($City_Res=="ANZ")
        {
                $city_label.="'"."Australia"."',";
                $city_value.="'"."7,82"."',";
                $ret_city .= "<option value=\"ANZ\" selected>Australia /NZ</option>\n";
        }
else
        {
                $city_label.="'"."Australia"."',";
                $city_value.="'"."7,82"."',";
                $ret_city .= "<option value=\"ANZ\">Australia /NZ</option>\n";
        }

        if($City_Res=="A")
        {
                $city_label.="'"."Asia"."',";
                $city_value.="'"."11,14,25,48,52,70,74,80,88,101,103,110,119,92"."',";
                $ret_city .= "<option value=\"A\" selected>Asia</option>\n";
        }
        else
        {
                $city_label.="'"."Asia"."',";
                $city_value.="'"."11,14,25,48,52,70,74,80,88,101,103,110,119,92"."',";
                $ret_city .= "<option value=\"A\">Asia</option>\n";
        }
                                                                                                                     
        if($label=='CASTE')
	{
                if(is_array($selected_community))
                {
                        $sel_arr_caste = $selected_community;
                }
                elseif($selected_community!="")
                {
                        $sel_arr_caste =explode(",",$selected_community);
                }
                else
                        $sel_arr_caste =array();
        }
        
        $highlight = 0;
        $class = "";
        if($lang=='hin')
        {
                $sql_religion = "select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL , GROUPID  from HIN_CASTE order by SORTBY";
                $sql_religion_top = "select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL , GROUPID  from HIN_CASTE where ISALL='Y' order by SORTBY";
        }
        else
        {
		//REVAMP JS_DB_CASTE
                $sql_religion = "select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL from CASTE order by SORTBY";                
		$sql_religion_top = "select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL from CASTE where ISALL='Y' order by SORTBY";
		//REVAMP JS_DB_CASTE
        }

        $res_religion = mysql_query_decide($sql_religion) or logError("error",$sql_religion);
        $res_religion_top = mysql_query_decide($sql_religion_top) or logError("error",$sql_religion);
        $ret_religion = "";
	while($myrow_religion = mysql_fetch_array($res_religion))
        {
		$reg=$myrow_religion['LABEL'];
                if ($myrow_religion['ISALL'] == 'Y')
                {
                        $class = "dropbg";
                }
		//REVAMP JS_DB_CASTE
                elseif (is_part_of_a_group($myrow_religion['VALUE']))
                {
                        $class = "dropcolour";
                }
                else
                        $class = "";
                if($Caste)
                {
                        if(is_array($Caste) && in_array($myrow_religion["VALUE"],$Caste))
                        {
                                $ret_religion .= "<option  value=\"$myrow_religion[VALUE]\" class=\"$class\" selected>$reg</option>\n";
                                                                                                                             
                        }
                        else
			{
                                $ret_religion .= "<option value=\"$myrow_religion[VALUE]\" class=\"$class\" >$reg</option>\n";
                                                                                                                             
                        }
                }
                else
                {
                        if(is_array($sel_arr_caste) && in_array($myrow_religion["VALUE"],$sel_arr_caste))
                        {
                                $ret_religion .= "<option class=\"$class\" value=\"$myrow_religion[VALUE]\" selected>$reg</option>\n";
                        }
                        else
                        {
                                $ret_religion .= "<option class=\"$class\" value=\"$myrow_religion[VALUE]\">$reg</option>\n";                        
			}
                }
        }
                                                                                                                             
        while($myrow_religion=mysql_fetch_array($res_religion_top))
        {
		if($Caste)
                {

                        if(is_array($Caste) && in_array($myrow_religion["VALUE"],$Caste))
                        {
                                $religion_label.="'"."$myrow_religion[LABEL]"."',";
                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
                        }
                        else
                        {
                                $religion_label.="'"."$myrow_religion[LABEL]"."',";
                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
                        }
                }
                else
                {
                        if(is_array($sel_arr_caste) && in_array($myrow_religion["VALUE"],$sel_arr_caste))
                        {
                                $religion_label.="'"."$myrow_religion[LABEL]"."',";
                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
                        }
                        else
                        {
                                $religion_label.="'"."$myrow_religion[LABEL]"."',";
                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
                	}
		}
        }                                                                                                                      
        
        if($lang=='hin')
        	$sql="select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL , GROUPID, SORTBY, PARENT  from HIN_CASTE order by PARENT,SORTBY";
       	else 
	{
		//REVAMP JS_DB_CASTE
       		$sql="select SQL_CACHE VALUE, SMALL_LABEL AS LABEL , ISALL, SORTBY, PARENT  from CASTE order by PARENT,SORTBY";
		//REVAMP JS_DB_CASTE
	}
	
       	$res_religion=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");  ;
        
        $num=0;
        while($myrow_religion = mysql_fetch_array($res_religion))
        {
        	if($myrow_religion["PARENT"]!=$prevparent)
        	{
        		if($prevparent)
        			$count_reg.="'".$num."',";
        		$num=0;
        	}
        	
        	$prevparent=$myrow_religion["PARENT"];
        	
        	if($myrow_religion["ISALL"]=="")
        	{
	        	$num++;
	        	
	                if($Caste)
	                {
	                        if(is_array($Caste) && in_array($myrow_religion["VALUE"],$Caste))
	                        {
					$label=ltrim($myrow_religion['LABEL'],'-');
	                                $religion_label.="'"."$label"."',";
	                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
	                        }
	                        else
	                        {
					$label=ltrim($myrow_religion['LABEL'],'-');
	                                $religion_label.="'"."$label"."',";
	                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
	                        }
			}
	                else
	                {
	                        if(is_array($sel_arr_caste) && in_array($myrow_religion["VALUE"],$sel_arr_caste))
	                        {
					$label=ltrim($myrow_religion['LABEL'],'-');
	                                $religion_label.="'"."$label"."',";
	                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
	                        }
	                        else
	                        {
					$label=ltrim($myrow_religion['LABEL'],'-');
	                                $religion_label.="'"."$label"."',";
	                                $religion_value.="'"."$myrow_religion[VALUE]"."',";
	                        }
	                }
        	}
        }
        
        $count_reg.="'".$num."',";
        
        $religion_label=substr($religion_label,0,-1);
        $religion_value=substr($religion_value,0,-1);
        $mtongue_label=substr($mtongue_label,0,-1);
        $mtongue_value=substr($mtongue_value,0,-1);
        $city_label=substr($city_label,0,-1);
        $city_value=substr($city_value,0,-1);
        $count_reg=substr($count_reg,0,-1);
        $reg_value="new Array($religion_value)";
        $reg_label="new Array($religion_label)";
	$count_reg="new Array($count_reg)";
        $city_label="new Array($city_label)";
        $city_value="new Array($city_value)";
        $mtongue_label="new Array($mtongue_label)";
        $mtongue_value="new Array($mtongue_value)";
	
        $smarty->assign("Gender",$Gender);
        $smarty->assign("Photos",$Photos);
        $smarty->assign("lage",$lage);
        $smarty->assign("hage",$hage);
        $smarty->assign("lheight",$lheight);
        $smarty->assign("hheight",$hheight);
        $smarty->assign("hp_mstatus",$hp_mstatus);
        $smarty->assign("searchorder",$searchorder);
//	echo " inner html " . htmlentities($ret_city);
//	echo $ret_city;
        $smarty->assign("CITY",$ret_city);
        $smarty->assign("RELIGION",$ret_religion);
        $smarty->assign("RELIGION_LABEL",$reg_label);
        $smarty->assign("RELIGION_VALUE",$reg_value);
        $smarty->assign("COUNT_REG",$count_reg);
        $smarty->assign("MTONGUE_LABEL",$mtongue_label);
        $smarty->assign("MTONGUE_VALUE",$mtongue_value);
        $smarty->assign("CITY_LABEL",$city_label);
        $smarty->assign("CITY_VALUE",$city_value);
}
function countryAndCityDropDown($country_residence,$isEdit=0)
	{
	/*Country city dropdown creation*/

	//top city dropdown
	$sql = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE DD_TOP='Y' ORDER BY DD_TOP_SORTBY";
        $res = mysql_query_decide($sql) or logError("error",$sql);
        $top_city_str="";
        while($row = mysql_fetch_array($res))
        {
                $top_city_str = $top_city_str.$row["STD_CODE"]."|{|".$row["VALUE"]."$".$row["LABEL"]."#";
        }
        $top_city_str = $top_city_str." |{} $ #";	
	
	$sql = "SELECT SQL_CACHE VALUE,LABEL,TOP_COUNTRY,ISD_CODE FROM newjs.COUNTRY_NEW ORDER BY ALPHA_ORDER";
	$res = mysql_query_decide($sql) or logError("error",$sql);
	$x = 0;
	while($row = mysql_fetch_array($res))
	{
		$country_isd_code1 = $row['ISD_CODE'];
		$country_isd_code = "+".$country_isd_code1;
		$country_label_arr[] = $row['LABEL'];
		$country_value = $row['VALUE'];

		$citizenship_arr[$x]["VALUE"] = $row['VALUE'];
		$citizenship_arr[$x]["LABEL"] = $row['LABEL'];
		$x++;
		
		if(($country_value==128 || $country_value==51))
		{
			if($isEdit)
			{
				//both usa and india are allowed
				$sql_city = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='$country_value' AND TYPE!='STATE' ORDER BY SORTBY";
				$res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
				while($row_city = mysql_fetch_array($res_city))
				{
					$city_value = $row_city['VALUE'];
					$city_label = $row_city['LABEL'];
					$city_std_code = $row_city['STD_CODE'];
					$city_str .= $city_std_code."|{|".$city_value."$".$city_label."#";
				}
			}
			else		
			{
				//only country india 
				if(($country_value==51))
				{
					$sql_city = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='$country_value' AND TYPE!='STATE' ORDER BY SORTBY";
					$res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
					while($row_city = mysql_fetch_array($res_city))
					{
						$city_value = $row_city['VALUE'];
						$city_label = $row_city['LABEL'];
						$city_std_code = $row_city['STD_CODE'];
						$city_str .= $city_std_code."|{|".$city_value."$".$city_label."#";
					}
				}
			}
		}

			$row_value='0';
		$row_others="Others";

		$city_str .= $row_value."$".$row_others."#";

		if($isEdit)
		{
			if(!($country_value==128 || $country_value==51))
				$city_str="";
		}
		else		
		{
			if(!($country_value==51))
				$city_str="";
		}
		
		if($country_value==51)
			$city_str=$top_city_str.$city_str;
		$country_str = $country_isd_code."|}|".$country_value."|X|".$city_str;
		if($city_str)
			$country_value_arr[] = substr($country_str,0,strlen($country_str)-1);
		else
			$country_value_arr[] = $country_str;

	       if($row["TOP_COUNTRY"] == "Y")
     	       {
				   $country_str=rtrim($country_str,"#");
               		 $top_country_label_arr[] = $row["LABEL"];
               		 $top_country_value_arr[] = $country_str;
               }
	       unset($city_str);
	       unset($country_str);
	}
       for($i=0;$i<count($top_country_value_arr);$i++)
       {
		$temp_country = explode("|X|",$top_country_value_arr[$i]);
		$temp_country = explode("|}|",$temp_country[0]);
		if($country_residence == $temp_country[1])
		$option_string.= "<option value=\"$top_country_value_arr[$i]\" selected=\"yes\">".$top_country_label_arr[$i]."</option>";
		else
		$option_string.= "<option value=\"$top_country_value_arr[$i]\">".$top_country_label_arr[$i]."</option>";
		
	}
	$option_string.= "<optgroup label=\"-----\"></optgroup>";

	//By default selecting country_residence as india if not selected
	if($country_residence=="")
		$country_residence=51;

	for($i=0;$i<count($country_value_arr);$i++)
	{
		$temp_country = explode("|X|",$country_value_arr[$i]);
		$temp_country = explode("|}|",$temp_country[0]);
		if($country_residence == $temp_country[1])
		$option_string.= "<option value=\"$country_value_arr[$i]\" selected=\"yes\">".$country_label_arr[$i]."</option>";
		else
		$option_string.= "<option value=\"$country_value_arr[$i]\">".$country_label_arr[$i]."</option>";

	}
	return $option_string;
	}

?>
