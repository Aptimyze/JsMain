<?php 
/*
  
  $pagelen = No of jobs shown in page // default 50
  $totalrec = No of record found for search
  $curpage = which page is shown currently
  $linkno = how many link is shown on page
  $link = link goes for numbers
*/

function pagelink($pagelen,$totalrec,$curpage,$linkno,$link,$scriptname="",$searchchecksum="",$send="",$folderid="",$showmsg="",$id="",$sortorder="")
{
	global $smarty;

	// set the links to be shown on each page to be five
	$linkno=10;

	$totalpage = $totalrec / $pagelen;
	$totalpage = ceil($totalpage);
	$curpage = round($curpage);
	$mid_linkno=abs(floor($linkno/2));
	$startwith=$curpage - $mid_linkno -1;
	if($startwith <= 0)
		$startwith=0;
	if(($startwith+$linkno) >= $totalpage)
		$startwith=$totalpage-$linkno;
	if($startwith <= 0)
		$startwith=0;

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
	$linkvar = "Page : <strong>";
	if($startwith && !($startwith % $linkno))
	 	//$linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."\">($prevwith - $startwith)</a></span>&nbsp;";
	 	$linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">&lt;&lt;</a></span>&nbsp;";
	 	//$linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">&lt;&lt;</a></span>&nbsp;";
		
	 
	for($i=1;$i<=$totallinkshow;$i++)
	{
		$nos = $startwith+$i;
		//if($nos != $curpage)
		// $linkvar .= ($nos-1)*$pagelen;
		//else
		if($nos != $curpage)
		 	$linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">";
		else
		 	$linkvar .= "&nbsp;";

/*		if($i == $totallinkshow && $lastlink)
		 $linkvar .= "($nos - $lastlink)>></a></span>&nbsp;";
		else*/
		 	$linkvar .= "$nos</a></span>&nbsp;";
	}
	
	if($lastlink)
	{
		$nos = $startwith+$i;
		$linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">";
		//$linkvar .= "($nos - $lastlink)</strong></a></span>&nbsp;";
		$linkvar .= "&gt;&gt;</strong></a></span>&nbsp;";
	}
	
	$linkvar.= "&lt;<strong><font color=\"#999999\">";
	
	if($curpage > 1)
		$linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">Previous</a></span>";
	else 
		$linkvar.="Previous";
		
	$linkvar.="</font></strong> <strong>|";

	$linkvar.="<font color=\"#999999\">";	
	if($curpage < $totalpage)
		$linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=". $curpage*$pagelen ."&send=$send&folderid=$folderid&showmsg=$showmsg&id=$id&sortorder=$sortorder\">Next</a></span>";
	else 
		$linkvar.="Next";
	$linkvar.="</font>";

	$linkvar.="&gt;</strong>";
	if($totalpage==0)
		$linkvar="";
	//echo "<br>linkvar : ".$linkvar;	
	$smarty->assign("PAGELINK",$linkvar);
	return $linkvar;
}//end function pagelink

if (!function_exists(check_privacy_filtered))
{
	function check_privacy_filtered($myprofileid,$hisprofileid)
	{
			$sql="select * from FILTERS where PROFILEID='$hisprofileid'";
			$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
																     
			if(mysql_num_rows($resultfilter)>0)
			{
				$filterrow=mysql_fetch_array($resultfilter);
				if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y")
				{
					$sqlfilter="select count(*) from JPROFILE where  activatedKey=1 and PROFILEID='$myprofileid'";                                                                                                                              
					$sql="select * from JPARTNER where PROFILEID='$hisprofileid'";
					$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					//Get the filter values
					if(mysql_num_rows($result) > 0)
					{
						$myrow=mysql_fetch_array($result);
						$partnerid=$myrow["PARTNERID"];
						//first filter : age
						if($filterrow["AGE"]=="Y")
						{
							if($myrow["LAGE"]!="" && $myrow["HAGE"]!="")
							{
								$sqlfilter.= " and AGE between '" . $myrow[LAGE] . "' and '" . $myrow[HAGE] ."'";                                                                                                                              
							}
						}
						//second filter : religion
						if($filterrow["RELIGION"]=="Y")
						{
							$sql="select * from PARTNER_CASTE where PARTNERID='$partnerid'";
							$resultpartner=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                                                                                                                              
							while($mypartner=mysql_fetch_row($resultpartner))
							{
								$PARTNER_CASTE[]=$mypartner[1];
							}
																     
							if(is_array($PARTNER_CASTE))
							{
								$sqlfilter.=" and CASTE in ('" . implode("','",get_all_caste($PARTNER_CASTE)) . "')";
							}
						}
						//third filter : country of residence
						if($filterrow["COUNTRY_RES"]=="Y")
						{
							$sql="select * from PARTNER_COUNTRYRES where PARTNERID='$partnerid'";
							$resultpartner=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                                                                                                                              
							while($mypartner=mysql_fetch_row($resultpartner))
							{
								$PARTNER_COUNTRYRES[]=$mypartner[1];
							}
							if(is_array($PARTNER_COUNTRYRES))
							{
								$sqlfilter.=" and COUNTRY_RES in ('" . implode("','",$PARTNER_COUNTRYRES) . "')";
							}
						}
						//fourth filter : marital status
						if($filterrow["MSTATUS"]=="Y")
						{
							$sql="select * from PARTNER_MSTATUS where PARTNERID='$partnerid'";
							$resultpartner=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                                                                                                                              
							while($mypartner=mysql_fetch_row($resultpartner))
							{
								$FILTER_MSTATUS[]=$mypartner[1];
							}
							if(is_array($FILTER_MSTATUS))
							{
								$sqlfilter.=" and MSTATUS in ('" . implode("','",$FILTER_MSTATUS) . "')";
							}
						}
						//fifth filter : community
						if($filterrow["MTONGUE"]=="Y")
						{
							$sql="select * from PARTNER_MTONGUE where PARTNERID='$partnerid'";
							$resultpartner=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                                                                                                                              
							while($mypartner=mysql_fetch_row($resultpartner))
							{
								$PARTNER_MTONGUE[]=$mypartner[1];
							}
							if(is_array($PARTNER_MTONGUE))
							{
								$sqlfilter.=" and MTONGUE in ('" . implode("','",$PARTNER_MTONGUE) . "')";
							}
						}
						$resfil=mysql_query_decide($sqlfilter) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlfilter,"ShowErrTemplate");
																     
						$finalfilterrow=mysql_fetch_row($resfil);
						mysql_free_result($resfil);
						if($finalfilterrow[0] <= 0)
						{
						return true;
						}
					}
				}
			}
		return false;
	}
}

function get_photo_details($profileid)
{
	global $data;

	//Sharding On Contacts done by Lavesh Rawat
	$contactResult=getResultSet("TYPE",$data[PROFILEID],"",$profileid);
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$contacted1[$profileid]=$contactResult[$key]["TYPE"];
			$contacted2[$profileid]="R";
		}
	}
	unset($contactResult);

	/*
	$imagesql="select TYPE from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and RECEIVER='$profileid'";
        $imageresult=mysql_query_decide($imagesql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$imagesql,"ShowErrTemplate");
                                                                                                                             
	if(mysql_num_rows($imageresult)>0)
	{
		while($imagerow=mysql_fetch_array($imageresult))
		{
			$contacted1[$profileid]=$imagerow["TYPE"];
			$contacted2[$profileid]="R";
		}
	}
	mysql_free_result($imageresult);
	*/                                                                                                                          

	$contactResult=getResultSet("TYPE",$profileid,"",$data[PROFILEID]);
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$contacted1[$profileid]=$contactResult[$key]["TYPE"];
			$contacted2[$profileid]="S";
		}
	}
	unset($contactResult);
	//Sharding On Contacts done by Lavesh Rawat

	/*													     
	$imagesql="select TYPE from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid'";
	$imageresult=mysql_query_decide($imagesql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$imagesql,"ShowErrTemplate");
													     
	if(mysql_num_rows($imageresult)>0)
	{
		while($imagerow=mysql_fetch_array($imageresult))
		{
			$contacted1[$profileid]=$imagerow["TYPE"];
			$contacted2[$profileid]="S";
		}
	}
	mysql_free_result($imageresult);
	*/
	
	$sql="select HAVEPHOTO,PRIVACY,PHOTO_DISPLAY from JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	
	if($myrow["HAVEPHOTO"]=="Y")
		$havephoto="Y";
	else
		$havephoto="N";
  
	if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
	{
		if(!$data)
		{
			$havephoto="P";
		}
		elseif($data && $myrow["PRIVACY"]=="F")
		{
			if(check_privacy_filtered($data["PROFILEID"],$profileid))
				$havephoto="P";
		}
	}
													     
	if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
	{
		if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
			$havephoto="P";
		elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
		{
			if(is_array($contacted1) && array_key_exists($profileid,$contacted1) && (($contacted2[$profileid]=="S" && ($contacted1[$profileid]=="I" || $contacted1[$profileid]=="A")) || ($contacted2[$profileid]=="R" && $contacted1[$profileid]=="A")))
				;
			else
			{
				$havephoto="P";
			}
		}
		elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
		{
			if(is_array($contacted1) && array_key_exists($profileid,$contacted1) && (($contacted2[$profileid]=="S" && ($contacted1[$profileid]=="I" || $contacted1[$profileid]=="A")) || ($contacted2[$profileid]=="R" && $contacted1[$profileid]=="A")))
				;
			elseif(check_privacy_filtered($data["PROFILEID"],$profileid))
				$havephoto="P";
		}
	}
	return $havephoto;
}

function get_mssg_cnt($profileid,$folderid,$receiver_status)
{
        global $myDb,$mysql;

        /*if($receiver_status==1)
        {
                $sql="SELECT COUNT(*) as cnt FROM MESSAGE_LOG WHERE RECEIVER='$profileid' AND FOLDERID='$folderid' AND OBSCENE='N'";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
                mysql_free_result($res);
                $tot_cnt=$row['cnt'];
                $count=$row['cnt'];
        }*/
        if($receiver_status==1)
        {
                $sql="SELECT count(*) as cnt FROM MESSAGE_LOG WHERE RECEIVER='$profileid' AND FOLDERID='$folderid' AND OBSCENE='N' AND RECEIVER_STATUS!='D'";
                $result=$mysql->ExecuteQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row=$mysql->fetchArray($result);

                $count=$row["cnt"];
        }
        else
        {
                $sql="SELECT count(*) as cnt FROM MESSAGE_LOG WHERE SENDER='$profileid' AND SENDER_STATUS!='D'";
                $result=$mysql->ExecuteQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row=$mysql->fetchArray($result);

                $count=$row["cnt"];
        }

        return $count;

}


?>
