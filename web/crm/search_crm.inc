<?php

include(JsConstants::$docRoot."/commonFiles/flag.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function pagelink($pagelen,$totalrec,$curpage,$linkno,$link,$scriptname="",$searchchecksum="",$newsearch="",$inf_checksum="",$castemapping="",$crmback="",$ordering="",$savesearch="",$label_select_no='',$from_index="",$viewall="",$hide_search_bar="",$save_search_redirect="")
{
        global $smarty,$IMG_URL,$PHOTO_URL;
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
}//end function pagelink


function displayresults($result,$curcount,$scriptname,$totalrec,$putactivate="",$nocalc="",$searchchecksum="",$moreurl="",$ordering="",$newsearch="",$optional="",$inf_checksum="",$castemapping="",$crmback="",$savesearch="",$label_select_no='',$from_index="",$save_search_redirect="")
        {
                global $PAGELEN,$smarty,$checksum,$data,$marriage_bureau_profile,$db,$crm_pid;

                $orig_scriptname = $scriptname;

                if($from_index)
                {
                        if ($data)
                        {
                                $smarty->assign("length",12);
                        }
                        else
                        {
                                $PAGELEN = 6;
                                $smarty->assign("length",6);
                        }
                        if ($newsearch!= 'isearch')
                                $smarty->assign("show_record_cnt","1");
                }
                else
                        $smarty->assign("length",12);


include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		 $lang=$_COOKIE['JS_LANG'];
                        $income_map=array(
"2" => "< Rs. 1Lac",
"3" => "Rs. 1 - 2Lac",
"4" => "Rs. 2 - 3Lac",
"5" => "Rs. 3 - 4Lac",
"6" => "Rs. 4 - 5Lac",
"8" => "< $ 25K",
"9" => "$ 25 - 40K",
"10" => "$ 40 - 60K",
"11" => "$ 60 - 80K",
"12" => "$ 80K - 1lac",
"13" => "$ 1 - 1.5lac",
"21" => "$ 1.5 - 2lac",
"14" => "> $ 2lac",
"15" => "No Income",
"16" => "Rs. 5 - 7.5lac",
"17" => "Rs. 7.5 - 10lac",
"18" => "Rs. 10 - 15lac",
"20" => "Rs. 15 - 20lac",
"22" => "Rs. 20 - 25lac",
"23" => "Rs. 25 - 35lac",
"24" => "Rs. 35 - 50lac",
"25" => "Rs. 50 - 70lac",
"26" => "Rs. 70 - 1cr",
"27" => "> Rs. 1cr");

                $FIELDS="PROFILEID,USERNAME,AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,MOD_DT,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,HAVEPHOTO,YOURINFO,SCREENING,INCOME,PHOTO_DISPLAY,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,SPOUSE,SHOW_HOROSCOPE,GENDER,COUNTRY_BIRTH,CITY_BIRTH,BTIME,LAST_LOGIN_DT,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,RELIGION,SHOWPHONE_MOB,SHOWPHONE_RES,MOB_STATUS,LANDL_STATUS,PHONE_FLAG";

/********************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       15 November, 2005
                        Reason          :       For optimizing search queries by fetching result set for 120 records
                                        :       and subsequently using mysql_data_seek()
********************************************************************************************************************/
		 //added by lavesh to log user going beyond 50 pages
                if($curcount>=600)
                {
                        if($searchchecksum)
                        {
                                /*$print_r = "SCRIPTNAME:".$scriptname;
                                $print_r .= "POST:".print_r($_POST,true);
                                $print_r .= "Get:".print_r($_GET,true);
                                mail("gaurav.arora@jeevansathi.com,lavesh.rawat@jeevansathi.com","Inputprofile TieupB - forceful login part",$print_r);
                                 */

                                $PAGENO=$curcount/12;
                                $ip=FetchClientIP();
                                if(strstr($ip, ","))
                                {
                                        $ip_new = explode(",",$ip);
                                        $ip = $ip_new[1];
                                }
                                if( strstr($searchchecksum,'i') )//advance search
                                {
                                        $arr=explode("i",$searchchecksum);
                                        $temp_sid=$arr[1];
                                }
                                else //top search
                                $temp_sid=$searchchecksum;
                                $sql1="REPLACE INTO newjs.SEARCH_50 VALUES('','$temp_sid','$ip',now(),$PAGENO+1)";
                                //mysql_query_decide($sql1);
                        }
                }
                //Ends Here.

		 if($optional)
                {
                        $i=0;
                        @mysql_data_seek($result,$curcount%120);
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
                                $i++;

                                if($i>=$optional)
                                        break;
                        }
                }
		 else
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
                }
		if($resultprofiles)
                {
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

		if($resultprofiles)
		{
			$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ProfilePicUrl,ThumbailUrl");	//Symfony Photo Modification
		}

		$monthnum=array("Jan"=>"01",
                                                "Feb"=>"02",
                                                "Mar"=>"03",
                                                "Apr"=>"04",
                                                "May"=>"05",
                                                "Jun"=>"06",
                                                "Jul"=>"07",
                                                "Aug"=>"08",
                                                "Sep"=>"09",
                                                "Oct"=>"10",
                                                "Nov"=>"11",
                                                "Dec"=>"12");

		while($myrow=mysql_fetch_array($result1))
                {

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

			if(is_array($bookmarks))
                        {
                                $pid=$myrow["PROFILEID"];
                                if(array_key_exists($pid,$bookmarks))
                                {
                                        $bookmarked=1;
                                        if($bookmarks[$pid]!='')
                                        {
                                                $bknote=$bookmarks[$pid];
                                                $bknotedis='';
                                                if(strlen($bknote)>30)
                                                {
                                                        $a=$bknote;
                                                        while(strlen($a)>30)
                                                        {
                                                                $display=substr($a,0,30);
                                                                if($display[29]=='')
                                                                {
                                                                        $a=substr($a,30,strlen($a)-30);
                                                                }
                                                                else
                                                                {
                                                                        for($i=strlen($display)-1;$i>=0;$i--)
                                                                        {
                                                                                if($display[$i]=="")
                                                                                break;
                                                                        }
									if($i>=0)
                                                                        {
                                                                                $leftover=substr($display,$i,strlen($display)-$i);
                                                                                $display=substr($display,0,$i+1);

                                                                                $a=substr($a,30,strlen($a)-30);
                                                                                $a=$leftover.$a;

                                                                        }
                                                                        else
                                                                        $a=substr($a,30,strlen($a)-30);
                                                                }
                                                                $bknotedis.=$display."<br>";
                                                        }
                                                        $bknotedis.=$a;
                                                }
                                                else
                                                $bknotedis='';

                                        }
                                        else
                                        {
                                                $bookmarked=1;
                                                $bknote='';
                                                $bknotedis='';
                                        }

                                }
                                else
                                {
                                        $bookmarked=0;
                                        $bknote='';
                                        $bknotedis='';
                                }
				}
                        else
                        {
                                $bookmarked=0;
                                $bknote='';
                                $bknotedis='';
                        }
                        //added by lavesh
                        if(is_array($ignores) && in_array($myrow["PROFILEID"],$ignores))
                                $ignore=1;
                        else
                                $ignore=0;

                        if(is_array($photo_reqs) && in_array($myrow["PROFILEID"],$photo_reqs))
                                $photo_req=1;
                        else
                                $photo_req=0;
                        //ends here.

                        if(is_array($onlinemembers) && in_array($myrow["PROFILEID"],$onlinemembers))
                                $online=1;
                        else
                                $online=0;

			 if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
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
                        }
                        if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
                        {
                                //if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
                                        //$havephoto="P";
                                if($myrow["PHOTO_DISPLAY"]=="H")
                                        $havephoto="H";
                                elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
                                        $havephoto="L";
                                elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
                                        $havephoto="L";
                                elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
					 {
                                        if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
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
                                }
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

			 //added by sriram.
                        if($myrow['SHOW_HOROSCOPE']=="Y" && is_array($astro_array) && in_array($myrow["PROFILEID"],$astro_array))
                        {
                                $horo_link="horoscope_astro.php";
                                $horoscope="Y";
                        }
                        else
                                $horoscope="N";

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
				$profilePicUrl = $profilePicUrlArr["ProfilePicUrl"];
				$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
			}
			else
			{
				$profilePicUrl = null;
				$thumbnailUrl = null;
			}
			//Symfony Photo Modification
			
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
                        if(substr($myrow["SOURCE"],0,2)=='mb')
                                $mb='Y';
                        else
                                $mb='';

                        /* Added By lavesh for manageing icon of chat request on 11 may 2006*/
                        if(($myrow["PHONE_MOB"]=='')||($myrow["GET_SMS"]=='N')||($myrow["COUNTRY_RES"]!='51'))
                                $chat='';
                        else
                                $chat='Y';
                        //Addition Ends Here


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
                                $info.="City: <b>$residence</b>";

                        //2 change for photo
                        $profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];
                        if($havephoto=='L')
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
                        }
                        elseif($havephoto=='H')
                        {
                                if($gender=='M')
                                        $image_file="photo_hidden_sm_b.gif";
                                else
                                        $image_file="photo_hidden_sm_g.gif";
                        }
                        elseif($havephoto=='P')
                        {
                                if($gender=='M')
                                        $image_file="photo_fil_sm_b.gif";
                                else
                                        $image_file="photo_fil_sm_g.gif";
                        }
                        elseif($havephoto=='U')
                        {
                                if($gender=='M')
                                        $image_file="ph_cmgsoon_sm_b.gif";
                                else
                                        $image_file="ph_cmgsoon_sm_g.gif";
                        }
                        elseif($havephoto=='N')
                        {
                                if($gender=='M')
                                        $image_file="Request-a-photo-male_small.gif";
                                else
                                        $image_file="Request-a-photo-Female_small.gif";
                        }
			if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
                        {
                                        $my_photo="<a href=\"/profile/matrimonial-$stat_uname.htm\" onclick=\"MM_openProfileWindow('/profile/matrimonial-$stat_uname.htm','','');return false;\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
                        }
                        elseif($havephoto=='Y')
			{
				//Symfony Photo Modification
                                        $my_photo="<a href=\"/profile/matrimonial-$stat_uname.htm\" onclick=\"MM_openProfileWindow('/profile/matrimonial-$stat_uname.htm','','');return false;\" onMouseover=\"showtrail('$photochecksum','$username','','','$profilePicUrl')\" onMouseout=\"hidetrail()\"><div style=\" float:left; width:60px; height:60px; margin:0 3px 3px 0; background-image:url($thumbnailUrl)\"><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" ></div></a>";
				//Symfony Photo Modification
			}
                        else
                        {
                                if($crmback)
                                        $my_photo="<a href=\"/profile/viewprofile.php?profilechecksum=$profilechecksum&crmback=admin&cid=$cid&inf_checksum=$inf_checksum\" target=\"_blank\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\"></a>";
                                else
                                        $my_photo="<a href=\"$SITE_URL/profile/photorequest.php?checksum=$checksum&profilechecksum=$profilechecksum\"><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"60\" height=\"60\" hspace=\"5\" vspace=\"0\" border=\"0\" align=\"left\" onclick=\"MM_openBrWindow('$SITE_URL/profile/photorequest.php?checksum=$checksum&profilechecksum=$profilechecksum','','width=370,height=330'); return false;\" ></a>";
                        }	

			// IVR - Phone Verification check
			$chk_phoneStatus =getPhoneStatus($profile_params);
                        if($chk_phoneStatus =='Y')
                                $mob_verified='Y';
                        else
                                $mob_verified='N';
                        if(($myrow['SHOWPHONE_MOB']=="Y" || $myrow['SHOWPHONE_RES']=="Y") && $mob_verified=="Y")
                                $show_mob="Y";
                        else
                                $show_mob="N";


			//If user is logged in, then check whether the user we are searching is viewed by logged in user.
                        $viewed_by_user='no';

			if($scriptname=="/crm/show_matchalert.php")
                        {
                                $sqlcrm="SELECT DATE FROM matchalerts.LOG WHERE USER='$myrow[0]' AND RECEIVER='$crm_pid'";
                                $rescrm=mysql_query_decide($sqlcrm) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlcrm,"ShowErrTemplate");
				if(mysql_num_rows($rescrm)==0)
				{
					//$sqlcrm="SELECT DATE FROM dailyalerts.LOG WHERE USER='$myrow[0]' AND RECEIVER='$crm_pid'";
					//$rescrm=mysql_query_decide($sqlcrm) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlcrm,"ShowErrTemplate");
				}
                                $rowcrm=mysql_fetch_assoc($rescrm);
                                $date=$rowcrm["DATE"];
                                $year=2005;
                                if($date>365)
                                {
                                        while($date>365)
                                        {
                                                $year++;
                                                if(($year%4==0)&&($year%100!=0)||($year%400)==0)
                                                $date-=366;
                                                else
                                                $date-=365;
                                        }

                                }
                                if(($year%4==0)&&($year%100!=0)||($year%400==0))
                                $leap=1;
                                else
                                $leap=0;
                                if($leap)
                                $montharr=array("Jan"=>31,
                                        "Feb"=>28,
                                        "Mar"=>31,
                                        "Apr"=>30,
                                        "May"=>31,
                                        "Jun"=>30,
                                        "Jul"=>31,
                                        "Aug"=>31,
                                        "Sep"=>30,
                                        "Oct"=>31,
                                        "Nov"=>30,
                                        "Dec"=>31);
                                else
                                $montharr=array("Jan"=>31,
                                        "Feb"=>28,
                                        "Mar"=>31,
                                        "Apr"=>30,
                                        "May"=>31,
                                        "Jun"=>30,
                                        "Jul"=>31,
                                        "Aug"=>31,
                                        "Sep"=>30,
                                        "Oct"=>31,
                                        "Nov"=>30,
                                        "Dec"=>31);
				foreach($montharr as $key=>$value)
                                {
                                        if($date<=$value)
                                        {
                                                $month=$key;
                                                break;
                                        }
                                        $date=$date-$value;

                                }
                                $day=$date;
                                $date=$day."-".$month."-".$year;
				$month=$monthnum[$month];
				if($month && $day && $year)
				{
					$datestamp=mktime(0, 0, 0,$month,$day+1,$year);
					$date=date("d-M-Y",$datestamp);
				}
                        }


                        //Addition Ends Here.

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
						"BOOKMARKED" => $bookmarked,
                                                "BKNOTE"=>$bknote,
                                                "BKNOTEDIS"=>$bknotedis,
                                                "BOLDLISTING" => $bold_listing,
                                                //done for new service added called eclassified NEW CHANGES
                                                "CONTACT_DETAILS" => $contact_details,
                                                "HOROSCOPE" =>$horoscope,
                                                "HORO_LINK" =>$horo_link,
                                                "ONLINE" => $online,
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
                                                "CHAT_REQUEST"=>$chat,
                                                "MY_PHOTO" =>$my_photo,
                                                "IGNORE"=>$ignore,
                                                "THREADNAME"=>$data["USERNAME"]."_".$myrow["USERNAME"],
                                                "MARRIAGE_BUREAU"=>$mb,
                                                "VIEWED_BY_USER"=>$viewed_by_user,
                                                "SHOW_MOB"=>$show_mob,
                                                "MATCHALERT_DATE"=>$date);
                        $sno++;
                }
                mysql_free_result($result1);
                for($z=0;$z<count($RESULT_ARRAY_3d);$z++)
                {
                        $key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$RESULT_ARRAY_3d);
                        $RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
                }
		/*********************************************************************************************************************
Changed by      : Shakti Srivastava
Change Date     : 26 July 2005
Reason          : Number of tables that has to be shown is needed while displaying the data in the template
*********************************************************************************************************************/
                $temp=count($RESULT_ARRAY);
                //print_r($RESULT_ARRAY);
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
                if($scriptname=='single_contact_aj.php')
                        $PAGELEN = 6;
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

                if($js_search_recomend)
                {
                        $a=array('jsa1','jsa2','jsa3','jsa4','jsa5','jsa6');
                        $b=array('jsb1','jsb2','jsb3','jsb4','jsb5','jsb6');
                        $c=array('jsc1','jsc2','jsc3','jsc4','jsc5','jsc6');
                        $e=array('e1','e2','e3','e4','e5','e6','e7','e8','e9','e10','e11','e12');
                        $f=array('f1','f2','f3','f4','f5','f6','f7','f8','f9','f10','f11','f12');
                        $n=array('n1','n2','n3','n4','n5','n6','n7','n8','n9','n10','n11','n12');
                        $boxheight=array('jsone','jstwo','jsthree','jsfour','jsfive','jssix');
                        $flag_for_js_recomend=1;
                }
                else
                {
                        $a=array('a1','a2','a3','a4','a5','a6','a7','a8','a9','a10','a11','a12');
                        $b=array('b1','b2','b3','b4','b5','b6','b7','b8','b9','b10','b11','b12');
                        $c=array('c1','c2','c3','c4','c5','c6','c7','c8','c9','c10','c11','c12');
                        $d=array('d1','d2','d3','d4','d5','d6','d7','d8','d9','d10','d11','d12');
                        $e=array('e1','e2','e3','e4','e5','e6','e7','e8','e9','e10','e11','e12');
                        $f=array('f1','f2','f3','f4','f5','f6','f7','f8','f9','f10','f11','f12');
                        $n=array('n1','n2','n3','n4','n5','n6','n7','n8','n9','n10','n11','n12');
                        $boxheight=array('one','two','three','four','five','six','seven','eight','nine','ten','eleven','tewelve');
                }
		if(!$flag_for_js_recomend)
                {
                        $box1="a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12";
                        $box2="b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12";
                        $box3="c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12";
                        $box4="one,two,three,four,five,six,seven,eight,nine,ten,eleven,tewelve";
                }
                else
                {
                        $box1="jsa1,jsa2,jsa3,jsa4,jsa5,jsa6";
                        $box2="jsb1,jsb2,jsb3,jsb4,jsb5,jsb6";
                        $box3="jsc1,jsc2,jsc3,jsc4,jsc5,jsc6";
                        $box4="jsone,jstwo,jsthree,jsfour,jsfive,jssix";
                }

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


?>
