<?php
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
//echo 'seo string '.$seo_string;
//echo '<br>';
//$seo_string=urldecode(urldecode($seo_string));
//echo 'seo string decoded '.$seo_string;
//echo '<br>';

$pattern = '/matrimonials\-(.*)/';
preg_match($pattern, $seo_string, $matches);
if(is_array($matches) && count($matches)>0)
{
        $arr=explode("-",$matches[1]);
        //$searchchecksum=$arr[0];
	//$searchorder=$arr[1];
        //$j=$arr[2];
        //$searchchecksum=$arr[0];
	$searchorder=$arr[0];
        $j=$arr[1];
	if($searchorder=='S')
	{
		$searchorder='O';
		$j=intval($j/12);
	}
       	//print_r($matches);
        $seo_string=str_replace("-".$matches[1],"",$seo_string);
}
unset($pattern);
unset($matches);
unset($arr);

//echo 'seo string decoded '.$seo_string;
//echo '<br>';
//echo '<br>';
//echo '<br>';
$seo_ll=1;

foreach($CITY_INDIA_DROP as $var)
{
	$seo_location_drop_india[$seo_ll]=$var;
	
	$seo_ll++;
}
$seo_ll=1;
foreach($CITY_USA_DROP as $var)
{
	$seo_location_drop_usa[$seo_ll]=$var;
	$seo_ll++;
}
$seo_ll=1;
foreach($COUNTRY_DROP as $var)
{
	$seo_location_drop[$seo_ll]=$var;
	$seo_ll++;
}

$seo_mstatus_drop=array("N" => "Never Married","W" => "Widowed","D" => "Divorced","S" => "Awaiting Divorce","O" => "Others","A"=>"Annulled");//To be replaced by arrays.php
$seo_gender_drop=array();
$seo_entries=array();
$seo_gender_drop["M"]="Groom";
$seo_gender_drop["F"]="Bride";
//8 denotes highest priority
$seo_priority_arr[8]='gender';
$seo_priority_arr[7]='community';
$seo_priority_arr[6]='religion';
$seo_priority_arr[5]='caste';
$seo_priority_arr[4]='location';
$seo_priority_arr[3]='education';
$seo_priority_arr[2]='profession';
$seo_priority_arr[1]='marital-status';

$seo_highest_priority=1;
$seo_lowest_priority=8;

$seo_pass=0;
$seo_bold1=0;
$seo_bold2=0;
$seo_flag=0;
$seo_bold=1;

function my_urlencode_arr($arr)
{
	//return $arr;
	foreach ($arr as $key => $value)
	{
		//$arr2[$key]=my_urlencode(my_urlencode($value));
		$arr2[$key]=my_urlencode($value);
	}
	return $arr2;
}

function my_urlencode($str)
{
	if(strstr($str,'&amp;'))
		return (str_replace('&amp;','%2526amp;',$str));
	elseif(strstr($str,'&'))
		return (str_replace('&','%2526amp;',$str));
	else
		return $str;
	//return urlencode($str);
}

function my_urldecode($str)
{
	if(strstr($str,'%2526amp;'))
		return (str_replace('%2526amp;','&amp;',$str));
	else	
		return $str;
	//return urlencode($str);
}

$smarty->assign("SEO_MARITALSTATUS",$seo_mstatus_drop);
$smarty->assign("SEO_LOCATION_INDIA",$seo_location_drop_india);
$smarty->assign("SEO_LOCATION_USA",$seo_location_drop_usa);
$smarty->assign("SEO_LOCATION",$seo_location_drop);
$smarty->assign("SEO_EDUCATION",$EDUCATION_LEVEL_NEW_DROP);
$smarty->assign("SEO_GENDER",$seo_gender_drop);
$smarty->assign("SEO_PROFESSION",$OCCUPATION_DROP);
$smarty->assign("SEO_COMMUNITY",$MTONGUE_DROP_SMALL);
$smarty->assign("SEO_RELIGION",$RELIGIONS);

$smarty->assign("SEO_STATIC_MARITALSTATUS_URL",my_urlencode_arr($seo_mstatus_drop));
$smarty->assign("SEO_STATIC_LOCATION_USA_URL",my_urlencode_arr($seo_location_drop_usa));
$smarty->assign("SEO_STATIC_LOCATION_INDIA_URL",my_urlencode_arr($seo_location_drop_india));
$smarty->assign("SEO_STATIC_LOCATION_URL",my_urlencode_arr($seo_location_drop));
$smarty->assign("SEO_STATIC_EDUCATION_URL",my_urlencode_arr($EDUCATION_LEVEL_NEW_DROP));
$smarty->assign("SEO_STATIC_GENDER_URL",my_urlencode_arr($seo_gender_drop));
$smarty->assign("SEO_STATIC_PROFESSION_URL",my_urlencode_arr($OCCUPATION_DROP));
$smarty->assign("SEO_STATIC_CASTE_URL",my_urlencode_arr($CASTE_DROP));
$smarty->assign("SEO_STATIC_COMMUNITY_URL",my_urlencode_arr($MTONGUE_DROP_SMALL));
$smarty->assign("SEO_STATIC_RELIGION_URL",my_urlencode_arr($RELIGIONS));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));


$seo_qqq=0;
foreach($seo_priority_arr as $key => $value)
{	
	$seo_bold1++;
	if(strstr($seo_string,$value))
	{	
		$seo_bold2++;
		$seo_pass+=1;
		$seo_string_priority_arr[]=$key;
                $seo_string_priority_name_arr[]=$value;
	}
	if(strstr($seo_string,$value) && $key>=$seo_highest_priority)
	{
		$seo_highest_priority=$key;
	}
	if(strstr($seo_string,$value) && $key<=$seo_lowest_priority)
	{	
		$seo_lowest_priority=$key;
	}
	if(($seo_bold1!=$seo_bold2)&&$seo_flag==0&&$seo_bold1==3)
	{
		$seo_bold=$seo_bold1;
		$seo_qqq=1;
		$seo_bold2++;
	}	
	if(($seo_bold1!=$seo_bold2)&&$seo_flag==0&&$seo_bold1!=3)
	{	
		if($seo_qqq&&$seo_bold1!=4)
		{	
			$seo_boldy=$seo_bold1;
                        $seo_flag=1;
		}
		elseif($seo_qqq&&$seo_bold1==4)
		{
			$seo_flag=0;	
			$seo_bold2++;
		}
		else
		{
			$seo_bold=$seo_bold1;	
			$seo_flag=1;
		}
	}
	$seo_pass*=10;

} 
$seo_pass/=10;	
$seo_pass=11111111-$seo_pass;
$seo_oes=1;

if($seo_pass<11111111)
	$seo_tag=1;
else 
	$seo_tag=0;

if($seo_pass==11111111 && $seo_browse_matrimony==1)
{
	$smarty->assign("SEO_CASTE",$CASTE_DROP);
	$smarty->display("seo/seo_general_all.html");
	exit;
}

function array_isearch($str, $array)
{
	$str = strtolower($str);
	foreach ($array as $k => $v) 
	{	
		$v=strtolower($v);
		if( ($v == $str) || ( str_replace('&amp;','%2526amp;',$str) == $v ) || ( str_replace('&','%2526amp;',$str) == $v ) )
			return $k;
	}
	return false;
}


/********** Code for extracting values from seo string starts **********/

function get_category_value_from_seo_url($arr_dropdown,$category)
{
	global $seo_string,$seo_priority_arr,$seo_string_priority_arr,$seo_entries;
	$seo_value=0;
	
	if(!$seo_value )	
	{	
		//echo 'pattren is ...';
		$pattern = '/'.$category.'-(.*)-matrimonials/';
		//echo '<br>';
		preg_match($pattern, $seo_string, $matches);
		if(is_array($matches))
		{	
			//echo 'matches is... ';
			//print_r($matches);
			//echo '<br>';
			$seo_value=array_isearch($matches[1],$arr_dropdown);//$seo_entries[]=$matches[1];}
		}
		unset($matches);
		unset($pattern);
	}
	
	if(!$seo_value)	
	{	
		if(is_array($seo_string_priority_arr))
		foreach ($seo_string_priority_arr as $key => $value)
		if(!$seo_value)	
		{	
			$pattern = '/'.$category.'-(.*)-'.$seo_priority_arr[$value].'/';
			preg_match($pattern, $seo_string, $matches);
			if(is_array($matches))
			{$seo_value=array_isearch($matches[1],$arr_dropdown);//$seo_entries[]=$matches[1];
			}
			unset($matches);
			unset($pattern);
		}
	}
	$seo_entries[]=my_urldecode($arr_dropdown[$seo_value]);
	return $seo_value;
}


$Gender = "F";
$TOP_BAND_SEARCH = "Y";
//$community = "ALL";   //is giving panga when set to ALL
//$Mtongue =
//$Mtongue_val = 
$City_Res = "All";
$lage = "18";
$hage = "70";
//$caste = 
//$Caste = 
//$Caste_display = 
//$Caste_val = 
$lheight = "1";
$hheight = "32";
//$hp_mstatus = 
//$City_res = 
//$City_res_val = 
//$Country_res = 
//$Country_res_val = 
$STYPE = "L";
//$searchonline = 
//$E_CLASS = 
//$checksum = 
$Search = "Search";

//echo "<br>";
//echo 'seo community is ';

$seo_gender=get_category_value_from_seo_url($seo_gender_drop,'gender');
$seo_community=get_category_value_from_seo_url(my_urlencode_arr($MTONGUE_DROP_SMALL),'community');
$seo_religion=get_category_value_from_seo_url($RELIGIONS,'religion');
$seo_caste=get_category_value_from_seo_url($CASTE_DROP,'caste');
$seo_location_city_india=get_category_value_from_seo_url(my_urlencode_arr($CITY_INDIA_DROP),'location');
$seo_location_city_usa=get_category_value_from_seo_url($CITY_USA_DROP,'location');
$seo_location_country=get_category_value_from_seo_url($COUNTRY_DROP,'location');
$seo_education=get_category_value_from_seo_url(my_urlencode_arr($EDUCATION_LEVEL_NEW_DROP),'education');
$seo_profession=get_category_value_from_seo_url(my_urlencode_arr($OCCUPATION_DROP),'profession');
$seo_marital_status=get_category_value_from_seo_url($seo_mstatus_drop,'marital-status');
//echo "$seo_gender--$seo_community--$seo_religion--$seo_caste--$seo_location_city_india--$seo_location_city_usa--$seo_location_country--$seo_education--$seo_profession--$seo_marital_status";
//echo '<br>';
//echo 'seo marital status is ';

//echo $seo_marital_status;
//echo '<br>';
//print_r($seo_entries);
//echo "<br>";
if($seo_gender)
	$Gender=$seo_gender;
//$Gender="F";

//echo "<br>";
if($seo_community)
{
	$Mtongue=$seo_community;
	$Mtongue_val=$seo_community;  	// community get ?
	$community=$seo_community.'|X|';
}
//$Mtongue=10;
//$Mtongue_val=10;

//echo "<br>";
if($seo_caste)
	$caste=$seo_caste;  		// these hv values of prev search [Caste] => 149 [Caste_display] => 149 [Caste_val] => 149
if($seo_religion)	
{
	$Religion=$seo_religion.'|X|';  		// these hv values of prev search [Caste] => 149 [Caste_display] => 149 [Caste_val] => 149
	$religionVal=$seo_religion;
}
$force=1;
$no_cluster_to_display=1;
$type='AS';
//$caste=14;

//echo "<br>";
if($seo_location_city_india)
{
       $City_Res=$seo_location_city_india; // i think city_res_val country_res and country_res_val not used
	$partner_city_arr[]=$City_Res;
	$partner_country_arr[]="51#";
	
}
elseif($seo_location_city_usa)
{
        $City_Res=$seo_location_city_usa;
	$partner_city_arr[]=$City_Res;
	$partner_country_arr[]="128#";
}
elseif($seo_location_country)
        $Country_Res=$seo_location_country;
//$City_Res="USA";

//echo "<br>";
if($seo_marital_status)
	$hp_mstatus=$seo_marital_status;
//$hp_mstatus="N";

//echo "<br>";
//$seo_education=14;
if($seo_education)
{
        $SEARCH_CLUSTERING='Y';
	$edu=$seo_education;
}

//echo "<br>";
//$seo_profession=3;
if($seo_profession)
{
        $SEARCH_CLUSTERING='Y';
	$occupation=$seo_profession;
}


/********* Code for extracting values from seo string ends **********/
$new_caste=array();
$seo_ll=1;
if(($seo_entries[2])&&(!$seo_entries[3]))
{
	if($seo_entries[2]=="Other")
		$new_caste[$seo_ll]="No Religion/Caste";
	else
		foreach($CASTE_DROP as $key => $value)
		{
			if(strstr($value,$seo_entries[2]))
				$new_caste[$seo_ll]=$value;
			$seo_ll++;
		}
}
/********* Creating proper URLs for every category starts **************/


function seo_create_urls($arr_dropdown,$category)
{
	global $seo_priority_arr,$seo_string,$seo_string_priority_arr,$seo_lowest_priority,$seo_highest_priority;
	$seo_ll=1;
	$my_priority=array_search($category,$seo_priority_arr);

	if($my_priority<$seo_lowest_priority &&is_array($seo_string_priority_arr) &&!in_array($my_priority,$seo_string_priority_arr) )
	foreach($arr_dropdown as $key => $value)
	{	
			//$value=strtolower($value);
			//$seo_category_url[$seo_ll]=my_urlencode(my_urlencode(str_replace("-matrimonials","/$category-$value-matrimonials",$seo_string)));
                        if($category=='community')
                                $seo_category_url[$key]=my_urlencode(str_replace("-matrimonials","-$category-$value-matrimonials",$seo_string));
                        else
                                $seo_category_url[$seo_ll]=my_urlencode(str_replace("-matrimonials","-$category-$value-matrimonials",$seo_string));
		$seo_ll++;
	}
	
	elseif($my_priority>$seo_highest_priority &&is_array($seo_string_priority_arr)&& !in_array($my_priority,$seo_string_priority_arr))
	foreach($arr_dropdown as $key => $value)
	{	
		//$value=strtolower($value);
		//$seo_category_url[$seo_ll]=my_urlencode(my_urlencode(str_replace($seo_priority_arr[$seo_highest_priority],"$category-$value/$seo_priority_arr[$seo_highest_priority]",$seo_string)));
		if($category=='community')
			$seo_category_url[$key]=my_urlencode(str_replace($seo_priority_arr[$seo_highest_priority],"$category-$value-$seo_priority_arr[$seo_highest_priority]",$seo_string));
		else
			$seo_category_url[$seo_ll]=my_urlencode(str_replace($seo_priority_arr[$seo_highest_priority],"$category-$value-$seo_priority_arr[$seo_highest_priority]",$seo_string));

		$seo_ll++;
	} 
	
	elseif(is_array($seo_string_priority_arr)&& !in_array($my_priority,$seo_string_priority_arr))
	{
		$lesser_priority=1;
                foreach($seo_string_priority_arr as $key2 => $value2)
                {
                        if($value2<$my_priority)
                        {
                                $lesser_priority=$value2;
                                break;
                        }
                }

                $pattern = '/'.$seo_priority_arr[$lesser_priority].'(.*)-matrimonials/';
                preg_match($pattern, $seo_string, $matches);
                if(is_array($matches))
                {
                        foreach($arr_dropdown as $key => $value)
                        {
                                //$value=strtolower($value);
                                //$seo_category_url[$seo_ll]=my_urlencode(my_urlencode(str_replace($seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$category.'-'.$value.'/'.$seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$seo_string)));
				if($category=='community')
					$seo_category_url[$key]=my_urlencode(str_replace($seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$category.'-'.$value.'-'.$seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$seo_string));
				else
	                                $seo_category_url[$seo_ll]=my_urlencode(str_replace($seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$category.'-'.$value.'-'.$seo_priority_arr[$lesser_priority].$matches[1].'-matrimonials',$seo_string));
				$seo_ll++;
                        }
                }
                unset($matches);
                unset($pattern);
	}
	return $seo_category_url;
}
$seo_gender_url=seo_create_urls($seo_gender_drop,'gender');
$seo_community_url=seo_create_urls(my_urlencode_arr($MTONGUE_DROP_SMALL),'community');
$seo_religion_url=seo_create_urls($RELIGIONS,'religion');
if(($seo_entries[2])&&(!$seo_entries[3]))
{	
	$seo_caste_url=seo_create_urls($new_caste,'caste');
	$smarty->assign("SEO_CASTE",$new_caste);
}	
else
{		
	$seo_caste_url=seo_create_urls($CASTE_DROP,'caste');
	$smarty->assign("SEO_CASTE",$CASTE_DROP);
}
$seo_location_url=seo_create_urls(my_urlencode_arr($seo_location_drop),'location');
$seo_location_usa_url=seo_create_urls(my_urlencode_arr($seo_location_drop_usa),'location');
$seo_location_india_url=seo_create_urls(my_urlencode_arr($seo_location_drop_india),'location');
$seo_education_url=seo_create_urls(my_urlencode_arr($EDUCATION_LEVEL_NEW_DROP),'education');
ksort($OCCUPATION_DROP);
$seo_profession_url=seo_create_urls(my_urlencode_arr($OCCUPATION_DROP),'profession');
$seo_marital_status_url=seo_create_urls($seo_mstatus_drop,'marital-status');
$seo_breadies=array();
function seo_bread_crum($check)
{
        static $heck,$turr=0,$burr=0;
        global $seo_entries,$seo_string_priority_name_arr;
        if(!$check)
        {
                $burr++;
                return null;
        }
        else
        {
                $check=$heck.$seo_string_priority_name_arr[$turr].'-'.$seo_entries[$burr];
                $heck=$check.'-';
                $turr++;
                $burr++;
                return my_urlencode($check) ;
        }
}
$seo_breadies[]=seo_bread_crum($seo_gender);
$seo_breadies[]=seo_bread_crum($seo_community);
$seo_breadies[]=seo_bread_crum($seo_religion);
$seo_breadies[]=seo_bread_crum($seo_caste);
$seo_breadies[]=seo_bread_crum($seo_location_city_india);
$seo_breadies[]=seo_bread_crum($seo_location_city_usa);
$seo_breadies[]=seo_bread_crum($seo_location_country);
$seo_breadies[]=seo_bread_crum($seo_education);
$seo_breadies[]=seo_bread_crum($seo_profession);
$seo_breadies[]=seo_bread_crum($seo_marital_status);



/********* Code for Creating proper URLs for every field ends**************/

if(($seo_entries[3])&&(!$seo_entries[2]))
{
	$seo_pass-=100000;

	if($seo_bold==3)
		$seo_bold=$seo_boldy;
}

//echo $seo_bold;	
//print_r($seo_entries);
//echo $seo_pass;			
foreach($seo_entries as $name)
{
	if($name)
	$seo_entries1[] = $name;
}
//print_r($seo_entries1);
foreach($seo_breadies as $name)
{
        if($name)
        $seo_breadies1[] = $name;
}

$seo_fail=$seo_pass;
$smarty->assign("SEO_BREADIES",$seo_breadies1);
$smarty->assign("SEO_ENTRIES",$seo_entries1);	
$smarty->assign("SEO_RELIGION_URL",$seo_religion_url);
$smarty->assign("SEO_COMMUNITY_URL",$seo_community_url);
$smarty->assign("SEO_CASTE_URL",$seo_caste_url);
$smarty->assign("SEO_PROFESSION_URL",$seo_profession_url);
$smarty->assign("SEO_EDUCATION_URL",$seo_education_url);
$smarty->assign("SEO_LOCATION_URL",$seo_location_url);
$smarty->assign("SEO_LOCATION_USA_URL",$seo_location_usa_url);
$smarty->assign("SEO_LOCATION_INDIA_URL",$seo_location_india_url);
$smarty->assign("SEO_GENDER_URL",$seo_gender_url);
$smarty->assign("SEO_MARITALSTATUS_URL",$seo_marital_status_url);


$smarty->assign("SEO_OES",$seo_oes);	
$smarty->assign("SEO_TAG",$seo_tag);
$smarty->assign("SEO_FAIL",$seo_fail);
$smarty->assign("SEO_BOLD",$seo_bold);
$smarty->assign("SEO_PASS",$seo_pass);	
$smarty->assign("SEO_DIV",$smarty->fetch("seo/common_seo.htm"));	

unset($seo_entries);
unset($seo_religion_url);
unset($seo_community_url);
unset($seo_caste_url);
unset($seo_profession_url);
unset($seo_education_url);
unset($seo_location_url);
unset($seo_gender_url);
unset($seo_marital_status_url);

unset($seo_gender_drop);
//unset($MTONGUE_DROP_SMALL);
unset($RELIGIONS);
//unset($CASTE_DROP);
unset($seo_location_drop);
//unset($EDUCATION_LEVEL_NEW_DROP);
//unset($OCCUPATION_DROP);
unset($seo_mstatus_drop);

//unset($CITY_INDIA_DROP);
//unset($CITY_USA_DROP);
//unset($COUNTRY_DROP);

//$seo_string=my_urlencode(my_urlencode($seo_string));
$seo_string=my_urlencode($seo_string);
$smarty->assign("seo_string",$seo_string);
if($seo_string=="gender-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");
	exit;
}
elseif($seo_string=="community-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");
	exit;
}
elseif($seo_string=="religion-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");
	exit;
}
elseif($seo_string=="caste-matrimonials")
{
	$smarty->assign("SEO_CASTE",$CASTE_DROP);
	$smarty->display("seo/seo_general_all.html");
	exit;
}
elseif($seo_string=="location-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");

	exit;
}
elseif($seo_string=="education-matrimonials")
{
	
	$smarty->display("seo/seo_general_all.html");

	exit;
}
elseif($seo_string=="profession-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");

	exit;
}
elseif($seo_string=="marital-status-matrimonials")
{
	$smarty->display("seo/seo_general_all.html");

	exit;
}
/*if(($seo_entries[2])&&(!$seo_entries[3]))
	$smarty->assign("SEO_CASTE",$new_caste);
else
	$smarty->assign("SEO_CASTE",$CASTE_DROP);
unset($CASTE_DROP);*/
?>
