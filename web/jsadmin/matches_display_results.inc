<?php
/************************************************************************************************************
Filename    : matches_display_result.inc [Offline Product 2586]
Description : Common functions for pagelink,assign profile details for offline product
Created On  : 25 January 2008
Created By  : Sadaf Alam
************************************************************************************************************/
$flag_using_php5=1;
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("view_contact.php");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysqlObj=new Mysql;

/*function display_format($str)
{
        if($str)
        {
                $str=trim($str,"'");

                $arr=explode("','",$str);
                return $arr;
        }
}*/

function pagelink($pagelen,$totalrec,$curpage,$linkno,$link,$scriptname="",$searchchecksum="",$newsearch="",$inf_checksum="",$castemapping="",$crmback="",$ordering="",$savesearch="",$label_select_no='',$from_index="",$viewall="",$hide_search_bar="",$save_search_redirect="")
{
        global $smarty;
        /*IMPORTANT*/
        /*WHEN CREATING A LINK, VARIABLE $hide_search_bar WILL ALWAYS BE AT THE END for #accepted_members to work*/
        //if($hide_search_bar)
                $hide_search_bar="y#accepted_members";
        // set the links to be shown on each page to be five
        $linkno=10;
        $totalpage = $totalrec / $pagelen;
        $totalpage = ceil($totalpage);
        $curpage = round($curpage);
        $mid_linkno=abs(floor($linkno/2));
        $startwith=$curpage - $mid_linkno -1;
        if($startwith <= 0)                $startwith=0;
        if(($startwith+$linkno) >= $totalpage)                $startwith=$totalpage-$linkno;        if($startwith <= 0)                $startwith=0;
	/*
        $startwith = $curpage / $linkno;
        $startwith = abs(floor($startwith - 0.1));
        $startwith = $startwith * $linkno;
*/
        if($totalpage > ($startwith + $linkno))
        {
                $totallinkshow = $linkno;
                if($totalpage < ($startwith + ($linkno * 2)))
                $lastlink = $startwith + $totalpage;
                else
                $lastlink = $startwith + $linkno + $totallinkshow;
        }
        else if($totalpage < ($startwith + ($linkno * 2)))
        {
                $totallinkshow = $totalpage - $startwith;
                $lastlink = "";
        }
        else
        {
                $totallinkshow = $totalpage - $startwith;
                $lastlink = $startwith + $linkno + $totallinkshow;
        }

        $prevwith=$startwith-9;
        //$linkvar = "Page : <strong>";
        if($startwith && !($startwith % $linkno))
                //$linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."\">($prevwith - $startwith)</a></span>&nbsp;";
                $linkvar .= "&nbsp;<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">&lt;&lt;</a>&nbsp;";


	$linkvar.="</b>&lt;<b>";
        //Modified By lavesh
        if($curpage > 1)
                $linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">Previous</a> ";
        else
                $linkvar.="Previous ";


        for($i=1;$i<=$totallinkshow;$i++)
        {
                $nos = $startwith+$i;

                //line added By lavesh
                if($i==1)
                        $linkvar .= "&nbsp;";
                //if($nos != $curpage)
                // $linkvar .= ($nos-1)*$pagelen;
                //else
                if($nos != $curpage)
                        $linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">";
                else
                        $linkvar .= "&nbsp;";
		/*              if($i == $totallinkshow && $lastlink)
                 $linkvar .= "($nos - $lastlink)>></a></span>&nbsp;";
                else*/

                //4 lines added By lavesh 
                if($i == $totallinkshow)
                        $linkvar .= "$nos</a>&nbsp;";
                elseif($curpage==$i)
                        $linkvar .= "$nos&nbsp;|&nbsp;";
                else
                        $linkvar .= "$nos</a>&nbsp;|&nbsp;";
        }

        if($lastlink)
        {
                $nos = $startwith+$i;
                $linkvar .= "&nbsp;<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">";
                //$linkvar .= "($nos - $lastlink)</strong></a></span>&nbsp;";
                $linkvar .= "</a>&nbsp;";
        }
	$recordid = explode("i",$searchchecksum);
        if(count($recordid) > 1)
                $searchrecid = $recordid[1];
        else
                $searchrecid = $recordid[0];
        if($searchrecid!="")
        {
                $sql = "Update newjs.SEARCHQUERY set PAGECOUNT = if(PAGECOUNT <= $curpage , '$curpage',PAGECOUNT) where ID ='$searchrecid'";
                //$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","Tampering with URL in search","ShowErrTemplate");
        }

        //Commented By lavesh
        /*if($curpage > 1)
                $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering\">Previous</a></span>";
        else 
                $linkvar.="Previous";
                
        $linkvar.="</font></strong> <strong>|";

        $linkvar.="<font color=\"#999999\">";*/

        if($curpage < $totalpage)
                $linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=". $curpage*$pagelen ."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">Next</a>";
        else
                $linkvar.="Next";

	 $linkvar.="&gt;";
        if($totalpage==0)
                $linkvar="";
        //echo "<br>linkvar : ".$linkvar;       
        $smarty->assign("PAGELINK",$linkvar);
        return $linkvar;
}
//end function pagelink


//Function to display lists
function displayresults($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="",$ordering="",$newsearch="",$optional="",$inf_checksum="",$castemapping="",$crmback="",$profileid="",$cid="")//$savesearch="",$label_select_no='',$from_index="",$save_search_redirect="")
        {
                global $PAGELEN,$smarty,$db,$PHOTO_URL,$IMG_URL;

                $orig_scriptname = $scriptname;

		$smarty->assign("length",10);

include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		 $lang=$_COOKIE['JS_LANG'];

	
		if($scriptname=="/jsadmin/pool_matches.php")
		$page="pool";
		if($scriptname=="/jsadmin/shortlisted_profiles.php")
		$page="shortlist";
		if($scriptname=="/jsadmin/accept_matches.php")
		$page="accept";
		if($scriptname=="/jsadmin/rejected_matches.php")
		$page="reject";

                //income_map will come from dropdowns .php
		if($scriptname=="/jsadmin/pool_matches.php")
		{
			while($myrow=mysql_fetch_row($result))
			{
				$emailstr.="'".$myrow[1]."',";
				$emailarr[]=$myrow[1];
			}
			$emailstr=trim($emailstr,",");
			$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE EMAIL IN($emailstr)";
			$result=mysql_query_decide($sql) or die(mysql_error_js());
		}

                $FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,CASTE,MANGLIK,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,
                FATHER_INFO,SIBLING_INFO,SPOUSE,SHOW_HOROSCOPE,GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,LAST_LOGIN_DT,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,RELIGION,SHOWPHONE_MOB,SHOWPHONE_RES,ACTIVATED,PHONE_RES,EMAIL";

/********************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       15 November, 2005
                        Reason          :       For optimizing search queries by fetching result set for 120 records
                                        :       and subsequently using mysql_data_seek()
********************************************************************************************************************/
		if(mysql_num_rows($result))
		{
                        while($myrow=mysql_fetch_row($result))
                        {
                                if($flag_script)
                                {
                                        $RESULT_ARRAY_3d[]=$myrow[1];
                                        $str.="'" . $myrow[1] . "',";
                                }
                                else
                                {
                                        $RESULT_ARRAY_3d[]=$myrow[0];
                                        $str.="'" . $myrow[0] . "',";
                                }
                        }

                $str=substr($str,0,strlen($str)-1);

                $sql="select";

                if($nocalc=="")
                        $sql.=" SQL_CALC_FOUND_ROWS";

                $sql.=" $FIELDS from newjs.JPROFILE where PROFILEID in ($str)";

                if($putactivate=="1")
                        $sql.=" and ACTIVATED='Y'";
		if($ordering=="F")
                        $sql.=" order by ENTRY_DT desc";
                elseif($ordering=="M")
                        $sql.=" order by MOD_DT desc";
                elseif($ordering=="L")
                        $sql.=" order by LAST_LOGIN_DT desc";
                else
                        $sql.=" order by SORT_DT desc";
                if($nocalc=="")
                        $sql.=" limit $curcount,$PAGELEN";
                        $result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if($nocalc=="")
                {
                        $sql="select FOUND_ROWS() as cnt";
                        $resultcount=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

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
                @mysql_data_seek($result1,0);
                
		if(mysql_num_rows($result1)>0)
                {
                        $sql_astro="SELECT PROFILEID FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ($resultprofiles)";
                        $result_astro=mysql_query_decide($sql_astro) or die("$sql_astro".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_astro,"ShowErrTemplate");

                        if(mysql_num_rows($result_astro) > 0)
                        {
                                while($myrow_astro=mysql_fetch_array($result_astro))
                                {
                                        $astro_array[]=$myrow_astro["PROFILEID"];
                                }
                        }

                        mysql_free_result($result_astro);
		if($resultprofiles)
                {
			$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ThumbailUrl");	//Symfony Photo Modification 

                        $onlinesql="select userID from userplane.users where userID in ($resultprofiles)";
                        $onlineresult=mysql_query_decide($onlinesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");

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
			$jprofile_email_arr[]=$myrow["EMAIL"];

			//Added by Neha Verma to show manglik status in tuple
			$manglik_st=$myrow["MANGLIK"];
			if($manglik_st=='M')
				$manglik_status="Yes";
			elseif($manglik_st=='N')
				$manglik_status="No";
			else
				$manglik_status="Don't Know";

			//End
                        $income=$myrow["INCOME"];
                        $occ=$myrow["OCCUPATION"];
                        if($lang=='hin')
                        {
                                $occupation_temp=label_select("HIN_OCCUPATION",$occ);
                                $occupation=$occupation_temp[0];
                                unset($occupation_temp);
                        }
                        else
                        {
                                $occupation=$OCCUPATION_DROP["$occ"];
                        }

                        $caste1=$myrow["CASTE"];
                        if($lang=='hin')
                        {
                                $caste_temp=label_select("HIN_CASTE",$caste1);
                                $caste=$caste_temp[0];
                                unset($caste_temp);
                        }
                        else
                        {
                                $caste=$CASTE_DROP["$caste1"];
                        }

                        if($lang=='hin')
                        {
                                $mtongue1 = label_select("HIN_MTONGUE",$myrow['MTONGUE']);
                        }
			 else
                        {
                                $mtongue_temp=$myrow['MTONGUE'];
                                $mtongue1[0] = $MTONGUE_DROP["$mtongue_temp"];
                        }
                        $mtongue = $mtongue1[0];

                        if($lang=='hin')
                        {
                                $edu_leveln=label_select("HIN_EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
                        }
                        else
                        {
                                $edu_temp=$myrow['EDU_LEVEL_NEW'];
                                $edu_leveln[0]=$EDUCATION_LEVEL_NEW_DROP["$edu_temp"];
                        }
                        $edu_level=$edu_leveln[0];

                        $screening=$myrow["SCREENING"];
                        if(isFlagSet("SUBCASTE",$screening))
                                $subcaste=trim($myrow["SUBCASTE"]);
                        else
                                $subcaste="";

                        if(isFlagSet("YOURINFO",$screening))
                                $yourinfo=$myrow["YOURINFO"];
                        else
                                $yourinfo="";

                        if(isFlagSet("FATHER_INFO",$screening))
                                $fatherinfo=$myrow["FATHER_INFO"];
                        else
                                $fatherinfo="";
			if(isFlagSet("SIBLING_INFO",$screening))
                                $siblinginfo=$myrow["SIBLING_INFO"];
                        else
                                $siblinginfo="";

                        if(isFlagSet("SPOUSE",$screening))
                                $spouseinfo=$myrow["SPOUSE"];
                        else
                                $spouseinfo="";

                        if($spouseinfo!="")
                                $spouseinfo="Looking for: " . $spouseinfo;

                        $heightn=$myrow["HEIGHT"];
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
                                {
                                        if($lang=='hin')
                                        {
                                                $residence_temp=label_select("HIN_CITY_INDIA",$city_res1);
                                                $residence=$residence_temp[0];
                                                unset($residence_temp);
                                        }
                                        else
                                                $residence=$CITY_INDIA_DROP["$city_res1"];
                                }
                                else
                                        $residence=$CITY_USA_DROP["$city_res1"];
                        }
                        else
                        {
                                $country1=$myrow["COUNTRY_RES"];
                                if($lang=='hin')
                                {
                                        $residence_temp=label_select("HIN_COUNTRY",$country1);
                                        $residence=$residence_temp[0];
                                        unset($residence_temp);
                                }
                                else
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

			if(in_array("1",$subscription))
				$member_101=1;
			else
				$member_101=0;

                        //done for new service added called Eclassified  NEW CHANGES
                        //line changed by lavesh
                        if(in_array("D",$subscription)) //&& !in_array("S",$subscription))
                                $contact_details=1;
                        else
                                $contact_details=0;

                        if($myrow["HAVEPHOTO"]=="U")
                                $havephoto="U";
                        elseif($myrow["HAVEPHOTO"]=="Y")
                                $havephoto="Y";
                        else
                                $havephoto="N";

			/* if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
                        {
                                if(!$data)
                                {
                                        //$havephoto="P";
                                        $havephoto="L";
                                }
                                elseif($data && $myrow["PRIVACY"]=="F")
                                {
                                        if(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
                                                //$havephoto="P";
                                                $havephoto="F";
                                }
                        }*/
                        //if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
			
			if($havephoto=="Y" && $myrow["PHOTO_DISPLAY"]=="H")
                        {
                                //if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
                                        //$havephoto="P";
                                if($myrow["PHOTO_DISPLAY"]=="H")
                                        $havephoto="H";
                                /*elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
                                        $havephoto="L";
                                elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
                                        $havephoto="L";
                                elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
				elseif($myrow["PHOTO_DISPLAY"]=="C")
				{
                                        //if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
				
                                                ;
                                        else
                                        {
                                                //$havephoto="P";
                                                $havephoto="C";
                                        }
                                }
                                elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
                                {
                                        if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
                                                ;
                                        elseif(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
                                                //$havephoto="P";
                                                $havephoto="P";
                                }*/
                        }

/*********************************************************************************************************************
Changed By      : Shakti Srivastava
Change Date     : 27 July 2005
Reason          : "YOURINFO" has to be broken into two parts so that the initial 3 words can be shown in Bold in
                  the template
**********************************************************************************************************************/
			$yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,300);
                        //$yourinfo=str_replace('0','',$yourinfo);
                        $yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
                        $yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
                        $yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
                        $yourinfo=str_replace('  ',' ',$yourinfo);              // replace '  ' (2 spaces) with ' ' (single space)

                        $temp="";
                        $protitle="";
                        $counter=0;
                        $len=0;
                        $temp=explode(" ",$yourinfo);
                        for($i=0;$i<=2;$i++)
                        {
                                $protitle.=" ".$temp[$i];
                        }

                        $protitle=str_replace(',',', ',$protitle);              //replace ',' with ', '(comma, space)
                        $protitle=str_replace(' ,',', ',$protitle);             //replace ' ,' (space, comma) with ', '(comma, space);
                        $protitle=str_replace('/','/ ',$protitle);              //replace '/' with '/ ' (slash, space)
                        $protitle=str_replace('  ',' ',$protitle);              // replace '  ' (2 spaces) with ' ' (single space)
                        $yourinfo="";
                        for($c=3;$c<count($temp);$c++)
                        {
                                $yourinfo.=" ".$temp[$c];
                        }


                        /*
                        //Correcting the logic of horoscope icon on search/favourite/ignore/photo req page
                        if($myrow['SHOW_HOROSCOPE']=='Y')
                        {       
                                if($myrow['COUNTRY_BIRTH']!='0' && $myrow['CITY_BIRTH']!='' && $myrow['BTIME']!='')
                                {
                                        $horo_link="horoscope_astro.php";
                                        $horoscope="Y";
                                }
                                else
                                        $horoscope="N";
                        }
                        else
                                $horoscope="N";
                        */

                        //code ends for showin horoscope icon
                        $photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
                        $photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);
			//Symfony Photo Modification 
			$profilePicUrlArr = $profilePicUrls[$myrow["PROFILEID"]];                       
			if ($profilePicUrlArr)
			{
				$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
			}
			else
			{
				$thumbnailUrl = null;
			}
			

                        $username=$myrow["USERNAME"];
			/**********************************************************************************************
                        Changed By      :       Shakti Srivastava
                        Change Date     :       4 January, 2006
                        Reason          :       New element, STAT_UNAME has been added for static urls. 
                ***********************************************************************************************/
                        $sumProfileid=0;

                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $sumProfileid=$sumProfileid+$myrow["PROFILEID"]{$tempcnt};      //sum of profileid digits
                        }
                        $rotator=$sumProfileid%(strlen($myrow["PROFILEID"]));           //sum mod length of profileid rotator

                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $newpos=($tempcnt+$rotator)%strlen($myrow["PROFILEID"]);
                                $rotatedProfileidArr[$newpos]=$myrow["PROFILEID"]{$tempcnt};    //rotated profileid
                        }

                        ksort($rotatedProfileidArr);

                        if(count($rotatedProfileidArr)>1)
                                $rotatedProfileid=implode("",$rotatedProfileidArr);
                        else
                                $rotatedProfileid=$rotatedProfileidArr[0];

                        unset($rotatedProfileidArr);
                        unset($sumProfileid);
			for($tempcnt=0;$tempcnt<strlen($myrow["USERNAME"]);$tempcnt++)
                        {
                                $asciiChr=ord($myrow["USERNAME"]{$tempcnt});

                                if($asciiChr>=33 && $asciiChr<=126)
                                {
                                        $stat_uname=$rotatedProfileid.$myrow["USERNAME"]{$tempcnt}.$rotator;
                                        break;
                                }
                        }
                        unset($rotatedProfileid);
                        unset($rotator);
                /***********************************************************************************************/

                /**********************************************************************************************
                        Changed By      :       Shakti Srivastava
                        Change Date     :       17 January, 2006
                        Reason          :       New element, HORO_LINK has been added for links on horoscope icon
                ***********************************************************************************************/



                        //Added By lavesh(suggested By Gaurav) for reduicing size of search.htm on 29 sep 2006
			$age=$myrow["AGE"];
                        $gothra=trim($myrow["GOTHRA"]);
                        $my_income=$income_map["$income"];
                        $gender=$myrow["GENDER"];
                        $nakshatra=trim($myrow["NAKSHATRA"]);

                        if($age)
                                $info="Age: <b>$age</b> ";
                        if($height2)
                                $info.="Height: <b>$height2</b> ";
                        if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)
                                $info.="Religion: <b>".$RELIGIONS[$myrow['RELIGION']]."</b> " ;
                        if($myCaste)
                                $info.="Caste: <b>$myCaste</b> ";
                        if($subcaste)
                                $info.="($subcaste) ";
                        if($gothra && $gothra!="i don't know")
                                $info.="Gothra: <b>$gothra</b> ";

                        if($nakshatra && $nakshatra!="i don't know" && $nakshatra!="Don't Know")
                                $info.="Nakshatra: <b>$nakshatra</b> ";

                        if($mtongue)
                                $info.="Community: <b>$mtongue</b> ";
                        //added by Gaurav on 3 Nov 2006
                        if($edu_level)
                                        $info.="Education: <b>$edu_level</b> ";
                        if($occupation)
                                        $info.="Occupation: <b>$occupation</b> ";

			/*if($occupation)
                        {
                                $info.="<b>Occupation:</b>:"; 
                                if($edu_level)
                                        $info.="$edu_level,";
                                $info.="$occupation ";
                        }*/

                        if($my_income)
                        {
                                if($crmback)
                                        $info.="Annual Income: <b>$my_income</b> ";
                                elseif($gender=='M')
                                        $info.="Annual Income: <b>$my_income</b> ";
                        }
                        if($residence)
                                $info.="City: <b>$residence</b> ";
			//Added by Neha Verma to show manglik status in tuple
			if($manglik_status)
				$info.="Manglik: <b>$manglik_status</b>";
			//End
                        //2 change for photo
                        $profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];
                        /*if($havephoto=='L')
                        {
                                if($gender=='M')
                                        $image_file="login_to_view_photo_sm_b.gif";
                                else
                                        $image_file="login_to_view_photo_sm_g.gif";
                        }
                        elseif($havephoto=='C')
                        {
                                if($gender=='M')
                                        $image_file="photo_vis_if_con_acc_sm_b.gif";
                                else
                                        $image_file="photo_vis_if_con_acc_sm_g.gif";
                        }
			 elseif($havephoto=='F')
                        {        
                                if($gender=='M')
                                        $image_file="pro_fil_sm_b.gif";
                                else
                                        $image_file="pro_fil_sm_g.gif";
                        }*/
                        if($havephoto=='H')
                        {
                                if($gender=='M')
                                        $image_file="photo_hidden_sm_b.gif";
                                else
                                        $image_file="photo_hidden_sm_g.gif";
                        }
                        /*elseif($havephoto=='P')
                        {
                                if($gender=='M')
                                        $image_file="photo_fil_sm_b.gif";
                                else
                                        $image_file="photo_fil_sm_g.gif";
                        }*/
                        elseif($havephoto=='U')
                        {
                                if($gender=='M')
                                        $image_file="ph_cmgsoon_sm_b.gif";
                                else
                                        $image_file="ph_cmgsoon_sm_g.gif";
                        }
                        elseif($havephoto=="N")
                        {
                                if($gender=='M')
                                        $image_file="Request-a-photo-male_small.gif";
                                else
                                        $image_file="Request-a-photo-Female_small.gif";
                        }
			//if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
			if($havephoto=='H' || $havephoto=='U')
                        {
                                        $my_photo="<a href=\"#\" onClick=\"win_open('$SITE_URL/jsadmin/displayprofile.php?cid=$cid&profileid=$profileid&searchid=$myrow[PROFILEID]&page=$page');return false;\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
                        }
                        elseif($havephoto=='Y')
                        {
				//Symfony Photo Modification 
                                        $my_photo="<a href=\"#\" onClick=\"win_open('$SITE_URL/jsadmin/displayprofile.php?cid=$cid&profileid=$profileid&searchid=$myrow[PROFILEID]&page=$page');return false;\"><div style=\" float:left; width:60px; height:60px; margin:0 3px 3px 0; background-image:url($thumbnailUrl)\"><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" oncontextmenu=\"return false;\"></div></a>";
                        }
			elseif($havephoto=="N")
			{
				$my_photo="<a href=\"$SITE_URL/profile/photorequest.php?Submit=submit&offline=1&profileid=$profileid&profilechecksum=$profilechecksum\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\" onclick=\"win_open('$SITE_URL/profile/photorequest.php?Submit=submit&offline=1&profileid=$profileid&profilechecksum=$profilechecksum','','width=370,height=330'); return false;\" ></a>";
			}
				
				$sqlcat="SELECT CATEGORY,NOTE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$myrow[PROFILEID]' AND PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
				$rescat=mysql_query_decide($sqlcat) or logError($sqlcat);
				$rowcat=mysql_fetch_assoc($rescat);
				$category=$rowcat["CATEGORY"];
				$note=$rowcat["NOTE"];
				if($note == "SEND_MAIL")
					$note = "";	
				$phone_res='';
				$phone_mob='';
				$icon=0;

				if($page=="pool")
                                {
                               	       	$sqlcat="SELECT DATE,TYPE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER='$profileid' AND RECEIVER='$myrow[PROFILEID]' AND TYPE IN ('N','NNOW')";
					$rescat=mysql_query_decide($sqlcat) or logError($sqlcat);
					if(mysql_num_rows($rescat))
					{
						$rowcat=mysql_fetch_assoc($rescat);
					 	$date_added_temp=explode(" ",$rowcat["DATE"]);
						if($category==3 || $category==5 || $category==6)
						{
							if($rowcat["TYPE"]=="N")
								$date_added=$date_added_temp[0];
							else
							{
								list($year,$month,$day)=explode("-",$date_added_temp[0]);
								 $date_added_stamp=mktime(0, 0, 0, date($month),date($day)+3,date($year));
								$date_added=date("Y-m-d",$date_added_stamp);
							}
						}
						elseif($category==1)
						{
							$sqlcat2="SELECT DATE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER='$myrow[PROFILEID]' AND RECEIVER='$profileid' AND TYPE='NACC'";
							$rescat2=mysql_query_decide($sqlcat2) or logError($sqlcat2);
							$rowcat2=mysql_fetch_assoc($rescat2);
							$date_added_temp=explode(" ",$rowcat2["DATE"]);
							$date_added=$date_added_temp[0];
						}
						elseif($category==4)
						{
							$sqlcat2="SELECT ENTRY_DATE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY BILLID DESC LIMIT 1";
							$rescat2=mysql_query_decide($sqlcat2) or logError($sqlcat2);
							$rowcat2=mysql_fetch_assoc($rescat2);
							$date_added_temp=explode(" ",$rowcat2["ENTRY_DATE"]);
							$date_added=$date_added_temp[0];
						}
					}
					elseif($category=="2")
					{
						$sqlcat2="SELECT DATE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER='$myrow[PROFILEID]' AND RECEIVER='$profileid' AND TYPE='NACC' AND V_CON='Y'";
						$rescat2=mysql_query_decide($sqlcat2) or logError($sqlcat2);
						$rowcat2=mysql_fetch_assoc($rescat2);
						$date_added_temp=explode(" ",$rowcat2["DATE"]);
						$date_added=$date_added_temp[0];

					}
					else
						$date_added='';
					$date_added_list=explode("-",$date_added);
					$date_added=$date_added_list[2]."-".$date_added_list[1]."-".$date_added_list[0];
                                }
                                else
                                        $date_added='';

	
				$pscore='';	
				$sql_score="SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID= '$myrow[PROFILEID]'";
				$res_score= mysql_query_decide($sql_score) or logError($sql_score);
				$row_score= mysql_fetch_assoc($res_score);
				$pscore=$row_score["SCORE"];	
				if(isFlagSet("PHONERES",$myrow["SCREENING"]))
				$phone_res=$myrow["PHONE_RES"];
				else
				$phone_res='';

				if(isFlagSet("PHONEMOB",$myrow["SCREENING"]))
				$phone_mob=$myrow["PHONE_MOB"];
				else	
				$phone_mob='';

				if($myrow["SHOWPHONE_RES"]=="N" && $myrow["SHOWPHONE_MOB"]=="N")
				$icon=1;
				else
				{
					if($myrow["SHOWPHONE_RES"]=="Y" && $myrow["SHOWPHONE_MOB"]=="Y")
					{
						if($phone_res=='' && $phone_mob=='')
						$icon=1;
					}
					else
					{
						if($myrow["SHOWPHONE_RES"]=="Y" && $phone_res=='')
						$icon=1;
						elseif($myrow["SHOWPHONE_MOB"]=="Y" && $phone_mob=='')
						$icon=1;
					}
				}
				$pro_id=$myrow["PROFILEID"];
				$sql_date="SELECT DATE_FORMAT(MOD_DATE,'%d-%m-%Y') as sl_date FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$pro_id' AND CATEGORY!=''";
                		$res_date=mysql_query_decide($sql_date) or logError($sql_date);
                		$row_date=mysql_fetch_assoc($res_date);
                		$sl_date=$row_date["sl_date"];
				//Added by Vibhor for Astro Service in Offline module
				$compatibility_link=compatibility_link($data['PROFILEID'],$myrow['PROFILEID']);
				//end
				/*if($category==4)
				{
					unset($show_star);
					$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_EXPIRED_PROFILES WHERE PROFILEID='$myrow[PROFILEID]'";
					$res=mysql_query_decide($sql) or logError($sql);
					$row=mysql_fetch_assoc($res);
					$count_oep=$row['CNT'];
                                        if($count_oep>0)
                                	        $show_star=1;
                                }*/
				$RESULT_ARRAY[]=array("SNO" => $sno,
                                                "PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
                                                "PROFILEID" => $myrow["PROFILEID"],
                                                "PHOTOCHECKSUM" => $photochecksum,
                                                "USERNAME" => $myrow["USERNAME"],
                                                "INFO" => $info,
                                                "AGE" => $myrow["AGE"],
                                                "HEIGHT" => $height2,
                                                "CASTE" => $myCaste,
                                                "MTONGUE" =>$mtongue,
                                                "OCCUPATION" => $occupation,
                                                "RESIDENCE" => $residence,
                                                "YOURINFO" => $yourinfo,
                                                "MOD_DT" => $mod_date,
                                               	"HAVEPHOTO" => $havephoto,
                                                "CONTACTSTATUS" => $contacted1[$myrow["PROFILEID"]],
                                                "SENDER_OR_RECEIVER" => $contacted2[$myrow["PROFILEID"]],//added by lavesh(S / R)
                                                "PHOTO_REQ" => $photo_req,//added by lavesh
                                                //done for new service added called eclassified NEW CHANGES
                                                "CONTACT_DETAILS" => $contact_details,
                                                "DEGREE" => $edu_level,
                                                "PROTITLE" => $protitle,
                                                "INCOME" => $income_map["$income"],
                                                "STAT_UNAME"=>$stat_uname,
                                                "LAST_LOGIN_DT"=>$myrow['LAST_LOGIN_DT'],
                                                "GENDER" => $myrow["GENDER"],
                                                "SUBCASTE" => $subcaste,
                                                "GOTHRA"=> $myrow["GOTHRA"],
                                                "NAKSHATRA"=>$myrow["NAKSHATRA"],
                                                "PHOTOCHECKSUM_NEW" => $photochecksum_new,
						"MY_PHOTO" =>$my_photo,
						"CATEGORY"=>$category,
						"ICON"=>$icon,
						"ACTIVATED"=>$myrow["ACTIVATED"],
						"PSCORE"=>$pscore,
						"MESSAGE1"=>$note,
						"DATE_ADDED"=>$date_added,
						"SL_DATE"=>$sl_date,
						"COMPATIBILITY_LINK"=>$compatibility_link,
						"SOURCE"=>$myrow["SOURCE"],
						"MEMBER_101"=>$member_101,
						"EMAIL"=>$myrow["EMAIL"]);
                       $sno++;
              }
			mysql_free_result($result1);
		}
		}
		if($scriptname=="/jsadmin/pool_matches.php")
		{
			if(is_array($jprofile_email_arr))
			{
				unset($emailstr);
				
				for($emailcnt=0;$emailcnt<count($emailarr);$emailcnt++)
				{
					
					if(!in_array($emailarr[$emailcnt],$jprofile_email_arr))
					{
						$emailstr.="'".$emailarr[$emailcnt]."',";
					}
				}
				$emailstr=trim($emailstr,",");
			}
			if($emailstr)
			{
			$sql="SELECT PROFILEID,USERNAME,AGE,HEIGHT,CASTE,MANGLIK,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,SPOUSE,GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,LAST_LOGIN_DT,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,PHONE_MOB,RELIGION,SHOWPHONE_MOB,SHOWPHONE_RES,ACTIVATED,PHONE_RES,EMAIL FROM jsadmin.AFFILIATE_DATA WHERE EMAIL IN($emailstr)";
                        $result1=mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result1))
                {
			unset($info);
			unset($subcaste);
			unset($my_photo);
			unset($subcaste);
			unset($edu_level);
			unset($income);
			unset($info);
			unset($myCaste);
			unset($height2);
			$RESULT_ARRAY_3d[]=$myrow["PROFILEID"];

                        //Added by Neha Verma to show manglik status in tuple
                        $manglik_st=$myrow["MANGLIK"];
                        if($manglik_st=='M')
                                $manglik_status="Yes";
                        elseif($manglik_st=='N')
                                $manglik_status="No";
                        else
                                $manglik_status="Don't Know";

                        //End
                        $income=$myrow["INCOME"];
                        $occ=$myrow["OCCUPATION"];
                        if($lang=='hin')
                        {
                                $occupation_temp=label_select("HIN_OCCUPATION",$occ);
                                $occupation=$occupation_temp[0];
                                unset($occupation_temp);
                        }
                        else
                        {
                                $occupation=$OCCUPATION_DROP["$occ"];
                        }
			$caste1=$myrow["CASTE"];
                        if($lang=='hin')
                        {
                                $caste_temp=label_select("HIN_CASTE",$caste1);
                                $caste=$caste_temp[0];
                                unset($caste_temp);
                        }
                        else
                        {
                                $caste=$CASTE_DROP["$caste1"];
                        }
                        if($lang=='hin')
                        {
                                $mtongue1 = label_select("HIN_MTONGUE",$myrow['MTONGUE']);
                        }
                         else
                        {
                                $mtongue_temp=$myrow['MTONGUE'];
                                $mtongue1[0] = $MTONGUE_DROP["$mtongue_temp"];
                        }
                        $mtongue = $mtongue1[0];
			$subcaste=trim($myrow["SUBCASTE"]);
			$yourinfo=$myrow["YOURINFO"];
			$spouseinfo=$myrow["SPOUSE"];
			$heightn=$myrow["HEIGHT"];
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
                                {
                                        if($lang=='hin')
                                        {
                                                $residence_temp=label_select("HIN_CITY_INDIA",$city_res1);
                                                $residence=$residence_temp[0];
                                                unset($residence_temp);
                                        }
                                        else
                                                $residence=$CITY_INDIA_DROP["$city_res1"];
                                }
                                else
                                        $residence=$CITY_USA_DROP["$city_res1"];
                        }
			else
                        {
                                $country1=$myrow["COUNTRY_RES"];
                                if($lang=='hin')
                                {
                                        $residence_temp=label_select("HIN_COUNTRY",$country1);
                                        $residence=$residence_temp[0];
                                        unset($residence_temp);
                                }
                                else
                                        $residence=$COUNTRY_DROP["$country1"];
                        }

                        $newCaste=explode(":",$caste);
                        if(trim($newCaste[1])!="")
                                $myCaste=$newCaste[1];
			$myrow['RELIGION']=$newCaste[0];
			$age=$myrow["AGE"];
                        $gothra=trim($myrow["GOTHRA"]);
                        $my_income=$income_map["$income"];
                        $gender=$myrow["GENDER"];
                        $nakshatra=trim($myrow["NAKSHATRA"]);

                        if($age)
                                $info="Age: <b>$age</b> ";
                        if($height2)
                                $info.="Height: <b>$height2</b> ";
                        if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)
                                $info.="Religion: <b>".$myrow['RELIGION']."</b> " ;
                        if($myCaste)
                                $info.="Caste: <b>$myCaste</b> ";
                        if($subcaste)
                                $info.="($subcaste) ";
                        if($gothra && $gothra!="i don't know")
                                $info.="Gothra: <b>$gothra</b> ";

                        if($nakshatra && $nakshatra!="i don't know" && $nakshatra!="Don't Know")
                                $info.="Nakshatra: <b>$nakshatra</b> ";

                        if($mtongue)
                                $info.="Community: <b>$mtongue</b> ";
                        //added by Gaurav on 3 Nov 2006
                        if($edu_level)
                                        $info.="Education: <b>$edu_level</b> ";
                        if($occupation)
                                        $info.="Occupation: <b>$occupation</b> ";
			 if($my_income)
                        {
                                if($crmback)
                                        $info.="Annual Income: <b>$my_income</b> ";
                                elseif($gender=='M')
                                        $info.="Annual Income: <b>$my_income</b> ";
                        }
                        if($residence)
                                $info.="City: <b>$residence</b> ";
                        //Added by Neha Verma to show manglik status in tuple
                        if($manglik_status)
                                $info.="Manglik: <b>$manglik_status</b>";
                        //End
                        //2 change for photo
                        $profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];

			if($gender=='M')
                                        $image_file="Request-a-photo-male_small.gif";
                                else
                                        $image_file="Request-a-photo-Female_small.gif";

			$my_photo="<img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\">";
			$RESULT_ARRAY[]=array("SNO" => $sno,
                                                "PROFILECHECKSUM" => md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"],
                                                "PROFILEID" => $myrow["PROFILEID"],
                                                "USERNAME" => $myrow["USERNAME"],
                                                "INFO" => $info,
                                                "AGE" => $myrow["AGE"],
                                                "HEIGHT" => $height2,
                                                "CASTE" => $myCaste,
                                                "MTONGUE" =>$mtongue,
                                                "OCCUPATION" => $occupation,
                                                "RESIDENCE" => $residence,
                                                "YOURINFO" => $yourinfo,
                                                "DEGREE" => $edu_level,
                                                "INCOME" => $income_map["$income"],
						"GENDER" => $myrow["GENDER"],
                                                "SUBCASTE" => $subcaste,
                                                "GOTHRA"=> $myrow["GOTHRA"],
                                                "NAKSHATRA"=>$myrow["NAKSHATRA"],
                                                "MY_PHOTO" =>$my_photo,
                                                "CATEGORY"=>"7",
						"EMAIL"=>$myrow["EMAIL"]);
                       $sno++;
		}
		}
		}
		if($scriptname=="/jsadmin/pool_matches.php")
		{
			for($z=0;$z<count($RESULT_ARRAY_3d);$z++)
                        {
                                $key = array_search($RESULT_ARRAY[$z]['EMAIL'],$emailarr);
                                $RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
                        }
		}
		else
		{
        		for($z=0;$z<count($RESULT_ARRAY_3d);$z++)
			{
				$key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$RESULT_ARRAY_3d);
				$RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
			}
		}
		/*********************************************************************************************************************
Changed by      : Shakti Srivastava
Change Date     : 26 July 2005
Reason          : Number of tables that has to be shown is needed while displaying the data in the template
*********************************************************************************************************************/
                $temp=count($RESULT_ARRAY);
                $table_cnt=ceil($temp/3);
                $smarty->assign("RESULTS_ARRAY_COUNT",$temp);

                $smarty->assign("table_cnt",$table_cnt);

                $smarty->assign("RECORDCOUNT",$totalrec);

                if($marriage_bureau_profile)
                        $smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY);
                else
                        $smarty->assign("RESULTS_ARRAY",$RESULT_ARRAY_FINAL);

                if( $curcount )
                        $cPage = ($curcount/$PAGELEN) + 1;
                else
                        $cPage = 1;
               
                        $PAGELEN = 10;
		if(!$js_search_recomend)
                {
                        if($moreurl=="")
                                //pagelink($PAGELEN,$totalrec,$cPage,10,$checksum,$scriptname,$searchchecksum,$newsearch);
                                pagelink($PAGELEN,$totalrec,$cPage,10,$checksum,$scriptname,$searchchecksum,$newsearch,$inf_checksum,$castemapping,$crmback,$ordering,$savesearch,$label_select_no,$from_index,"","",$save_search_redirect);
                        else
                                //pagelink($PAGELEN,$totalrec,$cPage,10,"$checksum&$moreurl",$scriptname,$searchchecksum,$newsearch);
                                pagelink($PAGELEN,$totalrec,$cPage,10,"$checksum&$moreurl",$scriptname,$searchchecksum,$newsearch,$inf_checksum,$castemapping,$crmback,$ordering,$savesearch,$label_select_no,$from_index,"","",$save_search_redirect);
                }

                $smarty->assign("newsearch",$newsearch);
                $smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));
                $smarty->assign("CURPAGE",$cPage);
                $smarty->assign("BACK_TO_SEARCH_PAGE",$curcount);
                $smarty->assign("SCRIPTNAME",$scriptname);

                //Added By lavesh for new search templates 
                if ($from_index && !$data)
                {
                        $profile_start=($cPage-1)*6+1;

                        if($profile_start+5<$totalrec)
                                $profile_end=$profile_start+5;
                        else
                                $profile_end=$totalrec;
                }
		 elseif (($from_index && $data) || !$from_index)
                {
                        $profile_start=($cPage-1)*12+1;

                        if($profile_start+11<$totalrec)
                                $profile_end=$profile_start+11;
                        else
                                $profile_end=$totalrec;
                }

                $smarty->assign("profile_start",$profile_start);
                $smarty->assign("profile_end",$profile_end);

		$a=array('a1','a2','a3','a4','a5','a6','a7','a8','a9','a10','a11','a12');
		$b=array('b1','b2','b3','b4','b5','b6','b7','b8','b9','b10','b11','b12');
		$c=array('c1','c2','c3','c4','c5','c6','c7','c8','c9','c10','c11','c12');
		$d=array('d1','d2','d3','d4','d5','d6','d7','d8','d9','d10','d11','d12');
		$e=array('e1','e2','e3','e4','e5','e6','e7','e8','e9','e10','e11','e12');
		$f=array('f1','f2','f3','f4','f5','f6','f7','f8','f9','f10','f11','f12');
		$n=array('n1','n2','n3','n4','n5','n6','n7','n8','n9','n10','n11','n12');
		$boxheight=array('one','two','three','four','five','six','seven','eight','nine','ten','eleven','tewelve');
		$box1="a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12";
		$box2="b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12";
		$box3="c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12";
		$box4="one,two,three,four,five,six,seven,eight,nine,ten,eleven,tewelve";
                $smarty->assign("box4",$box4);
                $smarty->assign("box1",$box1);
                $smarty->assign("box3",$box3);
                $smarty->assign("box2",$box2);
                $smarty->assign("a",$a);
                $smarty->assign("b",$b);
                $smarty->assign("c",$c);
                $smarty->assign("d",$d);
                $smarty->assign("e",$e);
                $smarty->assign("f",$f);
                $smarty->assign("n",$n);
                $smarty->assign("boxheight",$boxheight);
                //Ends here

                //return $RESULT_ARRAY_FINAL;
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
function assigndetails($pid,$profileid)
{
   
	global $smarty,$db,$jprofile_result,$PHOTO_URL;
	mysql_select_db_js("newjs");
        $path=$_SERVER['DOCUMENT_ROOT'];
        include("$path/profile/arrays.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        include_once("$path/profile/manglik.php");
        include_once("$path/profile/functions.inc");
        $VIEWPROFILE_IMAGE_URL="http://ser2.jeevansathi.com/profile";

	//Symfony Photo Modification 
	$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($profileid);
        $profilePicObj = $profilePicObjs[$profileid];
        if ($profilePicObj)
        {
                $profilePicUrl = $profilePicObj->getProfilePicUrl();
        }
        else
        {
                $profilePicUrl = null;
        }

	//Added by Vibhor for Astro Service in Offline module
	$compatibility_link=compatibility_link($pid,$profileid);
	$smarty->assign("COMPATIBILITY_LINK",$compatibility_link);
	//end
        $smarty->assign("VIEWPROFILE","Y");
        $profilechecksum=md5($profileid) . "i" . $profileid;
        $smarty->assign("PROFILECHECKSUM",$profilechecksum);
        $SCREENING_MESSAGE="<span class=\"smallred\">This field is currently being screened. Please re-check shortly.</span>";
        include_once("$path/profile/reduce_jprofilequery.php");
        limiting_jprofile_query($pid,$profileid);
        $smarty->assign("sim_contact",$profileid);
        $smarty->assign("viewed_gender",$jprofile_result["viewed"]["GENDER"]);
        $privacy=$jprofile_result["viewer"]["PRIVACY"];
         if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],'F') || strstr($jprofile_result["viewed"]['SUBSCRIPTION'],'D'))
                $paid='Y';
        else
                $paid='N';
        $sql_views="insert into MIS.VIEW_FOR_MIS(PROFILEID,GENDER,PAID,PHOTO,MTONGUE,CASTE,DATE,MATCHALERT,CONTACT_MATCHALERT) VALUES('$profileid','".$jprofile_result[viewed][GENDER]."','$paid','".$jprofile_result[viewed][HAVEPHOTO]."','".$jprofile_result[viewed][MTONGUE]."','".$jprofile_result[viewed][CASTE]."',now(),'$frommatchalert','$contact_matchalert')";
        $result_views=mysql_query_optimizer($sql_views) ;//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_views,"ShowErrTemplate");
        $smarty->assign("AGE",$jprofile_result["viewed"]["AGE"] . " years");

			/*
        		$mobile=$jprofile_result["viewed"]["PHONE_MOB"];
			$sql_mob="SELECT count(*) AS cnt FROM newjs.MOBILE_VERIFICATION_SMS AS M  WHERE M.MOBILE='$mobile'";

                        $result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
                        $row_mob=mysql_fetch_array($result_mob);
			*/

			// IVR - Phone Verification check
			$profile_params =$jprofile_result["viewed"];				
			$chk_phoneStatus =getPhoneStatus($profile_params);
                        if($chk_phoneStatus =='Y')
                                $mob_verified='Y';
                        else
                                $mob_verified='N';
                        if(($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" || $jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y") && $mob_verified=='Y')
                                $show_mob="Y";
                        else
                                $show_mob="N";
			// Ends IVR

        if($jprofile_result["viewed"]["GENDER"]=='F')
        	$mob_gender="her";
        else
        	$mob_gender="his";

	$Parents_Contact = $jprofile_result["viewed"]['PARENTS_CONTACT'];
                $Address = $jprofile_result["viewed"]['CONTACT'];
                $pincode = $jprofile_result["viewed"]['PINCODE'];
                $State_Code = $jprofile_result["viewed"]["STD"];
                $Phone = $jprofile_result["viewed"]["PHONE_RES"];
                $Mobile = $jprofile_result["viewed"]["PHONE_MOB"];
                $Messenger_ID = $jprofile_result["viewed"]["MESSENGER_ID"];
                $messenger=$jprofile_result["viewed"]["MESSENGER_CHANNEL"];
        if($jprofile_result["viewed"]["HAVEPHOTO"]=="Y")
        {
		//Symfony Photo Modification - start
		$haveScreenedMainPhoto = SymfonyPictureFunctions::haveScreenedMainPhoto($profileid);//considering screened photos only
		if($haveScreenedMainPhoto!=NULL)
		{
			$main_photo_is_screened=1;
			$album = SymfonyPictureFunctions::getAlbum($profileid);
			if($album[1])
				$smarty->assign("ISALBUM","1");
		}
			

                if($main_photo_is_screened)
		//Symfony Photo Modification - end
                {
                        $smarty->assign("FULLVIEW","1");
                        $smarty->assign("PHOTOFILE",$album['profile']);
                        if($jprofile_result["viewed"]["PHOTO_DISPLAY"]=="H")
                                {
                                        $smarty->assign("FULLVIEW","");
                                        $smarty->assign("ISALBUM","");
                                        //$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photo_hidden.gif");
                                        if($jprofile_result["viewed"]["GENDER"]=="M")
                                                $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ph_hidden_b.gif");
                                        else
                                                $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ph_hidden_g.gif");
                                }
		}
                else
                {
                        $smarty->assign("FULLVIEW","");
                        $smarty->assign("ISALBUM","");
                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming.gif");
                }
        }
                // main photo is being screened
                elseif($jprofile_result["viewed"]["HAVEPHOTO"]=="U" || $jprofile_result["viewed"]["HAVEPHOTO"]=="E")
                {
                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming.gif");
                }
                else
                {
                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/no_photo.gif");
                }
                $contactperson=$jprofile_result["viewed"]["USERNAME"];
                $smarty->assign("PROFILENAME",$jprofile_result["viewed"]["USERNAME"]);
                $smarty->assign("GENDER",$jprofile_result["viewed"]["GENDER"]);
                $height=$jprofile_result["viewed"]["HEIGHT"];
                $height1=explode("(",$HEIGHT_DROP["$height"]);
                $smarty->assign("HEIGHT",$height1[0]);
                $return_data=manglik($profileid,'viewed');
                $manglik_data=explode("+",$return_data);
                $smarty->assign("Own_Manglik_Status",$manglik_data[0]);
                $smarty->assign("Own_Manglik","Manglik");
                $smarty->assign("RELATION",$RELATIONSHIP[$jprofile_result["viewed"]["RELATION"]]);
                $smarty->assign("PROFILEGENDER",$GENDER[$jprofile_result["viewed"]["GENDER"]]);
                $smarty->assign("MSTATUS",$MSTATUS[$jprofile_result["viewed"]["MSTATUS"]]);
                $smarty->assign("CHILDREN",$CHILDREN[$jprofile_result["viewed"]["HAVECHILD"]]);
                $smarty->assign("MANGLIK",$MANGLIK[$jprofile_result["viewed"]["MANGLIK"]]);
                $smarty->assign("BODYTYPE",$BODYTYPE[$jprofile_result["viewed"]["BTYPE"]]);
                $smarty->assign("COMPLEXION",$COMPLEXION[$jprofile_result["viewed"]["COMPLEXION"]]);
                $smarty->assign("DIET",$DIET[$jprofile_result["viewed"]["DIET"]]);
                $smarty->assign("SMOKE",$SMOKE[$jprofile_result["viewed"]["SMOKE"]]);
		$smarty->assign("DRINK",$DRINK[$jprofile_result["viewed"]["DRINK"]]);
                $smarty->assign("RSTATUS",$RSTATUS[$jprofile_result["viewed"]["RES_STATUS"]]);
                $smarty->assign("HANDICAPPED",$HANDICAPPED[$jprofile_result["viewed"]["HANDICAPPED"]]);
                $caste=$jprofile_result["viewed"]["CASTE"];
                $caste=$CASTE_DROP["$caste"];
                $mtongue = array($MTONGUE_DROP[$jprofile_result["viewed"]["MTONGUE"]]);
                $religion=array($RELIGIONS[$jprofile_result["viewed"]["RELIGION"]]);
                $income=label_select("INCOME",$jprofile_result["viewed"]["INCOME"]);
                $edu_level=label_select("EDUCATION_LEVEL",$jprofile_result["viewed"]["EDU_LEVEL"]);
                $edu_level_new=array($EDUCATION_LEVEL_NEW_DROP[$jprofile_result["viewed"]["EDU_LEVEL_NEW"]]);

                $family_back=label_select("FAMILY_BACK",$jprofile_result["viewed"]["FAMILY_BACK"]);
                $family_type=$FAMILY_TYPE[$jprofile_result["viewed"]['FAMILY_TYPE']];
                $family_status=$FAMILY_STATUS[$jprofile_result["viewed"]['FAMILY_STATUS']];
                $mother_occ=label_select("MOTHER_OCC",$jprofile_result["viewed"]['MOTHER_OCC']);
                $tbrother=$jprofile_result["viewed"]['T_BROTHER'];
                $mbrother=$jprofile_result["viewed"]['M_BROTHER'];
                $tsister=$jprofile_result["viewed"]['T_SISTER'];
                $msister=$jprofile_result["viewed"]['M_SISTER'];
                $occupation=$jprofile_result["viewed"]["OCCUPATION"];
                $country_birth=$jprofile_result["viewed"]["COUNTRY_BIRTH"];
                $country_res=$jprofile_result["viewed"]["COUNTRY_RES"];
                $occupation=$OCCUPATION_DROP["$occupation"];
                $country_birth=$COUNTRY_DROP["$country_birth"];
                $country_res=$COUNTRY_DROP["$country_res"];
                $wife_working=$jprofile_result["viewed"]["WIFE_WORKING"];
                if($wife_working=="Y")
                        $smarty->assign("WORKINGSPOUSE","She should be working");
                elseif($wife_working=="N")
                        $smarty->assign("WORKINGSPOUSE","She should be homemaker");
                elseif($wife_working=="D")
                        $smarty->assign("WORKINGSPOUSE","Doesn't matter");
                elseif($wife_working=="")
                        $smarty->assign("WORKINGSPOUSE","-");

		$married_working=$jprofile_result["viewed"]["MARRIED_WORKING"];
                if($married_working=="Y")
                        $smarty->assign("CAREER_AFTER_MARRIAGE","Plan to work after marriage.");
                else
                        $smarty->assign("CAREER_AFTER_MARRIAGE","");
                $parents_city_same=$jprofile_result["viewed"]["PARENT_CITY_SAME"];
                if($parents_city_same=="Y")
                        $smarty->assign("LIVE_WITH_PARENTS","Yes");
                elseif($parents_city_same=="N")
                        $smarty->assign("LIVE_WITH_PARENTS","No");
                elseif($parents_city_same=="D")
                        $smarty->assign("LIVE_WITH_PARENTS","Not Applicable");
                elseif($parents_city_same=="")
                        $smarty->assign("LIVE_WITH_PARENTS","-");

                $family_values=$jprofile_result["viewed"]["FAMILY_VALUES"];
                if($family_values=="")
                        $smarty->assign("FAMILY_VALUES","-");
                else
                        $smarty->assign("FAMILY_VALUES",$FAMILY_VALUES[$family_values]);
                if($caste=="")
                $caste="-";

        if($mtongue[0]=="")
                $mtongue[0]="-";

        if($religion[0]=="")
                $religion[0]="-";

        if($income[0]=="")
                $income[0]="-";

        if($edu_level[0]=="")
                $edu_level[0]="-";

	if($occupation=="")
                $occupation="-";

        if($country_birth=="")
                $country_birth="-";

        if($country_res=="")
                $country_res="-";
                if($jprofile_result["viewed"]["COUNTRY_RES"]=="51")
        {
                $city_res=$jprofile_result["viewed"]["CITY_RES"];
                $city_res=$CITY_INDIA_DROP["$city_res"];
        }
        elseif($jprofile_result["viewed"]["COUNTRY_RES"]=="128")
        {
                $city_res=$jprofile_result["viewed"]["CITY_RES"];
                $city_res=$CITY_USA_DROP["$city_res"];
        }
        else
                $city_res="";
        if($jprofile_result["viewed"]['SHOW_HOROSCOPE']=='Y')
        {
                $sql_horo = "SELECT COUNTRY_BIRTH,CITY_BIRTH FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
                $res_horo = mysql_query_optimizer($sql_horo);
                $row_horo = mysql_fetch_array($res_horo);
                $astro_city_birth = $row_horo['CITY_BIRTH'];
                $smarty->assign("COUNTRY_BIRTH",$row_horo['COUNTRY_BIRTH']);
        }
        else
                $smarty->assign("COUNTRY_BIRTH",$country_birth);
        $smarty->assign("COUNTRY_RES",$country_res);
        $smarty->assign("CITY_RES",$city_res);
        $smarty->assign("OCCUPATION",$occupation);
        $smarty->assign("EDUCATION_LEVEL",$edu_level[0]);
        $smarty->assign("INCOME",$income[0]);
        $smarty->assign("RELIGION_SELF",$religion[0]);
	$smarty->assign("MTONGUE",$mtongue[0]);
        $smarty->assign("CASTE",$caste);
        $smarty->assign("EDU_LEVEL_NEW",$edu_level_new[0]);
        $smarty->assign("FAMILY_BACK",$family_back[0]);
        $smarty->assign("MOTHER_OCC",$mother_occ[0]);
        $smarty->assign("FAMILY_TYPE",$family_type);
        $smarty->assign("FAMILY_STATUS",$family_status);
        if($tbrother==4)
                $tbrother="3+";
        if($mbrother==4)
                $mbrother="3+";
        if($tsister==4)
                $tsister="3+";
        if($msister==4)
                $msister="3+";
        $smarty->assign("T_BROTHER",$tbrother);
        $smarty->assign("M_BROTHER",$mbrother);
        $smarty->assign("T_SISTER",$tsister);
        $smarty->assign("M_SISTER",$msister);

        if($jprofile_result["viewed"]["BTIME"]!="")
        {
                $btime=explode(":",$jprofile_result["viewed"]["BTIME"]);
                $smarty->assign("BTIMEHOUR",$btime[0]);
                $smarty->assign("BTIMEMIN",$btime[1]);
        }

        if($astro_city_birth)
        {
                $smarty->assign("CITYBIRTH",$astro_city_birth);
        }
         else
        {
                if($jprofile_result["viewed"]["CITY_BIRTH"]=="")
                        $smarty->assign("CITYBIRTH","-");
                elseif(isFlagSet("CITYBIRTH",$jprofile_result["viewed"]["SCREENING"]))
		$smarty->assign("CITYBIRTH",ucwords($jprofile_result["viewed"]["CITY_BIRTH"]));
                elseif($PERSON_HIMSELF && !$search)
                        $smarty->assign("CITYBIRTH",ucwords($jprofile_result["viewed"]["CITY_BIRTH"]) . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                        $smarty->assign("CITYBIRTH",$SCREENING_MESSAGE);
        }
        if($jprofile_result["viewed"]["SUBCASTE"]=="")
                $smarty->assign("SUBCASTE","-");
        elseif(isFlagSet("SUBCASTE",$jprofile_result["viewed"]["SCREENING"]))
                $smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"]);
        elseif($PERSON_HIMSELF && !$search)
                $smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"] . "<br>" . $SCREENING_MESSAGE_SELF);
        else
                $smarty->assign("SUBCASTE",$SCREENING_MESSAGE);
        if(isFlagSet("YOURINFO",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["YOURINFO"]))
                {
                        $yourinfo1=trim($jprofile_result["viewed"]["YOURINFO"]);
                        $len=strlen($yourinfo1);
                        $flag=0;
                        for($i=0;$i<$len;$i++)
                        {
                                if($yourinfo1[$i]==' ')
                                {
                                        $flag++;
                                }
                                if($flag<3)
                                {
                                        $subyourinfo.=$yourinfo1[$i];
                                }
                                else
                                {
                                        $yourinfo.=$yourinfo1[$i];
				$flag++;
                                }
                        }
                }
                $smarty->assign("SUBYOURINFO","<b>".$subyourinfo."</b>");
        }
        if(isFlagSet("JOB_INFO",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["JOB_INFO"]))
                {
                        if ($PERSON_HIMSELF)
                                $jobinfo = "<b>My Job:</b> ".$jprofile_result["viewed"]["JOB_INFO"];
                        else
                                $yourinfo.="\n\n<b>My Job:</b> ".$jprofile_result["viewed"]["JOB_INFO"];
                }
        }
        if(isFlagSet("SPOUSE",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["SPOUSE"]))
                {
                        if ($PERSON_HIMSELF && !$search)
                                $spouseinfo = "<b>Looking for:</b> ".$jprofile_result["viewed"]["SPOUSE"];
                        else
                                $yourinfo.="\n\n<b>Looking for:</b> ".$jprofile_result["viewed"]["SPOUSE"];
                }
        }

        $smarty->assign("YOURINFO",nl2br($yourinfo));
        $smarty->assign("JOBINFO",nl2br($jobinfo));
        $smarty->assign("SPOUSEINFO",nl2br($spouseinfo));
	if(isFlagSet("FATHER_INFO",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["FATHER_INFO"]))
                        $familyinfo=$jprofile_result["viewed"]["FATHER_INFO"];
        }
        if(isFlagSet("SIBLING_INFO",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["SIBLING_INFO"]))
                        $familyinfo.="\n".$jprofile_result["viewed"]["SIBLING_INFO"];
        }

        if(isFlagSet("FAMILYINFO",$jprofile_result["viewed"]["SCREENING"]) || ($PERSON_HIMSELF && !$search))
        {
                if(trim($jprofile_result["viewed"]["FAMILYINFO"]))
                        $familyinfo.="\n".$jprofile_result["viewed"]["FAMILYINFO"];
        }
        $smarty->assign("FAMILYINFO",nl2br($familyinfo));

        if($jprofile_result["viewed"]["GOTHRA"]=="")
                $smarty->assign("GOTHRA","-");
        elseif(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
                $smarty->assign("GOTHRA",$jprofile_result["viewed"]["GOTHRA"]);
        else
                $smarty->assign("GOTHRA",$SCREENING_MESSAGE);
        if($jprofile_result["viewed"]["NAKSHATRA"]=="")
                $smarty->assign("NAKSHATRA","-");
        else
                $smarty->assign("NAKSHATRA",$jprofile_result["viewed"]["NAKSHATRA"]);
        if($jprofile_result["viewed"]["EDUCATION"]=="")
                $smarty->assign("EDUCATION","-");
        elseif(isFlagSet("EDUCATION",$jprofile_result["viewed"]["SCREENING"]))
                $smarty->assign("EDUCATION",nl2br($jprofile_result["viewed"]["EDUCATION"]));
         else
                $smarty->assign("EDUCATION",$SCREENING_MESSAGE);
        $sql="select SQL_CACHE PROFILEID from newjs.HIDE_DOB where PROFILEID='$profileid'";
        $hideresult=mysql_query_optimizer($sql);
	if ($hideresult && mysql_num_rows($hideresult)<=0)
        {
                $dob=explode("-",$jprofile_result["viewed"]["DTOFBIRTH"]);
                $smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));

                unset($dob);
        }

        $dob=explode("-",substr($jprofile_result["viewed"]["MOD_DT"],0,10));

        if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
                $smarty->assign("MOD_DATE",my_format_date($dob[2],$dob[1],$dob[0]));

        unset($dob);

        $dob=explode("-",$jprofile_result["viewed"]["LAST_LOGIN_DT"]);

        if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
                $smarty->assign("LAST_LOGIN_DT",my_format_date($dob[2],$dob[1],$dob[0]));
        $sql="select * from newjs.JHOBBY where PROFILEID='$profileid'";
        $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        if(mysql_num_rows($result) > 0)
        {
                $myrow=mysql_fetch_array($result);

                $sql="select SQL_CACHE VALUE,LABEL,TYPE from newjs.HOBBIES order by SORTBY";
                $result_hobby=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                while($myhobby=mysql_fetch_array($result_hobby))
                {
                        $HOBBIES_ARR[$myhobby["VALUE"]]=array("LABEL" => $myhobby["LABEL"],
                                                                "TYPE" => $myhobby["TYPE"]);
                }
		mysql_free_result($result_hobby);

                $myhobbies=explode(",",$myrow["HOBBY"]);

                $hobbycount=count($myhobbies);

                for($i=0;$i<$hobbycount;$i++)
                {
                        $label=$HOBBIES_ARR[$myhobbies[$i]]["LABEL"];
                        $type=$HOBBIES_ARR[$myhobbies[$i]]["TYPE"];

                        ${$type}[]=$label;
                }

                if(is_array($HOBBY))
                        $smarty->assign("HOBBY",implode(", ",$HOBBY));
                if(is_array($INTEREST))
                        $smarty->assign("INTEREST",implode(", ",$INTEREST));

                if(is_array($MUSIC))
                        $smarty->assign("MUSIC",implode(", ",$MUSIC));

                if(is_array($BOOK))
                        $smarty->assign("BOOK",implode(", ",$BOOK));

                if(is_array($MOVIE))
                        $smarty->assign("MOVIE",implode(", ",$MOVIE));

                if(is_array($SPORTS))
                        $smarty->assign("SPORTS",implode(", ",$SPORTS));

                if(is_array($CUISINE))
                        $smarty->assign("CUISINE",implode(", ",$CUISINE));

                if(is_array($DRESS))
                        $smarty->assign("DRESS",implode(", ",$DRESS));
		if(is_array($LANGUAGE))
                        $smarty->assign("LANGUAGE",implode(", ",$LANGUAGE));

                if($myrow["ALLMUSIC"]=="N")
                        $smarty->assign("MUSIC","Not too keen on music");
                elseif($myrow["ALLMUSIC"]=="Y")
                        $smarty->assign("MUSIC","Enjoy most forms of music");

                if($myrow["ALLBOOK"]=="N")
                        $smarty->assign("BOOK","Not much of a reader");
                elseif($myrow["ALLBOOK"]=="Y")
                        $smarty->assign("BOOK","Love reading almost anything");
                if($myrow["ALLMOVIE"]=="N")
                        $smarty->assign("MOVIE","Not a movie buff");
                elseif($myrow["ALLMOVIE"]=="Y")
                        $smarty->assign("MOVIE","Love all kinds of movies");

                if($myrow["ALLSPORTS"]=="N")
                        $smarty->assign("SPORTS","Not a sportsperson");

                if($myrow["ALLCUISINE"]=="N")
                        $smarty->assign("CUISINE","Not much of a food-lover");
                elseif($myrow["ALLCUISINE"]=="Y")
                        $smarty->assign("CUISINE","Anything edible is great!");
          }
		else
                {
                        $smarty->assign("NOHOBBY","1");
                }
                //Sharding Concept added by Vibhor Garg on table JPARTNER
		include_once($path."/classes/Mysql.class.php");
                include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
                include_once($path."/classes/shardingRelated.php");
                $mysqlObj=new Mysql;
                $jpartnerObj=new Jpartner;
                $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
                $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
 
                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
                {
                	$HAVE_PARTNER=true;
	                $other_user_activated=$jprofile_result["viewed"]["ACTIVATED"];
                	if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                	{
				$FILTER_LAGE=$jpartnerObj->getLAGE();
				$FILTER_HAGE=$jpartnerObj->getHAGE();
				if($lang)
					$smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " से " . $$jpartnerObj->getHAGE() . " तक");
				else
					$smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " to " . $jpartnerObj->getHAGE());
                	}
                else
                        $smarty->assign("PARTNER_AGE","-");

                if($jpartnerObj->getLHEIGHT()!="" && $jpartnerObj->getHHEIGHT()!="")
                {
                        $lheight=$jpartnerObj->getLHEIGHT();
                        $lheight=$HEIGHT_DROP["$lheight"];

                        $hheight=$jpartnerObj->getHHEIGHT();
                        $hheight=$HEIGHT_DROP["$hheight"];

                        $lheight1=explode("(",$lheight);
                        $hheight1=explode("(",$hheight);

                        if($lang)
                                $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " से " . $hheight1[0] . " तक");
                        else
                                $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);
                }
                else
                        $smarty->assign("PARTNER_HEIGHT","-");
		if($jpartnerObj->getCHILDREN()=="")
                                $smarty->assign("PARTNER_CHILDREN","   - ");
                        elseif($jpartnerObj->getCHILDREN()=="N")
                                $smarty->assign("PARTNER_CHILDREN","No");
                        elseif($jpartnerObj->getCHILDREN()=="Y")
                                $smarty->assign("PARTNER_CHILDREN","Yes");
                        if($jpartnerObj->getHANDICAPPED()=="")
                                $smarty->assign("PARTNER_HANDICAPPED","   - ");
                        elseif($jpartnerObj->getHANDICAPPED()=="N")
                                $smarty->assign("PARTNER_HANDICAPPED","No");
                        elseif($jpartnerObj->getHANDICAPPED()=="Y")
                                $smarty->assign("PARTNER_HANDICAPPED","Yes");
		
		$p_manglik=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $p_mtongue=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $return_data1=partnermanglik($p_mtongue,$p_manglik);
                $manglik_data1=explode("+",$return_data1);
                $smarty->assign("Partner_Manglik_Status",$manglik_data1[0]);
                $smarty->assign("Partner_Manglik",$manglik_data1[1]);

		$temp=display_format($jpartnerObj->getPARTNER_BTYPE());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_BTYPE[]=$BODYTYPE[$temp[$ll]];
                unset($temp);

                $temp=display_format($jpartnerObj->getPARTNER_COMP());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_COMP[]=$COMPLEXION[$temp[$ll]];
                unset($temp);

                $temp=display_format($jpartnerObj->getPARTNER_DIET());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_DIET[]=$DIET[$temp[$ll]];
                unset($temp);
		
		 $temp=display_format($jpartnerObj->getPARTNER_DRINK());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_DRINK[]=$DRINK[$temp[$ll]];
                unset($temp);

                $temp=display_format($jpartnerObj->getPARTNER_MANGLIK());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_MANGLIK[]=$MANGLIK[$temp[$ll]];
                unset($temp);

                $temp=display_format($jpartnerObj->getPARTNER_MSTATUS());
                $FILTER_MSTATUS=$temp;
                for($ll=0;$ll<count($temp);$ll++)
                {
                        $PARTNER_MSTATUS[]=$MSTATUS[$temp[$ll]];
                }
                unset($temp);
		
		$temp=display_format($jpartnerObj->getPARTNER_RES_STATUS());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_RES_STATUS[]=$RSTATUS[$temp[$ll]];
                unset($temp);

                $temp=display_format($jpartnerObj->getPARTNER_SMOKE());
                for($ll=0;$ll<count($temp);$ll++)
                        $PARTNER_SMOKE[]=$SMOKE[$temp[$ll]];
                unset($temp);

		$PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
                $PARTNER_ELEVEL_NEW=display_format($jpartnerObj->getPARTNER_ELEVEL_NEW());
                $PARTNER_MTONGUE=display_format($jpartnerObj->getPARTNER_MTONGUE());
                $PARTNER_OCC=display_format($jpartnerObj->getPARTNER_OCC());
                $PARTNER_COUNTRYRES=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
                $PARTNER_INCOME=display_format($jpartnerObj->getPARTNER_INCOME());
		
		 if(is_array($PARTNER_BTYPE))
                       $smarty->assign("PARTNER_BTYPE",implode(", ",$PARTNER_BTYPE));
                else
                        $smarty->assign("PARTNER_BTYPE","   - ");

                if(is_array($PARTNER_COMP))
                        $smarty->assign("PARTNER_COMP",implode(", ",$PARTNER_COMP));
                else
                        $smarty->assign("PARTNER_COMP","   - ");

                if(is_array($PARTNER_DIET))
                        $smarty->assign("PARTNER_DIET",implode(", ",$PARTNER_DIET));
                else
                        $smarty->assign("PARTNER_DIET","   - ");

                if(is_array($PARTNER_DRINK))
                        $smarty->assign("PARTNER_DRINK",implode(", ",$PARTNER_DRINK));
                else
                        $smarty->assign("PARTNER_DRINK","   - ");

                if(is_array($PARTNER_MANGLIK))
                        $smarty->assign("PARTNER_MANGLIK",implode(", ",$PARTNER_MANGLIK));
                else
                        $smarty->assign("PARTNER_MANGLIK","   - ");

                if(is_array($PARTNER_MSTATUS))
                        $smarty->assign("PARTNER_MSTATUS",implode(", ",$PARTNER_MSTATUS));
                else
                        $smarty->assign("PARTNER_MSTATUS","   - ");

                if(is_array($PARTNER_RES_STATUS))
                        $smarty->assign("PARTNER_RES_STATUS",implode(", ",$PARTNER_RES_STATUS));
                else
                        $smarty->assign("PARTNER_RES_STATUS","   - ");
		 if(is_array($PARTNER_SMOKE))
                        $smarty->assign("PARTNER_SMOKE",implode(", ",$PARTNER_SMOKE));
                else
                       $smarty->assign("PARTNER_SMOKE","   - ");

                $smarty->assign("PARTNER_CASTE",get_partner_string_from_array($PARTNER_CASTE,"CASTE"));

                $smarty->assign("PARTNER_ELEVEL_NEW",get_partner_string_from_array($PARTNER_ELEVEL_NEW,"EDUCATION_LEVEL_NEW"));

                $smarty->assign("PARTNER_MTONGUE",get_partner_string_from_array($PARTNER_MTONGUE,"MTONGUE"));

                $smarty->assign("PARTNER_OCC",get_partner_string_from_array($PARTNER_OCC,"OCCUPATION"));

                $smarty->assign("PARTNER_COUNTRYRES",get_partner_string_from_array($PARTNER_COUNTRYRES,"COUNTRY"));

                $smarty->assign("PARTNER_INCOME",get_partner_string_from_array($PARTNER_INCOME,"INCOME"));

                if(is_array($PARTNER_CITYRES))
                {
                        $str=implode("','",$PARTNER_CITYRES);

                        if($lang)
                                $sql="select SQL_CACHE LABEL from HIN_CITY_INDIA where VALUE in ($str)";
                        else
                                $sql="select SQL_CACHE LABEL FROM CITY_NEW where VALUE in ($str)";
                        $dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);
			$sql="select SQL_CACHE LABEL FROM CITY_NEW where VALUE in ($str)";
                        $dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        $partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
                        $smarty->assign("PARTNER_CITYRES",$partner_city_str);
                }
        }
        else
        {
                $smarty->assign("NOPARTNER","1");
        }
        if($CHECK_FOR_PHOTO_CONTACT)
                        {
                                if($see_photo && $samegender!=1)
                                {
                                        $smarty->assign("FULLVIEW","1");
			             	$smarty->assign("PHOTOFILE",$profilePicUrl);		//Symfony Photo Modification 
                                }
                                else
                                {
                                        $smarty->assign("FULLVIEW","");
                                        $smarty->assign("ISALBUM","");
                                        if($myrow_gender=="M")
					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ph_visible_b.jpg");
                                        else
                                                $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ph_visible_g.jpg");
                                }
                        }
        $pass_a=0;
        $sql_a ="select REASON,SCREENED from newjs.`ANNULLED` where PROFILEID='$profileid'";
                $res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
                if($row_a=mysql_fetch_row($res_a))
                {
                        if($row_a[1]=='Y')
                                $smarty->assign("Annulled_Reason",nl2br($row_a[0]));
                        $pass_a=1;
                }
        if(!$pass_a)
        {
                $smarty->assign("Annulled_Reason",'No Reason Specified');
        }
	$smarty->assign("SEARCHED_PROFILEID",$profileid);
        $smarty->assign("MOB_VERIFIED",$show_mob);
        $smarty->assign("GENDER_mob",$mob_gender);
}
function get_partner_string_from_array($str,$tablename)
{
        global $db;
        if(is_array($str))
        	$str=implode("','",$str);
	if($str)
        {
                $sql="select SQL_CACHE distinct LABEL from newjs.".$tablename." where VALUE in ('$str')";
                $dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                while($droprow=mysql_fetch_array($dropresult))
                {
                        $str1.=$droprow["LABEL"] . ", ";
                }

                mysql_free_result($dropresult);

                return substr($str1,0,-2);
        }
        elseif($lang=="hin")
                return "मान्य नही";
        else
                return "   - ";
}
function mysql_query_optimizer($sql,$db="",$divert="")
{
        global $db_master, $db_slave, $db;
        if($db_master && $db_slave)
        {
                if(strtolower(substr(trim($sql),0,6)) == "select")
                {
                        @mysql_ping_js($db_slave);
                        return mysql_query_decide($sql,$db_slave,$divert);
                }
                else
                {
                        @mysql_ping_js($db_master);
                        return mysql_query_decide($sql,$db_master,$divert);
                }
        }

        //@mysql_ping_js($db);

        return mysql_query_decide($sql,$db,$divert);
}
                                     
function accept_profile($profileid,$accid,$oc_id)
{
	global $smarty,$db;
	$sql1= "SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
	$res1= mysql_query_decide($sql1) or die(mysql_error_js());
	$row= mysql_fetch_array($res1);
	$billid=$row["BILLID"];
	$sql="SELECT STATUS,CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID= '$profileid' AND MATCH_ID='$accid'";	
	$res= mysql_query_decide($sql) or die(mysql_error_js());
	$row=mysql_fetch_array($res);
	$stat= $row['STATUS'];
	$cat=$row["CATEGORY"];
	if($stat!='ACC')
	{
		$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS= 'ACC',MOD_DATE=now() WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
		mysql_query_decide($sql) or logError($sql);

		//Updating contact_status table.
		if($stat=='REJ')
		{
 			$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$accid'";
                        mysql_query_decide($sql_up) or logError($sql_up);
			
		}
		else
		{
 			$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$accid'";
                        mysql_query_decide($sql_up) or logError($sql_up);
		}

		if($cat==1 || $cat==2)
		{
			$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$accid',NOW(),'ACC')";
			mysql_query_decide($sql) or logError($sql);
		}		
		if(($cat == 4)||($cat == 5)||($cat == 6))
		{
			if($cat == 4)
			{
				$sql="SELECT OPERATOR from jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$accid' ";
				$res=mysql_query_decide($sql) or logError($sql);
				$row= mysql_fetch_array($res);
				$oname= $row['OPERATOR'];		
				$sql="SELECT EMAIL from jsadmin.PSWRDS WHERE USERNAME='$oname' ";
				$res=mysql_query_decide($sql) or logError($sql);
				$row= mysql_fetch_array($res);
				$email= $row['EMAIL'];	
				$sql="SELECT PHONE_MOB from newjs.JPROFILE WHERE PROFILEID='$accid' ";
				$res=mysql_query_decide($sql) or logError($sql);
				$row=mysql_fetch_assoc($res);
				$phone_mob=$row["PHONE_MOB"];	
			}
			else 
			{
				$sql="SELECT EMAIL,PHONE_MOB from newjs.JPROFILE WHERE PROFILEID='$accid' ";
				$res=mysql_query_decide($sql) or logError($sql);
				$row= mysql_fetch_array($res);
				$email= $row['EMAIL'];	
				$phone_mob=$row["PHONE_MOB"];
			}						
			$subject="Contact detail made available to offline customer";
			$from="offlinematches@jeevansathi.com";
			$Cc="";
			$Bcc="";		                
			$msg="One of our offline customer ".$oc_id." was interested in your profile. In accordance with your privacy settings, we have made available to the offline customer, your contact details. You can expect a call/contact from the offline customer soon. We wish you luck with this contact and your partner search.";		                	
			send_mail($email,$Cc,$Bcc,$msg,$subject,$from);		                
			$msg="We have shared your contact detail with ".$oc_id.". Please login to Jeevansathi to view the profile. This is in accordance with your privacy option.";
			//send_sms($msg,'',$phone_mob,$accid,'','Y');

		} 
		if($cat == 2)
		{
			$mysqlObj=new Mysql;
			$sql= "UPDATE newjs.CONTACTS SET TYPE= 'A' WHERE RECEIVER= '$profileid' AND TYPE='I' AND SENDER='$accid'";
			$myDbName1=getProfileDatabaseConnectionName($accid,'',$mysqlObj);
			$myDbName2=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
			if($myDbName1==$myDbName2)		
			{
				
				$myDb1=$mysqlObj->connect($myDbName1);
				$res= $mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));
			}
			else
			{
				$myDb1=$mysqlObj->connect($myDbName1);
				$myDb2=$mysqlObj->connect($myDbName2);
				$mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));
				$mysqlObj->executeQuery($sql,$myDb2) or die(mysql_error_js($myDb2));
			}
		}       			 

	}

}
?>
